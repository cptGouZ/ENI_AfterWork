<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ConnexionController extends AbstractController
{
    /**
     * @Route("/", name="connexion" , methods={"GET"})
     */
    public function connexion(): Response
    {
        return $this->render('connexion/connexion.html.twig');
    }
}
