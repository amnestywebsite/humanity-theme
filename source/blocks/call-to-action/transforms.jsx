const { createBlock } = '@wordpress/blocks';

const transforms = {
  from: [
    {
      type: 'block',
      isMultiBlock: true,
      blocks: ['core/paragraph'],
      transform: (attributes) =>
        attributes.map(({ content }) =>
          createBlock('amnesty-core/block-call-to-action', { content }),
        ),
    },
    {
      type: 'block',
      isMultiBlock: false,
      blocks: ['core/heading'],
      transform: ({ content }) =>
        createBlock('amnesty-core/block-call-to-action', { title: content }),
    },
  ],
};

export default transforms;
