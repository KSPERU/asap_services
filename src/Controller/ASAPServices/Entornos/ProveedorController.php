<?php

namespace App\Controller\ASAPServices\Entornos;

use DateTime;
use DateTimeZone;
use App\Entity\Persona;
use App\Form\PersonaType;
use App\Form\ProveedorType;
use App\Entity\Conversacion;
use App\Entity\PersonaServicio;
use App\Entity\GananciaProveedor;
use App\Entity\MetcobroProveedor;
use App\Entity\Historialservicios;
use App\servicios\ProveedorServicio;
use App\Repository\PersonaRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ServicioRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MetodocobroRepository;
use App\Repository\PersonaServicioRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProveedorController extends AbstractController
{
    #[Route('/proveedor', name: 'app_asap_services_entornos_proveedor_inicio')]
    public function index(UsuarioRepository $usuarios): Response
    {
        $user = $this->getUser();
        $proveedor = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $proveedor->getIdPersona();
        $id = $persona->getId();
        if ($persona->getPBiografia() === null) {
            return $this->redirectToRoute('app_asap_services_entornos_proveedor_biografia', ['id' => $id]);
        }

        return $this->render('asap_services/entornos/proveedor/inicio_proveedor.html.twig', [
            'controller_name' => 'ProveedorController',
            'proveedor' => $persona,
        ]);
    }

    #[Route('/proveedor/{id}/biografia', name: 'app_asap_services_entornos_proveedor_biografia')]
    public function biografia(Persona $persona, Request $request, $id, EntityManagerInterface $entityManager): Response
    {

        if ($request->isMethod('POST')) {
            $biografia = $request->get('p_biografia');

            if ($persona) {
                $persona->setPBiografia($biografia);
                $entityManager->persist($persona);
                $entityManager->flush();
            }


            return $this->redirectToRoute('app_asap_services_entornos_proveedor_servicios', ['id' => $persona->getId()]);
        }

        return $this->render('asap_services/entornos/proveedor/biografia.html.twig', [
            'controller_name' => 'ProveedorController',
            'persona' => $persona
        ]);
    }

    #[Route('/proveedor/{id}/servicios', name: 'app_asap_services_entornos_proveedor_servicios')]
    public function servicios(Persona $persona, $id, Request $request, ServicioRepository $servicios, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $serviciosselect = $request->get('servicios', []);

            if ($persona) {
                foreach ($serviciosselect as $servicioid) {
                    $servicio = $servicios->find($servicioid);

                    if ($servicio) {
                        if ($persona->getServicios()->isEmpty()) {
                            $personaservicio = new PersonaServicio;
                            $personaservicio->setIdPersona($persona);
                            $personaservicio->setIdServicio($servicio);
                            $entityManager->persist($personaservicio);
                        } else {
                            $personaservicio = $persona->getServicios();
                            foreach ($personaservicio as $pservicio) {
                                $pservicio->setIdServicio($servicio);
                            }
                        }
                    }
                }
                $entityManager->flush();

                return $this->redirectToRoute('app_asap_services_entornos_proveedor_inicio');
            }
        }

        return $this->render('asap_services/entornos/proveedor/ofrecer_servicios.html.twig', [
            'controller_name' => 'ProveedorController',
            'persona' => $persona,
            'servicios' => $servicios->findAll(),
        ]);
    }

    #[Route('/proveedor/menu/{id}/perfil', name: 'app_asap_services_entornos_proveedor_menu_perfil')]
    public function perfil(Request $request, Persona $persona, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, PersonaRepository $personaRepository): Response
    {
        $servicio = $persona->getServicios();

        $juanito = new Persona();
        $juanito->setPNombre($persona->getPNombre());
        $juanito->setPApellido($persona->getPApellido());
        $juanito->setPContacto($persona->getPContacto());
        $juanito->setPDireccion($persona->getPDireccion());

        $form = $this->createForm(ProveedorType::class, $juanito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $archivo_foto = $form['p_foto']->getData();
            $archivo_cv = $form['p_cv']->getData();
            $archivo_ap = $form['p_antpen']->getData();
            $archivo_cc = $form['p_cert']->getData();
            if ($archivo_foto !== null) {
                $codigo_unico = uniqid() . time();
                $destino = $this->getParameter('kernel.project_dir') . '/public/img';
                $archivo_foto->move($destino, $codigo_unico . $archivo_foto->getClientOriginalName());
                $persona->setPFoto($codigo_unico . $archivo_foto->getClientOriginalName());
            }
            if ($archivo_cv !== null) {
                $codigo_unico = uniqid() . time();
                $destino = $this->getParameter('kernel.project_dir') . '/public/docs';
                $archivo_cv->move($destino, $codigo_unico . $archivo_cv->getClientOriginalName());
                $persona->setPCv($codigo_unico . $archivo_cv->getClientOriginalName());
            }

            if ($archivo_ap !== null) {
                $codigo_unico = uniqid() . time();
                $destino = $this->getParameter('kernel.project_dir') . '/public/docs';
                $archivo_ap->move($destino, $codigo_unico . $archivo_ap->getClientOriginalName());
                $persona->setPAntpen($codigo_unico . $archivo_ap->getClientOriginalName());
            }

            if ($archivo_cc !== null) {
                $codigo_unico = uniqid() . time();
                $destino = $this->getParameter('kernel.project_dir') . '/public/docs';
                $archivo_cc->move($destino, $codigo_unico . $archivo_cc->getClientOriginalName());
                $persona->setPCert($codigo_unico . $archivo_cc->getClientOriginalName());
            }

            $persona->setPNombre($juanito->getPNombre());
            $persona->setPApellido($juanito->getPApellido());
            $persona->setPContacto($juanito->getPContacto());
            $persona->setPDireccion($juanito->getPDireccion());

            $entityManager->persist($persona);
            $entityManager->flush();
        }
        return $this->render('asap_services/entornos/proveedor/mi_perfil.html.twig', [
            'form' => $form,
            'persona' => $persona,
            'servicio' => $servicio,
        ]);
    }

    #[Route('/proveedor/{id}/downloadCV', name: 'download_pdf_cv')]
    public function downloadPdfCV(Persona $persona): Response
    {
        $pdfCV = $this->getParameter('kernel.project_dir') . '/public/docs/' . $persona->getPCv();
        return $this->file($pdfCV);
    }

    #[Route('/proveedor/{id}/downloadAP', name: 'download_pdf_ap')]
    public function downloadPdfAP(Persona $persona): Response
    {
        $pdfAP = $this->getParameter('kernel.project_dir') . '/public/docs/' . $persona->getPAntpen();
        return $this->file($pdfAP);
    }
    #[Route('/proveedor/{id}/downloadCE', name: 'download_pdf_ce')]
    public function downloadCE(Persona $persona): Response
    {
        $pdfCE = $this->getParameter('kernel.project_dir') . '/public/docs/' . $persona->getPCert();
        return $this->file($pdfCE);
    }

    #[Route('/proveedor/eliminar/{id}', name: 'app_asap_services_entornos_proveedor_ajustes_eliminar')]
    public function delete(Request $request, Persona $persona, PersonaRepository $personaRepository, UsuarioRepository $usuarioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $persona->getId(), $request->request->get('_token'))) {
            $personaRepository->remove($persona, true);
        }

        $session = $request->getSession();
        $session->invalidate();
        $this->container->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('app_asap_services_general_inicio');
    }

    #[Route('/proveedor/menu/{id}/historial', name: 'app_asap_services_entornos_proveedor_menu_historial')]
    public function historialservicios(Persona $persona): Response
    {
        # El template esta vacio
        $histservicios = $persona->getHistservproveedor();
        return $this->render('asap_services/entornos/proveedor/historialservicios.html.twig', [
            'historiales' => $histservicios,
        ]);
    }

    #[Route('/proveedor/menu/{id}/ganancias', name: 'app_asap_services_entornos_proveedor_menu_ganancias')]
    public function ganancias(Persona $persona, Request $request): Response
    {
        $ganancias = $persona->getgananciaProveedor();
        $gansincobrar = $persona->getHistservproveedor();

        $proveedorservice = new ProveedorServicio;
        $totalganancias = $proveedorservice->calcularTotalGanancias($ganancias);
        $serviciossincobro = $proveedorservice->filtrarGansincobrar($gansincobrar);
        $datosganancia = $proveedorservice->calcularGananciaReal($serviciossincobro);
        $gananciaTotal  = $datosganancia['gananciaTotal'];
        $comision  = $datosganancia['comision'];
        $gananciaReal  = $datosganancia['gananciaReal'];
        $cantservicios = $datosganancia['cantidad'];
        $fechamin = $datosganancia['fecha_min'];
        $fechamax = $datosganancia['fecha_max'];
        $session = $request->getSession();
        $session->set('gansincobro_data', [
            'gananciatotal' => $totalganancias,
            'gantotsincobro' => $gananciaTotal,
            'comision' => $comision,
            'ganrealsincobro' => $gananciaReal,
            'cantservicios' => $cantservicios,
            'fechamin' => $fechamin,
            'fechamax' => $fechamax
        ]);
        if ($request->isMethod('POST')) {
            return $this->redirectToRoute('app_asap_services_entornos_proveedor_detalle_ganancias', ['id' => $persona->getId()]);
        }
        return $this->render('asap_services/entornos/proveedor/ganancias_1.html.twig', [
            'proveedor' => $persona,
            'gananciatotal' => $totalganancias,
            'serviciossincobro' => $serviciossincobro


        ]);
    }

    #[Route('/proveedor/menu/{id}/ganancias/detalle', name: 'app_asap_services_entornos_proveedor_detalle_ganancias')]
    public function detalleganancias(Persona $persona, Request $request): Response
    {

        $gananciaData = $request->getSession()->get('gansincobro_data', []);
        $gananciaTotal = $gananciaData['gantotsincobro'] ?? null;
        $comision = $gananciaData['comision'] ?? null;
        $gananciaReal = $gananciaData['ganrealsincobro'] ?? null;
        $cantservicios = $gananciaData['cantservicios'] ?? null;
        $fechamin = $gananciaData['fechamin'] ?? null;
        $fechamax = $gananciaData['fechamax'] ?? null;

        if ($request->isMethod('POST')) {
            return $this->redirectToRoute('app_asap_services_entornos_proveedor_tipocobro', ['id' => $persona->getId()]);
        }

        return $this->render('asap_services/entornos/proveedor/ganancias_2.html.twig', [
            'proveedor' => $persona,
            'gananciatotal' => $gananciaTotal,
            'comision' => $comision,
            'gananciareal' => $gananciaReal,
            'cantservicios' => $cantservicios,
            'fechamin' => $fechamin,
            'fechamax' => $fechamax,
        ]);
    }

    #[Route('/proveedor/{id}/tipocobro', name: 'app_asap_services_entornos_proveedor_tipocobro')]
    public function tipocobro(Persona $persona, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $metodocobro = $request->request->get('metodo_cobro');
            $session = $request->getSession();
            if ($metodocobro == "banco") {
                $metcobro = $persona->getMetcobro();

                if ($metcobro === null || $metcobro->count() == 0) {

                    return $this->redirectToRoute('app_asap_services_entornos_proveedor_menu_metodo', ['id' => $persona->getId(), 'redi' => 'ganancia']);
                } else if ($metcobro->count() > 0) {
                    $session->set('ganancia_metodo', 'Tranferencia bancaria');
                }
            } else {
                if ($metodocobro == "efectivo") {
                    $session->set('ganancia_metodo', 'Yape-plin');
                }
            }
            return $this->redirectToRoute('app_asap_services_entornos_proveedor_conftarifa', ['id' => $persona->getId()]);
        }



        return $this->render('asap_services/entornos/proveedor/tipocobroganancia.html.twig', [
            'proveedor' => $persona,

        ]);
    }

    #[Route('/proveedor/menu/{id}/ganancias/conftarifa', name: 'app_asap_services_entornos_proveedor_conftarifa')]
    public function confitarifa(Persona $persona, Request $request): Response
    {

        $gananciaData = $request->getSession()->get('gansincobro_data', []);
        $saldo = $gananciaData['gananciatotal'] ?? null;
        $tarifa = $gananciaData['gantotsincobro'] ?? null;
        if ($request->isMethod('POST')) {
            return $this->redirectToRoute('app_asap_services_entornos_proveedor_confganancia', ['id' => $persona->getId()]);
        }
        return $this->render('asap_services/entornos/proveedor/ganancias_3.html.twig', [
            'proveedor' => $persona,
            'tarifa' => $tarifa,
            'saldo' => $saldo
        ]);
    }

    #[Route('/proveedor/menu/{id}/ganancias/confganancia', name: 'app_asap_services_entornos_proveedor_confganancia')]
    public function configanancia(Persona $persona, Request $request, EntityManagerInterface $entitymanager): Response
    {

        $gananciaData = $request->getSession()->get('gansincobro_data', []);
        $gananciareal = $gananciaData['ganrealsincobro'] ?? null;
        $metodocobro = $request->getSession()->get('ganancia_metodo') ?? null;
        if ($request->isMethod('POST')) {
            $gansincobrar = $persona->getHistservproveedor();
            $proveedorservice = new ProveedorServicio;
            $serviciossincobro = $proveedorservice->filtrarGansincobrar($gansincobrar);
            foreach ($serviciossincobro as $servicio) {
                $servicio->setHsEstadocobro(true);
                $entitymanager->flush();
            }
            $zonaHoraria = new DateTimeZone('America/Lima');  // Reemplaza 'America/Lima' con tu zona horaria
            $fechaActual = new DateTime('now', $zonaHoraria);
            $ganancia = new GananciaProveedor;
            $ganancia->setGpTotal($gananciareal);
            $ganancia->setIdproveedor($persona);
            $ganancia->setGpFechaoperacion($fechaActual);
            $ganancia->setGpEstadotransferencia(false);
            $ganancia->setGpMetodocobro($metodocobro);
            $entitymanager->persist($ganancia);
            $entitymanager->flush();
            return $this->redirectToRoute('app_asap_services_entornos_proveedor_inicio');
        }
        return $this->render('asap_services/entornos/proveedor/ganancias_4.html.twig', [
            'proveedor' => $persona,
            'gananciareal' => $gananciareal,

        ]);
    }

    #[Route('/proveedor/menu/{id}/chatclientes', name: 'app_asap_services_entornos_proveedor_menu_chat')]
    public function chatclientes(Persona $persona): Response
    {
        #Solo template falta integrar con back
        return $this->render('asap_services/entornos/proveedor/chatclientes.html.twig', [
            'proveedor' => $persona,

        ]);
    }

    #[Route('/proveedor/menu/preguntas', name: 'app_asap_services_entornos_proveedor_menu_preguntas')]
    public function preguntas(): Response
    {
        return $this->render('asap_services/entornos/proveedor/preguntas_frecuentes.html.twig', []);
    }

    #[Route('/proveedor/menu/{id}/metcobro/{redi}', name: 'app_asap_services_entornos_proveedor_menu_metodo')]
    public function metcobro(Request $request, $redi, Persona $persona, MetodocobroRepository $metodocobros): Response
    {
        if ($redi == "ganancia") {
            $session = $request->getSession();
            $session->set('vueltavganancia', 1);
        }
        if ($request->isMethod('POST')) {
            $metodocobroselect = $request->get('metodocobros', []);

            if ($persona) {
                $idmet = !empty($metodocobroselect) ? $metodocobroselect[0] : null;
                return $this->redirectToRoute('app_asap_services_entornos_proveedor_menu_metodo_numero', ['id' => $persona->getId(), 'idmet' => $idmet]);
            }
        }

        return $this->render('asap_services/entornos/proveedor/agregar_metodo_cobro.html.twig', [
            'proveedor' => $persona,
            'metodocobros' => $metodocobros->findAll()
        ]);
    }

    #[Route('/proveedor/menu/{id}/metcobro/{idmet}/numcuenta', name: 'app_asap_services_entornos_proveedor_menu_metodo_numero')]
    public function numcuenta(Request $request, $idmet, Persona $persona, MetodocobroRepository $metodocobros, EntityManagerInterface $entityManager): Response
    {
        #No tiene template
        if ($persona) {
            if ($request->isMethod('POST')) {

                $metcobro = $metodocobros->find($idmet);
                $numcuenta = $request->request->get('numcuenta');
                if ($metcobro) {
                    $metcobroprov = new MetcobroProveedor;
                    $metcobroprov->setIdproveedor($persona);
                    $metcobroprov->setIdmetcobro($metcobro);
                    $metcobroprov->setMcpNumerocuenta($numcuenta);
                    $metcobroprov->setMcpEstado(true);
                    $entityManager->persist($metcobroprov);
                }
                $entityManager->flush();

                if ($request->getSession()->get('vueltavganancia') == 1) {
                    return $this->redirectToRoute('app_asap_services_entornos_proveedor_conftarifa', ['id' => $persona->getId()]);
                }

                return $this->redirectToRoute('app_asap_services_entornos_proveedor_inicio');
            }
        }

        return $this->render('asap_services/entornos/proveedor/agregar_numero_cuenta.html.twig', [
            'proveedor' => $persona,
            'metodocobros' => $metodocobros->findAll()
        ]);
    }

    #[Route('/proveedor/menu/ayuda', name: 'app_asap_services_entornos_proveedor_menu_ayuda')]
    public function ayuda(): Response
    {
        return $this->render('asap_services/entornos/proveedor/ayuda.html.twig', []);
    }

    # Fin de revision sprint 03

    #[Route('/proveedor/{id}/edit', name: 'app_proveedor_edit')]
    public function edit(Request $request, Persona $persona, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(ProveedorType::class, $persona);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $archivo = $form['p_foto']->getData();
            if ($archivo !== null) {
                $destino = $this->getParameter('kernel.project_dir') . '/public/img';
                $archivo->move($destino, $archivo->getClientOriginalName());
                $persona->setPFoto($archivo->getClientOriginalName());
            }
            $archivo = $form['p_cv']->getData();
            if ($archivo !== null) {
                $destino = $this->getParameter('kernel.project_dir') . '/public/doc';
                $archivo->move($destino, $archivo->getClientOriginalName());
                $persona->setPFoto($archivo->getClientOriginalName());
            }
            $archivo = $form['p_antpen']->getData();
            if ($archivo !== null) {
                $destino = $this->getParameter('kernel.project_dir') . '/public/doc';
                $archivo->move($destino, $archivo->getClientOriginalName());
                $persona->setPFoto($archivo->getClientOriginalName());
            }
            $archivo = $form['p_cert']->getData();
            if ($archivo !== null) {
                $destino = $this->getParameter('kernel.project_dir') . '/public/doc';
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
        return $this->render('asap_services/entornos/proveedor/edit.html.twig', [
            'form' => $form,
            'persona' => $persona,

        ]);
    }

    #[Route('/proveedor/historial_servicios', name: 'app_prov_historial_servicios')]
    public function historial_servicios(): Response
    {
        return $this->render('asap_services/entornos/proveedor/historial_servicios.html.twig', []);
    }
    #[Route('/proveedor/conversa_cliente', name: 'app_prov_conversa_cliente')]
    public function conversa_cliente(): Response
    {
        return $this->render('asap_services/entornos/proveedor/conversa_cliente.html.twig', []);
    }
    #[Route('/proveedor/chatclientes', name: 'app_prov_chatclientes')]
    public function chatclientesX(): Response
    {
        return $this->render('asap_services/entornos/proveedor/chatclientes.html.twig', []);
    }

    #[Route('/proveedor/agregar_metodo_cobro', name: 'app_prov_agregar_metodo_cobro')]
    public function agregar_metodo_cobro(): Response
    {
        return $this->render('asap_services/entornos/proveedor/agregar_metodo_cobro.html.twig', []);
    }
    #[Route('/proveedor/agregar_numero_cuenta', name: 'app_prov_agregar_numero_cuenta')]
    public function agregar_numero_cuenta(): Response
    {
        return $this->render('asap_services/entornos/proveedor/agregar_numero_cuenta.html.twig', []);
    }

    #[Route('/proveedor/cantidad/{id}', name: 'app_cantidad')]
    public function newCantidadVist(Conversacion $conversacion): Response
    {
        return $this->render('asap_services/entornos/proveedor/proveedor_cantidad_cobro.html.twig', [
            'conversacion' => $conversacion,
        ]);
    }

    #[Route('/post/historial/{id}', name: 'app_historial_post', methods: ['POST'])]
    public function newHistorial(Conversacion $conversacion, Request $request, EntityManagerInterface $entityManager, ServicioRepository $servicioRepository, PersonaRepository $personaRepository): Response
    {
        $participantes = $conversacion->getParticipantes();

        $btnCobro = $request->request->get('cantidad-cobro');

        foreach ($participantes as $participante) {
            if ($participante->getUsuarioId()->getId() !== $this->getUser()->getId()) {
                $otroParticipante = $participante;
            }
        }

        $idCliente = $otroParticipante->getUsuarioId()->getId();
        $idProveedor = $this->getUser()->getId();
        $direccionCliente = $otroParticipante->getUsuarioId()->getIdPersona()->getPDireccion();

        foreach ($this->getUser()->getIdPersona()->getServicios() as $servicio) {
            if ($participante->getUsuarioId()->getId() === $this->getUser()->getId()) {
                $servicioProv = $servicio->getIdServicio()->getId();
            }
        }

        $servEnt = $servicioRepository->find($servicioProv);
        $cliEnt = $personaRepository->find($idCliente);
        $provEnt = $personaRepository->find($idProveedor);

        $historial = new Historialservicios();
        $historial->setIdservicio($servEnt);
        $historial->setIdcliente($cliEnt);
        $historial->setIdproveedor($provEnt);
        $historial->setHsEstado(false);
        $historial->setHsEstadopago(false);
        $historial->setHsImporte($btnCobro);
        $historial->setHsDireccion($direccionCliente);
        $entityManager->persist($historial);
        $entityManager->flush();

        return $this->redirectToRoute('app_cantidad', [
            'id' => $conversacion->getId(),
        ]);
    }

    #[Route('/post/cantidad/{id}', name: 'app_cantidad_post', methods: ['POST'])]
    public function newCantidad(Conversacion $conversacion): Response
    {
        return $this->redirectToRoute('app_cantidad', [
            'id' => $conversacion->getId(),
        ]);
    }
}
