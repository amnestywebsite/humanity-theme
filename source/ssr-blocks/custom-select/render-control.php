<div class="checkboxGroup is-control <?php echo esc_attr( $attributes['className'] ); ?>" <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
<?php if ( $attributes['showLabel'] ) : ?>
	<span class="checkboxGroup-label"><?php echo esc_html( $attributes['label'] ); ?></span>
<?php endif; ?>

	<button class="checkboxGroup-button" type="button" aria-haspopup="listbox" aria-expanded="false" <?php disabled( $attributes['disabled'] ); ?>>
	<?php

	if ( isset( $attributes['options'][ $attributes['active'] ] ) ) {
		echo esc_html( $attributes['options'][ $attributes['active'] ] );
	} elseif ( $attributes['showLabel'] && $attributes['label'] ) {
		echo esc_html( $attributes['label'] );
	} else {
		echo esc_html( $attributes['options'][ key( $attributes['options'] ) ] );
	}

	?>
	</button>

	<fieldset class="checkboxGroup-list">
	<?php foreach ( $attributes['options'] as $value => $item_label ) : ?>
		<span class="checkboxGroup-item">
			<input id="<?php echo esc_attr( $make_id( $value ) ); ?>" class="screen-reader-text" type="radio" name="<?php echo esc_attr( $attributes['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php checked( $value, $attributes['active'] ); ?>>
			<label for="<?php echo esc_attr( $make_id( $value ) ); ?>" data-value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $item_label ); ?></label>
		</span>
	<?php endforeach; ?>
	</fieldset>
</div>
