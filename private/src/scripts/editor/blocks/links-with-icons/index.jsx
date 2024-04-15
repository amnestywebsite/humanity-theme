/**
 * Third-party
 */
import classnames from 'classnames';

/**
 * Module-specific
 */
import DisplayComponent from './DisplayComponent.jsx';
import './inner-block.jsx';

/**
 * WordPress
 */
const { InnerBlocks } = wp.blockEditor;
const { registerBlockStyle, registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/repeatable-block', {
  // translators: [admin]
  title: __('Links with Icons Group', 'amnesty'),
  // translators: [admin]
  description: __('Add a repeatable links-with-icons block', 'amnesty'),
  icon: 'images-alt',
  category: 'amnesty-core',
  supports: {
    className: true,
    defaultStylePicker: false,
  },

  attributes: {
    backgroundColor: {
      type: 'string',
    },
    orientation: {
      type: 'string',
      default: 'horizontal',
    },
    quantity: {
      type: 'number',
      default: 2,
    },
    hideLines: {
      type: 'boolean',
      default: false,
    },
    dividerIcon: {
      type: 'text',
      default: 'none',
    },
  },
  deprecated: [
    {
      attributes: {
        backgroundColor: {
          type: 'string',
        },
        orientation: {
          type: 'string',
          default: 'horizontal',
        },
        quantity: {
          type: 'number',
          default: 2,
        },
        hideLines: {
          type: 'boolean',
          default: false,
        },
      },
      save({ attributes, className }) {
        const { quantity, orientation = 'horizontal', backgroundColor, hideLines } = attributes;
        const classes = classnames(
          'linksWithIcons-group',
          `is-${orientation}`,
          `has-${quantity}-items`,
          {
            'has-background': !!backgroundColor,
            [`has-${backgroundColor}-background-color`]: !!backgroundColor,
            'has-no-lines': !!hideLines,
            className: !!className,
          },
        );

        return (
          <div className={classes}>
            <InnerBlocks.Content />
          </div>
        );
      },
    },
  ],
  edit: DisplayComponent,

  save: () => <InnerBlocks.Content />,
});

registerBlockStyle('amnesty-core/repeatable-block', {
  name: 'square',
  // translators: [admin]
  label: __('Square', 'amnesty'),
});
