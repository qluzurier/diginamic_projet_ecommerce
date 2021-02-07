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


    // Fonction getArticlePaginator FONCTIONNELLE !
    public function getArticlePaginator(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();

        return new Paginator($query);
    }
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
}
