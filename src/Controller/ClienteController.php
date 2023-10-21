<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\ClienteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/cliente/{id}/edit', name: 'app_cliente_edit')]
    public function edit(Request $request, Persona $persona, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClienteType::class, $persona);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush(); 
        }
        return $this->render('cliente/edit.html.twig', [
            'form' => $form,
            'persona'=>$persona,
            
        ]);
    }
}
