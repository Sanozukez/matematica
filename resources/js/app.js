// plataforma/resources/js/app.js

/**
 * Inicialização da aplicação Vue + Inertia
 * 
 * Este arquivo configura o Vue 3 com Inertia.js para
 * renderização de páginas no lado do cliente.
 */

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import './bootstrap';

createInertiaApp({
    /**
     * Define o título da página dinamicamente
     */
    title: (title) => title ? `${title} - Matemática` : 'Matemática',

    /**
     * Resolve o componente da página baseado no nome
     */
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue')
    ),

    /**
     * Configura e monta a aplicação Vue
     */
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },

    /**
     * Configuração da barra de progresso
     */
    progress: {
        color: '#0ea5e9',
        showSpinner: true,
    },
});
