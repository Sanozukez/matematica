<?php

// plataforma/app/Http/Controllers/App/DashboardController.php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller do Dashboard do Aluno
 * 
 * Gerencia a página inicial da área do aluno
 * com estatísticas e progresso
 */
class DashboardController extends Controller
{
    /**
     * Exibe o dashboard do aluno
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        // TODO: Implementar queries reais quando tivermos dados
        $stats = [
            'totalBadges' => $user->badges()->count(),
            'totalPoints' => $user->badges()->sum('points'),
            'completedLessons' => $user->progress()
                ->where('status', 'completed')
                ->count(),
            'currentStreak' => 0, // TODO: Calcular streak
        ];

        $recentBadges = $user->badges()
            ->latest('user_badges.created_at')
            ->take(6)
            ->get();

        // TODO: Implementar próximas lições recomendadas
        $nextLessons = [];

        return Inertia::render('App/Dashboard', [
            'stats' => $stats,
            'recentBadges' => $recentBadges,
            'nextLessons' => $nextLessons,
        ]);
    }
}

