import { BlockIcon } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { PostList } from './PostList.jsx';
import { randId } from '../../../../utils';

function SearchBox({ value, onChange }) {
  return (
    <div className="searchbox">
      <label htmlFor="searchinput">
        <BlockIcon icon="search" />
        <input
          id="searchinput"
          type="search"
          /* translators: [admin] */
          placeholder={__('Please enter your search query…', 'amnesty')}
          value={value || ''}
          onChange={onChange}
        />
      </label>
    </div>
  );
}

function PostTypeOptions({ options, onChange }) {
  const id = `${randId()}-post-selector-post-type`;

  const label = (
    <label htmlFor={id}>{/* translators: [admin] */ __('Post Type:', 'amnesty')}&nbsp;</label>
  );

  if (!options?.length) {
    return (
      <>
        <select id={id} name={id} onChange={onChange}>
          {/* translators: [admin] */}
          <option value="">{__('Loading…', 'amnesty')}</option>
        </select>
      </>
    );
  }

  return (
    <>
      {label}
      <select id={id} name={id} onChange={onChange}>
        {Object.keys(options).map((key) => (
          <option key={key} value={key}>
            {options[key].name}
          </option>
        ))}
      </select>
    </>
  );
}

function TaxonomyOptions({ options, onChange }) {
  const id = `${randId()}-post-selector-taxonomy`;

  const label = (
    <label htmlFor={id}>{/* translators: [admin] */ __('Taxonomy:', 'amnesty')}&nbsp;</label>
  );

  if (!options?.length) {
    return (
      <>
        {label}
        <select id={id} name={id} onChange={onChange}>
          {/* translators: [admin] */}
          <option value="">{__('Loading…', 'amnesty')}</option>
        </select>
      </>
    );
  }

  return (
    <>
      {label}
      <select id={id} name={id} onChange={onChange}>
        <option value="">{/* translators: [admin] */ __('Select Taxonomy', 'amnesty')}</option>
        {Object.keys(options).map((key) => (
          <option key={key} value={options[key].rest_base}>
            {options[key].name}
          </option>
        ))}
      </select>
    </>
  );
}

function TermOptions({ options, onChange }) {
  const id = `${randId()}-post-selector-terms`;

  const label = (
    <label htmlFor={id}>{/* translators: [admin] */ __('Terms:', 'amnesty')}&nbsp;</label>
  );

  if (!options?.length) {
    return (
      <>
        {label}
        <select id={id} name={id} onChange={onChange}>
          {/* translators: [admin] */}
          <option value="">{__('No taxonomy selected', 'amnesty')}</option>
        </select>
      </>
    );
  }

  return (
    <>
      <select id={id} name={id} onChange={onChange}>
        <option value="">{/* translators: [admin] */ __('Select Term', 'amnesty')}</option>
        {Object.keys(options).map((key) => (
          <option key={key} value={options[key].id}>
            {options[key].name}
          </option>
        ))}
      </select>
    </>
  );
}

const PostSelector = (props) => {
  const isFiltered = props.state.filtering;

  const postList =
    isFiltered && !props.state.filterLoading
      ? props.state.filterPosts
      : props.state.posts.filter((post) => post.type === props.state.type);

  const canPaginate = (props.state.pages || 1) < props.state.pagesTotal;

  const addIcon = props.getSelectedPosts().length >= 100 ? null : <BlockIcon icon="plus" />;
  const removeIcon = <BlockIcon icon="minus" />;

  return (
    <div className="wp-block-bigbite-postlist">
      <div className="post-selector">
        <div className="post-selectorHeader">
          <SearchBox value={props.state.filter} onChange={props.handleInputFilterChange} />
          <div className="filter">
            <PostTypeOptions options={props.state.types} onChange={props.handlePostTypeChange} />
            <TaxonomyOptions
              options={props.state.taxonomies}
              onChange={props.handleTaxonomyChange}
            />
            <TermOptions options={props.state.terms} onChange={props.handleTermChange} />
          </div>
        </div>
        <div className="post-selectorContainer">
          <PostList
            posts={postList}
            loading={props.state.initialLoading || props.state.loading || props.state.filterLoading}
            filtered={isFiltered}
            action={props.addPost}
            paging={props.state.paging}
            canPaginate={canPaginate}
            doPagination={props.doPagination}
            icon={addIcon}
          />
          <PostList
            posts={props.getSelectedPosts()}
            loading={props.state.initialLoading}
            action={props.removePost}
            icon={removeIcon}
            reorderPosts={props.reorderPosts}
          />
        </div>
      </div>
    </div>
  );
};

export default PostSelector;
