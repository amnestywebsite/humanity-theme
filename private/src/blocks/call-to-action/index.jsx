import './style.scss';
import './editor.scss';

import { assign } from 'lodash';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';

import edit from './edit.jsx';
import metadata from './block.json';
import deprecated from './deprecated.jsx';
import transforms from './transforms.jsx';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  transforms,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'CallToActionBlockSave' }),
});
