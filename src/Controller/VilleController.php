<?php

namespace App\Controller;

use App\Form\VilleType;
use App\Form\VilleSearchType;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
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

            $this->addFlash('success', 'La ville a bien été ajoutée !');
           return $this->redirectToRoute('lieu_create');
        }
        return $this->render('ville/ajouter.html.twig', [
            'formVille' => $formVille->createView()]);
    }

    /**
     * @Route(path="/{id}/lieux", requirements={"id"="\d+"}, name="lieux", methods={"GET"})
     */
    public function lieux(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response{
        /** @var Ville $ville */
        $ville = $entityManager->getRepository(Ville::class)->findOneBy(['id' => $request->get('id')]);
        $lieux = $ville->getLieux() ;
        $lieuxJson = $serializer->serialize($lieux, 'json',['groups' => ['lieux']]);
        return new JsonResponse($lieuxJson);
    }
}
