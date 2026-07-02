/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // Core Theme Colors
                'primary': '#006e0a',
                'on-primary': '#ffffff',

                // Surface & Background Colors
                'surface': '#ffffff',
                'on-surface': '#1a1c1c',
                'on-surface-variant': '#4a4a4a', // Slightly muted text
                'surface-container-lowest': '#f8f9fa', // Light footer background
            },
            fontFamily: {
                // Typography mapped to the imported Google Fonts
                'headline': ['"Hanken Grotesk"', 'sans-serif'],
                'body': ['Inter', 'sans-serif'],
                'label': ['Inter', 'sans-serif'],
            },
            fontSize: {
                // Custom Font Sizes based on the template's utility classes
                'headline-xl': ['3.5rem', { lineHeight: '1.1', fontWeight: '800', letterSpacing: '-0.02em' }],
                'headline-lg': ['2.5rem', { lineHeight: '1.2', fontWeight: '700', letterSpacing: '-0.01em' }],
                'headline-md': ['1.5rem', { lineHeight: '1.3', fontWeight: '700' }],

                'body-lg': ['1.125rem', { lineHeight: '1.6', fontWeight: '400' }],
                'body-md': ['1rem', { lineHeight: '1.5', fontWeight: '400' }],
                'body-sm': ['0.875rem', { lineHeight: '1.5', fontWeight: '400' }],

                'label-md': ['0.875rem', { lineHeight: '1.2', fontWeight: '600', letterSpacing: '0.02em' }],
                'label-sm': ['0.75rem', { lineHeight: '1.2', fontWeight: '600', letterSpacing: '0.05em' }],
            },
            spacing: {
                // Custom Spacing Tokens
                'margin-desktop': '2rem',
                'margin-mobile': '1rem',
                'stack-xl': '4rem',
                'stack-lg': '2rem',
                'stack-md': '1rem',
                'gutter': '1.5rem',
            },
            maxWidth: {
                'container-max': '1280px',
            }
        },
    },
    plugins: [],
}