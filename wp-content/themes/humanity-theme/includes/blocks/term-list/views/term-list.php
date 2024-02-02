<aside class="wp-block-amnesty-core-term-list">
	<h2 class="<?php echo esc_attr( $args['alignment'] ? sprintf( 'u-text%s', ucfirst( $args['alignment'] ) ) : '' ); ?>"><?php echo esc_html( $args['title'] ); ?></h2>
	<div class="navigation">
	<?php foreach ( $letters as $letter ) : ?>
		<button class="<?php echo esc_attr( $first === $letter ? 'is-active' : '' ); ?>" <?php disabled( empty( $groups[ $letter ] ) ); ?>><?php echo esc_html( $letter ); ?></button>
	<?php endforeach; ?>
	</div>
	<div class="listContainer">
	<div class="activeLetter"><?php echo esc_html( $first ); ?></div>
	<?php foreach ( $letters as $letter ) : ?>
		<?php if ( empty( $groups[ $letter ] ) ) continue; // phpcs:ignore Generic.ControlStructures.InlineControlStructure.NotAllowed ?>
		<ul class="listItems" data-key="<?php echo esc_attr( $letter ); ?>" style="display:<?php echo esc_attr( $first === $letter ? 'flex' : 'none' ); ?>">
		<?php foreach ( $groups[ $letter ] as $_term ) : ?>
			<li class="listItem">
				<a href="<?php echo esc_url( amnesty_term_link( $_term ) ); ?>"><?php echo esc_html( $_term->name ); ?></a>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
	</div>
</aside>
