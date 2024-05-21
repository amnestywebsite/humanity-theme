import createSelector from '../components/createSelector';

const { createHigherOrderComponent } = wp.compose;
const { __ } = wp.i18n;

const AuthorSelectorComponent = createSelector({
  // translators: [admin]
  label: __('Select author', 'amnesty'),
  route: '/wp/v2/users',
});

const AuthorSelector = createHigherOrderComponent((OriginalComponent) => (props) => (
  <InspectorControls>
    <label>
      {/* translators: [admin] */ __('Author:', 'amnesty')}
      <br />
      <OriginalComponent {...props} />
      <br />
    </label>
  </InspectorControls>
), 'withInspectorControls');

export default AuthorSelector(AuthorSelectorComponent);
