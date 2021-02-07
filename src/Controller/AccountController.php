<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormModifyType;
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
    public function showAccount(UserInterface $user, Request $request, EntityManagerInterface $entityManager): Response
    {
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
        return $this->render('account/showaccount.html.twig', [
            "form_modify_user" => $form->createView()
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
}
