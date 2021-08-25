<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Enums\SortieStatus;
use App\Form\SortieAnnulationType;
use App\Form\SortieSearchType;
use App\Form\SortieType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * Renvoie la page de base pour effectuer les recherches sur la liste des sorties
     * @Route(path="", name="index", methods={"GET"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager){
        $formSortieSearch = $this->createForm(SortieSearchType::class,null,[]);
        return $this->render('sortie/index.html.twig', [
            'formSortieSearch' => $formSortieSearch->createView()
        ]);
    }

    /**
     * Renvoie les résultats de la recherche demanndée
     * @Route(path="/refresh", name="refresh", methods={"GET", "POST"})
     */
    public function refresh(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        //On récupère les données et on sérialize
        $sorties = $this->getSorties($request, $entityManager);
        $serialized = $serializer->serialize($sorties, 'json', ['groups' => ['sorties']]);
        return new JsonResponse($serialized);
    }


    /**
     * Inscrit l'utilisateur courant à la sortie et redirige vers la méthode de renvoie des résultats
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route(path="/{id}/inscrire", requirements={"id"="\d+"}, name="inscrire", methods={"POST"})
     */
    public function inscrire(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response{
        /** @var User $user */
        /** @var Sortie $sortie */

        //Récupération des repository
        $sortieRepo = $entityManager->getRepository(Sortie::class);

        //Récupération des paramètres utiles à la méthode
        $user = $this->getUser();
        $idSortie = $request->get('id');
        $sortie = $sortieRepo->find($idSortie);

        $sorties=null;
        //Contrôles back
        if($sortie == null){
            $sorties = $this->getSorties($request, $entityManager, 'danger', 'Cette sortie n\'existe pas');
        }
        if(in_array($user, $sortie->getInscrits()->getValues())){
            $sorties = $this->getSorties($request, $entityManager, 'danger', 'Tu es déjà inscrit à cette sortie !');
        }
        if(count($sortie->getInscrits()) >= $sortie->getNbInscriptionMax()) {
            $sorties = $this->getSorties($request, $entityManager, 'danger', 'Désolé mais il n\'y a plus de place');
        }
        if(date_diff($sortie->getDateLimiteInscription(), new DateTime('now'))->invert === 0 ) {
            $sorties = $this->getSorties($request, $entityManager,  'danger', 'Désolé mais les inscriptions sont fermées');
        }

        //Si nous n'avons pas d'erreur, aucune données dans sorties, du coup on fait l'enregistrement et le retour avec le succès
        if( $sorties == null ) {
            $sortie->addInscrit($user);
            $entityManager->persist($sortie);
            $entityManager->flush($sortie);
            $sorties = $this->getSorties($request, $entityManager, 'success', 'Bienvenu à cette sortie');
        }
        $serialized = $serializer->serialize($sorties, 'json', ['groups' => ['sorties']]);
        return new JsonResponse($serialized);
    }

    /**
     * Désinscrit l'utilisateur courant à la sortie et redirige vers la méthode de renvoie des résultats
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route(path="/{id}/desinscrire", requirements={"id"="\d+"}, name="desinscrire", methods={"POST"})
     */
    public function desinscrire(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response{
        //Déclaration du type des variables
        /** @var User $user */
        /** @var Sortie $sortie */

        //Récupération des repository
        $sortieRepo = $entityManager->getRepository(Sortie::class);

        //Récupération des paramètres utiles à la méthode
        $user = $this->getUser();
        $idSortie = $request->get('id');
        $sortie = $sortieRepo->find($idSortie);

        $sorties = null;
        //Contrôles back
        if($sortie == null){
            $sorties = $this->getSorties($request, $entityManager, 'danger', 'Cette sortie n\'existe pas');
        }
        if(!in_array($user, $sortie->getInscrits()->getValues())){
            $sorties = $this->getSorties($request, $entityManager, 'danger', 'Tu n\'est pas connu à dans cette sortie !');
        }

        //Si nous n'avons pas d'erreur, aucune données dans sorties, du coup on fait l'enregistrement et le retour avec le succès
        if( $sorties == null ) {
            //Enregistrement
            $sortie->removeInscrit($user);
            $entityManager->persist($sortie);
            $entityManager->flush($sortie);
            $sorties = $this->getSorties($request, $entityManager, 'success', 'Reviens nous voir à l\'occasion');
        }
        $serialized = $serializer->serialize($sorties, 'json', ['groups' => ['sorties']]);
        return new JsonResponse($serialized);
    }


    private function getSorties(Request $request, EntityManagerInterface $entityManager, ?string $msgGroup = null,  ?string $msg = null) :array {
        /** @var User $user */
        $user = $this->getUser();
        $repoSortie = $entityManager->getRepository(Sortie::class);

        //On récupère les données de recherches si elles sont présentes dans le corps de la requête
        $searchOptions = [];
        if($request->getContent()){
            $searchOptions = $request->toArray();
        }

        //Ajout des messages dans le tableau
        $sorties = [];
        if ($msgGroup && $msg){
            $sorties['msgGroup'] = $msgGroup;
            $sorties['msg'] = $msg;
        }

        //Ajout des sorties au résultat
        $sorties['datas'] = $repoSortie->getBySearch($user, $searchOptions);
        return $sorties;
    }

    /**
     * @Route(path="/{id}" , requirements={"id"="\d+"}, name="view" , methods={"GET"})
     */
    public function view(Request $request , EntityManagerInterface $entityManager)
    {
        /**@var  User $userConnect */

        $userConnect = $this->getUser() ;
        $sortieAAfficher = $entityManager->getRepository(Sortie::class)->findOneBy($request->get('id'));


        return $this->render('sortie/view.html.twig', [
            'sortie' => $sortieAAfficher,
            'user' => $userConnect,
        ]);
    }

    /**
     * @Route (path="/create" , name="create" , methods={"GET", "POST"})
     * @Route (path="/create_published" , name="create_published" , methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     */
    public function create (Request $request , EntityManagerInterface $entityManager): Response {

        /**@var  User $userConnect */
        $userConnect = $this->getUser();

        //Création d'une instance sortie
        $sortie = new Sortie() ;

        //Création formulaire de sortie
        $formCreateSortie = $this->createForm('App\Form\SortieType', $sortie) ;

        //Récupération des données du navigateur et les transmettre au form
        $formCreateSortie->handleRequest($request) ;

        $sortie->setOrganisateur($userConnect);
        $sortie->setCampus($userConnect->getCampus());

        if(str_contains($request->getUri(), 'published')){
            $etat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'published']);
            $sortie->setEtat($etat);
        }
        else{
            $etat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'created']);
            $sortie->setEtat($etat);
        }

        //Vérification des données du form
        if ($formCreateSortie->isSubmitted() && $formCreateSortie->isValid()){
            //Enregistrer la sortie en BDD
            $entityManager->persist($sortie);

            $entityManager->flush();

            //Message de success
            $this->addFlash('success', 'Youhou une nouvelle sortie a bien été créée !');

            //Redirection sur le controller
            return $this->redirectToRoute('sortie_create');
        }

        return $this->render('sortie/create.html.twig', [
            'formCreateSortie' => $formCreateSortie->createView()
        ]);

    }

    /**
     * @Route (path="/{id}/update", name="update", requirements={"id"="\d+"}, methods={"GET", "POST"})
     * @Route (path="/{id}/update_published" , name="update_published", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function update(Request $request, EntityManagerInterface $entityManager): Response {
        //Déclaration du type des variables
        /** @var User $user */
        /** @var Sortie $sortie */

        //Récupération des repository
        $sortieRepo = $entityManager->getRepository(Sortie::class);

        //Récupération des paramètres utiles à la méthode
        $user = $this->getUser();
        $idSortie = $request->get('id');
        $sortie = $sortieRepo->find($idSortie);

        //Création du formulaire
        $formSortie = $this->createForm(SortieType::class, $sortie);
        $formSortie->handleRequest($request);

        //Validation du formulaire
        if( $formSortie->isSubmitted() && $formSortie->isValid() ){
            //Contrôles coté back
            if($sortie == null){
                $this->addFlash('danger', 'Cette sortie n\'existe pas');
                $this->redirectToRoute('sortie_index');
            }
            if($sortie->getOrganisateur() != $user){
                $this->addFlash('danger', 'Désolé mais tu n\'es pas l\'auteur de cette sortie');
                $this->redirectToRoute('sortie_index');
            }
            if($sortie->getEtat()->getLibelle() != SortieStatus::CREEE){
                $this->addFlash('danger', 'Désolé mais cette sortie est déjà publiée ou passée. Veuillez l\'annuler et en créer une nouvelle');
                $this->redirectToRoute('sortie_index');
            }

            //Modification de l'état en published si on enregistre et que l'on publie en meme temps
            if(str_contains($request->getUri(), 'published')){
                $etat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'published']);
                $sortie->setEtat($etat);
            }

            //Enregistrement
            $entityManager->persist($sortie);
            $entityManager->flush();
        }

        return $this->render('sortie/create.html.twig', [
            'action' => 'Modifier',
            'formCreateSortie' => $formSortie->createView()
        ]);
    }



    /**
     * @Route(path="/{id}/publier", requirements={"id"="\d+"}, name="publier", methods={"POST"})
     */
    public function publier(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer){
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
            $serialized = $serializer->serialize(['hasError'=>'Cette sortie n\'existe pas'], 'json');
            return new JsonResponse($serialized);
        }
        if($user !== $sortie->getOrganisateur() && !in_array('ROLE_ADMIN', $user->getRoles())){
            $serialized = $serializer->serialize(['hasError'=>'Tu n\'est pas maître de cette sortie !'], 'json');
            return new JsonResponse($serialized);
        }

        //traitement de la publication
        $etat = $etatRepo->findOneBy(['libelle'=>'published']);
        $sortie->setEtat($etat);

        //Enregistrement et redirection
        $entityManager->persist($sortie);
        $entityManager->flush($sortie);
        return $this->redirectToRoute('sortie_refresh',[
            'request' => $request
        ], 307);
    }

    /**
     * @Route(path="/{id}/annuler", requirements={"id"="\d+"}, name="annuler")
     */
    public function annuler(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer){
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
        if ($sortie == null) {
            $this->addFlash('danger','Cette sortie n\'existe pas');
            return $this->redirectToRoute('sortie_index');
        }
        if ($user !== $sortie->getOrganisateur() && !in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->addFlash('danger','Tu n\'est pas maître de cette sortie !');
            return $this->redirectToRoute('sortie_index');
        }

        //Création du formulaire
        $formAnnulationSortie = $this->createForm(SortieAnnulationType::class, $sortie );
        $formAnnulationSortie->handleRequest($request);

        if($formAnnulationSortie->isSubmitted()) {
            //traitement de la publication
            $etat = $etatRepo->findOneBy(['libelle' => 'cancelled']);
            $sortie->setEtat($etat);

            //Enregistrer la sortie en BDD
            $entityManager->persist($sortie);
            $entityManager->flush();

            //Redirection sur le controller
            $this->addFlash('success', 'Dommage la sortie semblait sympa !');
            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('sortie/annulation.html.twig', [
            'sortie' => $sortie,
            'formAnnulationSortie' => $formAnnulationSortie->createView()
        ]);
    }
}
