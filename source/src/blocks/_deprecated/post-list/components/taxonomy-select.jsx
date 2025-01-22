import Select from 'react-select';
import * as api from './post-selector/api';

const { Component } = wp.element;
const { __ } = wp.i18n;

class TaxonomySelect extends Component {
  constructor(...args) {
    super(...args);

    this.state = {
      loading: false,
      options: [],
      route: '/amnesty/v1/categories',
    };

    this.fetch();
  }

  handleApiResult = (results) => {
    const options = [];

    Object.keys(results).map((key) => {
      let label = results[key].name;

      if (results[key].parent) {
        label = `- ${results.name}`;
      }

      options.push({ label, value: results[key].rest_base });

      return results[key];
    });

    this.setState({ options, loading: false });
  };

  fetch() {
    api.getTaxonomies().then((items) => {
      this.handleApiResult(items);
    });
  }

  handleInputChange = (value) => {
    if (value) {
      return this.props.onChange(value);
    }
    return this.props.onChange({});
  };

  componentDidMount = () => {
    if (this.props.value) {
      this.handleInputChange(this.props.value);
    }
  };

  render() {
    const { value } = this.props;

    return (
      <Select
        options={this.state.options}
        styles={{
          menu: (base) => ({
            ...base,
            position: 'relative',
          }),
        }}
        isMulti={false}
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

export default TaxonomySelect;
