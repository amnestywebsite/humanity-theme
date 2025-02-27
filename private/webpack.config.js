const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const ESLintPlugin = require('eslint-webpack-plugin');

const plugins = [
  ...defaultConfig.plugins,
  new ESLintPlugin({ extensions: ['js', 'jsx', 'ts', 'tsx'] }),
];

module.exports = {
  ...defaultConfig,
  plugins,
};
