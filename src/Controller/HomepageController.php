<?php

namespace App\Controller;


use App\Entity\Article;
use App\Form\RechercheArticleFormType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        $prix = $articleRepository->getListPrix();
        $prix_search = '';
        $genre = $articleRepository->getListGenre();
        $genre_search = '';

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $articleRepository->getArticlePaginator($offset);

        return $this->render('homepage/index.html.twig', [
            'prix_search' => $prix_search,
            'prix' => $prix,
            'genre_search' => $genre_search,
            'genres' => $genre,
            'controller_name' => 'HomepageController',
            'articles' => $paginator,
            'previous' => $offset - ArticleRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::PAGINATOR_PER_PAGE),
        ]);
    }

    /**
     * @Route("/article/{id}", name="detail_article")
     */
    //     public function showArticle( Article $articlce ): Response
    //     {
    //         return $this->render('detailsarticle/detailart.html.twig', [
    //             'article' => $article , 
    //         ]);
    //     }
}
