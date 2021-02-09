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

        $marque = $articleRepository->getListMarque();
        $marque_search = $request->query->get('marque_search','');

        $type = $articleRepository->getListType();
        $type_search = $request->query->get('type_search','');

        $genre = $articleRepository->getListGenre();
        $genre_search = $request->query->get('genre_search','');

        $prix = $articleRepository->getListPrix();
        $min_price = $request->query->getInt('min_price',);
        $max_price = $request->query->getInt('max_price',);


        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $articleRepository->getArticlePaginator($offset,$marque_search,$type_search,$genre_search,$min_price,$max_price);

        return $this->render('homepage/index.html.twig', [
            'marque_search' => $marque_search,
            'marques' => $marque,

            'type_search' => $type_search,
            'types' => $type,

            'genre_search' => $genre_search,
            'genres' => $genre,

            'min_price' => $min_price,
            'max_price' => $max_price,
            'prix' => $prix,

            'articles' => $paginator,
            'previous' => $offset - ArticleRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::PAGINATOR_PER_PAGE),
        ]);
    }

    /**
     * @Route("/article/{id}", name="detail_article")
     */
    public function showArticle(Article $articlce, Article $articleRepository): Response
    {
        $article = $articleRepository;
        return $this->render('homepag/showarticle.html.twig', [
            'article' => $article,
        ]);
    }
}
