<?php

if ( is_admin() ) {
	return '';
}

if ( doing_filter( 'get_the_excerpt' ) ) {
	return false;
}

$data = amnesty_petition_list_process_content( $attributes );

if ( ! $data ) {
	return '';
}

$grid_classes = $attributes['grid_class'] ?? 'grid grid-many';

?>

<?php if ( isset( $attributes['style'] ) && 'grid' === $attributes['style'] ) : ?>
	<div class="grid grid-<?php echo esc_attr( count( $data ) ); ?>">
		<?php array_map( 'amnesty_render_grid_item', $data ); ?>
	</div>
<?php endif; ?>

<?php if ( isset( $attributes['style'] ) && 'petition' === $attributes['style'] ) : ?>
	<div class="<?php echo esc_attr( $grid_classes ); ?>">
		<?php array_map( 'amnesty_render_petition_item', $data ); ?>
	</div>
<?php endif; ?>

<ul class="linkList">
	<pre>
		<?php var_dump( [ $attributes, $data ] ); ?>
	</pre>
</ul>
