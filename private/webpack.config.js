const path = require('path');
const webpack = require('webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const { EsbuildPlugin } = require('esbuild-loader');

// Project paths.
const SRC_PATH = path.resolve(__dirname, './src/');
const OUT_PATH = path.resolve(__dirname, '../wp-content/themes/humanity-theme/assets/');

/**
 * Filter entry points if specified in cli
 * @param {Object} env cli env vars
 * @returns {Object}
 */
const getEntries = (env) => {
  const entries = {
    bundle: path.resolve(__dirname, `${SRC_PATH}/scripts/App.js`),
    blocks: path.resolve(__dirname, `${SRC_PATH}/scripts/blocks.js`),
    editor: path.resolve(__dirname, `${SRC_PATH}/scripts/editor.js`),
    admin: path.resolve(__dirname, `${SRC_PATH}/scripts/admin.js`),
  };

  if (!env?.entry) {
    return entries;
  }

  const entry = {};

  env.entry.split(',').forEach((name) => {
    if (Object.hasOwnProperty.call(entries, name)) {
      entry[name] = entries[name];
    }
  });

  return entry;
};

/**
 * Filter list of loaded plugins based on cli args
 * @param {Object} argv raw cli args
 * @param {Object} env cli env vars
 * @returns {Array}
 */
const getPlugins = (argv, env) => {
  const staticPlugins = [
    new CopyWebpackPlugin({
      patterns: [
        {
          context: SRC_PATH,
          from: 'static/**/*',
          to({ absoluteFilename }) {
            return absoluteFilename.replace(`${SRC_PATH}/static`, OUT_PATH);
          },
        },
      ],
    }),
  ];

  // if we're only building static assets, skip CSS extraction
  if (env?.entry === 'static') {
    return [
      new ESLintPlugin(),
      // Sets mode so we can access it in `postcss.config.js`.
      new webpack.LoaderOptionsPlugin({ options: { mode: argv.mode } }),
      new StyleLintPlugin({ threads: true }),
      ...staticPlugins,
    ];
  }

  const plugins = [
    new ESLintPlugin(),
    // Sets mode so we can access it in `postcss.config.js`.
    new webpack.LoaderOptionsPlugin({ options: { mode: argv.mode } }),
    // Extract CSS to own bundle, filename relative to output.path.
    new MiniCssExtractPlugin({ filename: '../styles/[name].css', chunkFilename: '[name].css' }),
    new StyleLintPlugin({ threads: true }),
  ];

  // we're specifying an entry point that isn't static, skip static processing
  if (env?.entry) {
    return plugins;
  }

  // do everything
  return [...plugins, ...staticPlugins];
};

/**
 * Get the cache configuration for the build mode
 * @param {String} mode the build mode
 * @returns {Object|False}
 */
const getCacheConf = (mode) => {
  if (mode === 'production') {
    return false;
  }

  return {
    type: 'filesystem',
    profile: false,
    buildDependencies: {
      config: [__filename],
    },
  };
};

const config = (env, argv) => ({
  bail: argv.mode === 'production',
  cache: getCacheConf(argv.mode),
  target: 'web',
  profile: false,
  devtool: 'source-map',
  entry: getEntries(env),
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, `${OUT_PATH}/scripts`),
    pathinfo: false,
  },
  externals: {
    lodash: 'lodash',
    react: 'React',
    'react-dom': 'ReactDOM',
    '@wordpress/i18n': 'wp.i18n',
  },
  watchOptions: {
    ignored: /node_modules/,
  },
  performance: {
    hints: false,
  },
  optimization: {
    minimize: argv.mode === 'production',
    minimizer: [
      new EsbuildPlugin({
        target: 'es2015',
        css: true,
      }),
    ],
  },
  resolve: {
    symlinks: false,
  },
  stats: {
    all: false,
    assets: true,
    errors: true,
    errorDetails: true,
    excludeAssets: [/\.(eot|ttf|woff2?|jpg|png|svg)$/],
  },
  plugins: getPlugins(argv, env),
  module: {
    rules: [
      {
        test: /App\.js$/,
        loader: 'expose-loader',
        options: {
          exposes: 'App',
        },
      },
      {
        test: /\.s?(a|c)?ss$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
              url: false,
            },
          },
          {
            loader: 'postcss-loader',
            options: {
              sourceMap: true,
            },
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true,
            },
          },
        ],
      },
      {
        test: /\.jsx?$/,
        exclude: /(lodash|node_modules|react(-dom)?)/,
        loader: 'esbuild-loader',
        options: {
          target: 'es2015',
        },
      },
    ],
  },
});

module.exports = (env, argv) => config(env, argv);
