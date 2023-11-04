<?php

namespace App\Controller\ASAPServices\Entornos;

use App\Entity\MetcobroProveedor;
use App\Entity\Persona;
use App\Form\ProveedorType;
use App\Entity\PersonaServicio;
use App\Repository\MetodocobroRepository;
use App\Repository\PersonaRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ServicioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProveedorController extends AbstractController
{
    #[Route('/proveedor', name: 'app_proveedor')]
    public function index(UsuarioRepository $usuarios): Response
    {
        $user = $this->getUser();
        $proveedor = $usuarios->findOneBy([
            'email' => $user->getUserIdentifier(),
        ]);
        $persona = $proveedor->getIdPersona();
        $id = $persona->getId();
        if ($persona->getPBiografia() === null) {
            return $this->redirectToRoute('app_prov_bio', ['id' => $id]);
        }

        return $this->render('asap_services/entornos/proveedor/inicio_proveedor.html.twig', [
            'controller_name' => 'ProveedorController',
            'proveedor' => $persona,
        ]);
    }

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

    #[Route('/proveedor/{id}/biografia', name: 'app_prov_bio')]
    public function biografia($id, Request $request, Persona $persona, EntityManagerInterface $entityManager): Response
    {

        if ($request->isMethod('POST')) {
            $biografia = $request->get('p_biografia');

            if ($persona) {
                $persona->setPBiografia($biografia);
                $entityManager->persist($persona);
                $entityManager->flush();
            }


            return $this->redirectToRoute('app_prov_serv', ['id' => $id]);
        }

        return $this->render('asap_services/entornos/proveedor/biografia.html.twig', [
            'controller_name' => 'ProveedorController',
            'persona' => $persona
        ]);
    }

    #[Route('/proveedor/{id}/servicios', name: 'app_prov_serv')]
    public function servicios($id, Request $request, Persona $persona, ServicioRepository $servicios, EntityManagerInterface $entityManager): Response
    {
        
        if ($request->isMethod('POST')) {
            $serviciosselect = $request->get('servicios', []);

            if ($persona) {
                foreach ($serviciosselect as $servicioid) {
                    $servicio = $servicios->find($servicioid);

                    if ($servicio) {
                        $personaservicio = new PersonaServicio;
                        $personaservicio->setIdPersona($persona);
                        $personaservicio->setIdServicio($servicio);
                        $entityManager->persist($personaservicio);
                    }
                }
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_proveedor');
        }

        return $this->render('asap_services/entornos/proveedor/ofrecer_servicios.html.twig', [
            'controller_name' => 'ProveedorController',
            'persona' => $persona,
            'servicios' => $servicios->findAll(),

        ]);
    }

    #[Route('/proveedor/{id}/perfil', name: 'app_prov_perfil')]
    public function perfil(Persona $persona): Response
    {
        
        return $this->render('asap_services/entornos/proveedor/mi_perfil.html.twig', [
            'proveedor' => $persona,

        ]);
    }

    #[Route('/proveedor/{id}/historialserv', name: 'app_prov_histserv')]
    public function historialservicios(Persona $persona): Response
    {
        $histservicios = $persona->getHistservproveedor();

        return $this->render('asap_services/entornos/proveedor/historialservicios.html.twig', [
            'historiales' => $histservicios,

        ]);
    }

    #[Route('/proveedor/{id}/ganancias', name: 'app_prov_ganancias')]
    public function ganancias(Persona $persona): Response
    {

            return $this->render('asap_services/entornos/proveedor/ganancias.html.twig', [
            'proveedor' => $persona,

        ]);
    }

    #[Route('/proveedor/{id}/chatclientes', name: 'app_prov_chat')]
    public function chatclientes(Persona $persona): Response
    {

        return $this->render('asap_services/entornos/proveedor/chatclientes.html.twig', [
            'proveedor' => $persona,

        ]);
    }

    #[Route('/proveedor/preguntas', name: 'app_prov_preguntas')]
    public function preguntas(): Response
    {

        return $this->render('asap_services/entornos/proveedor/preguntas_frecuentes.html.twig', []);
    }

    #[Route('/proveedor/{id}/metcobro', name: 'app_prov_metcobro')]
    public function metcobro(Request $request,$id, Persona $persona, MetodocobroRepository $metodocobros): Response
    {
       
        if ($request->isMethod('POST')) {
            $metodocobroselect = $request->get('metodocobros', []);

            if ($persona) {
                $idmet = !empty($metodocobroselect) ? $metodocobroselect[0] : null;
                return $this->redirectToRoute('app_prov_metcobro_numcuenta', ['id' => $persona->getId(), 'idmet'=>$idmet]);
            }

            
        }

        return $this->render('asap_services/entornos/proveedor/metodocobro.html.twig', [
            'proveedor' => $persona,
            'metodocobros' => $metodocobros->findAll()
        ]);
    }

    #[Route('/proveedor/{id}/metcobro/{idmet}/numcuenta', name: 'app_prov_metcobro_numcuenta')]
    public function numcuenta(Request $request, $idmet, Persona $persona, MetodocobroRepository $metodocobros, EntityManagerInterface $entityManager): Response
    {
        
        if($persona){
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
    
                return $this->redirectToRoute('app_proveedor');
            }
        }
        
        return $this->render('asap_services/entornos/proveedor/numerocuenta.html.twig', [
            'proveedor' => $persona,
            'metodocobros' => $metodocobros->findAll()
        ]);
    }

    #[Route('/proveedor/ayuda', name: 'app_prov_ayuda')]
    public function ayuda(): Response
    {
        return $this->render('asap_services/entornos/proveedor/ayuda.html.twig', []);
    }

    // CREADO POR FRONTEND PARA VISUALIZAR LAS VISTAS - EKIMINAR SI ES NECESARIO
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
    // FIN


}
