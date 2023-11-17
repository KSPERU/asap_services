<?php

namespace App\Security;

use App\Entity\Persona;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class MyFacebookAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{
    private $clientRegistry;
    private $entityManager;
    private $router;
    private $userPasswordHasherInterface;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    public function authenticate(Request $request): Passport
    {
        $entorno = $request->attributes->get('entorno');

        $roles = ["ROLE_CLI"];
        $client = $this->clientRegistry->getClient('facebook_cliente');

        if ($entorno === 'proveedor') {
            $roles = ["ROLE_PROV"];
            $client = $this->clientRegistry->getClient('facebook_proveedor');
        }

        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client, $roles) {
                /** @var FacebookUser $facebookUser */
                $facebookUser = $client->fetchUserFromToken($accessToken);

                $email = $facebookUser->getEmail();

                // 1) have they logged in with Facebook before? Easy!
                $existingUser = $this->entityManager->getRepository(Usuario::class)->findOneBy(['facebookId' => $facebookUser->getId()]);

                if ($existingUser) {
                    return $existingUser;
                }

                // 2) do we have a matching user by email?
                $user = $this->entityManager->getRepository(Usuario::class)->findOneBy(['email' => $email]);

                // 3) Maybe you just want to "register" them by creating
                // a User object

                if ($user) {
                    $user->setFacebookId($facebookUser->getId());
                } else {
                    $user = new Usuario();
                    $person = new Persona();
                    $person->setPNombre($facebookUser->getFirstName());
                    $person->setPApellido($facebookUser->getLastName());
                    $person->setPContacto('999999999');
                    $person->setPDireccion('No definida');
                    $user->setIdPersona($person);
                    $user->setFacebookId($facebookUser->getId());
                    $user->setEmail($email);
                    $user->setPassword($this->userPasswordHasherInterface->hashPassword(
                        $user,
                        $facebookUser->getId()
                    ));
                    $user->setRoles($roles);
                }

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $usuario = $token->getUser();
        $roles = $usuario->getRoles();

        if (in_array("ROLE_CLI", $roles)) {
            $targetUrl = $this->router->generate('app_asap_services_entornos_cliente_inicio');
            return new RedirectResponse($targetUrl);
        } else {
            $targetUrl = $this->router->generate('app_asap_services_entornos_proveedor_inicio');
            return new RedirectResponse($targetUrl);
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}
