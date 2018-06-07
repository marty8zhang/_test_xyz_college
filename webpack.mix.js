let mix = require('laravel-mix');

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
if (process.env.APP_ENV === 'demo') {
  // Development Notes:
  //   * If the demo site isn't right underneath your web root, e.g., http://yourdomain.com/xyz-college/, all url() values in your CSSs won't be linked correctly by Laravel Mix. This is because by default Laravel Mix points the base/resource root to '/', e.g., '/fonts/' for font files. To resolve this problem, you can set the DEMO_ROOT_URI (or whatever variable name you like, as long as they are consistant among this file, .env and your Blade templates) to something like '/xyz-college' in .env to tell Laravel Mix starts from where to link those assets.
  //   * There shouldn't be a trailing slash in DEMO_ROOT_URI, otherwise it'll be problematic in Blade templates.
  //   * Since .env is used here, if you're using configuration cache as well, don't forget to run "php artisan cache:clear" before letting Laravel Mix compile the assets.
  mix.setResourceRoot(process.env.DEMO_ROOT_URI + '/');
}

mix.js('resources/assets/js/app.js', 'public/js')
        .sass('resources/assets/sass/app.scss', 'public/css')
        .styles([
          'resources/assets/stylesheets/custom.css',
        ], 'public/css/additions.css');

if (mix.inProduction()) {
  mix.version();
}