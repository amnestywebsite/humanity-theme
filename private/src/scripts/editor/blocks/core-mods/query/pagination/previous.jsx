const { useBlockProps, PlainText } = wp.blockEditor;
const { __ } = wp.i18n;

const arrowMap = {
  none: '',
  arrow: '←',
  chevron: '«',
};

export default function QueryPaginationPreviousEdit({
  attributes,
  setAttributes,
  context: { paginationArrow, showLabel },
}) {
  const displayArrow = arrowMap[paginationArrow];

  return (
    <a
      href="#pagination-previous-pseudo-link"
      onClick={(event) => event.preventDefault()}
      {...useBlockProps()}
    >
      {displayArrow && (
        <span
          className={`wp-block-query-pagination-previous-arrow is-arrow-${paginationArrow}`}
          aria-hidden
        >
          {displayArrow}
        </span>
      )}
      {showLabel && (
        <>
          <span className="icon"></span>
          <PlainText
            __experimentalVersion={2}
            tagName="span"
            // eslint-disable-next-line @wordpress/i18n-text-domain
            aria-label={__('Previous page link', 'default')}
            // eslint-disable-next-line @wordpress/i18n-text-domain
            placeholder={__('Previous Page', 'default')}
            value={attributes.label}
            onChange={(label) => setAttributes({ label })}
          />
        </>
      )}
    </a>
  );
}
