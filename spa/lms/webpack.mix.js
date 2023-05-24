const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath("../../assets/spa/lms/").js('src/appAdmin.js', 'js/app.js')
  .js('src/appBorne.js', 'js/borne.js')
  .sass('src/sass/app.scss', 'css')
  // .extract();
