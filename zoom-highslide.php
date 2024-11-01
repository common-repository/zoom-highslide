<?php
/*
Plugin Name: Zoom-Highslide
Plugin URI: http://ziming.org/dev/zoom-highslide
Description: This plugin integrate image zoom and highslide effect to make photo show beautiful.
Version: 1.2.1
Highslide Version: 4.2
Author: Suny Tse
Author URI: http://ziming.org
*/

/*  Copyright 2010  Suny Tse  ( email : message@ziming.org )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define("IMAGE_FILETYPE", "(bmp|gif|jpeg|jpg|png)", true);

init_check();
function init_check(){
		$options = get_option('zoom_highslide');
		if(!$options['widthRestriction']){
				$options['widthRestriction'] = '640';
		}
		if(!$options['heightRestriction']){
				$options['heightRestriction'] = '1000';
		}
		if(!$options['show_interval']){
				$options['show_interval'] = 5000;
		}
		if(!$options['controler_position']){
				$options['controler_position'] = 'top center';
		}
		if(!$options['background_opacity']){
				$options['background_opacity'] = 0.8;
		}
    if(!update_option('zoom_highslide', $options)){
    		add_option('zoom_highslide', $options);
    }
}

/*******************************  Setting Page   *******************************/

function zoom_highslide_add_option() {
		if (function_exists('zoom_highslide_option')) {
			add_options_page('zoom_highslide', 'Zoom-HighSlide',8, 'zoom_highslide', 'zoom_highslide_option');
		}//add_options_page(page_title, menu_title, access_level/capability, file, [function]);
}
function zoom_highslide_option(){
		$options = get_option('zoom_highslide');
		if (isset($_POST['update_setting'])) {
			$options['widthRestriction'] = $_POST['widthRestriction'];
			$options['heightRestriction'] = $_POST['heightRestriction'];
			$options['show_interval'] = $_POST['show_interval'];
			$options['controler_position'] = $_POST['controler_position'];
			$options['background_opacity'] = $_POST['background_opacity'];
			update_option('zoom_highslide', $options);
			echo '<div id="message" class="updated fade"><p>';
			echo 'Setting Updated...';
			echo '</p></div>';
		}
		else if (isset($_POST['set_default'])) {
			$options['widthRestriction'] = '640';
			$options['heightRestriction'] = '1000';
			$options['show_interval'] = 5000;
			$options['controler_position'] = 'top center';
			$options['background_opacity'] = 0.8;
			update_option('zoom_highslide', $options);
			echo '<div id="message" class="updated fade"><p>';
			echo 'Default setting loaded...';
			echo '</p></div>';		    
		}		
?>
<div class="wrap">
<h2>Zoom HighSlide Option</h2>
<form method="post">
	<fieldset name="set_widthRestriction">
		<p style="font-weight:bold">Width Restriction(e.g 640) : 
		<input type="text" name="widthRestriction" size="20" value="<?php echo $options['widthRestriction'];?>"/ >
		</p>
	</fieldset>
	<fieldset name="set_heightRestriction">
		<p style="font-weight:bold">Height Restriction(e.g 1000) : 
		<input type="text" name="heightRestriction" size="20" value="<?php echo $options['heightRestriction'];?>"/ >
		</p>
	</fieldset>
	
	<fieldset name="set_interval">
		<p style="font-weight:bold">Show Interval(e.g 4000) : 
		<input type="text" name="show_interval" size="20" value="<?php echo $options['show_interval'];?>"/ >
		</p>
	</fieldset>

	<fieldset name="set_controlerPosition">
		<p style="font-weight:bold">Controler Position(e.g top center) : 
		<input type="text" name="controler_position" size="20" value="<?php echo $options['controler_position'];?>"/ >
		</p>
	</fieldset>
	
		<fieldset name="set_dimmingOpacity">
		<p style="font-weight:bold">Background Opacity(0 ~ 1) : 
		<input type="text" name="background_opacity" size="20" value="<?php echo $options['background_opacity'];?>"/ >
		</p>
	</fieldset>
	
	<div class="submit">
		<input type="submit" name="set_default" value="Load Default" />
		<input type="submit" name="update_setting" value="Update Setting" />
	</div>
</form>
</div>
<?php
}
add_action('admin_menu', 'zoom_highslide_add_option');

/*******************************  HighSlide Init   *******************************/

function zoom_highslide_javascript() {
	if ( !function_exists('wp_enqueue_script') || is_admin() ) return;
	wp_enqueue_script('prototype');
	wp_enqueue_script('scriptaculous-effects');
}

add_action('init', 'zoom_highslide_javascript');

function zoom_highslide_init() {
	$url = get_bloginfo('wpurl');
	$options = get_option('zoom_highslide');
	wp_print_scripts('jquery');
?>
	<link rel="stylesheet" href="<?php echo $url; ?>/wp-content/plugins/zoom-highslide/highslide.css" type="text/css" media="screen" />
	<!--[if lte IE 6]>
	<link rel="stylesheet" href="<?php echo $url; ?>/wp-content/plugins/zoom-highslide/highslide-ie6.css" type="text/css" media="screen" />
	<![endif]-->
	<script type="text/javascript" src="<?php echo $url; ?>/wp-content/plugins/zoom-highslide/js/highslide-full.packed.js"></script>
	<script type="text/javascript">
	hs.graphicsDir = '<?php echo $url; ?>/wp-content/plugins/zoom-highslide/graphics/';
	hs.align = 'center';
	hs.transitions = ['expand', 'crossfade'];
	hs.outlineType = 'rounded-white';
  hs.wrapperClassName = 'controls-in-heading';
	hs.showCredits=false;
	hs.fadeInOut = true;
	hs.dimmingOpacity = <?php echo $options['background_opacity'];?>;

	// Add the controlbar
	if (hs.addSlideshow) hs.addSlideshow({
		//slideshowGroup: 'group1',
		interval: <?php echo $options['show_interval'];?>,
		repeat: false,
		useControls: true,
		fixedControls: false,
		overlayOptions: {
			opacity: 1,
			position: <?php echo '\''.$options['controler_position'].'\'' ?>,
			hideOnMouseOut: true
		}
	});
</script>
<script type="text/javascript" src="<?php echo $url; ?>/wp-content/plugins/zoom-highslide/js/jquery.lazyload.js"></script>
<script type="text/javascript">
		jQuery(document).ready(
				function($){
						$(".post img").lazyload({
     						placeholder : "<?php echo $url; ?>/wp-content/plugins/zoom-highslide/js/grey.gif",
     						effect      : "fadeIn"
						});
				});
</script>
<?php 
}
add_action('wp_head', 'zoom_highslide_init');

/*******************************  Photo Zoom Init   *******************************/
function wp_zoom_init() {
		$url = get_bloginfo('wpurl');
		$options = get_option('zoom_highslide');
?>

		<script type="text/javascript">
    		var widthRestriction = <?php echo $options['widthRestriction'];?>; 
     		var heightRestriction = <?php echo $options['heightRestriction'];?>; 
		</script>
		<script type="text/javascript" src="<?php echo $url; ?>/wp-content/plugins/zoom-highslide/js/zoom.js"></script>

<?php 
}
add_action('wp_head', 'wp_zoom_init');

function wp_zoom($string) {
		if(is_feed()){
			return $string;
		}else{
  			$pattern = '/(<img(.*?)src="([^"]*.)'.IMAGE_FILETYPE.'"(.*?)\>)/ie';
  			$replacement = 'stripslashes("<a\2href=\"\3\4\" class=\"highslide\" onclick=\"return hs.expand(this)\"><img src=\"\3\4\" \5></a>")';
				return preg_replace($pattern, $replacement, $string);
		}
}

add_filter('the_excerpt', 'wp_zoom');
add_filter('the_content', 'wp_zoom');

?>