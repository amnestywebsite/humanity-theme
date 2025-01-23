const { isInteger } = lodash;
const { currentLocale = 'en-GB', enforceGroupingSeparators } = window.amnestyCoreI18n;

// ensure value is an int
const toRawNumber = (value = '0') => {
  if (isInteger(value)) {
    return value;
  }

  const trimmed = value.replace(/[^\d]/g, '');
  const inted = parseInt(trimmed, 10);

  return inted;
};

// format a value as a locale-aware number
const toFormattedString = (value) => {
  if (!value) {
    return '';
  }

  const options = {};

  if (enforceGroupingSeparators) {
    options.useGrouping = true;
  }

  const formatted = toRawNumber(value).toLocaleString(currentLocale.replace('_', '-'), options);

  return formatted;
};

// animate an element's value from 0 to its end result
// eslint-disable-next-line default-param-last
const countUp = (target, end, duration = 2000, observerObj) => {
  let current = 0;
  let progress = 0;
  let startTime = 0;

  const step = (timestamp) => {
    if (!startTime) {
      startTime = timestamp;
    }
    progress = Math.min((timestamp - startTime) / duration, 1);
    current = Math.floor(progress * end);
    // eslint-disable-next-line no-param-reassign
    target.textContent = toFormattedString(current);
    target.setAttribute('style', `opacity: ${progress}`);

    if (progress < 1) {
      requestAnimationFrame(step);
    }

    // this one's done now.
    if (progress === 1) {
      observerObj.unobserve(target);
    }
  };

  requestAnimationFrame(step);
};

// animate once the element's well into view
const observer = new IntersectionObserver(
  (entries, observerObj) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) {
        return;
      }

      if (entry.intersectionRatio < 1) {
        return;
      }

      const { target } = entry;
      const { duration } = target.dataset;
      const end = toRawNumber(target.dataset.value);
      countUp(target, end, duration * 1000, observerObj);
    });
  },
  {
    rootMargin: '0px 0px -200px 0px',
    threshold: [1],
  },
);

export default () => {
  const elems = document.querySelectorAll('.wp-block-amnesty-core-counter');

  Array.from(elems).forEach((elem) => {
    // doing this in JS so that it's still visible if the JS doesn't work
    elem.setAttribute('style', 'opacity: 0');
    observer.observe(elem);
  });
};
