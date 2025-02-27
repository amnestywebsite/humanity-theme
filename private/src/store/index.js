import { createReduxStore, register } from '@wordpress/data';

import reducer from './reducer';
import * as actions from './actions';
import * as selectors from './selectors';

const store = createReduxStore('amnesty/blocks', {
  reducer,
  actions,
  selectors,
});

register(store);

export default store;
