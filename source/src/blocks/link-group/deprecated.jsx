import classnames from 'classnames';

const icon = (
  <svg viewBox="0 0 32 32">
    <path d="M8 20c0 0 1.838-6 12-6v6l12-8-12-8v6c-8 0-12 4.99-12 10zM22 24h-18v-12h3.934c0.315-0.372 0.654-0.729 1.015-1.068 1.374-1.287 3.018-2.27 4.879-2.932h-13.827v20h26v-8.395l-4 2.667v1.728z" />
  </svg>
);

const v1 = {
  attributes: {
    align: {
      type: 'string',
      default: '',
    },
    title: {
      type: 'string',
      default: '',
    },
    items: {
      type: 'array',
      default: [],
    },
  },
  save({ attributes, className }) {
    const classes = classnames(className, {
      [`align${attributes.align}`]: attributes.align,
    });

    return (
      <aside className={classes}>
        <h2>{attributes.title}</h2>
        <ul>
          {attributes.items.map((item) => {
            if (!item.link || !item.text) {
              return null;
            }

            if (!item.blank) {
              return (
                <li key={item.key}>
                  <a href={item.link}>{item.text}</a>
                </li>
              );
            }

            return (
              <li key={item.key}>
                <a
                  className="is-external"
                  href={item.link}
                  rel="noopener noreferrer"
                  target="_blank"
                >
                  {icon}
                  {item.text}
                </a>
              </li>
            );
          })}
        </ul>
      </aside>
    );
  },
};

export default [v1];
