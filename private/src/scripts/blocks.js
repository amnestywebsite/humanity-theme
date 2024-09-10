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

import './editor/blocks-deprecated/banner/index.jsx';
import './editor/blocks-deprecated/header/index.jsx';

import './editor/blocks/action/index.jsx';
import './editor/blocks/background-media/index.jsx';
import './editor/blocks/blockquote/index.jsx';
import './editor/blocks/button/index.jsx';
import './editor/blocks/call-to-action/index.jsx';
import './editor/blocks/collapsable/index.jsx';
import './editor/blocks/columns/index.jsx';
import './editor/blocks/core-mods';
import './editor/blocks/countdown-timer/index.jsx';
import './editor/blocks/custom-card/index.jsx';
import './editor/blocks/download/index.jsx';
import './editor/blocks/embed-flourish/index.jsx';
import './editor/blocks/embed-infogram/index.jsx';
import './editor/blocks/embed-sutori/index.jsx';
import './editor/blocks/embed-tickcounter/index.jsx';
import './editor/blocks/hero/index.jsx';
import './editor/blocks/hero/replaceHeaders';
import './editor/blocks/iframe-button/index.jsx';
import './editor/blocks/iframe/index.jsx';
import './editor/blocks/image/index.jsx';
import './editor/blocks/key-facts/index.jsx';
import './editor/blocks/link-group/index.jsx';
import './editor/blocks/links-with-icons/index.jsx';
import './editor/blocks/menu/index.jsx';
import './editor/blocks/petition-list/index.jsx';
import './editor/blocks/post-list/index.jsx';
import './editor/blocks/post-meta/index.jsx';
import './editor/blocks/raw-code/index.jsx';
import './editor/blocks/regions/index.jsx';
import './editor/blocks/related-content/index.jsx';
import './editor/blocks/section/index.jsx';
import './editor/blocks/slider/index.jsx';
import './editor/blocks/stat-counter/index.jsx';
import './editor/blocks/term-list/index.jsx';
import './editor/blocks/tweet/index.jsx';

import './editor/fse-blocks/pop-in/index.jsx';
import './editor/fse-blocks/site-header/index.jsx';
import './editor/fse-blocks/archive-filters/index.jsx';
