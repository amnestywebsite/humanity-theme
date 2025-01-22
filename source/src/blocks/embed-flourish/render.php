<?php

if ( ! $attributes['source'] ) {
	return;
}

// phpcs:ignore WordPressVIPMinimum.Security.ProperEscapingFunction.hrefSrcEscUrl ?>
<div class="flourish-embed" data-src="<?php echo esc_attr( $attributes['source'] ); ?>"></div>
