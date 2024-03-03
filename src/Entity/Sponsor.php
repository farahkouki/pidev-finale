<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"nom"}, message="Ce nom est déjà utilisé.")
 */
class Sponsor
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
 * @Assert\Length(
 *     max=255,
 *     maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères. Veuillez saisir un nom plus court."
 * )
 */
private $nom;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="L'adresse ne peut pas dépasser {{ limit }} caractères.")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse email ne peut pas être vide")
     * @Assert\Email(message="L'adresse email '{{ value }}' n'est pas valide.")
     * @Assert\Length(max=255, maxMessage="L'adresse email ne peut pas dépasser {{ limit }} caractères.")
     */
    private $mail;
/**
 * @ORM\Column(type="string", length=20)
 * @Assert\NotBlank(message="Le numéro de téléphone ne peut pas être vide")
 * @Assert\Regex(
 *     pattern="/^\d{8}$/",
 *     message="Le numéro de téléphone doit contenir exactement 8 chiffres et ne doit pas contenir d'autres caractères."
 * )
 */
private $telephone;



    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création ne peut pas être vide")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Association", inversedBy="sponsors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $association;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

        return $this;
    }
}
