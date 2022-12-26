<?php

declare(strict_types=1);

namespace App\Controller;

use Geekmusclay\Framework\Common\AbstractController;
use Geekmusclay\Framework\Renderer\TwigRenderer;
use GuzzleHttp\Psr7\Response;

class HomeController extends AbstractController
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
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
