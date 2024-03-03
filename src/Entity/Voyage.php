<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoyageRepository")
 */
class Voyage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ ville de départ ne peut pas être vide")
     */
    private $villeDepart;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ destination ne peut pas être vide")
     */
    private $destination;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank(message="Le champ heure de départ ne peut pas être vide")
     */
    private $heureDepart;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank(message="Le champ heure d'arrivée ne peut pas être vide")
     */
    private $heureArrivee;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ type de voyage ne peut pas être vide")
     */
    private $typeVoyage;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ type ne peut pas être vide")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="voyage")
     */
    private $reservations;

   
    public function __construct()
    {
        $this->activites = [];
        $this->reservations = new ArrayCollection();
       
        // Autres initialisations...
    }

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $activites;

   

    public function getActivites(): ?array
    {
        return $this->activites;
    }

    public function setActivites(?array $activites): self
    {
        $this->activites = $activites;
        return $this;
    }

    public function __toString()
{
    if (is_array($this->destination)) {
        // Si destination est un tableau, convertissez-le en chaîne de caractères en le joignant avec une virgule
        return $this->villeDepart . ' - ' . implode(', ', $this->destination);
    } else {
        // Sinon, retournez simplement la valeur de la propriété destination
        return $this->villeDepart . ' - ' . $this->destination;
    }
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVilleDepart(): ?string
    {
        return $this->villeDepart;
    }

    public function setVilleDepart(string $villeDepart): self
    {
        $this->villeDepart = $villeDepart;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getHeureDepart(): ?\DateTimeInterface
    {
        return $this->heureDepart;
    }

    public function setHeureDepart(\DateTimeInterface $heureDepart): self
    {
        $this->heureDepart = $heureDepart;

        return $this;
    }

    public function getHeureArrivee(): ?\DateTimeInterface
    {
        return $this->heureArrivee;
    }

    public function setHeureArrivee(\DateTimeInterface $heureArrivee): self
    {
        $this->heureArrivee = $heureArrivee;

        return $this;
    }

    public function getTypeVoyage(): ?string
    {
        return $this->typeVoyage;
    }

    public function setTypeVoyage(string $typeVoyage): self
    {
        $this->typeVoyage = $typeVoyage;

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
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setVoyage($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getVoyage() === $this) {
                $reservation->setVoyage(null);
            }
        }

        return $this;
    }
}