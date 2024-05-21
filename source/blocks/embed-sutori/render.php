<?php

if ( ! $attributes['source'] ) {
	return;
}

$classname = classnames( $attributes['className'], 'sutori-embed' );

?>

<a class="sutori-toggle aligncenter" href="#" data-toggle="<?php echo esc_attr( sprintf( '.%s iframe', $classname ) ); ?>" data-toggle-with="is-open">
	<?php /* translators: [front] no context avalible */ echo esc_html_x( 'Click to show/hide timeline', 'Toggle visibility of Sutori Timeline', 'amnesty' ); ?>
</a>
<div class="<?php echo esc_attr( $classname ); ?>">
	<script src="https://assets.sutori.com/frontend-assets/assets/iframeResizer.js"></script>
	<iframe src="<?php echo esc_url( $attributes['source'] ); ?>" width="100%" height="600" frameborder="0"></iframe>
	<script src="https://assets.sutori.com/frontend-assets/assets/iframeResizer.executer.js"></script>
</div>
