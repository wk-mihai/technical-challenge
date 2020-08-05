const mix = require('laravel-mix');

mix.options({processCssUrls: false})
    .js('resources/assets/js/app.js', 'public/js')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/fonts')
    .extract([
        'axios',
        'jquery',
        'popper.js',
        'bootstrap',
    ])
    .sass('resources/assets/sass/app.scss', 'public/css')
    .copy('resources/assets/images', 'public/img');

if (mix.inProduction()) {
    mix.version()
} else {
    mix.sourceMaps()
}

mix.disableNotifications()
