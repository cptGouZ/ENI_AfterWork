<?php

namespace App\Entity;

use App\Enums\SortieStatus;
use App\Repository\SortieRepository;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 * @ORM\Table(name="sorties")
 * @UniqueEntity(fields={"date_heure_debut", "nom"}, message="Un évènement existe déjà avec ce nom et à cette date")
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nom", type="string", length=30)
     */
    private $nom;

    /**
     * @ORM\Column(name="date_heure_debut", type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(name="duree", type="integer")
     */
    private $duree;

    /**
     * @ORM\Column(name="date_limite_inscription", type="datetime")
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(name="nb_inscription_max", type="integer")
     */
    private $nbInscriptionMax;

    /**
     * @ORM\Column(name="infos_sortie", type="text", nullable=true)
     */
    private $infosSortie;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class)
     * @ORM\JoinColumn(name="id_etat", nullable=false)
     */
    private Etat $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class)
     * @ORM\JoinColumn(name="id_campus", nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class)
     * @ORM\JoinColumn(name="id_lieu", nullable=false)
     */
    private $lieu;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sorties")
     * @ORM\JoinTable(name="users_sorties",
     *     joinColumns={ @ORM\JoinColumn(name="id_sortie", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="id_user", referencedColumnName="id") },
     * )
     */
    private $inscrits;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sortiesOrganises")
     * @ORM\JoinColumn(name="organisateur", nullable=false)
     */
    private $organisateur;

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

    public function getDateHeureFin(): ?\DateTimeInterface {
        return date_add($this->dateHeureDebut, new DateInterval('PT'.$this->duree.'M'));
    }

    public function getDateHeureDebut(): ?\DateTimeInterface {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self {
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

    public function getDateLimiteInscription(): ?\DateTimeInterface {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self {
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
        return $this->inscrits;
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
        $statut = '';

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
        return $statut;
    }
}
