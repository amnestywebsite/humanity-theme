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

const CLOSE_DELAY = 400;
function createEventHandler(article, references, tooltip) {
  let timeout;

  return function (event) {
    if (timeout) {
      clearTimeout(timeout);
    }

    // mouse has entered the tooltip bounding box - do nothing, so that it stays open
    if (event.target === tooltip || tooltip.contains(event.target)) {
      return;
    }

    // attempt to locate the target superscript
    let sup = event.target;
    if (!event.target.matches('sup.fn')) {
      sup = event.target.closest('sup.fn');
    }

    // mouse is within the sup containing the tooltip - do nothing, so that it stays open
    if (sup && sup.querySelector(tooltip.className)) {
      return;
    }

    // mouse is within an unusable element - remove the tooltip, and bail
    if (!sup) {
      timeout = setTimeout(() => resetTooltip(tooltip), CLOSE_DELAY);
      return;
    }

    // mouse is within a superscript we need to insert the tooltip into
    const ref = sup.dataset.fn;
    // but there's no usable list item - remove the tooltip, and bail
    if (!Object.prototype.hasOwnProperty.call(references, ref)) {
      timeout = setTimeout(() => resetTooltip(tooltip), CLOSE_DELAY);
      return;
    }

    // now we're cooking.
    // get the reference HTML, insert it into the tooltip,
    // and insert the tooltip into the superscript.
    // then show the tooltip.

    const data = references[ref].innerHTML;
    /* eslint-disable-next-line no-param-reassign */
    tooltip.innerHTML = window.DOMPurify.sanitize(data);

    // strip out the jump link
    const ret = tooltip.querySelector(`a[href="#${ref}-link"]`);
    tooltip.removeChild(ret);

    // insert the tooltip into the superscript, but outside of its anchor
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

  const handler = createEventHandler(article, refs, tooltip);
  article.addEventListener('mouseover', handler);
  article.addEventListener('focusin', handler);
  article.addEventListener('keydown', ({ key }) => {
    if (['Esc', 'Escape'].includes(key)) {
      resetTooltip(tooltip);
    }
  });
}
