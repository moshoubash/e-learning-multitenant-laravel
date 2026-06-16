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
                'primary-container': '#FFD600',
                'on-surface': '#0A0A0A',
                'surface-container-lowest': '#FFFFFF',
                'surface-container-low': '#F3F3F3',
                'surface-container': '#EEEEEE',
                'surface-container-high': '#E8E8E8',
                'surface-container-highest': '#E2E2E2',
                secondary: '#5f5e5e',
                error: '#ba1a1a',
                'on-primary-container': '#705d00',
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
