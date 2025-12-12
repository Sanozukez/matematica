 <script setup>
/**
 * Skill Tree do Aluno
 * 
 * Exibe a árvore de habilidades pessoal do aluno
 * com badges desbloqueadas e próximos passos
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
    <Head title="Minha Skill Tree" />

    <StudentLayout>
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold mb-2">
                📈 Minha Skill Tree
            </h1>
            <p class="text-slate-400">
                Visualize seu progresso e próximas conquistas
            </p>
        </div>

        <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700 min-h-[500px] flex items-center justify-center">
            <div class="text-center">
                <div class="text-6xl mb-4">🏅️</div>
                <h2 class="font-display text-xl font-bold mb-2">
                    Skill Tree em Desenvolvimento
                </h2>
                <p class="text-slate-400 max-w-md">
                    Esta funcionalidade está sendo construída. Em breve você
                    poderá visualizar toda sua progressão em uma árvore
                    interativa!
                </p>
            </div>
        </div>
    </StudentLayout>
</template>


