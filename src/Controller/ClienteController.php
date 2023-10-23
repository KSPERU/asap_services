<?php

namespace App\Controller;

use App\Repository\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Persona;
use App\Form\ClienteType;
use App\Repository\UsuarioRepository;
use App\Repository\ServicioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClienteController extends AbstractController
{
    #[Route('/cliente', name: 'app_cliente')]
    public function index(UsuarioRepository $usuarios): Response
    {
        $user = $this->getUser();
        $cliente = $usuarios->findOneBy([
            'email'=>$user->getUserIdentifier(),
        ]);
        $persona = $cliente->getIdPersona();
        echo $persona->getId();
        return $this->render('cliente/index.html.twig', [
            'controller_name' => 'ClienteController',
            'aux_session' => $this->getUser(), 
        ]);
    }

    #[Route('/cliente/verservicios', name: 'app_cliente_serv')]
    public function verservicios(ServicioRepository $servicios): Response
    {
        return $this->render('cliente/showservicios.html.twig', [
            'servicios' => $servicios->findAll()
        ]);
    }

    #[Route('/cliente/verservicios/{id}', name: 'app_cliente_servprov')]
    public function verprov($id, ServicioRepository $servicios): Response
    {
        $servicio = $servicios->find($id);
        $proveedores = $servicio->getPersonas();
        return $this->render('cliente/showproveedores.html.twig', [
            'proveedores' => $proveedores,
            'servicio' => $servicio
        ]);
    }

    #[Route('/cliente/verservicios/proveedor/{id}', name: 'app_cliente_provdetalle')]
    public function verprovdet($id, PersonaRepository $personas): Response
    {
        $proveedor = $personas->find($id);
        return $this->render('cliente/detalleproveedor.html.twig', [
            'proveedor' => $proveedor,
        ]);
    }

    #[Route('/cliente/{id}/edit', name: 'app_cliente_edit')]
    public function edit(Request $request, Persona $persona, EntityManagerInterface $entityManager, UsuarioRepository $usuarios): Response
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
