import './style.scss';
import './editor.scss';

import edit from './edit';
import metadata from './block.json';

import { registerBlockType } from '@wordpress/blocks';

registerBlockType(metadata, {
  ...metadata,
  edit,
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
