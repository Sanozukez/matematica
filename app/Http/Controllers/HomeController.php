<?php

// plataforma/app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller para páginas públicas da aplicação
 * 
 * Responsável por renderizar páginas acessíveis
 * sem autenticação
 */
class HomeController extends Controller
{
    /**
     * Exibe a página inicial da plataforma
     */
    public function index(): Response
    {
        return Inertia::render('Home', [
            'user' => auth()->user(),
        ]);
    }
}

