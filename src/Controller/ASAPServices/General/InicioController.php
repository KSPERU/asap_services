<?php

namespace App\Controller\ASAPServices\General;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends AbstractController
{
    #[Route('/inicio', name: 'app_asap_services_general_inicio')]
    public function index(): Response
    {
        return $this->render('asap_services/general/inicio/index.html.twig');
    }
}
