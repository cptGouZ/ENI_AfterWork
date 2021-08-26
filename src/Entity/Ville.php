<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 * @ORM\Table(name="villes")
 * @UniqueEntity(fields="codePostal", message="Ce code postal existe déjà.")
 */
class Ville
{
    /**
     * @Groups("lieux")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("lieux")
     * @ORM\Column(name="nom", type="string", length=50)
     * @Assert\NotBlank(message="Le nom de la ville doit être renseigné.")
     * @Assert\Length(
     *     min=3, minMessage="Le nom de la ville doit être au minimum de 3 caractères",
     *     max=50, maxMessage="Le nom de la ville ne peut pas dépasser 50 caratères"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(name="code_postal", type="string", length=5, unique=true)
     * @Assert\NotBlank(message="Le code postal doit être renseigné.")
     * @Assert\Regex(pattern="#\d{5}#", message="Le code postal doit être constitué de 5 chiffres")
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity=Lieu::class, mappedBy="ville")
     */
    private $lieux;

    public function __construct() {
        $this->lieux = new ArrayCollection();
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

    public function getCodePostal(): ?string {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self {
        $this->codePostal = $codePostal;
        return $this;
    }

    /**
     * @return Collection|Lieu[]
     */
    public function getLieux(): Collection {
        return $this->lieux;
    }

    public function addLieux(Lieu $lieux): self {
        if (!$this->lieux->contains($lieux)) {
            $this->lieux[] = $lieux;
            $lieux->setVille($this);
        }
        return $this;
    }

    public function removeLieux(Lieu $lieux): self {
        if ($this->lieux->removeElement($lieux)) {
            // set the owning side to null (unless already changed)
            if ($lieux->getVille() === $this) {
                $lieux->setVille(null);
            }
        }
        return $this;
    }
}
