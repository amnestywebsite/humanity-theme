import { __ } from '@wordpress/i18n';

import LinkList from './display/LinkList.jsx';
import GridItem from './display/GridItem.jsx';
import PetitionItem from './display/PetitionItem.jsx';

const SelectPreview = ({ loading, posts = [], ...props }) => {
  if (loading) {
    /* translators: [admin] */
    return <p>{__('Loading…', 'amnesty')}</p>;
  }

  if (!posts.length > 0) {
    /* translators: [admin] */
    return <p>{__('No Posts.', 'amnesty')}</p>;
  }

  if (props.style === 'grid') {
    return posts.length % 4 === 0 ? (
      <div className="grid grid-many">
        {posts.map((result) => (
          <GridItem key={`${props.prefix}-${result.id}`} {...result} />
        ))}
      </div>
    ) : (
      <div className={`grid grid-${posts.length}`}>
        {posts.map((result) => (
          <GridItem key={`${props.prefix}-${result.id}`} {...result} />
        ))}
      </div>
    );
  }

  if (props.style === 'petition') {
    return (
      <div className="grid grid-many petition-grid">
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
