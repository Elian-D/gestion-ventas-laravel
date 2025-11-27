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
            },
            
            backgroundImage: {
                'custom-gradient-1': `
                radial-gradient(1100px 600px at 0% 0%, rgba(123, 140, 255, .35) 0%, transparent 45%),
                linear-gradient(135deg, #121a41, #0f1639 60%)
                `,
                'custom-gradient-2': `
                linear-gradient(135deg,#5661ff,#7b8cff)
                `,
            },
        },
    },

    plugins: [forms],
};
