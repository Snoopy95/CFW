<?php

namespace App\Repository;

use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fournisseur[]    findAll()
 * @method Fournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Fournisseur[]    findByFamAndNuan($id familles, $id nuances)
 */
class FournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

     /**
      * @return Fournisseur[] Returns an array of Fournisseur objects
    */
    public function findByFamAndNuan($idf, $idn)
    {
        $qb = $this->createQueryBuilder('f')
            ->join('f.familles', 'x')
            ->addselect('x')
            ->join('f.nuances', 'w')
            ->addselect('w')
            ->andWhere('x.id = :val')
            ->andWhere('w.id = :val1')
            ->andWhere('f.statut = :val2')
            ->setParameter('val', $idf)
            ->setParameter('val1', $idn)
            ->setParameter('val2', "OK");

        return $qb->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Fournisseur
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
