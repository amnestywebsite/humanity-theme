<?php

/**
 * Title: Sidebar Pattern
 * Description: Sidebar pattern for the theme
 * Slug: amnesty/sidebar
 * Inserter: yes
 */

if ( amnesty_get_meta_field( '_disable_sidebar' ) === '1' ) {
	return;
}

$sidebar_id = amnesty_get_meta_field( '_sidebar_id' );

if ( ! $sidebar_id || 0 === $sidebar_id ) {
	$sidebar_key = is_page() ? '_default_sidebar_page' : '_default_sidebar';
	$sidebar_id  = amnesty_get_option( $sidebar_key );
}

if ( $sidebar_id && is_array( $sidebar_id ) ) {
	$sidebar_id = array_shift( $sidebar_id );
}

if ( ! $sidebar_id ) {
	return;
}

$query = new WP_Query(
	[
		'post__in'      => [ $sidebar_id ],
		'post_type'     => 'sidebar',
		'no_found_rows' => true,
	]
);

if ( ! $query->have_posts() ) {
	return;
}

?>

<!-- wp:group {"tagName":"aside","className":"article-sidebar"} -->
<aside class="wp-block-group article-sidebar">
<?php while ( $query->have_posts() ) : ?>
	<?php $query->the_post(); ?>
	<?php get_the_content(); ?>
<?php endwhile; ?>
</aside>
<!-- /wp:group -->

<?php

wp_reset_postdata();
