<?php

/**
 * Post single partial, "updated" date
 *
 * @package Amnesty\Partials
 */

if ( ! amnesty_validate_boolish( get_post_meta( get_the_ID(), 'show_updated_date', true ) ) ) {
	return;
}

$post_updated = get_post_meta( get_the_ID(), 'amnesty_updated', true );

if ( $post_updated ) {
	$post_updated = amnesty_locale_date( strtotime( $post_updated ) );
}

if ( ! $post_updated ) {
	return;
}

?>

<span class="updatedDate" aria-label="<?php /* translators: [front] */ esc_attr_e( 'Post updated timestamp', 'amnesty' ); ?>">
	<strong><?php echo esc_html( _x( 'Updated:', 'prefix for post updated date', 'amnesty' ) ); ?></strong>&nbsp;<time><?php echo esc_html( $post_updated ); ?></time>
</span>
