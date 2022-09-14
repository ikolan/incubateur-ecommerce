module.exports = {
    content: ['./src/**/*.{js,jsx,ts,tsx}', './index.html'],
    theme: {
        extend: {
            minWidth: {
                navBtn: '3rem',
            },
            minHeight: {
                '100px': '100px',
            },
            dropShadow: {
                productBox: '0 0 15px rgba(0, 0, 0, 0.25)',
            },
            boxShadow: {
                productDetails: '0px 10px 6px 10px rgba(0, 0, 0, 0.3)',
                cartProduct: '0px 6px 6px 5px rgba(0,0,0,0.3)',
            },
            fontFamily: {
                sans: ['"Quicksand"', 'sans-serif'],
                'sans-serif': ['"Quicksand"', 'sans-serif'],
                title: ['"Oxanium"', 'sans-serif'],
            },
            colors: {
                primary: {
                    light: '#f58d57',
                    DEFAULT: '#f16923',
                    dark: '#b24f1d',
                },
                secondary: {
                    light: '#212d6e',
                    DEFAULT: '#0d195a',
                    dark: '#0a1241',
                },
            },
        },
    },
    plugins: [require('@tailwindcss/forms')],
};
