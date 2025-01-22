<div class="checkboxGroup is-nav <?php echo esc_attr( $attributes['className'] ?? '' ); ?>" action="<?php echo esc_url( current_url() ); ?>" <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
<?php if ( $attributes['showLabel'] ) : ?>
	<span class="checkboxGroup-label"><?php echo esc_html( $attributes['label'] ); ?></span>
<?php endif; ?>

	<button class="checkboxGroup-button" type="button" aria-haspopup="listbox" aria-expanded="false" <?php disabled( $attributes['isDisabled'] ); ?>>
	<?php

	if ( $attributes['showLabel'] ) {
		echo esc_html( $attributes['options'][ $attributes['active'] ] ?? current( $attributes['options'] ) );
	} elseif ( $attributes['label'] ) {
		echo esc_html( $attributes['label'] );
	} else {
		echo esc_html( $attributes['options'][ key( $attributes['options'] ) ] );
	}

	?>
	</button>

	<fieldset class="checkboxGroup-list">
	<?php foreach ( $attributes['options'] as $value => $item_label ) : ?>
		<span class="checkboxGroup-item">
			<input id="<?php echo esc_attr( $make_id( $value ) ); ?>" type="radio" name="<?php echo esc_attr( $attributes['name'] ); ?>" <?php checked( $item_label, $attributes['active'] ); ?>>
			<label for="<?php echo esc_attr( $make_id( $value ) ); ?>" data-value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $item_label ); ?></label>
		</span>
	<?php endforeach; ?>
	</fieldset>
</div>
