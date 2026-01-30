<?php

namespace App\Controllers;

use Framework\Response;
use Framework\View\Views;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    public function __construct(
        private Views $views
    ) {}

    public function index(Request $request, array $params): Response
    {
        return new Response($this->views->render('home', [
            'title' => 'Test title',
            'content' => 'Test paragraph'
        ]));
    }
}
