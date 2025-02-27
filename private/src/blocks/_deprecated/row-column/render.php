<div <?php echo wp_kses_data( get_block_wrapper_attributes( [ 'class' => 'rowColumn' ] ) ); ?>>
	<?php echo wp_kses_post( $content ); ?>
</div>
