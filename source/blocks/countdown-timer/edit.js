import { useEffect, useState } from '@wordpress/element';
import { DateTimePicker, TextControl, PanelBody } from '@wordpress/components';
import { AlignmentToolbar, BlockControls, InspectorControls } from '@wordpress/block-editor';
import { __, _n, _x, sprintf } from '@wordpress/i18n';

const MINUTE = 60;
const HOUR = 60 * MINUTE;
const DAY = 24 * HOUR;

const getTimeRemaining = (date) => {
  if (!date) {
    return {};
  }

  const total = (Date.parse(date) - Date.now()) / 1000;
  const seconds = Math.floor(total % 60);
  const minutes = Math.floor((total / MINUTE) % 60);
  const hours = Math.floor((total / HOUR) % 24);
  const days = Math.floor(total / DAY);

  return {
    total,
    days,
    hours,
    minutes,
    seconds,
  };
};

const pad = (value) => `0${value}`.slice(-2);

const clockTemplate = (state, attributes) => {
  if (!attributes.date) {
    return <div className="countdownClock">00:00:00</div>;
  }

  const { days = 0, hours = 0, minutes = 0, seconds = 0 } = state.date;
  // translators: [admin]
  const dayPlural = _n('%d day', '%d days', days, 'amnesty');

  if (days <= 0 && hours <= 0 && minutes <= 0 && seconds <= 0) {
    return <div className="countdownClock">{attributes.expiredText}</div>;
  }

  if (days >= 3) {
    return <div className="countdownClock">{sprintf(dayPlural, days)}</div>;
  }

  if (days >= 1) {
    return (
      <div className="countdownClock">
        {sprintf(
          // translators: [admin]
          _x('%1$s, %2$s:%3$s:%4$s', 'format for timer, i.e. "x days, H:m:s"', 'amnesty'),
          sprintf(dayPlural, days),
          pad(hours),
          pad(minutes),
          pad(seconds),
        )}
      </div>
    );
  }

  return (
    <div className="countdownClock">
      {sprintf(
        // translators: [admin]
        _x('%1$s:%2$s:%3$s', 'time format, i.e. "H:m:s"', 'amnesty'),
        pad(hours),
        pad(minutes),
        pad(seconds),
      )}
    </div>
  );
};

const DisplayComponent = ({ attributes, setAttributes }) => {
  const [state, setState] = useState({
    date: getTimeRemaining(attributes.date),
    alignment: '',
  });

  useEffect(() => {
    const date = getTimeRemaining(attributes.date);

    if (date.days >= 3) {
      return setState({ date });
    }

    const interval = setInterval(() => {
      setState({
        date: getTimeRemaining(attributes.date),
      });
    }, 1000);
    return () => clearInterval(interval);
  }, [attributes.date]);

  return (
    <>
      <BlockControls>
        <AlignmentToolbar
          value={attributes.alignment}
          onChange={(alignment) => setAttributes({ alignment })}
        />
      </BlockControls>
      <InspectorControls>
        <PanelBody>
          <DateTimePicker
            currentDate={attributes.date}
            onChange={(date) => setAttributes({ date })}
            is12Hour={true}
          />
          <TextControl
            // translators: [admin]
            label={__('Time Expired Text', 'amnesty')}
            className="expiredTextBox"
            value={attributes.expiredText}
            onChange={(expiredText) => setAttributes({ expiredText })}
            // translators: [admin]
            placeholder={__('enter text here', 'amnesty')}
          />
        </PanelBody>
      </InspectorControls>
      <div className="countdownContainer">
        <div className="clockdiv" style={{ textAlign: attributes.alignment }}>
          {clockTemplate(state, attributes)}
        </div>
      </div>
    </>
  );
};

export default DisplayComponent;
