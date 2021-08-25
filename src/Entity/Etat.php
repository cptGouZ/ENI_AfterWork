<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Groups ("sorties")
     * @ORM\Column(name="libelle", type="string", length=20, nullable=true)
     * @Assert\NotBlank(message="L'état doit être renseigné")
     * @Assert\Regex (
     *     pattern="/[a-zA-Z]+/",
     *     match=false,
     *     message="Le libelle ne peut contenir que des lettres"
     * )
     * @Assert\Length (
     *      min=5, minMessage="5 charactères minimum",
     *      max=20, maxMessage="20 charactères maximum"
     * )
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
