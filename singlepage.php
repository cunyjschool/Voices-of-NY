<?php
/*
Template Name: SinglePage
*/
?>

<?php global $theme; get_header(); ?>

    <div id="main" class="singlepage-main">
    <?php $theme->hook('main_before'); ?>
    
        <div id="wrap-content" class="singlepage-content">
        <?php $theme->hook('content_before'); ?>
        
            <div class="content">
            <?php $theme->hook('loop_before'); ?>
                
                <?php
                    $theme->options['template_part'] = 'pages';
                    $get_post_elements =  $theme->get_option('pages_post_elemnts');
                    
                    if (have_posts()) while (have_posts()) : the_post();
                ?>

                <div class="wrap-post wrap-post-page">
                <?php $theme->hook('post_before'); ?>

                    <div <?php post_class('post clearfix'); ?> id="post-<?php the_ID(); ?>">
                    <?php $theme->hook('post'); ?>
                    
                        <h2 class="title"><?php the_title(); ?></h2>
                        <?php if($theme->display('edit_link', $get_post_elements))  { ?>
                        
                        <div class="postmeta-primary">
                            <span class="meta_edit"><?php edit_post_link(); ?></span>
                        </div>
                        
                        <?php } ?>
                        
                        <div class="entry clearfix">
                            
                            <?php
                                if($theme->options['general']['featured_image'] && $theme->display('featured_image', $get_post_elements) && has_post_thumbnail())  {
                                    the_post_thumbnail(
                                        array($theme->get_option($theme->options['template_part'] . '_featured_image_width'), $theme->get_option($theme->options['template_part'] . '_featured_image_height')),
                                        array("class" => $theme->get_option($theme->options['template_part'] . '_featured_image_position') . " featured_image")
                                    );
                                }
                            ?>
                            
                            <?php
                                the_content(''); 
                                wp_link_pages( array( 'before' => '<p><strong>' . __( 'Pages:', 'flexithemes' ) . '</strong>', 'after' => '</p>' ) );
                            ?>

                        </div>
                        
                    </div>
                <?php $theme->hook('post_after'); ?> 
                </div><!-- Post ID <?php the_ID(); ?> -->
                
                <?php 
                    if($theme->display('comments', $get_post_elements) && comments_open( get_the_ID() ))  {
                        comments_template('', true); 
                    }
                ?>
                <?php endwhile; ?>
                
            <?php $theme->hook('loop_after'); ?>
            </div><!-- .content -->
            
        <?php $theme->hook('content_after'); ?> 
        </div><!-- #wrap-content .singlepage-content -->

		<div id='singlepage-widget-wrap'>
		        <?php
			        if(!dynamic_sidebar('sidebar_singlepage_thenews')) {
			            printf( __( 'The single page widget area. <a href="%s">Click here</a> to add some widgets now.', 'flexithemes' ), get_bloginfo('url') . '/wp-admin/widgets.php' );
			        } ?>
		</div><!-- #singlepage-widget-wrap -->
        
    <?php $theme->hook('main_after'); ?> 
    </div><!-- #main .singlepage-main -->
        
<?php get_footer(); ?>