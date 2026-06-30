function createPositionAdjuster(article, tooltip) {
  const articleBounds = article.getBoundingClientRect();

  return function (entries) {
    entries.forEach((entry) => {
      tooltip.style.removeProperty('--offset');
      tooltip.style.removeProperty('--embellish-offset');

      // not in view at all
      if (!entry.isIntersecting || !entry.target.classList.contains('is-visible')) {
        return;
      }

      // fully contained within the article bounds
      if (entry.intersectionRatio === 1) {
        return;
      }

      // out of bounds - off left or right?
      const isClippedLeft = entry.boundingClientRect.left < articleBounds.left;
      const isClippedRight = entry.boundingClientRect.right > articleBounds.right;

      // then why is the ratio < 1? :thinking_face:
      if (!isClippedLeft && !isClippedRight) {
        return;
      }

      if (isClippedLeft) {
        const nudgeAmount = entry.boundingClientRect.left - articleBounds.left;
        tooltip.style.setProperty('--offset', `-${nudgeAmount}px`);

        if (nudgeAmount > 0) {
          tooltip.style.setProperty('--embellish-offset', `-${nudgeAmount}px`);
        }

        return;
      }

      if (isClippedRight) {
        const nudgeAmount = entry.boundingClientRect.right - articleBounds.right;
        tooltip.style.setProperty('--offset', `-${nudgeAmount}px`);
        tooltip.style.setProperty('--embellish-offset', `${nudgeAmount}px`);
      }
    });
  };
}

function resetTooltip(tooltip) {
  tooltip.classList.remove('is-visible');
  /* eslint-disable-next-line no-param-reassign */
  tooltip.innerHTML = '';

  if (tooltip.parentElement) {
    tooltip.parentElement.removeChild(tooltip);
  }
}

function createEventHandler(article, references, tooltip) {
  let timeout;

  return function (event) {
    if (timeout) {
      clearTimeout(timeout);
    }

    if (event.target === tooltip || event.target.closest(tooltip.className) === tooltip) {
      return;
    }

    const sup = event.target.parentElement;
    if (!sup || !sup.matches('sup') || !sup.classList.contains('fn')) {
      setTimeout(() => resetTooltip(tooltip), 500);
      return;
    }

    const ref = sup.dataset.fn;
    if (!Object.prototype.hasOwnProperty.call(references, ref)) {
      setTimeout(() => resetTooltip(tooltip), 500);
      return;
    }

    const data = references[ref].innerHTML;
    /* eslint-disable-next-line no-param-reassign */
    tooltip.innerHTML = window.DOMPurify.sanitize(data);

    const ret = tooltip.querySelector(`a[href="#${ref}-link"]`);
    tooltip.removeChild(ret);

    sup.appendChild(tooltip);
    tooltip.classList.add('is-visible');
  };
}

export default function footnoteTooltips() {
  const article = document.querySelector('.article');
  const fns = Array.from(document.querySelectorAll('.article sup.fn'));
  const refItems = Array.from(document.querySelectorAll('.wp-block-footnotes li'));
  const refs = {};

  if (!article || !fns.length || !refItems.length) {
    return;
  }

  refItems.forEach((ref) => {
    refs[ref.id] = ref;
  });

  const tooltip = document.createElement('span');
  tooltip.classList.add('footnote-tooltip');

  const observer = new IntersectionObserver(createPositionAdjuster(article, tooltip), {
    root: article,
  });

  observer.observe(tooltip);

  article.addEventListener('mouseover', createEventHandler(article, refs, tooltip));
}
