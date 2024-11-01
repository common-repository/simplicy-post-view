<?php
add_action('wp_head', 'spw_css_custom');
function spw_css_custom(){ 
wp_register_style('style',false,'screen');
echo '
<style>
'.get_option('simplicy_css_code').'

</style>

';
wp_enqueue_style( 'style' );
} 
?>
<?php
$spw_css =  ' 
/* Mise en forme du titre des articles */
.simplicy-title-shortcode a { color:#000; font-size:16px; }
.simplicy-title-shortcode a:hover { color:#999999; }

/* Mise en forme du  bloc contenat le lien lire la suite*/
.simplicy-read-more{
 float:right; 
 padding-right:10px; 
 margin-bottom:10px;
 }
/* Mise en forme du lien lire la suite avant le passage de la souris*/
.simplicy-read-more a {
color:#dedede;
 }
 
 /* Mise en forme du lien lire la suite au passage de la souris*/
 .simplicy-read-more a:hover {
color:#000;

 }

';
?>
<?php
// create custom plugin settings menu
add_action('admin_menu', 'omr_create_menu');

function omr_create_menu() {

	//create new top-level menu
	add_submenu_page( 'tools.php','Simplicy post view option', 'Simplicy post view', 'administrator', __FILE__, 'omr_settings_page', 'favicon.ico');

	//call register settings function
	add_action( 'admin_init', 'spw_register_mysettings' );
}



function spw_register_mysettings() {
	//register our settings
	register_setting( 'omr-settings-group', 'simplicy_read_code' );
}


function omr_settings_page() {
?>
<style>
.spw-parametre{ padding:10px; background:#FFFFFF; border-top:#999999 solid 1px; border-bottom: #999999 solid 1px;}
.param-txt {width:130px; float:left;} 
</style>

<div class="wrap">

<h2>Simplicy post view Shortcode</h2>



<form method="post" action="options.php">

    <?php settings_fields('omr-settings-group'); ?>
    <table class="form-table">

        <tr valign="top">
        <th scope="row"><?php echo _e('Text link to post', 'simplicypostview')?></th>
        <td><input type="text" name="simplicy_read_code" value="<?php echo get_option('simplicy_read_code'); ?>"/></td>
        </tr>
    </table>

    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

    </p>

</form>
 <table class="form-table">

        <tr valign="top">
        <th scope="row"><?php echo '<h2>' . __('Shortcode use', 'simplicypostview') . '</h2>'; ?></th>
          </tr>
          <tr>
        <td><div class="spw-parametre">
        <?php echo '<p><dt class="param-txt"><strong>title :</strong></dt><code>' . __(' Title of the column that contains your post', 'simplicypostview') . '</code></p>'; ?>
       	<?php echo '<p><dt class="param-txt"><strong>collum_width :</strong> </dt><code>' . __('Width of the column that contains your post', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>position :</strong></dt><code>' . __(' Position of the column that contains your post right, left, center', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>margin_right :</strong></dt><code>' . __('Right margin of the column that contains your post', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>margin_left :</strong></dt><code>' . __('Left margin of the column that contains your post', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>cat :</strong></dt><code>' . __('Post by category id example cat="5"', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>numberpost :</strong></dt><code>' . __('Post number to be displayed', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>meta :</strong></dt><code>' . __('Display the date of publication of the post and number of comment yes/no', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>read :</strong></dt><code>' . __('Display link to post on/off', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>image_width :</strong></dt><code>' . __(' Image width post', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>image_height :</strong></dt><code>' . __(' Image Height post', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>image_position :</strong></dt><code>' . __(' Image position: none, left, right', 'simplicypostview') . '</code></p>'; ?>
        <?php echo '<p><code>' . __('----------------------------------------------------------------------------------') . '</code></p>'; ?>
        <?php echo '<p><dt class="param-txt"><strong>[spw_clear]: </strong></dt><code>' . __('Horizontal separation', 'simplicypostview') . '</code></p>'; ?>
        </div></td>

        </tr>
  		<tr valign="top">
        <th scope="row"><?php echo '<h2>' . __('Exemple shortcode use', 'simplicypostview') . '</h2>'; ?></th>
          </tr>
           <tr>
        <td><div class="spw-parametre">
        <?php echo '<p>' . __('A single column in the center', 'simplicypostview') . '</p>'; ?>
		<?php echo '<p><code>[spw title="Your Title" position="center" collum_width="300" cat="1" numberpost="4" meta="no" excerpt="30" image_width="300" image_height="200"]</code></p>'; ?>
        <br/>
        <?php echo '<p>' . __('Two columns', 'simplicypostview') . '</p>'; ?>
		<?php echo '<p><code>[spw title="Your Title" position="left" collum_width="250" cat="1" numberpost="4" meta="yes" excerpt="30" image_width="250" image_height="190"]</code></p>'; ?>
        <?php echo '<p><code>[spw title="Your Title" position="left" collum_width="250" margin_left="30" cat="1" numberpost="4" meta="yes" excerpt="30" image_width="250" image_height="190"]</code></p>'; ?>
         <br/>
        <?php echo '<p>' . __('Four columns and two up two down', 'simplicypostview') . '</p>'; ?>
		<?php echo '<p><code>[spw title="Your Title" position="left" collum_width="250" cat="1" numberpost="4" meta="yes" excerpt="30" image_width="250" image_height="190"]</code></p>'; ?>
        <?php echo '<p><code>[spw title="Your Title" position="left" collum_width="250" margin_left="30" cat="1" numberpost="4" meta="yes" excerpt="30" image_width="250" image_height="190"]</code></p>'; ?>
        <?php echo '<p><code>[spw_clear]</code></p>'; ?>
        <?php echo '<p><code>[spw title="Your Title" position="left" collum_width="250" cat="1" numberpost="4" meta="yes" excerpt="30" image_width="250" image_height="190"]</code></p>'; ?>
        <?php echo '<p><code>[spw title="Your Title" position="left" collum_width="250" margin_left="30" cat="1" numberpost="4" meta="yes" excerpt="30" image_width="250" image_height="190"]</code></p>'; ?>
       	
        </div></td>

        </tr>
    </table>
</div>
<?php 
} 
// shortcode article 
function simplicy_post_view_shortcode($atts, $content = null) {
        extract(shortcode_atts(array(
				"collum_width"=> '300',
				"position"=> 'center',
                "numberpost" => '3',
				"margin_right" =>'',
				"margin_left" =>'',
                "cat" => '',
				"meta"=> 'yes',
				"read"=> 'on',
				"excerpt" => '15',
				"image_width"=>'150',
				"image_height"=>'120',
				"image_position"=>'',
				"title" =>''
				
        ), $atts));
		if ($position == 'center' ) $var_1= 'margin:0 auto;' ;
		if ($position == 'right' ) $var_2= 'right' ;
		if ($position == 'left' ) $var_3= 'left' ;
		
		if ($image_position == 'none' ) $var_6= '' ;
		if ($image_position == 'right' ) $var_7= 'float:right;' ;
		if ($image_position == 'left' ) $var_8= 'float:left;' ;
		
        global $post;
        $myposts = get_posts('numberposts='.$numberpost.'&order=DESC&orderby=post_date&category='.$cat);
		// variables
		$simplicy_read_code = get_option('simplicy_read_code');
		$simplicy_title_code = get_option('simplicy_title_code');
		// / variables end
        
		if (empty($title))
		{ $retour='<div style="margin-right:'.$margin_right.'px;margin-left:'.$margin_left.'px;width:'.$collum_width.'px; float:'.$var_2.''.$var_3.';'.$var_1.'" id="simplicy-shortcode-articles">'; }
		else {
		$retour='<div style="margin-right:'.$margin_right.'px;margin-left:'.$margin_left.'px;width:'.$collum_width.'px; float:'.$var_2.''.$var_3.';'.$var_1.'" id="simplicy-shortcode-articles">
				<h3 class="simplicy-shortcode-title">'.$title.'</h3>';
		}
        foreach($myposts as $post) :
                setup_postdata($post);
				 
		 if ($meta == 'yes' ) $var_4= '<p class="meta-simplicy-shortcode">'.get_the_date('j F, Y').' | '.$post->comment_count.'</p>' ;
		 if ($read == 'on' ) $var_5= '<span class="simplicy-read-more"><a  href="'.get_permalink().'">'.$simplicy_read_code.'</a></span>' ;
		 		$retour.='<div  class="simplicy-post-view-item">';
  						 
			
			 
			 if (has_post_thumbnail())  $thumb = get_post_thumbnail_id(); $img_url = wp_get_attachment_url( $thumb,'full' ); $image = sp_post_view_resize( $img_url, $image_width, $image_height, true ) ;
			 
			 
						

			 $retour.='<div class="shortcode-thumb"><a href="'.get_permalink().'">
			 
			 <img style="'.$var_8.''.$var_7.'" class="simplicy-shortcode-img" src="'.$image.'"/>
			 </a></div>
			 <dt class="simplicy-title-shortcode"><a href="'.get_permalink().'"><strong>'.the_title("","",false).'</strong></a></dt>
			 '.$var_4.'
			 <div class="simplicy-excerpt-shortcode">'.simplicy_content($excerpt).'</div></br>
			 '.$var_5.'
			 </div>';
        endforeach;
		wp_reset_query(); 
        $retour.='</div> ';
        return $retour;
		
}
add_shortcode("spw", "simplicy_post_view_shortcode");

// fonction clear div 

function simplicy_shortcode_clear($atts, $content = null) {
	return '
<div class="simplicy-shortcode-clear"></div>
';
}
add_shortcode('spw_clear', 'simplicy_shortcode_clear');



function wpex_clean_shortcodes($content){   
$array = array (
    '<p>[' => '[', 
    ']</p>' => ']', 
    ']<br />' => ']'
);
$content = strtr($content, $array);
return $content;
}
add_filter('the_content', 'wpex_clean_shortcodes');
?>
