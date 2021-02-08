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

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $panier = $session->get("panier_diginamic", []);   // Récupération du panier (ou d'un tableau vide si panier vide)
        $panier_details = [];
        // Construction du récapitulatif panier
        foreach($panier as $id => $quantity) {
            $panier_details[] = [
                "article" => $articleRepository($id),
                "quantity" => $quantity
            ];
        }
        //dd($panier_details);

        // Calcul du montant total
        $frais_port = 6;
        $montant_panier = 0;
        $nb_total_articles = 0;
        foreach($panier_details as $article) {
            $montant_panier += $article["article"]->getPrix() * $article["quantity"];
            $nb_total_articles += $article["quantity"];
        }
        $montant_panier += $frais_port;
        $session->set("montant_panier", $montant_panier);

        // Si un utilisateur est connecté
        if ($this->getUser()) {
            return $this->render('payment/index.html.twig', [
                'articles' => $panier_details,
                'frais_port' => $frais_port,
                'total_panier' => $montant_panier,
                'total_articles' => $nb_total_articles,
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
    public function confirm(SessionInterface $session, EntityManagerInterface $entityManager, ArticleRepository $articleRepository): Response
    {
        // Ajout de la commande en BDD (dans la table "commande")
        $date_cmd = new DateTime(null, new DateTimeZone('Europe/Paris'));
        $entityManager = $this->getDoctrine()->getManager();
        
        $commande = new Commande();
        $commande->setUser($this->getUser());
        $commande->setDateCommande($date_cmd);
        $commande->setReference($date_cmd->format('YmdHis') . '-' . $this->getUser()->getId());
        $commande->setEtat("En préparation");
        $commande->setMontantTotal($session->get("montant_panier"));

        $entityManager->persist($commande);
        $entityManager->flush();

        // Ajout des articles de la commande en BDD (dans la table "detail_commande")
        $panier = $session->get("panier_diginamic");   // Récupération du panier

        foreach($panier as $id => $quantity) {
            $article_cmd = new DetailCommande();
            $article_cmd->setCommande($commande);
            $article_ref = $articleRepository->find($id);
            $article_cmd->setArticle($article_ref);
            $article_cmd->setQuantite($quantity);
            $article_cmd->setTotalArticle($article_ref->getPrix() * $quantity);

            $entityManager->persist($article_cmd);
            $entityManager->flush();
        }

        // Vidage du panier
        $session->remove("panier_diginamic");
        // Redirection vers la page de confirmation de commande
        return $this->render('payment/confirm.html.twig', []);
    }
}
