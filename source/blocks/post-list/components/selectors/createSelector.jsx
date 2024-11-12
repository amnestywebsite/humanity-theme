import Select from 'react-select';

import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const defaultSearchFilterCallback = (results) => results.map((result) => ({
  label: result.parent ? `- ${result.name}` : result.name,
  value: result.id,
}));

const setupSearch = (callback, filterCallback = null, route) => (search = null) => {
  let path = route;

  if (search) {
    path += `?search=${encodeURIComponent(search)}`;
  }

  const filter = filterCallback ? filterCallback : defaultSearchFilterCallback;

  return wp.apiRequest({ path }).then(filter).then(callback);
};

const createSelector = ({ filterCallback = null, label, route }) => ({ onChange, value: data }) => {
  const [loading, setLoading] = useState(true);
  const [options, setOptions] = useState(options);
  const performSearch = setupSearch(setOptions, filterCallback, route);
  const mounted = useRef();

  useEffect(() => {
    if (!mounted?.current) {
      mounted.current = true;
      performSearch().then(() => setLoading(false));
    }
  }, []);

  const handleSearchInput = (value) => {
    if (value?.length >= 3) {
      setLoading(true);
      performSearch(value).then(setLoading(false));
    }
  };

  const handleInputChange = (value) => {
    if (!value) {
      return onChange(value);
    }

    if (!Array.isArray(value)) {
      return onChange(JSON.stringify([value]));
    }

    return onChange(JSON.stringify(value));
  };

  /* translators: [admin] */
  const placeholder = loading ? __('Loading', 'amnesty') : label;

  let value = data;

  try {
    value = JSON.parse(data);
  } catch (error) {
    console.debug(error);
    console.debug(data);
  }

  return (
    <Select
      options={options}
      styles={{ menu: (base) => ({ ...base, position: 'relative' }) }}
      isMulti={true}
      isLoading={loading}
      isDisabled={loading}
      isSearchable={true}
      placeholder={placeholder}
      onInputChange={handleSearchInput}
      onChange={handleInputChange}
      isClearable={true}
      value={value}
    />
  );
};

export default createSelector;
