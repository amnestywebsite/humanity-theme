import LinkList from './display/LinkList.jsx';
import GridItem from './display/GridItem.jsx';
import PetitionItem from './display/PetitionItem.jsx';

import { __ } from '@wordpress/i18n';

const SelectPreview = ({ loading, posts = [], ...props }) => {
  if (loading) {
    // translators: [admin]
    return <p>{__('Loading…', 'amnesty')}</p>;
  }

  if (!posts.length > 0) {
    // translators: [admin]
    return <p>{__('No Posts.', 'amnesty')}</p>;
  }

  if (props.style === 'grid') {
    return [1, 2, 3, 5, 6, 7].indexOf(posts.length) > -1 ? (
      <div className={`grid grid-${posts.length}`}>
        {posts.map((result) => (
          <GridItem key={`${props.prefix}-${result.id}`} {...result} />
        ))}
      </div>
    ) : (
      <div className={`grid grid-many`}>
        {posts.map((result) => (
          <GridItem key={`${props.prefix}-${result.id}`} {...result} />
        ))}
      </div>
    );
  }

  if (props.style === 'petition') {
    return (
      <div className={`grid grid-many petition-grid`}>
        {posts.map((result) => (
          <PetitionItem key={`${props.prefix}-${result.id}`} {...result} />
        ))}
      </div>
    );
  }

  return (
    <ul className="linkList">
      {posts.map((result) => (
        <LinkList
          key={`${props.prefix}-${result.id}`}
          {...result}
          showAuthor={props.showAuthor}
          showPostDate={props.showPostDate}
        />
      ))}
    </ul>
  );
};

export default SelectPreview;
