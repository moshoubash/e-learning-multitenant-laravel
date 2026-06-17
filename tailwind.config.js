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
                'primary-container': 'var(--color-primary-container, #FFD600)',
                'on-surface': 'var(--color-on-surface, #0A0A0A)',
                'surface-container-lowest': 'var(--color-surface-container-lowest, #FFFFFF)',
                'surface-container-low': 'var(--color-surface-container-low, #F3F3F3)',
                'surface-container': 'var(--color-surface-container, #EEEEEE)',
                'surface-container-high': 'var(--color-surface-container-high, #E8E8E8)',
                'surface-container-highest': 'var(--color-surface-container-highest, #E2E2E2)',
                secondary: 'var(--color-secondary, #5f5e5e)',
                error: 'var(--color-error, #ba1a1a)',
                'on-primary-container': 'var(--color-on-primary-container, #705d00)',
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
