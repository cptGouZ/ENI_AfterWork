<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SortieSearchType;
use App\Form\SortieType;
use App\Repository\SortieSearchOptions;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route(path="", name="index", methods={"GET", "POST"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response {
        /** @var User $user */
        $user = $this->getUser();
        $repoSortie = $entityManager->getRepository(Sortie::class);
        $searchOptions = [];

        $formSortieSearch = $this->createForm(SortieSearchType::class,null,[]);
        $formSortieSearch->handleRequest($request);

        if($formSortieSearch->isSubmitted() && $formSortieSearch->isValid()){
            $searchOptions = [
                SortieSearchOptions::INSCRIT_OUI => $formSortieSearch->get('inscrit_oui')->getData(),
                SortieSearchOptions::INSCRIT_NON => $formSortieSearch->get('inscrit_non')->getData(),
                SortieSearchOptions::MES_SORTIES =>  $formSortieSearch->get('mes_sorties')->getData(),
                SortieSearchOptions::DATE_DEBUT => $formSortieSearch->get('date_debut')->getData(),
                SortieSearchOptions::DATE_FIN => $formSortieSearch->get('date_fin')->getData(),
                SortieSearchOptions::SORTIES_PASSEES => $formSortieSearch->get('sorties_passees')->getData(),
                SortieSearchOptions::NOM_CONTIENT => $formSortieSearch->get('nom_sortie')->getData(),
                SortieSearchOptions::CAMPUS => $formSortieSearch->get('campus')->getData(),
            ];
        }
        $sorties = $repoSortie->getBySearch($user, $searchOptions);
        return $this->render('sortie/index.html.twig', [
            'formSortieSearch' => $formSortieSearch->createView(),
            'sorties' => $sorties,
        ]);
    }


    /**
     * @Route (path="/create" , name="create" , methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     */
    public function create (Request $request , EntityManagerInterface $entityManager): Response {

        //Création d'une instance sortie
        $sortie = new Sortie() ;

        //Création formulaire de sortie
        $formCreateSortie = $this->createForm(SortieType::class) ;

        //Récupération des données du navigateur et les transmettre au form
        $formCreateSortie->handleRequest($request) ;

        //Vérification des données du form
        if ($formCreateSortie->isSubmitted() && $formCreateSortie->isValid()){

            //Enregistrer la sortie en BDD
            $entityManager->persist($sortie);
            $entityManager->flush();

            //Message de success
            $this->addFlash('success', 'Youhou une nouvelle sortie a bien été créée !');

            //Redirection ur le controller
            return $this->redirectToRoute('sortie_');


        }


        return $this->render('sortie/create.html.twig', [
            'formCreateSortie' => $formCreateSortie->createView()
        ]);

    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route(path="/{id}/inscrire", requirements={"id"="\d+"}, name="inscrire")
     */
    public function inscrire(Request $request, EntityManagerInterface $entityManager): Response{
        $sortieRepo = $entityManager->getRepository(Sortie::class);
        $idSortie = $request->get('id');
        /** @var User $user */
        $user = $this->getUser();
        /** @var Sortie $sortie */
        $sortie = $sortieRepo->find($idSortie);
        if($sortie == null){
            $this->addFlash('danger', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('sortie_index');
        }
        if(in_array($user, $sortie->getInscrits()->getValues())){
            $this->addFlash('danger','Tu es déjà inscrit à cette sortie !');
            return $this->redirectToRoute('sortie_index');
        }
        if(count($sortie->getInscrits()) < $sortie->getNbInscriptionMax()) {
            $this->addFlash('danger','Il n\'y a plus de place');
            return $this->redirectToRoute('sortie_index');
        }
        $sortie->addInscrit($user);
        $entityManager->persist($sortie);
        $entityManager->flush($sortie);
        $this->addFlash('success','Vous êtes maintenant inscrit à la sortie');
        return $this->redirectToRoute('sortie_index');
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route(path="/{id}/desinscrire", requirements={"id"="\d+"}, name="desinscrire")
     */
    public function desinscrire(Request $request, EntityManagerInterface $entityManager): Response{
        $sortieRepo = $entityManager->getRepository(Sortie::class);
        $idSortie = $request->get('id');
        /** @var User $user */
        $user = $this->getUser();
        /** @var Sortie $sortie */
        $sortie = $sortieRepo->find($idSortie);
        if($sortie == null){
            $this->addFlash('danger', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('sortie_index');
        }
        if(!in_array($user, $sortie->getInscrits()->getValues())){
            $this->addFlash('danger','Tu n\'est pas connu à dans cette sortie !');
            return $this->redirectToRoute('sortie_index');
        }
        $sortie->removeInscrit($user);
        $entityManager->persist($sortie);
        $entityManager->flush($sortie);
        $this->addFlash('success','Vous êtes maintenant désinscrit de la sortie');
        return $this->redirectToRoute('sortie_index');
    }
}
