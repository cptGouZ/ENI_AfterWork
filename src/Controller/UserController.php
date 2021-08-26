<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/user" , name="user_")
 *
 */
class UserController extends AbstractController
{
    /**
     * @Route(path="/create" , name="create" , methods={"GET" , "POST"})
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
            $this->addFlash('success', 'Votre profil a bien été enregistré !');

            // Redirection sur le controlleur
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/create.html.twig', [
            'action' => 'Créer un',
            'formUser' => $formUser->createView(),
        ]);
    }

    /**
     * @Route(path="/modify" , name="modify" , methods={"GET" , "POST"})
     */
    public function modify(Request $request , EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        /**@var  User $userConnect */

        $userConnect = $this->getUser() ;

        // Création du formulaire
        $formUser = $this->createForm('App\Form\UserType', $userConnect);

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formUser->handleRequest($request);

        // Vérifier les données du formulaire
        if ($formUser->isSubmitted() && $formUser->isValid()) {

            // Hashage du mot de passe
            $userConnect->setPassword($passwordEncoder->hashPassword($userConnect, $userConnect->getPassword()));

            // Enregistrement de l'entité dans la BDD
            $entityManager->persist($userConnect);
            $entityManager->flush();

            // Ajout d'un message de confirmation
            $this->addFlash('success', 'Votre profil a bien été enregistré !');

            // Redirection sur le controlleur
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/create.html.twig', [
            'action' => 'Modifier mon',
            'formUser' => $formUser->createView(),
            'user' => $userConnect,
        ]);
    }

    /**
     * @Route(path="" , name="view" , methods={"GET" , "POST"})
     * @Route(path="/{id}", requirements={"id"="\d+"} , name="view_id" , methods={"GET" , "POST"})
     */
    public function view(Request $request , EntityManagerInterface $entityManager)
    {
        /**@var  User $userConnect */

        if ($request->get( 'id' )){
            $userConnect = $entityManager->getRepository(User::class)->findOneBy(['id' => $request->get( 'id' )]);
        }else{
            $userConnect = $this->getUser() ;
        }

        // Création du formulaire
        $formUser = $this->createForm('App\Form\UserType', $userConnect,array('is_view' => true));

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formUser->handleRequest($request);



        return $this->render('user/view.html.twig', [
            'formUser' => $formUser->createView(),
            'user' => $userConnect,
        ]);
    }
}