 <script setup>
/**
 * Dashboard do Aluno
 * 
 * Página inicial da área do aluno logado
 * Mostra resumo de progresso, badges recentes e próximos passos
 */
import { Head } from '@inertiajs/vue3';
import StudentLayout from '@/Layouts/StudentLayout.vue';

defineProps({
    /** Dados do usuário autenticado */
    auth: Object,
    /** Estatísticas de progresso */
    stats: {
        type: Object,
        default: () => ({
            totalBadges: 0,
            totalPoints: 0,
            completedLessons: 0,
            currentStreak: 0,
        }),
    },
    /** Badges recentes */
    recentBadges: {
        type: Array,
        default: () => [],
    },
    /** Próximas lições recomendadas */
    nextLessons: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <StudentLayout>
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold mb-2">
                Olá, {{ auth?.user?.name?.split(' ')[0] || 'Estudante' }}! x9
            </h1>
            <p class="text-slate-400">
                Continue sua jornada matemática de onde parou
            </p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-br from-yellow-500/20 to-yellow-600/20 border border-yellow-500/30 rounded-2xl p-6">
                <div class="text-3xl mb-2">⭐</div>
                <div class="text-2xl font-bold">{{ stats.totalPoints }}</div>
                <div class="text-sm text-slate-400">Pontos Totais</div>
            </div>

            <div class="bg-gradient-to-br from-purple-500/20 to-purple-600/20 border border-purple-500/30 rounded-2xl p-6">
                <div class="text-3xl mb-2">🏅 </div>
                <div class="text-2xl font-bold">{{ stats.totalBadges }}</div>
                <div class="text-sm text-slate-400">Badges</div>
            </div>

            <div class="bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 border border-emerald-500/30 rounded-2xl p-6">
                <div class="text-3xl mb-2"> S&</div>
                <div class="text-2xl font-bold">{{ stats.completedLessons }}</div>
                <div class="text-sm text-slate-400">Lições Completas</div>
            </div>

            <div class="bg-gradient-to-br from-orange-500/20 to-orange-600/20 border border-orange-500/30 rounded-2xl p-6">
                <div class="text-3xl mb-2"> x </div>
                <div class="text-2xl font-bold">{{ stats.currentStreak }}</div>
                <div class="text-sm text-slate-400">Dias Seguidos</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700">
                <h2 class="font-display text-xl font-bold mb-4 flex items-center">
                    <span class="mr-2"> x</span>
                    Continuar Estudando
                </h2>

                <div v-if="nextLessons.length > 0" class="space-y-3">
                    <div
                        v-for="lesson in nextLessons"
                        :key="lesson.id"
                        class="flex items-center bg-slate-700/50 rounded-xl p-4 hover:bg-slate-700 transition-colors cursor-pointer"
                    >
                        <div class="w-12 h-12 bg-primary-500/20 rounded-xl flex items-center justify-center text-2xl mr-4">
                            {{ lesson.icon || ' x' }}
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold">{{ lesson.title }}</div>
                            <div class="text-sm text-slate-400">{{ lesson.module }}</div>
                        </div>
                        <div class="text-primary-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-8 text-slate-500">
                    <div class="text-4xl mb-2">⏱️</div>
                    <p>Você está em dia! Explore novos cursos.</p>
                </div>
            </div>

            <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700">
                <h2 class="font-display text-xl font-bold mb-4 flex items-center">
                    <span class="mr-2">🏅 </span>
                    Badges Recentes
                </h2>

                <div v-if="recentBadges.length > 0" class="grid grid-cols-3 gap-4">
                    <div
                        v-for="badge in recentBadges"
                        :key="badge.id"
                        class="text-center"
                    >
                        <div
                            class="w-16 h-16 mx-auto rounded-full flex items-center justify-center text-3xl mb-2"
                            :style="{ backgroundColor: badge.color + '30' }"
                        >
                            {{ badge.icon || '🏅&' }}
                        </div>
                        <div class="text-sm font-medium truncate">{{ badge.name }}</div>
                    </div>
                </div>

                <div v-else class="text-center py-8 text-slate-500">
                    <div class="text-4xl mb-2"> x</div>
                    <p>Complete lições para desbloquear badges!</p>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-gradient-to-r from-primary-900/50 to-accent-900/50 rounded-2xl p-8 border border-primary-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-display text-2xl font-bold mb-2">
                        📈 Sua Skill Tree
                    </h2>
                    <p class="text-slate-400">
                        Visualize seu progresso e próximas conquistas
                    </p>
                </div>
                <a
                    href="/app/skill-tree"
                    class="bg-white/10 hover:bg-white/20 px-6 py-3 rounded-xl font-semibold transition-colors"
                >
                    Ver Completa 
                </a>
            </div>
        </div>
    </StudentLayout>
</template>


