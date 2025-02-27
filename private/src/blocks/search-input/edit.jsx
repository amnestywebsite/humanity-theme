import { __ } from '@wordpress/i18n';

import { randId } from '../../utils';

export default function Edit() {
  const searchId = randId();

  return (
    <p>
      <label htmlFor={`search-${searchId}`} className="u-hiddenVisually">
        {/* translators: [front] screen reader text for search field */}
        {__('Search input', 'amnesty')}
      </label>
      {/* translators: [front] placeholder text for search field */}
      <input
        id={`search-${searchId}`}
        className="has-autofocus"
        type="text"
        name="s"
        role="searchbox"
        placeholder={__('What are you looking for?', 'amnesty')}
        value=""
      />
    </p>
  );
}
