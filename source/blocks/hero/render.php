<?php

use Amnesty\Get_Image_Data;

// image attribute takes precedence over the featured image
$image_id = $attrs['imageID'];
if ( ! $image_id ) {
	$image_id = get_post_thumbnail_id();
}

$image = new Get_Image_Data( (int) $image_id );
$video = new Get_Image_Data( (int) $attrs['featuredVideoId'] );

$video_output = '';
// If the block has a featured video, get the video URL
if ( $attrs['featuredVideoId'] && 'video' === $attrs['type'] ) {
	// $video_output used in hero.php view
	$video_output .= sprintf(
		'<div class="hero-videoContainer">
			<video class="hero-video" autoplay loop muted>
				<source src="%s">
			</video>
		</div>',
		esc_url( wp_get_attachment_url( $attrs['featuredVideoId'] ) ),
	);
}

// Build output for the image/video caption and credit
// $media_meta_output used in hero.php view
// Reverse the boolean value of the arguments to match the value of the arguments in the function
$media_meta_output  = $image->metadata( ! $attrs['hideImageCaption'], ! $attrs['hideImageCredit'], 'image' );
$media_meta_output .= $video->metadata( ! $attrs['hideImageCaption'], ! $attrs['hideImageCredit'], 'video' );

$inner_blocks = '';
// If inner blocks are present, build the inner blocks
if ( $content ) {
	// $inner_blocks used in hero.php view
	$inner_blocks .= sprintf(
		'<div class="donation">%s</div>',
		wp_kses_post( $content )
	);
}

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
