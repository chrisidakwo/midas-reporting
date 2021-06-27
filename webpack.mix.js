const mix = require('laravel-mix');
const path = require('path');
const { exec } = require('child_process');

mix.extend('ziggy', new class {
  register(config = {}) {
    this.watch = config.watch ?? ['routes/**/*.php'];
    this.path = config.path ?? '';
    this.enabled = config.enabled ?? !Mix.inProduction();
  }

  boot() {
    if (!this.enabled) return;

    const command = () => exec(
      `php artisan ziggy:generate ${this.path}`,
      (error, stdout, stderr) => console.log(stdout)
    );

    command();

    if (Mix.isWatching() && this.watch) {
      ((require('chokidar')).watch(this.watch))
        .on('change', (path) => {
          console.log(`${path} changed...`);
          command();
        });
    }
  }
}());

mix.js('resources/js/app.js', 'public/js').vue()
  .sass('resources/css/app.scss', 'public/css')
  .postCss('node_modules/material-icons/iconfont/material-icons.css', 'public/css')
  .alias({
    '@': path.resolve('resources/js'),
    ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue'), // vendor/tightenco/ziggy/dist or 'vendor/tightenco/ziggy/dist/vue' if you're using the Vue plugin
  }).ziggy();
  // .extract(['jquery', 'axios', 'lodash'])
  // .sourceMaps();

if (!(mix.inProduction())) {
  mix.browserSync('midas-reporting.test');
}

if (mix.inProduction()) {
  mix.extract(['vue', 'axios', 'moment', 'popper.js', 'bootstrap', 'lodash', 'material-icons'])
    .sourceMaps()
    .version();
}
