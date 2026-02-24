/* eslint-disable no-underscore-dangle */

const { ToggleControl } = wp.components;
const { __ } = wp.i18n;

export default function ShareButtons({ postMeta: meta, editMeta }) {
  return (
    <ToggleControl
      label={__('Disable Sharing', 'amnesty')}
      help={__('Disable Share Buttons for this item', 'amnesty')}
      checked={meta?._disable_share_icons}
      onChange={(value) => editMeta('_disable_share_icons')(value)}
    />
  );
}
