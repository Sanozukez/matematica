 <script setup>
/**
 * Layout da Área do Aluno
 * 
 * Layout específico para alunos logados
 * Inclui navegação com progresso e badges
 */
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
</script>

<template>
    <div class="min-h-screen bg-slate-900 text-white">
        <header class="bg-slate-800 border-b border-slate-700">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/app" class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center shadow-lg">
                                <span class="text-xl">🚀</span>
                            </div>
                            <span class="font-display text-xl font-bold">
                                Matemática
                            </span>
                        </Link>
                    </div>

                    <div class="hidden md:flex items-center space-x-1">
                        <Link
                            href="/app"
                            class="px-4 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition-colors"
                            :class="{ 'bg-slate-700 text-white': $page.url === '/app' }"
                        >
                            <span class="mr-2"> x`</span>
                            Dashboard
                        </Link>
                        <Link
                            href="/app/cursos"
                            class="px-4 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition-colors"
                            :class="{ 'bg-slate-700 text-white': $page.url.startsWith('/app/cursos') }"
                        >
                            <span class="mr-2"> xa</span>
                            Meus Cursos
                        </Link>
                        <Link
                            href="/app/skill-tree"
                            class="px-4 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition-colors"
                            :class="{ 'bg-slate-700 text-white': $page.url.startsWith('/app/skill-tree') }"
                        >
                            <span class="mr-2">📈</span>
                            Skill Tree
                        </Link>
                        <Link
                            href="/app/badges"
                            class="px-4 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition-colors"
                            :class="{ 'bg-slate-700 text-white': $page.url.startsWith('/app/badges') }"
                        >
                            <span class="mr-2">🏅 </span>
                            Badges
                        </Link>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="hidden sm:flex items-center bg-slate-700 rounded-full px-4 py-1.5">
                            <span class="text-yellow-400 mr-2">⭐</span>
                            <span class="font-bold">{{ user?.total_points || 0 }}</span>
                            <span class="text-slate-400 ml-1 text-sm">pts</span>
                        </div>

                        <div class="relative">
                            <button class="flex items-center space-x-2 bg-slate-700 rounded-full pl-2 pr-4 py-1">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ user?.name?.charAt(0)?.toUpperCase() || '?' }}
                                </div>
                                <span class="text-sm font-medium">{{ user?.name || 'Usuário' }}</span>
                            </button>
                        </div>

                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="text-slate-400 hover:text-white transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </nav>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>
    </div>
</template>


