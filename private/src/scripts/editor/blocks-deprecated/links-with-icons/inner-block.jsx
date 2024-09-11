/**
 * Third-Party
 */
import classnames from 'classnames';

/**
 * Module-specific
 */
import BlockEdit from './InnerDisplayComponent.jsx';
import BlockSave, { saveVersionOne } from './InnerSaveComponent.jsx';

/**
 * WordPress
 */
const { assign } = lodash;
const { RichText } = wp.blockEditor;
const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const { __ } = wp.i18n;

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

registerBlockType('amnesty-core/links-with-icons', {
  // translators: [admin]
  title: __('Links with Icons', 'amnesty'),
  // translators: [admin]
  description: __('Add a links-with-icons block', 'amnesty'),
  icon: 'plus',
  category: 'layout',
  parent: ['amnesty-core/repeatable-block'],
  supports: {
    className: false,
    defaultStylePicker: false,
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

  deprecated: [
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
      migrate(attributes) {
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
      },
    },
  ],

  edit: BlockEdit,

  save: BlockSave,
});
