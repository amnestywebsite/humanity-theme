<?php

/**
 * Title: Author sidebar
 * Description: Template for the sidebar on author pages
 * Slug: amnesty/author-sidebar
 * Inserter: no
 */

$twitter_handle = get_the_author_meta( 'twitter' );
// translators: %s: the author's Twitter handle
$twitter_link_text = sprintf( __( 'Tweets by %s', 'amnesty' ), $twitter_handle );

if ( ! $twitter_handle ) {
	return;
}

?>
<!-- wp:group {"tagName":"aside","className":"twitter-sidebar"} -->
<aside class="twitter-sidebar">
	<a class="twitter-timeline" href="https://twitter.com/<?php echo esc_attr( $twitter_handle ); ?>?ref_src=twsrc%5Etfw" data-tweet-limit="3" data-width="500">Tweets by <?php echo esc_html( $twitter_handle ); ?></a>
	<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</aside>
<!-- /wp:group -->
