const { PanelBody, ToggleControl } = wp.components;
const { __ } = wp.i18n;

/**
 * Renders options specific to the page header
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component}
 */
const Header = ({ createMetaUpdate, props }) => {
  const navStyle = props.meta._nav_style;

  // uses old transparency option - update it
  if (navStyle.indexOf('transparent') !== -1 && navStyle !== 'transparent-light') {
    props.setAttributes({ navStyle: 'transparent-light' });
  }

  return (
    <PanelBody title={/* translators: [admin] */ __('Header', 'amnesty')}>
      <ToggleControl
        label={__('Use transparent header', 'amnesty')}
        help={__(
          'Enable transparent mode on the main header. Requires the use of a header block with a featured image',
          'amnesty',
        )}
        checked={props.meta._nav_style === 'transparent-light'}
        onChange={(value) =>
          createMetaUpdate(
            '_nav_style',
            value ? 'transparent-light' : 'global',
            props.meta,
            props.oldMeta,
          )
        }
      />
    </PanelBody>
  );
};

export default Header;
