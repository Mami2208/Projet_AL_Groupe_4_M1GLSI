import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    
    // Configuration de la purge sécurisée
    safelist: [
        // Classes de couleurs de texte
        ...['red', 'green', 'blue', 'yellow', 'purple', 'pink', 'indigo', 'gray'].map(color => `text-${color}-600`),
        // Classes de couleurs de fond
        ...['red', 'green', 'blue', 'yellow', 'purple', 'pink', 'indigo', 'gray'].map(color => `bg-${color}-100`),
        // Classes de couleurs de bordure
        ...['red', 'green', 'blue', 'yellow', 'purple', 'pink', 'indigo', 'gray'].map(color => `border-${color}-500`),
        // Classes de couleurs de texte au survol
        ...['red', 'green', 'blue', 'yellow', 'purple', 'pink', 'indigo', 'gray'].map(color => `hover:text-${color}-500`),
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': {
                    '50': '#f0f9ff',
                    '100': '#e0f2fe',
                    '200': '#bae6fd',
                    '300': '#7dd3fc',
                    '400': '#38bdf8',
                    '500': '#0ea5e9',
                    '600': '#0284c7',
                    '700': '#0369a1',
                    '800': '#075985',
                    '900': '#0c4a6e',
                },
                // Ajout des couleurs de base de Tailwind
                'red': {
                    '50': '#fef2f2',
                    '100': '#fee2e2',
                    '200': '#fecaca',
                    '300': '#fca5a5',
                    '400': '#f87171',
                    '500': '#ef4444',
                    '600': '#dc2626',
                    '700': '#b91c1c',
                    '800': '#991b1b',
                    '900': '#7f1d1d',
                },
                'green': {
                    '50': '#f0fdf4',
                    '100': '#dcfce7',
                    '200': '#bbf7d0',
                    '300': '#86efac',
                    '400': '#4ade80',
                    '500': '#22c55e',
                    '600': '#16a34a',
                    '700': '#15803d',
                    '800': '#166534',
                    '900': '#14532d',
                },
                'blue': {
                    '50': '#eff6ff',
                    '100': '#dbeafe',
                    '200': '#bfdbfe',
                    '300': '#93c5fd',
                    '400': '#60a5fa',
                    '500': '#3b82f6',
                    '600': '#2563eb',
                    '700': '#1d4ed8',
                    '800': '#1e40af',
                    '900': '#1e3a8a',
                },
                'yellow': {
                    '50': '#fffbeb',
                    '100': '#fef3c7',
                    '200': '#fde68a',
                    '300': '#fcd34d',
                    '400': '#fbbf24',
                    '500': '#f59e0b',
                    '600': '#d97706',
                    '700': '#b45309',
                    '800': '#92400e',
                    '900': '#78350f',
                },
                'purple': {
                    '50': '#faf5ff',
                    '100': '#f3e8ff',
                    '200': '#e9d5ff',
                    '300': '#d8b4fe',
                    '400': '#c084fc',
                    '500': '#a855f7',
                    '600': '#9333ea',
                    '700': '#7e22ce',
                    '800': '#6b21a8',
                    '900': '#581c87',
                },
                'pink': {
                    '50': '#fdf2f8',
                    '100': '#fce7f3',
                    '200': '#fbcfe8',
                    '300': '#f9a8d4',
                    '400': '#f472b6',
                    '500': '#ec4899',
                    '600': '#db2777',
                    '700': '#be185d',
                    '800': '#9d174d',
                    '900': '#831843',
                },
                'indigo': {
                    '50': '#eef2ff',
                    '100': '#e0e7ff',
                    '200': '#c7d2fe',
                    '300': '#a5b4fc',
                    '400': '#818cf8',
                    '500': '#6366f1',
                    '600': '#4f46e5',
                    '700': '#4338ca',
                    '800': '#3730a3',
                    '900': '#312e81',
                },
            },
        },
    },
    
    // Configuration de la pagination
    plugins: [
        forms,
        typography,
        function({ addComponents }) {
            addComponents({
                '.pagination': {
                    '@apply flex items-center space-x-1' : {},
                    '&-link': {
                        '@apply px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50' : {},
                        '&.active': {
                            '@apply bg-indigo-600 text-white border-indigo-600' : {},
                        },
                        '&.disabled': {
                            '@apply opacity-50 cursor-not-allowed' : {},
                        },
                    },
                },
            });
        },
    ],
};
