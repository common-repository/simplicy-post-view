<?php
/*
 * Plugin Name: Simplicy post view
 * Version: 2.1
 * Plugin URI: http://www.naxialis.com/simplicy-post-view
 * Description: afficher vos article avec miniature dans votre sidebar.
 * Author: Naxialis
 * Author URI: http://www.naxialis.com/
/*  Copyright YEAR  LEBEON FREDERIC  (email : fred@naxialis.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 
 
 


// Language
function plugin_name_load_plugin_textdomain() {
 
	$domain = 'simplicypostview';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	
	load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
 
}
add_action( 'init', 'plugin_name_load_plugin_textdomain' );


 // css
  wp_enqueue_style('simplicy-post-view', '/wp-content/plugins/simplicy-post-view/css/simplicy-post-view.css');
  wp_register_style('getnaxcssgrid', '/wp-content/plugins/simplicy-post-view/css/getnaxcssgrid.css');
  
  wp_enqueue_style('getnaxcssgrid');
 //*** Shortcodes 
require_once(dirname(__FILE__).'/func/function.php');
class Widget_Simplicy_Post extends WP_Widget  //class /!\
{
	function Widget_Simplicy_Post() 
	{		
		$widget_ops = array('classname' => 'Widget_Simplicy_Post', 'description' => __( "Afficher et personnaliser l&acute;affichage de vos articles dans la sidebar") );		
		$control_ops = array('width' => 400, 'height' => 300);		
		$this->WP_Widget('Widget_Simplicy_Post', __('Simplicy post view'), $widget_ops, $control_ops);
	}

	function widget($args, $instance){
		extract($args);    
		$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']); 
		$item = empty($instance['item']) ? '0' : $instance['item']; 
		$nb_posts = empty($instance['posts_nb']) ? 5 : $instance['posts_nb'] ; 
		$thumb_w = empty($instance['thumb_w']) ? null : $instance['thumb_w']; 
		$thumb_h = empty($instance['thumb_h']) ? null : $instance['thumb_h']; 
		$lenght = empty($instance['lenght']) ? null : $instance['lenght'];
		$title_size = empty($instance['title_size']) ? '14' : $instance['title_size'];
		$select_align_img = empty($instance['select_align_img']) ? 'left' : $instance['select_align_img'];
		
		 
		
		
		// Find dropdown value categorie
      if(strpos($item, 'p:') !== FALSE) {
        $post = str_replace('p:', '', $item);
      } else if(strpos($item, 'c:') !== FALSE) {
        $category = str_replace('c:', '', $item);
      }     
      if($category != 0) {
        $data = spp_get_post('category', $category);
        $data = $data[0];
      } else if($post != 0) {
        $data = spp_get_post('post', $post);
        $data = $data[0];
      } else {
        // If no post or category is selected, use the most recent post.
        $data = spp_get_post('post');
        $data = $data[0];
        if(!$data) {
          $title = "Simple Post view";
          $length = 100;
          $data = (object)array(
            'post_title' => 'Error!',
            'post_content' => 'This widget needs configuration',
          );
        }
    }
	

	
// fin categorie
		
		echo $before_widget;		
		if ( $title )
			echo $before_title . $title . $after_title;
						
	

		
		// affichage du widget
		echo "<ul class='SP-post'>" ;
		if ($item != null)
		{
			if (is_numeric($category))
			{
				query_posts('p='.$post.'&cat='.$category.'&showposts='.$nb_posts);
			}
			else
			{
				query_posts('p='.$post.'&category_name='.$category.'&showposts='.$nb_posts) ;
			}
			if (have_posts())
			{
				
			while (have_posts()) : the_post(); ?>
                     <?php if ( $instance['mod_gallery'] ) { ?>
                     <!-- case gallerie cocher pas de séprateur d'afficher -->
                     <?php } else { ?>
                     <?php echo "<div class='simplicy-post-clear'></div>"; ?>
                     <?php } ?> 
					 <!-- affichage de la miniature -->
                     <?php if ( $instance['view_thumbs'] ) : ?>
                     <?php
					if (has_post_thumbnail()) { ?>
                    <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
					<?php $thumb = get_post_thumbnail_id(); $img_url = wp_get_attachment_url( $thumb,'full' ); $image = sp_post_view_resize( $img_url, $thumb_w, $thumb_h, true ); ?>
					<dt><img style="float:<?php 
											if ( $select_align_img == 'Left' ) {
											echo 'left';
											} else if ( $select_align_img == 'None' ) {
											echo 'none';
											} else if ( $select_align_img == 'Right' ) {
											echo 'right'; }?>;" class="simplicy-post-img" src="<?php echo $image ?>" border="0"/><dt>
				   </a>
                   <?php } else { } // Si il n'y a pas d'image on affiche rien ?>
                    <?php endif; ?>
                    <!-- caption image -->
                   <?php if ( $instance['caption_view'] ) : ?>
                   <dt class="wp-caption-text-simplicy" style="width: <?php echo $thumb_w ; ?>px;"><?php if (the_post_thumbnail_caption_sp_view()) { echo the_post_thumbnail_caption_sp_view() ; }?> </dt>
                   <?php endif; ?>
                   <!-- affichage du titre -->
                   <?php if ( $instance['title_view'] ) : ?>
                   <dt class="simplicy-post-title" style="font-size:<?php echo $title_size ?>px;" ><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(the_title()); ?>"><strong><?php the_title(); ?></strong>									                    </a></dt>
                   <?php endif; ?>
                   <!-- affichage de la date -->
                   <?php if ( $instance['date'] ) : ?>
                   <dt class="simplicy-date_post"> Le <?php the_time('j F, Y') ?>| <strong><?php comments_number('0','1 ','%' );?></strong> </dt>
				   <?php endif; ?>
                   
                   <!-- affichage de l'extrait -->
                   <?php if ( $instance['excerpt'] ) : ?>
                   <?php echo simplicy_content($instance["excerpt_length"]); ?>
                   <?php endif; ?>
                   <!-- affichage du lien lire la suite -->
                   <?php if ( $instance['read_post_link'] ) : ?>
                   <dt class="sp-read-link"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"> <?php echo $instance["read_post_link_txt"] ;?></a></dt>                   
                   <?php endif; ?>
                   <?php if ( $instance['mod_gallery'] ) { ?>
                   <!-- case gallerie cocher pas de séprateur d'afficher -->
                   <?php } else { ?>
                   <?php echo "<div class='simplicy-post-content'></div>"; ?>
                   <?php } ?>  
				   <?php endwhile ;
				   wp_reset_query();
				   // the excerpt		
				   remove_filter('excerpt_length', $new_excerpt_length);
				   if ( $instance['mod_gallery'] ) { 
                   echo "<div class='simplicy-post-gallery'></div>";
                   } else { }  				
				   echo "</ul>" ;
				
			}
		}
		
		echo $after_widget;
	}

	function update($new_instance, $old_instance)
	{
		//on enregistre la variable 'titre'
		$instance = $old_instance; 		
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		//on enregistre la variable 'category'
		$instance['category'] = strip_tags(stripslashes($new_instance['category']));
		$instance['post_id'] = strip_tags(stripslashes($new_instance['post_id']));
		//on enregistre la variable 'posts'
		$instance['posts_nb'] = strip_tags(stripslashes($new_instance['posts_nb']));		
		$instance['thumb_w'] = strip_tags(stripslashes($new_instance['thumb_w']));		
		$instance['thumb_h'] = strip_tags(stripslashes($new_instance['thumb_h']));
		$instance['excerpt'] = strip_tags(stripslashes($new_instance['excerpt']));
		$instance['read_post_link'] = strip_tags(stripslashes($new_instance['read_post_link']));
		$instance['read_post_link_txt'] = strip_tags(stripslashes($new_instance['read_post_link_txt']));
		$instance['view_thumbs'] = strip_tags(stripslashes($new_instance['view_thumbs']));
		$instance['excerpt_length'] = strip_tags(stripslashes($new_instance['excerpt_length']));
		$instance['item'] = strip_tags(stripslashes($new_instance['item']));
		$instance['date'] = strip_tags(stripslashes($new_instance['date']));
		$instance['title_view'] = strip_tags(stripslashes($new_instance['title_view']));
		$instance['title_size'] = strip_tags(stripslashes($new_instance['title_size']));
		$instance['mod_gallery'] = strip_tags(stripslashes($new_instance['mod_gallery']));
		$instance['caption_view'] = strip_tags(stripslashes($new_instance['caption_view']));
		$instance['select_align_img'] = strip_tags(stripslashes($new_instance['select_align_img']));

		

		return $instance;

	
	$post = $post_old; // Restore the post object.
	}
	

	function form($instance) {
		//les valeurs par défaut sont définies ici, par exemple 'posts'=>'5' défini le nombre de posts à afficher à 5 par défaut
		$instance = wp_parse_args( (array) $instance, array('title'=>'', 'category'=>'', 'posts'=>'5') );
		

		//on stocke les valeurs, en s'assurant qu'ils vont s'afficher correctement
		$title = htmlspecialchars($instance['title']);
		$category = htmlspecialchars($instance['category']);
		$posts_nb = htmlspecialchars($instance['posts_nb']);
		$thumb_w = htmlspecialchars($instance['thumb_w']);
		$thumb_h = htmlspecialchars($instance['thumb_h']);
		$item = htmlspecialchars($instance['item']);
		$post = htmlspecialchars($instance['post_id']);
		$title_size = htmlspecialchars($instance['title_size']);
		$select_align_img = htmlspecialchars($instance['select_align_img']);
		

	
		
		

		echo '<p style="text-align:left;"><label for="' . $this->get_field_name('title') . '">' . _e('Title','simplicypostview') . ' <input style="width:100%;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>'; ?>
        
			<?php //La catégorie ?>         
     <p>
    <label style=" margin-bottom:0; padding-bottom:0;" for="<?php echo $this->get_field_name('item'); ?>"><?php echo _e('Select category : ','simplicypostview'); ?><p style="font-size:9px; padding-top:0; margin-top:0;"><em><?php echo _e('Leave blank to show all categories','simplicypostview'); ?></em></p></label>
    
    <select name="<?php echo $this->get_field_name('item'); ?>" id="<?php echo $this->get_field_id('item'); ?>">
          <option value="">  </option>
        <?php foreach(spp_get_dropdown() as $category) : ?>
          <option style="width: 280px;" <?php echo ('c:' . $category['category_id'] == $instance['item']) ? 'selected' : '' ?> value="c:<?php echo $category['category_id']; ?>">
          Catégorie: <?php echo $category['category_name']; ?> 
    
          </option>

        <?php foreach($category['children'] as $post): ?>
          <option <?php echo ('p:' . $post['post_id'] == $instance['item']) ? 'selected' : '' ?> value="p:<?php echo $post['post_id']; ?>">
            Article- <?php echo $post['post_name']; ?>
          </option>
        <?php endforeach; ?>
      	<?php endforeach; ?>
    </select>
  </p>
  <p>
	<?php //Le nombre de posts à montrer

	echo '<p style="text-align:left;"><label for="' . $this->get_field_name('posts_nb') . '">' . __(' Post number to display : ','simplicypostview') . ' <input style="width:10%;" id="' . $this->get_field_id('posts') . '" name="' . $this->get_field_name('posts_nb') . '" type="text" value="' . $posts_nb . '" /></label></p>'; ?>
</p>
<p>	
	<?php // afficher la date et le nombre de commentaire ?>    
			<label for="<?php echo $this->get_field_id("date"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("date"); ?>" name="<?php echo $this->get_field_name("date"); ?>"<?php checked( (bool) $instance["date"], true ); ?> />
				<?php _e( 'Show date and the number of comments','simplicypostview' ); ?>
			</label>
</p>
<p>	
	<?php // afficher le titre ?>    
			<label for="<?php echo $this->get_field_id("title_view"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("title_view"); ?>" name="<?php echo $this->get_field_name("title_view"); ?>"<?php checked( (bool) $instance["title_view"], true ); ?> />
				<?php _e( 'View post titles','simplicypostview' ); ?>
			</label>
</p>
<p>
	<?php // Taille du titre ?>
				<label>
				<?php _e('Size post titles : ','simplicypostview'); ?>
				<?php echo '<label for="' . $this->get_field_name('title_size') . '"><input style="width:10%;" id="' . $this->get_field_id('title_size') . '" name="' . $this->get_field_name('title_size') . '" type="text" value="' . $title_size . '" />px <em>'._e( '(14px default)','simplicypostview').'</em></label>'; ?>
				</label>
</p>
<p>	
	<?php // afficher extrait ?>    
<label for="<?php echo $this->get_field_id("excerpt"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("excerpt"); ?>" name="<?php echo $this->get_field_name("excerpt"); ?>"<?php checked( (bool) $instance["excerpt"], true ); ?> />
				<?php _e( 'Display excerpt','simplicypostview' ); ?>
			</label>
</p>
	<?php // longueur de l'article ?>
<p>
			<label for="<?php echo $this->get_field_id("excerpt_length"); ?>">
				<?php _e( 'Excerpt length (words):','simplicypostview' ); ?>
			</label>
			<input style="text-align: center;" type="text" id="<?php echo $this->get_field_id("excerpt_length"); ?>" name="<?php echo $this->get_field_name("excerpt_length"); ?>" value="<?php echo $instance["excerpt_length"]; ?>" size="3" />
</p>
<p>	
	<?php // afficher le lien lire la suite ?>    
			<label for="<?php echo $this->get_field_id("read_post_link"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("read_post_link"); ?>" name="<?php echo $this->get_field_name("read_post_link"); ?>"<?php checked( (bool) $instance["read_post_link"], true ); ?> />
				<?php _e( 'Display link read more','simplicypostview' ); ?>
			</label>
</p>
	<?php // Texte du lien ?>
<p>
			<label for="<?php echo $this->get_field_id("read_post_link_txt"); ?>">
				<?php _e( 'Text for the link:','simplicypostview' ); ?>
			</label>
			<input style="text-align: center;" type="text" id="<?php echo $this->get_field_id("read_post_link_txt"); ?>" name="<?php echo $this->get_field_name("read_post_link_txt"); ?>" value="<?php echo $instance["read_post_link_txt"]; ?>" size="20" />
</p>
        
	<?php // afficher une vignette ?>    
<label for="<?php echo $this->get_field_id("view_thumbs"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("view_thumbs"); ?>" name="<?php echo $this->get_field_name("view_thumbs"); ?>"<?php checked( (bool) $instance["view_thumbs"], true ); ?> />
				<?php _e( 'Display a thumbnail','simplicypostview' ); ?>
			</label>
    	
	<?php //dimention de la vignette ?>
<p>
			
				<p><?php _e('Size of the thumbnail:','simplicypostview'); ?></p>
				<?php echo '<label for="' . $this->get_field_name('thumb_w') . '">' . _e('Width:','simplicypostview') . '</label><br /><input style="width:10%;float:left;" id="' . $this->get_field_id('thumb_w') . '" name="' . $this->get_field_name('thumb_w') . '" type="text" value="' . $thumb_w . '" />'; ?>
                <br /><br />
				<?php echo '<label for="' . $this->get_field_name('thumb_h') . '">' . _e('Height:','simplicypostview') . '</label><br /><input style="width:10%;float:left;" id="' . $this->get_field_id('thumb_h') . '" name="' . $this->get_field_name('thumb_h') . '" type="text" value="' . $thumb_h . '" /><br /><br />Indiquez seulement la valeur sans <em>px</em><br />'; ?>
				
			
</p>
	<?php // Alignement de l'image ?>

<p>
		<label for="<?php echo $this->get_field_id('select_align_img'); ?>"><?php _e('Image Position','simplicypostview'); ?></label>
		<select name="<?php echo $this->get_field_name('select_align_img'); ?>" id="<?php echo $this->get_field_id('select_align_img'); ?>">
			<?php
			$options = array('Left', 'None', 'Right');
			foreach ($options as $option) {
				echo '<option value="' . $option . '" id="' . $option . '"', $select_align_img == $option ? ' selected="selected"' : '', '>', $option, '</option>';
			}
			?>
		</select>
</p>

<p>	
	<?php // afficher la légende de l'image ?>    
			<label for="<?php echo $this->get_field_id("caption_view"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("caption_view"); ?>" name="<?php echo $this->get_field_name("caption_view"); ?>"<?php checked( (bool) $instance["caption_view"], true ); ?> />
				<?php _e( 'Show images description','simplicypostview' ); ?>
			</label>
</p>       
<p>	
	<?php // Mode Gallery ?>    
			<label for="<?php echo $this->get_field_id("mod_gallery"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("mod_gallery"); ?>" name="<?php echo $this->get_field_name("mod_gallery"); ?>"<?php checked( (bool) $instance["mod_gallery"], true ); ?>  />
				<?php _e( 'Check this box to turn gallery mod','simplicypostview' ); ?>
			</label>
</p>
        <br /> <br />


<?php

	}
}




// ******************************************************** fonction image ***************************************************************
function sp_post_view_resize( $url, $width, $height = null, $crop = null, $single = true ) {
	
	//validate inputs
	if(!$url OR !$width ) return false;
	
	//define upload path & dir
	$upload_info = wp_upload_dir();
	$upload_dir = $upload_info['basedir'];
	$upload_url = $upload_info['baseurl'];
	
	//check if $img_url is local
	if(strpos( $url, $upload_url ) === false) return false;
	
	//define path of image
	$rel_path = str_replace( $upload_url, '', $url);
	$img_path = $upload_dir . $rel_path;
	
	//check if img path exists, and is an image indeed
	if( !file_exists($img_path) OR !getimagesize($img_path) ) return false;
	
	//get image info
	$info = pathinfo($img_path);
	$ext = $info['extension'];
	list($orig_w,$orig_h) = getimagesize($img_path);
	
	//get image size after cropping
	$dims = image_resize_dimensions($orig_w, $orig_h, $width, $height, $crop);
	$dst_w = $dims[4];
	$dst_h = $dims[5];
	
	//use this to check if cropped image already exists, so we can return that instead
	$suffix = "{$dst_w}x{$dst_h}";
	$dst_rel_path = str_replace( '.'.$ext, '', $rel_path);
	$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";
	
	//if orig size is smaller
	if($width >= $orig_w) {
		
		if(!$dst_h) :
			//can't resize, so return original url
			$img_url = $url;
			$dst_w = $orig_w;
			$dst_h = $orig_h;
			
		else :
			//else check if cache exists
			if(file_exists($destfilename) && getimagesize($destfilename)) {
				$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
			} 
			//else resize and return the new resized image url
			else {
				$resized_img_path = image_resize( $img_path, $width, $height, $crop );
				$resized_rel_path = str_replace( $upload_dir, '', $resized_img_path);
				$img_url = $upload_url . $resized_rel_path;
			}
			
		endif;
		
	}
	//else check if cache exists
	elseif(file_exists($destfilename) && getimagesize($destfilename)) {
		$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
	} 
	//else, we resize the image and return the new resized image url
	else {
		$resized_img_path = image_resize( $img_path, $width, $height, $crop );
		$resized_rel_path = str_replace( $upload_dir, '', $resized_img_path);
		$img_url = $upload_url . $resized_rel_path;
	}
	
	//return the output
	if($single) {
		//str return
		$image = $img_url;
	} else {
		//array return
		$image = array (
			0 => $img_url,
			1 => $dst_w,
			2 => $dst_h
		);
	}
	
	return $image;
}

// Fin fonction image

function the_post_thumbnail_caption_sp_view() {
  global $post;

  $thumbnail_id    = get_post_thumbnail_id($post->ID);
  $thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

  if ($thumbnail_image && isset($thumbnail_image[0])) {
    echo $thumbnail_image[0]->post_excerpt;
  }
}




// Excerpt length filter
	 function simplicy_content($limit) {
     $excerpt = explode(' ', get_the_excerpt(), $limit);
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
      } else {
        $excerpt = implode(" ",$excerpt);
      } 
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
	  
	 
	  
      return $excerpt;
    }
		
// Excerpt filter html tags
	 function simplicy_content_tags($limit) {
      $content = explode(' ', get_the_content(), $limit);
	  
      if (count($content)>=$limit) {
        array_pop($content);
        $content = implode(" ",$content).'';
      } else {
        $content = implode(" ",$content);
      } 
	  $content = preg_replace('/<img[^>]+./','', $content);
      $content = apply_filters('the_content', $content);
	   
      $content = str_replace( array( '<p>&nbsp;</p>' ), '', $content );
	  
   
      return $content;
    }



/**
 * Get all posts or all posts from a category
 */
function spp_get_all_posts($category = NULL) {
  global $wpdb;
  $query =
    "SELECT ID, post_title, post_content, post_date, post_status, guid, term_id
     FROM {$wpdb->posts}
     LEFT JOIN {$wpdb->term_relationships}
     ON object_id = ID
     LEFT JOIN {$wpdb->term_taxonomy}
     ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
     WHERE post_status = 'publish'";
     if($category != NULL) {
       $query .= " AND {$wpdb->term_taxonomy}.term_id = " . $category;
     }
     $query .= " AND post_type = 'post'
     GROUP BY ID
     ORDER BY post_date
     ;";
  $data = $wpdb->get_results($query);
  return $data;
}



/**
 * Select a specific post or the latest post from a category
 */
function spp_get_post($type, $selector = NULL) {
  global $wpdb;
  if($selector == NULL) {
    $data = $wpdb->get_results(
      "SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid
       FROM {$wpdb->posts}
       LEFT JOIN {$wpdb->term_relationships}
       ON object_id = ID
       WHERE ID = (SELECT max(ID) FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish')
       LIMIT 1;"
    );
  } else {
    switch($type) {
      case 'category':
        $data = $wpdb->get_results(
          "SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid, term_id
           FROM {$wpdb->posts}
           LEFT JOIN {$wpdb->term_relationships}
           ON object_id = ID
           LEFT JOIN {$wpdb->term_taxonomy}
           ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
           WHERE term_id = $selector
           AND post_status = 'publish'
           ORDER BY post_date
           DESC LIMIT 1;"
        );
        break;

      case 'post':
        $data = $wpdb->get_results(
          "SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid
           FROM {$wpdb->posts}
           LEFT JOIN {$wpdb->term_relationships}
           ON object_id = ID
           WHERE ID = $selector
           AND post_status = 'publish'
           LIMIT 1;"
        );
        break;
    }
  }
  return $data;
}

/**
 * Get all categories
 */
function spp_get_categories() {
 global $wpdb;
  $categories = $wpdb->get_results(
    "SELECT {$wpdb->terms}.term_id, name FROM {$wpdb->terms}
     LEFT JOIN {$wpdb->term_taxonomy}
     ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
     WHERE {$wpdb->term_taxonomy}.taxonomy = 'category'
     AND {$wpdb->term_taxonomy}.count > 0;"
  );
  return $categories;
}

function spp_get_dropdown() {
 $categories = spp_get_categories();
  $i = 0;
  foreach($categories as $category) {
    $posts = spp_get_all_posts($category->term_id);
    $select[$i]['category_name'] = $category->name;
    $select[$i]['category_id'] = $category->term_id;
    $j = 0;
    foreach($posts as $post) {
      $select[$i]['children'][$j]['post_name'] = $post->post_title;
      $select[$i]['children'][$j]['post_id'] = $post->ID;
      $j++;
    }
    $i++;
  }
  return $select;
}

function affichageCategorieInit() //donnez un nom qui vous parle -pas de prérequis
{
    register_widget('Widget_Simplicy_Post'); //le nom de la classe
}
add_action('widgets_init', 'affichageCategorieInit'); //le nom de la fonction définit juste au dessus