<?php

$classname = [];

if ( $attrs['align'] ) {
	$classname[] = 'is-aligned-' . $attrs['align'];
}

$classname[] = 'has-' . ( $attrs['background'] ?: 'dark' ) . '-background';

if ( 'video' === $attrs['type'] ) {
	$classname[] = 'has-video';
}

if ( $content ) {
	$classname[] = 'has-inner-blocks';
}

$classname = classnames( $attrs['className'], $classname );

$background_image = wp_get_attachment_image_url( $image_id, 'hero-md' );

?>

<section class="<?php echo esc_attr( $classname ); ?>" style="aiic:ignore;background-image:url('<?php echo esc_url( $background_image ); ?>')">
	<?php echo wp_kses_post( $video_output ); ?>
	<div class="container">
		<div class="hero-contentWrapper">
		<?php if ( $attrs['title'] ) : ?>
			<h1 class="hero-title">
				<span><?php echo wp_kses_post( $attrs['title'] ); ?></span>
			</h1>
		<?php endif; ?>
		<?php if ( $attrs['content'] ) : ?>
			<p class="hero-content"><?php echo wp_kses_post( $attrs['content'] ); ?></p>
		<?php endif; ?>
		<?php if ( $attrs['ctaText'] || $attrs['ctaLink'] ) : ?>
			<div class="hero-cta">
				<div class="btn btn--large">
					<a href="<?php echo esc_url( $attrs['ctaLink'] ); ?>"><?php echo wp_kses_post( $attrs['ctaText'] ); ?></a>
				</div>
			</div>
		<?php endif; ?>
		</div>
		<?php echo wp_kses_post( $content ); ?>
	</div>
	<?php echo wp_kses_post( $media_meta_output ); ?>
</section>
