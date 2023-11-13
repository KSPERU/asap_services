<?php

namespace App\Controller\ASAPServices\Entornos;

use App\Entity\Codigo;
use App\Entity\Persona;
use App\Entity\Tarjeta;
use App\Entity\Usuario;
use App\Form\ClienteType;
use App\Form\TarjetaType;
use App\Entity\Calificacion;
use App\Entity\Conversacion;
use App\Entity\Historialservicios;
use App\Entity\Participante;
use App\Form\CalificacionType;
use App\Form\ConversacionType;
use App\Repository\CodigoRepository;
use App\Repository\PersonaRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ServicioRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversacionRepository;
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
    public function verprov($id, ServicioRepository $servicios, UsuarioRepository $usuarioRepository, PersonaRepository $personaRepository): Response
    {
        # Identifiquemos al usuario
        $persona_aux = $this->getUser();
        $usuario = $usuarioRepository->findOneBy([
            'email' => $persona_aux->getUserIdentifier(),
        ]);
        $persona = $personaRepository->findOneBy([
            'usuario' => $usuario,
        ]);

        $servicio = $servicios->find($id);
        $proveedores = $servicio->getPersonas();
        return $this->render('asap_services/entornos/cliente/showproveedores.html.twig', [
            'proveedores' => $proveedores,
            'servicio' => $servicio,
            'persona' => $persona,
        ]);
    }

    #[Route('/cliente/verservicios/proveedor/{id}', name: 'app_asap_services_entornos_cliente_ver_servicio_ver_detalle_proveedor')]
    public function verprovdet($id, PersonaRepository $personas, ConversacionRepository $conversacionRepository): Response
    {
        $proveedor = $personas->find($id);
        // $conversacion = $conversacionRepository->findConversationByParticipants(
        //     $proveedor->getId(),
        //     $this->getUser()->getId()
        // );
        // if (count($conversacion)) {
        //     throw new \Exception("La conversación ya existe");
        // }
        return $this->render('asap_services/entornos/cliente/detalleproveedor.html.twig', [
            'proveedor' => $proveedor,
            // 'form' => $form,
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
        $neto_persona = new Persona();
        $neto = new Usuario();

        $neto_persona->setPNombre($persona->getPNombre());
        $neto_persona->setPApellido($persona->getPApellido());
        $neto_persona->setPContacto($persona->getPContacto());
        $neto_persona->setPDireccion($persona->getPDireccion());
        $neto->setEmail($persona->getUsuario()->getEmail());
        $neto->setPassword($persona->getUsuario()->getPassword());
        
        $neto->setIdPersona($neto_persona);
        
        
        $form = $this->createForm(ClienteType::class, $neto_persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $archivo = $form['p_foto']->getData();
            if ($archivo !== null) {
                $codigo_unico = uniqid() . time();
                $destino = $this->getParameter('kernel.project_dir') . '/public/img';
                $archivo->move($destino, $codigo_unico . $archivo->getClientOriginalName());
                $persona->setPFoto($codigo_unico . $archivo->getClientOriginalName());
            }

            if ($neto->getPassword() !== null && $neto->getPassword() !== '') {
                //echo($neto->getPassword());
                $persona->getUsuario()->setPassword($userPasswordHasher->hashPassword($persona->getUsuario(), $neto->getPassword()));
            }

            $persona->setPNombre($neto_persona->getPNombre());
            $persona->setPApellido($neto_persona->getPApellido());
            $persona->setPContacto($neto_persona->getPContacto());
            $persona->setPDireccion($neto_persona->getPDireccion());
            $persona->getUsuario()->setEmail($neto->getEmail());
            

            // $user = $persona->getUsuario();
            // $user->setPassword(
            //     $userPasswordHasher->hashPassword(
            //         $user,
            //         $form->get('usuario')->get('password')->getData()
            //     )
            // );
            // $plainPassword = $form->get('usuario')->get('password')->setData($user->getPassword());  
            
            // $neto->setPassword($persona->getUsuario()->getPassword());
            
            // $user = $persona->getUsuario();
            // $user->setPassword($neto->getPassword());

            $entityManager->persist($persona->getUsuario());
            $entityManager->persist($persona);
            $entityManager->flush();
            
            
            // $archivo_foto = $form['p_foto']->getData();
            // if ($form !== null) {
            //     $codigo_unico = uniqid() . time();
            //     $destino = $this->getParameter('kernel.project_dir') . '/public/img';
            //     $archivo_foto->move($destino, $codigo_unico . $archivo_foto->getClientOriginalName());
            //     $persona->setPFoto($codigo_unico . $archivo_foto->getClientOriginalName());
            // }

            
        }
        return $this->render('asap_services/entornos/cliente/mi_perfil.html.twig', [
            'form' => $form,
            'persona' => $persona,
        ]);
    }

    #[Route('/cliente/eliminar/{id}', name: 'app_asap_services_entornos_cliente_ajustes_eliminar')]
    public function delete(Request $request, Persona $persona, PersonaRepository $personaRepository, UsuarioRepository $usuarioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $persona->getId(), $request->request->get('_token'))) {
            $user_aux = $persona->getUsuario();
            $personaRepository->remove($persona, true);
            $usuarioRepository->remove($persona->getUsuario(), true);
        }

        $session = $request->getSession();
        $session->invalidate();
        $this->container->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('app_asap_services_general_logout', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/cliente/ajustes/favoritos', name: 'app_asap_services_entornos_cliente_ajustes_favoritos')]
    public function favoritos(EntityManagerInterface $entityManager, UsuarioRepository $usuarios, ServicioRepository $servicioRepository): Response
    {
        $user = $this->getUser();
        $cliente = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $cliente->getIdPersona()->getId();
        $historialServicioRepository = $entityManager->getRepository(Historialservicios::class);
        $personaservs = $historialServicioRepository->findBy([
            'idcliente' => $persona,
            'hs_estado' => 1, //estado uno es que es favorito y si pago 
        ]);
        $servicios = [];
        foreach ($personaservs as $historialServicio) {
            $servicio = $historialServicio->getIdServicio();
            $servicios[] = $servicio;
        }
        return $this->render('asap_services/entornos/cliente/showserviciosfav.html.twig', [//copie la misma plantilla de show servicios
            'servicios' => $servicios,
            'persona' => $persona
        ]);
    }

    #[Route('/cliente/ajustes/privacidad', name: 'app_asap_services_entornos_cliente_ajustes_privacidad')]
    public function privacidad(): Response
    {
        # No se encontro la vista privacidad [RESUELTO POR FRONTEND]
        return $this->render('asap_services/entornos/cliente/privacidad.html.twig');
    }

    #[Route('/cliente/ajustes/terminos', name: 'app_asap_services_entornos_cliente_ajustes_terminos')]
    public function terminos(): Response
    {
        return $this->render('asap_services/entornos/cliente/terminos_condiciones.html.twig'); 
    }

    #[Route('/cliente/ajustes/acerca_de', name: 'app_asap_services_entornos_cliente_ajustes_acerca_de')]
    public function acercade(): Response
    {
        return $this->render('asap_services/entornos/cliente/acercade_asap.html.twig');
    }

    #[Route('/cliente/menu/historial', name: 'app_asap_services_entornos_cliente_menu_hisorial_de_servicios')]
    public function histserv(UsuarioRepository $usuarios, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $cliente = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $cliente->getIdPersona()->getId();
        $historialServicioRepository = $entityManager->getRepository(Historialservicios::class);
        $personaservs = $historialServicioRepository->findBy([
            'idcliente' => $persona,
            'hs_estado' => 0,
        ]);
        return $this->render('asap_services\entornos\cliente\historial_servicios.html.twig', [
            'personaservs' => $personaservs,
        ]);
    }

    #[Route('/cliente/menu/metodos', name: 'app_asap_services_entornos_cliente_menu_metodos_de_pago')]
    public function metodopag(): Response
    {
        # No se encontro la vista Metodos de pago 
        return $this->render('asap_services/entornos/cliente/aniadir_tarjeta.html.twig');
    }

    #[Route('/cliente/menu/detalle_tarjeta', name: 'app_asap_services_entornos_cliente_menu_detalle_tarjeta')]
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

    #[Route('/cliente/menu/saldos_pagos', name: 'app_asap_services_entornos_cliente_menu_saldos_pagos')]
    public function saldos_pagos(Request $request, UsuarioRepository $usuarios, EntityManagerInterface $entityManager): Response
    {
        #Faltan metodo de pago (Integración con tercero)
        $user = $this->getUser();
        $cliente = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $cliente->getIdPersona()->getId();
        $historialServicioRepository = $entityManager->getRepository(Historialservicios::class);
        $personaservs = $historialServicioRepository->findBy([
            'idcliente' => $persona,
            'hs_estadopago' => 0,
        ]);

        $sumaImp = 0;
        // echo $personaservs;
        if ($request->isMethod('POST')) {
            foreach ($personaservs as $pr) {
                $check = $request->request->get('servicio_ids-' . $pr->getId() . '');
                if ($check) {
                    $sumaImp += $pr->getHsImporte();
                    // $sumaImp = $pr->getId();
                }
                // return $this->redirectToRoute('app_asap_services_entornos_cliente_menu_saldos_pagos',[],Response::HTTP_SEE_OTHER);
            }
        }
        // echo $checkbox;
        echo $sumaImp;
        return $this->render('asap_services/entornos/cliente/saldos_pagos.html.twig', [
            'personaservs' => $personaservs,
            'checkbox' => $sumaImp,
        ]);
    }

    #[Route('/cliente/menu/detalles_saldos_pagos', name: 'app_asap_services_entornos_cliente_menu_detalles_saldos_pagos')]
    public function detalles_saldos_pagos(): Response
    {
        #El procedimiento pago esta incompleto
        return $this->render('asap_services/entornos/cliente/detalles_saldos_pagos.html.twig', []);
    }

    #[Route('/cliente/menu/invitar', name: 'app_asap_services_entornos_cliente_menu_invitar')]
    public function invitar(UsuarioRepository $usuarios): Response
    {
        # Falla la opcion de llamar codigo
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

    #[Route('/cliente/menu/promociones', name: 'app_asap_services_entornos_cliente_menu_promociones')]
    public function codigo(): Response
    {
        return $this->render('asap_services/entornos/cliente/promocion_cupones.html.twig');
    }

    #[Route('/cliente/menu/promociones/codigo', name: 'app_asap_services_entornos_cliente_menu_promociones_codigo')]
    public function verificarCodigo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $codigo = $request->request->get('codigo');
        $codigoDescuento = $entityManager->getRepository(Codigo::class)->findOneBy(['c_codigo' => $codigo]);

        if ($codigoDescuento) {
            // Código de descuento válido guardarlo en algun lugar para la compra y el descuento
            $this->addFlash('success', 'Código aplicado correctamente.');
        } else {
            $this->addFlash('error', 'Código no válido.');
        }
        return $this->redirectToRoute('app_asap_services_entornos_cliente_menu_promociones');
    }

    #[Route('/cliente/menu/calificacion', name: 'app_asap_services_entornos_cliente_menu_calificacion')] //terminado
    public function calificacion(Request $request, UsuarioRepository $usuarios, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $cliente = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $cliente->getIdPersona();
        $calificacion = new Calificacion();
        $form = $this->createForm(CalificacionType::class, $calificacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $calificacion->setPersona($persona);
            $entityManager->persist($calificacion);
            $entityManager->flush();
            return $this->redirectToRoute('app_asap_services_entornos_cliente_inicio');
        }

        return $this->render('asap_services\entornos\cliente\tu_opinon_importa.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/cliente/menu/ayuda', name: 'app_asap_services_entornos_cliente_menu_ayuda')]
    public function ayuda(): Response
    {
        # No hay template
        return $this->redirectToRoute('app_asap_services_entornos_cliente_inicio');
    }

    //Fin de revisión 3er Sprint

    // #[Route('/cliente/verservicios/proveedor/{id}', name: 'app_cliente_provdetalle')]
    // public function verprovdet($id, PersonaRepository $personas, ConversacionRepository $conversacionRepository): Response
    // {
    //     $proveedor = $personas->find($id);

    //     $conversacion = $conversacionRepository->findConversationByParticipants(
    //         $proveedor->getId(),
    //         $this->getUser()->getId()
    //     );
    //     if (count($conversacion)) {
    //         throw new \Exception("La conversación ya existe");
    //     }
    //     return $this->render('asap_services/entornos/cliente/detalleproveedor.html.twig', [
    //         'proveedor' => $proveedor,
    //         // 'form' => $form,
    //     ]);
    // }

    #[Route('/cliente/verservicios/proveedor/post/{id}', name: 'app_cliente_provdetalle_post')]
    public function newConversacion($id, PersonaRepository $personas, UsuarioRepository $usuarioRepository, ConversacionRepository $conversacionRepository, EntityManagerInterface $entityManager): Response
    {
        $proveedor = $personas->find($id);

        $conversacion = $conversacionRepository->findConversationByParticipants(
            $proveedor->getId(),
            $this->getUser()->getId()
        );

        $aux = $usuarioRepository->find($id);

        if (count($conversacion)) {
            return $this->redirectToRoute('app_chat_conversacion');
        }
        $conversacion = new Conversacion();
        // echo $conversacion;
        $participante = new Participante();
        $participante->setUsuarioId($this->getUser());
        $participante->setConversacionId($conversacion);

        $otroParticipante = new Participante();
        $otroParticipante->setUsuarioId($aux);
        $otroParticipante->setConversacionId($conversacion);

        $entityManager->persist($conversacion);
        $entityManager->persist($participante);
        $entityManager->persist($otroParticipante);

        $entityManager->flush();

        return $this->redirectToRoute('app_chat_conversacion');
    }

    //Vista
    #[Route('/cliente/verservicios/proveedor/comunicate/{id}', name: 'app_cliente_comunicate')]
    public function verComunicate($id, PersonaRepository $personaRepository, ConversacionRepository $conversacionRepository): Response
    {
        $proveedor = $personaRepository->find($id);
        return $this->render('asap_services/entornos/cliente/pre_contactar_proveedor.html.twig', [
            'proveedor' => $proveedor
        ]);
    }

    //Redireccion a vista pre_contactar_proveedor
    #[Route('/cliente/verservicios/proveedor/comunicate/post/{id}', name: 'app_cliente_comunicate_post', methods: ['POST'])]
    public function verComunicatePost(Usuario $usuario): Response
    {
        return $this->redirectToRoute('app_cliente_comunicate', [
            'id' => $usuario->getId(),
        ]);
    }

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
