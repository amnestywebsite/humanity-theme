<?php

/**
 * Title: Post Byline
 * Description: Output the public byline for a post
 * Slug: amnesty/post-byline
 * Inserter: no
 */

$enabled = get_post_meta( get_the_ID(), '_display_author_info', true );

if ( ! $enabled ) {
	return;
}

?>
<!-- wp:pattern {"slug":"amnesty/post-byline-author"} /-->
<!-- wp:pattern {"slug":"amnesty/post-byline-custom"} /-->
