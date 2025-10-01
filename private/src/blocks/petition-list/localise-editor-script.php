<?php

return [
	'aiPostTypeCodenames' => array_flip(
		array_map(
			fn ( WP_Post_Type $type ): string => $type?->codename ?? $type->name,
			get_post_types( output: 'objects' ),
		),
	),
];
