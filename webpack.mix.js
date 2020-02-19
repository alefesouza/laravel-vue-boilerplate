const mix = require('laravel-mix');
const path = require('path');

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
  .sass('resources/assets/sass/app.scss', 'public/css')
  .webpackConfig({
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
    },
    devtool: mix.inProduction() ? '' : 'inline-source-map',
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
      }],
    },
    resolve: {
      extensions: ['*', '.js', '.jsx', '.vue', '.ts', '.tsx'],
      alias: {
        styles: path.resolve(__dirname, 'resources/assets/sass'),
        '@': path.resolve(__dirname, 'resources/assets/vue'),
      },
    },
    plugins: process.env.NODE_ENV != 'ci' ? [
      new BundleAnalyzerPlugin(),
    ] : [],
  });

// Thanks https://github.com/JeffreyWay/laravel-mix/issues/1483#issuecomment-366685986
Mix.listen('configReady', (webpackConfig) => {
  if (Mix.isUsing('hmr')) {
    webpackConfig.entry = Object.keys(webpackConfig.entry).reduce((entries, entry) => {
      entries[entry.replace(/^\//, '')] = webpackConfig.entry[entry];
      return entries;
    }, {});

    webpackConfig.plugins.forEach((plugin) => {
      if (plugin.constructor.name === 'ExtractTextPlugin') {
        plugin.filename = plugin.filename.replace(/^\//, '');
      }
    });
  }
});
