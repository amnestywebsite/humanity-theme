import { Placeholder } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Render a no-posts-found error area
 *
 * @returns {React.component}
 */
const NoPosts = ({ icon }) => (
  <div>
    <Placeholder icon={icon} label={__('Related Content', 'amnesty')}>
      {__('No posts found.')}
    </Placeholder>
  </div>
);

export default NoPosts;
