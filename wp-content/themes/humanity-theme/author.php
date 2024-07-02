<?php

/**
 * Author template
 *
 * @package Amnesty\Templates
 */

use Amnesty\Get_Image_Data;

get_header();

$author_id          = get_the_author_meta( 'ID' );
$author_name        = get_the_author_meta( 'display_name' );
$twitter_handle     = get_the_author_meta( 'twitter' );
$author_bio         = get_the_author_meta( 'authorbiographysection' );
$author_banner      = get_the_author_meta( 'authorbanner' );
$author_banner_id   = get_the_author_meta( 'authorbanner_id' );
$author_avatar      = get_the_author_meta( 'authoravatar' );
$author_description = get_the_author_meta( 'authordescriptionsection' );

$image = new Get_Image_Data( absint( $author_banner_id ) );

$args = [
	'author'         => $author_id,
	'orderby'        => 'post_date',
	'order'          => 'ASC',
	'posts_per_page' => 5,
];

$author_posts        = new WP_Query( $args );
$display_author_info = get_post_meta( get_the_ID(), '_display_author_info', true );

?>

<?php if ( $author_banner ) : ?>
<div class="desktop-author-info">
	<div class="author-banner container" style="background-image: url('<?php echo esc_attr( $author_banner ); ?>')">
		<div class="author-header-container">
		<?php if ( $author_avatar ) : ?>
			<div class="author-avatar">
				<?php printf( '<img src="%s" alt="">', esc_html( $author_avatar ) ); ?>
			</div>
		<?php endif; ?>
			<div class="author-info-container">
			<?php if ( $author_name ) : ?>
				<h2 class="author-display-name"><?php echo esc_html( $author_name ); ?></h2>
			<?php endif; ?>
			<?php if ( $author_description ) : ?>
				<p class="author-description"><?php echo esc_html( $author_description ); ?></p>
			<?php endif; ?>
			<?php if ( $twitter_handle ) : ?>
				<a class="author-page-twitter" rel="noopener noreferer" target="_blank" href="https://twitter.com/<?php echo esc_attr( $twitter_handle ); ?>"><?php esc_html_e( 'Follow', 'amnesty' ); ?> &commat;<?php echo esc_html( $twitter_handle ); ?></a>
			<?php endif; ?>
			</div>
		</div>

	<?php echo wp_kses_post( $image->metadata() ); ?>
	</div>
</div>
<?php endif; ?>

<main id="main">
	<div class="section section--small container article-container">
		<section class="article section--topSpacing">
			<div class="article-meta">
				<div class="author-content">
					<h2><?php esc_html_e( 'Biography', 'amnesty' ); ?></h2>
					<div class="biography-container">
						<div class="author-biography is-collapsed">
							<?php echo wp_kses_post( wpautop( $author_bio ) ); ?>
						</div>
						<button class="btn btn--white" id="author-read-more"><?php esc_html_e( 'Read more', 'amnesty' ); ?></button>
					</div>
					<div class="more-from-author">
						<h3><?php esc_html_e( 'More from this author', 'amnesty' ); ?></h3>
						<div class="author-post-feed section--dark">
						<?php
						while ( $author_posts->have_posts() ) {
							$author_posts->the_post();
							$style = 'search';
							get_template_part( 'partials/post/post', $style );
						}
							wp_reset_postdata();
						?>

						</div>
						<?php get_template_part( 'partials/pagination' ); ?>
					</div>
				</div>
			</div>
		</section>

	<?php if ( $twitter_handle ) : ?>
		<aside class="twitter-sidebar">
			<a class="twitter-timeline" href="https://twitter.com/<?php echo esc_attr( $twitter_handle ); ?>?ref_src=twsrc%5Etfw" data-tweet-limit="3" data-width="500">Tweets by <?php echo esc_html( $twitter_handle ); ?></a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
		</aside>
	<?php endif; ?>

	<?php if ( ! get_post_meta( get_the_ID(), '_disable_share_icons', true ) ) : ?>
		<aside class="article-shareContainer" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Social sharing options', 'amnesty' ); ?>">
			<?php get_template_part( 'partials/article-share' ); ?>
		</aside>
	<?php endif; ?>
	</div>
</main>

<?php get_footer(); ?>
