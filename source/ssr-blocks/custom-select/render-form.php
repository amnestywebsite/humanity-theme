<form class="checkboxGroup is-form <?php echo esc_attr( $attributes['className'] ); ?>" action="<?php echo esc_url( current_url() ); ?>" <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
<?php if ( $attributes['showLabel'] ) : ?>
	<span class="checkboxGroup-label"><?php echo esc_html( $attributes['label'] ); ?></span>
<?php endif; ?>

	<button class="checkboxGroup-button" type="button" aria-haspopup="listbox" aria-expanded="false" <?php disabled( $attributes['disabled'] ); ?>>
	<?php

	if ( $attributes['showLabel'] ) {
		echo esc_html( $attributes['options'][ $attributes['active'] ] ?? current( $attributes['options'] ) );
	} else {
		echo esc_html( $attributes['label'] );
	}

	?>
	</button>

	<fieldset class="checkboxGroup-list">
	<?php foreach ( $attributes['options'] as $value => $item_label ) : ?>
		<span class="checkboxGroup-item">
			<input id="<?php echo esc_attr( $make_id( $value ) ); ?>" type="radio" name="<?php echo esc_attr( $attributes['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>">
			<label for="<?php echo esc_attr( $make_id( $value ) ); ?>"><?php echo esc_html( $item_label ); ?></label>
		</span>
	<?php endforeach; ?>
	</fieldset>
</form>
