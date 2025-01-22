const v1 = {
  attributes: {
    title: {
      type: 'string',
    },
    content: {
      type: 'string',
    },
  },
  save: ({ attributes }) => (
    <li className="factBlock-item">
      <h3 className="factBlock-itemTitle">{attributes.title}</h3>
      <p className="factBlock-itemContent">{attributes.content}</p>
    </li>
  ),
};

const deprecated = [v1];

export default deprecated;
