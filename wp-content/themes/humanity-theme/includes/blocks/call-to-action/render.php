<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_cta_block' ) ) {
	/**
	 * Render a download block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attrs the block attributes
	 *
	 * @return string|null
	 */
	function amnesty_render_cta_block( $attrs = [] ) {

		$attrs = apply_filters( 'amnesty_download_block_attributes', $attrs );

		$divClasses = classnames(
			'callToAction',
			[
				sprintf( 'callToAction--%s', $attrs['background'] ) => ! empty( $attrs['background'] ),
			]
		);

		echo '<pre>';
		var_dump( $attrs );
		echo '</pre>';

		echo '<pre>';
		var_dump( $divClasses );
		echo '</pre>';

    	// return sprintf (
		// 	'<div className={divClasses} role="note" aria-label={title}>
       	// 		{!isEmpty(preheading) && (
        //  			<RichText.Content tagName="h2" className="callToAction-preHeading" value={preheading} />
        // 		)}
        // 		{!isEmpty(title) && (
        //   			<RichText.Content tagName="h1" className="callToAction-heading" value={title} />
        // 		)}
        // 		{!isEmpty(content) && (
        //   			<RichText.Content tagName="p" className="callToAction-content" value={content} />
        // 		)}
        // 		<div className="innerBlocksContainer">
        //   			<InnerBlocks.Content />
        // 		</div>
      	// 	</div>',
	  	// 	$divClasses,
	  	// 	$attrs['background'],
	  	// 	$attrs['preheading'],
	  	// 	$attrs['title'],
		// 	$attrs['content']
   		//  );
	}
}








save: ({ attributes }) => {
    const { background = false, preheading, title, content } = attributes;
    const divClasses = classnames('callToAction', { [`callToAction--${background}`]: background });

    return (
      <div className={divClasses} role="note" aria-label={title}>
        {!isEmpty(preheading) && (
          <RichText.Content tagName="h2" className="callToAction-preHeading" value={preheading} />
        )}
        {!isEmpty(title) && (
          <RichText.Content tagName="h1" className="callToAction-heading" value={title} />
        )}
        {!isEmpty(content) && (
          <RichText.Content tagName="p" className="callToAction-content" value={content} />
        )}
        <div className="innerBlocksContainer">
          <InnerBlocks.Content />
        </div>
      </div>
    );
  },
