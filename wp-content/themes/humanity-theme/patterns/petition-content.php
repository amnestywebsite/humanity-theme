<?php

/**
 * Title: Petition Content
 * Description: Output the content of a single petition
 * Slug: amnesty/petition-content
 * Inserter: no
 */

?>

<?php if ( get_transient( 'amnesty_petitions_errors' ) ) : ?>
<!-- wp:group {"tagName":"div","className":"section section--small container u-textCenter"} -->
<div class="section section--small container u-textCenter">
	<?php do_action( 'amnesty_petitions_errors' ); ?>
</div>
<!-- /wp:group -->
<?php endif; ?>

<!-- wp:group {"tagName":"div","className":"section section--small container article-container"} -->
<div class="section section--small container article-container">
	<!-- wp:post-content /-->
</div>
<!-- /wp:group -->
