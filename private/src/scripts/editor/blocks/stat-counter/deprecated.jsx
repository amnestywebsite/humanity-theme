import classnames from 'classnames';

const blockAttributes = {
  attributes: {
    alignment: {
      type: 'string',
    },
    duration: {
      type: 'number',
      default: 2,
    },
    value: {
      type: 'string',
    },
  },
};

const v1 = {
  attributes: blockAttributes,
  save: ({ attributes, className }) => {
    const { alignment, duration, value } = attributes;

    const blockClasses = classnames(className, {
      [`align${alignment}`]: !!alignment,
    });

    return (
      <div className={blockClasses} data-duration={duration}>
        {value}
      </div>
    );
  },
}

const v2 = {
  attributes: blockAttributes,
  save: ({ attributes, className }) => {
    const { alignment, duration, value } = attributes;

    const blockClasses = classnames(className, {
      [`align${alignment}`]: !!alignment,
    });

    return (
      <div className={blockClasses} data-duration={duration} data-value={value}>
        {value}
      </div>
    );
  },
}

const deprecated = [v2, v1];

export default deprecated;
