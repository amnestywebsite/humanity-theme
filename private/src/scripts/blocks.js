/**
 * Gutenberg Blocks
 *
 * All blocks related JavaScript files should be imported here.
 * You can create a new block folder in this dir and include code
 * for that block here as well.
 *
 * All blocks should be included here since this is the file that
 * Webpack is compiling as the input file.
 */
import '../styles/gutenberg.scss';

import './editor/block-styles';

import './editor/plugins/appearance-options/index.jsx';
import './editor/plugins/pop-in/index.jsx';

import './editor/blocks/core-mods';
// import './editor/blocks/petition-list/index.jsx';
// import './editor/blocks/post-meta/index.jsx';
