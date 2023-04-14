<?php 

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use GuzzleHttp\Psr7\Response;
use Geekmusclay\Router\Attribute\Route;
use Geekmusclay\Framework\Common\AbstractController;
use Psr\Http\Message\ServerRequestInterface as Request;


#[Route(path: '/article')]
final class ArticleController extends AbstractController
{
    #[Route(path: '/:id', name: 'article.details', with: [
        'id' => '[0-9]+'
    ])]
    public function detail(int $id): Response
    {
        $article = Article::find($id);
        if (false === $article) {
            // Error
        }

        return $this->render('articles/details.html.twig', [
            'article' => $article
        ]);
    }

    #[Route(path: '/add', name: 'article.add')]
    public function add(): Response
    {
        return $this->render('articles/add.html.twig');
    }

    #[Route(method: 'POST', path: '/store', name: 'article.store')]
    public function store(Request $request): Response
    {
        $data = $request->getParsedBody();
        if (null === $data || 0 === count($data)) {
            return $this->render('articles/add.html.twig', [
                'errors' => [
                    'Wrong form'
                ]
            ]);
        }

        $article = new Article($data);
        // $article->save() @TODO implement this

        return $this->render('articles/store.html.twig', [
            'article' => $article
        ]);
    }
}