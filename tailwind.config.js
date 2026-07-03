import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/**
 * Storefront theme.
 * Ported verbatim from the previous inline `tailwind.config` that was loaded
 * via the Tailwind CDN in resources/views/layouts/app.blade.php.
 *
 * @type {import('tailwindcss').Config}
 */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'on-secondary-fixed-variant': '#005049',
                'surface-container-low': '#f6f3f5',
                'surface-container': '#f0edef',
                'tertiary-container': '#0b1c30',
                'primary': '#2d5a27',
                'surface-container-high': '#eae7e9',
                'surface-variant': '#e4e2e4',
                'on-background': '#1b1b1d',
                'error': '#ba1a1a',
                'surface-dim': '#dcd9db',
                'on-tertiary': '#ffffff',
                'on-primary-fixed-variant': '#3f465c',
                'tertiary': '#000000',
                'outline-variant': '#c6c6cd',
                'on-error-container': '#93000a',
                'surface': '#fcf8fa',
                'on-surface-variant': '#45464d',
                'inverse-primary': '#bec6e0',
                'on-tertiary-container': '#75859d',
                'surface-container-lowest': '#ffffff',
                'inverse-surface': '#303032',
                'secondary': '#006a61',
                'on-tertiary-fixed-variant': '#38485d',
                'on-secondary-container': '#006f66',
                'on-error': '#ffffff',
                'on-primary-fixed': '#131b2e',
                'secondary-fixed': '#c9a452',
                'on-primary-container': '#c7d8c4',
                'primary-container': '#1B3A18',
                'on-surface': '#1b1b1d',
                'secondary-container': '#86f2e4',
                'surface-tint': '#565e74',
                'tertiary-fixed': '#d3e4fe',
                'background': '#fcf8fa',
                'outline': '#76777d',
                'primary-fixed-dim': '#bec6e0',
                'inverse-on-surface': '#f3f0f2',
                'surface-container-highest': '#e4e2e4',
                'tertiary-fixed-dim': '#b7c8e1',
                'on-secondary': '#ffffff',
                'on-tertiary-fixed': '#0b1c30',
                'secondary-fixed-dim': '#6bd8cb',
                'error-container': '#ffdad6',
                'surface-bright': '#fcf8fa',
                'primary-fixed': '#dae2fd',
                'on-primary': '#ffffff',
                'on-secondary-fixed': '#1e3a33',
                'soft-border': '#e1e4e8',
                'clinical-white': '#ffffff',
                // Storefront brand tokens — remapped to the warm botanical palette.
                // Kept under their original names so existing views adopt the new
                // look without markup changes: deep→forest, accent→gold, light→cream tint.
                'herbal-deep': '#1e3a33',
                'herbal-accent': '#b0893c',
                'herbal-light': '#f5eeda',
                'surface-gray': '#F2F2F2',

                // Brand palette — warm botanical luxury
                // forest → sage → cream → beige → gold
                'brand-forest': '#1e3a33',
                'brand-forest-dark': '#152a25',
                'brand-sage': '#7c9a86',
                'brand-sage-dark': '#5f8069',
                'brand-cream': '#f3eee7',
                'brand-beige': '#e9e0cd',
                'brand-gold': '#c9a452',
                'brand-gold-dark': '#b0893c',

                // Admin accent — the admin panel is built entirely on `indigo-*`.
                // Overriding the ramp with a forest-green scale rebrands the whole
                // admin to match the storefront without touching its markup.
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
            borderRadius: {
                DEFAULT: '0.125rem',
                lg: '0.25rem',
                xl: '0.5rem',
                full: '0.75rem',
            },
            spacing: {
                gutter: '18px',
                'margin-mobile': '16px',
                unit: '4px',
                'container-max': '1200px',
                'section-gap': '72px',
                'margin-desktop': '48px',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                'label-sm': ['Inter'],
                'label-md': ['Inter'],
                'display-lg-mobile': ['Plus Jakarta Sans'],
                'headline-sm': ['Plus Jakarta Sans'],
                'display-lg': ['Plus Jakarta Sans'],
                'headline-md': ['Plus Jakarta Sans'],
                'body-lg': ['Inter'],
                'body-md': ['Inter'],
            },
            fontSize: {
                'label-sm': ['11px', { lineHeight: '1.2', fontWeight: '500' }],
                'label-md': ['13px', { lineHeight: '1.4', letterSpacing: '0.01em', fontWeight: '600' }],
                'display-lg-mobile': ['26px', { lineHeight: '1.15', letterSpacing: '-0.01em', fontWeight: '700' }],
                'headline-sm': ['19px', { lineHeight: '1.35', fontWeight: '600' }],
                'display-lg': ['38px', { lineHeight: '1.1', letterSpacing: '-0.02em', fontWeight: '700' }],
                'headline-md': ['24px', { lineHeight: '1.25', fontWeight: '600' }],
                'body-lg': ['16px', { lineHeight: '1.55', fontWeight: '400' }],
                'body-md': ['14.5px', { lineHeight: '1.55', fontWeight: '400' }],
            },
        },
    },

    plugins: [forms, typography],
};
