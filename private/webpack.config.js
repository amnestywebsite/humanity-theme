const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const path = require('path');

// Project paths.
const SRC_PATH = path.resolve(__dirname, './src/');
const OUT_PATH = path.resolve(__dirname, '../wp-content/themes/humanity-theme/assets/');

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
    ...defaultConfig.plugins.map((plugin) => {
      if (plugin instanceof CopyWebpackPlugin) {
        return new CopyWebpackPlugin({
          patterns: [
            ...plugin.options.patterns,
            {
              from: 'static/**/*',
              context: path.resolve(__dirname, 'src'),
              to({ absoluteFilename }) {
                return absoluteFilename.replace(`${SRC_PATH}/static`, OUT_PATH);
              },
            },
          ],
        });
      }
      if (plugin instanceof MiniCssExtractPlugin) {
        return new MiniCssExtractPlugin({
          filename: '../styles/[name].css',
          chunkFilename: '[id].css',
        });
      }
      return plugin;
    }),
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
