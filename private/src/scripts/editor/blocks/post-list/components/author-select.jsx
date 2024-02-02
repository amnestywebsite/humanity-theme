import Select from 'react-select';

const { Component } = wp.element;
const { __ } = wp.i18n;

class AuthorSelect extends Component {
  constructor(...args) {
    super(...args);

    this.state = {
      loading: false,
      options: [],
      route: '/wp/v2/users',
    };

    this.fetch();
  }

  handleApiResult = (results) => {
    const options = results.map((result) => {
      let label = result.name;

      if (result.parent) {
        label = `- ${result.name}`;
      }

      return { label, value: result.id };
    });

    this.setState({ options, loading: false });
  };

  fetch(search = null) {
    let path = this.state.route;

    if (search) {
      path += `?search=${encodeURIComponent(search)}`;
    }

    wp.apiRequest({ path }).then(this.handleApiResult);
  }

  handleSearchInput = (value) => {
    if (!value || value.length < 3) {
      return;
    }

    this.fetch(value);
  };

  handleInputChange = (value) => {
    if (!value) {
      return this.props.onChange(value);
    }

    if (!Array.isArray(value)) {
      return this.props.onChange(JSON.stringify([value]));
    }

    return this.props.onChange(JSON.stringify(value));
  };

  render() {
    let { value } = this.props;

    if (value) {
      value = JSON.parse(value);
    }

    return (
      <Select
        options={this.state.options}
        styles={{
          menu: (base) => ({
            ...base,
            position: 'relative',
          }),
        }}
        isMulti={true}
        isLoading={this.state.loading}
        isDisabled={this.state.loading}
        isSearchable={true}
        placeholder={
          this.state.loading
            ? // translators: [admin]
              __('Loading', 'amnesty')
            : // translators: [admin]
              __('Select author', 'amnesty')
        }
        onInputChange={this.handleSearchInput}
        onChange={this.handleInputChange}
        isClearable={true}
        value={value}
      />
    );
  }
}

export default AuthorSelect;
