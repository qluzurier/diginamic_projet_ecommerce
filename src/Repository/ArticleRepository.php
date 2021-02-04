<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public const PAGINATOR_PER_PAGE = 10;

    public function getArticlePaginator(int $offset ): Paginator
    {
    $query = $this->createQueryBuilder ('p')
    ->setMaxResults( self::PAGINATOR_PER_PAGE)
    ->setFirstResult ($offset)
    ->getQuery();

    return new Paginator($query);
}


   


    // public function getArticlePaginator( int $offset): Paginator
    // {
    //     $prix = [] ;
    //     $query = $this->createQueryBuilder('c')
    //         ->select('c.prix') 
    //         ->setMaxResults(self::PAGINATOR_PER_PAGE)
    //         ->setFirstResult($offset)
    //         ->getQuery();
    //     return new Paginator($query);
    // }


    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
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
