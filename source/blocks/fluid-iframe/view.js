const init = () => {
  Array.from(
    document.querySelectorAll('.wp-block-embed.is-type-video:not(.responsive-iframe)'),
  ).forEach((embed) => {
    if (embed.parentElement.classList.contains('wp-embed-responsive')) {
      return;
    }

    const wrapper = document.createElement('div');
    wrapper.classList.add('wp-embed-responsive');
    wrapper.appendChild(embed.cloneNode(true));

    embed.replaceWith(wrapper);
  });
};

document.addEventListener('DOMContentLoaded', init);
