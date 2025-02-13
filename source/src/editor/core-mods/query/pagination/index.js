import { assign } from 'lodash';
import { addFilter } from '@wordpress/hooks';

import QueryPaginationNextEdit from './next.jsx';
import QueryPaginationPreviousEdit from './previous.jsx';

addFilter('blocks.registerBlockType', 'amnesty-core', (settings, name) => {
  if (name === 'core/query-pagination-next') {
    return assign({}, settings, {
      edit: QueryPaginationNextEdit,
    });
  }

  if (name === 'core/query-pagination-previous') {
    return assign({}, settings, {
      edit: QueryPaginationPreviousEdit,
    });
  }

  return settings;
});
