var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

    //Compiling CSS
    mix.sass(
        'app.scss',
        'public/css',
        {includePaths: ['vendor/bower_components/foundation/scss']}
    );

    //Compiling JavaScript
    mix.scripts(
        [
            'jquery/dist/jquery.js',
            'angular/angular.js',
            'angular-foundation/mm-foundation-tpls.js',
            'angular-ui-router/release/angular-ui-router.js',
            'angular-animate/angular-animate.js',
            'file-saver.js/FileSaver.js',
            'Chart.js/Chart.js',
            'angular-chart.js/dist/angular-chart.js'
        ],
        'public/js/dependencies.js',
        'vendor/bower_components/'
    ).scripts(
        [
            'resources/assets/js/**/*.js',
        ],
        'public/js/app.js'
    );

    //Copying Templates
    mix.copy('resources/assets/js/**/*.html', 'public/views');
});
