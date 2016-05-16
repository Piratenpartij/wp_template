<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php if ( is_home() && is_front_page() ) : ?>
				<?php
				    $promotion_banners = new WP_Query( array(
				      	'post_type' => 'promo',
				      	'orderby' => 'menu_order',
						'order'   => 'ASC',
						'posts_per_page' => 3,
				    ) );
				    if( $promotion_banners->have_posts() ) : ?>
				    <div class="promotion">
				    <?php
				      while( $promotion_banners->have_posts() ) {
				        $promotion_banners->the_post();
				        ?>
				        <div class="item">
			        	  <?php the_post_thumbnail('full'); ?>
				            <div class='content'>
                                              <span class="middle">
				              <?php the_content() ?>
                                              </span>
				            </div>
				        </div>
				        <?php
				      }
				      ?>
				    </div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="news-header"><?php echo sprintf(__('Latest articles off the %s','ppnl'),get_blog_details(get_current_blog_id())->blogname) ?></div>
		<?php if ( have_posts() ) : ?>
			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			ppnl_content_nav('nav-below');
			/*
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'ppnl' ),
				'next_text'          => __( 'Next page', 'ppnl' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'ppnl' ) . ' </span>',
			) );
			*/
		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>
