 <script setup>
/**
 * Minhas Badges
 * 
 * Lista todas as badges do sistema mostrando
 * quais o aluno já desbloqueou
 */
import { Head } from '@inertiajs/vue3';
import StudentLayout from '@/Layouts/StudentLayout.vue';

defineProps({
    badges: {
        type: Array,
        default: () => [],
    },
    unlockedBadgeIds: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Minhas Badges" />

    <StudentLayout>
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold mb-2">
                🏅  Minhas Badges
            </h1>
            <p class="text-slate-400">
                Conquistas desbloqueadas na sua jornada
            </p>
        </div>

        <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700">
            <div class="text-center py-8">
                <div class="text-6xl mb-4">🔒</div>
                <h2 class="font-display text-xl font-bold mb-2">
                    Nenhuma badge ainda
                </h2>
                <p class="text-slate-400 max-w-md mx-auto">
                    Complete lições e módulos para desbloquear badges!
                    Cada conquista concede pontos e desbloqueia novos conteúdos.
                </p>
            </div>
        </div>
    </StudentLayout>
</template>


