<?php
/**
 * Piratenpartij Nederland Customizer functionality
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Piratenpartij Nederland 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function ppnl_customize_register( $wp_customize ) {
	//$color_scheme = ppnl_get_color_scheme();

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// Remove the core header textcolor control, as it shares the sidebar text color.
	$wp_customize->remove_control( 'header_textcolor' );

	// Add an additional description to the header image section.
	$wp_customize->get_section( 'header_image' )->description = __( 'Applied to the header on small screens and the sidebar on wide screens.', 'ppnl' );
}
add_action( 'customize_register', 'ppnl_customize_register', 11 );

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Piratenpartij Nederland 1.0
 */
function ppnl_customize_preview_js() {
	wp_enqueue_script( 'ppnl-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20141216', true );
}
add_action( 'customize_preview_init', 'ppnl_customize_preview_js' );