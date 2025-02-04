import Flickity from 'flickity';

const handleStaticClick = (flcktyInstance) => (event, pointer, cellElement, cellIndex) => {
  if (!cellElement) {
    return;
  }

  const viewport = cellElement.closest('.flickity-viewport');

  if (viewport) {
    if (cellIndex > 0) {
      // viewport.nextElementSibling refers flickity PREVIOUS naviation arrow
      viewport.nextElementSibling.disabled = false;
    } else {
      viewport.nextElementSibling.disabled = true;
    }

    if (cellIndex === flcktyInstance.slides.length - 1) {
      // viewport.nextElementSibling.nextElementSibling refers flickity NEXT naviation arrow
      viewport.nextElementSibling.nextElementSibling.disabled = true;
    } else {
      viewport.nextElementSibling.nextElementSibling.disabled = false;
    }
  }
};

const handleChange = (flcktyInstance) => () => {
  // Makes array of all tabPanels and their content
  const tabContent = Array.from(flcktyInstance.element.nextElementSibling.children);
  // Removes "tab-" from element id
  const currentTabId = flcktyInstance.selectedElement.id.slice(4);

  tabContent.forEach((content) => {
    // If tabPanel includes current tab id, add is-selected-tab class and change aria-expanded to true.
    // else, remove class and change aria to false
    if (content.id.includes(currentTabId)) {
      content.classList.add('is-selected-tab');
      content.setAttribute('aria-expanded', 'true');
    } else {
      content.classList.remove('is-selected-tab');
      content.setAttribute('aria-expanded', 'false');
    }
  });
};

const addFlickityToTabs = () => {
  const FlickityContainer = document.querySelectorAll('.wp-block-bigbite-tabs .tabsContainer');

  FlickityContainer.forEach((element) => {
    const slides = Array.from(element.nextElementSibling.children);
    const slideCount = slides.length;

    // If tabs block has 3 or less slides adds a custom class
    if (slideCount <= 3) {
      element.classList.add('has-no-nav-arrows');
      return;
    }

    // Only adds flickity if slider has more than 3 slides
    const flcktyInstance = new Flickity(element, {
      cellAlign: 'left',
      pageDots: false,
    });

    setTimeout(() => {
      const urlHash = window.location.hash.replace('#', '');

      if (!urlHash) {
        return;
      }

      const initiallySelectedSlide = document.getElementById(urlHash);

      if (!initiallySelectedSlide) {
        return;
      }

      const items = Array.from(initiallySelectedSlide.parentElement.children);
      const index = items.indexOf(initiallySelectedSlide);

      if (index === -1) {
        return;
      }

      flcktyInstance.select(index, false, true);
    }, 1);

    flcktyInstance.on('staticClick', handleStaticClick(flcktyInstance));
    flcktyInstance.on('change', handleChange(flcktyInstance));
  });
};

export default addFlickityToTabs;
