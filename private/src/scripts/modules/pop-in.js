import Cookies from 'js-cookie';

const { delay } = lodash;

let globalData;

const hidePopIn = (element) => {
  const parent = element.parentElement;
  parent.parentElement.removeChild(parent);
};

const showPopIn = (element) => {
  element.parentElement.setAttribute('aria-hidden', 'false');
  element.parentElement.classList.remove('is-closed');
};

const closePopIn = (event) => {
  event.preventDefault();

  hidePopIn(event.target);

  Cookies.set('amnesty_pop_in_dismissed', '1', {
    domain: globalData.domain,
    expires: parseInt(globalData.pop_in_timeout, 10) || 30,
    sameSite: 'strict',
    secure: true,
  });
};

export default function popIn() {
  const close = document.getElementById('pop-in-close');
  if (!close) {
    return;
  }

  globalData = window.amnesty_data;

  if (Cookies.get('amnesty_pop_in_dismissed') === '1') {
    hidePopIn(close);
    return;
  }

  delay(showPopIn, 100, close);

  close.addEventListener('click', closePopIn);
  close.addEventListener('touchend', closePopIn);
}
