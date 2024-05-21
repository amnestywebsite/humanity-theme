<?php

if ( ! count( $attributes['files'] ) ) {
	require './render-deprecated.php';
	return;
}

$attributes = apply_filters( 'amnesty_download_block_attributes', $attributes );

$files  = $attributes['files'];
$text   = $attributes['buttonText'];
$colour = sprintf( 'btn--%s', $attributes['style'] );
$align  = sprintf( 'align%s', $attributes['alignment'] );

if ( count( $files ) < 2 ) :
	if ( empty( $files[0]['id'] ) ) {
		return;
	}

	$url = $files[0]['link'] ?? amnesty_get_attachment_url( $files[0]['id'] );

	if ( ! $url ) {
		return;
	}

	$name = explode( '/', $url );
	$name = array_pop( $name );

	?>

	<div class="download-block <?php echo esc_attr( $align ); ?>">
		<a
			class="<?php echo esc_attr( $colour ); ?>"
			href="<?php echo esc_url( $url ); ?>"
			download="<?php echo esc_attr( $name ); ?>"
			target="_blank"
			role="button"
		><?php echo esc_html( $text ); ?></a>
	</div>

	<?php

endif;

$first_url  = $files[0]['link'] ?? wp_get_attachment_url( $files[0]['id'] );
$first_name = basename( $files[0]['link'] ?? wp_get_attachment_url( $files[0]['id'] ) );
$options    = [];

foreach ( $files as $file ) {
	$key = $file['link'] ?? wp_get_attachment_url( $file['id'] );

	$options[ $key ] = $file['title'];
}

?>
<div class="<?php echo esc_attr( classnames( 'download-block is-multiple', $align, $attrs['className'] ?? null ) ); ?>">
	<?php

	amnesty_render_custom_select(
		[
			'label'      => wp_trim_words( $files[0]['title'], 8 ),
			'is_control' => true,
			'options'    => $options,
		]
	);

	?>
	<a
		class="btn btn--download <?php echo esc_attr( $colour ); ?>"
		href="<?php echo esc_url( $first_url ); ?>"
		download="<?php echo esc_attr( $first_name ); ?>"
		role="button"
	><?php echo esc_html( $text ); ?></a>
</div>
