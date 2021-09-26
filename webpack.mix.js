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

mix.js('resources/js/app.js', 'public/js')
    .styles(
        [
            'resources/adminlte/plugins/fontawesome-free/css/all.min.css',
            'resources/adminlte/dist/css/adminlte.min.css',
            "resources/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css",
            "resources/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css"
        ], 'public/css/plantilla.css')
    .scripts(
        [
            'resources/adminlte/plugins/jquery/jquery.min.js',
            'resources/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js',
            'resources/adminlte/dist/js/adminlte.min.js',
            'resources/adminlte/plugins/sweetalert2/sweetalert2.all.min.js',
            'resources/adminlte/plugins/datatables/jquery.dataTables.min.js',
            'resources/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
            'resources/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js',
            'resources/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js',
            'resources/adminlte/plugins/chart.js/Chart.min.js',
            'resources/adminlte/plugins/moment/moment.min.js'
        ], 'public/js/plantilla.js'
    )
    .copy('resources/adminlte/dist/css/adminlte.min.css.map', 'public/css/adminlte.min.css.map')
    .copy('resources/adminlte/dist/js/adminlte.min.js.map', 'public/js/adminlte.min.js.map')
    .copy('resources/adminlte/plugins/moment/moment.min.js.map', 'public/js/moment.min.js.map')
    .copy('node_modules/vue-select/dist/vue-select.js.map', 'public/js/vue-select.js.map')
    ;
