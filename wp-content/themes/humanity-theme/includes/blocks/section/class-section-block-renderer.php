<?php

declare( strict_types = 1 );

namespace Amnesty\Blocks;

use Amnesty\Get_Image_Data;

// phpcs:disable Universal.Files.SeparateFunctionsFromOO.Mixed

if ( ! function_exists( '\Amnesty\Blocks\amnesty_render_section_block' ) ) {
	/**
	 * Render the Section block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $attributes the block attributes
	 * @param string              $content    the innerblocks content
	 *
	 * @return string
	 */
	function amnesty_render_section_block( array $attributes, string $content = '' ): string {
		$renderer = new Section_Block_Renderer( $attributes, $content );
		return $renderer->render();
	}
}

/**
 * The renderer for the Section Block
 *
 * @package Amnesty\Blocks
 */
class Section_Block_Renderer {

	/**
	 * Randomly-generated ID for the block
	 *
	 * @var string
	 */
	protected string $id = '';

	/**
	 * Image data object for background image
	 *
	 * @var \Amnesty\Get_Image_Data
	 */
	protected Get_Image_Data $image;

	/**
	 * The block attributes
	 *
	 * @var array<string,mixed>
	 */
	protected array $attributes = [];

	/**
	 * The inner blocks HTML
	 *
	 * @var string
	 */
	protected string $content = '';

	/**
	 * Constructor
	 *
	 * @param array<string,mixed> $attributes the block attributes
	 * @param string              $content    the inner blocks HTML
	 */
	public function __construct( array $attributes, string $content = '' ) {
		$this->attributes = wp_parse_args(
			$attributes,
			[
				'background'               => '',
				'backgroundImage'          => '',
				'backgroundImageHeight'    => '',
				'backgroundImageId'        => 0,
				'backgroundImageOrigin'    => '',
				'enableBackgroundGradient' => false,
				'hideImageCaption'         => true,
				'hideImageCopyright'       => false,
				'minHeight'                => 0,
				'padding'                  => '',
				'sectionId'                => substr( md5( uniqid( (string) wp_rand(), true ) ), 0, 8 ),
				'sectionName'              => '',
				'textColour'               => 'black',
			]
		);

		$this->id      = $this->attributes['sectionId'];
		$this->image   = new Get_Image_Data( absint( $this->attributes['backgroundImageId'] ) );
		$this->content = $content;
	}

	/**
	 * Render the block
	 *
	 * @return string
	 */
	public function render(): string {
		ob_start();

		$this->render_style_tag();
		$this->open_section();
		$this->open_container();
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $this->content;
		$this->close_container();
		$this->render_caption();
		$this->close_section();

		return ob_get_clean();
	}

	/**
	 * Render the background image style
	 *
	 * @return void
	 */
	protected function render_style_tag() {
		$background_image = $this->attributes['backgroundImage'];

		if ( ! $background_image && ! $this->image->id() ) {
			return;
		}

		if ( 0 === $this->image->id() && $background_image ) {
			printf(
				'<style class="aiic-ignore">#section-%s{background-image:url("%s")}</style>',
				esc_attr( $this->id ),
				esc_url( $background_image )
			);

			return;
		}

		$small_bg_image  = wp_get_attachment_image_url( $this->image->id(), 'hero-sm' );
		$medium_bg_image = wp_get_attachment_image_url( $this->image->id(), 'hero-md' );
		$large_bg_image  = wp_get_attachment_image_url( $this->image->id(), 'hero-lg' );

		printf(
			( $this->image->credit() ? '<style class="aiic-ignore">' : '<style>' ) .
			'#section-%1$s{background-image:url("%2$s")}' .
			'@media screen and (min-width:770px){' .
			'#section-%1$s{background-image:url("%3$s")}' .
			'}' .
			'@media screen and (min-width:1444px){' .
			'#section-%1$s{background-image:url("%4$s")}' .
			'}' .
			'</style>',
			esc_attr( $this->id ),
			esc_url( $small_bg_image ),
			esc_url( $medium_bg_image ),
			esc_url( $large_bg_image )
		);
	}

	/**
	 * Render opening section tag
	 *
	 * @return void
	 */
	protected function open_section() {
		$origin  = $this->attributes['backgroundImageOrigin'];
		$padding = $this->attributes['padding'];

		$classes = classnames(
			'section',
			[
				'section--tinted'                          => 'grey' === $this->attributes['background'],
				'section--textWhite'                       => 'white' === $this->attributes['textColour'],
				'section--has-bg-image'                    => (bool) $this->attributes['backgroundImage'],
				'section--has-bg-overlay'                  => (bool) $this->attributes['enableBackgroundGradient'],
				sprintf( 'section--%s', $padding )         => (bool) $padding,
				sprintf( 'section--bgOrigin-%s', $origin ) => (bool) $origin,
			]
		);

		$css_attr = '';

		if ( $this->attributes['backgroundImage'] ) {
			if ( 0 === absint( $this->attributes['minHeight'] ) ) {
				$css_attr .= 'height:auto;';
			}

			if ( absint( $this->attributes['minHeight'] ) > 0 ) {
				$css_attr .= sprintf(
					'min-height:%svw;max-height:%spx;',
					absint( $this->attributes['minHeight'] ),
					absint( $this->attributes['backgroundImageHeight'] )
				);
			}
		}

		printf( '<section id="section-%s" class="%s" style="%s">', esc_html( $this->id ), esc_html( $classes ), esc_attr( $css_attr ) );
	}

	/**
	 * Render opening container tag
	 *
	 * @return void
	 */
	protected function open_container(): void {
		printf( '<div id="%s" class="container">', esc_attr( $this->id ) );
	}

	/**
	 * Render the closing container tag
	 *
	 * @return void
	 */
	protected function close_container() {
		print '</div>';
	}

	/**
	 * Render the caption
	 *
	 * @return void
	 */
	protected function render_caption(): void {
		if ( ! $this->image->id() ) {
			return;
		}

		$hide_caption = true === amnesty_validate_boolish( $this->attributes['hideImageCaption'] );
		$hide_credit  = true === amnesty_validate_boolish( $this->attributes['hideImageCopyright'] );

		if ( $hide_caption && $hide_credit ) {
			return;
		}

		echo wp_kses_post( $this->image->metadata( ! $hide_caption, ! $hide_credit ) );
	}

	/**
	 * Close the section
	 *
	 * @return void
	 */
	protected function close_section() {
		print '</section>';
	}
}
