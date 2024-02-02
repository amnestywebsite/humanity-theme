import Select from 'react-select';

const { Component } = wp.element;
const { __ } = wp.i18n;

class TermSelect extends Component {
  constructor(...args) {
    super(...args);

    this.state = {
      loading: false,
      options: [],
      route: '/amnesty/v1/categories',
    };
  }

  handleTermInputChange = (value) => this.props.onChange(value);

  render() {
    const { value, allTerms } = this.props;

    return (
      <Select
        options={allTerms}
        styles={{
          menu: (base) => ({
            ...base,
            position: 'relative',
          }),
        }}
        isMulti={true}
        isLoading={this.state.loading}
        isDisabled={this.state.loading}
        placeholder={
          this.state.loading
            ? // translators: [admin]
              __('Loading', 'amnesty')
            : // translators: [admin]
              __('Select terms', 'amnesty')
        }
        onChange={this.handleTermInputChange}
        isClearable={true}
        value={value}
      />
    );
  }
}

export default TermSelect;
