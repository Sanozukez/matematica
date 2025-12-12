 <script setup>
/**
 * Página de listagem de cursos
 * 
 * Exibe todos os cursos disponíveis na plataforma
 * organizados por nível de dificuldade
 */
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <Head title="Cursos" />

    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="font-display text-4xl font-bold text-slate-800 mb-4"> xa Cursos
                </h1>
                <p class="text-lg text-slate-600 mb-8">
                    Página em construção - Em breve você verá todos os cursos aqui
                </p>

                <div class="bg-white rounded-2xl shadow-lg p-12 border-2 border-dashed border-slate-300">
                    <div class="text-6xl mb-4">🏅️</div>
                    <p class="text-slate-500">
                        Sistema de cursos será implementado na próxima etapa
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


