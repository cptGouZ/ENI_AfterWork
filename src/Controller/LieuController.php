<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{

    /**
     * @Route (path="/create" , name="create" , methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     */
    public function create (Request $request , EntityManagerInterface $entityManager) {

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

            //Redirection sur le controller
            return $this->redirectToRoute('sortie_');

        }

        return $this->render('sortie/create.html.twig', [
            'formCreateSortie' => $formCreateSortie->createView()
        ]);

    }
    /**
     * @Route("/lieu", name="lieu")
     */
    public function index(): Response
    {
        return $this->render('lieu/index.html.twig', [
            'controller_name' => 'LieuController',
        ]);
    }
}
