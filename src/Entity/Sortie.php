<?php

namespace App\Entity;

use App\Enums\SortieStatus;
use App\Repository\SortieRepository;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 * @ORM\Table(name="sorties")
 * @UniqueEntity(fields={"dateHeureDebut", "nom"}, message="Un évènement existe déjà avec ce nom et à cette date")
 */
class Sortie
{
    /**
     * @Groups ("sorties")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups ("sorties")
     * @ORM\Column(name="nom", type="string", length=30)
     * @Assert\NotBlank(message="La rue doit être renseigné")
     * @Assert\Length (
     *      min=5, minMessage="5 charactères minimum",
     *      max=30, maxMessage="30 charactères maximum"
     * )
     */
    private $nom;

    /**
     * @Groups ("sorties")
     * @ORM\Column(name="date_heure_debut", type="datetime")
     * @Assert\NotBlank (message="La date limite d'inscription doit être renseignée")
     * Assert\DateTime (message="Le format Date Heure n'est pas le bon")
     */
    private $dateHeureDebut;

    /**
     * @Groups ("sorties")
     * @ORM\Column(name="duree", type="integer")
     * @Assert\NotBlank(message="La valeur doit être renseignée")
     * @Assert\Positive(message="La valeur doit être positive")
     */
    private $duree;

    /**
     * @Groups ("sorties")
     * @ORM\Column(name="date_limite_inscription", type="datetime")
     * @Assert\NotBlank (message="La date limite d'inscription doit être renseignée")
     * Assert\DateTime (message="Le format Date Heure n'est pas le bon")
     */
    private $dateLimiteInscription;

    /**
     * @Groups ("sorties")
     * @ORM\Column(name="nb_inscription_max", type="integer")
     * @Assert\NotBlank(message="La valeur doit être renseignée")
     * @Assert\Positive(message="La valeur doit être positive")
     */
    private $nbInscriptionMax;

    /**
     * @Groups ("sorties")
     * @ORM\Column(name="infos_sortie", type="text", nullable=true)
     */
    private $infosSortie;

    /**
     * @Groups ("sorties")
     * @ORM\Column(name="motif_annulation", type="text", nullable=true)
     */
    private $motifAnnulation;

    /**
     * @Groups ("sorties")
     * @ORM\ManyToOne(targetEntity=Etat::class)
     * @ORM\JoinColumn(name="id_etat", nullable=false)
     */
    private Etat $etat;

    /**
     * @Groups ("sorties")
     * @ORM\ManyToOne(targetEntity=Campus::class)
     * @ORM\JoinColumn(name="id_campus", nullable=false)
     */
    private $campus;

    /**
     * @Groups ("sorties")
     * @ORM\ManyToOne(targetEntity=Lieu::class)
     * @ORM\JoinColumn(name="id_lieu", nullable=false)
     */
    private $lieu;

    /**
     * @Groups ("sorties")
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sorties")
     * @ORM\JoinTable(name="users_sorties",
     *     joinColumns={ @ORM\JoinColumn(name="id_sortie", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="id_user", referencedColumnName="id") },
     * )
     */
    private $inscrits;

    /**
     * @Groups ("sorties")
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sortiesOrganises")
     * @ORM\JoinColumn(name="organisateur", nullable=false)
     */
    private $organisateur;

    /**
     * @Groups("sorties")
     */
    private $statut;

    public function __construct()
    {
        $this->inscrits = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): ?string {
        return $this->nom;
    }

    public function setNom(string $nom): self {
        $this->nom = $nom;
        return $this;
    }

    public function getDateHeureFin(): ?\DateTime {
        return date_add($this->dateHeureDebut, new DateInterval('PT'.$this->duree.'M'));
    }

    public function getDateHeureDebut(): ?\DateTime {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTime $dateHeureDebut): self {
        $this->dateHeureDebut = $dateHeureDebut;
        return $this;
    }

    public function getDuree(): ?int {
        return $this->duree;
    }

    public function setDuree(int $duree): self {
        $this->duree = $duree;
        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTime {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTime $dateLimiteInscription): self {
        $this->dateLimiteInscription = $dateLimiteInscription;
        return $this;
    }

    public function getNbInscriptionMax(): ?int {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): self {
        $this->nbInscriptionMax = $nbInscriptionMax;
        return $this;
    }

    public function getInfosSortie(): ?string {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): self {
        $this->infosSortie = $infosSortie;
        return $this;
    }

    public function getMotifAnnulation(): ?string {
        return $this->motifAnnulation;
    }

    public function setMotifAnnulation(?string $motifAnnulation): self {
        $this->motifAnnulation = $motifAnnulation;
        return $this;
    }

    public function getEtat(): ?Etat {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self {
        $this->etat = $etat;
        return $this;
    }

    public function getCampus(): ?Campus {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self {
        $this->campus = $campus;
        return $this;
    }

    public function getLieu(): ?Lieu {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self {
        $this->lieu = $lieu;
        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getInscrits(): Collection
    {
        return new ArrayCollection($this->inscrits->getValues());
    }

    public function addInscrit(User $inscrit): self
    {
        if (!$this->inscrits->contains($inscrit)) {
            $this->inscrits[] = $inscrit;
        }

        return $this;
    }

    public function removeInscrit(User $inscrit): self
    {
        $this->inscrits->removeElement($inscrit);
        return $this;
    }

    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?User $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getStatut() :string {
        $this->setStatut();
        return $this->statut;
    }


    private function setStatut() :void {
        $statut = 'toto';

        $statut = $this->etat->getLibelle() === 'created' ? SortieStatus::CREEE : $statut;

        if($this->etat->getLibelle() === 'published' ){
            //published et date limite d'inscription est dans le future => Ouverte
            if( date_diff(new DateTime('now'), $this->dateLimiteInscription)->invert === 0) {
                $statut = SortieStatus::OUVERTE;
            }

            //date limite d'inscription dans le passée => Fermée
            if( date_diff($this->dateLimiteInscription, new DateTime('now') )->invert === 0) {
                $statut = SortieStatus::FERME;
            }

            //date début < now < date de fin => En cours
            if( date_diff($this->getDateHeureDebut(), new DateTime('now'))->invert === 0
                && date_diff(new DateTime('now'), $this->getDateHeureFin())){
                $statut = SortieStatus::EN_COURS;
            }
        }


        //Date de fin dans le passée
        if(date_diff( $this->getDateHeureFin(), new DateTime('now') )->invert === 0){
            $statut = SortieStatus::PASSEE;
        }

        //En dernier contrôle d'une sortie annulée ou ( sortie créée avec et date d'inscription dépassée )
        if( $this->etat->getLibelle() === 'cancelled' ||
            ($this->etat->getLibelle() === 'created' &&
                date_diff( $this->getDateLimiteInscription(), new DateTime('now') )->invert === 0)
        ){
            $statut = SortieStatus::ANNULEE;
        }
        $this->statut = $statut;
    }
}
