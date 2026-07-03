/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // ── Lime Green Palette ──
                'lime': {
                    50:  '#f0fdf1',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#32cd32',  // Primary brand
                    600: '#28a828',
                    700: '#1a8c1a',
                    800: '#006e0a',  // Deep primary
                    900: '#003d06',
                },

                // ── Light Mode Tokens ──
                'primary': '#32cd32',
                'primary-dark': '#006e0a',
                'on-primary': '#ffffff',
                'primary-container': '#32cd32',
                'on-primary-container': '#000000',

                'surface': '#ffffff',
                'surface-container': '#f4f4f4',
                'surface-container-low': '#f0f0f0',
                'surface-container-lowest': '#f8f9fa',
                'on-surface': '#0d0d0d',
                'on-surface-variant': '#4a4a4a',
                'secondary': '#6b7280',
                'outline': '#9ca3af',
                'outline-variant': '#e5e7eb',
                'background': '#ffffff',
                'on-background': '#0d0d0d',

                // ── Dark Mode Tokens (used via dark: prefix) ──
                'dark-surface': '#0d0d0d',
                'dark-surface-container': '#1a1a1a',
                'dark-surface-elevated': '#242424',
                'dark-on-surface': '#f0f0f0',
                'dark-on-surface-variant': '#a0a0a0',
                'dark-outline': '#333333',
                'dark-outline-variant': '#2a2a2a',
            },
            fontFamily: {
                'headline': ['"Hanken Grotesk"', 'sans-serif'],
                'body': ['Inter', 'sans-serif'],
                'label': ['Inter', 'sans-serif'],
            },
            fontSize: {
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
                'margin-desktop': '2rem',
                'margin-mobile': '1rem',
                'stack-xl': '4rem',
                'stack-lg': '2rem',
                'stack-md': '1rem',
                'gutter': '1.5rem',
                'base': '1rem',
            },
            maxWidth: {
                'container-max': '1280px',
            },
            boxShadow: {
                'lime-sm':  '0 0 12px rgba(50, 205, 50, 0.25)',
                'lime-md':  '0 0 24px rgba(50, 205, 50, 0.30)',
                'lime-lg':  '0 0 40px rgba(50, 205, 50, 0.35)',
                'lime-glow':'0 0 20px rgba(50, 205, 50, 0.50)',
            },
            animation: {
                'float-slow':   'float-slow 9s ease-in-out infinite',
                'float-slower': 'float-slower 12s ease-in-out infinite',
                'shimmer':      'shimmer 3s linear infinite',
                'rise-in':      'rise-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) both',
                'pulse-glow':   'pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'slide-up':     'slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1) both',
            },
            keyframes: {
                'float-slow':   { '0%, 100%': { transform: 'translateY(0) translateX(0)' }, '50%': { transform: 'translateY(-20px) translateX(10px)' } },
                'float-slower': { '0%, 100%': { transform: 'translateY(0) translateX(0)' }, '50%': { transform: 'translateY(18px) translateX(-14px)' } },
                'shimmer':      { '0%': { backgroundPosition: '0% 50%' }, '100%': { backgroundPosition: '200% 50%' } },
                'rise-in':      { from: { opacity: '0', transform: 'translateY(14px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
                'pulse-glow':   { '0%, 100%': { opacity: '0.5', transform: 'scale(1)' }, '50%': { opacity: '1', transform: 'scale(1.05)' } },
                'slide-up':     { from: { opacity: '0', transform: 'translateY(24px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
            },
        },
    },
    plugins: [],
}