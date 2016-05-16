<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */
if ( is_active_sidebar( 'sidebar_right' )  ) : ?>
	<div id="secondary" class="secondary">
		<div class="join-donate">
			<span class="button orange">
				<a class="icon group" title="<?php _e('Signup','ppnl') ?>" href="https://lidworden.piratenpartij.nl"><?php _e('Signup','ppnl') ?></a>
			</span>
			<span class="button red">
				<a class="icon doneren" title="<?php _e('Donate','ppnl') ?>" href="https://piratenpartij.nl/doneren"><?php _e('Donate','ppnl') ?></a>
			</span>
		</div>
		<?php if ( is_active_sidebar( 'sidebar_right' ) ) : ?>
			<div id="widget-area" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'sidebar_right' ); ?>
			</div><!-- .widget-area -->
		<?php endif; ?>
	</div><!-- .secondary -->
<?php endif; ?>
