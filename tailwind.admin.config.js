import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/**
 * Admin theme.
 * The admin panel uses the DEFAULT Tailwind theme (e.g. `rounded-full` stays a
 * true circle for avatars/badges), matching how it was previously served via
 * the Tailwind CDN. Referenced from resources/css/admin.css through @config.
 *
 * @type {import('tailwindcss').Config}
 */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/admin/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Brand palette (shared with the storefront) for the admin chrome.
                'brand-forest': '#1e3a33',
                'brand-forest-dark': '#152a25',
                'brand-sage': '#7c9a86',
                'brand-sage-dark': '#5f8069',
                'brand-cream': '#f3eee7',
                'brand-beige': '#e9e0cd',
                'brand-gold': '#c9a452',
                'brand-gold-dark': '#b0893c',

                // The admin is built on `indigo-*`; remap the ramp to forest green
                // so every button, link, focus ring and badge adopts the brand
                // without editing the admin views.
                'indigo': {
                    50:  '#eef2f0',
                    100: '#d9e3dd',
                    200: '#b7c8bf',
                    300: '#8fa99b',
                    400: '#5f8069',
                    500: '#3a5f50',
                    600: '#1e3a33',
                    700: '#17302a',
                    800: '#12251f',
                    900: '#0d1b16',
                },
            },
        },
    },

    plugins: [forms, typography],
};
