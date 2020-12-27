<?php

namespace App\Repository;

use App\Entity\Rex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rex|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rex|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rex[]    findAll()
 * @method Rex[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rex::class);
    }

    // /**
    //  * @return Rex[] Returns an array of Rex objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rex
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
