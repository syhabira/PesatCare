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
                pesat: {
                    sidebar: '#2C5EAD', // dark blue for sidebar
                    blue: '#1591DC',    // medium blue for gradient
                    light: '#C4E2F5',   // greyish blue
                    cream: '#FAFBFD',   // cream / primary content bg
                    dark: '#244e91',
                },
            },
        },
    },

    plugins: [forms],
};
