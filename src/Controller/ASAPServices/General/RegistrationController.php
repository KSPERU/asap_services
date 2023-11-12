<?php

namespace App\Controller\ASAPServices\General;

use App\Entity\Persona;
use App\Entity\Usuario;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use App\Repository\UsuarioRepository;
use App\Form\RegistrationProvFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/cliente/registro', name: 'app_asap_services_general_cliente_registro')]
    public function registerCli(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Usuario();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_CLI"]);

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_asap_services_general_cliente_control_correo',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('confirmacion@ksperu.com', 'Confirmación KSPERU'))
                    ->to($user->getEmail())
                    ->subject('Por favor, confirme su correo electrónico de cliente.')
                    ->htmlTemplate('asap_services/general/registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_asap_services_general_login', ['entorno' => 'cliente']);
        }

        return $this->render('asap_services/general/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    # Falta el cargador de imagenes
    #[Route('/proveedor/registro', name: 'app_asap_services_general_proveedor_registro')]
    public function registerProv(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Usuario();
        $form = $this->createForm(RegistrationProvFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_PROV"]);

            # Procedimiento de almacenado de archivos
            $persona = $user->getIdPersona();
            $archivo_foto = $form['idPersona']['p_foto']->getData();
            $archivo_cv = $form['idPersona']['p_cv']->getData();
            $archivo_ap = $form['idPersona']['p_antpen']->getData();
            $archivo_cc = $form['idPersona']['p_cert']->getData();

            if ($archivo_foto !== null) {
                $codigo_unico = uniqid() . time();
                $destino = $this->getParameter('kernel.project_dir') . '/public/img';
                $archivo_foto->move($destino, $codigo_unico . $archivo_foto->getClientOriginalName());
                $persona->setPFoto($codigo_unico . $archivo_foto->getClientOriginalName());
            }

            if ($archivo_cv !== null) {
                $codigo_unico = uniqid() . time();
                $destino = $this->getParameter('kernel.project_dir') . '/public/docs';
                $archivo_cv->move($destino, $codigo_unico . $archivo_foto->getClientOriginalName());
                $persona->setPCv($codigo_unico . $archivo_foto->getClientOriginalName());
            }

            if ($archivo_ap !== null) {
                $codigo_unico = uniqid() . time();
                $destino = $this->getParameter('kernel.project_dir') . '/public/docs';
                $archivo_ap->move($destino, $codigo_unico . $archivo_foto->getClientOriginalName());
                $persona->setPAntpen($codigo_unico . $archivo_foto->getClientOriginalName());
            }

            if ($archivo_cc !== null) {
                $codigo_unico = uniqid() . time();
                $destino = $this->getParameter('kernel.project_dir') . '/public/docs';
                $archivo_cc->move($destino, $codigo_unico . $archivo_foto->getClientOriginalName());
                $persona->setPCert($codigo_unico . $archivo_foto->getClientOriginalName());
            }

            # Validación
            $user->setIdPersona($persona);

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_asap_services_general_proveedor_control_correo',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('confirmacion@ksperu.com', 'Confirmación KSPERU'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email de proveedor')
                    ->htmlTemplate('asap_services/general/registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_asap_services_general_login', ['entorno' => 'proveedor']);
        }

        return $this->render('asap_services/general/registration/registro_proveedor.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/cliente/control/correo', name: 'app_asap_services_general_cliente_control_correo')]
    public function controlCorreoCli(Request $request, TranslatorInterface $translator, UsuarioRepository $usuarioRepository): Response
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_asap_services_general_cliente_registro');
        }

        $user = $usuarioRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_asap_services_general_cliente_registro');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_asap_services_general_cliente_registro');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Tu dirección de correo electrónico ha sido verificada.');

        return $this->redirectToRoute('app_asap_services_general_login', ['entorno' => 'cliente']);
    }

    #[Route('/proveedor/control/correo', name: 'app_asap_services_general_proveedor_control_correo')]
    public function controlCorreo(Request $request, TranslatorInterface $translator, UsuarioRepository $usuarioRepository): Response
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_asap_services_general_proveedor_registro');
        }

        $user = $usuarioRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_asap_services_general_proveedor_registro');
        }
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_asap_services_general_proveedor_registro');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_asap_services_general_login', ['entorno' => 'proveedor']);
    }

    // By Frontend - Solo para visualizar temporalmente los terminos y condiciones
    #[Route('/terminos', name: 'app_asap_services_general_terminos')]
    public function terminos(): Response
    {
        return $this->render('asap_services/general/registration/terminos_condiciones.html.twig', []);
    }
}
