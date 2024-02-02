// @TODO: fix this

const manipulateQueryParameters = (url) => {
  const urlParts = url.split('?');
  const base = urlParts.shift();

  const queryParams =
    urlParts.length > 0
      ? urlParts
          .join('&')
          .split('&')
          .reduce((prev, obj) => {
            const [key, value] = obj.split('=');

            return {
              ...prev,
              [key]: value,
            };
          }, {})
      : {};

  return {
    base: `${base}?`,
    params: queryParams,
  };
};

const handleDropdownChange = (event) => {
  if (!window.amnesty_data) {
    return;
  }

  const { target: { value = '' } = {} } = event;
  const data = manipulateQueryParameters(window.amnesty_data.archive_base_url);
  data.params.sort = value;

  const params = Object.keys(data.params)
    .map((key) => `${key}=${data.params[key]}`)
    .join('&');

  window.location = `${data.base}${params}`;
};

const init = () => {
  const dropdown = document.getElementById('sort-by');

  if (!dropdown) {
    return;
  }

  dropdown.addEventListener('change', handleDropdownChange);
};

export default init;
