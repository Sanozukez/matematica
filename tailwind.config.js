// plataforma/tailwind.config.js
/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Habilita dark mode usando classe 'dark' no HTML
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
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

