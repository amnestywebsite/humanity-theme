import classnames from 'classnames';
import { getSaveCss } from '../background-media/utils';

const { assign, isObject, isString } = lodash;
const { InnerBlocks } = wp.editor;
const { Fragment } = wp.element;

const v4 = {
  supports: {
    alignWide: false,
    className: false,
    customClassName: false,
    defaultStylePicker: false,
    inserter: false,
    reusable: false,
  },
  attributes: {
    uniqId: {
      type: 'string',
      default: '',
      source: 'attribute',
      selector: 'div',
      attribute: 'id',
    },
    horizontalAlignment: {
      type: 'string',
    },
    verticalAlignment: {
      type: 'string',
    },
    image: {
      type: 'object',
      default: '',
    },
    background: {
      type: 'string',
    },
    opacity: {
      type: 'number',
      default: 1,
    },
    focalPoint: {
      type: 'object',
      default: {
        x: 0.5,
        y: 0.5,
      },
    },
  },
  migrate(attributes, innerBlocks) {
    if (isObject(attributes.image)) {
      return [assign({}, attributes, { image: parseInt(attributes.image.id, 10) }), innerBlocks];
    }

    if (isString(attributes.image) && attributes.image) {
      return [assign({}, attributes, { image: parseInt(attributes.image, 10) }), innerBlocks];
    }

    return [attributes, innerBlocks];
  },
  save({ attributes }) {
    const { background, horizontalAlignment, uniqId, verticalAlignment } = attributes;

    const containerClasses = classnames('text-media--itemContainer', {
      [`align${horizontalAlignment}`]: !!horizontalAlignment,
      [`is-vertically-aligned-${verticalAlignment}`]: !!verticalAlignment,
      [`has-${background}-background-color`]: !!background,
    });

    const css = getSaveCss(attributes);

    return (
      <Fragment>
        {css && <style>{css}</style>}
        <div id={uniqId} className={containerClasses}>
          <InnerBlocks.Content />
        </div>
      </Fragment>
    );
  },
};

const v3 = {
  supports: {
    alignWide: false,
    className: false,
    customClassName: false,
    defaultStylePicker: false,
    inserter: false,
    reusable: false,
  },
  attributes: {
    uniqId: {
      type: 'string',
      default: '',
      source: 'attribute',
      selector: 'div',
      attribute: 'id',
    },
    horizontalAlignment: {
      type: 'string',
    },
    verticalAlignment: {
      type: 'string',
    },
    image: {
      type: 'object',
      default: '',
    },
    background: {
      type: 'string',
    },
    opacity: {
      type: 'number',
      default: 1,
    },
    focalPoint: {
      type: 'object',
      default: {
        x: 0.5,
        y: 0.5,
      },
    },
  },
  save({ attributes }) {
    const { background, horizontalAlignment, uniqId, verticalAlignment, image } = attributes;

    const containerClasses = classnames('text-media--itemContainer', {
      [`align${horizontalAlignment}`]: !!horizontalAlignment,
      [`is-vertically-aligned-${verticalAlignment}`]: !!verticalAlignment,
      [`has-${background}-background-color`]: !!background,
    });

    const css = getSaveCss(attributes);

    return (
      <Fragment>
        {css && <style>{css}</style>}
        <div id={uniqId} className={containerClasses}>
          <InnerBlocks.Content />
          {image && <div className="imageBlock-image"></div>}
        </div>
      </Fragment>
    );
  },
};

const v2 = {
  attributes: {
    uniqId: {
      type: 'string',
      default: '',
      source: 'attribute',
      selector: 'div',
      attribute: 'id',
    },
    horizontalAlignment: {
      type: 'string',
    },
    verticalAlignment: {
      type: 'string',
    },
    image: {
      type: 'object',
      default: '',
    },
    background: {
      type: 'string',
    },
    opacity: {
      type: 'number',
      default: 1,
    },
    focalPoint: {
      type: 'object',
      default: {
        x: 0.5,
        y: 0.5,
      },
    },
  },
  save({ attributes }) {
    const { background, horizontalAlignment, uniqId, verticalAlignment, image } = attributes;

    const containerClasses = classnames('text-media--itemContainer', {
      [`align${horizontalAlignment}`]: !!horizontalAlignment,
      [`is-vertically-aligned-${verticalAlignment}`]: !!verticalAlignment,
      [`has-${background}-background-color`]: !!background,
    });

    const css = getSaveCss(attributes);

    return (
      <Fragment>
        {css && <style>{css}</style>}
        <div id={uniqId} className={containerClasses}>
          <InnerBlocks.Content />
          {image && (
            <div className="imageBlock-image">
              <span className="image-caption">{image.description}</span>
            </div>
          )}
        </div>
      </Fragment>
    );
  },
};

const v1 = {
  attributes: {
    uniqId: {
      type: 'string',
      default: '',
      source: 'attribute',
      selector: 'div',
      attribute: 'id',
    },
    horizontalAlignment: {
      type: 'string',
    },
    verticalAlignment: {
      type: 'string',
    },
    image: {
      type: 'object',
      default: '',
    },
    background: {
      type: 'string',
    },
    opacity: {
      type: 'number',
      default: 1,
    },
    focalPoint: {
      type: 'object',
      default: {
        x: 0.5,
        y: 0.5,
      },
    },
  },
  save({ attributes }) {
    const { background, horizontalAlignment, uniqId, verticalAlignment } = attributes;

    const containerClasses = classnames('text-media--itemContainer', {
      [`align${horizontalAlignment}`]: !!horizontalAlignment,
      [`is-vertically-aligned-${verticalAlignment}`]: !!verticalAlignment,
      [`has-${background}-background-color`]: !!background,
    });

    const css = getSaveCss(attributes);

    return (
      <Fragment>
        {css && <style>{css}</style>}
        <div id={uniqId} className={containerClasses}>
          <InnerBlocks.Content />
        </div>
      </Fragment>
    );
  },
};

const deprecated = [v4, v3, v2, v1];

export default deprecated;
