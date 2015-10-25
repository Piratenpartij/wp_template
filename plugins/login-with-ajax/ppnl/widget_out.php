<?php
/*
 * This is the page users will see logged out.
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
<div class="lwa lwa-divs-only">
    <span class="lwa-status"></span>
    <form class="lwa-form" action="<?php echo esc_attr(LoginWithAjax::$url_login); ?>" method="post">
        <div class="lwa-username">
            <label><?php esc_html_e( 'Username','login-with-ajax' ) ?></label>
            <input type="text" name="log" id="lwa_user_login" class="input" />
        </div>

        <div class="lwa-password">
            <label><?php esc_html_e( 'Password','login-with-ajax' ) ?></label>
            <input type="password" name="pwd" id="lwa_user_pass" class="input" />
        </div>

        <div class="lwa-login_form">
            <?php do_action('login_form'); ?>
        </div>

        <div class="lwa-submit-button">
            <input type="submit" name="wp-submit" id="lwa_wp-submit" value="<?php esc_attr_e('Log In','login-with-ajax'); ?>" tabindex="100" />
            <input type="hidden" name="lwa_profile_link" value="<?php echo esc_attr($lwa_data['profile_link']); ?>" />
            <input type="hidden" name="login-with-ajax" value="login" />
            <?php if( !empty($lwa_data['redirect']) ): ?>
            <input type="hidden" name="redirect_to" value="<?php echo esc_url($lwa_data['redirect']); ?>" />
            <?php endif; ?>
        </div>

        <div class="lwa-links">
            <input name="rememberme" type="checkbox" class="lwa-rememberme" value="forever" /> <label><?php esc_html_e( 'Remember Me','login-with-ajax' ) ?></label>
            <a href="https://pwm.piratenpartij.nl/" target="_blank" title="<?php esc_attr_e('Password Lost and Found','login-with-ajax') ?>"><?php esc_attr_e('Lost your password?','login-with-ajax') ?></a>
            <?php if ( get_option('users_can_register') && !empty($lwa_data['registration']) ) : ?>
            <br />
            <a href="https://pwm.piratenpartij.nl/" target="_blank"><?php esc_html_e('Register','login-with-ajax'); ?></a>
            <?php endif; ?>
        </div>
    </form>
</div>
