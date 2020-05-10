<?php

declare(strict_types=1);

namespace App\Application\Security;

use App\Application\UseCase\User\SignUp\Network\Command;
use App\Application\UseCase\User\SignUp\Network\Handler;
use App\Infrastructure\Http\Controller\AuthController;
use App\Infrastructure\Http\Controller\IndexController;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
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
    private UrlGeneratorInterface $urlGenerator;
    private ClientRegistry $clients;
    private Handler $handler;

    public function __construct(UrlGeneratorInterface $urlGenerator, ClientRegistry $clients, Handler $handler)
    {
        $this->urlGenerator = $urlGenerator;
        $this->clients = $clients;
        $this->handler = $handler;
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === AuthController::FB_CHECK_ROUTE_NAME;
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

        $command = new Command($network, $id);

        try {
            return $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            $this->handler->handle($command);
            return $userProvider->loadUserByUsername($username);
        }
    }

    private function getFacebookClient(): OAuth2ClientInterface
    {
        return $this->clients->getClient('facebook');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate(IndexController::INDEX_ROUTE_NAME));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}
