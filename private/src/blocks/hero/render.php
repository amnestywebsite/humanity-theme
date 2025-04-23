<?php

use Amnesty\Get_Image_Data;

// image attribute takes precedence over the featured image
$image_id = $attributes['imageID'];
if ( ! $image_id ) {
	$image_id = get_post_thumbnail_id();
}

$image = new Get_Image_Data( (int) $image_id );
$video = new Get_Image_Data( (int) $attributes['featuredVideoId'] );

$video_output = '';
if ( $attributes['featuredVideoId'] && 'video' === $attributes['type'] ) {
	$video_output .= sprintf(
		'<div class="hero-videoContainer">
			<video class="hero-video" autoplay loop muted>
				<source src="%s">
			</video>
		</div>',
		esc_url( wp_get_attachment_url( $attributes['featuredVideoId'] ) ),
	);
}

$media_meta_output  = $image->metadata( ! $attributes['hideImageCaption'], ! $attributes['hideImageCopyright'], 'image' );
$media_meta_output .= $video->metadata( ! $attributes['hideImageCaption'], ! $attributes['hideImageCopyright'], 'video' );

$block_classes = [
	// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	'has-dark-background' => ! (bool) $attributes['background'],
	'has-innerBlocks'     => (bool) trim( $content ),
	'has-video'           => 'video' === $attributes['type'],
	// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	'has-' . $attributes['background'] . '-background' => (bool) $attributes['background'],
];

$block_classes      = classnames( $attributes['className'] ?? '', $block_classes );
$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => $block_classes ] );
$background_image   = wp_get_attachment_image_url( $image_id, 'hero-md' );

?>
<section <?php echo wp_kses_data( $wrapper_attributes ); ?> style="aiic:ignore;background-image:url('<?php echo esc_url( $background_image ); ?>')">
	<?php echo wp_kses_post( $video_output ); ?>
	<div class="hero-contentWrapper">
		<h1 class="hero-title">
			<span><?php echo esc_html( $attributes['title'] ); ?></span>
		</h1>
	<?php if ( $attributes['content'] ) : ?>
		<p class="hero-content"><?php echo esc_html( $attributes['content'] ); ?></p>
	<?php endif; ?>
		<div class="hero-cta">
			<div class="btn btn--large">
				<span><?php echo esc_html( $attributes['ctaText'] ); ?></span>
				<a href="<?php echo esc_url( $attributes['ctaLink'] ); ?>"></a>
			</div>
		</div>
	</div>
<?php if ( $content ) : ?>
	<div class="hero-innerBlocks">
		<?php echo wp_kses_post( $content ); ?>
	</div>
<?php endif; ?>
	<?php echo wp_kses_post( $media_meta_output ); ?>
</section>
