import edit from './edit';
import metadata from './block.json';
import deprecated from './deprecated';

import './editor.scss';
import './style.scss';

import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import { assign } from 'lodash';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'SectionBlockSave' }),
});
