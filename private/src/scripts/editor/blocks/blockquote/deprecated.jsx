import classnames from 'classnames';

const { RichText } = wp.blockEditor;

const v2 = {
  supports: {
    className: false,
  },
  attributes: {
    align: {
      type: 'string',
    },
    size: {
      type: 'string',
    },
    colour: {
      type: 'string',
    },
    capitalise: {
      type: 'boolean',
    },
    lined: {
      type: 'boolean',
    },
    content: {
      type: 'string',
    },
    citation: {
      type: 'string',
    },
  },

  save({ attributes }) {
    const {
      align = '',
      size = '',
      colour = '',
      capitalise = false,
      lined = true,
      content = '',
      citation = '',
    } = attributes;

    const classes = classnames('blockquote', {
      [`align-${align}`]: !!align,
      [`is-${size}`]: !!size,
      [`is-${colour}`]: !!colour,
      'is-capitalised': capitalise,
      'is-lined': lined,
    });

    const quoteStyle = {};
    if (Object.prototype.hasOwnProperty.call(window, 'amnestyCoreI18n')) {
      const { openDoubleQuote, closeDoubleQuote, openSingleQuote, closeSingleQuote } =
        window.amnestyCoreI18n;

      quoteStyle.quotes = `"${openDoubleQuote}" "${closeDoubleQuote}" "${openSingleQuote}" "${closeSingleQuote}";`;
    }

    return (
      <blockquote className={classes} style={quoteStyle}>
        <RichText.Content tagName="p" value={content} />
        <RichText.Content tagName="cite" value={citation} />
      </blockquote>
    );
  },
};

const v1 = {
  supports: {
    className: false,
  },
  attributes: {
    align: {
      type: 'string',
    },
    size: {
      type: 'string',
    },
    colour: {
      type: 'string',
    },
    capitalise: {
      type: 'boolean',
    },
    lined: {
      type: 'boolean',
    },
    content: {
      type: 'string',
    },
    citation: {
      type: 'string',
    },
  },

  save({ attributes }) {
    const {
      align = '',
      size = '',
      colour = '',
      capitalise = false,
      lined = true,
      content = '',
      citation = '',
    } = attributes;

    const classes = classnames('blockquote', {
      [`align-${align}`]: !!align,
      [`is-${size}`]: !!size,
      [`is-${colour}`]: !!colour,
      'is-capitalised': capitalise,
      'is-lined': lined,
    });

    const quoteStyle = {};
    if (Object.prototype.hasOwnProperty.call(window, 'amnestyCoreI18n')) {
      const { openDoubleQuote, closeDoubleQuote, openSingleQuote, closeSingleQuote } =
        window.amnestyCoreI18n;

      quoteStyle.quotes = `"${openDoubleQuote}" "${closeDoubleQuote}" "${openSingleQuote}" "${closeSingleQuote}";`;
    }

    return (
      <blockquote className={classes} style={quoteStyle}>
        <RichText.Content tagName="p" value={content} />
        {citation && <RichText.Content tagName="cite" value={citation} />}
      </blockquote>
    );
  },
};

const deprecated = [v2, v1];

export default deprecated;
