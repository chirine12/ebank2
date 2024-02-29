<?php
// src/Repository/ClientRepository.php
// src/Repository/ClientRepository.php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function getClientWithComptecourant(?int $clientId): ?Client
    {
        if (!$clientId) {
            return null;
        }

        return $this->createQueryBuilder('c')
            ->leftJoin('c.comptecourant', 'cc')
            ->addSelect('cc')
            ->andWhere('c.id = :clientId')
            ->setParameter('clientId', $clientId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
