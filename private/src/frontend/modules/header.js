let header;

// previous scrollY cache
let previousKnown = 0;
// toggle sticky class on header
const maybeStick = (position) => {
  // do nothing
  if (position === previousKnown) {
    return;
  }

  // don't interfere with stateful changes
  if (document.documentElement.className.indexOf('-open') !== -1) {
    return;
  }

  // we're at the top
  if (position === 0) {
    header.classList.remove('stick');
    return;
  }

  if (position > previousKnown) {
    // scrolling down, unstick
    header.classList.remove('stick');
  } else {
    // scrolling up, stick
    header.classList.add('stick');
  }

  previousKnown = position;
};

// scroll handler values cache
let lastKnown = 0;
let ticking = false;

const handleScrollEvents = () => {
  lastKnown = window.scrollY;

  if (!ticking) {
    window.requestAnimationFrame(() => {
      maybeStick(lastKnown);
      ticking = false;
    });

    ticking = true;
  }
};

const init = () => {
  header = document.querySelector('.page-header');

  if (!header) {
    return;
  }

  document.addEventListener('scroll', handleScrollEvents);
};

export default init;
