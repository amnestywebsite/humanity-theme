<?php

declare( strict_types = 1 );

// when the object cache for a post is cleared, purge the WPE varnish cache
add_action(
	'clean_post_cache',
	function ( int $post_id ): void {
		if ( class_exists( 'WpeCommon' ) ) {
			WpeCommon::purge_varnish_cache( $post_id );
		}
	},
);
