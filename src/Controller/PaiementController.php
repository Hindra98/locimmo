<?php

namespace App\Controller;

use App\Entity\Abonnements;
use App\Entity\Proprietaires;
use App\Service\Helpers;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaiementController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private $user;
    public function __construct(ManagerRegistry $doctrine, Helpers $helpers) {
        $this->user = $helpers->getUser();
        $this->doctrine = $doctrine;
    }
    #[Route('/paiement', name: 'app_paiemen')]
    public function index(Request $request): Response
    {
        $phone = $request->get('phone');
        $subscription = [
            'title' => 'Abonnement Franck Immo',
            'subtitle' => 'Devenez proprietaire sur la plateforme et vendez/louer vos biens comme bon vous semble',
            'description' => 'Devenez proprietaire sur la plateforme et vendez/louer vos biens comme bon vous semble',
            'amount' => '20.00'
        ];

        $pay = $this->showPayPalPayement_Ui($subscription, $phone);
        $res = '<!DOCTYPE html><html><head>
		<meta charset="UTF-8" />
		<title> Abonnement - FI </title>
		<link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css" />
		</head>';
        $res .= "<div class='container'>
        <div class='card mx-auto my-5'>
            <div class='card-header'>
                <h1 class='mb-3 fw-bold text-center'>". $subscription['title']."</h1>
            </div>
            <div class='card-body'>
                <div class='box-propietaire'>".$subscription['subtitle']."</div>
                <div class='box-amount'>".$subscription['amount']."&euro;/month </div>
                <div class='text-body-secondary mx-auto mt-4'>$pay </div>
            </div>
        </div></div>";
        $res .= '</body></html>';
        return new Response("$res");
    }

    public function showPayPalPayement_Ui($payement, $phone): string
    {
        // A mettre dans une constante PAYPAL_ID, dans les variables d'environnement pour la production
        // $clienId = PAYPAL_ID;
        $clientId = 'AYcdG-MdjGJCh2koQgS_1x7CYexewyOq0jR4DVJMt219seoeLapjqcZFcxeZKc8hXaYuMpRVEZtdkXRv';
        $order = json_encode([
            'purchase_units' => [
                [
                    'title' => $payement['title'],
                    'description' => $payement['description'],
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => number_format($payement['amount'], 2, '.', ""),
                    ],
                ],
            ],
        ]);
        return <<<HTML
        <script src="https://www.paypal.com/sdk/js?client-id={$clientId}&currency=EUR&intent=authorize"></script>
        <div id="paypal-button-container"></div>
        <script>
          paypal.Buttons({
            createOrder: (data, actions) => {
              return actions.order.create({$order})
            },
            onApprove: async (data, actions) => {
              const authorization = await actions.order.authorize()
              const authorizationId = authorization.purchase_units[0].payments.authorizations[0].id
              await fetch('/paypal.php', {
                method: 'post',
                headers: {
                  'content-type': 'application/json'
                },
                body: JSON.stringify({authorizationId})
              })

              alert('Votre paiement a bien été enregistré')

              // Action a effectuer quand le payement reussit
              // Enregister l'utilisateur dans la bd
              // code de redirection redirection
              window.location.href = "/paiement/success/{$phone}";
            },
            onCancel: (data, actions) => {
              // Action a effectuer si l'utilisateur annule le payement
              window.location.href = "/paiement/cancel";
            },
            onError: (err) => {
              // Action a effectuer si une erreur survient lors du payement
              window.location.href = "/paiement/error/";
            },
          }).render('#paypal-button-container');
        </script>
    HTML;
    }

    #[Route('/paiement/success/{phone}', name: 'app_paiement_success')]
    public function success($phone): Response
    {
        $user = $this->user;
        $user->setRoles(['ROLE_PROPRIO']);
        $proprio = new Proprietaires();
        $proprio->setTel($phone)->setUtilisateur($user);
        $abonnement = new Abonnements();
        $da = new \DateTime();
        $timestamp = $da->getTimestamp() + 3600*24*30;
        $da->setTimestamp($timestamp);
        $abonnement->setProprietaires($proprio)->setCreateAt()
            ->setExpireAt(new \DateTimeImmutable($da->format("Y-m-d H:i:s")));
        $manager = $this->doctrine->getManager();
        $manager->persist($user);
        $manager->persist($proprio);
        $manager->persist($abonnement);
        $manager->flush();
        $this->addFlash('success', 'Congratulations, vous êtes maintenant un propriétaire');
        return $this->redirectToRoute('index');
    }
    #[Route('/paiement/cancel', name: 'app_paiement_cancel')]
    public function cancel(Helpers $helpers): Response
    {
        $this->addFlash('warning', 'Vous avez annulé le paiement');
        return $this->redirectToRoute('index');
    }
    #[Route('/paiement/error', name: 'app_paiement_error')]
    public function error(Helpers $helpers): Response
    {
        $this->addFlash('error', 'Une erreur s\'est produite lors du paiement');
        return $this->redirectToRoute('index');
    }

}
