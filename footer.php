<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */
?>
	<?php get_sidebar(); ?>
	</div><!-- .site-content -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info site-width">
			<ul >
				<li ><a href="<?= esc_url(home_url()); ?>/contact/">Contact</a></li>
				<li ><a href="https://piratenpartij.nl/anbi/">ANBI</a></li>
				<li ><a href="https://piratenpartij.nl/privacybeleid/">Privacybeleid</a></li>
				<li ><a href="https://piratenpartij.nl/partners/">Partners</a></li>
			</ul><br />
			CC0 1.0 Universal Public Domain Dedication<br />
			<a href="https://creativecommons.org/publicdomain/zero/1.0/"  title="Voor zover wettelijk mogelijk, heeft de Piratenpartij alle auteursrecht en naburige rechten opgegeven op de inhoud van deze website. Dit werk is gepubliceerd vanuit: Nederland."><img src="<?= esc_url( get_template_directory_uri() ); ?>/images/cc80x15.png" width="80" height="15" alt="Voor zover wettelijk mogelijk, heeft de Piratenpartij alle auteursrecht en naburige rechten opgegeven op de inhoud van deze website. Dit werk is gepubliceerd vanuit: Nederland."></a>
			<!-- <a href="<?= esc_url( __( 'https://wordpress.org/', 'ppnl' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'ppnl' ), 'WordPress' ); ?></a> -->
		</div><!-- .site-info -->
	</footer><!-- .site-footer -->
</div><!-- .site -->
<?php wp_footer(); ?>
</body>
</html>