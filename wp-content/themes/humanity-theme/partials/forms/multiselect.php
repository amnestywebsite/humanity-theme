<?php

/**
 * Global partial, multiple selection input
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
$active  = is_array( $params['active'] ?? [] ) ? $params['active'] : [ $params['active'] ];

?>

<div class="checkboxGroup">
	<button class="checkboxGroup-button" type="button" aria-haspopup="listbox" aria-expanded="false" <?php disabled( $params['disabled'] ); ?>>
		<?php echo esc_html( $params['label'] ); ?>
	</button>
	<fieldset class="checkboxGroup-list">
	<?php foreach ( $params['options'] as $value => $item_label ) : ?>
		<span class="checkboxGroup-item">
			<input id="<?php echo esc_attr( $make_id( $value ) ); ?>" type="checkbox" name="<?php echo esc_attr( $params['name'] ); ?>[]" <?php checked( in_array( absint( $value ), $active, true ) ); ?> value="<?php echo esc_attr( $value ); ?>">
			<label for="<?php echo esc_attr( $make_id( $value ) ); ?>"><?php echo esc_html( $item_label ); ?></label>
		</span>
	<?php endforeach; ?>
	</fieldset>
</div>
