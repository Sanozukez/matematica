 <script setup>
/**
 * Página inicial da plataforma
 * 
 * Apresenta a proposta da plataforma e
 * direciona o usuário para os cursos
 */
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    /** Nome do usuário logado (se houver) */
    user: {
        type: Object,
        default: null,
    },
});
</script>

<template>
    <Head title="Início" />

    <AppLayout>
        <section class="relative overflow-hidden bg-gradient-to-br from-primary-500 via-primary-600 to-accent-600 text-white">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
                <div class="text-center">
                    <div class="text-7xl mb-6 animate-float">
                        🚀
                    </div>

                    <h1 class="font-display text-4xl md:text-6xl font-bold mb-6">
                        Aprenda Matemática
                        <span class="block text-accent-200">do Zero ao Avançado</span>
                    </h1>

                    <p class="text-xl md:text-2xl text-white/80 max-w-3xl mx-auto mb-10">
                        Uma jornada gamificada pelo universo dos números. 
                        Desbloqueie conquistas, evolua suas habilidades e 
                        domine a matemática no seu ritmo.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            href="/cursos"
                            class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 font-bold text-lg rounded-2xl hover:bg-primary-50 transform hover:scale-105 transition-all shadow-xl"
                        >
                            <span class="mr-2">🎯</span>
                            Começar Jornada
                        </Link>
                        <Link
                            href="/skill-tree"
                            class="inline-flex items-center justify-center px-8 py-4 bg-white/10 text-white font-bold text-lg rounded-2xl hover:bg-white/20 transition-all border-2 border-white/30"
                        >
                            <span class="mr-2">📈</span>
                            Ver Skill Tree
                        </Link>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f8fafc"/>
                </svg>
            </div>
        </section>

        <section class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        Como Funciona?
                    </h2>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                        Uma plataforma pensada para tornar o aprendizado de matemática 
                        divertido e eficiente
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center text-3xl mb-6">
                            ✨
                        </div>
                        <h3 class="font-display text-xl font-bold text-slate-800 mb-3">
                            Aprendizado Gamificado
                        </h3>
                        <p class="text-slate-600">
                            Mini jogos interativos para os módulos iniciais. 
                            Aprenda brincando e fixe o conteúdo de forma natural.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-accent-100 rounded-2xl flex items-center justify-center text-3xl mb-6">
                            🏅 
                        </div>
                        <h3 class="font-display text-xl font-bold text-slate-800 mb-3">
                            Sistema de Badges
                        </h3>
                        <p class="text-slate-600">
                            Conquiste badges ao completar módulos e desbloqueie 
                            novos conteúdos na árvore de habilidades.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl mb-6">
                            📈
                        </div>
                        <h3 class="font-display text-xl font-bold text-slate-800 mb-3">
                            Skill Tree
                        </h3>
                        <p class="text-slate-600">
                            Visualize sua evolução em uma árvore de habilidades. 
                            Veja o que já domina e o que vem pela frente.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        Níveis de Aprendizado
                    </h2>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                        Do básico ao avançado, uma jornada completa pelo mundo da matemática
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="group relative bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl p-6 text-white overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-bl-full"></div>
                        <div class="text-4xl mb-4">📈</div>
                        <h3 class="font-display text-xl font-bold mb-2">Básico</h3>
                        <p class="text-white/80 text-sm">
                            Números, contagem e operações fundamentais
                        </p>
                        <div class="mt-4 text-xs bg-white/20 rounded-full px-3 py-1 inline-block">
                            Gamificado
                        </div>
                    </div>

                    <div class="group relative bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-bl-full"></div>
                        <div class="text-4xl mb-4"> xa</div>
                        <h3 class="font-display text-xl font-bold mb-2">Fundamental</h3>
                        <p class="text-white/80 text-sm">
                            Frações, decimais, geometria básica
                        </p>
                    </div>

                    <div class="group relative bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl p-6 text-white overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-bl-full"></div>
                        <div class="text-4xl mb-4">✨</div>
                        <h3 class="font-display text-xl font-bold mb-2">Médio</h3>
                        <p class="text-white/80 text-sm">
                            Álgebra, funções e trigonometria
                        </p>
                    </div>

                    <div class="group relative bg-gradient-to-br from-rose-400 to-rose-600 rounded-2xl p-6 text-white overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-bl-full"></div>
                        <div class="text-4xl mb-4">🎯</div>
                        <h3 class="font-display text-xl font-bold mb-2">Superior</h3>
                        <p class="text-white/80 text-sm">
                            Cálculo, álgebra linear e análise
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-gradient-to-r from-primary-600 to-accent-600 text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="font-display text-3xl md:text-4xl font-bold mb-6">
                    Pronto para começar sua jornada?
                </h2>
                <p class="text-xl text-white/80 mb-8">
                    Crie sua conta gratuitamente e comece a desbloquear suas habilidades matemáticas
                </p>
                <Link
                    href="/register"
                    class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 font-bold text-lg rounded-2xl hover:bg-primary-50 transform hover:scale-105 transition-all shadow-xl"
                >
                    <span class="mr-2">⭐</span>
                    Criar Conta Grátis
                </Link>
            </div>
        </section>
    </AppLayout>
</template>


