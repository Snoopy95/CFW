<?php

namespace App\Repository;

use App\Entity\AppelOffre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppelOffre|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppelOffre|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppelOffre[]    findAll()
 * @method AppelOffre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method AppelOffre[]    findAo()
 */
class AppelOffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppelOffre::class);
    }

    /**
    * @return AppelOffre Returns uniquement les statut wait et sending
    */
    public function findAo(): ?Array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.statut = :val')
            ->orWhere('a.statut = :val2')
            ->setParameter('val', 'wait')
            ->setParameter('val2', 'sending')
            ->orderBy('a.statut', 'ASC')
            ->addOrderBy('a.datecreat', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?AppelOffre
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
