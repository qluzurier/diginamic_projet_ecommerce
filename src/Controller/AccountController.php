<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Form\UserFormModifyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/newaccount", name="new_account")
     */
    public function createAccount(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUsername($user->getEmail());
            $user->setUsername($user->setRoles(["ROLE_USER"]));
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
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
        $form = $this->createForm(UserFormModifyType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('account/showaccount.html.twig', [
            "form_modify_user" => $form->createView()
        ]);
    }

    /**
     * @Route("/changepassword", name="password")
     */
    public function changePassword(UserInterface $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('account/password.html.twig', [
            "test" => "tets"
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
        return $this->redirectToRoute('homepage');
    }
}
