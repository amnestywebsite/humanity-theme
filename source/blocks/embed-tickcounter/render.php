<?php

if ( ! function_exists( 'amnesty_allow_position_inline_style' ) ) {
	/**
	 * Add "position" to the list of allowed CSS properties for inline styles
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<int,string> $safe the existing list of allowed properties
	 *
	 * @return array<int,string>
	 */
	function amnesty_allow_position_inline_style( array $safe ): array {
		return array_merge( $safe, [ 'position' ] );
	}
}

if ( ! $attributes['source'] ) {
	return;
}

add_filter( 'safe_style_css', 'amnesty_allow_position_inline_style' );

?>

<div class="align<?php echo esc_attr( $attributes['alignment'] ); ?>">
	<?php echo wp_kses_post( $attributes['source'] ); ?>
</div>

<?php

remove_filter( 'safe_style_css', 'amnesty_allow_position_inline_style' );
