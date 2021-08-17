<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 * @UniqueEntity(fields={"nom"}, message={"Le nom du campus existe déjà !"})
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id" , type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nom" , type="string", length=150)
     */
    private $nom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
}
