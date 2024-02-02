<?php

/**
 * Global partial, custom select input
 *
 * Don't call directly.
 *
 * @package Amnesty\Partials
 *
 * @see amnesty_render_custom_select()
 */

// if there are no options, or there's only one and it has no value (i.e. placeholder), don't render at all
if ( empty( $params['options'] ) || ( 1 === count( $params['options'] ) && ! array_keys( $params['options'] )[0] ) ) {
	return;
}

$make_id = fn ( string $value ): string => amnesty_hash_id( $params['name'] . '-' . $value );

?>
<div class="checkboxGroup is-control <?php echo esc_attr( $params['class'] ); ?>">
<?php if ( $params['show_label'] ) : ?>
	<span class="checkboxGroup-label"><?php echo esc_html( $params['label'] ); ?></span>
<?php endif; ?>

	<button class="checkboxGroup-button" type="button" aria-haspopup="listbox" aria-expanded="false" <?php disabled( $params['disabled'] ); ?>>
	<?php

	if ( isset( $params['options'][ $params['active'] ] ) ) {
		echo esc_html( $params['options'][ $params['active'] ] );
	} elseif ( $params['show_label'] && $params['label'] ) {
		echo esc_html( $params['label'] );
	} else {
		echo esc_html( $params['options'][ key( $params['options'] ) ] );
	}

	?>
	</button>

	<fieldset class="checkboxGroup-list">
	<?php foreach ( $params['options'] as $value => $item_label ) : ?>
		<span class="checkboxGroup-item">
			<input id="<?php echo esc_attr( $make_id( $value ) ); ?>" class="screen-reader-text" type="radio" name="<?php echo esc_attr( $params['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php checked( $value, $params['active'] ); ?>>
			<label for="<?php echo esc_attr( $make_id( $value ) ); ?>" data-value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $item_label ); ?></label>
		</span>
	<?php endforeach; ?>
	</fieldset>
</div>
