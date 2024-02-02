import { key } from '../utils';

const { cloneDeep, isString, remove, set } = lodash;
const { Button, IconButton, TextControl } = wp.components;
const { Component } = wp.element;
const { __ } = wp.i18n;

export default class RecipientEdit extends Component {
  static newRecipient = {
    key: '',
    name: '',
    jobTitle: '',
  };

  constructor(...args) {
    super(...args);
    const recipients = this.props.attributes.refreshedRecipients.replace(/<-->/gi, '') || '[]';
    const parsedRecipients = JSON.parse(recipients).map((recipient) => ({
      ...recipient,
      key: recipient.key || key(4),
    }));

    this.state = { parsedRecipients };
  }

  componentDidMount() {
    const { attributes, setAttributes } = this.props;
    const { hasRefreshed } = attributes;
    let { recipients } = attributes;
    if (isString(recipients)) {
      recipients = recipients.replace(/"{3,}/g, '');
    }

    const hasRecipients = !!recipients && recipients !== 'false' && recipients !== 'Array';
    if (hasRecipients && hasRefreshed !== '1') {
      const parsedRecipients = JSON.parse(recipients || '[]');
      const refreshMap = parsedRecipients.map((recipient) => ({
        ...RecipientEdit.newRecipient,
        key: key(4),
        jobTitle: recipient,
      }));
      this.setState(
        {
          parsedRecipients: refreshMap,
        },
        () => {
          setAttributes({
            recipients: 'false',
            hasRefreshed: '1',
            // Using <--> to prevent issues with updating meta that looks like JSON.
            refreshedRecipients: `<-->${JSON.stringify(refreshMap)}<-->`,
          });
        },
      );

      return;
    }

    if (hasRefreshed !== '1') {
      setAttributes({ hasRefreshed: '1' });
    }
    if (this.state.parsedRecipients.length < 1) {
      this.addRecipient();
    }
  }

  updateRecipientsAttribute = () => {
    this.props.setAttributes({
      // Using <--> to prevent issues with updating meta that looks like JSON.
      refreshedRecipients: `<-->${JSON.stringify(this.state.parsedRecipients)}<-->`,
    });
  };

  // a Wrapper that setState that automatically updates the attributes after.
  doState = (state) => {
    this.setState(
      {
        ...state,
      },
      this.updateRecipientsAttribute,
    );
  };

  addRecipient = () => {
    this.doState({
      parsedRecipients: [
        ...this.state.parsedRecipients,
        { ...RecipientEdit.newRecipient, key: key(4) },
      ],
    });
  };

  updateRecipient = (index) => (prop) => (value) => {
    const parsedRecipients = cloneDeep(this.state.parsedRecipients);
    set(parsedRecipients, [index, prop], value);

    this.doState({
      parsedRecipients,
    });
  };

  removeRecipient = (index) => () => {
    const parsedRecipients = cloneDeep(this.state.parsedRecipients);
    remove(parsedRecipients, (val, n) => n === index);

    this.doState({
      parsedRecipients,
    });
  };

  render() {
    const { parsedRecipients } = this.state;

    return (
      <div>
        <h3>Recipients</h3>
        <div className="form">
          <div className="form-row">
            <div className="form-column">
              <h4>{/* translators: [admin] */ __('Name', 'amnesty')}</h4>
            </div>
            <div className="form-column">
              <h4>{/* translators: [admin] */ __('Role', 'amnesty')}</h4>
            </div>
          </div>
          {parsedRecipients.map((recipient, index) => {
            const update = this.updateRecipient(index);
            const removeRecipient = this.removeRecipient(index);

            return (
              <div key={recipient.key} className="form-row">
                <div className="form-column">
                  <TextControl
                    value={recipient.name}
                    // translators: [admin]
                    placeholder={__('Name', 'amnesty')}
                    onChange={update('name')}
                  />
                </div>
                <div className="form-column">
                  <TextControl
                    value={recipient.jobTitle}
                    onChange={update('jobTitle')}
                    // translators: [admin]
                    placeholder={__('Role', 'amnesty')}
                  />
                </div>
                <div className="form-action">
                  <IconButton icon="trash" onClick={removeRecipient} />
                </div>
              </div>
            );
          })}

          <div className="form-footer">
            <Button isPrimary onClick={this.addRecipient}>
              {/* translators: [admin] */ __('Add Row', 'amnesty')}
            </Button>
          </div>
        </div>
      </div>
    );
  }
}
