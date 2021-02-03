<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/connection", name="connection")
     */
    public function connection(Request $request): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        return $this->render('account/connection.html.twig', [
            "test" => $email,
        ]);
    }

    /**
     * @Route("/newaccount", name="new_account")
     */
    public function createAccount(Request $request): Response
    {
        return $this->render('account/newaccount.html.twig', [
            "test" => "creation compte",
        ]);
    }
}

    /**
     * @Route("/newaccount", name="new_account")
     */
    // public function index(): Response
    // {
    //     return $this->render('account/index.html.twig', [
    //         'controller_name' => 'AccountController',
    //     ]);
    // }
// }