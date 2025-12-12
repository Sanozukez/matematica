 <script setup>
/**
 * Página da Árvore de Habilidades (Skill Tree)
 * 
 * Exibe a progressão do usuário em formato de árvore
 * com badges e dependências entre módulos
 */
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <Head title="Skill Tree" />

    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="font-display text-4xl font-bold text-slate-800 mb-4">
                    📈 Skill Tree
                </h1>
                <p class="text-lg text-slate-600 mb-8">
                    Sua árvore de habilidades matemáticas
                </p>

                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-xl p-8 text-white">
                    <div class="text-6xl mb-6">✨</div>
                    
                    <div class="flex justify-center items-center space-x-4 mb-8">
                        <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center text-2xl shadow-lg animate-pulse-glow"> S
                        </div>
                        
                        <div class="w-12 h-1 bg-emerald-500"></div>
                        
                        <div class="w-20 h-20 bg-primary-500 rounded-full flex items-center justify-center text-3xl shadow-lg ring-4 ring-primary-300 animate-pulse-glow"> x
                        </div>
                        
                        <div class="w-12 h-1 bg-slate-600"></div>
                        
                        <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center text-2xl opacity-50"> x
                        </div>
                    </div>

                    <p class="text-slate-400">
                        Sistema de Skill Tree será implementado na próxima etapa
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


