/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
    colors: {
      'primary': '#384394',
      'secondary': '#CCC65D',
      'third' : '#DCDCDC',
      'error' : '#EA0A33'
    }
  },
  plugins: [],
}
