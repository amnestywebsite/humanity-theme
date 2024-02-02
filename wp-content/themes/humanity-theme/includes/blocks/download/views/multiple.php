<?php

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
	<a class="btn btn--download <?php echo esc_attr( $colour ); ?>" role="button" href="<?php echo esc_url( $first_url ); ?>" download="<?php echo esc_attr( $first_name ); ?>"><?php echo esc_html( $text ); ?></a>
</div>
