import { getFeaturedImage } from '../utils';

import { __ } from '@wordpress/i18n';

/**
 * Render an item within the block
 *
 * @param {Object} param0 the props passed in
 *
 * @returns {wp.element.Component}
 */
const GridItem = ({ item, onClick }) => {
  const image = getFeaturedImage(item, 'post-half@2x');
  const style = {};

  if (image) {
    style.backgroundImage = `url('${image}')`;
  }

  return (
    <article id={`${item.type}-id-${item.id}`} className="grid-item" style={style}>
      {item.tagText && item.tagLink && (
        <span className="grid-itemMeta">
          <a href={item.tagLink} rel="noopener noreferer" onClick={onClick}>
            {item.tagText}
          </a>
        </span>
      )}
      {item.title && (
        <h3 className="grid-itemTitle">
          <a href={item.titleLink || '#'} rel="noopener noreferer" onClick={onClick}>
            {item.title}
          </a>
        </h3>
      )}
    </article>
  );
};

export default GridItem;
