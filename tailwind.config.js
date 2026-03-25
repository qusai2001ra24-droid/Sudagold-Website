import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                gold: {
                    DEFAULT: '#d4af37',
                    light: '#e8c547',
                    dark: '#b8962e',
                },
                black: {
                    DEFAULT: '#0a0a0a',
                    light: '#1a1a1a',
                },
            },
        },
    },

    plugins: [forms],
};
