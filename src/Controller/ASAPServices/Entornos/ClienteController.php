<?php

namespace App\Controller\ASAPServices\Entornos;

use App\Entity\Persona;
use App\Entity\Tarjeta;
use App\Form\ClienteType;
use App\Entity\Calificacion;
use App\Entity\Conversacion;
use App\Entity\Participante;
use App\Form\ConversacionType;
use App\Form\TarjetaType;
use App\Repository\ConversacionRepository;
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
    public function verservicios(PersonaRepository $personaRepository, UsuarioRepository $usuarioRepository, ServicioRepository $servicios): Response
    {
        $persona_aux = $this->getUser();
        $usuario = $usuarioRepository->findOneBy([
            'email' => $persona_aux->getUserIdentifier(),
        ]);
        $persona = $personaRepository->findOneBy([
            'usuario' => $usuario,
        ]);
        return $this->render('asap_services/entornos/cliente/showservicios.html.twig', [
            'servicios' => $servicios->findAll(),
            'persona' => $persona
        ]);
    }

    #[Route('/cliente/verservicios/{id}', name: 'app_asap_services_entornos_cliente_ver_servicio')]
    public function verprov($id, ServicioRepository $servicios): Response
    {
        $servicio = $servicios->find($id);
        $proveedores = $servicio->getPersonas();
        return $this->render('asap_services/entornos/cliente/showproveedores.html.twig', [
            'proveedores' => $proveedores,
            'servicio' => $servicio
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

    #[Route('/cliente/ajustes/{id}/editar', name: 'app_asap_services_entornos_cliente_ajustes_editar')]
    public function edit(Request $request, Persona $persona, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        # El template no tiene un metodo para guardar y se requiere de un metodo para eliminar
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

    #[Route('/cliente/ajustes/favoritos', name: 'app_asap_services_entornos_cliente_ajustes_favoritos')]
    public function favoritos(): Response
    {
        # No se encontro la vista favoritos
        return $this->redirectToRoute('app_asap_services_entornos_cliente_inicio');
    }

    #[Route('/cliente/ajustes/privacidad', name: 'app_asap_services_entornos_cliente_ajustes_privacidad')]
    public function privacidad(): Response
    {
        # No se encontro la vista privacidad
        return $this->redirectToRoute('app_asap_services_entornos_cliente_inicio');
    }

    #[Route('/cliente/ajustes/terminos', name: 'app_asap_services_entornos_cliente_ajustes_terminos')]
    public function terminos(): Response
    {
        # No se encontro la vista Terminos
        return $this->redirectToRoute('app_asap_services_entornos_cliente_inicio');
    }

    #[Route('/cliente/ajustes/acerca_de', name: 'app_asap_services_entornos_cliente_ajustes_acerca_de')]
    public function acercade(): Response
    {
        # No se encontro la vista Acerca de
        return $this->redirectToRoute('app_asap_services_entornos_cliente_inicio');
    }

    #[Route('/cliente/menu/historial', name: 'app_asap_services_entornos_cliente_menu_hisorial_de_servicios')]
    public function histserv(UsuarioRepository $usuarios): Response
    {
        # Corregir listado y listar en el template
        $user = $this->getUser();
        $cliente = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $cliente->getIdPersona();
        $personaservs = $persona->getServicios();
        return $this->render('asap_services\entornos\cliente\historial_servicios.html.twig', [
            'personaservs' => $personaservs,
        ]);
    }

    //Hasta aca estamos

    #[Route('/cliente/verservicios/proveedor/{id}', name: 'app_cliente_provdetalle')]
    public function verprovdet($id, Request $request, PersonaRepository $personas, ConversacionRepository $conversacionRepository, EntityManagerInterface $entityManager): Response
    {
        $proveedor = $personas->find($id);

        $otherUser = $request->get('otherUser', 0);
        $otherUser = $personas->find($otherUser);
        // echo $otherUser->getId();
        // echo $proveedor->getId();
        // echo $this->getUser()->getId();
        $conversacion = $conversacionRepository->findConversationByParticipants(
            $proveedor->getId(),
            $this->getUser()->getId()
        );
        if (count($conversacion)) {
            throw new \Exception("La conversación ya existe");
        }
        // $form = $this->createForm(ConversacionType::class);
        // $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            // $conversacion = new Conversacion();

            // $participante = new Participante();
            // $participante->setUsuarioId($this->getUser());
            // $participante->setConversacionId($conversacion);

            // $otroParticipante = new Participante();
            // $otroParticipante->setUsuarioId($proveedor);
            // $otroParticipante->setConversacionId($conversacion);

            // $entityManager->persist($conversacion);
            // $entityManager->persist($participante);
            // $entityManager->persist($otroParticipante);

            // $entityManager->flush();

            $this->redirectToRoute('app_chat_conversacion');
        }
        // echo $proveedor;
        return $this->render('asap_services/entornos/cliente/detalleproveedor.html.twig', [
            'proveedor' => $proveedor,
            // 'form' => $form,
        ]);
    }

    #[Route('/cliente/invitar', name: 'app_cliente_invitar')]
    public function invitar(UsuarioRepository $usuarios): Response
    {
        $user = $this->getUser();
        $cliente = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $cliente->getIdPersona();
        $codigo = $persona->getCodigo()->getCCodigo();
        return $this->render('asap_services\entornos\cliente\invitar_amigos.html.twig', [
            'codigo' => $codigo,
        ]);
    }

    #[Route('/cliente/calificacion', name: 'app_cliente_calificacion')]
    public function calificacion(Request $request, UsuarioRepository $usuarios, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $cliente = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $cliente->getIdPersona(); //para saber de quien es la opinion
        $calificacion = new Calificacion();
        $form = $this->createForm(CalificacionType::class, $calificacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $calificacion->setPersona($persona);
            $entityManager->persist($calificacion);
            $entityManager->flush();

            return $this->redirectToRoute('app_cliente');
        }

        return $this->render('cliente\calificacion.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/cliente/promociones', name: 'app_cliente_promociones')]
    public function Codigo(): Response
    {
        return $this->render('cliente\promocion.html.twig');
    }

    #[Route('/cliente/promociones/codigo', name: 'app_cliente_promociones_codigo')]
    public function verificarCodigo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $codigo = $request->request->get('codigo');
        $codigoDescuento = $entityManager->getRepository(Promocion::class)->findOneBy(['codigo' => $codigo]);

        if ($codigoDescuento) {
            // Código de descuento válido
            // Aquí puedes aplicar la lógica para aplicar el descuento en tu aplicación
            // Por ejemplo, guardarlo en la sesión para su uso posterior
            $this->addFlash('success', 'Código de descuento aplicado correctamente.');
        } else {
            $this->addFlash('error', 'Código de descuento no válido.');
        }

        return $this->redirectToRoute('app_cliente_promociones');
    }

    // CREADO POR FRONTEND PARA VISUALIZAR LAS VISTAS - EKIMINAR SI ES NECESARIO
    #[Route('/cliente/verservicios', name: 'app_cliente_verservicios')]
    public function verservicios2(): Response
    {
        return $this->render('asap_services/entornos/cliente/showservicios.html.twig', []);
    }
    #[Route('/cliente/servicio_electricista', name: 'app_cliente_servicio_electricista')]
    public function servicio_electricista(): Response
    {
        return $this->render('asap_services/entornos/cliente/servicio_electricidad.html.twig', []);
    }
    #[Route('/cliente/servicio_gasfiteria', name: 'app_cliente_servicio_gasfiteria')]
    public function servicio_gasfiteria(): Response
    {
        return $this->render('asap_services/entornos/cliente/servicio_gasfiteria.html.twig', []);
    }

    #[Route('/cliente/servicios_menu', name: 'app_cliente_servicios_menu')]
    public function servicios_menu(): Response
    {
        return $this->render('asap_services/entornos/cliente/servicios_menu.html.twig', []);
    }
    #[Route('/cliente/aniadir_tarjeta', name: 'app_aniadir_tarjeta')]
    public function aniadir_tarjeta(): Response
    {
        return $this->render('asap_services/entornos/cliente/aniadir_tarjeta.html.twig', []);
    }
    #[Route('/cliente/detalle_tarjeta', name: 'app_detalle_tarjeta')]
    public function detalle_tarjeta(Request $request, UsuarioRepository $usuarios, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $cliente = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $cliente->getIdPersona();
        $tarjeta = new Tarjeta();
        $form = $this->createForm(TarjetaType::class, $tarjeta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tarjeta->setPersona($persona);
            $entityManager->persist($tarjeta);
            $entityManager->flush();
            return $this->redirectToRoute('app_detalles_saldos_pagos');
        }
        return $this->render('asap_services/entornos/cliente/detalle_tarjeta.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/cliente/saldos_pagos', name: 'app_saldos_pagos.html.twig')]
    public function saldos_pagos(): Response
    {
        return $this->render('asap_services/entornos/cliente/saldos_pagos.html.twig', []);
    }
    #[Route('/cliente/detalles_saldos_pagos', name: 'app_detalles_saldos_pagos')]
    public function detalles_saldos_pagos(): Response
    {
        return $this->render('asap_services/entornos/cliente/detalles_saldos_pagos.html.twig', []);
    }

    // FIN

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

    // #[Route('cliente/conversacion', name: 'app_conversacion')]
    // public function newConversacion($id, ConversacionRepository $conversacionRepository, PersonaRepository $personaRepository): Response
    // {
    //     $conversaciones = $conversacionRepository->findConversationsByUser($this->getUser()->getId());

    //     $conversacion = $personaRepository->find($id);
    //     return $this->render('chat/conversacion.html.twig', [
    //         'conversacion' => $conversaciones,  
    //     ]);
    //     // return $this->json([
    //     //     'conver' => $conversacion,
    //     // ]);
    // }
    // #[Route('/cliente/verservicios/proveedor/{id}', name: 'app_cliente_provdetalle')]
    // public function verprovdet($id, PersonaRepository $personas): Response
    // {
    //     $proveedor = $personas->find($id);
    //     return $this->render('asap_services/entornos/cliente/detalleproveedor.html.twig', [
    //         'proveedor' => $proveedor,
    //     ]);
    // }
}
