/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.{html,php}",
    "./includes/**/*.{html,php}",
    "./admin/**/*.{html,php}",
    "./assets/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        'cds-green': '#3A7D5C',
        'cds-blue': '#1e3a8a',
        'cds-bg': '#f7faf8',
        'warm-white': '#FAF8F3',
        background: 'oklch(0.982 0.012 90)',
        foreground: 'oklch(0.27 0.03 260)',
        card: 'oklch(0.995 0.006 90)',
        'card-foreground': 'oklch(0.27 0.03 260)',
        popover: 'oklch(1 0 0)',
        'popover-foreground': 'oklch(0.27 0.03 260)',
        primary: {
          DEFAULT: 'oklch(0.55 0.09 155 / <alpha-value>)',
          foreground: 'oklch(0.99 0.006 90 / <alpha-value>)',
          soft: 'oklch(0.93 0.04 155 / <alpha-value>)'
        },
        secondary: {
          DEFAULT: 'oklch(0.32 0.13 268 / <alpha-value>)',
          foreground: 'oklch(0.98 0.01 90 / <alpha-value>)'
        },
        muted: {
          DEFAULT: 'oklch(0.94 0.015 85)',
          foreground: 'oklch(0.48 0.02 260)'
        },
        accent: {
          DEFAULT: 'oklch(0.93 0.04 155)',
          foreground: 'oklch(0.35 0.08 155)'
        },
        destructive: {
          DEFAULT: 'oklch(0.58 0.22 27)',
          foreground: 'oklch(0.99 0.01 90)'
        }
      },
      fontFamily: {
        'sans-bn': ['"Noto Sans Bengali"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        'serif-bn': ['"Noto Serif Bengali"', 'ui-serif', 'Georgia', 'serif'],
        'sans': ['"Noto Sans Bengali"', '"SolaimanLipi"', 'Arial', 'sans-serif'],
        'serif': ['"Noto Serif Bengali"', 'serif']
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-20px)' },
        }
      },
      animation: {
        'float': 'float 6s ease-in-out infinite',
      }
    },
  },
  plugins: [],
}
