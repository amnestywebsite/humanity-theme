<?php

/**
 * Title: Post Metadata
 * Description: Output contextual data for a post
 * Slug: amnesty/post-metadata
 * Inserter: no
 */

// prevent weird output in the site editor
if ( ! get_the_ID() ) {
	return;
}

$show_back_link    = ! amnesty_validate_boolish( amnesty_get_option( '_display_category_label' ) );
$show_share_icons  = ! amnesty_validate_boolish( get_post_meta( get_the_ID(), '_disable_share_icons', true ) );
$show_byline       = amnesty_validate_boolish( get_post_meta( get_the_ID(), '_display_author_info', true ) );
$show_publish_date = amnesty_validate_boolish( get_post_meta( get_the_ID(), 'show_published_date', true ) );
$show_updated_date = amnesty_validate_boolish( get_post_meta( get_the_ID(), 'show_updated_date', true ) );
$main_category     = amnesty_get_a_post_term( get_the_ID() );

$show_top_row    = ( $main_category && $show_back_link ) || $show_share_icons;
$show_bottom_row = $show_byline || $show_publish_date || $show_updated_date;

?>
<!-- wp:group {"tagName":"div","className":"article-meta"} -->
<div class="wp-block-group article-meta">
<?php if ( $show_top_row ) : ?>
	<!-- wp:group {"tagName":"div","className":"article-metaActions"} -->
	<div class="wp-block-group article-metaActions">
		<!-- wp:pattern {"slug":"amnesty/post-back-link"} /-->
		<!-- wp:pattern {"slug":"amnesty/post-share"} /-->
	</div>
	<!-- /wp:group -->
<?php endif; ?>

<?php if ( $show_bottom_row ) : ?>
	<!-- wp:group {"tagName":"div","className":"article-metaData"} -->
	<div class="wp-block-group article-metaData">
		<!-- wp:group {"tagName":"div"} -->
		<div class="wp-block-group">
			<!-- wp:pattern {"slug":"amnesty/post-byline"} /-->
			<!-- wp:pattern {"slug":"amnesty/post-published-date"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:pattern {"slug":"amnesty/post-updated-date"} /-->
	</div>
	<!-- /wp:group -->
<?php endif; ?>
</div>
<!-- /wp:group -->
