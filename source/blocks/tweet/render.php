<?php

$tweet_url     = get_permalink();
$share_base    = 'https://twitter.com/intent/tweet';
$full_url      = sprintf( '%s?text=%s', $share_base, rawurlencode( $attributes['content'] ?? '' ) );
$block_classes = $attributes['className'] . ' tweetAction';

if ( 'narrow' === $attributes['size'] ) {
	$block_classes .= ' tweetAction--narrow';
}

?>
<div class="tweetBlock align-<?php echo esc_attr( $attributes['alignment'] ); ?>">
	<div class="<?php echo esc_attr( $block_classes ); ?>">
		<div class="tweetAction-header">
			<span class="dashicons dashicons-twitter" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Twitter Logo', 'amnesty' ); ?>"></span>
		<?php if ( isset( $attributes['title'] ) ) : ?>
			<h3 class="tweetAction-title"><?php echo esc_html( $attributes['title'] ); ?></h3>
		<?php endif; ?>
		</div>
		<div class="tweetAction-content">
			<?php echo esc_html( $attributes['content'] ); ?>

		<?php if ( $attributes['embedLink'] && $tweet_url ) : ?>
			<p class="tweetAction-embed">
				<?php echo esc_html( $tweet_url ); ?>
			</p>
		<?php endif; ?>
		</div>
		<div class="tweetButton">
			<a class="btn btn--fill btn--large" href="<?php echo esc_url( $full_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php /* translators: [front] https://wordpresstheme.amnesty.org/blocks/b012-tweet-action/ */ esc_attr_e( 'Send this Tweet', 'amnesty' ); ?>">
				<?php /* translators: [front] https://wordpresstheme.amnesty.org/blocks/b012-tweet-action/ */ esc_html_e( 'Send this Tweet', 'amnesty' ); ?>
			</a>
		</div>
	</div>
</div>
