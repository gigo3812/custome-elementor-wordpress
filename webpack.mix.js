let mix = require('laravel-mix');

// main app.js and public.css as style.css
mix.sass(`resources/scss/public.scss`, `/assets/css`).sourceMaps();
mix.js(`resources/js/app.js`, `assets/js`).vue();
