import classnames from 'classnames';

const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;
const { Component, Fragment } = wp.element;
const { compose } = wp.compose;
const { withSelect } = wp.data;
const { __ } = wp.i18n;

class DisplayComponent extends Component {
  static colours = [
    // translators: [admin]
    { label: __('White', 'amnesty'), value: '' },
    // translators: [admin]
    { label: __('Grey', 'amnesty'), value: 'dark' },
  ];

  state = {
    loadingMenu: false,
    errorMenu: false,
    loadingList: false,
    errorList: false,
    // translators: [admin]
    list: [{ label: __('Select a menu…', 'amnesty'), value: '' }],
    menus: {},
  };

  componentDidMount() {
    if (this.props.attributes.menuId) {
      this.fetchMenu();
    }

    if (this.props.attributes.type === 'standard-menu') {
      this.fetchList();
    }
  }

  componentDidUpdate(prevProps) {
    if (prevProps.attributes.type !== this.props.attributes.type) {
      if (this.props.attributes.type === 'standard-menu') {
        this.fetchList();
      }
    }

    if (prevProps.attributes.menuId !== this.props.attributes.menuId) {
      this.fetchMenu();
    }
  }

  fetchMenu() {
    const { menuId } = this.props.attributes;
    if (this.state.menus[menuId]) {
      return;
    }

    this.setState({ loadingMenu: true });

    wp.apiRequest({
      path: `/amnesty/v1/menu/${menuId}`,
    }).then((response) => {
      this.setState({
        loadingMenu: false,
        menus: {
          ...this.state.menus,
          [menuId]: response,
        },
      });
    });
  }

  fetchList() {
    this.setState({ loadingList: true });

    wp.apiRequest({
      path: '/amnesty/v1/menu',
    }).then((response) => {
      this.setState({
        loadingList: false,
        list: [
          ...this.state.list,
          ...response.map((resp) => ({
            label: resp.name,
            value: resp.term_id,
          })),
        ],
      });
    });
  }

  renderStandardMenu() {
    const { attributes } = this.props;
    const { color, menuId } = attributes;
    const { loadingMenu, menus = [] } = this.state;

    const containerClasses = classnames('postlist-categoriesContainer', {
      [`section--${color}`]: !!color,
    });

    return (
      <Fragment>
        {!menuId && (
          <p>{/* translators: [admin] */ __('Select a menu in the sidebar', 'amnesty')}</p>
        )}
        {menuId && loadingMenu && (
          <p>{/* translators: [admin] */ __('Loading Menu…', 'amnesty')}</p>
        )}
        {menuId && !loadingMenu && this.state.menus[menuId] && (
          <div className={containerClasses}>
            <ul
              className="postlist-categories"
              dangerouslySetInnerHTML={{ __html: menus[menuId].rendered }}
            />
          </div>
        )}
      </Fragment>
    );
  }

  renderInPageMenu() {
    const { attributes, sectionAttributes } = this.props;
    const { color } = attributes;

    return (
      <Fragment>
        <div
          className={classnames('postlist-categoriesContainer', {
            [`section--${color}`]: !!color,
          })}
        >
          <ul className="postlist-categories">
            {sectionAttributes.map((value, index) => (
              <li key={index}>
                <a className="btn btn--white" href={`#${value.sectionId}`}>
                  {value.sectionName}
                </a>
              </li>
            ))}
          </ul>
        </div>
      </Fragment>
    );
  }

  renderCustomMenu() {
    const { attributes } = this.props;
    const { color } = attributes;

    return (
      <Fragment>
        <div
          className={classnames('postlist-categoriesContainer', {
            [`section--${color}`]: !!color,
          })}
        >
          <ul className="postlist-categories">
            {attributes?.items?.map(({ id, label }) => (
              <li key={id}>
                <a className="btn btn--white" href={`#${id}`}>
                  {label}
                </a>
              </li>
            ))}
          </ul>
        </div>
      </Fragment>
    );
  }

  render() {
    const { attributes, setAttributes } = this.props;

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
            {this.state.loadingList && (
              <p>{/* translators: [admin] */ __('Loading Menus…', 'amnesty')}</p>
            )}
            <SelectControl
              // translators: [admin]
              label={__('Menu', 'amnesty')}
              // translators: [admin]
              help={__('Which type of menu you would like to add to the page', 'amnesty')}
              value={attributes.type}
              options={[
                // translators: [admin]
                { label: __('Standard Menu', 'amnesty'), value: 'standard-menu' },
                // translators: [admin]
                { label: __('In-page Menu', 'amnesty'), value: 'inpage-menu' },
                // translators: [admin]
                { label: __('Custom Menu', 'amnesty'), value: 'custom-menu' },
              ]}
              onChange={(option) => setAttributes({ type: option })}
            />
            {attributes.type === 'standard-menu' && (
              <SelectControl
                // translators: [admin]
                label={__('Menu', 'amnesty')}
                options={this.state.list}
                disabled={this.state.loadingList}
                value={attributes.menuId}
                onChange={(menuId) => setAttributes({ menuId: parseInt(menuId, 10) })}
              />
            )}
            <SelectControl
              // translators: [admin]
              label={__('Background Colour', 'amnesty')}
              options={DisplayComponent.colours}
              value={attributes.color}
              onChange={(color) => setAttributes({ color })}
            />
          </PanelBody>
        </InspectorControls>
        {attributes.type === 'standard-menu' && this.renderStandardMenu()}
        {attributes.type === 'inpage-menu' && this.renderInPageMenu()}
        {attributes.type === 'custom-menu' && this.renderCustomMenu()}
      </Fragment>
    );
  }
}

const applyWithSelect = withSelect((select) => {
  const allBlocks = select('core/block-editor').getBlocks();
  const sectionBlocksWithIds = allBlocks.filter((block) => block.attributes.sectionId);
  const sectionAttributes = sectionBlocksWithIds.map((a) => a.attributes);

  return {
    allBlocks,
    sectionBlocksWithIds,
    sectionAttributes,
  };
});

export default compose(applyWithSelect)(DisplayComponent);
