import classnames from 'classnames';
import { RichText } from '@wordpress/block-editor';

import { uniqueId } from './utils';

const blockAttributes = {
  type: {
    type: 'string',
  },
  style: {
    type: 'string',
  },
  align: {
    type: 'string',
  },
  hasOverlay: {
    type: 'boolean',
  },
  parallax: {
    type: 'boolean',
  },
  identifier: {
    type: 'string',
    source: 'attribute',
    selector: '.imageBlock',
    attribute: 'class',
  },

  imageID: {
    type: 'integer',
  },
  imageURL: {
    type: 'string',
  },

  videoID: {
    type: 'integer',
  },
  videoURL: {
    type: 'string',
  },

  title: {
    type: 'string',
  },
  content: {
    type: 'string',
  },
  buttons: {
    type: 'array',
    default: [],
  },
  caption: {
    type: 'string',
  },
};

const v2 = {
  attributes: blockAttributes,
  save({ attributes }) {
    const {
      type = '',
      style = 'loose',
      hasOverlay = false,
      parallax = false,
      align = 'default',

      imageURL = '',
      videoURL = '',

      title,
      content,
      caption,
      buttons = [],
    } = attributes;

    let identifier;
    if (attributes.identifier) {
      // pull existing id from outer div instead of generating it.
      identifier = attributes.identifier
        .replace(/imageBlock\s+imageBlock-([0-9a-zA-Z]*?)\s+(?:has-parallax)?/, '$1')
        .trim();
    }

    if (!identifier) {
      identifier = uniqueId();
    }

    const classes = classnames('imageBlock', {
      [`imageBlock-${identifier}`]: parallax,
      'imageBlock--fixed': style === 'fixed',
      'has-video': type === 'video',
      'has-parallax': parallax,
    });

    const capClasses = classnames('imageBlock-caption', {
      [`align${align}`]: align !== 'default',
    });

    if (parallax) {
      const css = `.imageBlock-${identifier} .imageBlock-overlay{background-image: url('${encodeURI(
        imageURL,
      )}')}`;

      return (
        <>
          <div className={classes}>
            <style>{css}</style>
            {hasOverlay ? (
              <div className="imageBlock-overlay">
                <div className="imageBlock-title">{title}</div>
                <div className="imageBlock-content">
                  <RichText.Content tagName="p" value={content} />
                </div>
                <div className="imageBlock-buttonWrapper">
                  {buttons.map((button) => (
                    <a key={button.url} className="btn btn--white" href={button.url}>
                      {button.text}
                    </a>
                  ))}
                </div>
              </div>
            ) : (
              <div className="imageBlock-overlay"></div>
            )}
          </div>
          {caption && <div className={capClasses}>{caption}</div>}
        </>
      );
    }

    const block = (
      <>
        <div className={classes}>
          {!type && <img className="imageBlock-image" src={encodeURI(imageURL)} />}
          {type === 'video' && (
            <video className="imageBlock-video" autoPlay loop muted>
              <source src={encodeURI(videoURL)} />
            </video>
          )}
          {hasOverlay && (
            <div className="imageBlock-overlay">
              <div className="imageBlock-title">{title}</div>
              <div className="imageBlock-content">
                <RichText.Content tagName="p" value={content} />
              </div>
              <div className="imageBlock-buttonWrapper">
                {buttons.map((button) => (
                  <a key={encodeURI(button.url)} className="btn btn--white" href={button.url}>
                    {button.text}
                  </a>
                ))}
              </div>
            </div>
          )}
        </div>
        {caption && <div className={capClasses}>{caption}</div>}
      </>
    );

    if (align === 'default') {
      return block;
    }

    const wrapperClasses = classnames('imageBlock-wrapper', 'u-cf', {
      [`align${align}`]: align !== 'default',
    });

    return <div className={wrapperClasses}>{block}</div>;
  },
};

const v1 = {
  supports: {
    className: false,
  },
  attributes: blockAttributes,
  save({ attributes }) {
    const {
      type = '',
      style = 'loose',
      hasOverlay = false,
      parallax = false,
      align = 'default',

      imageURL = '',
      videoURL = '',

      title,
      content,
      caption,
      buttons = [],
    } = attributes;

    let identifier;
    if (attributes.identifier) {
      // pull existing id from outer div instead of generating it.
      identifier = attributes.identifier
        .replace(/imageBlock\s+imageBlock-([0-9a-zA-Z]*?)\s+(?:has-parallax)?/, '$1')
        .trim();
    }

    if (!identifier) {
      identifier = uniqueId();
    }

    const classes = classnames('imageBlock', {
      [`imageBlock-${identifier}`]: parallax,
      'imageBlock--fixed': style === 'fixed',
      'has-video': type === 'video',
      'has-parallax': parallax,
    });

    const capClasses = classnames('imageBlock-caption', {
      [`align${align}`]: align !== 'default',
    });

    if (parallax) {
      const css = `.imageBlock-${identifier} .imageBlock-overlay{background-image: url('${encodeURI(
        imageURL,
      )}')}`;

      return (
        <>
          <div className={classes}>
            <style>{css}</style>
            {hasOverlay ? (
              <div className="imageBlock-overlay">
                <div className="imageBlock-title">{title}</div>
                <div className="imageBlock-content">
                  <RichText.Content tagName="p" value={content} />
                </div>
                <div className="imageBlock-buttonWrapper">
                  {buttons.map((button) => (
                    <a
                      key={encodeURI(button.url)}
                      className="btn btn--white btn--ghost"
                      href={button.url}
                    >
                      {button.text}
                    </a>
                  ))}
                </div>
              </div>
            ) : (
              <div className="imageBlock-overlay"></div>
            )}
          </div>
          {caption && <div className={capClasses}>{caption}</div>}
        </>
      );
    }

    const block = (
      <>
        <div className={classes}>
          {!type && <img className="imageBlock-image" src={encodeURI(imageURL)} />}
          {type === 'video' && (
            <video className="imageBlock-video" autoPlay loop muted>
              <source src={encodeURI(videoURL)} />
            </video>
          )}
          {hasOverlay && (
            <div className="imageBlock-overlay">
              <div className="imageBlock-title">{title}</div>
              <div className="imageBlock-content">
                <RichText.Content tagName="p" value={content} />
              </div>
              <div className="imageBlock-buttonWrapper">
                {buttons.map((button) => (
                  <a
                    key={encodeURI(button.url)}
                    className="btn btn--white btn--ghost"
                    href={button.url}
                  >
                    {button.text}
                  </a>
                ))}
              </div>
            </div>
          )}
        </div>
        {caption && <div className={capClasses}>{caption}</div>}
      </>
    );

    if (align === 'default') {
      return block;
    }

    const wrapperClasses = classnames('imageBlock-wrapper', 'u-cf', {
      [`align${align}`]: align !== 'default',
    });

    return <div className={wrapperClasses}>{block}</div>;
  },
};

const deprecated = [v2, v1];

export default deprecated;
