<?php

// plataforma/app/Http/Middleware/HandleInertiaRequests.php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * Middleware para compartilhar dados globais com o Inertia
 * 
 * Disponibiliza dados do usuário autenticado e flash messages
 * para todas as páginas Vue
 */
class HandleInertiaRequests extends Middleware
{
    /**
     * Template raiz carregado na primeira visita
     */
    protected $rootView = 'app';

    /**
     * Versão dos assets para cache busting
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Props compartilhadas com todas as páginas
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            // Dados do usuário autenticado
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'roles' => $request->user()->getRoleNames(),
                    'total_points' => $request->user()->total_points ?? 0,
                ] : null,
            ],

            // Flash messages
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],
        ];
    }
}
