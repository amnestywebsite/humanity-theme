<?php $active = is_array( $attributes['active'] ?? [] ) ? $attributes['active'] : [ $attributes['active'] ]; ?>
<div class="checkboxGroup" <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
	<button class="checkboxGroup-button" type="button" aria-haspopup="listbox" aria-expanded="false" <?php disabled( $attributes['isDisabled'] ); ?>>
		<?php echo esc_html( $attributes['label'] ); ?>
	</button>
	<fieldset class="checkboxGroup-list">
	<?php foreach ( $attributes['options'] as $value => $item_label ) : ?>
		<span class="checkboxGroup-item">
			<input id="<?php echo esc_attr( $make_id( $value ) ); ?>" type="checkbox" name="<?php echo esc_attr( $attributes['name'] ); ?>[]" <?php checked( in_array( absint( $value ), $active, true ) ); ?> value="<?php echo esc_attr( $value ); ?>">
			<label for="<?php echo esc_attr( $make_id( $value ) ); ?>"><?php echo esc_html( $item_label ); ?></label>
		</span>
	<?php endforeach; ?>
	</fieldset>
</div>
