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
        return $this->render('proveedor/index.html.twig', [
            'controller_name' => 'ProveedorController',
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
}
