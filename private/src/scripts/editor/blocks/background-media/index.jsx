import classnames from 'classnames';
import DisplayComponent from './DisplayComponent.jsx';
import './components/Column.jsx';

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

  edit: DisplayComponent,

  save({ className, innerBlocks }) {
    const leftImage = innerBlocks[0]?.attributes?.image?.id;
    const rightImage = innerBlocks[1]?.attributes?.image?.id;

    const blockClasses = classnames(className, {
      'has-imageLeft': !!leftImage,
      'has-imageRight': !!rightImage,
    });

    return (
      <div className={blockClasses}>
        <InnerBlocks.Content />
      </div>
    );
  },
});
