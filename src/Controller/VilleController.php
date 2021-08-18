<?php

namespace App\Controller;

use App\Form\VilleType;
use App\Form\VilleSearchType;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route(path="/ville", name="ville_")
 */
class VilleController extends AbstractController
{
    /**
     * @Route(path="/", name="index", methods={"GET", "POST"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response {
        $formVilleSearch = $this->createForm(VilleSearchType::class, null, []);
        $formVilleSearch->handleRequest($request);
        $liste = $entityManager->getRepository(Ville::class)->findAll();
        if($formVilleSearch->isSubmitted() && $formVilleSearch->isSubmitted()){
            $liste = $entityManager->getRepository(Ville::class)->searchVillesByName($formVilleSearch->get('search')->getData());
        }
        return $this->render('ville/index.html.twig', [
            'formVilleSearch'=>$formVilleSearch->createView(),
            'liste'=>$liste,
        ]);
    }

    /**
     * @Route(path="/ajouter", name="ajouter", methods={"GET", "POST"})
     */
    public function ajouter (Request $request, EntityManagerInterface $entityManager): Response
    {
        $ville = new Ville();
        $formVille = $this->createForm(VilleType::class, $ville, []);
        $formVille->handleRequest($request);
        if ($formVille->isSubmitted() && $formVille->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();
            return $this->render('ville/index.html.twig', [
                'controller_name' => 'VilleController',
            ]);
        }
        return $this->render('ville/ajouter.html.twig', ['formVille' => $formVille->createView()]);
    }
}