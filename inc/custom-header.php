<?php
/**
 * Custom Header functionality for Piratenpartij Nederland
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses ppnl_header_style()
 */
function ppnl_custom_header_setup() {
	/**
	 * Filter Piratenpartij Nederland custom-header support arguments.
	 *
	 * @since Piratenpartij Nederland 1.0
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type int    $width                  Width in pixels of the custom header image. Default 954.
	 *     @type int    $height                 Height in pixels of the custom header image. Default 1300.
	 *     @type string $wp-head-callback       Callback function used to styles the header image and text
	 *                                          displayed on the blog.
	 * }
	 */
	add_theme_support( 'custom-header', array(
		'width'         => 125,
		'height'        => 125,
		'default-image' => get_template_directory_uri() . '/images/piratenpartij_nederland_logo.png',
		'uploads'       => true
	));
}
add_action( 'after_setup_theme', 'ppnl_custom_header_setup' );

if ( ! function_exists('ppnl_promotion_type') ) {

	// Register Custom Post Type
	function ppnl_promotion_type() {

		$labels = array(
			'name'                => _x( 'Uitgelichte banners', 'Post Type General Name', 'ppnl' ),
			'singular_name'       => _x( 'Uitgelichte banner', 'Post Type Singular Name', 'ppnl' ),
			'menu_name'           => __( 'Uitgelichte banners', 'ppnl' ),
			'parent_item_colon'   => __( 'Parent....', 'ppnl' ),
			'all_items'           => __( 'Alle uitgelichte banners', 'ppnl' ),
			'view_item'           => __( 'Bekijk uitgelicht banner', 'ppnl' ),
			'add_new_item'        => __( 'Add New uitgelicht banner', 'ppnl' ),
			'add_new'             => __( 'New uitgelicht banner', 'ppnl' ),
			'edit_item'           => __( 'Edit uitgelicht banner', 'ppnl' ),
			'update_item'         => __( 'Update uitgelicht banner', 'ppnl' ),
			'search_items'        => __( 'Search uitgelicht banners', 'ppnl' ),
			'not_found'           => __( 'Geen uitgelicht banners gevonden', 'ppnl' ),
			'not_found_in_trash'  => __( 'Geen uitgelicht banners gevonden in Trash', 'ppnl' ),
		);
		$args = array(
			'label'               => __( 'Uitgelichte_banner', 'ppnl' ),
			'description'         => __( 'Uitgelichte blokken op de voorpagina', 'ppnl' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'uitgelicht', $args );

	}
	// Hook into the 'init' action
	add_action( 'init', 'ppnl_promotion_type', 0 );
}

if ( ! function_exists('ppnl_newsletter_item') ) {

        // Register Custom Post Type
        function ppnl_newsletter_item() {

                $labels = array(
                        'name'                => _x( 'Nieuwsbrief item', 'Post Type General Name', 'ppnl' ),
                        'singular_name'       => _x( 'Nieuwsbrief item', 'Post Type Singular Name', 'ppnl' ),
                        'menu_name'           => __( 'Nieuwsbrief items', 'ppnl' ),
                        'parent_item_colon'   => __( 'Parent....', 'ppnl' ),
                        'all_items'           => __( 'Alle Nieuwsbrief items', 'ppnl' ),
                        'view_item'           => __( 'Bekijk Nieuwsbrief item', 'ppnl' ),
                        'add_new_item'        => __( 'Voeg nieuwsbrief item toe', 'ppnl' ),
                        'add_new'             => __( 'Voeg nieuwsbrief item toe', 'ppnl' ),
                        'edit_item'           => __( 'Bewerk nieuwsbrief item', 'ppnl' ),
                        'update_item'         => __( 'Wijzig nieuwsbrief item', 'ppnl' ),
                        'search_items'        => __( 'Zoek nieuwsbrief items', 'ppnl' ),
                        'not_found'           => __( 'Geen nieuwsbrief items gevonden', 'ppnl' ),
                        'not_found_in_trash'  => __( 'Geen nieuwsbrief items gevonden in prullenbak', 'ppnl' ),
                );
		$args = array(
                        'label'               => __( 'Nieuwsbrief item', 'ppnl' ),
                        'description'         => __( 'Nieuwsbrief items voor in de nieuwsbrief', 'ppnl' ),
                        'labels'              => $labels,
                        'supports'            => array( 'title', 'editor', 'thumbnail', 'post-attributes', 'tags'),
                        'hierarchical'        => false,
                        'public'              => true,
                        'show_ui'             => true,
                        'show_in_menu'        => true,
                        'show_in_nav_menus'   => true,
                        'show_in_admin_bar'   => true,
                        'menu_position'       => 5,
                        'can_export'          => true,
                        'has_archive'         => false,
                        'exclude_from_search' => true,
                        'publicly_queryable'  => false,
                        'capability_type'     => 'post',
			'taxonomies' => array('post_tag')
                );
                register_post_type( 'newsletter_item', $args );

        }
        // Hook into the 'init' action
        add_action( 'init', 'ppnl_newsletter_item', 0 );
}

