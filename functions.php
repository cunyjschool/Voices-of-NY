<?php
//ADD MEDIA TYPE TAXONOMY
add_action( 'init', 'create_media_type_taxonomy', 0 );
function create_media_type_taxonomy() {
	if (!taxonomy_exists('media_type')) {
		register_taxonomy( 'media_type', 'post', array( 'hierarchical' => false, 'label' => __('Media Type'), 'query_var' => 'media_type', 'rewrite' => array( 'slug' => 'media_type' ) ) ); 
		wp_insert_term('Audio', 'media_type');
		wp_insert_term('Graphic', 'media_type');
		wp_insert_term('Gallery', 'media_type');
		wp_insert_term('Video', 'media_type');
		wp_insert_term('None', 'media_type');
	}
}
function remove_media_type_meta() {
	remove_meta_box( 'tagsdiv-media_type', 'post', 'side' );
}
add_action( 'admin_menu' , 'remove_media_type_meta' );
function add_media_type_box() {
	add_meta_box('media_type_box_ID', __('Media Type'), 'your_media_type_styling_function', 'post', 'side', 'core');
}
function add_media_type_menus() {
 
	if ( ! is_admin() )
		return;
 
	add_action('admin_menu', 'add_media_type_box');
	
	/* Use the save_post action to save new post data */
	add_action('save_post', 'save_taxonomy_data');
}
add_media_type_menus();


// This function gets called in edit-form-advanced.php
function your_media_type_styling_function($post) {
 
	echo '<input type="hidden" name="taxonomy_noncename" id="taxonomy_noncename" value="' . 
    		wp_create_nonce( 'taxonomy_media_type' ) . '" />';

	// Get all media_type typestaxonomy terms
	$media_types = get_terms('media_type', 'hide_empty=0');
 
?>
<select name='post_media_type' id='post_media_type'>
	<!-- Display media_types as options -->
    <?php 
        $names = wp_get_object_terms($post->ID, 'media_type'); 
        ?>
        <option class='media_type-option' value='' 
        <?php if (!count($names)) echo "selected";?>>Select one</option>
        <?php
	foreach ($media_types as $media_type) {
		if (!is_wp_error($names) && !empty($names) && !strcmp($media_type->slug, $names[0]->slug)) 
			echo "<option class='media_type-option' value='" . $media_type->slug . "' selected>" . $media_type->name . "</option>\n"; 
		else
			echo "<option class='media_type-option' value='" . $media_type->slug . "'>" . $media_type->name . "</option>\n"; 
	}
   ?>
</select>
<?php
}

//SAVE MEDIA TYPE TAXONOMY
function save_taxonomy_data($post_id) {
// verify this came from our screen and with proper authorization.
 
 	if ( !wp_verify_nonce( $_POST['taxonomy_noncename'], 'taxonomy_media_type' )) {
    	return $post_id;
  	}
 
  	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
  	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
    	return $post_id;
 
 
  	// Check permissions
  	if ( 'page' == $_POST['post_type'] ) {
    	if ( !current_user_can( 'edit_page', $post_id ) )
      		return $post_id;
  	} else {
    	if ( !current_user_can( 'edit_post', $post_id ) )
      	return $post_id;
  	}
 
  	// OK, we're authenticated: we need to find and save the data
	$post = get_post($post_id);
	if (($post->post_type == 'post') || ($post->post_type == 'page')) { 
           // OR $post->post_type != 'revision'
			$media_type = $_POST['post_media_type'];
	   wp_set_object_terms( $post_id, $media_type, 'media_type' );
        }
	return $media_type;
}



//ADD VIDEO URL META BOX
add_action( 'add_meta_boxes', 'cd_meta_box_add' );
function cd_meta_box_add()
{
	add_meta_box( 'video-url-box-id', 'Featured Video', 'cd_meta_box_cb', 'post', 'side', 'high' );
}
function cd_meta_box_cb( $post )
{
	$values = get_post_custom( $post->ID );
	$text = isset( $values['video_url_box_text'] ) ? esc_attr( $values['video_url_box_text'][0] ) : '';
	wp_nonce_field( 'video_url_box_nonce', 'meta_box_nonce' );
	?>
	<p>
		<label for="video_url_box_text">Video URL</label>
		<input type="url" name="video_url_box_text" id="video_url_box_text" value="<?php echo $text; ?>" />
		<?php global $wp_embed;
		$post_embed = $wp_embed->run_shortcode('[embed width="260"]' . $text . '[/embed]');
		echo $post_embed;
		?>
	</p>
	<?php	
}


add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'video_url_box_nonce' ) ) return;
	
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;
	
	// now we can actually save the data
	$allowed = array( 
		'a' => array( // on allow a tags
			'href' => array() // and those anchords can only have href attribute
		)
	);
	
	// Probably a good idea to make sure your data is set
	if( isset( $_POST['video_url_box_text'] ) )
		update_post_meta( $post_id, 'video_url_box_text', wp_kses( $_POST['video_url_box_text'], $allowed ) );
}



//ADD SOURCE TAXONOMY
add_action( 'init', 'create_source_taxonomy', 0 );
function create_source_taxonomy() {
	if (!taxonomy_exists('source')) {
		register_taxonomy( 'source', 'post', array( 'hierarchical' => true, 'label' => __('Source'), 'query_var' => 'source', 'rewrite' => array( 'slug' => 'source' ) ) ); 
		wp_insert_term('African Sun Times', 'source');
		wp_insert_term('AL Manassah AL Arabeyah', 'source');
		wp_insert_term('Amerikai Magyar Nepszava', 'source');
		wp_insert_term('Black and Brown News', 'source');
		wp_insert_term('Brooklyn Free Press', 'source');
		wp_insert_term('Bueno, Bonito y Barato', 'source');
		wp_insert_term('City Limits', 'source');
		wp_insert_term('Colorlines', 'source');
		wp_insert_term('Consultorio en Casa', 'source');
		wp_insert_term('Crossings', 'source');
		wp_insert_term('CUNY-TV', 'source');
		wp_insert_term('Desi Talk', 'source');
		wp_insert_term('Diario de Mexico', 'source');
		wp_insert_term('Dominican Times News', 'source');
		wp_insert_term('DTM', 'source');
		wp_insert_term('Ecuador News', 'source');
		wp_insert_term('El Diario La Prensa', 'source');
		wp_insert_term('El Especial/El Especialito', 'source');
		wp_insert_term('El Nacional', 'source');
		wp_insert_term('El Sol News', 'source');
		wp_insert_term('El Tiempo', 'source');
		wp_insert_term('Extra', 'source');
		wp_insert_term('FORWARD/Yiddish Forward', 'source');
		wp_insert_term('Haitian Times', 'source');
		wp_insert_term('Icones dAmerique', 'source');
		wp_insert_term('Indo US News', 'source');
		wp_insert_term('Irish Echo', 'source');
		wp_insert_term('La Tribuna Hispana', 'source');
		wp_insert_term('La Voz', 'source');
		wp_insert_term('Lilith Magazine', 'source');
		wp_insert_term('Little India', 'source');
		wp_insert_term('Manhattan Times', 'source');
		wp_insert_term('Mount Hope Monitor', 'source');
		wp_insert_term('News India Times', 'source');
		wp_insert_term('New Youth Connections', 'source');
		wp_insert_term('Norwoord News', 'source');
		wp_insert_term('Nowy Dziennik', 'source');
		wp_insert_term('Nueva Luz', 'source');
		wp_insert_term('Quisqueya International', 'source');
		wp_insert_term('REPRESENT!', 'source');
		wp_insert_term('Resumen Newspaper', 'source');
		wp_insert_term('Rockaway Family and Kids Mag', 'source');
		wp_insert_term('Sada E Pakistan', 'source');
		wp_insert_term('Sing Tao Daily', 'source');
		wp_insert_term('The Brooklyn Free Press', 'source');
		wp_insert_term('The Immigrants Journal', 'source');
		wp_insert_term('The Indypendent', 'source');
		wp_insert_term('The Riverdale Press', 'source');
		wp_insert_term('The Weekly Bangla Patrika', 'source');
		wp_insert_term('Thikana', 'source');
		wp_insert_term('Times Square.com', 'source');
		wp_insert_term('Urdu Times', 'source');
		wp_insert_term('Vechemiy New York', 'source');
		wp_insert_term('Vishwa Sandesh NY', 'source');
		wp_insert_term('V Novom Svete', 'source');
		wp_insert_term('Westchester Hispano', 'source');
		wp_insert_term('Womens E News', 'source');
		wp_insert_term('World Journal', 'source');
		wp_insert_term('Yevreiski Mir', 'source');
		wp_insert_term('Youth Communication', 'source');
		wp_insert_term('ZAMAN', 'source');
	}
}
function remove_source_meta() {
	remove_meta_box( 'tagsdiv-source', 'post', 'side' );
}
add_action( 'admin_menu' , 'remove_source_meta' );
function add_source_box() {
	add_meta_box('source_box_ID', __('Source'), 'your_source_styling_function', 'post', 'side', 'core');
}
//deleted checkbox styling here




//ADD CSS CLASSES FOR MEDIA TYPE TAXONOMY
add_filter( 'post_class', 'voiceofny_post_class', 10, 3 );
if( !function_exists( 'voiceofny_post_class' ) ) {
    /**
     * Append taxonomy terms to post class.
     * @since 2010-07-10
     */
    function voiceofny_post_class( $classes, $class, $ID ) {
        $taxonomy = 'media_type';
        $terms = get_the_terms( (int) $ID, $taxonomy );
        if( !empty( $terms ) ) {
            foreach( (array) $terms as $order => $term ) {
                if( !in_array( $term->slug, $classes ) ) {
                    $classes[] = $term->slug;
                }
            }
        }
        return $classes;
    }
}

//ADD WIDGETIZED SIDEBAR TO FOOTER
register_sidebar(array(
    'name' => __('Footer', 'flexithemes'),
    'id' => 'sidebar_footer_thenews',
    'description' => __('The footer widget area', 'flexithemes'),
    'before_widget' => '<ul class="wrap-widget"><li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li></ul>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
));

//ADD WIDGETIZED SIDEBAR TO FOOTER COPYRIGHTS AREA
register_sidebar(array(
    'name' => __('Footer Menus', 'flexithemes'),
    'id' => 'sidebar_footermenus_thenews',
    'description' => __('The footer menus widget area', 'flexithemes'),
    'before_widget' => '<ul class="wrap-widget"><li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li></ul>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
));

//ADD WIDGETIZED SIDEBAR TO SINGLE POST
register_sidebar(array(
    'name' => __('Single Post', 'flexithemes'),
    'id' => 'sidebar_singlepost_thenews',
    'description' => __('The single post widget area', 'flexithemes'),
    'before_widget' => '<ul class="wrap-widget"><li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li></ul>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
));

//ADD WIDGETIZED SIDEBAR TO SINGLE PAGE
register_sidebar(array(
    'name' => __('Single Page', 'flexithemes'),
    'id' => 'sidebar_singlepage_thenews',
    'description' => __('The single page widget area', 'flexithemes'),
    'before_widget' => '<ul class="wrap-widget"><li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li></ul>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
));

//ADD WIDGETIZED SIDEBAR TO SINGLE CATEGORY
register_sidebar(array(
    'name' => __('Single Category', 'flexithemes'),
    'id' => 'sidebar_singlecat_thenews',
    'description' => __('The single category widget area', 'flexithemes'),
    'before_widget' => '<ul class="wrap-widget"><li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li></ul>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
));



//ADD CAPTION TO FEATURED IMAGES
function the_post_thumbnail_caption() {
  global $post;

  $thumb_id = get_post_thumbnail_id($post->id);

  $args = array(
	'post_type' => 'attachment',
	'post_status' => null,
	'post_parent' => $post->ID,
	'include'  => $thumb_id
	); 

   $thumbnail_image = get_posts($args);

   if ($thumbnail_image && isset($thumbnail_image[0])) {
     //Uncomment to show thumbnail title
     //echo $thumbnail_image[0]->post_title; 

/*	//Uncomment to show the thumbnail alt field for alt-text
     $alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
     if(count($alt)) 
		echo "<span class='photo-caption'>";
		echo $alt;
		echo "</span><br />";
*/

	//Uncomment to show the thumbnail description 
	 echo "<span class='photo-caption'>";
     echo $thumbnail_image[0]->post_content; 
	 echo "</span><br/>";
	
     //Uncomment to show the thumbnail caption
     echo $thumbnail_image[0]->post_excerpt; 

     
  }
}


// EXCLUDE CATEGORIES FROM BEIGN DISPLAYED
function exclude_post_categories($excl='', $spacer=''){
   $categories = get_the_category($post->ID);
      if(!empty($categories)){
      	$exclude=$excl;
      	$exclude = explode(",", $exclude);
      	foreach ($categories as $child){
      	  $parents = get_category_parents($child->cat_ID, FALSE, ',');
      	  $parents = explode(',', $parents);
      	  foreach($exclude as $excluded){
      	   if (in_array($excluded, $parents)){
      	       $excluded_children[] = $child->cat_ID;
      	   }
      	  }
      	}
		$thecount = count(get_the_category()) - count($excluded_children);
      	foreach ($categories as $cat) {
      		$html = '';
      		if(!in_array($cat->cat_ID, $excluded_children)) {
				$html .= '<a href="' . get_category_link($cat->cat_ID) . '" ';
				$html .= 'title="' . $cat->cat_name . '">' . $cat->cat_name . '</a>';
				if($thecount>1){
					$html .= $spacer;
				}
			$thecount--;
      		echo $html;
      		}
	      }
      }
}


// DISPLAY LATEST UPDATE 
function site_last_updated($d = '') {
	$recent = new WP_Query("showposts=1&orderby=modified&post_status=publish");
	if ( $recent->have_posts() ) {
		while ( $recent->have_posts() ) {
			$recent->the_post();
			$last_update = get_the_modified_date('D, M j Y, G:i T');
		}
		echo "Last Update: ";
		echo $last_update;
	}
	else
		echo 'No posts.';
}


//ADD TRANSLATOR META BOX
add_action( 'add_meta_boxes', 'cd_add_translator_meta' );  
function cd_add_translator_meta()  
{  
    add_meta_box( 'translator-meta', __( 'Translator' ), 'cd_translator_meta_cb', 'post', 'normal', 'high' );  
}
function cd_translator_meta_cb( $post )  
{  
    // Get values for filling in the inputs if we have them.  
    $translator = get_post_meta( $post->ID, '_cd_translator_name', true );  
  
    // Nonce to verify intention later  
    wp_nonce_field( 'save_translator_meta', 'translator_nonce' );  
    ?>  
    <p>  
        <label for="translator-name">Translated by:</label>  
        <input type="text" class="text" id="translator-name" name="_cd_translator_name" value="<?php echo $translator; ?>" />    
    </p>  
    <?php  
  
}
add_action( 'save_post', 'cd_translator_meta_save' );  
function cd_translator_meta_save( $id )  
{  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;  
  
    if( !isset( $_POST['translator_nonce'] ) || !wp_verify_nonce( $_POST['translator_nonce'], 'save_translator_meta' ) ) return;  
  
    if( !current_user_can( 'edit_post' ) ) return;  
  
    $allowed = array(  
        'p' => array()  
    );  
  
    if( isset( $_POST['_cd_translator_name'] ) )  
        update_post_meta( $id, '_cd_translator_name', wp_kses( $_POST['_cd_translator_name'], $allowed ) );  
  
}


//ADD ORIGINAL STORY META BOX
add_action( 'add_meta_boxes', 'cd_add_original_meta' );  
function cd_add_original_meta()  
{  
    add_meta_box( 'original-meta', __( 'Original Story' ), 'cd_original_meta_cb', 'post', 'side', 'high' );  
}
function cd_original_meta_cb( $post )  
{  
    // Get values for filling in the inputs if we have them.  
    $original = get_post_meta( $post->ID, '_cd_original_link', true );  
  
    // Nonce to verify intention later  
    wp_nonce_field( 'save_original_meta', 'original_nonce' );  
    ?>  
    <p>  
        <label for="original-link">Link to original story:</label>
        <input type="url" class="text" id="original-link" name="_cd_original_link" value="<?php echo $original; ?>" />    
    </p>  
    <?php  
  
}
add_action( 'save_post', 'cd_original_meta_save' );  
function cd_original_meta_save( $id )  
{  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;  
  
    if( !isset( $_POST['original_nonce'] ) || !wp_verify_nonce( $_POST['original_nonce'], 'save_original_meta' ) ) return;  
  
    if( !current_user_can( 'edit_post' ) ) return;  
  
    $allowed = array(  
        'p' => array()  
    );  
  
    if( isset( $_POST['_cd_original_link'] ) )  
        update_post_meta( $id, '_cd_original_link', wp_kses( $_POST['_cd_original_link'], $allowed ) );  
  
}


?>