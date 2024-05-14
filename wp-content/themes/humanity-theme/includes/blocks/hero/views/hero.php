<?php

$alignment = '';

if ( $attrs['align'] ) {
	$alignment = 'is-aligned-' . $attrs['align'];
}

$background = 'has-dark-background';

if ( $attrs['background'] ) {
	$background = 'has-' . $attrs['background'] . '-background';
}

$has_video = '';

if ( 'video' === $attrs['type'] ) {
	$has_video = 'has-video';
}

$classname = classnames( $attrs['className'], $alignment, $background, $has_video );

$background_image = wp_get_attachment_image_url( $image_id, 'hero-md' );

?>

<section class="<?php echo esc_attr( $classname ); ?>" style="aiic:ignore;background-image:url('<?php echo esc_url( $background_image ); ?>')">
	<?php echo wp_kses_post( $video_output ); ?>
	<div class="container">
		<div class="hero-contentWrapper">
			<h1>
				<span class="hero-title"><?php echo esc_html( $attrs['title'] ); ?></span>
			</h1>
			<p class="hero-content"><?php echo esc_html( $attrs['content'] ); ?></p>
			<div class="hero-cta">
				<div class="btn btn--large">
					<span><?php echo esc_html( $attrs['ctaText'] ); ?></span>
					<a href="<?php echo esc_url( $attrs['ctaLink'] ); ?>"></a>
				</div>
			</div>
		</div>
		<?php echo wp_kses_post( $inner_blocks ); ?>
	</div>
	<?php echo wp_kses_post( $media_meta_output ); ?>
</section>
