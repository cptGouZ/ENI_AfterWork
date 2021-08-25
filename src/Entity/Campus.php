<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 * @ORM\Table(name="campus")
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
     * @ORM\Column(name="nom" , type="string", length=50)
     * @Assert\Regex (
     *     pattern="/[a-zA-Z]+/",
     *     match=false,
     *     message="Le nom ne peut contenir que des lettres"
     * )
     * @Assert\NotBlank(message="Le nom doit être renseigné")
     * @Assert\Length (
     *      min=5, minMessage="5 charactères minimum",
     *      max=50, maxMessage="50 charactères maximum"
     * )
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="campus")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }



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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCampus($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCampus() === $this) {
                $user->setCampus(null);
            }
        }

        return $this;
    }

}
