<?php

/**
 * Post partial, no image
 *
 * @package Amnesty\Partials
 */

// translators: [front] %s: the title of the article
$aria_label = sprintf( __( 'Article: %s', 'amnesty' ), format_for_aria_label( get_the_title() ) );

?>
<article class="post postImage--none" aria-label="<?php echo esc_attr( $aria_label ); ?>">
	<?php get_template_part( 'partials/post/post', 'content' ); ?>
</article>
