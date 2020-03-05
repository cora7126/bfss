let mix = require('laravel-mix');

mix.sass('scss/phelix.scss', 'css/');
mix.options({
  processCssUrls: false
});
