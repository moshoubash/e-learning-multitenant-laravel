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
                sans: ['Space Grotesk', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary-container': 'rgb(var(--color-primary-container-rgb, 255 214 0) / <alpha-value>)',
                'on-surface': 'rgb(var(--color-on-surface-rgb, 10 10 10) / <alpha-value>)',
                'surface-container-lowest': 'rgb(var(--color-surface-container-lowest-rgb, 255 255 255) / <alpha-value>)',
                'surface-container-low': 'rgb(var(--color-surface-container-low-rgb, 243 243 243) / <alpha-value>)',
                'surface-container': 'rgb(var(--color-surface-container-rgb, 238 238 238) / <alpha-value>)',
                'surface-container-high': 'rgb(var(--color-surface-container-high-rgb, 232 232 232) / <alpha-value>)',
                'surface-container-highest': 'rgb(var(--color-surface-container-highest-rgb, 226 226 226) / <alpha-value>)',
                secondary: 'rgb(var(--color-secondary-rgb, 95 94 94) / <alpha-value>)',
                error: 'rgb(var(--color-error-rgb, 186 26 26) / <alpha-value>)',
                'on-primary-container': 'rgb(var(--color-on-primary-container-rgb, 112 93 0) / <alpha-value>)',
            },
            borderRadius: {
                DEFAULT: '0.125rem',
                lg: '0.25rem',
                full: '0.75rem',
            },
        },
    },

    plugins: [forms],
};
