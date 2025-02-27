import classnames from 'classnames';

import { InnerBlocks } from '@wordpress/block-editor';

const v1 = {
  supports: {
    className: false,
    inserter: false,
  },
  attributes: {
    title: {
      type: 'string',
    },
    quantity: {
      type: 'number',
    },
    background: {
      type: 'string',
    },
  },
  save({ attributes }) {
    const { title = '', background = '' } = attributes;

    const classes = classnames('factBlock', {
      'has-background': !!background,
      [`has-${background}-background-color`]: !!background,
    });

    const label = title.replace(' ', '-').toLowerCase();

    return (
      <aside className={classes} aria-labelledby={label}>
        <h2 id={label} className="factBlock-title" aria-hidden="true">
          {title}
        </h2>
        <ol>
          <InnerBlocks.Content />
        </ol>
      </aside>
    );
  },
};

const deprecated = [v1];

export default deprecated;
