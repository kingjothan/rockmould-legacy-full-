/** @type {import('tailwindcss').Config} */
export default {
    safelist: [
        'text-sm', 'text-base', 'text-lg', 'text-xl', 'text-2xl', 'text-3xl',
        'text-4xl', 'text-5xl', 'text-6xl', 'text-7xl', 'text-8xl', 'text-9xl',
    ],
    content: [
        './app/**/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        container: {
            center: true,
            padding: '2rem',
            screens: {
                '2xl': '1400px',
            },
        },
        extend: {
            screens: {
                '3xl': '1920px',
                '4xl': '2560px',
            },
            fontFamily: {
                display: ['var(--font-display)'],
                subheader: ['var(--font-subheader)'],
                body: ['var(--font-body)'],
                sans: ['var(--font-body)'],
            },
            colors: {
                border: 'hsl(var(--border) / <alpha-value>)',
                input: 'hsl(var(--input) / <alpha-value>)',
                ring: 'hsl(var(--ring) / <alpha-value>)',
                background: 'hsl(var(--background) / <alpha-value>)',
                foreground: 'hsl(var(--foreground) / <alpha-value>)',
                primary: {
                    DEFAULT: 'hsl(var(--primary) / <alpha-value>)',
                    foreground: 'hsl(var(--primary-foreground) / <alpha-value>)',
                },
                secondary: {
                    DEFAULT: 'hsl(var(--secondary) / <alpha-value>)',
                    foreground: 'hsl(var(--secondary-foreground) / <alpha-value>)',
                },
                muted: {
                    DEFAULT: 'hsl(var(--muted) / <alpha-value>)',
                    foreground: 'hsl(var(--muted-foreground) / <alpha-value>)',
                },
                accent: {
                    DEFAULT: 'hsl(var(--accent) / <alpha-value>)',
                    foreground: 'hsl(var(--accent-foreground) / <alpha-value>)',
                },
                destructive: {
                    DEFAULT: 'hsl(var(--destructive) / <alpha-value>)',
                    foreground: 'hsl(var(--destructive-foreground) / <alpha-value>)',
                },
                card: {
                    DEFAULT: 'hsl(var(--card) / <alpha-value>)',
                    foreground: 'hsl(var(--card-foreground) / <alpha-value>)',
                },
                popover: {
                    DEFAULT: 'hsl(var(--popover) / <alpha-value>)',
                    foreground: 'hsl(var(--popover-foreground) / <alpha-value>)',
                },
                gold: {
                    DEFAULT: 'hsl(var(--gold) / <alpha-value>)',
                    light: 'hsl(var(--gold-light) / <alpha-value>)',
                    dark: 'hsl(var(--gold-dark) / <alpha-value>)',
                },
                charcoal: {
                    DEFAULT: 'hsl(var(--charcoal) / <alpha-value>)',
                    light: 'hsl(var(--charcoal-light) / <alpha-value>)',
                },
                ash: 'hsl(var(--ash) / <alpha-value>)',
                cream: 'hsl(var(--cream) / <alpha-value>)',
                surface: {
                    DEFAULT: 'hsl(var(--surface) / <alpha-value>)',
                    elevated: 'hsl(var(--surface-elevated) / <alpha-value>)',
                    overlay: 'hsl(var(--surface-overlay) / <alpha-value>)',
                },
            },
            borderRadius: {
                lg: 'var(--radius)',
                md: 'calc(var(--radius) - 2px)',
                sm: 'calc(var(--radius) - 4px)',
            },
            keyframes: {
                'accordion-down': {
                    from: { height: '0', opacity: '0' },
                    to: { height: 'var(--radix-accordion-content-height)', opacity: '1' },
                },
                'accordion-up': {
                    from: { height: 'var(--radix-accordion-content-height)', opacity: '1' },
                    to: { height: '0', opacity: '0' },
                },
                'fade-in': {
                    from: { opacity: '0', transform: 'translateY(8px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                shimmer: {
                    '0%, 100%': { 'background-position': '0% 50%' },
                    '50%': { 'background-position': '100% 50%' },
                },
            },
            animation: {
                'accordion-down': 'accordion-down 0.2s ease-out',
                'accordion-up': 'accordion-up 0.2s ease-out',
                'fade-in': 'fade-in 0.3s ease-out',
                shimmer: 'shimmer 3s ease infinite',
            },
        },
    },
    plugins: [],
};
