import { Placeholder } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Render a no-posts-found error area
 *
 * @returns {React.component}
 */
export default function NoPosts({ icon }) {
  return (
    <div>
      <Placeholder icon={icon} label={__('Related Content', 'amnesty')}>
        {__('No posts found.')}
      </Placeholder>
    </div>
  );
}
