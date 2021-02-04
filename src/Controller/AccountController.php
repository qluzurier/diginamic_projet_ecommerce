<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/newaccount", name="new_account")
     */
    public function createAccount(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUsername($user->getEmail());
            $user->setUsername($user->setRoles(["ROLE_USER"]));
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('account/newaccount.html.twig', [
            "form_new_user" => $form->createView()
        ]);
    }

    /**
     * @Route("/account", name="account")
     */
    public function showAccount(UserInterface $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager->persist($user);
        //     $entityManager->flush();
        //     return $this->redirectToRoute('homepage');
        // }
        return $this->render('account/showaccount.html.twig', [
            "form_user" => $form->createView()
        ]);
    }
}
