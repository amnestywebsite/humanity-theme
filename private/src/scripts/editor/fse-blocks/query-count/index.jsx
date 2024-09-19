import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/query-count', {
  title: __('Query Count', 'amnesty'),
  description: __('Display the query results count', 'amnesty'),
  category: 'theme',
  icon: 'info',
  usesContext: ['queryId', 'query'],
  edit: DisplayComponent,
  save: () => null,
});
