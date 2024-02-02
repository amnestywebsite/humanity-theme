import { PostList } from './PostList.jsx';

const { BlockIcon } = wp.blockEditor;
const { __ } = wp.i18n;

const PostSelector = (props) => {
  const isFiltered = props.state.filtering;

  const postList =
    isFiltered && !props.state.filterLoading
      ? props.state.filterPosts
      : props.state.posts.filter((post) => post.type === props.state.type);

  const pageKey = props.state.filter ? 'filter' : props.state.type;
  const canPaginate = (props.state.pages[pageKey] || 1) < props.state.pagesTotal[pageKey];

  const addIcon = props.getSelectedPosts().length >= 100 ? null : <BlockIcon icon="plus" />;
  const removeIcon = <BlockIcon icon="minus" />;

  return (
    <div className="wp-block-bigbite-postlist">
      <div className="post-selector">
        <div className="post-selectorHeader">
          <div className="searchbox">
            <label htmlFor="searchinput">
              <BlockIcon icon="search" />
              <input
                id="searchinput"
                type="search"
                // translators: [admin]
                placeholder={__('Please enter your search query…', 'amnesty')}
                value={props.state.filter}
                onChange={props.handleInputFilterChange}
              />
            </label>
          </div>
          <div className="filter">
            <label htmlFor="options">
              {/* translators: [admin] */ __('Post Type:', 'amnesty')}{' '}
            </label>
            <select name="options" id="options" onChange={props.handlePostTypeChange}>
              {props.state.types.length < 1 ? (
                // translators: [admin]
                <option value="">{__('Loading…', 'amnesty')}</option>
              ) : (
                Object.keys(props.state.types).map((key) => (
                  <option key={key} value={key}>
                    {props.state.types[key].name}
                  </option>
                ))
              )}
            </select>
            <label htmlFor="options">
              {/* translators: [admin] */ __('Taxonomy:', 'amnesty')}{' '}
            </label>
            <select name="options" id="options" onChange={props.handleTaxonomyChange}>
              {props.state.taxonomies.length < 1 ? (
                // translators: [admin]
                <option value="">{__('Loading…', 'amnesty')}</option>
              ) : (
                Object.keys(props.state.taxonomies).map((key, index) => {
                  if (index === 0) {
                    return (
                      <>
                        <option value="">
                          {/* translators: [admin] */ __('Select Taxonomy', 'amnesty')}
                        </option>
                        <option key={key} value={props.state.taxonomies[key].rest_base}>
                          {props.state.taxonomies[key].name}
                        </option>
                      </>
                    );
                  }
                  return (
                    <option key={key} value={props.state.taxonomies[key].rest_base}>
                      {props.state.taxonomies[key].name}
                    </option>
                  );
                })
              )}
            </select>
            <label htmlFor="options">{/* translators: [admin] */ __('Terms:', 'amnesty')} </label>
            <select name="options" id="options" onChange={props.handleTermChange}>
              {props.state.terms.length < 1 ? (
                // translators: [admin]
                <option value="">{__('No taxonomy selected', 'amnesty')}</option>
              ) : (
                Object.keys(props.state.terms).map((key, index) => {
                  if (index === 0) {
                    return (
                      <>
                        <option value="">
                          {/* translators: [admin] */ __('Select Term', 'amnesty')}
                        </option>
                        <option key={key} value={props.state.terms[key].id}>
                          {props.state.terms[key].name}
                        </option>
                      </>
                    );
                  }
                  return (
                    <option key={key} value={props.state.terms[key].id}>
                      {props.state.terms[key].name}
                    </option>
                  );
                })
              )}
            </select>
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
