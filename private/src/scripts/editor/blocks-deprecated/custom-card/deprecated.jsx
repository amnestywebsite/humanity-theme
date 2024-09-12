import classnames from 'classnames';

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
  scrollLink: {
    type: 'string',
  },
  linkText: {
    type: 'string',
  },
  largeImageURL: {
    type: 'string',
  },
};

const v1 = {
  attributes: blockAttributes,
  supports: {
    className: false,
    align: true,
  },
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
      scrollLink,
    } = attributes;

    const blockClasses = classnames('customCard', {
      'actionBlock--wide': style === 'wide',
      'is-centred': centred,
    });

    const buttonClasses = classnames('btn', 'btn--fill', 'btn--large');

    let linkButton;
    if (scrollLink) {
      linkButton = (
        <a className={buttonClasses} href={`#${scrollLink}`}>
          {linkText}
        </a>
      );
    } else {
      linkButton = (
        <a className={buttonClasses} href={link}>
          {linkText}
        </a>
      );
    }

    return (
      <figure className={blockClasses}>
        <span className="customCard-label">{label}</span>
        <div className="customCard-figure">
          {style === 'wide' && (
            <img className="customCard-image" src={largeImageURL} alt={imageAlt} />
          )}
          {style === 'standard' && (
            <img className="customCard-image" src={imageURL} alt={imageAlt} />
          )}
        </div>
        <figcaption className="customCard-content">
          <p>{content}</p>
          {linkButton}
        </figcaption>
      </figure>
    );
  },
};

const deprecated = [v1];

export default deprecated;
