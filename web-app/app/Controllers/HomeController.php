<?php

namespace App\Controllers;

use Framework\Response;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    public function index(Request $request, array $params): Response
    {
        return new Response('<h1>ree</h1>');
    }
}
