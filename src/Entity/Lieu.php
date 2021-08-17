<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 * @ORM\Table(name="lieux")
 * @UniqueEntity(fields={"nom"}, message="Ce lieu existe déjà.")
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(name="rue", type="string", length=100, nullable=true)
     */
    private $rue;

    /**
     * @ORM\Column(name="latitude", type="string", length=20, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(name="longitude", type="string", length=20, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="lieux", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id_ville")
     */
    private $ville;

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

    public function getRue(): ?string {
        return $this->rue;
    }

    public function setRue(?string $rue): self {
        $this->rue = $rue;
        return $this;
    }

    public function getLatitude(): ?string {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?string {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self {
        $this->longitude = $longitude;
        return $this;
    }

    public function getVille(): ?Ville {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self {
        $this->ville = $ville;
        return $this;
    }
}
