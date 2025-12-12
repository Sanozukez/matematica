<?php

// plataforma/app/Http/Middleware/EnsureUserIsStudent.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para garantir que o usuário tem acesso à área do aluno
 * 
 * Permite acesso para: student, creator e super_admin
 * (Todos podem consumir cursos)
 */
class EnsureUserIsStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Super admins e creators também podem acessar a área do aluno
        if ($request->user()->hasAnyRole(['student', 'creator', 'super_admin'])) {
            return $next($request);
        }

        abort(403, 'Acesso não autorizado.');
    }
}

