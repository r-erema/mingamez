<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\Exception\EntityNotFoundException;
use App\Application\UseCase\User\ResetPassword\Request\Command;
use App\Application\UseCase\User\ResetPassword\Reset\Command as ResetResetCommand;
use App\Application\UseCase\User\SignUp\Confirm\Command as ConfirmCommand;
use App\Application\UseCase\User\SignUp\Request\Command as RequestCommand;
use App\Infrastructure\Http\Form\ResetPasswordRequestForm;
use App\Infrastructure\Http\Form\ResetPasswordResetForm;
use App\Infrastructure\Http\Form\SignUpRequestForm;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthController extends AbstractController
{

    public const SIGN_UP_ROUTE_NAME = 'auth.sign_up';
    public const CONFIRM_ROUTE_NAME = 'auth.confirm';
    public const SIGN_IN_ROUTE_NAME = 'auth.sign_in';
    public const LOGOUT_ROUTE_NAME = 'auth.logout';
    public const FB_CONNECT_ROUTE_NAME = 'auth.facebook_connect';
    public const FB_CHECK_ROUTE_NAME = 'auth.facebook_check';
    public const RESET_REQUEST_ROUTE_NAME = 'auth.reset_request';
    public const RESET_RESET_ROUTE_NAME = 'auth.reset_reset';

    private TranslatorInterface $translator;
    private LoggerInterface $logger;
    private MessageBusInterface $bus;

    public function __construct(TranslatorInterface $translator, LoggerInterface $logger, MessageBusInterface $bus)
    {
        $this->translator = $translator;
        $this->logger = $logger;
        $this->bus = $bus;
    }

    /**
     * @Route("/sign-up", name=AuthController::SIGN_UP_ROUTE_NAME)
     * @param Request $request
     * @return Response
     */
    public function request(Request $request): Response
    {
        $form = $this->createForm(SignUpRequestForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var RequestCommand $command */
                $command = $form->getData();
                $this->bus->dispatch($command);
                $this->addFlash('success', $this->translator->trans('Check your email', [], 'common'));
                return $this->redirectToRoute(IndexController::INDEX_ROUTE_NAME);
            } catch (RuntimeException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'exceptions'));
            }
        }

        return $this->render('app/auth/sign_up.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm/{token}", name=AuthController::CONFIRM_ROUTE_NAME)
     * @param string $token
     * @return Response
     */
    public function confirm(string $token): Response
    {
        $command = new ConfirmCommand($token);
        try {
            $this->bus->dispatch($command);
            $this->addFlash('success', $this->translator->trans('Email is successfully confirmed', [], 'common'));
            return $this->redirectToRoute(IndexController::INDEX_ROUTE_NAME);
        }  catch (RuntimeException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'exceptions'));
            return $this->redirectToRoute(IndexController::INDEX_ROUTE_NAME);
        }
    }

    /**
     * @Route("/sign-in", name=AuthController::SIGN_IN_ROUTE_NAME)
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('app/auth/sign_in.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name=AuthController::LOGOUT_ROUTE_NAME, methods={"GET"})
     */
    public function logout(): Response
    {
        return new Response();
    }

    /**
     * @Route("/oauth/facebook", name=AuthController::FB_CONNECT_ROUTE_NAME)
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connect(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry->getClient('facebook')->redirect(['public_profile'], []);
    }

    /**
     * @Route("/oauth/facebook/check", name=AuthController::FB_CHECK_ROUTE_NAME)
     */
    public function check(): Response
    {
        return $this->redirectToRoute(IndexController::INDEX_ROUTE_NAME);
    }

    /**
     * @Route("/reset", name=AuthController::RESET_REQUEST_ROUTE_NAME)
     * @param Request $request
     * @return Response
     * @throws EntityNotFoundException
     */
    public function resetPasswordRequest(Request $request): Response
    {
        $form = $this->createForm(ResetPasswordRequestForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var Command $command */
                $command = $form->getData();
                $this->bus->dispatch($command);
                $this->addFlash('success', $this->translator->trans('Check your email', [], 'common'));
                return $this->redirectToRoute(IndexController::INDEX_ROUTE_NAME);
            } catch (RuntimeException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'exceptions'));
            }
        }

        return $this->render('app/auth/reset_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/reset/{token}", name=AuthController::RESET_RESET_ROUTE_NAME)
     * @param string $token
     * @param Request $request
     * @return Response
     */
    public function resetPasswordReset(string $token, Request $request): Response
    {
        $form = $this->createForm(ResetPasswordResetForm::class, null, ['token' => $token]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var ResetResetCommand $command */
                $command = $form->getData();
                $this->bus->dispatch($command);
                $this->addFlash('success', $this->translator->trans('Password is successfully changed', [], 'common'));
                return $this->redirectToRoute(IndexController::INDEX_ROUTE_NAME);
            } catch (RuntimeException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'exceptions'));
            }
        }

        return $this->render('app/auth/reset_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
