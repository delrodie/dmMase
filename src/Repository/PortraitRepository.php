<?php

namespace App\Repository;

use App\Entity\Portrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Portrait|null find($id, $lockMode = null, $lockVersion = null)
 * @method Portrait|null findOneBy(array $criteria, array $orderBy = null)
 * @method Portrait[]    findAll()
 * @method Portrait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PortraitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Portrait::class);
    }
	
	/**
	 * @param $string
	 * @return float|int|mixed|string
	 */
	public function findByInstance($string)
	{
		return $this->createQueryBuilder('p')
			->addSelect('i')
			->leftJoin('p.instance', 'i')
			->where('i.libelle LIKE :str')
			->orderBy('p.ordre', 'ASC')
			->setParameter('str', '%'.$string.'%')
			->getQuery()->getResult()
			;
	}

    // /**
    //  * @return Portrait[] Returns an array of Portrait objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Portrait
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
