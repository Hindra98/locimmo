<?php

namespace App\Controller;

use App\Entity\Appartements;
use App\Entity\Biens;
use App\Entity\Chambres;
use App\Entity\Favoris;
use App\Entity\Maisons;
use App\Entity\PhotosBiens;
use App\Entity\Proprietaires;
use App\Entity\Studios;
use App\Entity\Utilisateur;
use App\Form\BienPhotoType;
use App\Form\BiensType;
use App\Form\SearchType;
use App\Form\TypeBienType;
use App\Repository\BiensRepository;
use App\Repository\ProprietairesRepository;
use App\Service\FileUploader;
use App\Service\Helpers;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/FI')]
class BiensController extends AbstractController
{

    private BiensRepository $biens;
    private ManagerRegistry $doctrine;
    private $tab_bien = [
        0 => 'Chambres',
        1 => 'Studios',
        2 => 'Appartements',
        3 => 'Maisons'
    ];

  public function __construct(BiensRepository $biens, ManagerRegistry $doctrine, private UrlGeneratorInterface $urlGenerator) {
      $this->biens = $biens;
      $this->doctrine = $doctrine;
  }
    #[
        Route('/p/{page?1}', name: 'biens'),
        IsGranted('ROLE_PROPRIO')
    ]
    public function index($page, Helpers $helpers, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('target', '');

        $biensUser = $this->biens->findByUser($helpers->getUser()->getProprio()->current());
        $nbreBien = count($biensUser);
        $nbrePage = ceil($nbreBien / 10);
        $biens = $this->biens->findByUserWithOfset($helpers->getUser()->getProprio(), $page);
        return $this->render('biens/index.html.twig', [
            'page' => $page,
            'nbrePage' => $nbrePage,
            'biens' => $biens
        ]);
    }

    #[
        Route('/create', name: 'biens.create'),
        IsGranted('ROLE_PROPRIO')
    ]
    public function newBien(Request $request, FileUploader $fileUploader, Helpers $helpers): Response
    {
        $session = $request->getSession();
        $session->set('target', '');
        $manager = $this->doctrine->getManager();

        $biens = new Biens();
        $form = $this->createForm(BienPhotoType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tof = $form->get('photos')->getData();
            $adresse = $form->get('numero')->getData().' '.$form->get('rue')->getData().', ';
            $adresse .= $form->get('code')->getData().' '.$form->get('ville')->getData();
            $description = $form->get('description')->getData();
            $directory = $this->getParameter('repertoire_images_biens');

            $biens->setPrix($form->get('prix')->getData())
                ->setSuperficie($form->get('superficie')->getData())
                ->setNom($form->get('nom')->getData())
                ->setProprietaires($helpers->getUser()->getProprio()->current())
                ->setAdresse($adresse)->setDescription($description);
            $biens->setEtat(1)->setDateCreation(new DateTime());
            $type = $form->get('type')->getData();

            foreach($tof as $photoFile) {
                $nouveauNom = $fileUploader->uploads($photoFile, $directory);
                $photo = new PhotosBiens();
                $photo->setNom($nouveauNom[0])->setCreateAt()->setBiens($biens);
                if (!$nouveauNom[1]) { // Si on tombe sur une image
                    $photo->setVideo(0);
                    $biens->setPhoto($nouveauNom[0]);
                } else $photo->setVideo(1);
                $manager->persist($photo);
            }
            $manager->persist($biens);

            if ($type == 1) {
                $ch = new Studios();
                $ch->setBiens($biens);
                $manager->persist($ch);
                $manager->flush();
                $this->addFlash('success', "Le studio a été crée ");
                return $this->redirectToRoute("biens");
            } else {
                $manager->flush();
                return $this->render('biens/type.html.twig', [
                    'biens' => $biens,
                    'type' => $type,
                    'modif' => 0
                ]);
            }
        }
        return $this->render('biens/create.html.twig', [
            'biens' => $biens,
            'form' => $form->createView()
        ]);
    }

    #[Route('/type/{biens}/{type}/{modif?0}', name: 'biens.type')]
    public function type(Biens $biens, $type, $modif, Request $request) : Response
    {
        $session = $request->getSession();
        $session->set('target', '');

        if ($modif != 0) {
            if ($biens->getAppart()->current())
                $biens->removeAppart($biens->getAppart()->current());
            elseif ($biens->getMaisons()->current())
                $biens->removeMaison($biens->getMaisons()->current());
            elseif ($biens->getChambres()->current())
                $biens->removeChambre($biens->getChambres()->current());
            else
                $biens->removeStudio($biens->getStudios()->current());
        }

        $manager = $this->doctrine->getManager();
        if ($type == 0) { // Chambre
            $ch = new Chambres();
            $ch->setBiens($biens)->setType($request->get('type_cam'));
            if ($modif == 0)
                $this->addFlash('success', "La chambre a bien été crée ");
            else
                $this->addFlash('success', "La chambre a bien été modifié ");
        } elseif($type == 2) { // Appartement
            $ch = new Appartements();
            $etage = ($request->get('etage') == 'on') ? true : false;
            $ascenceur = ($request->get('ascenceur') == 'on') ? true : false;
            $garage = ($request->get('garage') == 'on') ? true : false;
            $ch->setBiens($biens)->setNbrePieces($request->get('piece_appart'))->setEtage($etage)
                ->setGarage($garage)->setAscenceur($ascenceur);
            if ($modif == 0)
                $this->addFlash('success', "L'appartement a bien été crée ");
            else
                $this->addFlash('success', "L'appartement a bien été modifié ");
        } elseif($type == 3) { // Maison
            $ch = new Maisons();
            $grenier = ($request->get('grenier') == 'on') ? true : false;
            $ch->setBiens($biens)->setNbrePieces($request->get('piece'))->setGrenier($grenier);
            if ($modif == 0)
                $this->addFlash('success', "La maison a bien été crée ");
            else
                $this->addFlash('success', "La maison a bien été modifié ");
        } else {
            $this->addFlash('error', "Erreur de chemin!!!");
            return $this->redirectToRoute("index");
        }
        $manager->persist($ch);
        $manager->flush();
        return $this->redirectToRoute("biens");
    }

    #[Route('/{id}', name: 'biens.detail')]
    public function detail($id, Request $request, Helpers $helpers) : Response
    {
        $session = $request->getSession();
        $targetPath = $this->urlGenerator->generate('biens.detail', ['id' => $id]) ;
        $session->set('target', $targetPath);

        $user = 0;
        if ($helpers->getUser() != null) {
            $user = $helpers->getUser()->getId();
        }

        $repo = $this->doctrine->getRepository(Biens::class);
        $repoPhoto = $this->doctrine->getRepository(PhotosBiens::class);
        $repoFav = $this->doctrine->getRepository(Favoris::class);
        $bien = $repo->find($id);
        $photos = $repoPhoto->findByBien($bien);
        $fav = $repoFav->findByBiens($id, $user);
        if (!$bien) {
            $this->addFlash('error', "Le bien n'existe pas");
            return $this->redirectToRoute("index");
        }

        return $this->render('biens/detail.html.twig', [
            'bien' => $bien,
            'fav' => $fav,
            'photos' => $photos
        ]);

    }

    #[
        Route('/edit/{id}', name: 'biens.edit', methods: 'GET|POST'),
        IsGranted('ROLE_PROPRIO')
    ]
    public function edit(Biens $biens, Request $request, FileUploader $fileUploader, Helpers $helpers): Response
    {
        $session = $request->getSession();
        $session->set('target', '');
        $manager = $this->doctrine->getManager();

        $form = $this->createForm(BienPhotoType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tof = $form->get('photos')->getData();
            $adresse = $form->get('numero')->getData().' '.$form->get('rue')->getData().', ';
            $adresse .= $form->get('code')->getData().' '.$form->get('ville')->getData();
            $description = $form->get('description')->getData();
            $directory = $this->getParameter('repertoire_images_biens');

            $biens->setPrix($form->get('prix')->getData())
                ->setSuperficie($form->get('superficie')->getData())
                ->setNom($form->get('nom')->getData())
                ->setProprietaires($helpers->getUser()->getProprio()->current())
                ->setAdresse($adresse)->setDescription($description);
            $biens->setEtat(1)->setDateCreation(new DateTime());
            $type = $form->get('type')->getData();

            foreach($tof as $photoFile) {
                $nouveauNom = $fileUploader->uploads($photoFile, $directory);
                $photo = new PhotosBiens();
                $photo->setNom($nouveauNom[0])->setCreateAt()->setBiens($biens);
                if (!$nouveauNom[1]) { // Si on tombe sur une image
                    $photo->setVideo(0);
                    $biens->setPhoto($nouveauNom[0]);
                } else $photo->setVideo(1);
                $manager->persist($photo);
            }
            $manager->persist($biens);
            if ($type == 1) {
                $ch = new Studios();
                $ch->setBiens($biens);
                $manager->persist($ch);
                $manager->flush();
                $this->addFlash('success', $biens->getNom()." a été modifié ");
                return $this->redirectToRoute("biens");
            } else {
                $manager->flush();
                return $this->render('biens/type.html.twig', [
                    'biens' => $biens,
                    'type' => $type,
                    'modif' => 1
                ]);
            }
        }
        return $this->render('biens/edit.html.twig', [
            'biens' => $biens,
            'form' => $form->createView()
        ]);
    }

    #[
        Route('/delete/{id}', name: 'biens.delete'),
        IsGranted('ROLE_PROPRIO')
    ]
    public function delete(Biens $biens, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('target', '');

        if ($this->isCsrfTokenValid('delete'.$biens->getId(), $request->get('_token'))) {
            $manager = $this->doctrine->getManager();

            if ($biens->getImage()->current()) {
                foreach ($biens->getImage() as $item) {
                    $directory = $this->getParameter('repertoire_images_biens');
                    dd($directory.'/'.$item->getNom());
                    unlink($directory.'/'.$item->getNom());
                    $manager->remove($item);
                }
            }
            if ($biens->getFavoris()->current()) {
                foreach ($biens->getFavoris() as $item) {
                    $manager->remove($item);
                }
            }
            if ($biens->getChambres()->current()) {
                foreach ($biens->getChambres() as $item) {
                    $manager->remove($item);
                }
            }
            if ($biens->getStudios()->current()) {
                foreach ($biens->getStudios() as $item) {
                    $manager->remove($item);
                }
            }
            if ($biens->getMaisons()->current()) {
                foreach ($biens->getMaisons() as $item) {
                    $manager->remove($item);
                }
            }
            if ($biens->getAppart()->current()) {
                foreach ($biens->getAppart() as $item) {
                    $manager->remove($item);
                }
            }

            $nom = $biens->getNom();
            $manager->remove($biens);
            $manager->flush();
            $this->addFlash("success",$nom." a été supprimé avec succès");
        }
        return $this->redirectToRoute("biens");
    }
    #[
        Route('/fav/{id}', name: 'biens.fav'),
        IsGranted('ROLE_USER')
    ]
    public function favori(Biens $biens, Helpers $helpers, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('target', '');

        $user = $helpers->getUser();
        if ($this->isCsrfTokenValid('fav'.$biens->getId(), $request->get('_token'))) {
            $manager = $this->doctrine->getManager();
            $favori = new Favoris();
            $favori->setBiens($biens)->setUtilisateur($user);

            // $biens->addFavori($favori);
            $manager->persist($favori);
            $manager->flush();
        }
        return $this->redirectToRoute("biens.detail", [
            'id' => $biens->getId()
        ]);
    }
    #[
        Route('photo/del/{bien}/{id}', name: 'bien.photo.del'),
        IsGranted('ROLE_PROPRIO')
    ]
    public function del_fav(Biens $bien, PhotosBiens $com, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('target', '');

        if ($this->isCsrfTokenValid('delete'.$com->getId(), $request->get('_token'))) {
            $manager = $this->doctrine->getManager();

            $directory = $this->getParameter('repertoire_images_biens');
            unlink($directory.'/'.$com->getNom());

            $manager->remove($com);
            $manager->flush();
        }
        $this->addFlash("success","La photo a été supprimé avec succès");
        return $this->redirectToRoute('biens.edit', ['id' => $bien->getId()]);
    }
}
