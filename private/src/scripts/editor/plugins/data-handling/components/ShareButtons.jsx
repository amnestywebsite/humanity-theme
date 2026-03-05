/* eslint-disable no-underscore-dangle */

const { ToggleControl } = wp.components;
const { __ } = wp.i18n;

export default function ShareButtons({ postMeta: meta, editMeta }) {
  return (
    <ToggleControl
      label={__('Hide Share buttons', 'amnesty')}
      help={__('Hide the share buttons for this entity', 'amnesty')}
      checked={meta?._disable_share_icons}
      onChange={(value) => editMeta('_disable_share_icons')(value)}
    />
  );
}
