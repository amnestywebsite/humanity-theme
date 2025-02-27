/* eslint-disable no-underscore-dangle */
import { PanelBody } from '@wordpress/components';
import { PluginSidebar } from '@wordpress/editor';
import { __ } from '@wordpress/i18n';

import FeaturedImage from './components/FeaturedImage.jsx';
import Header from './components/Header.jsx';
import Metadata from './components/Metadata.jsx';
import RelatedContent from './components/RelatedContent.jsx';
import Sidebar from './components/Sidebar.jsx';
import TermFeature from './components/TermFeature.jsx';

/**
 * Renders options specific to the page post type
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component|null}
 */
const PageOptions = ({ createMetaUpdate, props }) => {
  if (props.type !== 'page') {
    return null;
  }

  return (
    <PanelBody title={/* translators: [admin] */ __('Page Options', 'amnesty')}>
      <Header createMetaUpdate={createMetaUpdate} props={props} />
    </PanelBody>
  );
};

/**
 * Renders options specific to the post post type
 *
 * @param {Boolean} loaded whether the API request has completed
 * @param {Array} termOptions list of options
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component|null}
 */
const PostOptions = ({ createMetaUpdate, props }) => {
  if (props?.type !== 'post') {
    return null;
  }

  return (
    <PanelBody title={/* translators: [admin] */ __('Post Options', 'amnesty')} initialOpen={false}>
      <FeaturedImage createMetaUpdate={createMetaUpdate} props={props} />
      <RelatedContent createMetaUpdate={createMetaUpdate} props={props} />
      <TermFeature createMetaUpdate={createMetaUpdate} props={props} />
    </PanelBody>
  );
};

/**
 * Render the AppearanceOptions plugin sidebar
 *
 * @param {Object} param0 the passed props
 *
 * @returns {wp.element.Component}
 */
const AppearanceOptions = ({ createMetaUpdate, ...props }) => (
  <>
    {/* translators: [admin] */}
    <PluginSidebar name="amnesty-appearance" title={__('Appearance', 'amnesty')}>
      <PageOptions createMetaUpdate={createMetaUpdate} props={props} />
      <PostOptions createMetaUpdate={createMetaUpdate} props={props} />
      <Sidebar createMetaUpdate={createMetaUpdate} props={props} />
      <Metadata createMetaUpdate={createMetaUpdate} props={props} />
    </PluginSidebar>
  </>
);

export default AppearanceOptions;
