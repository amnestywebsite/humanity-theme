<aside class="<?php echo esc_attr( $attributes['className'] ?? '' ); ?>">
<?php if ( $attributes['title'] ) : ?>
	<h2><?php echo esc_html( $attributes['title'] ); ?></h2>
<?php endif; ?>
<ul>
<?php foreach ( $attributes['items'] as $item ) : ?>
	<?php

	if ( ! $item['link'] || ! $item['text'] ) {
		continue;
	}

	?>
	<?php if ( ! $item['blank'] ) : ?>
		<li>
			<a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
		</li>
	<?php else : ?>
		<li>
			<a class="is-external" href="<?php echo esc_url( $item['link'] ); ?>" rel="noopener noreferrer" target="_blank">
				<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo esc_html( $item['text'] ); ?>
			</a>
		</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
</aside>
