<?php

declare(strict_types=1);

namespace App\Security\OAuth;

use App\Model\User\UseCase\Network\Auth;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookAuthenticator extends SocialAuthenticator
{
    private $urlGenerator;
    private $clients;
    private $handler;

    /**
     * FacebookAuthenticator constructor.
     * @param $urlGenerator
     * @param $clients
     * @param $handler
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, ClientRegistry $clients, Auth\Handler $handler)
    {
        $this->urlGenerator = $urlGenerator;
        $this->clients = $clients;
        $this->handler = $handler;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'oauth.facebook_check';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $facebookUser = $this->getFacebookClient()->fetchUserFromToken($credentials);

        $network = 'facebook';
        $id = $facebookUser->getId();
        $username = $network . ':' . $id;

        try {
            return $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            $this->handler->handle(new Auth\Command($network, $id));
            return $userProvider->loadUserByUsername($username);
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate('auth.login'));
    }

    /**
     * @return FacebookClient|OAuth2Client
     */
    private function getFacebookClient(): FacebookClient
    {
        return $this->clients->getClient('facebook');
    }
}
