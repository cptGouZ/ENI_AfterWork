<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Security;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 * @UniqueEntity(fields={"pseudo"}, message="Un compte possède déjà ce pseudo")
 * @UniqueEntity(fields={"email"}, message="Un compte possède déjà cet email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @Groups ("sorties")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id" , type="integer")
     */
    private $id;

    /**
     * @Groups ("sorties")
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank (message="Quel est ton pseudo ?")
     * @Assert\Length (
     *     max=50, maxMessage="Houlà c'est trop long !"
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(name="email" , type="string", length=100, unique=true)
     * @Assert\NotBlank (message="Veuillez renseigner le mot de passe")
     * @Assert\Length (
     *     max=100, maxMessage="Houlà c'est trop long !"
     * )
     * @Assert\Email(
     *     mode="loose",
     *     message="Le format de l'adresse mail est invalide",
     * )
     */
    private $email;

    /**
     * @ORM\Column(name="roles" , type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(name="password" , type="string")
     * @Assert\NotBlank
     * Assert\NotCompromisedPassword()
     */
    private $password;

    /**
     * @ORM\Column(name="nom" , type="string", length=50)
     * @Assert\NotBlank (message="Quel est ton nom ?")
     * @Assert\Length (
     *     max=50, maxMessage="Houlà c'est trop long !"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(name="prenom" , type="string", length=50)
     * @Assert\NotBlank (message="Quel est ton prenom ?")
     * @Assert\Length (
     *     max=50, maxMessage="Houlà c'est trop long !"
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(name="telephone" , type="string", length=20)
     * @Assert\NotBlank (message="Quel est ton pseudo ?")
     * @Assert\Positive (message="Il me faut des chiffres !")
     */
    private $telephone;

    /**
     * @ORM\Column(name="administrateur" , type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(name="actif" , type="boolean")
     */
    private $actif;


    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="inscrits")
     */
    private $sorties;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur")
     */
    private $sortiesOrganises;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="users")
     * @ORM\JoinColumn(name="Campus_id")
     */
    private $campus;



    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->sortiesOrganises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSortie(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->addInscrit($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            $sorty->removeInscrit($this);
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesOrganises(): Collection
    {
        return $this->sortiesOrganises;
    }

    public function addSortiesOrganise(Sortie $sortiesOrganise): self
    {
        if (!$this->sortiesOrganises->contains($sortiesOrganise)) {
            $this->sortiesOrganises[] = $sortiesOrganise;
            $sortiesOrganise->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortiesOrganise(Sortie $sortiesOrganise): self
    {
        if ($this->sortiesOrganises->removeElement($sortiesOrganise)) {
            // set the owning side to null (unless already changed)
            if ($sortiesOrganise->getOrganisateur() === $this) {
                $sortiesOrganise->setOrganisateur(null);
            }
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }


}
