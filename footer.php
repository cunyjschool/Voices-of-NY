<?php global $theme; ?>

    <div id="wrap-footer">
    <?php $theme->hook('footer_before'); ?>
        
        <div id="footer">
	
        <?php $theme->hook('footer'); ?>


		<div id="footer-widget-wrap">
		<?php
            if(!dynamic_sidebar('sidebar_footer_thenews')) {
                printf( __( 'The footer widget area. <a href="%s">Click here</a> to add some widgets now.', 'flexithemes' ), get_bloginfo('url') . '/wp-admin/widgets.php' );
            } ?>
		</div>

        
            <div id="copyrights">
				<div id="footermenus-widget-wrap">
				<?php
		            if(!dynamic_sidebar('sidebar_footermenus_thenews')) {
		                printf( __( 'The footer menus widget area. <a href="%s">Click here</a> to add some widgets now.', 'flexithemes' ), get_bloginfo('url') . '/wp-admin/widgets.php' );
		            } ?>
				</div>
                <?php
                    if($theme->display('footer_custom_text')) {
                        $theme->option('footer_custom_text');
                    } else { 
                        ?> &copy; <?php echo date('Y'); ?>  <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a>. <?php _e('All Rights Reserved.', 'flexithemes');
                    } ?> 
            </div><!-- #copyrights -->
            
        </div><!-- #footer -->
        
        <?php  if($theme->display('footer_credits') || $theme->display('wordpress_credits') ) { ?> 
        <div id="credits">
            <?php
                if($theme->display('wordpress_credits')) {
                    _e('Powered by', 'flexithemes'); ?> <a href="http://wordpress.org/">WordPress</a><?php
                }
                
                if($theme->display('wordpress_credits') && $theme->display('footer_credits')) {
                    echo ' &nbsp;|&nbsp; ';
                }
                
                if($theme->display('footer_credits')) {
                    $footer_affiliate_id = $theme->display('footer_affiliate_id') ? "?partner=" . $theme->get_option('footer_affiliate_id') : ''; ?> <a href="http://flexithemes.com/themes/thenews/<?php echo $footer_affiliate_id; ?>">TheNews</a> <?php _e('theme by', 'flexithemes'); ?> <a href="http://flexithemes.com/<?php echo $footer_affiliate_id; ?>">FlexiThemes</a><?php 
                } ?>
        </div><!-- #credits -->
        <?php } ?>
        
    <?php $theme->hook('footer_after'); ?> 
    </div><!-- #wrap-footer -->
    
<?php $theme->hook('wrapper_after'); ?>
</div><!-- #wrapper -->

<?php wp_footer(); ?>
<?php $theme->hook('html_after'); ?>
</body>
</html>