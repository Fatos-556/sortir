<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Table(name="PARTICIPANTS")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository", repositoryClass=UserRepository::class)
 * UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Images", mappedBy="User", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setUser($this);
        }

        return $this;
    }

public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getUser() === $this) {
                $image->setUser(null);
            }
        }

        return $this;
    }


    /**
     *toString
     *@return string
     */
    public function __toString(): string
    {
        return $this->getId();
    }





















    /**
     * @Assert\NotBlank(message="Merci de remplir ce champ")
     * @ORM\Column (type="string", length=30)
     */
    private $pseudo;

    /**
     * @Assert\NotBlank(message="Merci de remplir ce champ")
     * @ORM\Column (type="string", length=30)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="Merci de remplir ce champ")
     * @ORM\Column (type="string", length=30)
     */
    private $prenom;


    /**
     * @ORM\Column (type="integer", length=15)
     */
    private $telephone;

    /**
     * @ORM\Column (type="string", length=255)
     */
    private $email;

    /**
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit contenir au moins 8 caractères")
     * @ORM\Column (type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column (type="integer", nullable=true)
     */
    private $administrateur = false;

    /**
     * @ORM\Column (type="integer", nullable=true)
     */
    private $actif;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = ["ROLE_USER"];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="users")
     */
    private $campus;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="organisateur", cascade="remove")
     */
    private $sortiesOrganisees;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="membresInscrits", cascade="remove")
     */
    private $inscriptionsSortie;

    public function __construct()
    {
        $this->sortiesOrganisees = new ArrayCollection();
        $this->inscriptionsSortie = new ArrayCollection();

        $this->images = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isAdministrateur(): bool
    {
        return $this->administrateur;
    }

    /**
     * @param bool $administrateur
     */
    public function setAdministrateur(bool $administrateur): void
    {
        $this->administrateur = $administrateur;
    }

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     */
    public function setActif($actif): void
    {
        $this->actif = $actif;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getCampus()
    {
        return $this->campus;
    }

    /**
     * @param mixed $campus
     */
    public function setCampus($campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return ArrayCollection
     */
    public function getSortiesOrganisees(): ArrayCollection
    {
        return $this->sortiesOrganisees;
    }

    /**
     * @param ArrayCollection $sortiesOrganisees
     */
    public function setSortiesOrganisees(ArrayCollection $sortiesOrganisees): void
    {
        $this->sortiesOrganisees = $sortiesOrganisees;
    }

    /**
     * @return ArrayCollection
     */
    public function getInscriptionsSortie(): ArrayCollection
    {
        return $this->inscriptionsSortie;
    }

    /**
     * @param ArrayCollection $inscriptionsSortie
     */
    public function setInscriptionsSortie(ArrayCollection $inscriptionsSortie): void
    {
        $this->inscriptionsSortie = $inscriptionsSortie;
    }







    //inutile pour nous
    public function getSalt() {return null;}
    //inutile pour nous
    public function eraseCredentials(){
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->pseudo;
    }


}
