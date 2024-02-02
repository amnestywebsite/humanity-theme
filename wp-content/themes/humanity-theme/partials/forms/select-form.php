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
<form class="checkboxGroup is-form <?php echo esc_attr( $params['class'] ); ?>" action="<?php echo esc_url( current_url() ); ?>">
<?php if ( $params['show_label'] ) : ?>
	<span class="checkboxGroup-label"><?php echo esc_html( $params['label'] ); ?></span>
<?php endif; ?>

	<button class="checkboxGroup-button" type="button" aria-haspopup="listbox" aria-expanded="false" <?php disabled( $params['disabled'] ); ?>>
	<?php

	if ( $params['show_label'] ) {
		echo esc_html( $params['options'][ $params['active'] ] ?? current( $params['options'] ) );
	} else {
		echo esc_html( $params['label'] );
	}

	?>
	</button>

	<fieldset class="checkboxGroup-list">
	<?php foreach ( $params['options'] as $value => $item_label ) : ?>
		<span class="checkboxGroup-item">
			<input id="<?php echo esc_attr( $make_id( $value ) ); ?>" type="radio" name="<?php echo esc_attr( $params['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>">
			<label for="<?php echo esc_attr( $make_id( $value ) ); ?>"><?php echo esc_html( $item_label ); ?></label>
		</span>
	<?php endforeach; ?>
	</fieldset>
</form>
