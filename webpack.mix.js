const mix = require('laravel-mix');
const path = require('path');

require('laravel-mix-workbox');

const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

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

mix.disableSuccessNotifications();

mix
  .options({
    processCssUrls: false,
  })
  .js('resources/assets/vue/app.ts', 'public/js')
  .vue()
  .sass('resources/assets/sass/app.scss', 'public/css')

if (mix.inProduction()) {
  mix.injectManifest({
    swSrc: './resources/assets/vue/service-worker.js',
    chunks: ['/js/app', 'login', 'example'],
  });
}

mix.webpackConfig({
  output: {
    chunkFilename: mix.inProduction() ? "js/[name].[chunkhash].js" : "js/[name].js",
    devtoolModuleFilenameTemplate: info => {
      var $filename = 'sources://' + info.resourcePath;
      if (info.resourcePath.match(/\.vue$/) && !info.allLoaders.match(/type=script/)) {
        $filename = 'webpack-generated:///' + info.resourcePath + '?' + info.hash;
      }
      return $filename;
    },
    devtoolFallbackModuleFilenameTemplate: 'webpack:///[resource-path]?[hash]',
    publicPath: mix.inProduction() ? '' : 'http://localhost:8080/',
  },
  devtool: mix.inProduction() ? 'hidden-source-map' : 'inline-source-map',
  module: {
    rules: [{
      test: /\.tsx?$/,
      loader: 'ts-loader',
      options: {
        appendTsSuffixTo: [/\.vue$/],
      },
      exclude: /node_modules/,
    }, {
      test: /\.(graphql|gql)$/,
      loader: 'graphql-tag/loader',
      exclude: /node_modules/,
    }, {
      test: /\.pug$/,
      loader: 'pug-plain-loader'
    }, {
      test: /\.worker\.js$/,
      use: { loader: 'worker-loader' }
    }],
  },
  resolve: {
    extensions: ['*', '.js', '.jsx', '.vue', '.ts', '.tsx'],
    alias: {
      styles: path.resolve(__dirname, 'resources/assets/sass'),
      '@': path.resolve(__dirname, 'resources/assets/vue'),
    },
  },
  plugins: process.env.NODE_ENV == 'production' ? [
    new BundleAnalyzerPlugin(),
  ] : [],
});
