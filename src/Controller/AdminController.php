<?php

namespace App\Controller;

use App\Entity\Biens;
use App\Entity\Commentaires;
use App\Entity\Messages;
use App\Entity\UserBan;
use App\Entity\Utilisateur;
use App\Repository\BiensRepository;
use App\Repository\CommentairesRepository;
use App\Repository\UtilisateurRepository;
use App\Service\Helpers;
use App\Service\SendMail;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private UtilisateurRepository $user;
    private CommentairesRepository $com;
    private ManagerRegistry $doctrine;

    public function __construct(UtilisateurRepository $user, CommentairesRepository $com, ManagerRegistry $doctrine) {
        $this->user = $user;
        $this->com = $com;
        $this->doctrine = $doctrine;
    }
    #[
        Route('/p/{page?1}', name: 'app_admin'),
        IsGranted('ROLE_ADMIN')
    ]
    public function index($page, Helpers $helpers, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('target', '');

        $nbreBien = $this->user->count([]);
        $nbrePage = ceil($nbreBien / 10);
        $user = $this->user->findUser($helpers->getUser()->getId(), $page);
        return $this->render('admin/index.html.twig', [
            'page' => $page,
            'nbrePage' => $nbrePage,
            'user' => $user
        ]);
    }

    #[
        Route('/comment/{page?1}', name: 'app_admin_comment'),
        IsGranted('ROLE_ADMIN')
    ]
    public function comment($page, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('target', '');

        $nbreBien = $this->com->count([]);
        $nbrePage = ceil($nbreBien / 10);
        $user = $this->com->findBy([],['id' => 'DESC'], 10, ($page-1)*10);
        // Changer la valeur du lu
        if ($session->get('tCom') > 0) {
            $manager = $this->doctrine->getManager();
            foreach ($user as $item) {
                $item->setLu(1);
                $manager->persist($item);
            }
            $manager->flush();
            $repoCom = $this->doctrine->getRepository(Commentaires::class);
            $com = $repoCom->findComUnread();
            $nbreCom = count($com);
            $session->set('tCom', $nbreCom);
        }
        // Fin Changer la valeur du lu

        return $this->render('admin/comment.html.twig', [
            'page' => $page,
            'nbrePage' => $nbrePage,
            'user' => $user
        ]);
    }

    #[
        Route('/delete/{id}', name: 'app_admin_delete'),
        IsGranted('ROLE_USER')
    ]
    public function delete(Utilisateur $user, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('target', '');

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->get('_token'))) {
            $nom = $user->getEmail();
            $manager = $this->doctrine->getManager();
            if ($user->getProprio()->current()) {
                foreach ($user->getProprio() as $proprio) {
                    if ($proprio->getBien()->current()) {
                        foreach ($proprio->getBien() as $item) {
                            $manager->remove($item);
                        }
                    }
                    foreach ($proprio->getAbonnement() as $item) {
                        $manager->remove($item);
                    }
                    $manager->remove($proprio);
                }
            }
            if ($user->getAdmin()->current()) {
                foreach ($user->getAdmin() as $item) {
                    $manager->remove($item);
                }
            }
            if ($user->getCommentaires()->current()) {
                foreach ($user->getCommentaires() as $commentaire) {
                    $manager->remove($commentaire);
                }
            }
            if ($user->getMessage()->current()) {
                foreach ($user->getMessage() as $item) {
                    $manager->remove($item);
                }
            }
            if ($user->getMessages()->current()) {
                foreach ($user->getMessages() as $item) {
                    $manager->remove($item);
                }
            }

            $manager->remove($user);
            if ($request->query->get('user') == 'user') {
                $manager->flush();
                $this->addFlash("success"," Vous avez supprimé votre compte avec succès");
                return $this->redirectToRoute('index');
            } else {
                $userBan = new UserBan();
                $userBan->setEmail($nom)->setCreateAt(new \DateTimeImmutable());
                $manager->persist($userBan);
            }
            $manager->flush();
            $this->addFlash("success",$nom." a été supprimé avec succès");
        }
        return $this->redirectToRoute('app_admin');
    }
    #[
        Route('com/del/{id}', name: 'app_admin_del_com'),
        IsGranted('ROLE_ADMIN')
    ]
    public function del_com(Commentaires $com, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('target', '');

        if ($this->isCsrfTokenValid('delete'.$com->getId(), $request->get('_token'))) {
            $manager = $this->doctrine->getManager();
            $nom = $com->getUtilisateur()->getEmail();
            $manager->remove($com);
            $manager->flush();
        }
        $this->addFlash("success","Le commentaire de $nom a été supprimé avec succès");
        return $this->redirectToRoute('app_admin_comment');
    }
    #[
        Route('nomme/{id}', name: 'app_admin_nomme'),
        IsGranted('ROLE_ADMIN')
    ]
    public function nomme(Utilisateur $user, Request $request, Helpers $helpers, SendMail $sendMail): Response
    {
        $session = $request->getSession();
        $session->set('target', '');
        $nom = $user->getNom();
        $manager = $this->doctrine->getManager();
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();

        $to = $user->getEmail();
        $subject = 'Franck Immo - Notifications!';
        $contenu = 'Vous avez été nommé admin par '.$helpers->getUser()->getNom().'.Vous êtes desormais administrateur de Franck Immo';
        $sendMail->sendMailNotification($to, $subject, $contenu);

        $this->addFlash("success","L'utilisateur $nom a été nommé administrateur avec succès");
        return $this->redirectToRoute('app_admin');
    }
}
