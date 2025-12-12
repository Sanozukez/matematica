 <script setup>
/**
 * Página de Login
 * 
 * Formulário de autenticação para alunos e criadores
 */
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Entrar" />

    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-primary-900 to-slate-900 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex items-center space-x-3">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-accent-500 rounded-2xl flex items-center justify-center shadow-xl">
                        <span class="text-3xl">🚀</span>
                    </div>
                </Link>
                <h1 class="font-display text-2xl font-bold text-white mt-4">
                    Bem-vindo de volta!
                </h1>
                <p class="text-slate-400 mt-1">
                    Entre para continuar sua jornada
                </p>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-2xl p-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                            E-mail
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            autofocus
                            autocomplete="email"
                            class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                            placeholder="seu@email.com"
                        />
                        <p v-if="form.errors.email" class="mt-2 text-sm text-red-400">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                            Senha
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                            placeholder="⬢⬢⬢⬢⬢⬢⬢⬢"
                        />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="w-4 h-4 rounded border-slate-600 bg-slate-900 text-primary-500 focus:ring-primary-500"
                            />
                            <span class="ml-2 text-sm text-slate-400">Lembrar de mim</span>
                        </label>

                        <a href="#" class="text-sm text-primary-400 hover:text-primary-300">
                            Esqueceu a senha?
                        </a>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3 px-4 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/25 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="form.processing">Entrando...</span>
                        <span v-else>Entrar</span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-slate-400">
                        Não tem conta?
                        <Link href="/register" class="text-primary-400 hover:text-primary-300 font-medium">
                            Criar conta grátis
                        </Link>
                    </p>
                </div>
            </div>

            <div class="mt-6 text-center">
                <Link href="/" class="text-slate-500 hover:text-slate-400 text-sm">  Voltar para o início
                </Link>
            </div>
        </div>
    </div>
</template>


