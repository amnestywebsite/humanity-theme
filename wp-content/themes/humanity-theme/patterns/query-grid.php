<?php
/**
 * Title: Query Grid
 * Description: Query grid with a heading, image, and pagination.
 * Slug: amnesty/query-grid
 * Keywords: query, grid, post, list
 * Categories: humanity-posts
 * Block Types: core/query
 */
?>

<!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"taxQuery":{"category":[1148],"location":[1711]}}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":10,"isDark":false,"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-10 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:post-terms {"term":"category"} /-->

<!-- wp:post-title {"isLink":true} /--></div></div>
<!-- /wp:cover -->
<!-- /wp:post-template -->

<!-- wp:query-pagination -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
<p></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->
