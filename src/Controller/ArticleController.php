<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use Geekmusclay\Framework\Common\AbstractController;
use Geekmusclay\Framework\Core\Encrypter;
use Geekmusclay\Framework\Interfaces\RendererInterface;
use Geekmusclay\Router\Attribute\Route;
use Geekmusclay\Router\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

use function count;

#[Route(path: '/articles')]
final class ArticleController extends AbstractController
{
    /** @var Encrypter $encrypter Contain the application encryption service */
    private Encrypter $encrypter;

    /** @var RendererInterface $renderer Defdault renderer */
    protected RendererInterface $renderer;

    /** @var RouterInterface $router Application router */
    protected RouterInterface $router;

    /**
     * Controller constructor
     *
     * @param RendererInterface $renderer  Defdault renderer
     * @param RouterInterface   $router    Application router
     * @param Encrypter         $encrypter The application encryption service
     */
    public function __construct(
        RendererInterface $renderer,
        RouterInterface $router,
        Encrypter $encrypter
    ) {
        $this->renderer  = $renderer;
        $this->router    = $router;
        $this->encrypter = $encrypter;
    }

    #[Route(
        path: '/',
        name: 'article.index'
    )]
    public function index(): Response
    {
        $articles = Article::all();

        return $this->render(
            'artiicles/index.html.twig',
            [
                'articles' => $articles,
            ]
        );
    }

    #[Route(
        path: '/add',
        name: 'article.add'
    )]
    public function add(): Response
    {
        return $this->render('articles/add.html.twig');
    }

    #[Route(
        method: 'POST',
        path: '/store',
        name: 'article.store'
    )]
    public function store(Request $request): Response
    {
        $data = $request->getParsedBody();
        if (null === $data || 0 === count($data)) {
            return $this->render(
                'articles/add.html.twig',
                [
                    'errors' => [
                        'Wrong form',
                    ],
                ]
            );
        }

        $article = new Article($data);
        try {
            $article->save();
        } catch (Throwable $e) {
            // Do something with the error

            return $this->redirect($request, 'app.home');
        }

        return $this->render(
            'articles/store.html.twig',
            [
                'article' => $article,
            ]
        );
    }

    #[Route(
        path: '/:id',
        name: 'article.details',
        with: [
            'id' => '[a-zA-Z0-9]+',
        ]
    )]
    public function detail(ServerRequestInterface $request, string $id): Response
    {
        $decrypt = $this->encrypter->decrypt($id);
        if (-1 === $decrypt) {
            return $this->redirect($request, 'app.home');
        }

        $article = Article::find($decrypt);
        if (false === $article) {
            return $this->redirect($request, 'app.home');
        }

        return $this->render(
            'articles/details.html.twig',
            [
                'article' => $article,
            ]
        );
    }

    #[Route(
        method: 'GET',
        path: '/edit/:id',
        name: 'article.edit',
        with: [
            'id' => '[a-zA-Z0-9]+',
        ]
    )]
    public function edit(Request $request, string $id): Response
    {
        $decrypt = $this->encrypter->decrypt($id);
        if (-1 === $decrypt) {
            return $this->redirect($request, 'app.home');
        }

        $article = Article::find($decrypt);
        if (null === $article) {
            return $this->redirect($request, 'app.home');
        }

        return $this->render(
            'articles/store.html.twig',
            [
                'article' => $article,
            ]
        );
    }

    #[Route(
        method: 'POST',
        path: '/update/:id',
        name: 'article.update',
        with: [
            'id' => '[a-zA-Z0-9]+',
        ]
    )]
    public function update(Request $request, string $id): Response
    {
        $decrypt = $this->encrypter->decrypt($id);
        if (-1 === $decrypt) {
            return $this->redirect($request, 'app.home');
        }

        $data = $request->getParsedBody();
        if (null === $data || 0 === count($data)) {
            return $this->render(
                'articles/edit.html.twig',
                [
                    'errors' => [
                        'Wrong form',
                    ],
                ]
            );
        }

        $article = new Article($data);
        try {
            $article->save();
        } catch (Throwable $e) {
            // Do something with the error

            return $this->redirect($request, 'app.home');
        }

        return $this->render(
            'articles/store.html.twig',
            [
                'article' => $article,
            ]
        );
    }
}
