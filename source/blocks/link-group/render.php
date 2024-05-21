<?php

$icon  = '<svg viewBox="0 0 32 32"><path d="M8 20c0 0 1.838-6 12-6v6l12-8-12-8v6c-8 0-12 4.99-12 10zM22 24h-18v-12h3.934c0.315-0.372 0.654-0.729 1.015-1.068 1.374-1.287 3.018-2.27 4.879-2.932h-13.827v20h26v-8.395l-4 2.667v1.728z" /></svg>';
$items = array_values( array_filter( $attributes['items'], fn ( $item ) => $item['link'] || $item['text'] ) );

?>
<aside class="<?php echo esc_attr( $attributes['className'] ); ?>">
<?php if ( $attributes['title'] ) : ?>
	<h2><?php echo esc_html( $attributes['title'] ); ?></h2>
<?php endif; ?>
<ul>
<?php foreach ( $items as $item ) : ?>
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
