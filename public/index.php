<?php

declare(strict_types=1);

require "../vendor/autoload.php";

use App\Controller\HomeController;
use function Http\Response\send;
use Geekmusclay\DI\Core\Container;
use GuzzleHttp\Psr7\ServerRequest;
use Geekmusclay\Framework\Core\App;
use Geekmusclay\Router\Core\Router;
use Geekmusclay\Framework\Core\DotEnv;
use Geekmusclay\Framework\Renderer\TwigRenderer;

use Geekmusclay\Router\Interfaces\RouterInterface;
use Geekmusclay\Framework\Factory\TwigRendererFactory;

$env = getenv('APP_ENV');
$path = __DIR__ . '/.env';
if ((false === $env || $env === 'dev') && is_file($path)) {
    // Loading environement variables
    DotEnv::load($path);
}

// Instanciate application DI Container
$container = new Container();

// Register application router into container
$router = $container->get(Router::class, [$container]);
// Register our router as RouterInterface for injections
$container->set(RouterInterface::class, $router);

// Register views root dir
$container->set('view.path', __DIR__ . '\..\templates');
// Register twig configuration
$container->set('twig.config', []);
// Instnciating TwigRenderer by factory
$renderer = $container->get(TwigRendererFactory::class);
// Register TwigRenderer for injections
$container->set(TwigRenderer::class, $renderer);

$app = new App($container);
$app->get('/', [HomeController::class, 'index'], 'app.home');

try {
    $response = $app->run(ServerRequest::fromGlobals());
} catch (Throwable $e) {
    dd($e);
}

send($response);
