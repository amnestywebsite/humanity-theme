<?php

/**
 * Global fallback template
 *
 * @package Amnesty\Templates
 */

get_header();
the_post();

?>
<main id="main">
	<?php the_content(); ?>
</main>
<?php get_footer(); ?>
