// /** @type {import('tailwindcss').Config} */
export default {
    theme: {
        extend: {
            colors: {
                'surface-container-lowest': '#ffffff',
                'primary': '#006e0a',
                'on-surface': '#1a1c1c',
                // ... (copy your full color palette here)
            },
            borderRadius: {
                'lg': '0.25rem',
                'xl': '0.5rem',
                'full': '0.75rem',
            },
            // ... (copy spacing, fontFamily, and fontSize here)
        },
    },
    plugins: [require('@tailwindcss/forms')],
};