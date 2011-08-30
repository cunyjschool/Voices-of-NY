<?php global $theme; get_header(); ?>

    <div id="main" class="singlepost-main">
    <?php $theme->hook('main_before'); ?>
    
        <div id="wrap-content" class="singlepost-content">
	
        <?php $theme->hook('content_before'); ?>
        
            <div class="content">
            <?php $theme->hook('loop_before'); ?>
                
                <?php
                    $theme->options['template_part'] = 'singlepost';
                    $get_post_elements =  $theme->get_option('singlepost_post_elemnts');
                    
                    if (have_posts()) while (have_posts()) : the_post();
                ?>

                <div class="wrap-post wrap-post-single">
                <?php $theme->hook('post_before'); ?>

                    <div <?php post_class('post clearfix'); ?> id="post-<?php the_ID(); ?>">
                    <?php $theme->hook('post'); ?>
                    
                        <?php if($theme->display('date', $get_post_elements))  { ?>
                        
                        
						<div class="postmeta-primary">
							<div class="postmeta-date">
	                            <span class="meta_date"><?php the_time($theme->get_option('dateformat')); ?></span>
	                        </div>
                            <?php  if($theme->display('categories', $get_post_elements)) {
                                ?><span class="meta_categories"><?php _e( '', 'flexithemes' ); ?>  <?php exclude_post_categories('Uncategorized,Featured Posts,Top News, In the News', ', '); ?></span><?php
                            }if($theme->display('edit_link', $get_post_elements))  {
                                    ?> &nbsp; <span class="meta_edit"><?php edit_post_link(); ?></span><?php
                                } ?> 
                        </div> <!-- end postmeta-primary-->
                        <?php } ?>
                        
                        <h2 <?php post_class('post clearfix title'); ?> id="post-<?php the_ID(); ?>"><?php the_title(); ?></h2>
                        <?php if($theme->display('author', $get_post_elements) || $theme->display('comments', $get_post_elements) ||
$theme->display('source', $get_post_elements) || $theme->display('edit_link', $get_post_elements))  { ?>
                        
                        
                        <?php } ?>
						<?php
						$values = get_post_custom( $post->ID );
						$text = isset( $values['video_url_box_text'] ) ? esc_attr( $values['video_url_box_text'][0] ) : '';
						wp_nonce_field( 'video_url_box_nonce', 'meta_box_nonce' );
						if (!empty($text)) {
								global $wp_embed;
								$post_embed = $wp_embed->run_shortcode('[embed width="674"]' . $text . '[/embed]');
								echo $post_embed;
                        } elseif($theme->options['general']['featured_image'] && $theme->display('featured_image', $get_post_elements) && has_post_thumbnail())  {
                                the_post_thumbnail(
                                    array($theme->get_option($theme->options['template_part'] . '_featured_image_width'), $theme->get_option($theme->options['template_part'] . '_featured_image_height')),
                                    array("class" => $theme->get_option($theme->options['template_part'] . '_featured_image_position') . " featured_image")
				
                                );
							?><span class="caption"><?php the_post_thumbnail_caption();?></span><?php
                            }
                        ?>
						
                        
                        <div class="entry clearfix">
	
                          <div class="postmeta-byline">  
                            <?php if($theme->display('author',$get_post_elements)) { 
                                   ?> &nbsp; <span class="meta_author">By <?php coauthors_posts_links(); ?></span><?php
                                }
								$taxo_text = "";
								$source_list = get_the_term_list( $post->ID, 'source', '', ', ', '' );  
								if ( '' != $source_list ) {  
								    $taxo_text .= "$source_list";  
								}
								if ( '' != $taxo_text ) 
							{   
	                                   ?>&nbsp;<span class="meta_source">| 
									<?php  
									echo $taxo_text;  
									?></span><?php 
								}
								$translator = get_post_meta( $post->ID, '_cd_translator_name', true );
								if (!empty($translator)) {
								?>&nbsp;<br /> <span class="meta_translator">Translated by
									<?php  
									echo $translator;  
									?></span>
								<?php }	
								$original = get_post_meta( $post->ID, '_cd_original_link', true );
								if (!empty($original)) {
								?>&nbsp; <br /><a href="<?php echo $original; ?>" target=”_blank” class="meta_original">Go to original story</a>
								<?php }
								if($theme->display('comments', $get_post_elements) && comments_open( get_the_ID() ))  {
		                                ?> &nbsp; <span class="meta_comments"><?php comments_popup_link( __( 'No comments', 'flexithemes' ), __( '1 Comment', 'flexithemes' ), __( '% Comments', 'flexithemes' ) ); ?></span><?php
		                            }
							?>
                         </div> <!-- end postmeta-byline-->
							
                            <?php
                                the_content(''); 
                                wp_link_pages( array( 'before' => '<p><strong>' . __( 'Pages:', 'flexithemes' ) . '</strong>', 'after' => '</p>' ) );
                            ?>

                        </div>
                        <?php if($theme->display('categories', $get_post_elements) || $theme->display('tags', $get_post_elements))  { ?>

                        <div class="postmeta-secondary">
                            <?php if($theme->display('tags', $get_post_elements)) {
                                if(get_the_tags()) {
                                    ?> &nbsp; <span class="meta_tags"><?php the_tags(__( 'Tags:', 'flexithemes') . ' ', ', ', ''); ?></span><?php
                                } 
                            }
                            ?> 
                        </div>
                        <?php } ?>
                        
                    </div>
                <?php $theme->hook('post_after'); ?> 
                </div><!-- Post ID <?php the_ID(); ?> -->
                
                <?php if($theme->display('next_previous_links', $get_post_elements))  { ?>
                        
                        <div class="navigation clearfix">
        					<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
        					<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
        				</div>
                <?php } ?>
                
                <?php 
                    if($theme->display('comments', $get_post_elements) && comments_open( get_the_ID() ))  {
                        comments_template('', true); 
                    }
                ?>
                <?php endwhile; ?>
                
            <?php $theme->hook('loop_after'); ?>
            </div><!-- .content -->
            
        <?php $theme->hook('content_after'); ?> 
        </div><!-- #wrap-content .singlepost -->

<div id='singlepost-widget-wrap'>
        <?php
	        if(!dynamic_sidebar('sidebar_singlepost_thenews')) {
	            printf( __( 'The single post widget area. <a href="%s">Click here</a> to add some widgets now.', 'flexithemes' ), get_bloginfo('url') . '/wp-admin/widgets.php' );
	        } ?>
</div><!-- #singlepost-widget-wrap -->
		
    <?php $theme->hook('main_after'); ?> 
    </div><!-- #main-singlepost -->
	
<?php get_footer(); ?>