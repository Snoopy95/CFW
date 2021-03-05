<?php

namespace App\Repository;

use App\Entity\Dossier;
use App\Entity\SearchIn;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Dossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dossier[]    findAll()
 * @method Dossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Dossier|null findByRef($value)
 */
class DossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dossier::class);
    }

    /**
     * @return Query
     */
    
    public function findByRef(SearchIn $search)
    {
        $query = $this->createQueryBuilder('d');
        if ($search->getInfield() == 'client') {
            $query = $query
                ->where('d.client LIKE :val')
                ->setParameter('val', '%' . $search->getSearchin() . '%');
        } elseif ($search->getInfield() == 'desigpiece') {
            $query = $query
                ->where('d.desigpiece LIKE :val')
                ->setParameter('val', '%' . $search->getSearchin() . '%');
        } elseif ($search->getInfield() == 'numdossier') {
            $query = $query
                ->where('d.numdossier LIKE :val')
                ->setParameter('val', '%' . $search->getSearchin() . '%');
        } else {
            $query = $query
                ->where('d.refpiece LIKE :val')
                ->setParameter('val', '%' . $search->getSearchin() . '%');
        }
            $query = $query
                ->orderBy('d.id', 'ASC')
                ->getQuery()
                ->getResult();

        return $query;
    }

    // /**
    //  * @return Dossier[] Returns an array of Dossier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dossier
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
