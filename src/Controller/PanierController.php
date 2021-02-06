<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier_show")
     */
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $panier = $session->get("panier_diginamic", []);   // Récupération du panier (ou d'un tableau vide si panier vide)
        $panier_details = [];
        // Construction du panier détaillé (avec les infos sur les articles)
        foreach($panier as $id => $quantity) {
            $panier_details[] = [
                "article" => $articleRepository->find($id),
                "quantity" => $quantity
            ];
        }
        //dd($panier_details);
        return $this->render('panier/index.html.twig', [
            'articles' => $panier_details
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="panier_add_article")
     */
    public function addArticle($id, SessionInterface $session) {
        $panier = $session->get("panier_diginamic", []);   // Récupération de la variable de session "panier_diginamic" ou création d'un tableau si inexistant
        if(!empty($panier[$id])) {
            $panier[$id]++;   // Ajout d'un exemplaire supplémentaire de l'article
        }
        else {
            $panier[$id] = 1;   // Ajout d'un exemplaire de l'article
        }
        $session->set('panier_diginamic', $panier);   // Mise à jour du panier
        //dd($session->get('panier_diginamic'));
        $this->addFlash(
            'success', 'Un exemplaire de l\'article a été ajouté au panier.'
        );
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/panier/remove/{id}", name="panier_remove_article")
     */
    public function removeArticle($id, SessionInterface $session) {
        $panier = $session->get("panier_diginamic", []);   // Récupération de la variable de session "panier_diginamic"
        if(!empty($panier[$id])) {
            unset($panier[$id]);   // Suprresion de l'article
        }
        $session->set('panier_diginamic', $panier);   // Mise à jour du panier
        //dd($session->get('panier_diginamic'));
        $this->addFlash(
            'success', 'L\'article a bien été supprimé du panier.'
        );
        return $this->redirectToRoute('panier_show');
    }

    /**
     * @Route("/panier/remove-one/{id}", name="panier_remove_one_unity")
     */
    public function removeOneArticle($id, SessionInterface $session) {
        $panier = $session->get("panier_diginamic", []);   // Récupération de la variable de session "panier_diginamic"
        if(!empty($panier[$id])) {
            if($panier[$id] > 1) $panier[$id]--;   // Retrait d'un exemplaire
            if($panier[$id] === 1) unset($panier[$id]);   // Suppression de l'article
        }

        $session->set('panier_diginamic', $panier);   // Mise à jour du panier
        //dd($session->get('panier_diginamic'));
        $this->addFlash(
            'success', 'Un exemplaire a bien été supprimé du panier.'
        );
        return $this->redirectToRoute('panier_show');
    }

    /**
     * @Route("/panier/add-one/{id}", name="panier_add_one_unity")
     */
    public function addOneArticle($id, SessionInterface $session) {
        $panier = $session->get("panier_diginamic", []);   // Récupération de la variable de session "panier_diginamic"
        if(!empty($panier[$id])) $panier[$id]++;   // Ajout d'un exemplaire
        $session->set('panier_diginamic', $panier);   // Mise à jour du panier
        //dd($session->get('panier_diginamic'));
        $this->addFlash(
            'success', 'Un exemplaire a bien été ajouté au panier.'
        );
        return $this->redirectToRoute('panier_show');
    }
}