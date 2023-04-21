<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use Geekmusclay\Framework\Common\AbstractController;
use Geekmusclay\Router\Attribute\Route;
use GuzzleHttp\Psr7\Response;

/**
 * Sample HomeController class
 */
final class HomeController extends AbstractController
{
    /**
     * Sample controller route index.
     */
    #[Route(
        path: '/',
        name: 'app.home'
    )]
    public function index(): Response
    {
        return $this->render(
            'home/index.html.twig',
            [
                'articles' => Article::all(10),
            ]
        );
    }
}
