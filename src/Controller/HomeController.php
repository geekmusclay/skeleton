<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use Geekmusclay\Framework\Common\AbstractController;
use Geekmusclay\Framework\Renderer\TwigRenderer;
use Geekmusclay\Router\Core\JsonResponse;
use GuzzleHttp\Psr7\Response;
use Geekmusclay\Router\Attribute\Route;

final class HomeController extends AbstractController
{
    /**
     * @param TwigRenderer $renderer The controller renderer
     */
    public function __construct(TwigRenderer $renderer)
    {
        parent::__construct($renderer);
    }

    /**
     * Controller index route.
     */
    #[Route(path: '/', name: 'app.home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route(path: '/api', name: 'app.api')]
    public function api(): JsonResponse
    {
        $res = [];
        foreach (Article::all(10) as $article) {
            $res[] = $article->serialize();
        }

        return $this->json($res);
    }
}
