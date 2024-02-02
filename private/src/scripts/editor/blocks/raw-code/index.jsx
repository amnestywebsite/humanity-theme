import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { RawHTML } = wp.element;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/code', {
  // translators: [admin]
  title: __('Raw Code', 'amnesty'),
  category: 'amnesty-core',
  supports: {
    className: false,
    html: false,
  },
  attributes: {
    content: {
      type: 'string',
      source: 'html',
    },
  },
  edit: DisplayComponent,
  save({ attributes }) {
    const { content } = attributes;

    return <RawHTML>{content}</RawHTML>;
  },
});
