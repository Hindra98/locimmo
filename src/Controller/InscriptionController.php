<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\UtilisateursType;
use App\Service\SendMail;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route ('/inscription')]
class InscriptionController extends AbstractController
{
    #[Route('/', name: 'app_inscription')]
    public function addUser(ManagerRegistry $doctrine, Request $request): Response
    {
        $user = new Utilisateurs();
        $form = $this->createForm(UtilisateursType::class, $user);
        $form->remove('date_creation');
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $manager = $doctrine->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', $user->getNom() . "a été ajouté avec succès");

            return $this->redirectToRoute("/connexion");
        } else

            return $this->render('inscription/indexe.html.twig', [
                'form' => $form->createView()
            ]);
    }

    #[Route('/add', name: 'inscription.add')]
    public function index(SendMail $sendMail): Response
    {

        $to = 'franckimmo@noreply.com';
        $subject = 'Franck Immo - Notifications!';
        $contenu = 'Ce bien m\'interesse, il est encore en stock !?';
        $sendMail->sendMailNotification($to, $subject, $contenu);

        $this->addFlash('success', 'Message envoyé à ' . $to);
        return $this->redirectToRoute('index');
    }
}
