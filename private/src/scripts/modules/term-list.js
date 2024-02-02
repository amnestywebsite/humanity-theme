const showCorrectList = (target) => {
  const letter = target.textContent;
  const block = target.parentElement.parentElement; // block container
  const lists = block.querySelectorAll('.listItems');
  const activeLetter = block.querySelector('.activeLetter');

  activeLetter.innerText = letter;

  Array.from(lists).forEach((list) => {
    const display = list.dataset.key === letter ? 'flex' : 'none';
    list.setAttribute('style', `display:${display}`);
  });
};

const handleBlockClickEvents = (event) => {
  const { target } = event;
  const { nodeName } = target;

  if (nodeName !== 'BUTTON') {
    return;
  }

  if (target.getAttribute('disabled')) {
    return;
  }

  const buttons = target.parentElement.getElementsByTagName('button');
  Array.from(buttons).forEach((button) => {
    button.setAttribute('class', '');
  });

  target.setAttribute('class', 'is-active');
  showCorrectList(target);
};

export default function init() {
  const elements = document.querySelectorAll('.wp-block-amnesty-core-term-list');

  if (!elements) {
    return;
  }

  Array.from(elements).forEach((item) => {
    item.addEventListener('click', handleBlockClickEvents);
  });
}
