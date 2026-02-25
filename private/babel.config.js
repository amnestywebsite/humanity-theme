const presets = [
  [
    '@babel/preset-env',
    {
      corejs: '3.48',
      useBuiltIns: 'usage',
      modules: 'auto',
      shippedProposals: true,
    },
  ],
  [
    '@babel/preset-react',
    {
      pragma: 'wp.element.createElement',
      pragmaFrag: 'wp.element.Fragment',
      throwIfNamespace: false,
    },
  ],
];

const plugins = [
  ['@babel/plugin-transform-optional-chaining'],
  ['@babel/plugin-proposal-pipeline-operator', { proposal: 'minimal' }],
  ['@babel/plugin-transform-class-properties'],
];

module.exports = {
  presets,
  plugins,
};
