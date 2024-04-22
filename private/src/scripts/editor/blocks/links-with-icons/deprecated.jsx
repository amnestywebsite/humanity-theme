import classnames from 'classnames';

const blockAttributes = {
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
}

const v1 = {
  attributes: blockAttributes,
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
};

const v2 = {
  attributes: blockAttributes,
  save({ attributes, className }) {
    const {
      quantity,
      orientation = 'horizontal',
      backgroundColor,
      hideLines,
      dividerIcon = 'none',
    } = attributes;
    const classes = classnames(
      'linksWithIcons-group',
      `is-${orientation}`,
      `has-${quantity}-items`,
      {
        className: !!className,
        'has-background': !!backgroundColor,
        'has-no-lines': !!hideLines,
        [`has-${backgroundColor}-background-color`]: !!backgroundColor,
        [`icon-${dividerIcon}`]: !!dividerIcon,
      },
    );

    return (
      <div className={classes}>
        <InnerBlocks.Content />
      </div>
    );
  },
};

const deprecated = [v2, v1];

export default deprecated;
