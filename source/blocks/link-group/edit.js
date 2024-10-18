import classnames from 'classnames';

import { isObject } from 'lodash';
import { BlockAlignmentToolbar, BlockControls, RichText, URLInputButton } from '@wordpress/block-editor';
import { Button, CheckboxControl } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Generate random string key for iterators
 */
const randId = () =>
  Math.random()
    .toString(36)
    .replace(/[^a-z]+/g, '')
    .substr(2, 10);

const newItem = (data = {}) => ({
  ...{
    key: randId(),
    text: '',
    link: '',
    blank: false,
  },
  ...data,
});

const edit = ({ attributes, setAttributes }) => {
  const [links, setLinks] = useState({});

  useEffect(() => {
    Object.keys(links).forEach((key) => {
      if (!key.match(/^link-\d+$/)) {
        return;
      }

      const index = key.replace(/^link-(\d+)$/, '$1');
      updateItemProp(index, 'link', links[key]);
    });
  }, [links]);

  /**
   * Add a link item to the collection
   */
  const addItem = (data = {}) =>
    setAttributes({ items: [...items, newItem(data)] });

  /**
   * Remote a link item from the collection
   *
   * @param {number} index the current item in the collection
   */
  const removeItem = (index) => {
    const items = [...attributes.items];
    items.splice(index, 1);
    setAttributes({ items });
  };

  /**
   * Update a link item property
   *
   * @param {number} index the current item in the collection
   * @param {string} prop the item prop to update
   * @param {mixed} value the item prop value to update
   */
  const updateItemProp = (index, prop, value) => {
    const { items } = attributes;

    if (!isObject(items[index])) {
      items[index] = newItem();
    }

    if (prop !== 'text' || value.indexOf('\n') === -1) {
      items[index][prop] = value;
      setAttributes({ items: [...items] });
      return;
    }

    const texts = value.split('\n');
    items[index][prop] = texts[0]; // eslint-disable-line
    setAttributes({ items: [...items] });

    texts.slice(1).forEach((text) => addItem({ text }));
  };

  /**
   * Update a link item's link property
   *
   * @param {number} index the current item in the collection
   * @param {string} value the item value to update
   */
  const updateItemLink = (index, value) => {
    setLinks({ ...links, [`link-${index}`]: value });
  };

  const classes = classnames(className, {
    [`align${attributes.align}`]: attributes.align,
  });

  return (
    <>
      <BlockControls>
        <BlockAlignmentToolbar
          value={attributes.align}
          onChange={(align) => setAttributes({ align })}
        />
      </BlockControls>
      <aside className={classes}>
        <RichText
          tagName="h2"
          format="string"
          // translators: [admin]
          placeholder={__('Further Reading', 'amnesty')}
          value={attributes.title}
          onChange={(title) => setAttributes({ title })}
          keepPlaceholderOnFocus={true}
          multiline={false}
          withoutInteractiveFormatting={true}
        />
        <ul>
          {attributes.items.map((item, index) => (
            <li key={item.key}>
              <RichText
                tagName="span"
                format="string"
                // translators: [admin]
                placeholder={__('Type link', 'amnesty')}
                value={item.text}
                onChange={(text) => updateItemProp(index, 'text', text)}
                onRemove={() => removeItem(index)}
                keepPlaceholderOnFocus={true}
                multiline={false}
                preserveWhiteSpace={true}
                withoutInteractiveFormatting={true}
              />
              <URLInputButton
                url={links[`link-${index}`]}
                onChange={(link) => updateItemLink(index, link)}
              />
              <CheckboxControl
                className="newtab"
                // translators: [admin]
                label={__('Open in new tab', 'amnesty')}
                onChange={(blank) => updateItemProp(index, 'blank', blank)}
                checked={item.blank}
              />
            </li>
          ))}
          <li>
            <Button
              label={/* translators: [admin] */ __('Add a link', 'amnesty')}
              onClick={() => addItem()}
            >
              {/* translators: [admin] */ __('Add a link', 'amnesty')}
            </Button>
          </li>
        </ul>
      </aside>
    </>
  );
};

export default edit;
