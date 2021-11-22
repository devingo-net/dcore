let mix = require('laravel-mix');
mix.autoload({
    jquery: ['$', 'window.jQuery']
}).js('assets/script/entry.js', 'dist/script.js')
    .sass('assets/style/entry.scss', 'dist/style.css');