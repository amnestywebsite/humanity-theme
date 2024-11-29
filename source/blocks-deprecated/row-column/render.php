<?php

$wrapper_attributes = get_block_wrapper_attributes([
	'class' => 'rowColumn',
]);

?>

<div <?php echo wp_kses_data($wrapper_attributes) ?>>
	<?php echo $content; ?>
</div>
