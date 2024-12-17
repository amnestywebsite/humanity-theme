<?php

if ( ! isset( $block->context['postId'], $attributes['metaKey'] ) ) {
	return '';
}

$post_id = $block->context['postId'];
$classes = [];

if ( isset( $attributes['textAlign'] ) ) {
	$classes[] = 'has-text-align-' . $attributes['textAlign'];
}

if ( isset( $attributes['style']['elements']['link']['color']['text'] ) ) {
	$classes[] = 'has-link-color';
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => implode( ' ', $classes ) ] );

$meta_value = get_post_meta( $post_id, $attributes['metaKey'], $attributes['isSingle'] ?? true );
$metadata   = [];

if ( $attributes['isSingle'] ?? true ) {
	$meta_value = [ $meta_value ];
}

$meta_value = apply_filters(
	'amnesty_core_post_meta_meta_value',
	$meta_value,
	$post_id,
	$block->context['postType'],
	$attributes['metaKey'],
);

if ( $attributes['isLink'] ?? false ) {
	foreach ( $meta_value as $row ) {
		$metadata[] = sprintf( '<a href="%1s">%2s</a>', get_the_permalink( $post_id ), $row );
	}
} else {
	$metadata = $meta_value;
}

$metadata = implode( ', ', $metadata );

?>

<div <?php echo $wrapper_attributes; ?>>
	<?php echo $metadata; ?>
</div>
