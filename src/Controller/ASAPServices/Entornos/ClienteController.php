<?php

namespace App\Controller\ASAPServices\Entornos;

use App\Entity\Persona;
use App\Form\ClienteType;
use App\Repository\PersonaRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ServicioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClienteController extends AbstractController
{
    #[Route('/cliente', name: 'app_asap_services_entornos_cliente_inicio')]
    public function verservicios(ServicioRepository $servicios): Response
    {
        return $this->render('asap_services/entornos/cliente/showservicios.html.twig', [
            'servicios' => $servicios->findAll()
        ]);
    }

    #[Route('/cliente/ajustes', name: 'app_asap_services_entornos_cliente_ajustes')]
    public function ajustes(PersonaRepository $personaRepository, UsuarioRepository $usuarioRepository): Response
    {
        $persona_aux = $this->getUser();
        $usuario = $usuarioRepository->findOneBy([
            'email' => $persona_aux->getUserIdentifier(),
        ]);
        $persona = $personaRepository->findOneBy([
            'usuario' => $usuario,
        ]);
        return $this->render('asap_services/entornos/cliente/configuracion.html.twig', [
            'persona' => $persona,
        ]);
    }

    #[Route('/cliente/{id}/editar', name: 'app_asap_services_entornos_cliente_editar')]
    public function edit(Request $request, Persona $persona, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(ClienteType::class, $persona);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $archivo = $form['p_foto']->getData();
            if ($archivo !== null) {
                $destino = $this->getParameter('kernel.project_dir') . '/public/img';
                $archivo->move($destino, $archivo->getClientOriginalName());
                $persona->setPFoto($archivo->getClientOriginalName());
            }
            $user = $persona->getUsuario();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('usuario')->get('password')->getData()
                )
            );
            $entityManager->flush();
        }
        return $this->render('asap_services/entornos/cliente/mi_perfil.html.twig', [
            'form' => $form,
            'persona' => $persona,

        ]);
    }

    #[Route('/cliente/verservicios/{id}', name: 'app_cliente_servprov')]
    public function verprov($id, ServicioRepository $servicios): Response
    {
        $servicio = $servicios->find($id);
        $proveedores = $servicio->getPersonas();
        return $this->render('asap_services/entornos/cliente/showproveedores.html.twig', [
            'proveedores' => $proveedores,
            'servicio' => $servicio
        ]);
    }

    #[Route('/cliente/verservicios/proveedor/{id}', name: 'app_cliente_provdetalle')]
    public function verprovdet($id, PersonaRepository $personas): Response
    {
        $proveedor = $personas->find($id);
        return $this->render('asap_services/entornos/cliente/detalleproveedor.html.twig', [
            'proveedor' => $proveedor,
        ]);
    }

    #[Route('/cliente/test', name: 'app_asap_services_entornos_cliente_test')]
    public function index(UsuarioRepository $usuarios): Response
    {
        $user = $this->getUser();
        $cliente = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $cliente->getIdPersona();
        echo $persona->getId();
        return $this->render('asap_services/entornos/cliente/index.html.twig', [
            'controller_name' => 'ClienteController',
            'aux_session' => $this->getUser(),
        ]);
    }

    // CREADO POR FRONTEND PARA VISUALIZAR LAS VISTAS - EKIMINAR SI ES NECESARIO
    #[Route('/cliente/verservicios', name: 'app_cliente_verservicios')]
    public function verservicios2(): Response
    {
        return $this->render('asap_services/entornos/cliente/showservicios.html.twig', [
            
        ]);
    }
    #[Route('/cliente/servicio_electricista', name: 'app_cliente_servicio_electricista')]
    public function servicio_electricista(): Response
    {
        return $this->render('asap_services/entornos/cliente/servicio_electricidad.html.twig', [
            
        ]);
    }
    #[Route('/cliente/servicio_gasfiteria', name: 'app_cliente_servicio_gasfiteria')]
    public function servicio_gasfiteria(): Response
    {
        return $this->render('asap_services/entornos/cliente/servicio_gasfiteria.html.twig', [
            
        ]);
    }
    #[Route('/cliente/servicios_menu', name: 'app_cliente_servicios_menu')]
    public function servicios_menu(): Response
    {
        return $this->render('asap_services/entornos/cliente/servicios_menu.html.twig', [
            
        ]);
    }
    #[Route('/cliente/aniadir_tarjeta', name: 'app_aniadir_tarjeta.html.twig')]
    public function aniadir_tarjeta(): Response
    {
        return $this->render('asap_services/entornos/cliente/aniadir_tarjeta.html.twig', [
            
        ]);
    }
    #[Route('/cliente/detalle_tarjeta', name: 'app_detalle_tarjeta.html.twig')]
    public function detalle_tarjeta(): Response
    {
        return $this->render('asap_services/entornos/cliente/detalle_tarjeta.html.twig', [
            
        ]);
    }
    #[Route('/cliente/saldos_pagos', name: 'app_saldos_pagos.html.twig')]
    public function saldos_pagos(): Response
    {
        return $this->render('asap_services/entornos/cliente/saldos_pagos.html.twig', [
            
        ]);
    }
    #[Route('/cliente/detalles_saldos_pagos', name: 'app_detalles_saldos_pagos.html.twig')]
    public function detalles_saldos_pagos(): Response
    {
        return $this->render('asap_services/entornos/cliente/detalles_saldos_pagos.html.twig', [
            
        ]);
    }
    
    
    // FIN
}
