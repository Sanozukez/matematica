 <script setup>
/**
 * Meus Cursos
 * 
 * Lista cursos em que o aluno está matriculado
 * com progresso de cada um
 */
import { Head } from '@inertiajs/vue3';
import StudentLayout from '@/Layouts/StudentLayout.vue';

defineProps({
    courses: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Meus Cursos" />

    <StudentLayout>
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold mb-2"> xa Meus Cursos
            </h1>
            <p class="text-slate-400">
                Cursos em que você está matriculado
            </p>
        </div>

        <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700 min-h-[400px] flex items-center justify-center">
            <div class="text-center">
                <div class="text-6xl mb-4">🔒</div>
                <h2 class="font-display text-xl font-bold mb-2">
                    Nenhum curso iniciado
                </h2>
                <p class="text-slate-400 max-w-md mb-6">
                    Você ainda não começou nenhum curso. Explore nosso catálogo
                    e comece sua jornada matemática!
                </p>
                <a
                    href="/cursos"
                    class="inline-flex items-center px-6 py-3 bg-primary-500 hover:bg-primary-600 rounded-xl font-semibold transition-colors"
                >
                    Explorar Cursos 
                </a>
            </div>
        </div>
    </StudentLayout>
</template>


