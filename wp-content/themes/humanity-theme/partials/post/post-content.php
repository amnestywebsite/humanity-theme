<?php

/**
 * Post partial, content
 *
 * @package Amnesty\Partials
 */

$tax_term = amnesty_get_prominent_term( get_the_ID() );
$epoch    = get_post_time( 'U', true );
$date     = amnesty_locale_date( $epoch );
$updated  = get_post_meta( get_the_ID(), 'amnesty_updated', true );

if ( $updated ) {
	$updated = amnesty_locale_date( strtotime( $updated ) );
	$updated = sprintf( '%s: %s', _x( 'Updated', 'updated date label', 'amnesty' ), $updated );
}

?>
<div class="post-content">
<?php if ( $tax_term ) : ?>
	<a class="post-category" aria-label="<?php /* translators: [front] ARIA https://isaidotorgstg.wpengine.com/en/latest/news/2021/05/china-three-child-policy-still-a-violation/ placeholder is category name */ sprintf( __( 'Article category %s', 'amnesty' ), esc_attr( $tax_term->name ) ); ?>" href="<?php echo esc_url( amnesty_term_link( $tax_term ) ); ?>"><?php echo esc_html( $tax_term->name ); ?></a>
<?php endif; ?>
	<header class="post-header">
		<span class="post-meta" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Post published date', 'amnesty' ); ?>"><?php echo esc_html( $date ); ?></span>
	<?php if ( $updated ) : ?>
		<span class="post-meta" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Post updated date', 'amnesty' ); ?>"><?php echo esc_html( $updated ); ?></span>
	<?php endif; ?>
		<h1 class="post-title"><span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span></h1>
	</header>
</div>
