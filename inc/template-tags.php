<?php
/**
 * Custom template tags for Piratenpartij Nederland
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */

if ( ! function_exists( 'ppnl_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 *
 * To be honest - I'm pretty proud of this function. Through a lot of trial and
 * error, I was able to user a core WordPress function (paginate_links()) and
 * adjust it in a way, that the end result is a legitimate pagination.
 * A pagination many developers buy (code) expensively with Plugins like
 * WP Pagenavi. No need! WordPress has it all!
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @return	void
 */
function ppnl_content_nav() {
	global $wp_query, $wp_rewrite;

	$paged			=	( get_query_var( 'paged' ) ) ? intval( get_query_var( 'paged' ) ) : 1;

	$pagenum_link	=	html_entity_decode( get_pagenum_link() );
	$query_args		=	array();
	$url_parts		=	explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}
	$pagenum_link	=	remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link	=	trailingslashit( $pagenum_link ) . '%_%';

	$format			=	( $wp_rewrite->using_index_permalinks() AND ! strpos( $pagenum_link, 'index.php' ) ) ? 'index.php/' : '';
	$format			.=	$wp_rewrite->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

	$links	=	paginate_links( array(
		'base'		=>	$pagenum_link,
		'format'	=>	$format,
		'total'		=>	$wp_query->max_num_pages,
		'current'	=>	$paged,
		'mid_size'	=>	3,
		'type'		=>	'list',
		'add_args'	=>	array_map( 'urlencode', $query_args )
	) );

	if ( $links ) {
		echo "<nav class=\"pagination pagination-centered\">{$links}</nav>";
	}
}
endif;


if ( ! function_exists( 'ppnl_comment_nav' ) ) :
/**
 * Display navigation to next/previous comments when applicable.
 *
 * @since Piratenpartij Nederland 1.0
 */
function ppnl_comment_nav() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
	?>
	<nav class="navigation comment-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'ppnl' ); ?></h2>
		<div class="nav-links">
			<?php
				if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'ppnl' ) ) ) :
					printf( '<div class="nav-previous">%s</div>', $prev_link );
				endif;

				if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'ppnl' ) ) ) :
					printf( '<div class="nav-next">%s</div>', $next_link );
				endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .comment-navigation -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'ppnl_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, tags.
 *
 * @since Piratenpartij Nederland 1.0
 */
function ppnl_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', __( 'Featured', 'ppnl' ) );
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'ppnl' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			get_the_date(),
			esc_attr( get_the_modified_date( 'c' ) ),
			get_the_modified_date()
		);

		printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
			_x( 'Posted on', 'Used before publish date.', 'ppnl' ),
			esc_url( get_permalink() ),
			$time_string
		);
	}

	if ( 'post' == get_post_type() ) {
		if ( is_singular() || is_multi_author() ) {
			printf( '<span class="byline"><span class="author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span>',
				_x( 'Author', 'Used before post author name.', 'ppnl' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author()
			);
		}

		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'ppnl' ) );
		if ( $categories_list && ppnl_categorized_blog() ) {
			printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Categories', 'Used before category names.', 'ppnl' ),
				$categories_list
			);
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'ppnl' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Tags', 'Used before tag names.', 'ppnl' ),
				$tags_list
			);
		}
	}

	if ( is_attachment() && wp_attachment_is_image() ) {
		// Retrieve attachment metadata.
		$metadata = wp_get_attachment_metadata();

		printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
			_x( 'Full size', 'Used before full size attachment link.', 'ppnl' ),
			esc_url( wp_get_attachment_url() ),
			$metadata['width'],
			$metadata['height']
		);
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'ppnl' ), __( '1 Comment', 'ppnl' ), __( '% Comments', 'ppnl' ) );
		echo '</span>';
	}
}
endif;

/**
 * Determine whether blog/site has more than one category.
 *
 * @since Piratenpartij Nederland 1.0
 *
 * @return bool True of there is more than one category, false otherwise.
 */
function ppnl_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'ppnl_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'ppnl_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so ppnl_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so ppnl_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in {@see ppnl_categorized_blog()}.
 *
 * @since Piratenpartij Nederland 1.0
 */
function ppnl_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'ppnl_categories' );
}
add_action( 'edit_category', 'ppnl_category_transient_flusher' );
add_action( 'save_post',     'ppnl_category_transient_flusher' );

if ( ! function_exists( 'ppnl_post_thumbnail' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @since Piratenpartij Nederland 1.0
 */
function ppnl_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( !is_singular() ) :
	?>

	<a class="thumbnail post-thumbnail" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" aria-hidden="true">
		<?php
			the_post_thumbnail( 'list-thumb', array( 'alt' => get_the_title() ) );
		?>
	</a>

	<?php endif; // End is_singular()
}
endif;

if ( ! function_exists( 'ppnl_get_link_url' ) ) :
/**
 * Return the post URL.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since Piratenpartij Nederland 1.0
 *
 * @see get_url_in_content()
 *
 * @return string The Link format URL.
 */
function ppnl_get_link_url() {
	$has_url = get_url_in_content( get_the_content() );

	return $has_url ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}
endif;

if ( ! function_exists( 'ppnl_header_image' ) ) :
/**
 * Displays the header image.
 *
 * Falls back to the static template header image if there is NO custom header image uploaded.
 *
 * @since Piratenpartij Nederland 1.0
 *
 * @see get_header_image()
 *
 * @return string The Link format URL.
 */
function ppnl_header_image() {
	$header_image = get_header_image();
	if ($header_image == '') {
		$header_image = get_template_directory_uri() . '/images/piratenpartij_nederland_logo.png';
	}

	echo $header_image;
}
endif;

if ( ! function_exists( 'ppnl_show_header_image' ) ) :
/**
 * Displays the header image.
 *
 * Falls back to the static template header image if there is NO custom header image uploaded.
 *
 * @since Piratenpartij Nederland 1.0
 *
 * @see get_header_image()
 *
 * @return string The Link format URL.
 */
function ppnl_show_header_image() {
	$header_image = get_header_image();
	$header_image_width = 125;
	$header_image_height = 125;

	if ($header_image == '') {
		$header_image = get_template_directory_uri() . '/images/piratenpartij_nederland_logo.png';
	}

	echo '<img src="' . $header_image . '" alt="Logo '. get_bloginfo( 'name' ) . '" width="' . $header_image_width . '" height="' . $header_image_height . '">';
}
endif;

if ( ! function_exists( 'ppnl_blog_name' ) ) :
/**
 * Displays the header image.
 *
 * Falls back to the static template header image if there is NO custom header image uploaded.
 *
 * @since Piratenpartij Nederland 1.0
 *
 * @see get_header_image()
 *
 * @return string The Link format URL.
 */
function ppnl_blog_name() {
	$blog_name = explode(' ',trim(get_bloginfo( 'name' )));
	/**
	 * The blog name is in the form: Piratepartij Groningen
	 * These are sub departments
	 */

	if (count($blog_name) > 1) {
		$blog_name_tmp = trim($blog_name[0]);
		unset($blog_name[0]);
		$blog_name = $blog_name_tmp . ' <span class="department">' . implode(' ',$blog_name) . '</span>';
	} else {
		$blog_name = trim($blog_name[0]);
	}
	echo $blog_name;
}
endif;


#wp_oembed_remove_provider( 'http://www.youtube.com/oembed*' );

wp_embed_register_handler(
  'yt_nocookie',
  '#https?://www\.youtube-nocookie\.com/embed/([^/]+)/?#i',
  'wp_embed_handler_yt_nocookie'
);

function wp_embed_handler_yt_nocookie( $matches, $attr, $url, $rawattr )
{

//  print_r($matches);
//  print_r($attr);

    $embed = sprintf('<iframe src="https://www.youtube-nocookie.com/embed/%1$s" width="100%%" height="480" allowfullscreen="allowfullscreen"></iframe>',
       esc_attr( $matches[1] ));

  return apply_filters( 'embed_yt_nocookie', $embed, $matches, $attr, $url, $rawattr );
}



/*

 '#http://((m|www)\.)?youtube\.com/watch.*#i'          => array( 'http://www.youtube.com/oembed',                             true  ),
                        '#https://((m|www)\.)?youtube\.com/watch.*#i'         => array( 'http://www.youtube.com/oembed?scheme=https',                true  ),
                        '#http://((m|www)\.)?youtube\.com/playlist.*#i'       => array( 'http://www.youtube.com/oembed',                             true  ),
                        '#https://((m|www)\.)?youtube\.com/playlist.*#i'      => array( 'http://www.youtube.com/oembed?scheme=https',                true  ),
                        '#http://youtu\.be/.*#i'                              => array( 'http://www.youtube.com/oembed',                             true  ),
                        '#https://youtu\.be/.*#i'                             => array( 'http://www.youtube.com/oembed?scheme=https',                true  ),



*/
