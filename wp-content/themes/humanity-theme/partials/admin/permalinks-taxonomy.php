<?php

/**
 * Admin options partial for taxonomy permalinks
 *
 * @package Amnesty\Partials
 */

?>
<tr>
	<th colspan="2" scope="rowgroup">
		<h3>
		<?php

		echo esc_html( sentence( $this->labels( false )->name ) );
		echo '&nbsp;';
		/* translators: [admin] */
		esc_html_e( 'Taxonomy Settings', 'amnesty' );

		?>
		</h3>
	</th>
</tr>
<tr>
	<th scope="row">
		<label for="<?php echo esc_attr( "{$this->key}_slug" ); ?>"><?php /* translators: [admin] */ esc_html_e( 'URL base', 'amnesty' ); ?></label>
	</th>
	<td>
		<input id="<?php echo esc_attr( "{$this->key}_slug" ); ?>" class="regular-text" type="text" name="<?php echo esc_attr( "{$this->key}_slug" ); ?>" value="<?php echo esc_attr( $this->slug ); ?>">
		<span><?php printf( /* translators: [admin] %s: the default slug for this item */ esc_html__( 'Default: %s', 'amnesty' ), esc_html( $this->name ) ); ?></span>
	</td>
</tr>
<tr>
	<th scope="row">
		<label for="<?php echo esc_attr( "{$this->key}_hierarchical" ); ?>"><?php /* translators: [admin] */ esc_html_e( 'Use hierarchical URLs', 'amnesty' ); ?></label>
	</th>
	<td>
		<?php $hierarchical = amnesty_validate_boolish( get_option( "{$this->key}_hierarchical", 'on' ) ); ?>
		<input id="<?php echo esc_attr( "{$this->key}_hierarchical" ); ?>" type="checkbox" name="<?php echo esc_attr( "{$this->key}_hierarchical" ); ?>" <?php echo esc_attr( $hierarchical ? 'checked' : '' ); ?>>
	</td>
</tr>
<tr>
	<td colspan="2"><hr></td>
</tr>
