<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReclamationRepository")
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ message ne peut pas être vide")
     */
    private string $message;

    /**
     * @ORM\ManyToOne(targetEntity=Equipement::class, inversedBy="reclamation")
     */
    private $equipement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipement(): Equipement
    {
        return $this->equipement;
    }

    public function setEquipement(Equipement $equipement)
    {
        $this->equipement = $equipement;
    }
    
    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }
}
