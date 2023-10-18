<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClienteController extends AbstractController
{
    #[Route('/cliente', name: 'app_cliente')]
    public function index(Request $request): Response
    {
        return $this->render('cliente/index.html.twig', [
            'controller_name' => 'ClienteController',
            'aux_session' => $this->getUser(), 
        ]);
    }
}
