<?php
/**
 * The template for displaying Woocommerce
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		woocommerce_content();
		?>
		<a target="_blank" href="<?php echo esc_url( wc_get_page_permalink( 'terms' ) ); ?>" title="Piratenpartij Nederland Webshop Algemene Voorwaarden">Algemene Voorwaarden</a>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>
