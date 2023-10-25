<?php

namespace App\Controller\ASAPServices\Ajustes;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArranqueController extends AbstractController
{
    #[Route('/', name: 'app_asap_services_ajustes_arranque')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_asap_services_general_inicio');
    }
}
