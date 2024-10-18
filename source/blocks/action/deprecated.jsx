import classnames from 'classnames';

import { assign, omit } from 'lodash';
import { __ } from '@wordpress/i18n';

const blockAttributes = {
  style: {
    type: 'string',
  },
  centred: {
    type: 'boolean',
  },
  label: {
    type: 'string',
  },
  content: {
    type: 'string',
  },
  imageID: {
    type: 'integer',
  },
  imageURL: {
    type: 'string',
  },
  imageAlt: {
    type: 'string',
  },
  link: {
    type: 'string',
  },
  buttonBackground: {
    type: 'string',
  },
};

const v4 = {
  supports: {
    className: false,
    align: true,
  },
  attributes: assign({}, omit(blockAttributes, 'buttonBackground'), {
    linkText: {
      type: 'string',
    },
    largeImageURL: {
      type: 'string',
    },
  }),
  save({ attributes }) {
    const {
      style = 'standard',
      centred = false,
      content,
      imageAlt,
      imageURL,
      label,
      link,
      linkText,
      largeImageURL,
    } = attributes;

    const blockClasses = classnames('actionBlock', {
      'actionBlock--wide': style === 'wide',
      'is-centred': centred,
    });

    const buttonClasses = classnames('btn', 'btn--fill', 'btn--large');

    return (
      <figure className={blockClasses}>
        <div className="actionBlock-figure">
          {style === 'wide' && (
            <img className="actionBlock-image" src={largeImageURL} alt={imageAlt} />
          )}
          {style === 'standard' && (
            <img className="actionBlock-image" src={imageURL} alt={imageAlt} />
          )}
          <span className="actionBlock-label">{label}</span>
        </div>
        <figcaption className="actionBlock-content">
          <p>{content}</p>
          <a className={buttonClasses} href={link}>
            {linkText}
          </a>
        </figcaption>
      </figure>
    );
  },
};

const v3 = {
  supports: {
    className: false,
  },
  attributes: blockAttributes,
  save({ attributes }) {
    const {
      style = 'standard',
      centred = false,
      content,
      imageAlt,
      imageURL,
      label,
      link,
      linkText,
    } = attributes;

    const blockClasses = classnames('actionBlock', {
      'actionBlock--wide': style === 'wide',
      'is-centred': centred,
    });

    const buttonClasses = classnames('btn', 'btn--fill', 'btn--large');

    return (
      <figure className={blockClasses}>
        <div className="actionBlock-figure">
          <img className="actionBlock-image" src={imageURL} alt={imageAlt} />
          <span className="actionBlock-label">{label}</span>
        </div>
        <figcaption className="actionBlock-content">
          <p>{content}</p>
          <a className={buttonClasses} href={link}>
            {linkText}
          </a>
        </figcaption>
      </figure>
    );
  },
};

const v2 = {
  supports: {
    className: false,
  },
  attributes: blockAttributes,
  save({ attributes }) {
    const {
      style = 'standard',
      label,
      content,
      imageURL,
      imageAlt,
      link,
      buttonBackground,
      align,
    } = attributes;

    const classes = classnames('actionBlock', {
      'actionBlock--wide': style === 'wide',
      align,
    });

    return (
      <figure className={classes}>
        <div className="actionBlock-figure">
          <img className="actionBlock-image" src={imageURL} alt={imageAlt} />
          <span className="actionBlock-label">{label}</span>
        </div>
        <figcaption className="actionBlock-content">
          <p>{content}</p>
          <a
            className={classnames({
              'tweetAction-button': true,
              'actionBlock-button': true,
              [`actionBlock-button--${buttonBackground}`]: !!buttonBackground,
            })}
            href={link}
          >
            {/* translators: [admin] */ __('Act Now', 'amnesty')}
          </a>
        </figcaption>
      </figure>
    );
  },
};

const v1 = {
  supports: {
    className: false,
  },
  attributes: blockAttributes,
  save({ attributes }) {
    const {
      style = 'standard',
      centred = false,
      label,
      content,
      imageURL,
      imageAlt,
      link,
    } = attributes;

    const blockClasses = classnames('actionBlock', {
      'actionBlock--wide': style === 'wide',
      'is-centred': centred,
    });

    const buttonClasses = classnames('btn', 'btn--fill', 'btn--large');

    return (
      <figure className={blockClasses}>
        <div className="actionBlock-figure">
          <img className="actionBlock-image" src={imageURL} alt={imageAlt} />
          <span className="actionBlock-label">{label}</span>
        </div>
        <figcaption className="actionBlock-content">
          <p>{content}</p>
          <a className={buttonClasses} href={link}>
            {/* translators: [admin] */ __('Act Now', 'amnesty')}
          </a>
        </figcaption>
      </figure>
    );
  },
};

const deprecated = [v4, v3, v2, v1];

export default deprecated;
