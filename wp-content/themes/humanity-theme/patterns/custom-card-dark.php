<?php
/**
 * Title: Custom Card Dark
 * Description: dark background behind heading, image, paragraph, button
 * Slug: amnesty/custom-card-dark
 * Keywords: custom, card, dark, grey
 * Categories: humanity-actions
 */
?>

<!-- wp:group {"style":{"spacing":{"padding":{"top":"8px","bottom":"8px"}}},"className":"custom-card is-style-dark"} -->
<div class="wp-block-group custom-card is-style-dark" style="padding-top:8px;padding-bottom:8px"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">(Label)</h2>
<!-- /wp:heading -->

<!-- wp:image {"aspectRatio":"16/9","scale":"cover","hideImageCopyright":true} -->
<figure class="wp-block-image"><img alt="" style="aspect-ratio:16/9;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Content</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-default"} -->
<div class="wp-block-button is-style-default"><a class="wp-block-button__link wp-element-button" href="#">Button</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
