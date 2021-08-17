<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 * @ORM\Table(name="etats")
 * @UniqueEntity(fields={"libelle"}, message={"Ce status existe déjà !"})
 */
class Etat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="libelle", type="string", length=20, nullable=true)
     */
    private $libelle;

    public function getId(): ?int {
        return $this->id;
    }

    public function getLibelle(): ?string {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self {
        $this->libelle = $libelle;
        return $this;
    }
}
