<?php

declare( strict_types = 1 );

register_rest_field(
	'post',
	'authorName',
	[
		'object_subtype' => 'post',
		'get_callback'   => function ( $post ) {
			return get_the_author_meta( 'display_name', $post['author'] );
		},
	]
);

register_rest_field(
	'post',
	'datePosted',
	[
		'object_subtype' => 'post',
		'get_callback'   => function () {
			return get_the_date();
		},
	]
);
