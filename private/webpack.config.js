// eslint-disable-next-line import/no-extraneous-dependencies
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const ESLintPlugin = require('eslint-webpack-plugin');

const plugins = [
  ...defaultConfig.plugins,
  new ESLintPlugin({ extensions: ['js', 'jsx', 'ts', 'tsx'] }),
];

module.exports = {
  ...defaultConfig,
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
