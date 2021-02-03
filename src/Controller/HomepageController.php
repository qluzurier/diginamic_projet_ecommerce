<?php

namespace App\Controller;
use App\Entity\Article;
use App\Form\RechercheArticleFormType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Article $article,ArticleRepository $articleRepository,Request $request): Response
    {
        $offset= max(0,$request->query->geInt('offset',0)) ; 
        $paginator=$articleRepository->getArticleRepository($article,$offset) ; 
        // Elementd de FormType (!!! A SUPPRIMER !!!)
        // $article = new Article();
        // $searchForm = $this->createForm(RechercheArticleFormType::class, $article);
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
