<?php

/**
 * Title: Post Content
 * Description: Output the content of a single post
 * Slug: amnesty/post-content
 * Inserter: no
 */

$max_post_content    = amnesty_validate_boolish( get_post_meta( get_the_ID(), '_maximize_post_content', true ) );
$article_has_sidebar = $max_post_content ? '' : 'has-sidebar';

?>
<!-- wp:group {"tagName":"section","className":"article <?php echo esc_attr( $article_has_sidebar ); ?>"} -->
<section class="wp-block-group article <?php echo esc_attr( $article_has_sidebar ); ?>">
	<!-- wp:group {"tagName":"header","className":"article-header"} -->
	<header class="wp-block-group article-header">
		<!-- wp:pattern {"slug":"amnesty/post-metadata"} /-->
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
		<!-- wp:pattern {"slug":"amnesty/post-terms"} /-->
		<!-- wp:pattern {"slug":"amnesty/related-content"} /-->
	</footer>
	<!-- /wp:group -->
<?php endif; ?>
</section>
<!-- /wp:group -->
