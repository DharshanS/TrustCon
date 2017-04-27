<?php
/*
Plugin Name: LivIcons ShortCodes
Plugin URI: http://livicons.com
Description: This plugin is created for LivIcons (Live Icons) - animated vector icons. It allows insert LivIcons as shortcodes in a TinyMCE editor and/or get the plain HTML LivIcons' code.
Version: 1.1
Author: DeeThemes
Author URI: http://codecanyon.net/user/DeeThemes
*/

/* 
Copyright 2013 | DeeThemes | http://livicons.com | http://codecanyon.net/user/DeeThemes
*/


//Require minimum version of WordPress
function lisc_wordpress_version() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.3", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires WordPress 3.3 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
		}
	}
}
add_action( 'admin_init', 'lisc_wordpress_version' );

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'lisc_livicons_activation');
add_action('admin_init', 'lisc_init' );
add_action('admin_menu', 'lisc_add_options_page');
add_filter( 'plugin_action_links', 'lisc_plugin_action_links', 10, 2 );

//Setting default options when plugin is activated
function lisc_livicons_activation() {
	$full_livicons_list = array('address-book','adjust','alarm','albums','align-center','align-justify','align-left','align-right','anchor','android','angle-double-down','angle-double-left','angle-double-right','angle-double-up','angle-down','angle-left','angle-right','angle-up','angle-wide-down','angle-wide-left','angle-wide-right','angle-wide-up','apple','apple-logo','archive-add','archive-extract','arrow-circle-down','arrow-circle-left','arrow-circle-right','arrow-circle-up','arrow-down','arrow-left','arrow-right','arrow-up','asterisk','balance','ban','barchart','barcode','battery','beer','bell','bing','biohazard','bitbucket','blogger','bluetooth','bold','bolt','bookmark','bootstrap','briefcase','brightness-down','brightness-up','brush','bug','calendar','camcoder','camera','camera-alt','car','caret-down','caret-left','caret-right','caret-up','cellphone','certificate','check','check-circle','check-circle-alt','checked-off','checked-on','chevron-down','chevron-left','chevron-right','chevron-up','chrome','circle','circle-alt','clapboard','clip','clock','cloud','cloud-bolts','cloud-down','cloud-rain','cloud-snow','cloud-sun','cloud-up','code','collapse-down','collapse-up','columns','comment','comments','compass','concrete5','connect','credit-card','crop','css3','dashboard','desktop','deviantart','disconnect','doc-landscape','doc-portrait','download','download-alt','dribbble','drop','dropbox','edit','exchange','expand-left','expand-right','external-link','eye-close','eye-open','eyedropper','facebook','facebook-alt','file-export','file-import','film','filter','fire','firefox','flag','flickr','flickr-alt','folder-add','folder-flag','folder-lock','folder-new','folder-open','folder-remove','font','gear','gears','ghost','gift','github','github-alt','glass','globe','google-plus','google-plus-alt','hammer','hand-down','hand-left','hand-right','hand-up','heart','heart-alt','help','home','html5','ie','image','inbox','inbox-empty','inbox-in','inbox-out','indent-left','indent-right','info','instagram','ios','italic','jquery','key','lab','laptop','leaf','legal','linechart','link','linkedin','linkedin-alt','list','list-ol','list-ul','livicon','location','lock','magic','magic-alt','magnet','mail','mail-alt','map','medal','message-add','message-flag','message-in','message-lock','message-new','message-out','message-remove','microphone','minus','minus-alt','money','moon','more','morph-c-o','morph-c-s','morph-c-t-down','morph-c-t-left','morph-c-t-right','morph-c-t-up','morph-o-c','morph-o-s','morph-o-t-down','morph-o-t-left','morph-o-t-right','morph-o-t-up','morph-s-c','morph-s-o','morph-s-t-down','morph-s-t-left','morph-s-t-right','morph-s-t-up','morph-t-down-c','morph-t-down-o','morph-t-down-s','morph-t-left-c','morph-t-left-o','morph-t-left-s','morph-t-right-c','morph-t-right-o','morph-t-right-s','morph-t-up-c','morph-t-up-o','morph-t-up-s','move','music','myspace','new-window','notebook','opera','pacman','paypal','pen','pencil','phone','piechart','piggybank','pin-off','pin-on','pinterest','pinterest-alt','plane-down','plane-up','playlist','plus','plus-alt','presentation','printer','qrcode','question','quote-left','quote-right','raphael','recycled','reddit','redo','refresh','remove','remove-alt','remove-circle','resize-big','resize-big-alt','resize-horizontal','resize-horizontal-alt','resize-small','resize-small-alt','resize-vertical','resize-vertical-alt','responsive','responsive-menu','retweet','rocket','rotate-left','rotate-right','rss','safari','sandglass','save','scissors','screen-full','screen-full-alt','screen-small','screen-small-alt','screenshot','search','servers','settings','share','shield','shopping-cart','shopping-cart-in','shopping-cart-out','shuffle','sign-in','sign-out','signal','sitemap','sky-dish','skype','sort','sort-down','sort-up','soundcloud','speaker','spinner-five','spinner-four','spinner-one','spinner-seven','spinner-six','spinner-three','spinner-two','star-empty','star-full','star-half','stopwatch','striked','stumbleupon','stumbleupon-alt','sun','table','tablet','tag','tags','tasks','text-decrease','text-height','text-increase','text-size','text-width','thermo-down','thermo-up','thumbnails-big','thumbnails-small','thumbs-down','thumbs-up','timer','trash','tree','trophy','truck','tumblr','twitter','twitter-alt','umbrella','underline','undo','unlink','unlock','upload','upload-alt','user','user-add','user-ban','user-flag','user-remove','users','users-add','users-ban','users-remove','vector-circle','vector-curve','vector-line','vector-polygon','vector-square','video-backward','video-eject','video-fast-backward','video-fast-forward','video-forward','video-pause','video-play','video-play-alt','video-step-backward','video-step-forward','video-stop','vimeo','vk','warning','warning-alt','webcam','wifi','wifi-alt','windows','windows8','wordpress','wordpress-alt','wrench','xing','yahoo','youtube','zoom-in','zoom-out');
	
	$tmp = get_option('lisc_iconslist');
	if( (!is_array($tmp)) || $tmp['chosen_livicons'] == '' ) {
		$arr1 = array( 
			'full_livicons_list' => implode(',', $full_livicons_list),
			'chosen_livicons' => 'adjust' //chosen icons on the first init
		);
		update_option('lisc_iconslist', $arr1);
  	} elseif ( (is_array($tmp)) && $tmp['chosen_livicons'] !== 'adjust' ) {
  		$arr1 = array( 
			'full_livicons_list' => implode(',', $full_livicons_list),
			'chosen_livicons' => $tmp['chosen_livicons']
		);
		update_option('lisc_iconslist', $arr1);
  	} else {
  		$arr1 = array( 
			'full_livicons_list' => implode(',', $full_livicons_list),
			'chosen_livicons' => 'adjust'
		);
		update_option('lisc_iconslist', $arr1);
  	};
  	
	update_option('lisc_iconslist', $arr1);

	$tmp = get_option('lisc_options');
	if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('lisc_options');
		$arr2 = array( 
			'defsize' => '32', //default icon size in pixels
			'defhtmltag' => 'span', //default html tag of element with class livicon
			'defcolor' => '#555555', //default color value
			'deforiginalcolor' => 'false', //set icon color to 'original' value (true or false)
			'defhovercolor' => '#000000', //default color on hover
			'defchangecoloronhover' => 'true', //change color on hover or do not( true or false)
			'defeventtype' => 'hover', //default event type ('hover' or 'click')
			'defanimated' => 'true', //animated by default or not (true or false)
			'deflooped' => 'false', //animation is repeated or not (true or false)
			'defonparent' => 'false', //trigger events on parent element containing a livicon (true or false)
			'morphduration' => '200', //transition time for morph icons (milliseconds)
			'hoverduration' => '200',//transition time for changing color on hover (milliseconds)
			'activeclass' => 'activeicon', //name of the class for active state of icons
			'activeparentclass' => 'active', //name of the parent's class for active state of icons
			'defactivecolor' => '#ff0000', //default color for active state class
			'in_widget' => 'true', //LivIcons in widgets
			'in_comments' => 'false', //LivIcons in comments
			'in_excerpt' => 'false', //LivIcons in excerpts
			'chk_default_options_db' => ''
		);
		update_option('lisc_options', $arr2);
	}
}

// Init plugin options to white list the options
function lisc_init(){
	register_setting( 'lisc_plugin_options', 'lisc_options', 'lisc_validate_options' );
	register_setting( 'lisc_livicons_list_options', 'lisc_iconslist' );
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function lisc_validate_options($input) {
	//Strip html from textboxes
	$input['activeclass'] =  wp_filter_nohtml_kses($input['activeclass']); // Sanitize textbox input (strip html tags, and escape characters)
	$input['activeparentclass'] =  wp_filter_nohtml_kses($input['activeparentclass']); // Sanitize textbox input (strip html tags, and escape characters)
	return $input;
}

// Display a Settings link on the main Plugins page
function lisc_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$lisc_links = '<a href="'.get_admin_url().'options-general.php?page=livicons-shortcodes/livicons-shortcodes.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $lisc_links );
	}
	return $links;
}

//Define scripts in a HEAD section in frontend
add_action('wp_enqueue_scripts', 'lisc_scripts');
function lisc_scripts() {
	wp_register_style('livicons_css', plugins_url('result/customlivicons.css', __FILE__),false,'1.1');
	wp_enqueue_style('livicons_css');
	wp_enqueue_script('json2');
	wp_enqueue_script('jquery');
	wp_register_script('raphael_core', plugins_url('js/raphael-min.js', __FILE__),false,'2.1.0');
	wp_enqueue_script('raphael_core');
	wp_register_script('livicons_js', plugins_url('result/customlivicons.js', __FILE__),array('jquery','raphael_core'),'1.1');
	wp_enqueue_script('livicons_js');
}

//Make LivIcons available in widgets, comments and excerpts
$options = get_option('lisc_options');
if( isset($options['in_widget']) && $options['in_widget'] === 'true') {
add_filter('widget_text', 'do_shortcode'); 
};
if( isset($options['in_comments']) && $options['in_comments'] === 'true') {	
add_filter('comment_text', 'do_shortcode'); 
};
if( isset($options['in_excerpt']) && $options['in_excerpt'] === 'true') {
add_filter('the_excerpt', 'do_shortcode'); 
};
unset($options);

//Shortcode function itself
function lisc_livicon_shortcode ($atts = null, $content = null) {
	extract( shortcode_atts( array(
		'htmltag' => 'span',
		'id' => null,
		'addclass' => null,
		'addactiveclass' => null,
		'name' => null,
		'size' => null,
		'color' => null,
		'hovercolor' => null,
		'animate' => null,
		'iteration' => null,
		'duration' => null,
		'loop' => null,
		'eventtype' => null,
		'onparent' => null,
		'styles' => null,
		'link' => null,
		'target' => null
		), $atts ) );
		
		if (!is_null($id)) {
			$id = ' id="'.$id.'"';
		}
		if (!is_null($addclass)) {
				$addclass = ' '.$addclass;
		}
		if (!is_null($addactiveclass)) {
				$addactiveclass = ' '.$addactiveclass;
		}
		if (!is_null($name)) {
			$name = ' data-n="'.$name.'"';
		}
		if (!is_null($size)) {
			$size = ' data-s="'.$size.'"';
		}
		if (!is_null($color)) {
			$color = ' data-c="'.$color.'"';
		}
		if (!is_null($hovercolor)) {
			$hovercolor = ' data-hc="'.$hovercolor.'"';
		}
		if (!is_null($animate)) {
			$animate = ' data-a="'.$animate.'"';
		}
		if (!is_null($iteration)) {
			$iteration = ' data-i="'.$iteration.'"';
		}
		if (!is_null($duration)) {
			$duration = ' data-d="'.$duration.'"';
		}
		if (!is_null($loop)) {
			$loop = ' data-l="'.$loop.'"';
		}
		if (!is_null($eventtype)) {
			$eventtype = ' data-et="'.$eventtype.'"';
		}
		if (!is_null($onparent)) {
			$onparent = ' data-op="'.$onparent.'"';
		}
		if (!is_null($styles)) {
				$styles = ' style="'.$styles.'"';
		}
		if (!is_null($target)) {
				$target = ' target="'.$target.'"';
		}
		
	if (!isset($link)) {
		return '<' .$htmltag. ' class="livicon' .$addclass.$addactiveclass. '"' .$id.$name.$size.$color.$hovercolor.$animate.$iteration.$duration.$loop.$eventtype.$onparent.$styles. '>' . do_shortcode($content) . '</' .$htmltag. '>';
	} else {
		return '<a href="' .$link. '"' .$target. '><' .$htmltag. ' class="livicon' .$addclass.$addactiveclass. '" ' .$id.$name.$size.$color.$hovercolor.$animate.$iteration.$duration.$loop.$eventtype.$onparent.$styles. '>' . do_shortcode($content) . '</' .$htmltag. '></a> ';
	}
}
add_shortcode('livicon', 'lisc_livicon_shortcode');
add_shortcode('liviconmorph', 'lisc_livicon_shortcode');

//functions for TinyMCE plugin
function lisc_insert_livicon($plugin_array){
	$plugin_array['lisc_insert_livicon'] = plugins_url() . '/livicons-shortcodes/mce/insert_livicon.js';
	return $plugin_array;
}
function lisc_add_button($buttons){
	array_push($buttons, "|", "lisc_insert_livicon");
	return $buttons;
}
function lisc_custom_button(){
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;
	if( get_user_option('rich_editing') == 'true'){
		add_filter('mce_external_plugins', 'lisc_insert_livicon');
		add_filter('mce_buttons', 'lisc_add_button');
	}
}
add_action('init', 'lisc_custom_button');

// Add plugin menu page
function lisc_add_options_page() {
	global $lisc_settings_page;
	$lisc_settings_page = add_options_page('LivIcons Shortcodes Plugin Options Page', 'LivIcons Settings', 'manage_options', __FILE__, 'lisc_display_settings');
	add_action( 'admin_enqueue_scripts', 'lisc_on_load_settings_page' );
}

//Register styles and scripts for admin backend
function lisc_on_load_settings_page($hook) {
	global $lisc_settings_page;
	if( $hook != $lisc_settings_page ) 
		return;
	wp_register_style('minicolors_styles', plugins_url('css/jquery.minicolors.css', __FILE__),false,'2.0');
	wp_enqueue_style('minicolors_styles');
	wp_register_style('livicons_shortcodes', plugins_url('css/livicons-shortcodes.css', __FILE__),false,'1.1');
	wp_enqueue_style('livicons_shortcodes');

	wp_enqueue_script('json2');
	wp_enqueue_script('jquery');
	wp_register_script('raphael_core', plugins_url('js/raphael-min.js', __FILE__),false,'2.1.0');
	wp_enqueue_script('raphael_core');
	wp_register_script('livicons_init', plugins_url('js/livicons-wp-1.1.min.js', __FILE__),array('jquery','raphael_core'),'1.1');
	wp_enqueue_script('livicons_init');
	wp_register_script('inputCtl', plugins_url('js/jquery.inputCtl.min.js', __FILE__),array('jquery'),'0.1');
	wp_enqueue_script('inputCtl');
	wp_register_script('minicolors', plugins_url('js/jquery.minicolors.js', __FILE__),array('jquery'),'2.0');
	wp_enqueue_script('minicolors');
	wp_register_script('bootstrap_tooltip', plugins_url('js/bootstrap-tooltip.js', __FILE__),array('jquery'),'2.3.2');
	wp_enqueue_script('bootstrap_tooltip');
}

//Visual part of LivIcons settings
function lisc_display_settings(){
	$filename_data = plugin_dir_path(__FILE__).'js/source/livicons_data_init.txt';
	if (!file_exists($filename_data) || !is_readable($filename_data)) {
		wp_die('<div class="alert alert-error"><h3>"livicons_data_init.txt" could not be read</h3>Cannot read the sourse file <strong>'.$filename_data.'</strong><br> Please check that the file exists and/or you need to make this file readable. See <a href="http://codex.wordpress.org/Changing_File_Permissions">the Codex</a> for more information.</div>');
	};
	$filename_core = plugin_dir_path(__FILE__).'js/source/livicons_core_code.txt';
	if (!file_exists($filename_core) || !is_readable($filename_core)) {
		wp_die('<div class="alert alert-error"><h3>"livicons_core_code.txt" could not be read</h3>Cannot read the sourse file <strong>'.$filename_core.'</strong><br> Please check that the file exists and/or you need to make this file readable. See <a href="http://codex.wordpress.org/Changing_File_Permissions">the Codex</a> for more information.</div>');
	};
	$filename = plugin_dir_path(__FILE__).'result/customlivicons.js';
	if (!file_exists($filename) || !is_writable($filename)) {
		wp_die('<div class="alert alert-error"><h3>"customlivicons.js" could not be created</h3>The file <strong>'.$filename.'</strong> cannot be saved.<br>Please check that the file exists and/or you need to make this file writable before you can use the plugin. See <a href="http://codex.wordpress.org/Changing_File_Permissions">the Codex</a> for more information.</div>');
	};	
?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"><br></div>
	<h2>Livicons Global Settings</h2>
	<form method="post" action="options.php">
	<h3>Step 1 - Set the global default options</h3>
		<?php settings_fields('lisc_plugin_options'); ?>
		<?php $options = get_option('lisc_options'); ?>
		<table class="form-table">
			<tr valign="top">
				<td colspan="2"><h4>These options can be changed individually for any LivIcon in a TinyMCE editor:</h4></td>
				<td colspan="2"><h4>These options can NOT be changed individually for an icon. They affect all LivIcons on the site:</h4></td>
			</tr>
			<tr valign="top">
				<th scope="row">Default HTML tag for a container of LivIcon:</th>
				<td>
					<label><input name="lisc_options[defhtmltag]" type="radio" value="span" <?php checked( 'span', $options['defhtmltag'] ); ?>> &lt;span> tag</label><br>
					<label><input name="lisc_options[defhtmltag]" type="radio" value="div" <?php checked( 'div', $options['defhtmltag'] ); ?>> &lt;div> tag</label><br>
					<label><input name="lisc_options[defhtmltag]" type="radio" value="i" <?php checked( 'i', $options['defhtmltag'] ); ?>> &lt;i> tag</label>
				</td>
				<td scope="row">Default morphs' transition time:</td>
				<td>
					<input class="small-text" type="text" id="morphduration" name="lisc_options[morphduration]" value="<?php echo $options['morphduration']; ?>">
					<span class="description">milliseconds</span>
				</td>

			</tr>
			<tr valign="top">
				<th scope="row">Default icons size:</th>
				<td>
					<input class="small-text" type="text" id="defsize" name="lisc_options[defsize]" value="<?php echo $options['defsize']; ?>"><span class="description"> pixels</span>
				</td>
				<td scope="row">Default 'hover' transition time:</td>
				<td>
					<input class="small-text" type="text" id="hoverduration" name="lisc_options[hoverduration]" value="<?php echo $options['hoverduration']; ?>">
					<span class="description">milliseconds</span>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Default icons color:</th>
				<td>
						<label><input name="lisc_options[deforiginalcolor]" type="radio" value="true" <?php checked( 'true', $options['deforiginalcolor'] ); ?>> original</label><br>
						<label><input name="lisc_options[deforiginalcolor]" type="radio" value="false" <?php checked( 'false', $options['deforiginalcolor'] ); ?>>
							<input class="minicolors" id="defcolor" type="text" name="lisc_options[defcolor]" value="<?php echo $options['defcolor']; ?>">
						</label>
				</td>
				<td scope="row">Default active class name for all icons:</td>
				<td>
					<input type="text" name="lisc_options[activeclass]" value="<?php echo $options['activeclass']; ?>">
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Change color on hover:</th>
				<td>
						<label><input name="lisc_options[defchangecoloronhover]" type="radio" value="false" <?php checked( 'false', $options['defchangecoloronhover'] ); ?>> no</label><br>
						<label><input name="lisc_options[defchangecoloronhover]" type="radio" value="true" <?php checked( 'true', $options['defchangecoloronhover'] ); ?>>
							<input class="minicolors" id="defhovercolor" type="text" name="lisc_options[defhovercolor]" value="<?php echo $options['defhovercolor']; ?>">
						</label>
				</td>
				<td scope="row">Default active class name for all icons' parents:</td>
				<td>
					<input  type="text" name="lisc_options[activeparentclass]" value="<?php echo $options['activeparentclass']; ?>">
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Are icons animated by default:</th>
				<td>
						<label><input name="lisc_options[defanimated]" type="radio" value="true" <?php checked( 'true', $options['defanimated'] ); ?>> yes</label><br>
						<label><input name="lisc_options[defanimated]" type="radio" value="false" <?php checked( 'false', $options['defanimated'] ); ?>> no</label>
				</td>
				<td scope="row">Color for active state of all icons:</td>
				<td>
					<input class="minicolors" type="text" id="defactivecolor" name="lisc_options[defactivecolor]" value="<?php echo $options['defactivecolor']; ?>">
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Default event type:</th>
				<td>
						<label><input name="lisc_options[defeventtype]" type="radio" value="hover" <?php checked( 'hover', $options['defeventtype'] ); ?>> hover event</label><br>
						<label><input name="lisc_options[defeventtype]" type="radio" value="click" <?php checked( 'click', $options['defeventtype'] ); ?>> click event</label>
				</td>
				<td scope="row">Allow shortcodes in:</td>
				<td>
					<label>
						<input type="checkbox" name="lisc_options[in_widget]" value="true" <?php if (isset($options['in_widget'])) { checked('true', $options['in_widget']); } ?>> in widgets
					</label>
					<br>
					<label>
						<input type="checkbox" name="lisc_options[in_comments]" value="true" <?php if (isset($options['in_comments'])) { checked('true', $options['in_comments']); } ?>> in comments
					</label>
					<br>
					<label>
						<input type="checkbox" name="lisc_options[in_excerpt]" value="true" <?php if (isset($options['in_excerpt'])) { checked('true', $options['in_excerpt']); } ?>> in excerpts
					</label>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Is animation looped by default <strong>(please be careful with this option)</strong>:</th>
				<td>
						<label><input name="lisc_options[deflooped]" type="radio" value="true" <?php checked( 'true', $options['deflooped'] ); ?>> yes</label><br>
						<label><input name="lisc_options[deflooped]" type="radio" value="false" <?php checked( 'false', $options['deflooped'] ); ?>> no</label>
				</td>
				<td><br>For changing "customlivicons.css" file please
				</td>
				<td><br><a href="plugin-editor.php?file=livicons-shortcodes/result/customlivicons.css">click here</a>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Bind an event handler to parent container with a LivIcon:</th>
				<td>
						<label><input name="lisc_options[defonparent]" type="radio" value="true" <?php checked( 'true', $options['defonparent'] ); ?>> yes</label><br>
						<label><input name="lisc_options[defonparent]" type="radio" value="false" <?php checked( 'false', $options['defonparent'] ); ?>> no</label>
				</td>
			</tr>

			<tr valign="top" style="border-top:#dddddd 1px solid;">
				<th scope="row">Database Options</th>
				<td colspan="3">
					<label><input name="lisc_options[chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_db'])) { checked('1', $options['chk_default_options_db']); } ?>> Restore defaults upon plugin deactivation/reactivation</label>
					<br><span class="description">Only check this if you want to reset plugin settings upon Plugin reactivation</span>
				</td>
			</tr>
		
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"><strong>&nbsp;&nbsp;&nbsp;IMPORTANT! After saving default options please do not forget click "Save Result File" button of Step 2 on the bottom of the page even if you did not change chosen icons.</strong>
		</p>
	</form>
	<br>
	
	<h3>Step 2 - Choose LivIcons you plan to use on your site</h3>
	<p class="description"><strong>Note: </strong>Please keep in mind that if you uncheck any previously checked icon, this icon will not be rendered on your site any more.</p>
<script type="text/javascript">
//<![CDATA[
	function TestIconCheck() {
		var namevalues = jQuery('.icon input:checkbox:checked').map(function () {
			return this.value;
			}).get();
		var returnval;
		if (namevalues.length>0) {
			returnval = true;
		} else {
			alert('Please select at least one icon!');
			returnval = false;
		}
		return returnval;
	}
//]]>
</script>
	<form method="post" action="" onSubmit="return TestIconCheck();">
	<?php
	$options = get_option('lisc_options');
	$iconlist_options = get_option('lisc_iconslist');
	$full_livicons_list = explode(',', $iconlist_options['full_livicons_list']);
	$livicons_data_init = file_get_contents($filename_data);
	$livicons_core_code = file_get_contents($filename_core);
	$livicons_data_init = json_decode($livicons_data_init, true); 
	
	//preparing parameters for resulting file
	if(!empty($_POST['check_list'])) {
		$checked_icons = $_POST['check_list'];
		$global_defaults = array(
			'size' => $options['defsize'],
			'animated' => $options['defanimated'],
			'loop' => $options['deflooped'],
			'eventtype' => $options['defeventtype'],
			'onparent' => $options['defonparent']
		);
		if ($options['deforiginalcolor'] == 'true') {
			$global_defaults['color'] = 'original';
		} else {
			$global_defaults['color'] = $options['defcolor'];
		};

		//creating custom LivIcons data
		foreach ($checked_icons as $key) {
				$livicons_data[$key] = $livicons_data_init[$key];
		}
		unset($key);
		
		$livicons_data_result = '/*PLEASE DO NOT EDIT THIS FILE*/;jQuery(document).ready(function(){var defname="'.$checked_icons[0].'",defsize='.$global_defaults["size"].',defcolor="'.$global_defaults["color"].'",defhovercolor="'.$options['defhovercolor'].'",defchangecoloronhover='.$options['defchangecoloronhover'].',defeventtype="'.$global_defaults["eventtype"].'",defanimated='.$global_defaults["animated"].',deflooped='.$global_defaults["loop"].',defonparent='.$global_defaults["onparent"].',morphduration='.$options['morphduration'].',hoverduration='.$options['hoverduration'].',activeclass="'.$options['activeclass'].'",activeparentclass="'.$options['activeparentclass'].'",defactivecolor="'.$options['defactivecolor'].'",liviconsdatainit=JSON.stringify('.json_encode($livicons_data);
		
		$checked_icons = implode(',', $_POST['check_list']);
		$iconlist_options['chosen_livicons'] = $checked_icons;
		update_option('lisc_iconslist', $iconlist_options);

		//saving result file to a disk
		$res1 = file_put_contents ($filename, $livicons_data_result, LOCK_EX);
		$res2 = file_put_contents ($filename, $livicons_core_code, FILE_APPEND | LOCK_EX);
		if ($res1 != false && $res2 != false) {
			echo '<input type="hidden" value="true" name="saveresult" id="saveresult">';
		} else {
			echo '<input type="hidden" value="false" name="saveresult" id="saveresult">';
		};
		
	};//end check $_POST empty
	?>
	<div>
	<?php 
	//first init of LivIcons on admin LivIcons Global Settings page
	$chosen_livicons = explode(',', $iconlist_options['chosen_livicons']);
	
	foreach ($full_livicons_list as $value) {
		if(in_array($value, $chosen_livicons)) {
			$html_livicons = '<div class="icon" title="' .$value. '"><input class="checkbox" id="livicon_' .$value. '" type="checkbox" name="check_list[]" value="' .$value. '" checked><label for="livicon_' .$value. '"><div class="livicon" data-n="' .$value. '"></div></label></div>';
		} else {
			$html_livicons = '<div class="icon" title="' .$value. '"><input class="checkbox" id="livicon_' .$value. '" type="checkbox" name="check_list[]" value="' .$value. '"><label for="livicon_' .$value. '"><div class="livicon" data-n="' .$value. '"></div></label></div>';
		}
		echo $html_livicons;
	}
	unset($value);?>
	</div>
	<div style="clear:both;"></div>
	
	<p class="description"><strong>Note: </strong>Please keep in mind that if you uncheck any previously checked icon, this icon will not be rendered on your site any more.</p>
	<p class="submit"><input type="submit" id="saveicons" class="button-primary" value="Save Result File"><span><strong>&nbsp;&nbsp;&nbsp;Please be sure to save the result file to all options take affect.</strong></span></p>
	</form>
	

<script type="text/javascript">
//<![CDATA[
	jQuery(document).ready(function($){
		$('#defcolor').minicolors({swatchPosition:"left"});
		$('#defhovercolor').minicolors({swatchPosition:"left"});
		$('#defactivecolor').minicolors({swatchPosition:"left"});
		$('#defsize').inputCtl({minVal: 1, step: 1});
		$('#morphduration').inputCtl({minVal: 10, step: 10});
		$('#hoverduration').inputCtl({minVal: 10, step: 10});
		if ($('#saveresult').val() === 'true') {
			alert('File saved successfully!');
		} else if ($('#saveresult').val() === 'false') {
			alert('ERROR! Cannot write the result file. Please try again.');
		};
	});
//]]>
</script>
<script>
//<![CDATA[
	!function ($) {
		$('.icon').tooltip();
	}(jQuery)
//]]>
</script>

</div>

<?php } ?>