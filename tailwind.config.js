// plataforma/tailwind.config.js
/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Habilita dark mode usando classe 'dark' no HTML
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    safelist: [
        // Cores de texto para o Block Editor
        'text-red-500', 'text-red-600', 'text-red-700',
        'text-orange-500', 'text-orange-600', 'text-orange-700',
        'text-yellow-500', 'text-yellow-600', 'text-yellow-700',
        'text-green-500', 'text-green-600', 'text-green-700',
        'text-blue-500', 'text-blue-600', 'text-blue-700',
        'text-indigo-500', 'text-indigo-600', 'text-indigo-700',
        'text-purple-500', 'text-purple-600', 'text-purple-700',
        'text-pink-500', 'text-pink-600', 'text-pink-700',
        // Cores de fundo para o seletor
        'bg-red-500', 'bg-red-600', 'bg-red-700',
        'bg-orange-500', 'bg-orange-600', 'bg-orange-700',
        'bg-yellow-500', 'bg-yellow-600', 'bg-yellow-700',
        'bg-green-500', 'bg-green-600', 'bg-green-700',
        'bg-blue-500', 'bg-blue-600', 'bg-blue-700',
        'bg-indigo-500', 'bg-indigo-600', 'bg-indigo-700',
        'bg-purple-500', 'bg-purple-600', 'bg-purple-700',
        'bg-pink-500', 'bg-pink-600', 'bg-pink-700',
        'bg-gray-900',
    ],
    theme: {
        extend: {
            colors: {
                // Paleta personalizada para a plataforma de matemática
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                    950: '#082f49',
                },
                accent: {
                    50: '#fdf4ff',
                    100: '#fae8ff',
                    200: '#f5d0fe',
                    300: '#f0abfc',
                    400: '#e879f9',
                    500: '#d946ef',
                    600: '#c026d3',
                    700: '#a21caf',
                    800: '#86198f',
                    900: '#701a75',
                    950: '#4a044e',
                },
            },
            fontFamily: {
                sans: ['Nunito', 'system-ui', 'sans-serif'],
                display: ['Fredoka', 'system-ui', 'sans-serif'],
            },
            typography: {
                DEFAULT: {
                    css: {
                        color: '#1f2937',
                        maxWidth: 'none',
                        '--tw-prose-body': '#1f2937',
                        '--tw-prose-headings': '#111827',
                        '--tw-prose-lead': '#374151',
                        '--tw-prose-links': '#2563eb',
                        '--tw-prose-bold': '#111827',
                        '--tw-prose-counters': '#6b7280',
                        '--tw-prose-bullets': '#6b7280',
                        '--tw-prose-hr': '#e5e7eb',
                        '--tw-prose-quotes': '#111827',
                        '--tw-prose-quote-borders': '#e5e7eb',
                        '--tw-prose-captions': '#6b7280',
                        '--tw-prose-code': '#111827',
                        '--tw-prose-pre-code': '#e5e7eb',
                        '--tw-prose-pre-bg': '#1f2937',
                        '--tw-prose-th-borders': '#d1d5db',
                        '--tw-prose-td-borders': '#e5e7eb',
                    },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
};

