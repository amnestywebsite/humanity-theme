import classnames from 'classnames';

const { assign, isEmpty } = lodash;
const { InnerBlocks, RichText } = wp.blockEditor;
const { createBlock } = wp.blocks;

const blockAttributes = {
  preheading: {
    type: 'array',
  },
  title: {
    type: 'string',
  },
  content: {
    type: 'array',
  },
  ctaLink: {
    type: 'string',
  },
  ctaText: {
    type: 'array',
  },
  background: {
    type: 'string',
  },
  style: {
    type: 'string',
  },
};

const v3 = {
  supports: {
    className: false,
    multiple: true,
  },
  attributes: assign({}, blockAttributes, {
    preheading: {
      type: 'string',
    },
    content: {
      type: 'string',
    },
    ctaText: {
      type: 'string',
    },
  }),
  save({ attributes }) {
    const { background = false, preheading, title, content } = attributes;
    const divClasses = classnames('callToAction', {
      [`callToAction--${background}`]: background,
    });

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
        <InnerBlocks.Content />
      </div>
    );
  },
};

const v2 = {
  supports: {
    className: false,
    multiple: true,
  },
  attributes: assign({}, blockAttributes, {
    preheading: {
      type: 'string',
    },
    content: {
      type: 'string',
    },
    ctaText: {
      type: 'string',
    },
  }),
  migrate: (attributes) => [
    attributes,
    [
      createBlock('amnesty-core/block-button', {
        ctaText: attributes.ctaText,
        style: attributes.style,
        ctaLink: attributes.ctaLink,
      }),
    ],
  ],
  save: ({ attributes }) => {
    const {
      background = false,
      style = false,
      preheading,
      title,
      content,
      ctaLink,
      ctaText,
    } = attributes;

    const divClasses = classnames('callToAction', {
      [`callToAction--${background}`]: background,
    });

    const linkClasses = classnames('btn', { [`btn--${style}`]: style });

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
        {!isEmpty(ctaLink) && !isEmpty(ctaText) && (
          <a href={ctaLink} className={linkClasses}>
            <RichText.Content tagName="span" value={ctaText} />
          </a>
        )}
      </div>
    );
  },
};

const v1 = {
  supports: {
    className: false,
    multiple: true,
  },
  attributes: blockAttributes,
  save: ({ attributes }) => {
    const {
      background = false,
      style = false,

      preheading,
      title,
      content,
      ctaLink,
      ctaText,
    } = attributes;

    const divClasses = classnames('callToAction', {
      [`callToAction--${background}`]: background,
    });

    const linkClasses = classnames('btn', { [`btn--${style}`]: style });

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
        {!isEmpty(ctaLink) && !isEmpty(ctaText) && (
          <a href={ctaLink} className={linkClasses}>
            <RichText.Content tagName="span" value={ctaText} />
          </a>
        )}
      </div>
    );
  },
};

const deprecated = [v3, v2, v1];

export default deprecated;
