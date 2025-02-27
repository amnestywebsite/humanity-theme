import { InspectorControls } from '@wordpress/block-editor';
import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

import createSelector from './createSelector.jsx';

const AuthorSelectorComponent = createSelector({
  /* translators: [admin] */
  label: __('Select author', 'amnesty'),
  route: '/wp/v2/users',
});

const AuthorSelector = createHigherOrderComponent((OriginalComponent) => {
  const WrappedAuthorSelectorComponent = (props) => (
    <InspectorControls>
      <label>
        {/* translators: [admin] */ __('Author:', 'amnesty')}
        <br />
        <OriginalComponent {...props} />
        <br />
      </label>
    </InspectorControls>
  );

  return WrappedAuthorSelectorComponent;
}, 'withInspectorControls');

export default AuthorSelector(AuthorSelectorComponent);
