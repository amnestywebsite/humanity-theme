/* eslint-disable camelcase */
import LinkList from './editable/LinkList.jsx';
import GridItem from './editable/GridItem.jsx';

const { BlockIcon } = wp.blockEditor;
const { Component } = wp.element;
const { __ } = wp.i18n;

class DisplayCustom extends Component {
  static defaultObject = {
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

  // eslint-disable-next-line camelcase
  createUpdateMediaAttribute =
    (index) =>
    ({ featured_image_id, featured_image }) => {
      if (this.props.custom[index]) {
        return this.props.setAttributes({
          custom: [
            ...this.props.custom.map((item, i) =>
              index === i
                ? {
                    ...item,
                    featured_image,
                    featured_image_id,
                  }
                : item,
            ),
          ],
        });
      }

      return this.props.setAttributes({
        custom: [
          ...this.props.custom,
          {
            ...DisplayCustom.defaultObject,
            featured_image,
            featured_image_id,
          },
        ],
      });
    };

  /**
   * Creates a higher order function that takes the current item index
   * from there the returned function takes a parameter of key,
   * this could be title or any other key from the object
   * the final function takes a parameter of value, this changes
   * the value of the desired key and in the desired item
   * @param index
   * @returns {function(*=): Function}
   */
  createUpdateAttribute = (index) => (key) => (value) => {
    // If the index already exists just update the value for that specific key
    // This should always be the case however there was an occasion where
    // the default object wasnt created so this is a safe guard
    if (this.props.custom[index]) {
      return this.props.setAttributes({
        custom: [
          ...this.props.custom.map((item, i) =>
            index === i
              ? {
                  ...item,
                  [key]: value,
                }
              : item,
          ),
        ],
      });
    }

    return this.props.setAttributes({
      custom: [
        ...this.props.custom,
        {
          ...DisplayCustom.defaultObject,
          [key]: value,
        },
      ],
    });
  };

  addItem = () =>
    this.props.setAttributes({
      custom: [...this.props.custom, { ...DisplayCustom.defaultObject }],
    });

  createRemoveItem = (index) => () =>
    this.props.setAttributes({
      custom: [...this.props.custom.filter((item, i) => i !== index)],
    });

  render() {
    let { custom = [] } = this.props;
    const { style, prefix } = this.props;

    if (style === 'petition') {
      return null;
    }

    if (!custom.length) {
      custom = [{ ...DisplayCustom.defaultObject }];
    }

    let appender = null;
    if (custom.length < 8) {
      appender = (
        <button onClick={this.addItem} className="add-more-button">
          <BlockIcon icon="plus-alt" />
          <span>{/* translators: [admin] */ __('Add another item', 'amnesty')}</span>
        </button>
      );
    }

    if (style !== 'grid') {
      return (
        <div>
          <ul className="linkList">
            {custom.map((item, index) => (
              <LinkList
                key={`${prefix}-${index}`}
                {...item}
                showAuthor={this.props.showAuthor}
                showPostDate={this.props.showPostDate}
                createUpdate={this.createUpdateAttribute(index)}
                createRemove={this.createRemoveItem(index)}
              />
            ))}
          </ul>
          {appender}
        </div>
      );
    }

    // style === 'grid'
    if ([1, 2, 3, 5, 6, 7].indexOf(custom.length) > -1) {
      return (
        <div className={`grid grid-${custom.length}`}>
          {custom.map((item, index) => (
            <GridItem
              key={`${prefix}-${index}`}
              {...item}
              createUpdate={this.createUpdateAttribute(index)}
              createRemove={this.createRemoveItem(index)}
              updateMedia={this.createUpdateMediaAttribute(index)}
            />
          ))}
          {appender}
        </div>
      );
    }

    return (
      <div className={`grid grid-many`}>
        {custom.map((item, index) => (
          <GridItem
            key={`${prefix}-${index}`}
            {...item}
            createUpdate={this.createUpdateAttribute(index)}
            createRemove={this.createRemoveItem(index)}
            updateMedia={this.createUpdateMediaAttribute(index)}
          />
        ))}
        {appender}
      </div>
    );
  }
}

export default DisplayCustom;
