import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // ====================================================================
    // SAFELIST AGREGADO PARA INCLUIR CLASES DINÁMICAS DE COLOR
    // ====================================================================
    safelist: [
        {
            // Patrones para las clases de fondo de los selectores de color (bg-X-600)
            pattern: /bg-(green|indigo|red|yellow|gray|blue|purple)-600/,
        },
        {
            // Patrones para las clases de anillo/borde del selector activo (ring-X-500)
            pattern: /ring-(green|indigo|red|yellow|gray|blue|purple)-500/,
        },
        {
            // Patrones para las clases de texto y fondo de la vista previa del badge
            // (bg-X-100 y text-X-800)
            pattern: /(bg|text)-(green|indigo|red|yellow|gray|blue|purple)-(100|800)/,
        },
    ],
    // ====================================================================
    
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
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