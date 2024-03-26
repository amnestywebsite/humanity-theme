<?php

$has_credit = false;

if ( $data['featured_image_id'] ?? false ) {
	$has_credit = (bool) amnesty_get_image_credit( $data['featured_image_id'] );
}

// translators: [front] %s: the title of the article
$aria_label = sprintf( __( 'Article: %s', 'amnesty' ), format_for_aria_label( $title ) );

spaceless();
?>
<article class="grid-item <?php echo esc_attr( $has_credit ? 'aimc-ignore' : '' ); ?>" aria-label="<?php echo esc_attr( $aria_label ); ?>" style="aiic:ignore;background-image: url('<?php echo esc_url( $data['featured_image'] ); ?>')" tabindex="0">
	<div class="grid-content">
	<?php require realpath( __DIR__ . '/grid-item-meta.php' ); ?>
	<?php require realpath( __DIR__ . '/grid-item-title.php' ); ?>
	</div>
</article>
<?php

endspaceless();
