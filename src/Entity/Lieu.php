<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 * @ORM\Table(name="lieux")
 * @UniqueEntity(fields={"nom"}, message="Ce lieu existe déjà.")
 */
class Lieu
{
    /**
     * @Groups (groups={"sorties", "lieux"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups (groups={"sorties", "lieux"})
     * @ORM\Column(name="nom", type="string", length=50)
     * @Assert\NotBlank(message="Le nom doit être renseigné")
     * @Assert\Regex (
     *     pattern="/[\p{L}\p{Ll}\p{Lu}\p{Z}]+/",
     *     message="Le nom ne peut contenir que des lettres"
     * )
     * @Assert\Length (
     *      min=5, minMessage="5 charactères minimum",
     *      max=50, maxMessage="50 charactères maximum"
     * )
     */
    private $nom;

    /**
     * @Groups (groups={"sorties", "lieux"})
     * @ORM\Column(name="rue", type="string", length=100, nullable=true)
     * @Assert\NotBlank(message="La rue doit être renseigné")
     * @Assert\Length (
     *      min=5, minMessage="5 charactères minimum",
     *      max=20, maxMessage="100 charactères maximum"
     * )
     */
    private $rue;

    /**
     * @Groups (groups={"sorties", "lieux"})
     * @ORM\Column(name="latitude", type="string", length=20, nullable=true)
     */
    private $latitude;

    /**
     * @Groups (groups={"sorties", "lieux"})
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
