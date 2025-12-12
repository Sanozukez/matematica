 <script setup>
/**
 * Layout principal da aplicação
 * 
 * Contém a estrutura base com navegação e footer
 * Utilizado por todas as páginas públicas
 */
import { Link } from '@inertiajs/vue3';

defineProps({
    /** Título da página atual */
    title: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <header class="bg-gradient-to-r from-primary-600 to-primary-700 shadow-lg">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md">
                                <span class="text-2xl">🚀</span>
                            </div>
                            <span class="font-display text-xl font-bold text-white">
                                Matemática
                            </span>
                        </Link>
                    </div>

                    <div class="hidden md:flex items-center space-x-4">
                        <Link
                            href="/cursos"
                            class="text-white/90 hover:text-white px-3 py-2 rounded-lg hover:bg-white/10 transition-colors"
                        >
                            Cursos
                        </Link>
                        <Link
                            href="/skill-tree"
                            class="text-white/90 hover:text-white px-3 py-2 rounded-lg hover:bg-white/10 transition-colors"
                        >
                            Skill Tree
                        </Link>
                        <Link
                            href="/login"
                            class="bg-white text-primary-600 px-4 py-2 rounded-lg font-semibold hover:bg-primary-50 transition-colors"
                        >
                            Entrar
                        </Link>
                    </div>
                </div>
            </nav>
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <footer class="bg-slate-800 text-slate-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <p class="font-display text-lg text-white mb-2">
                        🚀 Plataforma Matemática
                    </p>
                    <p class="text-sm text-slate-400">
                        Aprendendo matemática do zero ao avançado
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>


