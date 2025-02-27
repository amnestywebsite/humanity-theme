import './editor.scss';

import { registerBlockType } from '@wordpress/blocks';
import { RawHTML } from '@wordpress/element';

import edit from './edit.jsx';
import metadata from './block.json';

registerBlockType(metadata, {
  ...metadata,
  edit,
  save: ({ attributes }) => <RawHTML>{attributes.content}</RawHTML>,
});
