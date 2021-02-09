<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Cookie;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier_show")
     */
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        // Récupération du cookie de gestion du panier si existant (sinon tableau vide)
        $panier_diginamic = [];
        if ($request->cookies->get('panier_diginamic')) {
            $cookie_panier = $request->cookies->get('panier_diginamic');
            $panier_diginamic = json_decode($cookie_panier, true);   // Décodage du panier (JSON)
        }

        // Construction du panier détaillé (avec les infos sur les articles)
        $panier_details = [];
        foreach($panier_diginamic as $id => $quantity) {
            $panier_details[] = [
                "article" => $articleRepository->find($id),
                "quantity" => $quantity
            ];
        }

        // Calcul du montant total
        $montant_panier = 0;
        $nb_total_articles = 0;
        foreach($panier_details as $article) {
            $montant_panier += $article["article"]->getPrix() * $article["quantity"];
            $nb_total_articles += $article["quantity"];
        }

        return $this->render('panier/index.html.twig', [
            'articles' => $panier_details,
            'total_panier' => $montant_panier,
            'total_articles' => $nb_total_articles
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="panier_add_article")
     */
    public function addArticle($id, Request $request) {
        // Création ou récupération du cookie de gestion du panier
        $panier_diginamic = $this->getCookiePanier($request);

        // Ajout de l'article
        if(!empty($panier_diginamic[$id])) {
            $panier_diginamic[$id]++;   // Ajout d'un exemplaire supplémentaire de l'article
        }
        else {
            $panier_diginamic[$id] = 1;   // Ajout d'un exemplaire de l'article
        }

        $this->addFlash(
            'success', 'Un exemplaire de l\'article a été ajouté au panier.'
        );

        // Mise à jour du cookie et redirection
        return $this->setCookiePanierAndRedirect($panier_diginamic, 'homepage');
    }

    /**
     * @Route("/panier/remove/{id}", name="panier_remove_article")
     */
    public function removeArticle($id, Request $request) {
        // Création ou récupération du cookie de gestion du panier
        $panier_diginamic = $this->getCookiePanier($request);

        // Suppression de l'article
        if(!empty($panier_diginamic[$id])) {
            unset($panier_diginamic[$id]);   // Suprresion de l'article
        }

        $this->addFlash(
            'success', 'L\'article a bien été supprimé du panier.'
        );

        // Mise à jour du cookie et redirection
        return $this->setCookiePanierAndRedirect($panier_diginamic, 'panier_show');
    }

    /**
     * @Route("/panier/remove-one/{id}", name="panier_remove_one_unity")
     */
    public function removeOneArticle($id, Request $request) {
        // Création ou récupération du cookie de gestion du panier
        $panier_diginamic = $this->getCookiePanier($request);

        if(!empty($panier_diginamic[$id])) {
            if($panier_diginamic[$id] > 1) $panier_diginamic[$id]--;   // Retrait d'un exemplaire
            if($panier_diginamic[$id] === 1) unset($panier_diginamic[$id]);   // Suppression de l'article
        }

        $this->addFlash(
            'success', 'Un exemplaire a bien été supprimé du panier.'
        );

        // Mise à jour du cookie et redirection
        return $this->setCookiePanierAndRedirect($panier_diginamic, 'panier_show');
    }

    /**
     * @Route("/panier/add-one/{id}", name="panier_add_one_unity")
     */
    public function addOneArticle($id, Request $request) {
        // Création ou récupération du cookie de gestion du panier
        $panier_diginamic = $this->getCookiePanier($request);

        if(!empty($panier_diginamic[$id])) $panier_diginamic[$id]++;   // Ajout d'un exemplaire

        $this->addFlash(
            'success', 'Un exemplaire a bien été ajouté au panier.'
        );

        // Mise à jour du cookie et redirection
        return $this->setCookiePanierAndRedirect($panier_diginamic, 'panier_show');
    }

    /**
     * @Route("/panier/delete", name="panier_delete")
     */
    public function deletePanier() {
        $this->addFlash(
            'success', 'Votre panier a été vidé.'
        );

        // Construction de la réponse du contrôleur
        $response = new Response();
        $response = $this->redirectToRoute('panier_show');
        // Suppression du cookie panier
        $response->headers->clearCookie('panier_diginamic');
        return $response;
    }

    // Factorisation : récupération des data du panier mis en cookie
    public function getCookiePanier(Request $request) {
        // Création ou récupération du cookie de gestion du panier
        if ($request->cookies->get('panier_diginamic')) {
            $cookie_panier = $request->cookies->get('panier_diginamic');
            $panier_diginamic = json_decode($cookie_panier, true);   // Décodage du panier (JSON)
        }
        else {
            $panier_diginamic = [];
        }
        return $panier_diginamic;
    }

    // Factorisation : mise à jour des data du panier mis en cookie et redirection
    public function setCookiePanierAndRedirect(array $panier_diginamic, string $route): Response {
        // Construction de la réponse du contrôleur
        $response = new Response();
        $response = $this->redirectToRoute($route);
        // Création (ou remplacement du cookie)
        $cookie_panier = Cookie::create('panier_diginamic')
            ->withValue(json_encode($panier_diginamic))
            ->withExpires(new \DateTime('+1 week'));
        $response->headers->setCookie($cookie_panier);
        return $response;
    }
}