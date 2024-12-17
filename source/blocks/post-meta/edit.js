/* eslint-disable @wordpress/i18n-text-domain */
/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const { useEntityProp, store: coreStore } = wp.coreData;
const { AlignmentControl, BlockControls, InspectorControls, useBlockProps } = wp.blockEditor;
const { PanelBody, SelectControl, ToggleControl } = wp.components;
const { useSelect } = wp.data;
const { useMemo } = wp.element;
const { applyFilters } = wp.hooks;
const { __, sprintf } = wp.i18n;

/**
 * Format meta keys for selection
 *
 * @param {Object} rawMeta the meta for a post
 *
 * @returns {Array<Object>}
 */
function formatMetaKeys(rawMeta) {
  const formatted = [];

  Object.keys(rawMeta).forEach((key) => {
    const label = key
      .replace(/[_-]/g, ' ')
      .trim()
      .split(' ')
      .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
      .join(' ');

    formatted.push({ label, value: key });
  });

  return formatted.sort((a, b) => a.label.charCodeAt(0) - b.label.charCodeAt(0));
}

export default function edit({ attributes, context, setAttributes }) {
  const { isLink, isSingle } = attributes;
  const { postId, postType: postTypeSlug } = context;

  const blockProps = useBlockProps({
    className: classnames({ [`has-text-align-${attributes.textAlign}`]: attributes.textAlign }),
  });

  const [meta] = useEntityProp('postType', postTypeSlug, 'meta', postId);
  const metaKeys = useMemo(() => formatMetaKeys(meta), [meta]);
  const postType = useSelect(
    (select) => (postTypeSlug ? select(coreStore).getPostType(postTypeSlug) : null),
    [postTypeSlug],
  );

  let metaValue = `<${__('Select a meta key', 'amnesty')}>`;
  if (attributes.metaKey && Object.hasOwnProperty.call(meta, attributes.metaKey)) {
    metaValue = Array.isArray(meta[attributes.metaKey])
      ? meta[attributes.metaKey].join(', ')
      : meta[attributes.metaKey];
  }

  metaValue = applyFilters(
    'amnesty-core/post-meta/meta-value',
    metaValue,
    postId,
    postTypeSlug,
    attributes.metaKey,
  );

  return (
    <>
      <BlockControls group="block">
        <AlignmentControl
          value={attributes.textAlign}
          onChange={(textAlign) => setAttributes({ textAlign })}
        />
      </BlockControls>

      <InspectorControls>
        <PanelBody title={__('Settings', 'default')}>
          <SelectControl
            label={__('Choose a meta key', 'amnesty')}
            value={attributes.metaKey}
            options={[{ label: __('None', 'default'), value: '' }, ...metaKeys]}
            onChange={(metaKey) => setAttributes({ metaKey })}
          />
          <ToggleControl
            label={__('Does this meta key have a single value?', 'amnesty')}
            checked={isSingle}
            onChange={() => setAttributes({ isSingle: !isSingle })}
          />
          <ToggleControl
            __nextHasNoMarginBottom
            label={
              postType?.labels.singular_name
                ? sprintf(
                    // translators: %s: Name of the post type e.g: "post".
                    __('Link to %s', 'default'),
                    postType.labels.singular_name.toLowerCase(),
                  )
                : __('Link to post', 'default')
            }
            onChange={() => setAttributes({ isLink: !isLink })}
            checked={isLink}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>{metaValue}</div>
    </>
  );
}
