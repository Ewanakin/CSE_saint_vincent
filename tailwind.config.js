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
      'error' : '#EA0A33',
      'black_transparent' : "rgba(0,0,0,0.4)",
    }
  },
  plugins: [],
}
