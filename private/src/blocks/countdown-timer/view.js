import './style.scss';

import { _n, _x, sprintf } from '@wordpress/i18n';

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

const clockTemplate = (data, attributes) => {
  if (!attributes.date) {
    return '00:00:00';
  }

  const { days = 0, hours = 0, minutes = 0, seconds = 0 } = data;
  /* translators: [front] https://wordpresstheme.amnesty.org/blocks/b021-countdown-timer/ */
  const dayPlural = _n('%d day', '%d days', days, 'amnesty');

  if (days <= 0 && hours <= 0 && minutes <= 0 && seconds <= 0) {
    return attributes.expiredText;
  }

  if (days >= 3) {
    return sprintf(dayPlural, days);
  }

  if (days >= 1) {
    return sprintf(
      /* translators: [front] https://wordpresstheme.amnesty.org/blocks/b021-countdown-timer/ */
      _x(
        '%1$s, %2$s:%3$s:%4$s',
        'time format(prefix translated separately) i.e. "x, H:i:s"',
        'amnesty',
      ),
      sprintf(dayPlural, days),
      pad(hours),
      pad(minutes),
      pad(seconds),
    );
  }

  return sprintf(
    /* translators: [front] https://wordpresstheme.amnesty.org/blocks/b021-countdown-timer/ */
    _x('%1$s:%2$s:%3$s', 'time format, H:i:s', 'amnesty'),
    pad(hours),
    pad(minutes),
    pad(seconds),
  );
};

const renderClock = (endTime) => {
  const { expiredtext, timestamp } = endTime.dataset;
  const data = getTimeRemaining(timestamp);

  /* eslint-disable no-param-reassign */
  endTime.textContent = clockTemplate(data, {
    expiredText: expiredtext,
    date: timestamp,
  });
};

document.addEventListener('DOMContentLoaded', () => {
  const endTimes = document.querySelectorAll('.countdownClock');

  Array.from(endTimes).forEach((endTime) => {
    setInterval(() => renderClock(endTime), 1000);
  });
});
