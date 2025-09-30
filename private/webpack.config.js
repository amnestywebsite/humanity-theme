const path = require('path');
// eslint-disable-next-line import/no-extraneous-dependencies
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const ESLintPlugin = require('eslint-webpack-plugin');

const plugins = [
  ...defaultConfig.plugins,
  new ESLintPlugin({ extensions: ['js', 'jsx', 'ts', 'tsx'] }),
];

module.exports = {
  ...defaultConfig,
  entry: {
    ...defaultConfig.entry(),
    admin: path.resolve(__dirname, './src/admin/index.js'),
    editor: path.resolve(__dirname, './src/editor/index.js'),
    editorPlugins: path.resolve(__dirname, './src/editor-plugins/index.js'),
    frontend: path.resolve(__dirname, './src/frontend/index.js'),
    shared: path.resolve(__dirname, './src/shared/index.js'),
  },
  plugins,
  devtool: 'source-map',
  performance: {
    hints: false,
  },
  stats: {
    preset: 'normal',
    colors: true,
    excludeAssets: [/\.(eot|ttf|woff2?|jpg|json|php|png|svg)$/],
    timings: true,
  },
};
