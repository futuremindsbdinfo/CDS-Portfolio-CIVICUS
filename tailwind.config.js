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
        'warm-white': '#FAF8F3'
      },
      fontFamily: {
        'sans': ['"Noto Sans Bengali"', '"SolaimanLipi"', 'Arial', 'sans-serif'],
        'serif': ['"Noto Serif Bengali"', 'serif']
      }
    },
  },
  plugins: [],
}
