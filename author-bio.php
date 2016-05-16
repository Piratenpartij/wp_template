<?php
/**
 * The template for displaying Author bios
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */
?>
<div class="author-info">
	<?php
		// Author line
		printf( '<span class="sep">%1$s</span>
				 <a href="%3$s" title="%4$s" rel="bookmark">
				 	<time class="entry-date" datetime="%5$s" pubdate>%6$s</time>
				 </a>
				 <span class="sep">%2$s</span>
				 <span class="author vcard">
				 	<a class="url fn n" href="%7$s" title="%8$s" rel="author">%9$s</a>
				 </span>',
			__('Published on', 'ppnl' ),
			__('by', 'ppnl' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'Show all articles for %s', 'ppnl' ), get_the_author() ) ),
			get_the_author()
		);

	if ( comments_open() AND ! post_password_required() ) { ?>
		<span class="sep"> | </span>
		<span class="comments-link">
			<?php
				comments_popup_link('<span class="leave-reply">' . __( 'Leave comment', 'ppnl' ) . '</span>',
									'<strong>1</strong> ' . __( 'comment', 'ppnl' ),
									'<strong>%</strong> ' . __( 'comments', 'ppnl' )
				);
			?>
		</span>
		<?php
	}
	edit_post_link( __( 'Edit', 'ppnl' ), '<span class="sep"> | </span><span class="edit-link label">', '</span>' );
?>
</div><!-- .author-info -->