<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Enums\SortieSearchOptions;
use App\Form\SortieSearchType;
use App\Form\SortieType;
use DateTime;
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
        /** @var User $user */
        $user = $this->getUser();
        $repoSortie = $entityManager->getRepository(Sortie::class);
        $searchOptions = [];

        $formSortieSearch = $this->createForm(SortieSearchType::class,null,[]);
        $formSortieSearch->handleRequest($request);

        if($formSortieSearch->isSubmitted() && $formSortieSearch->isValid()){
            foreach(SortieSearchOptions::getConstants() as $option){
                $searchOptions[$option] = $formSortieSearch->get($option)->getData();
            }
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

        /**@var  User $userConnect */

        $userConnect = $this->getUser();

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
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route(path="/{id}/inscrire", requirements={"id"="\d+"}, name="inscrire")
     */
    public function inscrire(Request $request, EntityManagerInterface $entityManager): Response{
        /** @var User $user */
        /** @var Sortie $sortie */

        //Récupération des repository
        $sortieRepo = $entityManager->getRepository(Sortie::class);

        //Récupération des paramètres utiles à la méthode
        $idSortie = $request->get('id');
        $user = $this->getUser();
        $sortie = $sortieRepo->find($idSortie);

        //Contrôles back
        if($sortie == null){
            $this->addFlash('danger', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('sortie_index');
        }
        if(in_array($user, $sortie->getInscrits()->getValues())){
            $this->addFlash('danger','Tu es déjà inscrit à cette sortie !');
            return $this->redirectToRoute('sortie_index');
        }
        if(count($sortie->getInscrits()) >= $sortie->getNbInscriptionMax()) {
            $this->addFlash('danger','Désolé mais il n\'y a plus de place');
            return $this->redirectToRoute('sortie_index');
        }
        if(date_diff($sortie->getDateLimiteInscription(), new DateTime('now'))->invert === 0 ) {
            $this->addFlash('danger','Désolé mais les inscriptions sont fermées');
            return $this->redirectToRoute('sortie_index');
        }

        //Enregistrement et redirection
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
        //Déclaration du type des variables
        /** @var User $user */
        /** @var Sortie $sortie */

        //Récupération des repository
        $sortieRepo = $entityManager->getRepository(Sortie::class);
        $idSortie = $request->get('id');

        //Récupération des paramètres utiles à la méthode
        $user = $this->getUser();
        $sortie = $sortieRepo->find($idSortie);

        //Contrôles back
        if($sortie == null){
            $this->addFlash('danger', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('sortie_index');
        }
        if(!in_array($user, $sortie->getInscrits()->getValues())){
            $this->addFlash('danger','Tu n\'est pas connu à dans cette sortie !');
            return $this->redirectToRoute('sortie_index');
        }

        //Enregistrement et redirection
        $sortie->removeInscrit($user);
        $entityManager->persist($sortie);
        $entityManager->flush($sortie);
        $this->addFlash('success','Vous êtes maintenant désinscrit de la sortie');
        return $this->redirectToRoute('sortie_index');
    }

    /**
     * @Route(path="/{id}/publier", requirements={"id"="\d+"}, name="publier")
     * @Route(path="/{id}/annuler", requirements={"id"="\d+"}, name="annuler")
     */
    public function publier_annuler(Request $request, EntityManagerInterface $entityManager){
        //Déclaration du type des variables
        /** @var User $user */
        /** @var Sortie $sortie */

        //Récupération des repository
        $sortieRepo = $entityManager->getRepository(Sortie::class);
        $etatRepo = $entityManager->getRepository(Etat::class);

        //Récupération des paramètres utiles à la méthode
        $idSortie = $request->get('id');
        $user = $this->getUser();
        $sortie = $sortieRepo->find($idSortie);

        //Contrôles back
        if($sortie == null){
            $this->addFlash('danger', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('sortie_index');
        }
        if($user !== $sortie->getOrganisateur() && !in_array('ROLE_ADMIN', $user->getRoles())){
            $this->addFlash('danger','Tu n\'est pas maître de cette sortie !');
            return $this->redirectToRoute('sortie_index');
        }
        if (str_contains($request->getUri(), 'annuler')){
            $etat = $etatRepo->findOneBy(['libelle'=>'cancelled']);
            $this->addFlash('success','C\'est dommage, la sortie semblait sympas');
            $sortie->setEtat($etat);
        }
        if (str_contains($request->getUri(), 'publier')){
            $etat = $etatRepo->findOneBy(['libelle'=>'published']);
            $this->addFlash('success','Vive l\'apéro !');
            $sortie->setEtat($etat);
        }

        //Enregistrement et redirection
        $entityManager->persist($sortie);
        $entityManager->flush($sortie);
        return $this->redirectToRoute('sortie_index');
    }
}
