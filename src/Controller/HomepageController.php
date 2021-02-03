<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\RechercheArticleFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }

    /**
     * @Route("/", name="search_articles")
     */
    public function search(Article $article): Response
    {
        $article = new Article();
        $searchForm = $this->createForm(RechercheArticleFormType::class, $article);

        return $this->render('homepage/index.html.twig', [
            'article' => $article,
            'searchForm'=>$searchForm->createView()
        ]);
    }
}
