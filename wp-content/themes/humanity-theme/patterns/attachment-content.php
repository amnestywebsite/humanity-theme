<?php

/**
 * Title: Attachment Content
 * Description: Output the content of an attachment
 * Slug: amnesty/attachment-content
 * Inserter: no
 */

?>
<!-- wp:group {"tagName":"section","className":"article has-sidebar"} -->
<section class="wp-block-group article has-sidebar">
	<!-- wp:group {"tagName":"header","className":"article-header"} -->
	<header class="wp-block-group article-header">
		<!-- wp:pattern {"slug":"amnesty/attachment-metadata"} /-->
		<!-- wp:post-title {"level":1,"className":"article-title"} /-->
	</header>
	<!-- /wp:group -->
	<!-- wp:group {"tagName":"article","className":"article-content"} -->
	<article class="wp-block-group article-content">
		<!-- wp:post-content /-->
	</article>
	<!-- /wp:group -->
<?php if ( get_the_ID() ) : ?>
	<!-- wp:group {"tagName":"footer","className":"article-footer"} -->
	<footer class="wp-block-group article-footer">
		<!-- wp:pattern {"slug":"amnesty/attachment-terms"} /-->
	</footer>
	<!-- /wp:group -->
<?php endif; ?>
</section>
<!-- /wp:group -->
