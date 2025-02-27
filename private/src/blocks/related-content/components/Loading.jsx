import { Placeholder, Spinner } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Render a loading area
 *
 * @returns {React.Component}
 */
export default function Loading({ icon }) {
  return (
    <div>
      <Placeholder icon={icon} label={__('Related Content', 'amnesty')}>
        <Spinner />
      </Placeholder>
    </div>
  );
}
