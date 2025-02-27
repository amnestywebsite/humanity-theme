<?php

if ( ! $show_author ) {
	return;
}

?>
<p class="linkList-itemAuthor">
	<span class="authorTerm"><?php echo esc_html( sprintf( '%s: ', /* translators: [front] */ __( 'Author', 'amnesty' ) ) ); ?></span>
	<span class="authorDescription"> <?php echo esc_html( $author ); ?> </span>
</p>
