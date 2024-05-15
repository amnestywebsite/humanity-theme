<?php

declare( strict_types = 1 );

namespace Amnesty\Blocks;

use Amnesty\Get_Image_Data;

// phpcs:disable Universal.Files.SeparateFunctionsFromOO.Mixed

if ( ! function_exists( '\Amnesty\Blocks\amnesty_render_header_block' ) ) {
	/**
	 * Render a header block
	 *
	 * @param array<string,mixed>            $attributes   the block attributes
	 * @param array<int,array<string,mixed>> $inner_blocks inner blocks, if any
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return string
	 */
	function amnesty_render_header_block( array $attributes = [], array $inner_blocks = [] ): string {
		$renderer = new Header_Block_Renderer( $attributes, $inner_blocks );
		return $renderer->render();
	}
}

/**
 * The renderer for the Header block
 *
 * @package Amnesty\Blocks
 */
class Header_Block_Renderer {

	/**
	 * Randomly-generated ID for the block
	 *
	 * @var string
	 */
	protected string $id = '';

	/**
	 * The block attributes
	 *
	 * @var array<string,mixed>
	 */
	protected array $attributes = [];

	/**
	 * The block's inner blocks
	 *
	 * @var array<int,array<string,mixed>>
	 */
	protected array $inner_blocks = [];

	/**
	 * The image data object
	 *
	 * @var \Amnesty\Get_Image_Data
	 */
	protected Get_Image_Data $image;

	/**
	 * The video data object
	 *
	 * @var \Amnesty\Get_Image_Data
	 */
	protected Get_Image_Data $video;

	/**
	 * Constructor
	 *
	 * @param array<string,mixed>            $attributes   the block attributes
	 * @param array<int,array<string,mixed>> $inner_blocks inner blocks, if any
	 */
	public function __construct( array $attributes = [], array $inner_blocks = [] ) {
		$this->id = substr( md5( uniqid( (string) wp_rand(), true ) ), 0, 8 );

		$this->inner_blocks = $inner_blocks;
		$this->attributes   = wp_parse_args(
			$attributes,
			[
				'alignment'          => 'none',
				'background'         => 'dark',
				'content'            => '',
				'ctaLink'            => '',
				'ctaText'            => '',
				'imageID'            => 0,
				'imageURL'           => '',
				'size'               => 'small',
				'title'              => '',
				'type'               => '',
				'hideImageCaption'   => true,
				'hideImageCopyright' => false,
				'featuredVideoId'    => 0,
			]
		);

		$this->image = new Get_Image_Data( $this->attributes['imageID'] );
		$this->video = new Get_Image_Data( (int) $this->attributes['featuredVideoId'] );
	}

	/**
	 * Render the block
	 *
	 * @return string
	 */
	public function render(): string {
		ob_start();

		$this->image();
		$this->open();
		$this->video();
		$this->inner_open();
		$this->title();
		$this->content();
		$this->cta();
		$this->inner_close();
		$this->inner_blocks();
		$this->metadata();
		$this->close();

		return ob_get_clean();
	}

	/**
	 * Render the background image CSS
	 *
	 * @return void
	 */
	protected function image() {
		$image_id = absint( $this->attributes['imageID'] ?? 0 );

		if ( 0 === $image_id ) {
			return;
		}

		$media_lg = wp_get_attachment_image_url( $image_id, 'hero-lg' );
		$media_md = wp_get_attachment_image_url( $image_id, 'hero-md' );
		$media_sm = wp_get_attachment_image_url( $image_id, 'hero-sm' );

		printf(
			( $this->image->credit() ? '<style class="aiic-ignore">' : '<style>' ) .
			'#banner-%1$s{background-image:url("%2$s")}' .
			'@media screen and (min-width:770px){' .
			'#banner-%1$s{background-image:url("%3$s")}' .
			'}' .
			'@media screen and (min-width:1444px){' .
			'#banner-%1$s{background-image:url("%4$s")}' .
			'}' .
			'</style>',
			esc_html( $this->id ),
			esc_html( $media_sm ),
			esc_html( $media_md ),
			esc_html( $media_lg )
		);
	}

	/**
	 * Render the opener
	 *
	 * @return void
	 */
	protected function open() {
		$size       = $this->attributes['size'];
		$type       = $this->attributes['type'];
		$alignment  = $this->attributes['alignment'];
		$background = $this->attributes['background'] ?: 'dark';

		$has_credit = (bool) $this->image->credit();

		$classlist = classnames(
			'page-hero',
			'headerBlock',
			[
				sprintf( 'page-heroSize--%s', $size ) => $size,
				sprintf( 'page-heroAlignment--%s', $alignment ) => $alignment,
				sprintf( 'page-heroBackground--%s', $background ) => $background,
				// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
				'page-hero--video' => 'video' === $type,
				'aimc-ignore'      => $has_credit && $this->attributes['hideImageCopyright'],
				// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
			]
		);

		printf(
			'<div id="banner-%s" class="%s" role="region">',
			esc_attr( $this->id ),
			esc_attr( $classlist )
		);
	}

	/**
	 * Render the video
	 *
	 * @return void
	 */
	protected function video() {
		if ( 'video' !== $this->attributes['type'] ) {
			return;
		}

		$video_id = absint( $this->attributes['featuredVideoId'] ?? 0 );

		if ( 0 === $video_id ) {
			return;
		}

		printf(
			'<div class="page-heroVideoContainer"><video class="page-heroVideo" autoplay loop muted><source src="%s"></video></div>',
			esc_url( wp_get_attachment_url( $video_id ) )
		);
	}

	/**
	 * Render the inner content wrapper
	 *
	 * @return void
	 */
	protected function inner_open() {
		$classes = 'hero-content';

		if ( ! empty( $this->inner_blocks ) ) {
			$classes .= ' has-donation-block';
		}

		printf( '<div class="container"><div class="%s">', esc_attr( $classes ) );
	}

	/**
	 * Render the title
	 *
	 * @return void
	 */
	protected function title() {
		if ( ! $this->attributes['title'] ) {
			return;
		}

		printf(
			'<h1 class="page-heroTitle"><span>%s</span></h1>',
			wp_kses_post( apply_filters( 'the_title', $this->attributes['title'] ) )
		);
	}

	/**
	 * Render the content area
	 *
	 * @return void
	 */
	protected function content() {
		if ( ! $this->attributes['content'] ) {
			return;
		}

		printf(
			'<p class="page-heroContent">%s</p>',
			wp_kses_post( apply_filters( 'the_title', $this->attributes['content'] ) )
		);
	}

	/**
	 * Render the CTA
	 *
	 * @return void
	 */
	protected function cta() {
		if ( ! $this->attributes['ctaText'] && ! $this->attributes['ctaLink'] ) {
			return;
		}

		printf(
			'<div class="page-heroCta"><a class="btn btn--large" href="%s">%s</a></div>',
			esc_url( $this->attributes['ctaLink'] ),
			esc_html( wp_strip_all_tags( $this->attributes['ctaText'] ) )
		);
	}

	/**
	 * Render the inner content wrapper closer
	 *
	 * @return void
	 */
	protected function inner_close() {
		print '</div>';
	}

	/**
	 * Render inner blocks
	 *
	 * @return void
	 */
	protected function inner_blocks() {
		foreach ( $this->inner_blocks as $block ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo render_block( $block );
		}
	}

	/**
	 * Render the image caption
	 *
	 * @return void
	 */
	protected function metadata() {
		if ( ! $this->image->id() && ! $this->video->id() ) {
			return;
		}

		$hide_caption = true === amnesty_validate_boolish( $this->attributes['hideImageCaption'] );
		$hide_credit  = true === amnesty_validate_boolish( $this->attributes['hideImageCopyright'] );

		if ( $hide_caption && $hide_credit ) {
			return;
		}

		echo wp_kses_post( $this->image->metadata( ! $hide_caption, ! $hide_credit, 'image' ) );
		echo wp_kses_post( $this->video->metadata( ! $hide_caption, ! $hide_credit, 'video' ) );
	}

	/**
	 * Render the closer
	 *
	 * @return void
	 */
	protected function close() {
		$blocks = $this->attributes['innerBlocks'] ?? [];

		foreach ( $blocks as $inner ) {
			print render_block( $inner ); // phpcs:ignore
		}

		print '</div></div>';
	}

}
