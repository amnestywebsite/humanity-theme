<?php

/**
 * Title: Search pattern
 * Description: Search pattern for the theme
 * Slug: amnesty/search
 * Inserter: no
 */

?>

<!-- wp:group {"tagName":"div","className":"container search-container has-gutter"} -->
<div class="wp-block-group container search-container has-gutter">
<!-- wp:amnesty-core/search-form /-->

<!-- wp:group {"tagName":"div","className":"section search-results section--tinted"} -->
<div class="wp-block-group section search-results section--tinted">

<!-- wp:pattern {"slug":"amnesty/archive-header"} /-->



<?php
do_action( 'amnesty_before_search_results' );

?>

<!-- wp:query {"inherit":true} -->
<!-- wp:post-template -->

<!-- wp:amnesty-core/post-search /-->

<!-- /wp:post-template -->
<!-- /wp:query -->

<?php

do_action( 'amnesty_after_search_results' );

?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
