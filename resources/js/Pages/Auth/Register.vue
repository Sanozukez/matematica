 <script setup>
/**
 * Página de Registro
 * 
 * Formulário de cadastro para novos alunos
 */
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Criar Conta" />

    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-accent-900/50 to-slate-900 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex items-center space-x-3">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-accent-500 rounded-2xl flex items-center justify-center shadow-xl">
                        <span class="text-3xl">🚀</span>
                    </div>
                </Link>
                <h1 class="font-display text-2xl font-bold text-white mt-4">
                    Comece sua jornada!
                </h1>
                <p class="text-slate-400 mt-1">
                    Crie sua conta gratuita
                </p>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-2xl p-8">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                            Nome completo
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                            placeholder="Seu nome"
                        />
                        <p v-if="form.errors.name" class="mt-2 text-sm text-red-400">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                            E-mail
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
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
                            autocomplete="new-password"
                            class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                            placeholder="Mínimo 8 caracteres"
                        />
                        <p v-if="form.errors.password" class="mt-2 text-sm text-red-400">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                            Confirmar senha
                        </label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                            placeholder="Repita a senha"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3 px-4 bg-gradient-to-r from-accent-500 to-accent-600 hover:from-accent-600 hover:to-accent-700 text-white font-semibold rounded-xl shadow-lg shadow-accent-500/25 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="form.processing">Criando conta...</span>
                        <span v-else>🎯 Criar conta grátis</span>
                    </button>
                </form>

                <p class="mt-4 text-xs text-slate-500 text-center">
                    Ao criar sua conta, você concorda com nossos
                    <a href="#" class="text-primary-400">Termos de Uso</a>
                    e
                    <a href="#" class="text-primary-400">Política de Privacidade</a>.
                </p>

                <div class="mt-6 text-center">
                    <p class="text-slate-400">
                        Já tem conta?
                        <Link href="/login" class="text-primary-400 hover:text-primary-300 font-medium">
                            Entrar
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


