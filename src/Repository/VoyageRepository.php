<?php
// src/Repository/VoyageRepository.php

namespace App\Repository;

use App\Entity\Voyage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VoyageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voyage::class);
    }

    public function findAllSortedByVilleDepart(): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.villeDepart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Autres méthodes du repository...
}
