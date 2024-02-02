const { getBlockVariations, unregisterBlockVariation } = wp.blocks;

const unused = [
  'amazon-kindle',
  'animoto',
  'cloudup',
  'crowdsignal',
  'dailymotion',
  'flickr',
  'imgur',
  'issuu',
  'kickstarter',
  'mixcloud',
  'pinterest',
  'pocket-casts',
  'reddit',
  'reverbnation',
  'screencast',
  'scribd',
  'slideshare',
  'smugmug',
  'soundcloud',
  'speaker-deck',
  'spotify',
  'ted',
  'tiktok',
  'tumblr',
  'videopress',
  'wolfram-cloud',
  'wordpress',
  'wordpress-tv',
];

/**
 * Remove unused embeds.
 */
wp.domReady(() => {
  getBlockVariations('core/embed').forEach((variant) => {
    if (unused.includes(variant.name)) {
      unregisterBlockVariation('core/embed', variant.name);
    }
  });
});
