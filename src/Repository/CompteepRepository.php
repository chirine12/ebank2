<?php

namespace App\Repository;

use App\Entity\Compteep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Compteep>
 *
 * @method Compteep|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compteep|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compteep[]    findAll()
 * @method Compteep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compteep::class);
    }
    public function searchByRib(string $rib)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.Rib = :Rib')
            ->setParameter('Rib', $rib)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Compteep[] Returns an array of Compteep objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Compteep
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
