<?php

namespace App\Controller;

use App\Repository\PersonaRepository;
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

    #[Route('/cliente/ver_prov/{id}', name: 'app_prov_detalle')]
    public function verprovdet($id, Request $request, PersonaRepository $personas): Response
    {
        $proveedor = $personas->find($id);

        return $this->render('cliente/detalleproveedor.html.twig', [
            'proveedor' => $proveedor,
            
        ]);
    }
}
