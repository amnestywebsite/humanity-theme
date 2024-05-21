import classnames from 'classnames';

const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;
const { useSelect } = wp.data;
const { useEffect, useRef, useState } = wp.element;
const { compose } = wp.compose;
const { withSelect } = wp.data;
const { __ } = wp.i18n;

const colours = [
  // translators: [admin]
  { label: __('White', 'amnesty'), value: '' },
  // translators: [admin]
  { label: __('Grey', 'amnesty'), value: 'dark' },
];

const options = [
  // translators: [admin]
  { label: __('Standard Menu', 'amnesty'), value: 'standard-menu' },
  // translators: [admin]
  { label: __('In-page Menu', 'amnesty'), value: 'inpage-menu' },
];

const fetchMenu = (menus, menuId, setLoadingMenu, setMenus) => {
  if (menus[menuId]) {
    return;
  }

  setLoadingMenu(true);

  wp.apiRequest({ path: `/amnesty/v1/menu/${menuId}` })
    .then((response) => {
      setLoadingMenu(false);
      setMenus({ ...menus, [menuId]: response });
    });
};

const fetchList = (list, setList, setLoadingList) => {
  setLoadingList(true);

  wp.apiRequest({ path: '/amnesty/v1/menu' })
    .then((response) => response.map((r) => ({ label: r.name, value: r.term_id })))
    .then((response) => {
      setLoadingList(false);
      setList([...list, ...response ]);
    });
};

const StandardMenu = ({ attributes, className, loadingMenu, menus }) => {
  if (!attributes.menuId) {
    // translators: [admin]
    return <p>{__('Select a menu in the sidebar', 'amnesty')}</p>;
  }

  if (loadingMenu) {
    // translators: [admin]
    return <p>{__('Loading Menu…', 'amnesty')}</p>;
  }

  if (!menus[menuId]) {
    return null;
  }

  return (
    <div className={className}>
      <ul
        className="postlist-categories"
        dangerouslySetInnerHTML={{ __html: menus[menuId].rendered }}
      />
    </div>
  );
};

const InPageMenu = ({ attributes, sectionAttributes }) => {
  return (
    <div
      className={classnames('postlist-categoriesContainer', {
        [`section--${attributes.color}`]: !!attributes.color,
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
  );
};

const edit = ({ attributes, setAttributes }) => {
  const sectionAttributes = useSelect((select) => {
    const allBlocks = select('core/block-editor').getBlocks();
    const sectionBlocksWithIds = allBlocks.filter((block) => block.attributes.sectionId);
    return sectionBlocksWithIds.map((a) => a.attributes);
  });

  const [loadingMenu, setLoadingMenu] = useState(false);
  const [loadingList, setLoadingList] = useState(false);
  const [list, setList] = useState([{ label: __('Select a menu…', 'amnesty'), value: '' }]);
  const [menus, setMenus] = useState({});

  const mounted = useRef();
  useEffect(() => {
    if (!mounted?.current) {
      mounted.current = true;

      if (attributes.menuId) {
        fetchMenu(menus, attributes.menuId, setLoadingMenu, setMenus);
      }

      if (attributes.type === 'standard-menu') {
        fetchList(list, setList, setLoadingList);
      }
    }
  }, []);

  useEffect(() => {
    if (attributes.type === 'standard-menu') {
      fetchList(list, setList, setLoadingList);
    }

    fetchMenu(menus, attributes.menuId, setLoadingMenu, setMenus);
  }, [attributes.menuId, attributes.type]);

  const containerClasses = classnames('postlist-categoriesContainer', {
    [`section--${attributes.color}`]: !!attributes.color,
  });

  const Controls = () => {
    return (
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
          {loadingList && (
            <p>{/* translators: [admin] */ __('Loading Menus…', 'amnesty')}</p>
          )}
          <SelectControl
            // translators: [admin]
            label={__('Menu', 'amnesty')}
            // translators: [admin]
            help={__('Which type of menu you would like to add to the page', 'amnesty')}
            value={attributes.type}
            options={options}
            onChange={(option) => setAttributes({ type: option })}
          />
          {attributes.type === 'standard-menu' && (
            <SelectControl
              // translators: [admin]
              label={__('Menu', 'amnesty')}
              options={list}
              disabled={loadingList}
              value={attributes.menuId}
              onChange={(menuId) => setAttributes({ menuId: parseInt(menuId, 10) })}
            />
          )}
          <SelectControl
            // translators: [admin]
            label={__('Background Colour', 'amnesty')}
            options={colours}
            value={attributes.color}
            onChange={(color) => setAttributes({ color })}
          />
        </PanelBody>
      </InspectorControls>
    );
  };

  if (attributes.type === 'standard-menu') {
    return (
      <>
        <Controls />
        <StandardMenu
          attributes={attributes}
          className={containerClasses}
          loadingMenu={loadingMenu}
          menus={menus}
        />
      </>
    );
  }

  if (attributes.type === 'inpage-menu') {
    return (
      <>
        <Controls />
        <InPageMenu attributes={attributes} sectionAttributes={sectionAttributes} />
      </>
    );
  }

  return <Controls />;
};

export default edit;
