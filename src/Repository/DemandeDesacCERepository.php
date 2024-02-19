<?php

namespace App\Repository;

use App\Entity\Compteep;
use App\Entity\DemandeDesacCE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeDesacCE>
 *
 * @method DemandeDesacCE|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeDesacCE|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeDesacCE[]    findAll()
 * @method DemandeDesacCE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeDesacCERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeDesacCE::class);
    }
    public function hasDemandeForCompteep(Compteep $compteep): bool
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.compteep = :compteep')
            ->setParameter('compteep', $compteep)
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }
//    /**
//     * @return DemandeDesacCE[] Returns an array of DemandeDesacCE objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DemandeDesacCE
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
