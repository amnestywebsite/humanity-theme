<?php

/**
 * Title: Post Published Date
 * Description: Output the published date metadata for a post
 * Slug: amnesty/post-published-date
 * Inserter: no
 */

if ( ! amnesty_validate_boolish( get_post_meta( get_the_ID(), 'show_published_date', true ) ) ) {
	return;
}

?>
<!-- wp:group {"tagName":"div","className":"article-metaDataRow"} -->
<div class="wp-block-group article-metaDataRow">
<?php do_action( 'amnesty_before_published_date' ); ?>
<!-- wp:post-date {"className":"publishedDate"} /-->
<?php do_action( 'amnesty_after_published_date' ); ?>
</div>
<!-- /wp:group -->
