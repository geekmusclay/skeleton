<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Model\Article;
use Geekmusclay\Framework\Common\AbstractController;
use Geekmusclay\Router\Attribute\Route;
use Geekmusclay\Router\Core\JsonResponse;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Sample Article API controller
 */
#[Route(path: '/api/v1/article')]
final class ArticleController extends AbstractController
{
    /**
     * Controller index function.
     *
     * @param Request $request The server request
     */
    #[Route(path: '/', name: 'article.api.index')]
    public function index(Request $request): JsonResponse
    {
        $limit = 10;

        /** @var string[] $params Potential request GET params */
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
