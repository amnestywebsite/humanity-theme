const init = (block) => (event) => {
  if (event.target.matches('.btn--blank')) {
    block.classList.toggle('is-collapsed');
  }
};

const collapsableBlock = () => {
  const blocks = Array.from(document.getElementsByClassName('wp-block-amnesty-core-collapsable'));
  blocks.forEach((block) => block.addEventListener('click', init(block)));
};

export default collapsableBlock;
