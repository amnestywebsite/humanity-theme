<?php

$alignment = 'headerAlignment--left';

if ( $attrs['align'] ) {
	$alignment = 'headerAlignment--' . $attrs['align'];
}

$background = 'headerBackground--dark';

if ( $attrs['background'] ) {
	$background = 'headerBackground--' . $attrs['background'];
}

$has_video = '';

if ( $attrs['type'] === 'video' ) {
	$has_video = 'has-video';
}

$classname = classnames( $name, 'header', $alignment, $background, $has_video );
?>

<section class="<?php echo esc_attr( $classname ); ?>" style="aiic:ignore; background-image:url('<?php echo esc_url( $image_url ); ?>')">
	<?php echo wp_kses_post( $video_output ); ?>
	<div class="container">
		<div class="header-content">
			<h1>
				<span class="headerTitle"><?php echo esc_html( $attrs['title'] ); ?></span>
			</h1>
			<p class="headerContent"><?php echo esc_html( $attrs['content'] ); ?></p>
			<div class="headerCta">
				<div class="btn btn--large">
					<span><?php echo esc_html( $attrs['ctaText'] ); ?></span>
					<a href="<?php echo esc_url( $attrs['ctaLink'] ); ?>"></a>
				</div>
			</div>
		</div>
		<?php echo wp_kses_post( $inner_blocks ); ?>
		<?php echo wp_kses_post( $image_meta_output ); ?>
	</div>
</section>
