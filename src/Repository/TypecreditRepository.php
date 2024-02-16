<?php

namespace App\Repository;

use App\Entity\Typecredit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typecredit>
 *
 * @method Typecredit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typecredit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typecredit[]    findAll()
 * @method Typecredit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypecreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typecredit::class);
    }

//    /**
//     * @return Typecredit[] Returns an array of Typecredit objects
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

//    public function findOneBySomeField($value): ?Typecredit
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
