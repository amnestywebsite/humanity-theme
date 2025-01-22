import classnames from 'classnames';
import { __ } from '@wordpress/i18n';

const blockAttributes = {
  title: {
    type: 'string',
  },
  content: {
    type: 'string',
  },
  size: {
    type: 'string',
  },
  alignment: {
    type: 'string',
  },
};

const v2 = {
  supports: {
    className: true,
  },
  attributes: blockAttributes,
  save({ attributes }) {
    const { content = '', size = '', title = '', alignment = 'center', className } = attributes;

    const shareBase = 'https://twitter.com/intent/tweet';
    const fullUrl = `${shareBase}?text=${encodeURIComponent(content)}`;

    const blockClasses = classnames(className, 'tweetAction', {
      'tweetAction--narrow': size === 'narrow',
    });

    const buttonClasses = classnames(['btn', 'btn--fill', 'btn--large']);

    return (
      <div className={`tweetBlock align-${alignment}`}>
        <div className={blockClasses}>
          <div className="tweetAction-header">
            <span className="dashicons dashicons-twitter" aria-label="Twitter Logo"></span>
            <h3 className="tweetAction-title">{title}</h3>
          </div>
          <div className="tweetAction-content">{content}</div>
          <div className="tweetButton">
            <a
              className={buttonClasses}
              href={fullUrl}
              target="_blank"
              rel="noopener noreferrer"
              /* translators: [admin] */
              aria-label={__('Send this Tweet', 'amnesty')}
            >
              {/* translators: [admin] */ __('Send this Tweet', 'amnesty')}
            </a>
          </div>
        </div>
      </div>
    );
  },
};

const v1 = {
  supports: {
    className: false,
  },
  attributes: blockAttributes,
  save({ attributes }) {
    const { centred = false, content = '', size = '', title = '' } = attributes;

    const shareBase = 'https://twitter.com/intent/tweet';
    const fullUrl = `${shareBase}?text=${encodeURIComponent(content)}`;

    const blockClasses = classnames('tweetAction', {
      'tweetAction--narrow': size === 'narrow',
      aligncentre: centred === true,
    });

    const buttonClasses = classnames(['btn', 'btn--fill', 'btn--large']);

    return (
      <div className={blockClasses}>
        <div className="tweetAction-header">
          <span className="dashicons dashicons-twitter" aria-label="Twitter Logo"></span>
          <h3 className="tweetAction-title">{title}</h3>
        </div>
        <div className="tweetAction-content">{content}</div>
        <div>
          <a
            className={buttonClasses}
            href={fullUrl}
            target="_blank"
            rel="noopener noreferrer"
            /* translators: [admin] */
            aria-label={__('Send this Tweet', 'amnesty')}
          >
            {/* translators: [admin] */ __('Send this Tweet', 'amnesty')}
          </a>
        </div>
      </div>
    );
  },
};

export default [v2, v1];
