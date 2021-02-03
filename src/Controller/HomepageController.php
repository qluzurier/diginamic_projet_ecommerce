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
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $articleRepository->getListArticle($offset);
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            // 'article' => $article,
            'article' => $paginator,
            'previous' => $offset - ArticleRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::PAGINATOR_PER_PAGE),
            // 'article' => $article,
            // 'searchForm'=>$searchForm->createView()
        ]);
    }
}
