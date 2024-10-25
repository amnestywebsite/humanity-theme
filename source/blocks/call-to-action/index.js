import './style.scss';
import './editor.scss';

import { assign } from 'lodash';
import edit from './edit';
import metadata from './block.json';
import deprecated from './deprecated';
import transforms from './transforms';

import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  transforms,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'CallToActionBlockSave' }),
});
