import edit from './edit';
import metadata from './block.json';

const { registerBlockType } = wp.blocks;
const { RawHTML } = wp.element;

registerBlockType(metadata, {
  ...metadata,
  edit,
  save({ attributes }) {
    const { content } = attributes;

    return <RawHTML>{content}</RawHTML>;
  },
});
