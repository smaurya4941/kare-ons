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
                'primary': '#000000',
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
                'secondary-fixed': '#89f5e7',
                'on-primary-container': '#7c839b',
                'primary-container': '#131b2e',
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
                'on-secondary-fixed': '#00201d',
                'soft-border': '#e1e4e8',
                'clinical-white': '#ffffff',
            },
            borderRadius: {
                DEFAULT: '0.125rem',
                lg: '0.25rem',
                xl: '0.5rem',
                full: '0.75rem',
            },
            spacing: {
                gutter: '24px',
                'margin-mobile': '20px',
                unit: '4px',
                'container-max': '1280px',
                'section-gap': '120px',
                'margin-desktop': '64px',
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
                'label-sm': ['12px', { lineHeight: '1.2', fontWeight: '500' }],
                'label-md': ['14px', { lineHeight: '1.4', letterSpacing: '0.01em', fontWeight: '600' }],
                'display-lg-mobile': ['32px', { lineHeight: '1.2', letterSpacing: '-0.01em', fontWeight: '700' }],
                'headline-sm': ['24px', { lineHeight: '1.4', fontWeight: '600' }],
                'display-lg': ['48px', { lineHeight: '1.1', letterSpacing: '-0.02em', fontWeight: '700' }],
                'headline-md': ['30px', { lineHeight: '1.3', fontWeight: '600' }],
                'body-lg': ['18px', { lineHeight: '1.6', fontWeight: '400' }],
                'body-md': ['16px', { lineHeight: '1.6', fontWeight: '400' }],
            },
        },
    },

    plugins: [forms, typography],
};
