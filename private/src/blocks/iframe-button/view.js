import './style.scss';

const toggle = (event) => {
  event.target.parentElement.classList.toggle('is-visible');
};

const init = (button) => {
  button.addEventListener('click', toggle);
};

export default () => {
  Array.from(document.querySelectorAll('.iframeButton')).forEach(init);
};
