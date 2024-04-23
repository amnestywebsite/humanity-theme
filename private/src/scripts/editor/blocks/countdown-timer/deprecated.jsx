const blockAttributes = {
  date: {
    type: 'string',
  },
  timeRemaining: {
    type: 'string',
  },
  expiredText: {
    type: 'string',
    default: '',
  },
  isTimeSet: {
    type: 'bool',
    default: false,
  },
  alignment: {
    type: 'string',
    default: 'none',
  },
};

const v1 = {
  attributes: blockAttributes,
  save({ attributes }) {
    const { date, expiredText } = attributes;

    return (
      <div className="clockdiv" style={{ textAlign: attributes.alignment }}>
        <div className="countdownClock" data-timestamp={date} data-expiredText={expiredText} />
      </div>
    );
  },
}

const deprecated = [v1];

export default deprecated;
