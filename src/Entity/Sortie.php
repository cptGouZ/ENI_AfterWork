<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
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
     * @ORM\Column(name="duree", type="time")
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
    private $etat;

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
}
