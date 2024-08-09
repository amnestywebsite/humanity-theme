const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const path = require('path');

// Project paths.
const SRC_PATH = path.resolve(__dirname, './src/');
const OUT_PATH = path.resolve(__dirname, '../wp-content/themes/humanity-theme/assets/');

// Extend the baseConfig module rules to disable url handling in css-loader
defaultConfig.module.rules = defaultConfig.module.rules.map((rule) => {
  if (Array.isArray(rule.use)) {
    rule.use = rule.use.map((loaderConfig) => {
      if (
        typeof loaderConfig === 'object' &&
        loaderConfig.loader &&
        loaderConfig.loader.includes('node_modules/css-loader')
      ) {
        return {
          ...loaderConfig,
          options: {
            ...loaderConfig.options,
            url: false, // Disable URL handling in css-loader
          },
        };
      }
      return loaderConfig;
    });
  }
  return rule;
});

module.exports = {
  ...defaultConfig,
  entry: {
    bundle: path.resolve(__dirname, `${SRC_PATH}/scripts/App.js`),
    blocks: path.resolve(__dirname, `${SRC_PATH}/scripts/blocks.js`),
    editor: path.resolve(__dirname, `${SRC_PATH}/scripts/editor.js`),
    admin: path.resolve(__dirname, `${SRC_PATH}/scripts/admin.js`),
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, `${OUT_PATH}/scripts`),
  },
  plugins: [
    ...defaultConfig.plugins
      .map((plugin) => {
        if (plugin.constructor.name === 'CopyPlugin') {
          return new CopyWebpackPlugin({
            patterns: [
              ...plugin.patterns,
              {
                from: 'static/**/*',
                context: SRC_PATH,
                to({ absoluteFilename }) {
                  return absoluteFilename.replace(`${SRC_PATH}/static`, OUT_PATH);
                },
              },
            ],
          });
        }
        if (plugin.constructor.name === 'MiniCssExtractPlugin') {
          // Output the CSS into humanity-theme/assets/styles/
          return new MiniCssExtractPlugin({
            filename: '../styles/[name].css',
            chunkFilename: '[id].css',
          });
        }
        // Disable RTL stylesheet generation.
        if (plugin.constructor.name === 'RtlCssPlugin') {
          return false;
        }
        return plugin;
      })
      .filter(Boolean),
  ],
  module: {
    ...defaultConfig.module,
    rules: [
      ...defaultConfig.module.rules,
      {
        test: /App\.js$/,
        loader: 'expose-loader',
        options: {
          exposes: 'App',
        },
      },
    ],
  },
  externals: {
    lodash: 'lodash',
    react: 'React',
    'react-dom': 'ReactDOM',
    '@wordpress/i18n': 'wp.i18n',
  },
};
