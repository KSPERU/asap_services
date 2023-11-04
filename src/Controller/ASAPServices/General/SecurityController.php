<?php

namespace App\Controller\ASAPServices\General;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /*     #[Route(path: '/cliente/login', name: 'app_asap_services_general_cliente_login')]
    public function loginCli(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('asap_services/general/security/login_cli.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/proveedor/login', name: 'app_asap_services_general_proveedor_login')]
    public function loginProv(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('asap_services/general/security/login_prov.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    } */

    #[Route(path: '/{entorno}/login/', name: 'app_asap_services_general_login')]
    public function identificarse($entorno, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('asap_services/general/security/login.html.twig', [
            'entorno' => $entorno,
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/desconectar', name: 'app_asap_services_general_logout')]
    public function logout(): void
    {
        throw new \LogicException('Este método puede ser invisible, ¡será interceptado por la llave de cierre en tu firewall como un ninja de la ciberseguridad!');
    }
}
