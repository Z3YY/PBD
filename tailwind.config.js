module.exports = {
  content: ["./*.php", "./components/**/*.php", "./node_modules/preline/dist/*.js"],
  theme: {
    extend: {},
  },
  plugins: [
     // require('@tailwindcss/forms'),
     require('preline/plugin'),
  ],
};
