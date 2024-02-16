<?php

namespace App\Repository;

use App\Entity\Typetaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typetaux>
 *
 * @method Typetaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typetaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typetaux[]    findAll()
 * @method Typetaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypetauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typetaux::class);
    }

//    /**
//     * @return Typetaux[] Returns an array of Typetaux objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Typetaux
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
