<figure id="<?php echo esc_attr( $attrs['anchor'] ); ?>" <?php echo get_block_wrapper_attributes( $wrapper_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<figcaption>
		<h2><?php echo wp_kses_post( $attrs['title'] ); ?></h2>
		<button class="btn--blank" aria-hidden="true">
			<span class="icon icon-arrow-down"></span>
		</button>
	</figcaption>
	<div class="wp-block-amnesty-core-collapsable-inner"><?php echo wp_kses_post( $content ); ?></div>
</figure>
