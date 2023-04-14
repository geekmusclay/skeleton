<?php

declare(strict_types=1);

require "../vendor/autoload.php";

use Tracy\Debugger;

use Geekmusclay\ORM\DB;
use function Http\Response\send;
use Geekmusclay\DI\Core\Container;
use GuzzleHttp\Psr7\ServerRequest;
use Geekmusclay\Framework\Core\App;
use Geekmusclay\Router\Core\Router;

use Geekmusclay\Framework\Core\DotEnv;
use Geekmusclay\Framework\Renderer\TwigRenderer;
use Geekmusclay\Router\Interfaces\RouterInterface;
use Geekmusclay\Framework\Factory\TwigRendererFactory;
use Geekmusclay\Framework\Interfaces\RendererInterface;

Debugger::enable();

$env = getenv('APP_ENV');
$path = __DIR__ . '/.env';
if ((false === $env || $env === 'dev') && is_file($path)) {
    // Loading environement variables
    DotEnv::load($path);
    Debugger::$productionMode = false;
}

// Instanciate application DI Container
$container = new Container();

// Instanciate db connector
$db = new DB(getenv('DATABASE_URL'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));
$container->set(DB::class, $db);

// Register application router into container
$router = $container->get(Router::class, [$container]);
// Register our router as RouterInterface for injections
$container->set(RouterInterface::class, $router);

// Register views root dir
$container->set('view.path', __DIR__ . DS . '..' . DS .'templates');
// Register twig configuration
$container->set('twig.config', []);
// Instnciating TwigRenderer by factory
$renderer = $container->get(TwigRendererFactory::class);
// Register TwigRenderer for injections
$container->set(TwigRenderer::class, $renderer);
// Default renderer
$container->set(RendererInterface::class, $renderer);

$app = new App($container);

$path = __DIR__ . DS . '..' . DS . 'src' . DS . 'Controller';
$app->registerDir(
    $path,
    'App\\Controller'
);

$response = $app->run(ServerRequest::fromGlobals());

send($response);
