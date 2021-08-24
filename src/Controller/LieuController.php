<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/lieu", name="lieu_")
 */
class LieuController extends AbstractController
{

    /**
     * @Route (path="/create" , name="create" , methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     */
    public function create (Request $request , EntityManagerInterface $entityManager) {

        //Création d'une instance sortie
        $lieu = new Lieu() ;

        //Création formulaire de sortie
        $formLieu = $this->createForm('App\Form\LieuType', $lieu) ;

        //Récupération des données du navigateur et les transmettre au form
        $formLieu->handleRequest($request) ;

        //Vérification des données du form
        if ($formLieu->isSubmitted() && $formLieu->isValid()){

            //Enregistrer la sortie en BDD
            $entityManager->persist($lieu);
            $entityManager->flush();

            //Message de success
            $this->addFlash('success', 'Un nouveau lieu est disponible !');

            //Redirection sur le controller
            return $this->redirectToRoute('lieu_create');

        }

        return $this->render('lieu/create.html.twig', [
            'formLieu' => $formLieu->createView()
        ]);

    }
//    /**
//     * @Route("/lieu", name="lieu")
//     */
//    public function index(): Response
//    {
//        return $this->render('lieu/index_old.html.twig', [
//            'controller_name' => 'LieuController',
//        ]);
//    }
}
