import classnames from 'classnames';

const { isEqual, isObject } = lodash;
const { BlockAlignmentToolbar, BlockControls, RichText, URLInputButton } = wp.blockEditor;
const { Button, CheckboxControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

/**
 * Generate random string key for iterators
 */
const randId = () =>
  Math.random()
    .toString(36)
    .replace(/[^a-z]+/g, '')
    .substr(2, 10);

class DisplayComponent extends Component {
  state = {};

  /**
   * Save state to props when required
   *
   * @param {object} prevProps previous component props
   * @param {object} prevState previous component state
   */
  componentDidUpdate(prevProps, prevState) {
    if (isEqual(prevState, this.state)) {
      return;
    }

    Object.keys(this.state).forEach((key) => {
      if (!key.match(/^link-\d+$/)) {
        return;
      }

      const index = key.replace(/^link-(\d+)$/, '$1');
      this.updateItemProp(index, 'link', this.state[key]);
    });
  }

  /**
   * Create a new item
   *
   * @param {object} data any props to assign to new item
   */
  static newItem = (data = {}) => ({
    ...{
      key: randId(),
      text: '',
      link: '',
      blank: false,
    },
    ...data,
  });

  /**
   * Add a link item to the collection
   */
  addItem = (data = {}) => {
    const { attributes, setAttributes } = this.props;
    const { items } = attributes;

    setAttributes({ items: [...items, DisplayComponent.newItem(data)] });
  };

  /**
   * Update a link item property
   *
   * @param {number} index the current item in the collection
   * @param {string} prop the item prop to update
   * @param {mixed} value the item prop value to update
   */
  updateItemProp = (index, prop, value) => {
    const { attributes, setAttributes } = this.props;
    const { items } = attributes;

    if (!isObject(items[index])) {
      items[index] = DisplayComponent.newItem();
    }

    if (prop !== 'text' || value.indexOf('\n') === -1) {
      items[index][prop] = value;
      setAttributes({ items: [...items] });
      return;
    }

    const texts = value.split('\n');
    items[index][prop] = texts[0]; // eslint-disable-line
    setAttributes({ items: [...items] });

    texts.slice(1).forEach((text) => this.addItem({ text }));
  };

  /**
   * Update a link item's link property
   *
   * @param {number} index the current item in the collection
   * @param {string} value the item value to update
   */
  updateItemLink = (index, value) => {
    this.setState({ [`link-${index}`]: value });
  };

  /**
   * Remote a link item from the collection
   *
   * @param {number} index the current item in the collection
   */
  removeItem = (index) => {
    const { attributes, setAttributes } = this.props;
    const { items: oldItems } = attributes;

    const items = [...oldItems];
    items.splice(index, 1);

    setAttributes({ items });
  };

  /**
   * Render the component
   */
  render() {
    const { attributes, className, setAttributes } = this.props;
    const classes = classnames(className, {
      [`align${attributes.align}`]: attributes.align,
    });

    return (
      <Fragment>
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
                  onChange={(text) => this.updateItemProp(index, 'text', text)}
                  onRemove={() => this.removeItem(index)}
                  keepPlaceholderOnFocus={true}
                  multiline={false}
                  preserveWhiteSpace={true}
                  withoutInteractiveFormatting={true}
                />
                <URLInputButton
                  url={this.state[`link-${index}`]}
                  onChange={(link) => this.updateItemLink(index, link)}
                />
                <CheckboxControl
                  className="newtab"
                  // translators: [admin]
                  label={__('Open in new tab', 'amnesty')}
                  onChange={(blank) => this.updateItemProp(index, 'blank', blank)}
                  checked={item.blank}
                />
              </li>
            ))}
            <li>
              <Button
                label={/* translators: [admin] */ __('Add a link', 'amnesty')}
                onClick={() => this.addItem()}
              >
                {/* translators: [admin] */ __('Add a link', 'amnesty')}
              </Button>
            </li>
          </ul>
        </aside>
      </Fragment>
    );
  }
}

export default DisplayComponent;
