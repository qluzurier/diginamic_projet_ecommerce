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

    public const PAGINATOR_PER_PAGE = 12;

    //n getArticlePaginator ESSAI, NON FONCTIONNEL, NON TERMINE !
    public function getArticlePaginator(int $offset, string $marque = '', string $type_search = '', string $genre = '', string $min_price = '', $max_price = ''): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('c');
        if ($marque) {
            $queryBuilder = $queryBuilder
                ->andWhere('c.marque = :marque')
                ->setParameter('marque', $marque);
        }
        if ($type_search) {
            $queryBuilder = $queryBuilder
                ->andWhere('c.type = :type')
                ->setParameter('type', $type_search);
        }
        if ($genre) {
            $queryBuilder = $queryBuilder
                ->andWhere('c.genre = :genre')
                ->setParameter('genre', $genre);
        }
        if ($min_price || $max_price) {
            $queryBuilder = $queryBuilder
                ->andWhere('c.prix > :min_price')
                ->setParameter('min_price', $min_price)
                ->andWhere('c.prix < :max_price')
                ->setParameter('max_price', $max_price);
        }
     


        $query = $queryBuilder
            ->orderBy('c.prix', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }


    public function getListMarque()
    {
        $marque = [];
        foreach ($this->createQueryBuilder('a')
            ->select('a.marque')
            ->distinct(true)->orderBy('a.marque', 'ASC')
            ->getQuery()
            ->getResult() as $cols) {
            $marque[] = $cols['marque'];
        }
        return $marque;
    }

    public function getListType()
    {
        $type = [];
        foreach ($this->createQueryBuilder('a')
            ->select('a.type')
            ->distinct(true)->orderBy('a.type', 'ASC')
            ->getQuery()
            ->getResult() as $cols) {
            $type[] = $cols['type'];
        }
        return $type;
    }

    public function getListGenre()
    {
        $genre = [];
        foreach ($this->createQueryBuilder('a')
            ->select('a.genre')
            ->distinct(true)->orderBy('a.genre', 'ASC')
            ->getQuery()
            ->getResult() as $cols) {
            $genre[] = $cols['genre'];
        }
        return $genre;
    }


    // A CHANGER POUR OBTENIR PRIX MIN PUIS MAX
    public function getListPrix()
    {
        $prix = [];
        foreach ($this->createQueryBuilder('a')
            ->select('a.prix')
            ->distinct(true)->orderBy('a.prix', 'ASC')
            ->getQuery()->getResult() as $cols) {
            $prix[] = $cols['prix'];
        }
        return $prix;
    }
}
