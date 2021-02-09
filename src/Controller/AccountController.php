<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormModifyType;
use App\Repository\CommandeRepository;
use App\Repository\DetailCommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function showAccount(UserInterface $user, Request $request, EntityManagerInterface $entityManager, CommandeRepository $commandeRepository): Response
    {
        // Gestion de la modification des informations du compte
        $form = $this->createForm(UserFormModifyType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success', 'Compte modifié avec succès !'
            );
            return $this->redirectToRoute('homepage');
        }

        // Gestion de l'historique des commandes
        $cmd_history = $commandeRepository->getOrdersList($this->getUser()->getId());
        //dd($cmd_history);

        return $this->render('account/showaccount.html.twig', [
            "form_modify_user" => $form->createView(),
            "commandes" => $cmd_history
        ]);
    }

    /**
     * @Route("/deleteaccount", name="delete_account")
     */
    public function deleteAccount(UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();
        $this->addFlash(
            'success', 'Votre compte a bien été supprimé'
        );
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/orderdetails/{id}", name="order_details")
     */
    public function showOrderDetails($id, UserInterface $user, CommandeRepository $commandeRepository, DetailCommandeRepository $detailCommandeRepository): Response
    {
        // Récupération des informations sur la commande
        $cmd_informations = $commandeRepository->find($id);

        // Récupération du détail de la commande
        $order_details = $detailCommandeRepository->getOrderDetails($id);
        //dd($order_details);

        // Calcul des sous-totaux
        $nb_articles = 0;
        $ss_total_avt_port = 0;
        foreach($order_details as $art) {
            $nb_articles += $art->getQuantite();
            $ss_total_avt_port += $art->gettotalArticle();
        }
        
        return $this->render('account/orderdetails.html.twig', [
            "commande_informations" => $cmd_informations,
            "details_commande" => $order_details,
            "nb_total_articles" => $nb_articles,
            "montant_total_articles" => $ss_total_avt_port            
        ]);
    }
    
}
