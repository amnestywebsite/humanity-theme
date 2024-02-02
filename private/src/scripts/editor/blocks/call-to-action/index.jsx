import classnames from 'classnames';
import DisplayComponent from './DisplayComponent.jsx';
import transforms from './transforms.jsx';
import deprecated from './deprecated.jsx';

const { isEmpty } = lodash;
const { InnerBlocks, RichText } = wp.blockEditor;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/block-call-to-action', {
  // translators: [admin]
  title: __('Call To Action', 'amnesty'),
  icon: 'megaphone',
  category: 'amnesty-core',
  // translators: [admin]
  keywords: [__('Call To Action', 'amnesty')],
  supports: {
    className: false,
    multiple: true,
  },
  attributes: {
    preheading: {
      type: 'string',
    },
    title: {
      type: 'string',
    },
    content: {
      type: 'string',
    },
    background: {
      type: 'string',
    },
    actionType: {
      type: 'string',
    },
  },

  transforms,

  deprecated,

  edit: DisplayComponent,

  save: ({ attributes }) => {
    const { background = false, preheading, title, content } = attributes;
    const divClasses = classnames('callToAction', { [`callToAction--${background}`]: background });

    return (
      <div className={divClasses} role="note" aria-label={title}>
        {!isEmpty(preheading) && (
          <RichText.Content tagName="h2" className="callToAction-preHeading" value={preheading} />
        )}
        {!isEmpty(title) && (
          <RichText.Content tagName="h1" className="callToAction-heading" value={title} />
        )}
        {!isEmpty(content) && (
          <RichText.Content tagName="p" className="callToAction-content" value={content} />
        )}
        <div className="innerBlocksContainer">
          <InnerBlocks.Content />
        </div>
      </div>
    );
  },
});
