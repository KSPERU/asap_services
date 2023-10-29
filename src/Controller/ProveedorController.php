<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\ProveedorType;
use App\Entity\PersonaServicio;
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
            'email'=>$user->getUserIdentifier(),
        ]);
        $persona = $proveedor->getIdPersona();
        $id = $persona->getId();
        if($persona->getPBiografia()===null){
            return $this->redirectToRoute('app_prov_bio', ['id' => $id]); 
        } 
        return $this->render('proveedor/inicio_proveedor.html.twig', [
            'controller_name' => 'ProveedorController',
            'proveedor' => $persona,
        ]);
    }

    #[Route('/proveedor/{id}/edit', name: 'app_proveedor_edit')]
    public function edit(Request $request, Persona $persona, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(ProveedorType::class, $persona);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $archivo = $form['p_foto']->getData();
            if($archivo!==null){
                $destino = $this->getParameter('kernel.project_dir').'/public/img';
                $archivo->move($destino, $archivo->getClientOriginalName());
                $persona->setPFoto($archivo->getClientOriginalName());
            }
            $archivo = $form['p_cv']->getData();
            if($archivo!==null){
                $destino = $this->getParameter('kernel.project_dir').'/public/doc';
                $archivo->move($destino, $archivo->getClientOriginalName());
                $persona->setPFoto($archivo->getClientOriginalName());
            }
            $archivo = $form['p_antpen']->getData();
            if($archivo!==null){
                $destino = $this->getParameter('kernel.project_dir').'/public/doc';
                $archivo->move($destino, $archivo->getClientOriginalName());
                $persona->setPFoto($archivo->getClientOriginalName());
            }
            $archivo = $form['p_cert']->getData();
            if($archivo!==null){
                $destino = $this->getParameter('kernel.project_dir').'/public/doc';
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
        return $this->render('proveedor\edit.html.twig', [
            'form' => $form,
            'persona'=>$persona,
            
        ]);
    }

    #[Route('/proveedor/{id}/biografia', name: 'app_prov_bio')]
    public function biografia($id, Request $request, PersonaRepository $personas, EntityManagerInterface $entityManager): Response
    {
        $persona = $personas->find($id);

        if ($request->isMethod('POST')) {
            $biografia = $request->get('p_biografia');

            if ($persona) {
                $persona->setPBiografia($biografia);
                $entityManager->persist($persona);
                $entityManager->flush();
            }

            
            return $this->redirectToRoute('app_prov_serv', ['id' => $id]);
        }

        return $this->render('proveedor/biografia.html.twig', [
            'controller_name' => 'ProveedorController',
            'persona' => $persona
        ]);
    }

    #[Route('/proveedor/{id}/servicios', name: 'app_prov_serv')]
    public function servicios($id, Request $request, PersonaRepository $personas, ServicioRepository $servicios, EntityManagerInterface $entityManager): Response
    {
        $persona = $personas->find($id);
        
        if ($request->isMethod('POST')) {
            $serviciosselect = $request->get('servicios',[]);

            if ($persona) {
                foreach($serviciosselect as $servicioid){
                    $servicio = $servicios->find($servicioid);

                    if($servicio){
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

        return $this->render('proveedor/ofrecer_servicios.html.twig', [
            'controller_name' => 'ProveedorController',
            'persona' => $persona,
            'servicios' => $servicios->findAll(),
            
        ]);
    }

    #[Route('/proveedor/{id}/perfil', name: 'app_prov_perfil')]
    public function perfil($id, PersonaRepository $personas): Response
    {
        $proveedor = $personas->find($id);
        
        
        return $this->render('proveedor/mi_perfil.html.twig', [
            'proveedor' => $proveedor,
            
        ]);
    }

    #[Route('/proveedor/{id}/historialserv', name: 'app_prov_histserv')]
    public function historialservicios($id, PersonaRepository $personas): Response
    {
        $proveedor = $personas->find($id);
        $histservicios = $proveedor->getHistservproveedor();
        
        return $this->render('proveedor/historialservicios.html.twig', [
            'historiales' => $histservicios,
            
        ]);
    }

    #[Route('/proveedor/{id}/ganancias', name: 'app_prov_ganancias')]
    public function ganancias($id, PersonaRepository $personas): Response
    {
        $proveedor = $personas->find($id);
        
        
        return $this->render('proveedor/ganancias.html.twig', [
            'proveedor' => $proveedor,
            
        ]);
    }

    #[Route('/proveedor/{id}/chatclientes', name: 'app_prov_chat')]
    public function chatclientes($id, PersonaRepository $personas): Response
    {
        $proveedor = $personas->find($id);
        
        
        return $this->render('proveedor/chatclientes.html.twig', [
            'proveedor' => $proveedor,
            
        ]);
    }

    #[Route('/proveedor/preguntas', name: 'app_prov_preguntas')]
    public function preguntas(): Response
    {
      
        return $this->render('proveedor/preguntas_frecuentes.html.twig', [
            
        ]);
    }

    #[Route('/proveedor/{id}/metcrobro', name: 'app_prov_metcobro')]
    public function metcobro($id, PersonaRepository $personas): Response
    {
        $proveedor = $personas->find($id);

        return $this->render('proveedor/metodocobro.html.twig', [
            'proveedor' => $proveedor,
        ]);
    }

    #[Route('/proveedor/ayuda', name: 'app_prov_ayuda')]
    public function ayuda(): Response
    {
        return $this->render('proveedor/ayuda.html.twig', [
            
        ]);
    }

    #[Route('/proveedor/historial_servicios', name: 'app_prov_historial_servicios')]
    public function historial_servicios(): Response
    {
        return $this->render('proveedor/historial_servicios.html.twig', [
            
        ]);
    }
    #[Route('/proveedor/chatclientes', name: 'app_prov_chatclientes')]
    public function chatclientesX(): Response
    {
        return $this->render('proveedor/chatclientes.html.twig', [
            
        ]);
    }
}
