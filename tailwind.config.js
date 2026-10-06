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
                sans: ['Geist', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    primary: '#3368A0',
                    secondary: '#66A3BF',
                    accent: '#C8DFDB',
                    dark: '#275382',
                    soft: '#EEF6F8',
                },
            },
        },
    },

    plugins: [forms],
};
