<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

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
                "article" => $articleRepository->find($id),
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
    public function confirm(SessionInterface $session): Response
    {
        $session->remove("panier_diginamic");
        return $this->render('payment/confirm.html.twig', []);
    }
}
