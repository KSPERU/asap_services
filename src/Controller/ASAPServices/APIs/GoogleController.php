<?php

namespace App\Controller\ASAPServices\APIs;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    #[Route('/{entorno}/connect/google', name: 'connect_google_start')]
    public function connectAction(ClientRegistry $clientRegistry, $entorno)
    {
        $client = $clientRegistry->getClient('google_cliente');
        if ($entorno == 'proveedor') {
            $client = $clientRegistry->getClient('google_proveedor');
        }

        return $client->redirect(
            [
                'profile',
                'email'
            ],
            []
        );
    }

    #[Route('/{entorno}/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry, $entorno)
    {
    }
}
