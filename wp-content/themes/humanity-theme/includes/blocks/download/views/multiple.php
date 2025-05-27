<?php

reset( $files );

$first_key  = key( $files );
$first_url  = $files[ $first_key ]['link'] ?? wp_get_attachment_url( $files[ $first_key ]['id'] );
$first_name = basename( $first_url );
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
			'label'      => wp_trim_words( $files[ $first_key ]['title'], 8 ),
			'is_control' => true,
			'options'    => $options,
		]
	);

	?>
	<a class="btn btn--download <?php echo esc_attr( $colour ); ?>" role="button" href="<?php echo esc_url( $first_url ); ?>" download="<?php echo esc_attr( $first_name ); ?>"><?php echo esc_html( $text ); ?></a>
</div>
