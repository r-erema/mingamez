<?php

use App\Kernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Spiral\Goridge;
use Spiral\RoadRunner;

require __DIR__ . '/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$worker = new RoadRunner\Worker(new Goridge\StreamRelay(STDIN, STDOUT));
$psr7 = new RoadRunner\PSR7Client($worker);
$symfonyRequestFactory = new HttpFoundationFactory();

$psr17Factory = new Psr17Factory();
$psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

while ($req = $psr7->acceptRequest()) {
    try {
        $request = $symfonyRequestFactory->createRequest($req);
        if ($request->cookies->has(session_name())) {
            session_id($request->cookies->get(session_name()));
        }
        $response = $kernel->handle($request);

        $cookieOptions = $kernel->getContainer()->getParameter('session.storage.options');
        $response->headers->setCookie(new Cookie(
                session_name(),
                session_id(),
                $cookieOptions['cookie_lifetime'] ?? 0,
                $cookieOptions['cookie_path'] ?? '/',
                $cookieOptions['cookie_domain'] ?? '',
                ($cookieOptions['cookie_secure'] ?? 'auto') === 'auto' ? $request->isSecure() : (bool)($cookieOptions['cookie_secure'] ?? 'auto'),
                $cookieOptions['cookie_httponly'] ?? true,
                false,
                $cookieOptions['cookie_samesite'] ?? null
            )
        );

        $psr7->respond($psrHttpFactory->createResponse($response));
        $kernel->terminate($request, $response);
    } catch (Throwable $e) {
        $psr7->getWorker()->error((string) $e);
    }

}

