<?php

if ( ! $show_post_date ) {
	return;
}

?>

<p class="linkList-itemDate">
	<span class="dateTerm"><?php echo esc_html( sprintf( '%s: ', /* translators: [front] */ __( 'Date', 'amnesty' ) ) ); ?></span>
	<span class="dateDescription"><?php echo esc_html( $post_date ); ?></span>
</p>

<?php

if ( ! $post_updated ) {
	return;
}

?>

<p class="linkList-itemDate">
	<span class="dateTerm"><?php echo esc_html( sprintf( '%s: ', /* translators: [front] */ __( 'Updated', 'amnesty' ) ) ); ?></span>
	<span class="dateDescription"><?php echo esc_html( $post_updated ); ?></span>
</p>
