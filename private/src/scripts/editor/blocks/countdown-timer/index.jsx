import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/countdown-timer', {
  // translators: [admin]
  title: __('Countdown Timer', 'amnesty'),
  icon: 'clock',
  keywords: [
    // translators: [admin]
    __('Countdown', 'amnesty'),
    // translators: [admin]
    __('Timer', 'amnesty'),
  ],
  category: 'amnesty-core',
  attributes: {
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
  },

  edit: DisplayComponent,

  save({ attributes }) {
    const { date, expiredText } = attributes;

    return (
      <div className="clockdiv" style={{ textAlign: attributes.alignment }}>
        <div className="countdownClock" data-timestamp={date} data-expiredText={expiredText} />
      </div>
    );
  },
});
