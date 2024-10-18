import classnames from 'classnames';

import { assign } from 'lodash';
import { InnerBlocks } from '@wordpress/block-editor';

const v3 = {
  attributes: {
    background: {
      type: 'string',
    },
    padding: {
      type: 'string',
    },
    sectionId: {
      type: 'string',
    },
    sectionName: {
      type: 'string',
    },
    backgroundImage: {
      type: 'string',
      default: '',
    },
    backgroundImageHeight: {
      type: 'number',
      default: 0,
    },
    backgroundImageOrigin: {
      type: 'string',
      default: '',
    },
    enableBackgroundGradient: {
      type: 'boolean',
      default: false,
    },
    minHeight: {
      type: 'number',
      default: 0,
    },
    textColour: {
      type: 'string',
      default: 'black',
    },
    backgroundImageId: {
      type: 'number',
      default: 0,
    },
    hideImageCaption: {
      type: 'boolean',
      default: true,
    },
    hideImageCopyright: {
      type: 'boolean',
      default: false,
    },
  },
};

const v2 = {
  attributes: {
    background: {
      type: 'string',
    },
    padding: {
      type: 'string',
    },
    sectionId: {
      type: 'string',
    },
    sectionName: {
      type: 'string',
    },
    backgroundImage: {
      type: 'string',
      default: '',
    },
    backgroundImageHeight: {
      type: 'number',
      default: 0,
    },
    backgroundImageOrigin: {
      type: 'string',
      default: '',
    },
    enableBackgroundGradient: {
      type: 'boolean',
      default: false,
    },
    minHeight: {
      type: 'number',
      default: 0,
    },
    textColour: {
      type: 'string',
      default: 'black',
    },
  },
  save: assign(
    ({ attributes }) => {
      const { minHeight, backgroundImage, backgroundImageHeight } = attributes;

      const styles = (h) => {
        if (!backgroundImage) {
          return {};
        }

        if (h > 0) {
          return {
            'background-image': `url(${backgroundImage})`,
            minHeight: `${minHeight}vw`,
            maxHeight: `${backgroundImageHeight}px`,
          };
        }

        return {
          'background-image': `url(${backgroundImage})`,
          height: 'auto',
        };
      };

      const classes = classnames('section', {
        'section--tinted': attributes.background === 'grey',
        [`section--${attributes.padding}`]: !!attributes.padding,
        'section--textWhite': attributes.textColour === 'white',
        'section--has-bg-image': backgroundImage,
        'section--has-bg-gradient': !!attributes.enableBackgroundGradient,
        [`section--bgOrigin-${attributes.backgroundImageOrigin}`]:
          !!attributes.backgroundImageOrigin,
      });

      return (
        <section className={classes} style={styles(minHeight)}>
          <div id={attributes.sectionId} className="container">
            <InnerBlocks.Content />
          </div>
        </section>
      );
    },
    { displayName: 'SectionBlockSave' },
  ),
};

const v1 = {
  attributes: {
    background: {
      type: 'string',
    },
    padding: {
      type: 'string',
    },
  },
  save: assign(
    ({ attributes }) => (
      <section
        className={classnames({
          section: true,
          'section--tinted': attributes.background === 'grey',
          [`section--${attributes.padding}`]: !!attributes.padding,
        })}
      >
        <div className="container">
          <InnerBlocks.Content />
        </div>
      </section>
    ),
    { displayName: 'SectionBlockSave' },
  ),
};

const deprecated = [v3, v2, v1];

export default deprecated;
