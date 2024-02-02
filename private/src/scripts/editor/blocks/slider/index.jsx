import DisplayComponent from './DisplayComponent.jsx';
import deprecated from './deprecated.jsx';
import attributes from './attributes';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/block-slider', {
  // translators: [admin]
  title: __('Slider', 'amnesty'),
  icon: 'welcome-widgets-menus',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('Slider', 'amnesty'),
    // translators: [admin]
    __('Carousel', 'amnesty'),
    // translators: [admin]
    __('Scroller', 'amnesty'),
  ],
  attributes,
  deprecated,
  edit: DisplayComponent,
  save: () => null,
});
