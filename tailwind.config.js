const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './vendor/laravel/jetstream/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
  ],

  // darkMode: 'class',

  theme: {
    extend: {
      colors: {
        primary: "#B12F47",
        charcoal: "#454851",
        "bright-maroon": "#B12F47",
        "baby-powder": "#FDFFFC",
        "pacific-blue": "#009FB7",
        "light-salmon": "#FF9B71",
      },
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
