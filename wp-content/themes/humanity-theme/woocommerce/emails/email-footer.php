<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

// phpcs:disable
defined( 'ABSPATH' ) || exit;

?>
															</div>
														</td>
													</tr>
												</table>
												<!-- End Content -->
											</td>
										</tr>
									</table>
									<!-- End Body -->
									<table border="0" cellpadding="0" cellspacing="0" width="1300" id="template_footer_branding">
										<tr>
											<td valign="middle">
											<?php

											if ( $img = get_option( 'woocommerce_email_footer_image' ) ) {
												printf( '<img src="%s" alt=""/>', esc_url( $img ) );
											}

											?>

												<address style="font-style: normal;margin: 20px 0;">
												<?php

												$address = array_filter( [
													get_option( 'woocommerce_store_address' ),
													get_option( 'woocommerce_store_address_2' ),
													get_option( 'woocommerce_store_city' ),
													get_option( 'woocommerce_default_country' ),
													get_option( 'woocommerce_store_postcode' ),
												] );

												echo wp_kses_post( implode( '<br/>', $address ) );

												?>
												</address>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" valign="top">
						<!-- Footer -->
						<table border="0" cellpadding="10" cellspacing="0" width="1300" id="template_footer">
							<tr>
								<td valign="top">
									<table border="0" cellpadding="10" cellspacing="0" width="100%">
										<tr>
											<td colspan="2" valign="middle" id="credit">
												<?php echo wp_kses_post( wpautop( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- End Footer -->
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
