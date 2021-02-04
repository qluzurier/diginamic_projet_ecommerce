<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/userlogin", name="user_login")
     */
    public function login(Request $request): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');



        return $this->render('account/user_login.html.twig', [
            "test" => $email,
        ]);
    }

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
}