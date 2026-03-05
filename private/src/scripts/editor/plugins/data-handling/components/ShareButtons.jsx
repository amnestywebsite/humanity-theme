/* eslint-disable no-underscore-dangle */

const { ToggleControl } = wp.components;
const { useState } = wp.element;
const { __ } = wp.i18n;

/**
 * Render the component for managing an entity's share button visibility
 *
 * Note: the value is inverted because the label is the inverse of the field value
 *
 * @param {object} param0 props passed to the component
 * @param {object} param0.postMeta the entity's meta object
 * @param {function} param0.editMeta callback for manipulating entity meta
 *
 * @return {JSX.Element}
 */
export default function ShareButtons({ postMeta: meta, editMeta }) {
  const [checked, setChecked] = useState(!meta?._disable_share_icons);

  const onChange = (value) => {
    setChecked(value);
    editMeta('_disable_share_icons')(!value);
  };

  return (
    <ToggleControl
      label={__('Show Share buttons', 'amnesty')}
      help={__('Show the share buttons for this entity', 'amnesty')}
      checked={checked}
      onChange={onChange}
    />
  );
}
