import { RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { httpsOnly } from '../../../utils';

export default function EmbedContainer({ attributes, setAttributes }) {
  const { isSelected, className, embedUrl, height, width } = attributes;
  let { minHeight, minWidth } = attributes;

  if (!width && !height) {
    minHeight = Math.max(minHeight, 350);
  }

  if (!width) {
    minWidth = '100%';
  } else {
    minWidth = `${width}%`;
  }

  return (
    <figure className={className}>
      <div className="iframe-wrapper" style={{ minHeight }}>
        <iframe src={httpsOnly(embedUrl)} style={{ height: `${minHeight}px`, width: minWidth }} />
      </div>
      {attributes.caption ||
        (isSelected && (
          <div className="iframe-caption-wrapper">
            <RichText
              tagName="figcaption"
              /* translators: [admin] */
              placeholder={__('Write caption…', 'amnesty')}
              value={attributes.caption}
              onChange={(caption) => setAttributes({ caption })}
              inlineToolbar
              format="string"
            />
          </div>
        ))}
    </figure>
  );
}
