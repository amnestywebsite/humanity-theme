<?php

// Prevent a bug in the admin panel where the editor
// shows a different post if the list item is selected
// using one of the selection methods.
if ( is_admin() ) {
	return;
}

if ( doing_filter( 'get_the_excerpt' ) ) {
	return;
}

$data = amnesty_list_process_content( $attributes );

if ( ! $data ) {
	return;
}

if ( ! isset( $attributes['style'] ) || 'grid' !== $attributes['style'] ) {
	print '<ul class="linkList">';
	array_map( 'amnesty_render_list_item', $data );
	print '</ul>';
	return;
}

if ( 0 !== count( $data ) % 4 ) {
	printf( '<div class="grid grid-%s post-list">', esc_attr( count( $data ) ) );
	array_map( 'amnesty_render_grid_item', $data );
	print '</div>';
	return;
}

printf( '<div class="grid grid-many">' );
array_map( 'amnesty_render_grid_item', $data );
print '</div>';
