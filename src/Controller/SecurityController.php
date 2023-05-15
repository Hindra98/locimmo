<?php

namespace App\Controller;

use App\Entity\Biens;
use App\Entity\Commentaires;
use App\Entity\Favoris;
use App\Entity\Messages;
use App\Entity\Proprietaires;
use App\Form\MessageType;
use App\Repository\FavorisRepository;
use App\Repository\MessagesRepository;
use App\Service\Helpers;
use App\Service\SendMail;
use PhpParser\Node\Expr\New_;
use SevenGps\PayUnit;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private ManagerRegistry $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
    $this->doctrine = $doctrine;
}
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash("warning"," Deconnectez vous avant d'essayer de vous reconnecter'");
            return $this->redirectToRoute('index');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    #[Route(path: '/verif', name: 'app_verif')]
    public function verif(Helpers $helpers, Request $request): Response
    {
        $session = $request->getSession();
        $user = $helpers->getUser();
        $repoMess = $this->doctrine->getRepository(Messages::class);
        $repoCom = $this->doctrine->getRepository(Commentaires::class);
        $mess = $repoMess->findMessUnread($user->getId());
        $com = $repoCom->findComUnread();
        $nbreCom = count($com);
        $nbreMess = count($mess);
        $session->set('tMess', $nbreMess);

        $manager = $this->doctrine->getManager();
        $user->setUpdateAt();
        $manager->persist($user);
        $manager->flush();
        if (in_array('ROLE_ADMIN', $user->getRoles()))
            $session->set('tCom', $nbreCom);
        $targetPath = $session->get('target');
        if ($targetPath != "") {
            return new RedirectResponse($targetPath);
        }
        return $this->redirectToRoute('index');
    }
    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, FileUploader $fileUploader, UserPasswordHasherInterface $hasher): Response
    {
        if ($this->getUser()) {
            $this->addFlash("warning"," Deconnectez vous avant de pouvoir créer un compte");
            return $this->redirectToRoute('index');
        }

        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->remove('create_at');
        $form->remove('update_at');
        $form->remove('roles');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {// Upload de photo

            $repo = New UtilisateurRepository($this->doctrine);
            $users = $repo->verifUser($form->get('email')->getData());
            if ($users) {
                $this->addFlash("error","Cette adresse email est déjà utilisée");
                return $this->redirectToRoute('app_register');
            }
            $photoFile = $form->get('profil')->getData();
            if ($photoFile) {
                $directory = $this->getParameter('repertoire_profil');
                $nouveauNom = $fileUploader->upload($photoFile, $directory);
                $user->setProfil($nouveauNom);
            }
            $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));
            $user->setCreateAt();
            $user->setUpdateAt();
            // dd($user);
            $manager = $this->doctrine->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash("success",$user->getEmail()." a été ajouté avec succès");
            return $this->redirectToRoute("app_login");
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[
        Route('/profil/compte', name: 'app_edit'),
        IsGranted('ROLE_USER')
    ]
    public function edit(Request $request, FileUploader $fileUploader, Helpers $helpers, UserPasswordHasherInterface $hasher): Response
    {
        $user = $helpers->getUser();
        if (!$request->query->get('edit'))
            return $this->render('security/edit.html.twig', [
                'user' => $user,
                'edit' => 0
            ]);
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->remove('create_at');
        $form->remove('update_at');
        $form->remove('roles');
        $form->handleRequest($request);
        $manager = $this->doctrine->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            // Upload de photo
            $photoFile = $form->get('profil')->getData();
            if ($photoFile) {
                $directory = $this->getParameter('repertoire_profil');
                $nouveauNom = $fileUploader->upload($photoFile, $directory);
                $user->setProfil($nouveauNom);
            }
            if ($form->get('password')->getData() == "")
                $user->setPassword($user->getPassword());
            else
                $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));
            $manager->persist($user);
            $manager->flush();
            $this->addFlash("success",$user->getEmail()." a été modifié avec succès");
            return $this->redirectToRoute("index");
        }
        return $this->render('security/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
    #[
        Route('/profil/message/', name: 'app_message'),
        IsGranted('ROLE_USER')
    ]
    public function message(Request $request, Helpers $helpers): Response
    {
        $user = $helpers->getUser();
        $repo = New UtilisateurRepository($this->doctrine);
        $repoM = new MessagesRepository($this->doctrine);
        $mess = $repoM->findUsers($user->getId());

        if (!$mess) {
            return $this->render('security/message.html.twig', [
                'retour' => 'Aucun échange pour l\'instant',
                'title' => 'Contacts'
            ]);
        }
        $tab = [];
        $i = 0;
        $session = $request->getSession();
        foreach ($mess as $me) {
            if ($me->getUtilisateurReceive()->getId() == $user->getId()) {
                $count = count($repoM->countMessUnread($user->getId(), $me->getUtilisateurSend()->getId()));
                $session->set($me->getUtilisateurSend()->getId(), $count);
                if (!(in_array($me->getUtilisateurSend(), $tab))) {
                    $tab[$i] = $me->getUtilisateurSend();
                    $i += 1;
                }
            } else {
                if (!(in_array($me->getUtilisateurReceive(), $tab))) {
                    $tab[$i] = $me->getUtilisateurReceive();
                    $i += 1;
                }
            }
        }
        return $this->render('security/message.html.twig', [
            'messUser' => $tab,
            'title' => 'Contacts'
        ]);
    }
    #[
        Route('/profil/message/{receive?0}', name: 'app_message.send'),
        IsGranted('ROLE_USER')
    ]
    public function send(Utilisateur $receive, Request $request, Helpers $helpers, SendMail $sendMail): Response
    {
        $repo = New MessagesRepository($this->doctrine);
        $message = new Messages();
        $mess = $request->get('mess');
        $bien_name = ($request->get('bien_name')) ? $request->get('bien_name') : 0;
        $user = $helpers->getUser();
        $flush = false;
        $manager = $this->doctrine->getManager();
        if ($mess != "") {
            $message->setContenu($mess)->setCreateAt()->setUtilisateurSend($user)->setUtilisateurReceive($receive)->setLu(0)->setBien($bien_name);
            $manager->persist($message);
            $flush = true;

            $to = $message->getUtilisateurReceive()->getEmail();
            $subject = 'Franck Immo - Notifications!';
            $contenu = 'Vous avez reçu un message d\'un utilisateur de Franck Immo !Connecter vous pour y répondre';
            $sendMail->sendMailNotification($to, $subject, $contenu);
        }
        // Changer la valeur du lu
        $session = $request->getSession();
        if ($session->get($receive->getId()) >= 0) {
            $messLu = $repo->countMessUnread($user->getId(), $receive->getId());
            foreach ($messLu as $item) {
                $item->setLu(1);
                $manager->persist($item);
            }
            $flush = true;
        }
        // Fin Changer la valeur du lu
        if ($flush) $manager->flush();

        $nbreMess = count($repo->findMessUnread($user));
        $session->set($receive->getId(), count($repo->countMessUnread($user->getId(), $receive->getId())));
        $session->set('tMess', $nbreMess);

        $msg = $repo->findMessage($user->getId(), $receive);
        if (!$msg) {
            return $this->render('security/message.html.twig', [
                'receive' => $receive,
                'retour' => 'Aucun message pour l\'instant',
                'title' => 'Messagerie'
            ]);
        }
        return $this->render('security/message.html.twig', [
            'receive' => $receive,
            'message' => $msg,
            'title' => 'Messagerie'
        ]);
    }

    #[
        Route('/profil/favori/', name: 'app_favori'),
        IsGranted('ROLE_USER')
    ]
    public function favori(Helpers $helpers): Response
    {
        $user = $helpers->getUser();
        $repoFav = new FavorisRepository($this->doctrine);
        $fav = $repoFav->findByUser($user->getId());

        if (!$fav) {
            return $this->render('security/message.html.twig', [
                'retour' => 'Aucun favori pour l\'instant',
                'title' => 'Favori',
                'favs' => 0
            ]);
        }
        return $this->render('security/message.html.twig', [
            'favori' => $fav,
            'title' => 'Favori',
            'favs' => 1
        ]);
    }
    #[
        Route('fav/del/{id}', name: 'app_fav_del'),
        IsGranted('ROLE_USER')
    ]
    public function del_fav(Favoris $com, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('target', '');

        if ($this->isCsrfTokenValid('delete'.$com->getId(), $request->get('_token'))) {
            $manager = $this->doctrine->getManager();
            $manager->remove($com);
            $manager->flush();
        }
        $this->addFlash("success","Le favori a été supprimé avec succès");
        return $this->redirectToRoute('app_favori');
    }
    #[
        Route('/profil/message/del/{user}/{id}', name: 'app_message_del'),
        IsGranted('ROLE_USER')
    ]
    public function delMessage(Utilisateur $user, Messages $mess, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mess->getId(), $request->get('_token'))) {
            $manager = $this->doctrine->getManager();
            $manager->remove($mess);
            $manager->flush();
            $this->addFlash("success","Message supprimé avec succès");
        }
        return $this->redirectToRoute("app_message.send", ['receive' => $user->getId()]);
    }

    #[
        Route(path: '/logout', name: 'app_logout'),
        IsGranted('ROLE_USER')
    ]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
