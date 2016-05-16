<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="shortcut icon" href="<?= ppnl_header_image(); ?>"/>
	<link rel="image_src" href="<?= ppnl_header_image(); ?>"/>
	<script type="text/javascript" src="/wp-content/themes/ppnl/ym.js"></script>
	<!--[if lt IE 9]>
	<script src="<?= esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'ppnl' ); ?></a>
	<div class="site-header">
		<div class="site-width">
			<header>
				<div class="site-branding">
					<a href="<?= esc_url( home_url( '/' ) ); ?>"  title="<?php bloginfo('name'); ?>" rel="home">
						<span class="site-logo"><?php ppnl_show_header_image(); ?></span>
					</a>
					<h1 class="site-title"><a href="<?= esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo('name'); ?>" rel="home"><?php ppnl_blog_name(); ?></a></h1><br />
					<h2 class="site-description"><a href="<?= esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo('name'); ?>" rel="home"><?php bloginfo( 'description' ); ?></a></h2>
				</div>
				<?php if ( is_active_sidebar( 'header' ) ) : ?>
					<div id="widget-area-header" class="widget-area header">
						<?php dynamic_sidebar( 'header' ); ?>
					</div><!-- .widget-area -->
				<?php endif; ?>
			</header>
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<?php
						// Primary navigation menu.
						wp_nav_menu( array(
							'menu_class'     => 'slimmenu',
							'theme_location' => 'primary',
						) );
					?>
				</nav><!-- .main-navigation -->
			<?php endif; ?>
		</div>
	</div>
	<div id="content" class="site-content site-width">
