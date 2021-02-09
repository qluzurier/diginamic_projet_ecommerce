<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Repository\ArticleRepository;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function index(SessionInterface $session, Request $request, ArticleRepository $articleRepository): Response
    {
        // Récupération du cookie de gestion du panier
        if ($request->cookies->get('panier_diginamic')) {
            $cookie_panier = $request->cookies->get('panier_diginamic');
            $panier_diginamic = json_decode($cookie_panier, true);   // Décodage du panier (JSON)
        }
        else {
            $panier_diginamic = [];
        }

        $panier_details = [];
        // Construction du récapitulatif panier
        foreach($panier_diginamic as $id => $quantity) {
            $panier_details[] = [
                "article" => $articleRepository->find($id),
                "quantity" => $quantity
            ];
        }

        // Calcul du montant
        $montant_panier = 0;
        $nb_total_articles = 0;
        foreach($panier_details as $article) {
            $montant_panier += $article["article"]->getPrix() * $article["quantity"];
            $nb_total_articles += $article["quantity"];
        }

        $frais_port = $this->getParameter("frais_port");   // Utilisation de la variable globale définie dans services.yaml

        $session->set("montant_total_cmd", $montant_panier + $frais_port);

        // Si un utilisateur est connecté
        if ($this->getUser()) {
            return $this->render('payment/index.html.twig', [
                'articles' => $panier_details,
                'frais_port' => $frais_port,
                'mt_total_panier' => $montant_panier,
                'nb_total_articles' => $nb_total_articles,
                'mt_total_cmd' => $montant_panier + $frais_port
            ]);
        }
        
        // Si aucun utilisateur connecté
        else  {
            $this->addFlash(
                'success', 'Veuillez vous connecter ou créer un compte pour passer commande.'
            );
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/confirm", name="order_confirmation")
     */
    public function confirm(SessionInterface $session, Request $request, EntityManagerInterface $entityManager, ArticleRepository $articleRepository): Response
    {
        // Ajout de la commande en BDD (dans la table "commande")
        $date_cmd = new DateTime(null, new DateTimeZone('Europe/Paris'));
        $entityManager = $this->getDoctrine()->getManager();
        
        $commande = new Commande();
        $commande->setUser($this->getUser());
        $commande->setDateCommande($date_cmd);
        $commande->setReference($date_cmd->format('YmdHis') . '-' . $this->getUser()->getId());
        $commande->setEtat("En préparation");
        $commande->setMontantTotal($session->get("montant_total_cmd"));

        $entityManager->persist($commande);
        $entityManager->flush();

        // Récupération du cookie de gestion du panier
        if ($request->cookies->get('panier_diginamic')) {
            $cookie_panier = $request->cookies->get('panier_diginamic');
            $panier_diginamic = json_decode($cookie_panier, true);   // Décodage du panier (JSON)
        }
        else {
            $panier_diginamic = [];
        }

        // Ajout des articles de la commande en BDD (dans la table "detail_commande")
        foreach($panier_diginamic as $id => $quantity) {
            $article_cmd = new DetailCommande();
            $article_cmd->setCommande($commande);
            $article_ref = $articleRepository->find($id);
            $article_cmd->setArticle($article_ref);
            $article_cmd->setQuantite($quantity);
            $article_cmd->setTotalArticle($article_ref->getPrix() * $quantity);

            $entityManager->persist($article_cmd);
            $entityManager->flush();
        }

        // Construction de la réponse du contrôleur
        $response = new Response();
        // Redirection vers la page de confirmation de commande
        $response = $this->render('payment/confirm.html.twig', []);
        // Suppression du cookie panier (vidage du panier)
        $response->headers->clearCookie('panier_diginamic');
        return $response;
    }
}
