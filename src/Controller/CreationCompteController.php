<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreationCompteController extends AbstractController
{
    /**
     * @Route("/creation/compte", name="creation_compte")
     */
    public function index(): Response
    {
        return $this->render('creation_compte/index.html.twig', [
            'controller_name' => 'CreationCompteController',
        ]);
    }
}
