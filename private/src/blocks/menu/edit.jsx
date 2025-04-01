import classnames from 'classnames';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { useEntityRecord, useEntityRecords } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

const colours = [
  /* translators: [admin] */
  { label: __('White', 'amnesty'), value: '' },
  /* translators: [admin] */
  { label: __('Grey', 'amnesty'), value: 'dark' },
];

const options = [
  /* translators: [admin] */
  { label: __('Standard Menu', 'amnesty'), value: 'standard-menu' },
  /* translators: [admin] */
  { label: __('In-page Menu', 'amnesty'), value: 'inpage-menu' },
];

const StandardMenu = ({ loadingMenu, menu, items }) => {
  if (!menu) {
    /* translators: [admin] */
    return <p>{__('Select a menu in the sidebar', 'amnesty')}</p>;
  }

  if (loadingMenu) {
    /* translators: [admin] */
    return <p>{__('Loading Menu…', 'amnesty')}</p>;
  }

  if (!items) {
    /* translators: [admin] */
    return <p>{__('No menu items', 'amnesty')}</p>;
  }

  return (
    <ul className="postlist-categories">
      {items?.map((item) => {
        const itemClasses = classnames(
          'menu-item',
          `menu-item-type-${item.type}`,
          `menu-item-object-${item.object}`,
          `menu-item-${item.id}`,
        );

        return (
          <li key={item.id} className={itemClasses}>
            <a className="btn btn--white" href={item.url}>
              {item.title.rendered}
            </a>
          </li>
        );
      })}
    </ul>
  );
};

const InPageMenu = ({ sectionAttributes }) => (
  <ul className="postlist-categories">
    {sectionAttributes.map((value, index) => (
      <li key={index}>
        <a className="btn btn--white" href={`#${value.sectionId}`}>
          {value.sectionName}
        </a>
      </li>
    ))}
  </ul>
);

export default function Edit({ attributes, setAttributes }) {
  const sectionAttributes = useSelect((select) => {
    const allBlocks = select('core/block-editor').getBlocks();
    const sectionBlocksWithIds = allBlocks.filter((block) => block.attributes.sectionId);
    return sectionBlocksWithIds.map((a) => a.attributes);
  });

  const { records: menuRecords, isResolving } = useEntityRecords('root', 'menu', {
    per_page: -1,
    context: 'view',
  });

  const menus = [
    { label: __('Select a menu…', 'amnesty'), value: '' },
    ...(menuRecords?.map((m) => ({ label: m.name, value: m.id })) || []),
  ];

  const { record: menu } = useEntityRecord('root', 'menu', attributes.menuId);
  const { records: menuItems } = useEntityRecords('root', 'menuItem', { menus: attributes.menuId });

  const containerClasses = classnames('postlist-categoriesContainer', {
    [`section--${attributes.color}`]: !!attributes.color,
  });

  const Controls = () => (
    <InspectorControls>
      <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
        {isResolving && <p>{/* translators: [admin] */ __('Loading Menus…', 'amnesty')}</p>}
        <SelectControl
          /* translators: [admin] */
          label={__('Menu', 'amnesty')}
          /* translators: [admin] */
          help={__('Which type of menu you would like to add to the page', 'amnesty')}
          value={attributes.type}
          options={options}
          onChange={(type) => setAttributes({ type })}
        />
        {attributes.type === 'standard-menu' && (
          <SelectControl
            /* translators: [admin] */
            label={__('Menu', 'amnesty')}
            options={menus}
            disabled={isResolving}
            value={attributes.menuId}
            onChange={(menuId) => setAttributes({ menuId })}
          />
        )}
        <SelectControl
          /* translators: [admin] */
          label={__('Background Colour', 'amnesty')}
          options={colours}
          value={attributes.color}
          onChange={(color) => setAttributes({ color })}
        />
      </PanelBody>
    </InspectorControls>
  );

  const blockProps = useBlockProps({
    className: classnames(containerClasses, {
      'postlist-categoriesContainer': attributes.type === 'inpage-menu',
      [`section--${attributes.color}`]: !!attributes.color,
    }),
  });

  if (attributes.type === 'standard-menu') {
    return (
      <div {...blockProps}>
        <Controls />
        <StandardMenu loadingMenu={isResolving} menu={menu} items={menuItems} />
      </div>
    );
  }

  if (attributes.type === 'inpage-menu') {
    return (
      <div {...blockProps}>
        <Controls />
        <InPageMenu attributes={attributes} sectionAttributes={sectionAttributes} />
      </div>
    );
  }

  return <Controls />;
}
