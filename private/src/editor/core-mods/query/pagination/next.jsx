import { useBlockProps, PlainText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const arrowMap = {
  none: '',
  arrow: '→',
  chevron: '»',
};

export default function QueryPaginationNextEdit({
  attributes,
  setAttributes,
  context: { paginationArrow, showLabel },
}) {
  const displayArrow = arrowMap[paginationArrow];

  return (
    <a
      href="#pagination-next-pseudo-link"
      onClick={(event) => event.preventDefault()}
      {...useBlockProps()}
    >
      {showLabel && (
        <>
          <PlainText
            __experimentalVersion={2}
            tagName="span"
            // eslint-disable-next-line @wordpress/i18n-text-domain
            aria-label={__('Next page link', 'default')}
            // eslint-disable-next-line @wordpress/i18n-text-domain
            placeholder={__('Next Page', 'default')}
            value={attributes.label}
            onChange={(label) => setAttributes({ label })}
          />
          <span className="icon"></span>
        </>
      )}
      {displayArrow && (
        <span
          className={`wp-block-query-pagination-next-arrow is-arrow-${paginationArrow}`}
          aria-hidden
        >
          {displayArrow}
        </span>
      )}
    </a>
  );
}
