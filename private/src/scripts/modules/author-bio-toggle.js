export default function authorBioToggle() {
  const buttonElement = document.getElementById('author-read-more');
  const textContainer = document.querySelector('.author-biography');

  if (!buttonElement || !textContainer) {
    return;
  }

  const textContainerHeight = textContainer.clientHeight;

  if (textContainerHeight < 300) {
    buttonElement.style.display = 'none';
  }

  const removeClass = () => {
    textContainer.classList.remove('is-collapsed');
    buttonElement.style.display = 'none';
  };

  buttonElement.addEventListener('click', removeClass);
}
