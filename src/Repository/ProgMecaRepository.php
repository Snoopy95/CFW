<?php

namespace App\Repository;

use App\Entity\ProgMeca;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProgMeca|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgMeca|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgMeca[]    findAll()
 * @method ProgMeca[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method ProgMeca|null findByNumprog(numprog)
 * @method ProgMeca[]    findInProg($value, array $filtre)
 */
class ProgMecaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgMeca::class);
    }

    // /**
    //  * @return ProgMeca[] Returns an array of ProgMeca objects
    //  */
    
    public function findByNumprog($value)
    {
        return $this->createQueryBuilder('p')
            ->Where('p.numprog LIKE :val')
            ->setParameter('val', $value.'%')
            ->orderBy('p.numprog', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findInProg($value, $filtre): ?Array
    {
        $query = $this->createQueryBuilder('p')
            ->orWhere('p.refpiece LIKE :val')
            ->orWhere('p.desigpiece LIKE :val')
            ->orWhere('p.numprog LIKE :val')
            ->setParameter('val', '%'.$value.'%')
        ;
        if ($filtre) {
            if ($filtre['client']) {
                $client = $filtre['client'];
                $query = $query
                ->andwhere('p.client = :client')
                ->setParameter('client', $client);
            }
            if ($filtre['machine']) {
                $machine = $filtre['machine'];
                $query = $query
                ->andWhere('p.typemachine = :machine')
                ->setParameter('machine', $machine);
            }
        }
        $query = $query
            ->orderBy('p.datecreat', 'DESC')
            ->getQuery()
            ->getResult();
        return $query
        ;
    }
    /*
    public function findOneBySomeField($value): ?ProgMeca
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
