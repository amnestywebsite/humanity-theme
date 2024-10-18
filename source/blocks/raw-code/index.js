import edit from './edit';
import metadata from './block.json';

import { registerBlockType } from '@wordpress/blocks';
import { RawHTML } from '@wordpress/element';

registerBlockType(metadata, {
  ...metadata,
  edit,
  save({ attributes }) {
    return <RawHTML>{attributes.content}</RawHTML>;
  },
});
