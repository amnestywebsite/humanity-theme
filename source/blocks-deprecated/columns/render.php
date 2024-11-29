<?php

$classes = sprintf(
	'row layout-%s',
	$attributes['layout']
);


$wrapper_attributes = get_block_wrapper_attributes([
	'class' => $classes,
]);

?>

<div <?php echo wp_kses_data($wrapper_attributes) ?>>
	<div class="block-editor-block-list__layout">
		<?php echo $content; ?>
	</div>
</div>
