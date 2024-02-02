<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/result-count.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.7.0
 */
?>
<p class="woocommerce-result-count">
<?php

$total = absint( $total );

if ( 1 === $total ) {
	/* translators: [front] Donate */
	esc_html_e( 'Showing one result', 'amnesty' );
	return;
}

if ( $total <= $per_page || -1 === $per_page ) {
	/* translators: [front] Donate %d: total results */
	printf( esc_html( _n( 'Showing all %d result', 'Showing all %d results', $total, 'amnesty' ) ), esc_html( number_format_i18n( $total, 2 ) ) );
	return;
}

$first = ( $per_page * $current ) - $per_page + 1;
$last  = min( $total, $per_page * $current );

echo esc_html(
	sprintf(
	/* translators: [front] Donate 1: first result 2: last result 3: total results */
		_nx(
			'Showing %1$d&ndash;%2$d of %3$d result',
			'Showing %1$d&ndash;%2$d of %3$d results',
			$total,
			'shown with first and last result on product pages',
			'amnesty'
		),
		number_format_i18n( $first, 2 ),
		number_format_i18n( $last, 2 ),
		number_format_i18n( $total, 2 )
	) 
);

?>
</p>
