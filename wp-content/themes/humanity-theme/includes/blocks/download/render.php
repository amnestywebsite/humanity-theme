<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_download_block' ) ) {
	/**
	 * Render a download block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attrs the block attributes
	 *
	 * @return string|null
	 */
	function amnesty_render_download_block( $attrs = [] ) {
		if ( empty( $attrs['files'] ) ) {
			return amnesty_render_download_block__deprecated();
		}

		$attrs = apply_filters( 'amnesty_download_block_attributes', $attrs );

		$files  = $attrs['files'];
		$text   = isset( $attrs['buttonText'] ) ? $attrs['buttonText'] : /* translators: [front] */ __( 'Download', 'amnesty' );
		$colour = sprintf( 'btn--%s', isset( $attrs['style'] ) ? $attrs['style'] : 'dark' );
		$align  = isset( $attrs['alignment'] ) ? sprintf( 'align%s', $attrs['alignment'] ) : '';

		if ( count( $files ) < 2 ) {
			if ( empty( $files[0]['id'] ) ) {
				return null;
			}

			$url = $files[0]['link'] ?? amnesty_get_attachment_url( $files[0]['id'] );

			if ( ! $url ) {
				return '';
			}

			$name = explode( '/', $url );
			$name = array_pop( $name );

			spaceless();
			require locate_template( 'includes/blocks/download/views/simple.php' );
			return endspaceless( false );
		}

		spaceless();
		require locate_template( 'includes/blocks/download/views/multiple.php' );
		return endspaceless( false );
	}
}

if ( ! function_exists( 'amnesty_render_download_block__deprecated' ) ) {
	/**
	 * Render a download block (deprecated version)
	 *
	 * @package Amnesty\Blocks
	 * @deprecated 1.0.0
	 *
	 * @return string
	 */
	function amnesty_render_download_block__deprecated() {
		$file_id = amnesty_get_meta_field( 'download_id' ) ?: false;

		if ( ! $file_id ) {
			return '';
		}

		$button_text = amnesty_get_meta_field( 'download_text' ) ?: /* translators: [front] Download block https://wordpresstheme.amnesty.org/blocks/015-download-resource/ */ __( 'Download', 'amnesty' );
		$file_url    = wp_get_attachment_url( $file_id );
		$file_name   = explode( '/', $file_url );
		$file_name   = array_pop( $file_name );

		spaceless();
		?>
		<div>
			<a class="btn btn--dark btn--download" href="<?php echo esc_url( $file_url ); ?>" target="_blank" download="<?php echo esc_attr( $file_name ); ?>" role="button"><?php echo esc_html( $button_text ); ?></a>
		</div>
		<?php
		return endspaceless( false );
	}
}
