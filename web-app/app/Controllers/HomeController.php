<?php

namespace App\Controllers;

use Framework\Templating\View;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    public function index(Request $request, array $params): View
    {
        return new View('home', [
            'title' => 'Test title',
            'content' => 'Test paragraph'
        ]);
    }
}
