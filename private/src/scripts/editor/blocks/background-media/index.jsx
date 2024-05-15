import DisplayComponent from './DisplayComponent.jsx';
import './components/Column.jsx';
import deprecated from './deprecated.jsx';

const { InnerBlocks } = wp.blockEditor;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/background-media', {
  // translators: [admin]
  title: __('Background Media', 'amnesty'),
  // translators: [admin]
  description: __('Two-column layout with background images', 'amnesty'),
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('Background', 'amnesty'),
    // translators: [admin]
    __('Media', 'amnesty'),
    // translators: [admin]
    __('Text', 'amnesty'),
  ],

  deprecated,
  edit: DisplayComponent,

  save: () => <InnerBlocks.Content />,
});
