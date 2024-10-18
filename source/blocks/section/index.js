import './style.scss';
import './editor.scss';

import edit from './edit';
import metadata from './block.json';
import deprecated from './deprecated';

import { assign } from 'lodash';
import { registerBlockType } from '@wordpress/blocks';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'SectionBlockSave' }),
});
