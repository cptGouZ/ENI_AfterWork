<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieSearchType;
use Doctrine\ORM\EntityManagerInterface;
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
        $repoSortie = $entityManager->getRepository(Sortie::class);
        $formSortieSearch = $this->createForm(SortieSearchType::class,null,[]);
        $formSortieSearch->handleRequest($request);
        $sorties = $repoSortie->findAll();
        if($formSortieSearch->isSubmitted() && $formSortieSearch->isValid()){
            $sorties = $repoSortie->getBySearch($formSortieSearch);
        }
        return $this->render('sortie/index.html.twig', [
            'formSortieSearch' => $formSortieSearch->createView(),
        ]);
    }

}
