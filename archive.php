<?php global $theme; get_header(); ?>

    <div id="main" class="singlecat-main">
    <?php $theme->hook('main_before'); ?>
    
        <div id="wrap-content" class="singlecat-content">
        <?php $theme->hook('content_before'); ?>
        
            <div class="content">
            
                <h2 class="generic"><?php
                
                /* If this is a category archive */ 
               if (is_category()) { printf( __( '<span></span>', 'flexithemes' ), single_cat_title( '', false ) ); 
                    $the_template_part = 'categories';
               
               /* If this is a tag archive */ 
               } elseif (is_tag()) { printf( __( 'Tag: <span>%s</span>', 'flexithemes' ), single_tag_title( '', false ) ); 
                    $the_template_part = 'categories';
                        
               /* If this is a daily archive */ 
               } elseif (is_day()) { printf( __( 'Daily Archives: <span>%s</span>', 'flexithemes' ), get_the_date() ); 
                    $the_template_part = 'categories';
                
                /* If this is a monthly archive */ 
                } elseif (is_month()) { printf( __( 'Monthly Archives: <span>%s</span>', 'flexithemes' ), get_the_date('F Y') );
                    $the_template_part = 'categories';
                  
                /* If this is a yearly archive */ 
                } elseif (is_year()) { printf( __( 'Yearly Archives: <span>%s</span>', 'flexithemes' ), get_the_date('Y') );
                    $the_template_part = 'categories';
                
                /* If this is an author archive */ 
                } elseif (is_author()) { printf( __( '', 'flexithemes' ),  get_the_author() );
                    $the_template_part = 'categories';

				/* If this is an source archive */ 
	            } elseif (is_tax('source')) { 
	                $the_template_part = 'categories';
					$taxo_text = '';
					$taxo_list = strip_tags(get_the_term_list( $post->ID, 'source'));
					  if ( '' != $taxo_list )
					  $taxo_text .= $taxo_list;
					printf( __( 'Source: <span>%s</span>', 'flexithemes' ),  $taxo_text );
                
                /* If this is a general archive */ 
                } else { _e( 'Blog Archives', 'flexithemes' ); $the_template_part = 'categories';} 
            ?></h2>
            
                <?php
                    $theme->options['template_part'] = $the_template_part;
                    get_template_part('loop', $the_template_part);
                ?> 
            </div><!-- .content -->
            
        <?php $theme->hook('content_after'); ?> 
    </div><!-- #wrap-content .singlecat-content -->

	<div id='singlecat-widget-wrap'>
	        <?php
		        if(!dynamic_sidebar('sidebar_singlecat_thenews')) {
		            printf( __( 'The category widget area. <a href="%s">Click here</a> to add some widgets now.', 'flexithemes' ), get_bloginfo('url') . '/wp-admin/widgets.php' );
		        } ?>
	</div><!-- #singlecat widget-wrap -->
        
    <?php $theme->hook('main_after'); ?> 
    </div><!-- #main .singlecat-->
        
<?php get_footer(); ?>