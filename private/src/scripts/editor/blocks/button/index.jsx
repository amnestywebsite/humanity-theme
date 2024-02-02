import classnames from 'classnames';
import DisplayComponent from './DisplayComponent.jsx';

const { assign, isEmpty } = lodash;
const { RichText } = wp.blockEditor;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

const blockAttributes = {
  ctaLink: {
    type: 'string',
  },
  ctaText: {
    type: 'array',
  },
  style: {
    type: 'string',
  },
};

registerBlockType('amnesty-core/block-button', {
  // translators: [admin]
  title: __('Button', 'amnesty'),
  icon: 'megaphone',
  category: 'amnesty-core',
  // translators: [admin]
  keywords: [__('Button', 'amnesty')],
  supports: {
    className: false,
    multiple: true,
    inserter: false,
  },
  attributes: assign({}, blockAttributes, {
    ctaText: {
      type: 'string',
    },
  }),

  edit: DisplayComponent,

  save: ({ attributes }) => {
    const { style = false, ctaLink, ctaText } = attributes;

    if (isEmpty(ctaLink) || isEmpty(ctaText)) {
      return null;
    }

    const linkClasses = classnames('btn', { [`btn--${style}`]: style });

    return (
      <a href={ctaLink} className={linkClasses}>
        <RichText.Content tagName="span" value={ctaText} />
      </a>
    );
  },
});
