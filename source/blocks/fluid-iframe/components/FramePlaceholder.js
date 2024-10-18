import { Button, Placeholder } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Placeholder component
 *
 * @param {Object} props component props
 *
 * @returns {WP.Element}
 */
const FramePlaceholder = ({ label, className, onSubmit, inputRef }) => {
  return (
    <Placeholder label={label} className={className}>
      <form onSubmit={onSubmit}>
        <input
          ref={inputRef}
          type="url"
          className="components-placeholder__input"
          // translators: [admin]
          aria-label={__('Iframe URL', 'amnesty')}
          // translators: [admin]
          placeholder={__('Enter URL to embed here…', 'amnesty')}
        />
        <Button isLarge type="submit">
          {/* translators: [admin] */ __('Embed', 'amnesty')}
        </Button>
      </form>
    </Placeholder>
  );
};

export default FramePlaceholder;
