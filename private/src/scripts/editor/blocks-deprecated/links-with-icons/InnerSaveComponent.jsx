/**
 * Third-Party
 */
import classnames from 'classnames';

/**
 * WordPress
 */
const { RichText } = wp.blockEditor;
const { Fragment } = wp.element;
const { sprintf } = wp.i18n;

/**
 * Internal
 */
const sizeMap = {
  xsmall: 'logomark',
  small: 'lwi-block-sm',
  medium: 'lwi-block-md',
  large: 'lwi-block-lg',
  smallRetina: 'lwi-block-sm@2x',
  mediumRetina: 'lwi-block-md@2x',
  largeRetina: 'lwi-block-lg@2x',
};

const getImageTag = (attributes) => {
  const {
    iconSize = 'medium',
    uncredited = false,

    imageAlt,
    imageData,
  } = attributes;

  const getUrl = (size, retina = false) => {
    const key = retina ? sizeMap[`${size}Retina`] : sizeMap[size];
    const obj = imageData[key] || imageData.full || { url: '' };
    return encodeURI(obj.url);
  };

  const imgClasses = classnames('linksWithIcons-imageWrapper', {
    [`is-${iconSize}`]: iconSize !== 'medium',
    'is-uncredited': uncredited,
  });

  let srcset = false;
  if (imageData) {
    const x1 = getUrl(iconSize);
    const x2 = getUrl(iconSize, true);

    if (x1 && x2 && x1 !== x2) {
      srcset = sprintf('%1$s 1x, %2$s 2x', x1, x2);
    }
  }

  return (
    <div className={imgClasses}>
      <img className="linksWithIcons-image" src={getUrl(iconSize)} srcSet={srcset} alt={imageAlt} />
    </div>
  );
};

export function saveVersionOne({ attributes }) {
  const {
    buttonStyle = 'white',
    style = 'icon',
    imageLocation = 'middle',
    underlined = false,

    title,
    body,
    buttonText,
    buttonLink,

    bigTextCss,
    bigtext,
  } = attributes;

  const { hasButton = buttonText && buttonLink } = attributes;

  const txtClasses = classnames('linksWithIcons-bigtext', {
    'has-underline': underlined,
  });

  if (style === 'square') {
    if (!hasButton) {
      return (
        <div className="linksWithIcons">
          {imageLocation === 'top' && getImageTag(attributes)}
          <RichText.Content className="linksWithIcons-title" tagName="div" value={title} />
          {imageLocation !== 'top' && getImageTag(attributes)}
        </div>
      );
    }

    return (
      <a className="linksWithIcons" href={buttonLink}>
        {imageLocation === 'top' && getImageTag(attributes)}
        <RichText.Content className="linksWithIcons-title" tagName="div" value={title} />
        {imageLocation !== 'top' && getImageTag(attributes)}
        {hasButton && (
          <span
            dangerouslySetInnerHTML={{
              __html: document.body.classList.contains('rtl') ? '&larr;' : '&rarr;',
            }}
          />
        )}
      </a>
    );
  }

  return (
    <Fragment>
      <div className="linksWithIcons">
        {style.indexOf('icon') !== -1 && imageLocation === 'top' && getImageTag(attributes)}
        <RichText.Content className="linksWithIcons-title" tagName="div" value={title} />
        {style.indexOf('icon') !== -1 && imageLocation !== 'top' && getImageTag(attributes)}
        {style === 'text' && (
          <RichText.Content
            className={txtClasses}
            tagName="div"
            value={bigtext}
            style={bigTextCss}
          />
        )}
        <RichText.Content className="linksWithIcons-body" tagName="div" value={body} />
        {hasButton && (
          <a className={classnames('btn', `btn--${buttonStyle}`)} href={buttonLink}>
            {buttonText}
          </a>
        )}
      </div>
      <div className="linksWithIcons-spacer"></div>
    </Fragment>
  );
}

export default function save({ attributes }) {
  const {
    buttonStyle = 'white',
    style = 'icon',
    imageLocation = 'middle',
    underlined = false,

    title,
    body,
    buttonText,
    buttonLink,

    bigTextCss,
    bigtext,
  } = attributes;

  const { hasButton = buttonText && buttonLink } = attributes;

  const txtClasses = classnames('linksWithIcons-bigtext', {
    'has-underline': underlined,
  });

  if (style === 'square') {
    if (!hasButton) {
      return (
        <div className="linksWithIcons">
          {imageLocation === 'top' && getImageTag(attributes)}
          <RichText.Content className="linksWithIcons-title" tagName="div" value={title} />
          {imageLocation !== 'top' && getImageTag(attributes)}
        </div>
      );
    }

    return (
      <a className="linksWithIcons" href={buttonLink}>
        {imageLocation === 'top' && getImageTag(attributes)}
        {title && <RichText.Content className="linksWithIcons-title" tagName="div" value={title} />}
        {imageLocation !== 'top' && getImageTag(attributes)}
        {hasButton && (
          <span
            dangerouslySetInnerHTML={{
              __html: document.body.classList.contains('rtl') ? '&larr;' : '&rarr;',
            }}
          />
        )}
      </a>
    );
  }

  return (
    <Fragment>
      <div className="linksWithIcons">
        {style.indexOf('icon') !== -1 && imageLocation === 'top' && getImageTag(attributes)}
        {title && <RichText.Content className="linksWithIcons-title" tagName="div" value={title} />}
        {style.indexOf('icon') !== -1 && imageLocation !== 'top' && getImageTag(attributes)}
        {style === 'text' && (
          <RichText.Content
            className={txtClasses}
            tagName="div"
            value={bigtext}
            style={bigTextCss}
          />
        )}
        {body && <RichText.Content className="linksWithIcons-body" tagName="div" value={body} />}
        {hasButton && (
          <a className={classnames('btn', `btn--${buttonStyle}`)} href={buttonLink}>
            {buttonText}
          </a>
        )}
      </div>
      <div className="linksWithIcons-spacer"></div>
    </Fragment>
  );
}
