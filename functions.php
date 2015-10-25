<?php
/**
 * Piratenpartij Nederland functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Piratenpartij Nederland 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1200;
}

/**
 * Piratenpartij Nederland only works in WordPress 4.1 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'ppnl_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Piratenpartij Nederland 1.0
 */
function ppnl_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on ppnl, use a find and replace
	 * to change 'ppnl' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'ppnl', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );
	add_image_size( 'list-thumb', 125, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'ppnl' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	/*
	 * Remote the theming message of WooCommerce
	 * According to: http://docs.woothemes.com/document/third-party-custom-theme-compatibility/#section-3
	 * Using the simple technique: 'Using woocommerce_content()'
	 */
    add_theme_support( 'woocommerce' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	//add_editor_style( array( 'css/editor-style.css', 'lucidagrande/lucidagrande.css' ) );
}
endif; // ppnl_setup
add_action( 'after_setup_theme', 'ppnl_setup' );

// Needed to convert links to clickable urls
//add_filter('the_content', 'make_clickable');

/**
 * Register widget area.
 *
 * @since Piratenpartij Nederland 1.0
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function ppnl_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Widget Header', 'ppnl' ),
		'id'            => 'header',
		'description'   => __( 'Add widgets here to appear in your header at the right side.', 'ppnl' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Widget Side bar right', 'ppnl' ),
		'id'            => 'sidebar_right',
		'description'   => __( 'Add widgets here to appear on the right side.', 'ppnl' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_widget( 'WijZijn_Widget' );

}
add_action( 'widgets_init', 'ppnl_widgets_init' );

/**
 * Enqueue scripts and styles.
 *
 * @since Piratenpartij Nederland 1.0
 */
function ppnl_scripts() {
	// Add Lucida Grande, used in the main stylesheet.
	wp_enqueue_style( 'lucidagrande', get_template_directory_uri() . '/lucidagrande/lucidagrande.css', array(), '3.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'ppnl-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'ppnl-ie', get_template_directory_uri() . '/css/ie.css', array( 'ppnl-style' ), '20141010' );
	wp_style_add_data( 'ppnl-ie', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'ppnl-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'ppnl-style' ), '20141010' );
	wp_style_add_data( 'ppnl-ie7', 'conditional', 'lt IE 8' );

	// Load slimmenu css
	wp_enqueue_style( 'ppnl-slimmenu-css', get_template_directory_uri() . '/libraries/slimmenu/slimmenu.min.css', array( 'ppnl-style' ), '' );

	wp_enqueue_script( 'ppnl-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'ppnl-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20141010' );
	}

	// Load slimmenu js
	wp_enqueue_script( 'ppnl-slimmenu-js', get_template_directory_uri() . '/libraries/slimmenu/jquery.slimmenu.min.js', array( 'jquery' ), '20141212' );

	wp_enqueue_script( 'ppnl-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20141212', true );
	wp_localize_script( 'ppnl-script', 'screenReaderText', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'ppnl' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'ppnl' ) . '</span>',
	) );
}
add_action( 'wp_enqueue_scripts', 'ppnl_scripts' );

/**
 * Display descriptions in main navigation.
 *
 * @since Piratenpartij Nederland 1.0
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function ppnl_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( 	$args->link_after . '</a>',
						'<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>',
						$item_output );
	}

	return $item_output;
}
//add_filter( 'walker_nav_menu_start_el', 'ppnl_nav_description', 10, 4 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Piratenpartij Nederland 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function ppnl_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'ppnl_search_form_modify' );

/**
 * Implement the Custom Header feature.
 *
 * @since Piratenpartij Nederland 1.0
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 *
 * @since Piratenpartij Nederland 1.0
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 *
 * @since Piratenpartij Nederland 1.0
 */
require get_template_directory() . '/inc/customizer.php';


// Drupal 2 Wordpress updates
function ppnl_init_drupal_users($drupal2wp_obj) {
  global $wpdb;
  global $ppnl_drupal_user_list;
  global $ppnl_drupal_images_list;

  $ppnl_drupal_user_list = array();
  $ppnl_drupal_images_list = array();
  $tmp = array();

  $drupal_db = $drupal2wp_obj->drupalDB();

  $drupal_images = $drupal_db->results("SELECT fid, TRIM(uri) as uri FROM file_managed");

  if (!empty($drupal_images)) {
	  	foreach($drupal_images as $drupal_image ) {
	  		$ppnl_drupal_images_list[$drupal_image['fid']] = str_replace('public://', '', $drupal_image['uri']);
	  	}
  }

  $drupal_users = $drupal_db->results("SELECT uid, TRIM(LOWER(name)) as username, TRIM(LOWER(mail)) as email FROM users");
  if (!empty($drupal_users)) {
	  	foreach($drupal_users as $drupal_user ) {
	  		if ($drupal_user['username'] != '') {
	  			$tmp[$drupal_user['username']] = $drupal_user['uid'];
	  		}
	  		if ($drupal_user['email'] != '') {
	  			$tmp[$drupal_user['email']] = $drupal_user['uid'];
	  		}
	  }
  }

  $wordpress_users = $wpdb->get_results("SELECT ID, user_login, LOWER(user_email) as email FROM {$wpdb->users} ;");
  foreach ($wordpress_users as $wordpress_user) {
  	// Save existing users
  	$ppnl_drupal_user_list[$wordpress_user->user_login] = $wordpress_user->ID;
  	$ppnl_drupal_user_list[$wordpress_user->email] = $wordpress_user->ID;

  	if (!empty($tmp[$wordpress_user->user_login])) {
  		$ppnl_drupal_user_list[$tmp[$wordpress_user->user_login]] = $wordpress_user->ID;

  	} else if (!empty($tmp[$wordpress_user->email])) {
  		$ppnl_drupal_user_list[$tmp[$wordpress_user->email]] = $wordpress_user->ID;

  	}
  }
}
add_action ('drupal2wp_fix_settings', 'ppnl_init_drupal_users');
add_action ('drupal2wp_after_import_users', 'ppnl_init_drupal_users');

function ppnl_update_drupal_post($post) {
	global $ppnl_drupal_user_list;
	global $ppnl_drupal_images_list;

	// Map Drupal userid to Wordpress userid
	if (!empty($ppnl_drupal_user_list[$post['post_author']])) {
		$post['post_author'] = $ppnl_drupal_user_list[$post['post_author']];
	}

	// Clear the post excerpts during import
	$post['post_excerpt'] = '';

	// Fix Drupal image views
	// format: [[{“type”:”media”,”view_mode”:”media_preview”,”fid”:”929″,”attributes”:{“alt”:””,”class”:”media-image”,”height”:”180″,”style”:”width: 180px; height: 180px; float: right;”,”width”:”180″}}]]
	preg_match_all("/\\[\\[[^\\]\\]]+\\]\\]/i", $post['post_content'], $_contentImgTags);
	if (!empty($_contentImgTags[0])) {
		foreach ($_contentImgTags[0] as $img_tag) {
			$new_img_tag = json_decode(substr(trim($img_tag), 2,-2));
			if (!empty($ppnl_drupal_images_list[$new_img_tag->fid])) {
				$img_attr = array();
				foreach ($new_img_tag->attributes as $key => $value) {
					$img_attr[] = trim($key). '="' . trim($value) . '"';
				}
				$new_img_tag = '<img src="/sites/piratenpartij.nl/files/' . $ppnl_drupal_images_list[$new_img_tag->fid] . '" ' . implode(' ', $img_attr). '>';
				$post['post_content'] = str_replace($img_tag, $new_img_tag, $post['post_content']);
			}
		}
	}

	return $post;
}
add_filter ('drupal2wp_modify_post', 'ppnl_update_drupal_post');

function ppnl_allowedtags() {
// Add custom tags to this string
	return '<br>,<em>,<i>,<a>,<p>,<pre><b><strong>';
}

if ( ! function_exists( 'ppnl_custom_wp_trim_excerpt' ) ) :
    function ppnl_custom_wp_trim_excerpt($wpse_excerpt) {
	    $raw_excerpt = $wpse_excerpt;
	    if ( '' == $wpse_excerpt ) {
	        $wpse_excerpt = get_the_content('');
	        $wpse_excerpt = strip_shortcodes( $wpse_excerpt );
	        $wpse_excerpt = apply_filters('the_content', $wpse_excerpt);
	        $wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
	        $wpse_excerpt = strip_tags($wpse_excerpt, ppnl_allowedtags()); /*IF you need to allow just certain tags. Delete if all tags are allowed */

	        //Set the excerpt word count and only break after sentence is complete.
	        $excerpt_word_count = 75;
	        $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count);
	        $tokens = array();
	        $excerptOutput = '';
	        $count = 0;

	        // Divide the string into tokens; HTML tags, or words, followed by any whitespace
	        preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);

	        foreach ($tokens[0] as $token) {
	            if ($count >= $excerpt_length && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) {
	            // Limit reached, continue until , ; ? . or ! occur at the end
	                $excerptOutput .= trim($token);
	                break;
	            }
	            // Add words to complete sentence
	            $count++;
	            // Append what's left of the token
	            $excerptOutput .= $token;
	        }
	        $wpse_excerpt = trim(force_balance_tags($excerptOutput));
	        $excerpt_end = '<a href="'. esc_url( get_permalink() ) . '" title="' . sprintf(__( 'Continue reading %s', 'ppnl' ), get_the_title()) .' ">' .
				sprintf(__( 'Continue reading', 'ppnl' )) . ' -></a>';
	        $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);
	        //$pos = strrpos($wpse_excerpt, '</');
	        //if ($pos !== false)
	        // Inside last HTML tag
	        //$wpse_excerpt = substr_replace($wpse_excerpt, $excerpt_end, $pos, 0); /* Add read more next to last word */
	        //else
	        // After the content
	        $wpse_excerpt .= $excerpt_more; /*Add read more in new paragraph */
	        return $wpse_excerpt;
	    }
	    return apply_filters('ppnl_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
	}
endif;

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'ppnl_custom_wp_trim_excerpt');


// Remove version information
// http://www.teknobites.com/remove-wordpress-version-number-and-query-strings-from-css-and-js-files/
remove_action( 'wp_head', 'wp_generator' );
// remove query strings from any enqueued scripts
function ppnl_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'ppnl_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'ppnl_remove_wp_ver_css_js', 9999 );

// Max items per page in the shop
function show_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'show_products_per_page' );


/**
 * Adds Foo_Widget widget.
 */
class WijZijn_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wijzijn_widget', // Base ID
			__( 'Wij zijn ...', 'ppnl' ), // Name
			array( 'description' => __( 'Wij Zijn ... widget', 'ppnl' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$content = '<ul>
				<li><a href="https://programma.piratenpartij.nl/index.php?title=Burgerparticipatie_en_open_overheid">Burgerparticipatie en open overheid</a></li>
				<li><a href="https://programma.piratenpartij.nl/index.php?title=Transparantie">Transparantie</a></li>
				<li><a href="https://programma.piratenpartij.nl/index.php?title=Privacy_en_burgerrechten">Privacy en burgerrechten</a></li>
				<li><a href="https://programma.piratenpartij.nl/index.php?title=Auteursrecht">Auteursrecht</a></li>
				<li><a href="https://programma.piratenpartij.nl/index.php?title=Vrijheid_van_informatie_en_onderwijs">Vrijheid van informatie en onderwijs</a></li>
				<li><a href="https://programma.piratenpartij.nl/index.php?title=Patenten">Patenten</a></li>
				<li><a href="https://programma.piratenpartij.nl/index.php?title=Internationale_Handel">Internationale handel</a></li>
				<li><a href="https://programma.piratenpartij.nl/index.php?title=Net-politiek">Net-politiek</a></li>
			</ul>';

		if (!empty($instance['content']) && !in_array(trim($instance['content']),array('','<p></p>','<br>','<br />','<br/>'))) {
			$content = trim($instance['content']);
		}

		echo $args['before_widget'];
		echo '<span>Wij zijn</span> een politieke beweging met de kernpunten:' . $content;
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		?>
		<p>
		<?php echo __('Laat onderstaande veld leeg om de landelijke standaard tekst te tonen','ppnl'); ?>
		<label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Content:' ); ?></label>
		<?php wp_editor( $instance['content'], $this->get_field_id( 'content' ),
				array(	'textarea_name' => $this->get_field_name( 'content' ),
					'media_buttons' => false,
					'textarea_rows' => 6,
					'teeny' => true) ); ?>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['content'] = ( ! empty( $new_instance['content'] ) ) ? strip_tags( $new_instance['content'] ,'<ul><li><a><br><br/>') : '';
		return $instance;
	}

} // class WijZijn_Widget

/**
 * The Disable Google Fonts Plugin
 *
 * Disable enqueuing of Open Sans and other fonts used by WordPress from Google.
 *
 * @package Disable_Google_Fonts
 * @subpackage Main
 */
/**
 * Plugin Name: Disable Google Fonts
 * Plugin URI:  http://blog.milandinic.com/wordpress/plugins/disable-google-fonts/
 * Description: Disable enqueuing of Open Sans and other fonts used by WordPress from Google.
 * Author:      Milan Dinić
 * Author URI:  http://blog.milandinic.com/
 * Version:     1.1
 * License:     GPL
 */
/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;
class Disable_Google_Fonts {
	/**
	 * Hook actions and filters.
	 * 
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		add_filter( 'gettext_with_context', array( $this, 'disable_open_sans'             ), 888, 4 );
		add_action( 'after_setup_theme',    array( $this, 'register_theme_fonts_disabler' ), 1      );
	}
	/**
	 * Force 'off' as a result of Open Sans font toggler string translation.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param  string $translations Translated text.
	 * @param  string $text         Text to translate.
	 * @param  string $context      Context information for the translators.
	 * @param  string $domain       Text domain. Unique identifier for retrieving translated strings.
	 * @return string $translations Translated text.
	 */
	public function disable_open_sans( $translations, $text, $context, $domain ) {
		if ( 'Open Sans font: on or off' == $context && 'on' == $text ) {
			$translations = 'off';
		}
		return $translations;
	}
	/**
	 * Force 'off' as a result of Lato font toggler string translation.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param  string $translations Translated text.
	 * @param  string $text         Text to translate.
	 * @param  string $context      Context information for the translators.
	 * @param  string $domain       Text domain. Unique identifier for retrieving translated strings.
	 * @return string $translations Translated text.
	 */
	public function disable_lato( $translations, $text, $context, $domain ) {
		if ( 'Lato font: on or off' == $context && 'on' == $text ) {
			$translations = 'off';
		}
		return $translations;
	}
	/**
	 * Force 'off' as a result of Source Sans Pro font toggler string translation.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param  string $translations Translated text.
	 * @param  string $text         Text to translate.
	 * @param  string $context      Context information for the translators.
	 * @param  string $domain       Text domain. Unique identifier for retrieving translated strings.
	 * @return string $translations Translated text.
	 */
	public function disable_source_sans_pro( $translations, $text, $context, $domain ) {
		if ( 'Source Sans Pro font: on or off' == $context && 'on' == $text ) {
			$translations = 'off';
		}
		return $translations;
	}
	/**
	 * Force 'off' as a result of Bitter font toggler string translation.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param  string $translations Translated text.
	 * @param  string $text         Text to translate.
	 * @param  string $context      Context information for the translators.
	 * @param  string $domain       Text domain. Unique identifier for retrieving translated strings.
	 * @return string $translations Translated text.
	 */
	public function disable_bitter( $translations, $text, $context, $domain ) {
		if ( 'Bitter font: on or off' == $context && 'on' == $text ) {
			$translations = 'off';
		}
		return $translations;
	}
	/**
	 * Force 'off' as a result of Noto Sans font toggler string translation.
	 *
	 * @since 1.1
	 * @access public
	 *
	 * @param  string $translations Translated text.
	 * @param  string $text         Text to translate.
	 * @param  string $context      Context information for the translators.
	 * @param  string $domain       Text domain. Unique identifier for retrieving translated strings.
	 * @return string $translations Translated text.
	 */
	public function disable_noto_sans( $translations, $text, $context, $domain ) {
		if ( 'Noto Sans font: on or off' == $context && 'on' == $text ) {
			$translations = 'off';
		}
		return $translations;
	}
	/**
	 * Force 'off' as a result of Noto Serif font toggler string translation.
	 *
	 * @since 1.1
	 * @access public
	 *
	 * @param  string $translations Translated text.
	 * @param  string $text         Text to translate.
	 * @param  string $context      Context information for the translators.
	 * @param  string $domain       Text domain. Unique identifier for retrieving translated strings.
	 * @return string $translations Translated text.
	 */
	public function disable_noto_serif( $translations, $text, $context, $domain ) {
		if ( 'Noto Serif font: on or off' == $context && 'on' == $text ) {
			$translations = 'off';
		}
		return $translations;
	}
	/**
	 * Force 'off' as a result of Inconsolata font toggler string translation.
	 *
	 * @since 1.1
	 * @access public
	 *
	 * @param  string $translations Translated text.
	 * @param  string $text         Text to translate.
	 * @param  string $context      Context information for the translators.
	 * @param  string $domain       Text domain. Unique identifier for retrieving translated strings.
	 * @return string $translations Translated text.
	 */
	public function disable_inconsolata( $translations, $text, $context, $domain ) {
		if ( 'Inconsolata font: on or off' == $context && 'on' == $text ) {
			$translations = 'off';
		}
		return $translations;
	}
	/**
	 * Register filters that disable fonts for bundled themes.
	 *
	 * This filters can be directly hooked as Disable_Google_Fonts::disable_open_sans()
	 * but that would mean that comparison is done on each string
	 * for each font which creates performance issues.
	 *
	 * Instead we check active template's name very late and just once
	 * and hook appropriate filters.
	 *
	 * Note that Open Sans disabler is used for both WordPress core
	 * and for Twenty Twelve theme.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @uses get_template() To get name of the active parent theme.
	 * @uses add_filter()   To hook theme specific fonts disablers.
	 */
	public function register_theme_fonts_disabler() {
		$template = get_template();
		switch ( $template ) {
			case 'twentyfifteen' :
				add_filter( 'gettext_with_context', array( $this, 'disable_noto_sans'       ), 888, 4 );
				add_filter( 'gettext_with_context', array( $this, 'disable_noto_serif'      ), 888, 4 );
				add_filter( 'gettext_with_context', array( $this, 'disable_inconsolata'     ), 888, 4 );
				break;
			case 'twentyfourteen' :
				add_filter( 'gettext_with_context', array( $this, 'disable_lato'            ), 888, 4 );
				break;
			case 'twentythirteen' :
				add_filter( 'gettext_with_context', array( $this, 'disable_source_sans_pro' ), 888, 4 );
				add_filter( 'gettext_with_context', array( $this, 'disable_bitter'          ), 888, 4 );
				break;
		}
	}
}
/* Although it would be preferred to do this on hook,
 * load early to make sure Open Sans is removed
 */
$disable_google_fonts = new Disable_Google_Fonts;

/*
 * iDeal integratie
 */
require_once('libraries/ideal/ideal.php');
add_shortcode('ideal', 'ideal_shortcode_handler');
