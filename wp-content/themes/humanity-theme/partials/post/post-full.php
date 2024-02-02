<?php

/**
 * Post partial, full image
 *
 * @package Amnesty\Partials
 */

use Amnesty\Get_Image_Data;

$image_id  = get_post_thumbnail_id( get_the_ID() );
$image_url = wp_get_attachment_image_url( $image_id, 'post-full' );
$image     = new Get_Image_Data( $image_id );

// translators: [front] %s: the title of the article
$aria_label = sprintf( __( 'Article: %s', 'amnesty' ), format_for_aria_label( get_the_title() ) );

?>
<article class="post postImage--full" aria-label="<?php echo esc_attr( $aria_label ); ?>" style="background-image: url('<?php echo esc_url( $image_url ); ?>')">
<?php

get_template_part( 'partials/post/post', 'content' );
echo wp_kses_post( $image->metadata() );

?>
</article>
