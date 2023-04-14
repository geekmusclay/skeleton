<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use Geekmusclay\Framework\Common\AbstractController;
use Geekmusclay\Framework\Renderer\TwigRenderer;
use Geekmusclay\Router\Core\JsonResponse;
use GuzzleHttp\Psr7\Response;
use Geekmusclay\Router\Attribute\Route;
use Psr\Http\Message\ServerRequestInterface as Request;

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
    public function api(Request $request): JsonResponse
    {
        $limit = 10;
        $params = $request->getQueryParams();
        if (true === isset($params['limit'])) {
            $limit = (int) $params['limit'];
        }

        $res = [];
        foreach (Article::all($limit) as $article) {
            $res[] = $article->serialize();
        }

        return $this->json($res);
    }
}
