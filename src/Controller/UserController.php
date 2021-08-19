<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/" , name="user_")
 *
 */
class UserController extends AbstractController
{
    /**
     * @Route(path="create" , name="create" , methods={"GET" , "POST"})
     */
    public function create(Request $request , EntityManagerInterface $entityManager)
    {

        // Création de l'entité à créer
        $user = new User();

        // Création du formulaire
        $formUser = $this->createForm('App\Form\UserType', $user);

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formUser->handleRequest($request);

        // Vérifier les données du formulaire
        if ($formUser->isSubmitted() && $formUser->isValid()) {

            // Enregistrement de l'entité dans la BDD
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajout d'un message de confirmation
            $this->addFlash('success', 'Category successfully added !');

            // Redirection sur le controlleur
            return $this->redirectToRoute('app_login');

        }

        return $this->render('user/create.html.twig', [
            'formUser' => $formUser->createView(),
        ]);
    }
}