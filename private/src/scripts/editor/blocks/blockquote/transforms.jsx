import { versionCompare } from '../../utils';

const { createBlock } = wp.blocks;

/**
 * backwards-compatible getPhrasingContentSchema
 */
const getPhrasingContentSchema = () => {
  if (versionCompare(window.WPVersion, '5.6') < 0) {
    return wp.blocks.getPhrasingContentSchema();
  }

  return wp.dom.getPhrasingContentSchema();
};

const transforms = {
  from: [
    {
      type: 'block',
      isMultiBlock: true,
      blocks: ['core/heading'],
      transform: (attributes) =>
        attributes.map(({ content }) => createBlock('amnesty-core/quote', { content })),
    },
    {
      type: 'block',
      isMultiBlock: true,
      blocks: ['core/paragraph'],
      transform: (attributes) =>
        attributes.map(({ content }) => createBlock('amnesty-core/quote', { content })),
    },
    {
      type: 'block',
      blocks: ['core/pullquote'],
      transform: ({ value, citation }) =>
        createBlock('amnesty-core/quote', {
          content: value,
          citation,
        }),
    },
    {
      type: 'pattern',
      regExp: /^>\s/,
      transform: ({ content }) =>
        createBlock('amnesty-core/quote', {
          content: `<p>${content}</p>`,
        }),
    },
    {
      type: 'raw',
      selector: 'blockquote',
      schema: {
        blockquote: {
          children: {
            p: {
              children: getPhrasingContentSchema(),
            },
          },
        },
      },
      transform: (node) => {
        const content = node.querySelector('p')?.innerHTML || '';
        const citation = node.querySelector('cite')?.innerHTML || '';
        return createBlock('amnesty-core/quote', { content, citation });
      },
    },
  ],
  to: [
    {
      type: 'block',
      blocks: ['core/heading'],
      transform: ({ content, citation, ...attrs }) => {
        if (content === '<p></p>') {
          return createBlock('core/heading', {
            ...attrs,
            content: citation,
          });
        }

        return [content, citation].filter(Boolean).map((item) =>
          createBlock('core/heading', {
            ...attrs,
            content: item,
          }),
        );
      },
    },
    {
      type: 'block',
      blocks: ['core/paragraph'],
      transform: ({ content, citation, ...attrs }) => {
        if (content === '<p></p>') {
          return createBlock('core/paragraph', {
            ...attrs,
            content: citation,
          });
        }

        return [content, citation].filter(Boolean).map((item) =>
          createBlock('core/paragraph', {
            ...attrs,
            content: item,
          }),
        );
      },
    },
    {
      type: 'block',
      blocks: ['core/pullquote'],
      transform: ({ content, citation }) =>
        createBlock('core/pullquote', {
          value: content,
          citation,
        }),
    },
  ],
};

export default transforms;
