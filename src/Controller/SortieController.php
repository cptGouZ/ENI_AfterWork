<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SortieSearchType;
use App\Repository\SortieSearchOptions;
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

        /** @var User $user */
        $user = $this->getUser();
        if($formSortieSearch->isSubmitted() && $formSortieSearch->isValid()){
            $sorties = $repoSortie->getBySearch( $user,[
                SortieSearchOptions::INSCRIT => $formSortieSearch->get('inscrit')->getData(),
                SortieSearchOptions::MES_SORTIES =>  $formSortieSearch->get('inscrit')->getData()
            ]);
        }
        return $this->render('sortie/index.html.twig', [
            'formSortieSearch' => $formSortieSearch->createView(),
        ]);
    }

}
