import { assign } from 'lodash';
import { RichText } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import classnames from 'classnames';
import { saveVersionOne } from './InnerSaveComponent';

// Migrate old attributes to new ones
function migrateImageData(attributes) {
  return assign({}, attributes, {
    imageData: {
      full: {
        url: attributes.imageURL,
      },
      'lwi-block-lg': {
        url: attributes.imageURL,
      },
      'lwi-block-md': {
        url: attributes.imageURL,
      },
      'lwi-block-sm': {
        url: attributes.imageURL,
      },
    },
  });
}

const blockAttributes = {
  style: {
    type: 'string',
  },
  hasButton: {
    type: 'boolean',
  },
  bigTextCss: {
    type: 'object',
    default: {
      fontSize: 20,
    },
  },
  iconSize: {
    type: 'string',
    default: 'medium',
  },
  underlined: {
    type: 'boolean',
    default: false,
  },

  title: {
    type: 'string',
  },
  body: {
    type: 'string',
  },
  buttonText: {
    type: 'string',
  },
  buttonLink: {
    type: 'string',
  },

  imageID: {
    type: 'number',
  },
  imageAlt: {
    type: 'string',
  },
  imageURL: {
    type: 'string',
  },
  bigtext: {
    type: 'string',
  },
};

// Deprecate function with migrated attributes and save logic
const deprecated = [
  {
    supports: {
      className: false,
    },
    attributes: assign({}, blockAttributes, {
      uncredited: {
        type: 'boolean',
        default: false,
      },
      imageData: {
        type: 'object',
        default: {},
      },
      imageLocation: {
        type: 'string',
        default: 'middle',
      },
      buttonStyle: {
        type: 'string',
        default: 'white',
      },
    }),
    save: saveVersionOne,
  },
  {
    supports: {
      className: false,
    },
    attributes: blockAttributes,
    save({ attributes }) {
      const {
        style = 'icon',
        iconSize = 'medium',
        underlined = false,

        title,
        body,
        buttonText,
        buttonLink,

        bigTextCss,
        bigtext,

        imageAlt,
        imageURL,
      } = attributes;

      const { hasButton = buttonText && buttonLink } = attributes;

      const imgClasses = classnames('linksWithIcons-imageWrapper', {
        [`is-${iconSize}`]: iconSize !== 'medium',
      });

      const txtClasses = classnames('linksWithIcons-bigtext', {
        'has-underline': underlined,
      });

      return (
        <Fragment>
          <div className="linksWithIcons">
            <RichText.Content className="linksWithIcons-title" tagName="div" value={title} />
            {style === 'icon' && (
              <div className={imgClasses}>
                <RichText.Content
                  className="linksWithIcons-image"
                  tagName="img"
                  src={imageURL}
                  alt={imageAlt}
                />
              </div>
            )}
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
              <a className="btn btn--outline linksWithIcons-button" href={buttonLink}>
                {buttonText}
              </a>
            )}
          </div>
          <div className="linksWithIcons-spacer"></div>
        </Fragment>
      );
    },
  },
  {
    attributes: assign({}, blockAttributes, {
      uncredited: {
        type: 'boolean',
        default: false,
      },
    }),
    save({ attributes }) {
      const {
        style = 'icon',
        iconSize = 'medium',
        underlined = false,
        uncredited = false,

        title,
        body,
        buttonText,
        buttonLink,

        bigTextCss,
        bigtext,

        imageAlt,
        imageURL,
      } = attributes;

      const { hasButton = buttonText && buttonLink } = attributes;

      const imgClasses = classnames('linksWithIcons-imageWrapper', {
        [`is-${iconSize}`]: iconSize !== 'medium',
        'is-uncredited': uncredited,
      });

      const txtClasses = classnames('linksWithIcons-bigtext', {
        'has-underline': underlined,
      });

      return (
        <Fragment>
          <div className="linksWithIcons">
            <RichText.Content className="linksWithIcons-title" tagName="div" value={title} />
            {style === 'icon' && (
              <div className={imgClasses}>
                <img className="linksWithIcons-image" src={imageURL} alt={imageAlt} />
              </div>
            )}
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
              <a className="btn btn--white" href={buttonLink}>
                {buttonText}
              </a>
            )}
          </div>
          <div className="linksWithIcons-spacer"></div>
        </Fragment>
      );
    },
    migrate: migrateImageData,
  },
];

export default deprecated;
