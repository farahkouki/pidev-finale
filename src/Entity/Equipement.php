<?php

// src/Entity/Equipement.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipementRepository")
 */
class Equipement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom de l'équipement ne peut pas être vide.")
     * @Assert\Length(max=255, maxMessage="Le nom de l'équipement ne peut pas dépasser {{ limit }} caractères.")
     */
    private $nomEquipe;

    /**
     * @ORM\Column(type="integer")
     */
    private $Nbr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Evenement", mappedBy="equipements")
     */
    private $evenements;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="equipement")
     */
    private $reclamation;

    // Getters and setters...

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getReclamation(): Reclamation
    {
        return $this->reclamation;
    }
    
    public function setReclamation(Reclamation $reclamation)
    {
        $this->reclamation = $reclamation;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nomEquipe;
    }

    public function setNomEquipe(string $nomEquipe): self
    {
        $this->nomEquipe = $nomEquipe;

        return $this;
    }

    public function getNbr(): ?int
    {
        return $this->Nbr;
    }

    public function setNbr(int $Nbr): self
    {
        $this->Nbr = $Nbr;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements()
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->addEquipement($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->contains($evenement)) {
            $this->evenements->removeElement($evenement);
            $evenement->removeEquipement($this);
        }

        return $this;
    }
}
