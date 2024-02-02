<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;


if ( $max_value && $min_value === $max_value ) :
	?>

	<div class="quantity hidden">
		<input id="<?php echo esc_attr( $input_id ); ?>" class="qty" type="hidden" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>">
	</div>

	<?php

	return;

endif;


?>

<tr>
	<td class="label">
		<label for="<?php echo esc_attr( $input_id ); ?>"><?php /* translators: [front] Donate */ esc_html_e( 'Qty', 'amnesty' ); ?></label>
	</td>
	<td>
		<input
			type="number"
			id="<?php echo esc_attr( $input_id ); ?>"
			class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
			step="<?php echo esc_attr( $step ); ?>"
			min="<?php echo esc_attr( $min_value ); ?>"
			max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
			name="<?php echo esc_attr( $input_name ); ?>"
			value="<?php echo esc_attr( $input_value ?: 1 ); ?>"
			title="<?php /* translators: [front] Donate */ esc_attr_e( 'Qty', 'amnesty' ); ?>"
			size="4"
			inputmode="<?php echo esc_attr( $inputmode ); ?>" />
		<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
	</td>
</tr>
