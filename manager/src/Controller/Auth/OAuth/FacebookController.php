<?php

declare(strict_types=1);

namespace App\Controller\Auth\OAuth;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends AbstractController
{
    /**
     * @Route("/oauth/facebook", name="oauth.facebook")
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function connect(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('facebook')
            ->redirect(['public_profile']);
    }

    /**
     * @Route("/oauth/facebook/check", name="oauth.facebook_check")
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function check(Request $request, ClientRegistry $clientRegistry): Response
    {
        return $this->redirectToRoute('home');
//        // ** if you want to *authenticate* the user, then
//        // leave this method blank and create a Guard authenticator
//        // (read below)
//
//        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient $client */
//        $client = $clientRegistry->getClient('facebook_main');
//
//        try {
//            // the exact class depends on which provider you're using
//            /** @var \League\OAuth2\Client\Provider\FacebookUser $user */
//            $user = $client->fetchUser();
//
//            // do something with all this new power!
//            // e.g. $name = $user->getFirstName();
//            var_dump($user); die;
//            // ...
//        } catch (IdentityProviderException $e) {
//            // something went wrong!
//            // probably you should return the reason to the user
//            var_dump($e->getMessage()); die;
//        }
    }
}
