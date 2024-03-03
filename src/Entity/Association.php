<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Association
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom ne peut pas être vide")
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description ne peut pas être vide")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse ne peut pas être vide")
     */
    private $adresse;

   
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse email ne peut pas être vide")
     * @Assert\Email(message="L'adresse email '{{ value }}' n'est pas valide.")
     * @Assert\Length(max=255, maxMessage="L'adresse email ne peut pas dépasser {{ limit }} caractères.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=8)
     * @Assert\NotBlank(message="Le numéro de téléphone ne peut pas être vide")
     * @Assert\Regex(
     *     pattern="/^\d{8}$/",
     *     message="Le numéro de téléphone doit contenir exactement 8 chiffres"
     * )
     */
    private $numeroTelephone;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création ne peut pas être vide")
     */
    private $dateCreation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sponsor", mappedBy="association", orphanRemoval=true)
     */
    private $sponsors;
    

    public function __construct()
    {
        $this->sponsors = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

    public function getNumeroTelephone(): ?string
    {
        return $this->numeroTelephone;
    }

    public function setNumeroTelephone(string $numeroTelephone): self
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection|Sponsor[]
     */
    public function getSponsors(): Collection
    {
        return $this->sponsors;
    }

    public function addSponsor(Sponsor $sponsor): self
    {
        if (!$this->sponsors->contains($sponsor)) {
            $this->sponsors[] = $sponsor;
            $sponsor->setAssociation($this);
        }

        return $this;
    }

    public function removeSponsor(Sponsor $sponsor): self
    {
        if ($this->sponsors->removeElement($sponsor)) {
            if ($sponsor->getAssociation() === $this) {
                $sponsor->setAssociation(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
