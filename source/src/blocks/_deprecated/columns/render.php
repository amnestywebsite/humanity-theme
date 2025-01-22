<div <?php echo wp_kses_data( get_block_wrapper_attributes( [ 'class' => sprintf( 'row layout-%s', $attributes['layout'] ) ] ) ); ?>>
	<div class="block-editor-block-list__layout">
		<?php echo wp_kses_post( $content ); ?>
	</div>
</div>
