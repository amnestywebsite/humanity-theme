import classnames from 'classnames';
import { isObject } from 'lodash';
import apiFetch from '@wordpress/api-fetch';
import {
  BlockAlignmentToolbar,
  BlockControls,
  BlockVerticalAlignmentToolbar,
  InnerBlocks,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  useBlockProps,
} from '@wordpress/block-editor';
import {
  Button,
  FocalPointPicker,
  PanelBody,
  RangeControl,
  SelectControl,
} from '@wordpress/components';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { getEditorCssV2, getDimensionsV2, getUrlV2, randId } from '../background-media/utils';

const ALLOWED_BLOCKS = [
  'core/heading',
  'core/paragraph',
  'core/list',
  'core/buttons',
  'core/button',
];

export default function Edit(props) {
  const { attributes, clientId, setAttributes } = props;
  const { background, horizontalAlignment, image, uniqId, verticalAlignment } = attributes;
  const [focalPoint, setFocalPoint] = useState({ x: 0.5, y: 0.5 });
  const [opacity, setOpacity] = useState(1);
  const [imageObject, setImageObject] = useState({});

  // componentDidMount
  const mounted = useRef();
  useEffect(() => {
    if (mounted.current) {
      return;
    }

    mounted.current = true;

    if (!uniqId) {
      setAttributes({ uniqId: randId() });
    }

    if (!image) {
      return;
    }

    if (isObject(image)) {
      setAttributes({ image: image.id });
      return;
    }

    if (!imageObject?.id) {
      apiFetch({ path: `wp/v2/media/${image}` }).then(setImageObject);
    }
  }, [image, uniqId, setAttributes, imageObject]);

  // componentDidUpdate
  useEffect(() => {
    if (!mounted.current) {
      return;
    }

    setAttributes({ focalPoint, opacity });

    if (!image) {
      return;
    }

    if (!imageObject?.id) {
      apiFetch({ path: `wp/v2/media/${image}` }).then(setImageObject);
    }
  }, [focalPoint, image, imageObject, opacity, setAttributes]);

  const containerClasses = classnames('text-media--itemContainer', {
    [`align${horizontalAlignment}`]: !!horizontalAlignment,
    [`is-vertically-aligned-${verticalAlignment}`]: !!verticalAlignment,
    [`has-${background}-background-color`]: !!background,
  });

  const blockProps = useBlockProps({
    className: containerClasses,
  });

  const css = getEditorCssV2(imageObject, `block-${clientId}`, focalPoint, opacity);

  return (
    <>
      <InspectorControls>
        {imageObject && (
          <PanelBody title={/* translators: [admin] */ __('Image Settings', 'amnesty')}>
            <FocalPointPicker
              /* translators: [admin] */
              label={__('Focal Point', 'amnesty')}
              url={getUrlV2(imageObject, 'lwi-block-sm@2x')}
              dimensions={getDimensionsV2(imageObject, 'lwi-block-sm@2x')}
              value={focalPoint}
              onChange={setFocalPoint}
            />
            <RangeControl
              /* translators: [admin] */
              label={__('Opacity', 'amnesty')}
              min={0}
              max={1}
              step={0.1}
              value={opacity}
              onChange={(value) => setOpacity(value)}
            />
          </PanelBody>
        )}
        {!image && (
          /* translators: [admin] */
          <PanelBody title={__('Background Colour', 'amnesty')}>
            <SelectControl
              /* translators: [admin] */
              label={__('Colour', 'amnesty')}
              value={background}
              onChange={(value) => setAttributes({ background: value })}
              options={[
                {
                  /* translators: [admin] */
                  label: __('White', 'amnesty'),
                  value: '',
                },
                {
                  /* translators: [admin] */
                  label: __('Grey', 'amnesty'),
                  value: 'very-light-gray',
                },
              ]}
            />
          </PanelBody>
        )}
      </InspectorControls>
      <BlockControls>
        <BlockAlignmentToolbar
          value={horizontalAlignment}
          onChange={(value) => setAttributes({ horizontalAlignment: value })}
        />
        <BlockVerticalAlignmentToolbar
          value={verticalAlignment}
          onChange={(value) => setAttributes({ verticalAlignment: value })}
        />
      </BlockControls>
      {css && <style>{css}</style>}
      <div id={uniqId} {...blockProps}>
        <div>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ image: media.id })}
              allowedTypes={['image']}
              value={isObject(image) ? image.id : image}
              render={({ open }) => (
                <Button isPrimary onClick={open}>
                  {image
                    ? /* translators: [admin] */
                      __('Replace Image', 'amnesty')
                    : /* translators: [admin] */
                      __('Add Image', 'amnesty')}
                </Button>
              )}
            />
          </MediaUploadCheck>
          {image ? (
            <Button isSecondary onClick={() => setAttributes({ image: 0 })}>
              {/* translators: [admin] */ __('Remove Image', 'amnesty')}
            </Button>
          ) : null}
        </div>
        <div>
          <InnerBlocks templateLock={false} allowedBlocks={ALLOWED_BLOCKS} />
        </div>
      </div>
    </>
  );
}
