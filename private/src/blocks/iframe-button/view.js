import './style.scss';

const toggle = (event) => {
  event.target.parentElement.classList.toggle('is-visible');
};

const init = (button) => {
  button.addEventListener('click', toggle);
};

document.addEventListener('DOMContentLoaded', () => {
  Array.from(document.querySelectorAll('.iframeButton')).forEach(init);
});
