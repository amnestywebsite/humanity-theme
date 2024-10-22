/* eslint-disable camelcase */
import React from 'react';
import LinkList from '../0components/editable/LinkList.jsx';
import GridItem from '../0components/editable/GridItem.jsx';
import Appender from '../0components/Appender.jsx';

const DisplayCustom = ({ custom = [], setAttributes, style, prefix, showAuthor, showPostDate }) => {
  // Default object structure for each custom item
  const defaultObject = {
    excerpt: '',
    featured_image_id: '',
    featured_image: '',
    tag: '',
    tagLink: '',
    title: '',
    titleLink: '',
    date: null,
    authorName: '',
  };

  // Function to update media attributes for a specific item
  const createUpdateMediaAttribute = (index) => ({ featured_image_id, featured_image }) => {
    if (custom[index]) {
      // Update existing item
      setAttributes({
        custom: custom.map((item, i) =>
          index === i
            ? { ...item, featured_image, featured_image_id }
            : item
        ),
      });
    } else {
      // Add new item with default values
      setAttributes({
        custom: [
          ...custom,
          { ...defaultObject, featured_image, featured_image_id },
        ],
      });
    }
  };

  // Function to create an updater for a specific key in a specific item
  const createUpdateAttribute = (index) => (key) => (value) => {
    if (custom[index]) {
      // Update existing item
      setAttributes({
        custom: custom.map((item, i) =>
          index === i
            ? { ...item, [key]: value }
            : item
        ),
      });
    } else {
      // Add new item with default values
      setAttributes({
        custom: [
          ...custom,
          { ...defaultObject, [key]: value },
        ],
      });
    }
  };

  // Function to add a new item
  const addItem = () => {
    setAttributes({
      custom: [...custom, { ...defaultObject }],
    });
  };

  // Function to remove an item at a specific index
  const createRemoveItem = (index) => () => {
    setAttributes({
      custom: custom.filter((_, i) => i !== index),
    });
  };

  // If style is 'petition', return null
  if (style === 'petition') {
    return null;
  }

  // Ensure there's at least one item in custom
  let itemsToRender = custom.length ? custom : [{ ...defaultObject }];

  // Conditionally render the Appender if there are less than 8 items
  const appender = itemsToRender.length < 8 ? <Appender onClick={addItem} /> : null;

  // Render based on the selected style
  if (style !== 'grid') {
    return (
      <div>
        <ul className="linkList">
          {itemsToRender.map((item, index) => (
            <LinkList
              key={`${prefix}-${index}`}
              {...item}
              showAuthor={showAuthor}
              showPostDate={showPostDate}
              createUpdate={createUpdateAttribute(index)}
              createRemove={createRemoveItem(index)}
            />
          ))}
        </ul>
        {appender}
      </div>
    );
  }

  // For grid style, check the number of items and render accordingly
  const gridClass = [1, 2, 3, 5, 6, 7].includes(itemsToRender.length) ? `grid-${itemsToRender.length}` : 'grid-many';

  return (
    <div>
      <div className={`grid ${gridClass}`}>
        {itemsToRender.map((item, index) => (
          <GridItem
            key={`${prefix}-${index}`}
            {...item}
            createUpdate={createUpdateAttribute(index)}
            createRemove={createRemoveItem(index)}
            updateMedia={createUpdateMediaAttribute(index)}
          />
        ))}
      </div>
      {appender}
    </div>
  );
};

export default DisplayCustom;
