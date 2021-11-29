<?php

namespace App\Repository;

use App\Entity\Backdossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Backdossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Backdossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Backdossier[]    findAll()
 * @method Backdossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackdossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Backdossier::class);
    }

    // /**
    //  * @return Backdossier[] Returns an array of Backdossier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Backdossier
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
