import Select from 'react-select';

const { Component } = wp.element;
const { __ } = wp.i18n;

class CategorySelect extends Component {
  constructor(...args) {
    super(...args);

    this.state = {
      loading: false,
      options: [],
      route: '/amnesty/v1/categories',
    };

    this.fetch();
  }

  handleApiResult = (results /* status, xhr */) => {
    const options = results.map((result) => {
      let label = result.name;

      if (result.parent) {
        label = `- ${result.name}`;
      }

      return { label, value: result.term_id };
    });

    this.setState({ options, loading: false });
  };

  fetch() {
    wp.apiRequest({ path: this.state.route }).then(this.handleApiResult);
  }

  handleInputChange = (value) => {
    if (!value) {
      return this.props.onChange(value);
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
        isLoading={this.state.loading}
        isDisabled={this.state.loading}
        placeholder={
          this.state.loading
            ? /* translators: [admin] */
              __('Loading', 'amnesty')
            : /* translators: [admin] */
              __('Select a category', 'amnesty')
        }
        onChange={this.handleInputChange}
        isClearable={true}
        value={value}
      />
    );
  }
}

export default CategorySelect;
