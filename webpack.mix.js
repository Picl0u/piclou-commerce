let mix = require('laravel-mix');
mix.setPublicPath(path.normalize('web'));
mix.setResourceRoot(path.normalize('web'));

mix.disableNotifications();

mix.js('resources/assets/js/app.js', 'web/js');
mix.js('resources/assets/js/admin.js', 'web/js');
mix.sass('resources/assets/sass/app.scss', 'web/css').options({
    processCssUrls: false,
    outputStyle: 'compressed'
});
mix.sass('resources/assets/sass/admin.scss', 'web/css').options({
    processCssUrls: false,
    outputStyle: 'compressed'
});
