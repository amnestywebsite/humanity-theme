const handleClickEvents = (event) => {
  if (!event.target.classList.contains('tabs-nav-trigger')) {
    return;
  }

  event.preventDefault();

  const wrapper = event.target.closest('.tabs');
  const { content } = event.target.dataset;

  if (!wrapper || !content) {
    return;
  }

  const navItems = Array.from(wrapper.getElementsByClassName('tabs-nav-trigger'));

  if (!navItems.length) {
    return;
  }

  navItems.forEach((navItem) => {
    navItem.classList.remove('is-selected');
  });

  event.target.classList.add('is-selected');

  const items = Array.from(wrapper.getElementsByClassName('tab-content'));

  if (!items.length) {
    return;
  }

  items.forEach((item) => {
    item.classList.remove('is-selected');

    if (item.getAttribute('aria-hidden') !== null) {
      item.setAttribute('aria-hidden', item.dataset.content === content ? 'false' : 'true');
    }

    if (item.dataset.content === content) {
      item.classList.add('is-selected');
    }
  });
};

export default function init() {
  document.addEventListener('click', handleClickEvents);
}
