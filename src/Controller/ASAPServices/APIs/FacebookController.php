<?php

namespace App\Controller\ASAPServices\APIs;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends AbstractController
{
    #[Route('/{entorno}/connect/facebook', name: 'connect_facebook_start')]
    public function connectAction(ClientRegistry $clientRegistry, $entorno)
    {
        $client = $clientRegistry->getClient('facebook_cliente');
        if ($entorno == 'proveedor') {
            $client = $clientRegistry->getClient('facebook_proveedor');
        }

        return $client->redirect(
            [
                'public_profile',
                'email'
            ],
            []
        );
    }

    #[Route('/{entorno}/connect/facebook/check', name: 'connect_facebook_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry, $entorno)
    {
    }
}
