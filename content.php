<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
			// Author line
			get_template_part( 'author-bio' );
			//edit_post_link( __( 'Bewerken', 'ppnl' ), '<span class="edit-link">', '</span>' );
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			if ( has_post_thumbnail()) {
				ppnl_post_thumbnail();
			}

			/* translators: %s: Name of current post */
			if ( is_single() ) {
				the_content( sprintf(
					__( 'Continue reading %s', 'ppnl' ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
					));
			} else {
				the_excerpt();
			};

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'ppnl' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'ppnl' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<footer class="entry-meta">
		<?php
		if ( 'post' == get_post_type()) { // Hide category text for pages on Search
			$categories_list = get_the_category_list( _x( ', ', 'used between list items, there is a space after the comma', 'ppnl' ) );
			if ( $categories_list)
				printf( '<span class="cat-links block">' . __( 'Gepubliceerd in %1$s.', 'ppnl' ) . '</span>', $categories_list );
			the_tags( '<span class="tag-links block">' . __( 'Tagged', 'ppnl' ) . ' ', ', ' , '</span>');
		}
		?>
	</footer><!-- #entry-meta -->

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
