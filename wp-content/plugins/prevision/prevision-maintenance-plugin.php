<?php /*

**************************************************************************

Plugin Name:  Prevision Maintenance Plugin (Share On Theme123.Net)
Plugin URI:   http://www.wp-3sqrd.com
Version:      1.33
Description:  Maintenance Plugin
Author:       Darryl Holtby
Author URI:   http://www.wp-3sqrd.com

**************************************************************************/

class WP3S_Prevision {
	
	var $_settings;
	var $_options_pagename = 'wp3s_prevision_options';
	var $_exception_urls = array('wp-login.php', 'async-upload.php', '/plugins/', 'wp-admin/', 'upgrade.php', 'trackback/', 'feed/');
	var $location_folder;
	var $menu_page;
	var $update_name = 'prevision/prevision-maintenance-plugin.php';
	var $pluginversion;
	
	function WP3S_Prevision() {
		return $this->__construct();
	}
	
	function __construct() {
	
		require_once('post-type/prevision-slider.php');
		require_once('post-type/prevision-quote.php');
		require_once('fonts/fonts.php');
		
		$this->pluginversion = '1.33';
		$this->_settings = get_option('wp3s_prevision_settings') ? get_option('wp3s_prevision_settings') : array();
		$this->location_folder = trailingslashit(WP_PLUGIN_URL) . dirname(plugin_basename(__FILE__));
		
		$this->_set_standard_values();
		
		add_action('admin_menu', array(&$this, 'create_menu_link'));
		add_action('init', array(&$this, 'maintenance_active'), 100);
		
		// PV slider
		add_action('init','wp3s_pvslider_post_type'); // register PV slider post-type
		add_action('load-post-new.php', 'wp3s_pvslider_metabox_init'); // initialize PV metaboxes on add page
		add_action('load-post.php', 'wp3s_pvslider_metabox_init'); // initialize PV metaboxes on edit page
		add_filter('admin_post_thumbnail_html', 'wp3s_pvslider_featured_image_note'); // add note below PV Featured Image
		add_filter('manage_wp3s_pvslider_posts_columns' , 'wp3s_pvslider_columns'); // add PV columns for thumbnail, categories, etc.
		add_action('manage_posts_custom_column' , 'wp3s_pvslider_columns_content'); // add PV content to the new columns
		// PV Quote
		add_action('init','wp3s_pvquote_post_type'); // register PV slider post-type
		add_filter('manage_wp3s_pvquote_posts_columns' , 'wp3s_pvquote_columns'); // add PV columns for thumbnail, categories, etc.
		add_action('manage_posts_custom_column' , 'wp3s_pvquote_columns_content'); // add PV content to the new columns
		
		wp_enqueue_script('jquery');	
		// Google Web Font Loader (for live preview in Theme Options)
		wp_enqueue_script( 'google-webfont-loader', 'http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js', false, null ); // null - don't mess with Google Web Fonts URL by adding version
		
		wp_enqueue_style('wp3s_prevision-admin-icon', $this->location_folder . '/css/admin-icon.css', false, $this->pluginversion);
	}
		
	function add_settings_link($links){
		$settings = '<a href="' . admin_url('options-general.php?page=wp3s_prevision_options') . '">' . __('Settings') . '</a>';
		array_unshift($links, $settings);
		return $links;
	}
	
	function output_activation_warning() {
?>
		<div id="message" class="error"><p><?php echo __('Prevision plugin is not active. Activate it here.','wp3s_prevision'); ?></p></div>
<?php
	}
	
	function create_menu_link() {
		$this->menu_page = add_options_page('Prevision Plugin Options', 'Prevision Plugin', 'manage_options', $this->_options_pagename, array(&$this, 'build_settings_page'));
		add_action("admin_print_scripts-{$this->menu_page}", array(&$this, 'plugin_page_js'));
		add_action("admin_head-{$this->menu_page}", array(&$this, 'plugin_page_css'));
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'), 10, 2);
	}
	
	function build_settings_page() {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.','wp3s_prevision'));
		}
		
		if (isset($_REQUEST['saved'])) {
			if ($_REQUEST['saved'])
				echo '<div id="message" class="updated fade"><p><strong>' . __('Prevision settings saved','wp3s_prevision') . '</strong></p></div>';
		}
		
		if (isset($_POST['wp3s_prevision_settings_saved']))
			$this->_save_settings_todb($_POST);
			
		$meta = $this->_settings['wp3s_prevision_logo'];
		$meta_video = $this->_settings['wp3s_prevision_video_file'];
		$meta_video_image = $this->_settings['wp3s_prevision_video_image'];
?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>WP-3Sqrd Prevision Plugin Options</h2>
			
			<form name="wp3s_prevision_form" method="post">
				<table class="form-table wp3s_metabox">
					<tr valign="top" class="wp3s-type-file">
						<th scope="row">
							<label for="wp3s_prevision_logo"><?php echo __('Logo URL','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_logo" type="text" id="wp3s_prevision_logo" value="<?php echo ($this->_settings['wp3s_prevision_logo']); ?>" class="wp3s_upload_file" />
							<input class="wp3s_upload_button button" type="button" value="Upload File" />
							<input class="wp3s_upload_file_id" type="hidden" id="wp3s_prevision_logo_id" name="wp3s_prevision_logo_id" value=""/>
							<p class="wp3s_metabox_description"><?php echo __('Input the URL to your logo image.','wp3s_prevision'); ?></p>
							<div id="wp3s_prevision_logo_status" class="wp3s_media_status">
							<?php if ( $meta != '' ) {
								$check_image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $meta );
								if ( $check_image ) {
							?>
									<div class="img_status">
										<img src="<?php echo $meta; ?>" alt="" />
										<a href="#" class="wp3s_remove_file_button" rel="wp3s_prevision_logo">Remove Image</a>
									</div>
							<?php } else {
									$parts = explode( '/', $meta );
									for( $i = 0; $i < count( $parts ); ++$i ) {
										$title = $parts[$i];
									}
								
									echo 'File: <strong>', $title, '</strong>&nbsp;&nbsp;&nbsp; (<a href="', $meta, '" target="_blank" rel="external">Download</a> / <a href="#" class="wp3s_remove_file_button" rel="wp3s_prevision_logo">Remove</a>)';
								}
							}
							?>
							</div>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-checkbox">
						<th scope="row">
							<label for="wp3s_prevision_date"><?php echo __('Date', 'wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_date" type="text" id="wp3s_prevision_date" value="<?php echo ($this->_settings['wp3s_prevision_date']); ?>" class="wp3s_timepicker wp3s_text_medium" />
							<p class="wp3s_meta_description"><?php echo __('Enter a completion date. ex: 05/20/2013 00:00:00', 'wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-radio_inline">
						<th scope="row">&nbsp;</th>
						<td>
							<div class="wp3s-type-radio_inline">
								<div class="wp3s_radio_inline_option">
									<?php
									$checked_complete = '';
									if (!empty($this->_settings['wp3s_prevision_date_yes'])) {
										$checked_complete = 'checked="checked"';
									}
									?>
									<label for="wp3s_prevision_date_yes">
										<input type="checkbox" name="wp3s_prevision_date_yes" id="wp3s_prevision_date_yes" <?php echo $checked_complete; ?> />
										<?php echo __('Automatically show site after completion date', 'wp3s_prevision'); ?>
									</label>
								</div>
							</div>
						</td>
					</tr>
					
					<tr valign="top" class="wp3s-type-radio_inline">
						<th scope="row">
							<label for="wp3s_prevision_type"><?php echo __('Background Type', 'wp3s_prevision'); ?></label>
						</th>
						<td>
							<div class="wp3s-type-radio_inline">
								<div class="wp3s_radio_inline_option">									
									<input type="radio" name="wp3s_prevision_bg-type[]" id="wp3s_prevision_bg-type-0" value="static" <?php if ($this->_settings['wp3s_prevision_bg-type'][0] != 'video') : echo 'checked="checked"'; endif; ?> />
									<label for="wp3s_prevision_bg-type-0">Static/Image Slider</label>
								</div>
								
								<div class="wp3s_radio_inline_option">									
									<input type="radio" name="wp3s_prevision_bg-type[]" id="wp3s_prevision_bg-type-1" value="video" <?php if ($this->_settings['wp3s_prevision_bg-type'][0] == 'video') : echo 'checked="checked"'; endif; ?> />
									<label for="wp3s_prevision_bg-type-1">Video</label>
								</div>
							</div>
						</td>
					</tr>
						
					
					<tr valign="top" class="wp3s-type-file">
						<th scope="row">
							<label for="wp3s_prevision_video-file"><?php echo __('Video URL','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_video_file" type="text" id="wp3s_prevision_video_file" value="<?php echo ($this->_settings['wp3s_prevision_video_file']); ?>" class="wp3s_upload_file" />
							<input class="wp3s_upload_button button" type="button" value="Upload File" />
							<input class="wp3s_upload_file_id" type="hidden" id="wp3s_prevision_video_file_id" name="wp3s_prevision_video_file_id" value=""/>
							<p class="wp3s_metabox_description"><?php echo __('Input the URL to your background video. Required if you chose &quot;Video&quot; for the <b>Background Type</b> above.','wp3s_prevision'); ?></p>
							<div id="wp3s_prevision_video_file_status" class="wp3s_media_status">
							<?php if ( $meta_video != '' ) {
								$check_image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $meta_video );
								if ( $check_image ) {
							?>
									<div class="img_status">
										<img src="<?php echo $meta_video; ?>" alt="" />
										<a href="#" class="wp3s_remove_file_button" rel="wp3s_prevision_video_file">Remove Image</a>
									</div>
							<?php } else {
									$parts = explode( '/', $meta_video );
									for( $i = 0; $i < count( $parts ); ++$i ) {
										$title = $parts[$i];
									}
								
									echo 'File: <strong>', $title, '</strong>&nbsp;&nbsp;&nbsp; (<a href="', $meta_video, '" target="_blank" rel="external">Download</a> / <a href="#" class="wp3s_remove_file_button" rel="wp3s_prevision_video_file">Remove</a>)';
								}
							}
							?>
							</div>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-file">
						<th scope="row">
							<label for="wp3s_prevision_video_image"><?php echo __('Video Image URL','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_video_image" type="text" id="wp3s_prevision_video_image" value="<?php echo ($this->_settings['wp3s_prevision_video_image']); ?>" class="wp3s_upload_file" />
							<input class="wp3s_upload_button button" type="button" value="Upload File" />
							<input class="wp3s_upload_file_id" type="hidden" id="wp3s_prevision_video_image_id" name="wp3s_prevision_video_image_id" value=""/>
							<p class="wp3s_metabox_description"><?php echo __('Input the URL to your background image for you video. This image shows while your video loads. Required if you chose &quot;Video&quot; for the <b>Background Type</b> above.','wp3s_prevision'); ?></p>
							<div id="wp3s_prevision_video_image_status" class="wp3s_media_status">
							<?php if ( $meta_video_image != '' ) {
								$check_image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $meta_video_image );
								if ( $check_image ) {
							?>
									<div class="img_status">
										<img src="<?php echo $meta_video_image; ?>" alt="" />
										<a href="#" class="wp3s_remove_file_button" rel="wp3s_prevision_video_image">Remove Image</a>
									</div>
							<?php } else {
									$parts = explode( '/', $meta_video_image );
									for( $i = 0; $i < count( $parts ); ++$i ) {
										$title = $parts[$i];
									}
								
									echo 'File: <strong>', $title, '</strong>&nbsp;&nbsp;&nbsp; (<a href="', $meta_video_image, '" target="_blank" rel="external">Download</a> / <a href="#" class="wp3s_remove_file_button" rel="wp3s_prevision_video_image">Remove</a>)';
								}
							}
							?>
							</div>
						</td>
					</tr>
					
					<tr valign="top" class="wp3s-type-radio_inline">
						<th scope="row">
							<label for="wp3s_prevision_content_pages"><?php echo __('PV Slider Pages', 'wp3s_prevision'); ?></label>
						</th>
						<td>
							<div class="wp3s-type-radio_inline">
								
							<?php
								$args = array(
									'post_type' => 'wp3s_pvslider',
								);
								$loop = new WP_Query($args);

								while($loop->have_posts()): $loop->the_post();
									$checked = '';
									if (!empty($this->_settings['wp3s_prevision_content-pages'])) {
										if (in_array(get_the_ID(), $this->_settings['wp3s_prevision_content-pages'])) $checked = 'checked="checked"';
									}
							?>
								<div class="wp3s_radio_inline_option">									
									<input type="checkbox" name="wp3s_prevision_content-pages[]" id="<?php echo 'wp3s_prevision_content-pages-',get_the_ID(); ?>" value="<?php echo (get_the_ID()); ?>" <?php echo $checked; ?> />
									<label for="<?php echo 'wp3s_prevision_content-pages-'. get_the_ID(); ?>">
									<?php the_title(); ?>
									</label>
								</div>
							<?php
								endwhile;
								wp_reset_query();
							?>
							</div>
						</td>
					</tr>
					
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_title"><?php echo __('Title','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_title" type="text" id="wp3s_prevision_title" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_title'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('This is the copy under the countdown','wp3s_prevision'); ?></p>
						</td>
					</tr>
					
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_meetus"><?php echo __('Meet Us','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_meetus" type="text" id="wp3s_prevision_meetus" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_meetus'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('This is the copy above the social icons. ex. Meet Us','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_copyright"><?php echo __('Copyright in footer','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_copyright" type="text" id="wp3s_prevision_copyright" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_copyright'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('ex. &copy; Copyright WP-3Sqrd Themes','wp3s_prevision'); ?><br><img src="http://www.lolinez.com/erw.jpg"></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_emailaddress"><?php echo __('Contact Email','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_emailaddress" type="text" id="wp3s_prevision_emailaddress" value="<?php echo($this->_settings['wp3s_prevision_emailaddress']); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('The email that will receive the contact form submissions','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_formtitle"><?php echo __('Contact Form Title','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_formtitle" type="text" id="wp3s_prevision_formtitle" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_formtitle'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('The title at the top of the contact form','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_form_name"><?php echo __('Contact Form Name','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_form_name" type="text" id="wp3s_prevision_form_name" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_form_name'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('The word &quot;NAME&quot; above the name field on the contact form','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_form_email"><?php echo __('Contact Form Email','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_form_email" type="text" id="wp3s_prevision_form_email" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_form_email'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('The word &quot;EMAIL&quot; above the email field on the contact form','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_form_message"><?php echo __('Contact Form Message','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_form_message" type="text" id="wp3s_prevision_form_message" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_form_message'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('The word &quot;MESSAGE&quot; above the message textarea field on the contact form','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_form_button"><?php echo __('Contact Form Button','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_form_button" type="text" id="wp3s_prevision_form_button" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_form_button'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('The word &quot;SEND&quot; on the button for the contact form','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_form_sent"><?php echo __('Contact Form Sent Message','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_form_sent" type="text" id="wp3s_prevision_form_sent" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_form_sent'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('This is the message that appears after a visitor successfully submits the contact form','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_form_validate"><?php echo __('Contact Form Validation Message','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_form_validate" type="text" id="wp3s_prevision_form_validate" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_form_validate'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('This is the message that appears if a visitor did not complete all of the fields on the contact form','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_form_error"><?php echo __('Contact Form Error Message','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_form_error" type="text" id="wp3s_prevision_form_error" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_form_error'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('This is the message that appears if your server cannot send the contact form to you','wp3s_prevision'); ?></p>
						</td>
					</tr>
					
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_twitter_url"><?php echo __('Twitter Page URL','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_twitter_url" type="text" id="wp3s_prevision_twitter_url" value="<?php echo($this->_settings['wp3s_prevision_twitter_url']); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('ex. <code>http://twitter.com/WP3Sqrd</code>','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_facebook_url"><?php echo __('Facebook Page URL','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_facebook_url" type="text" id="wp3s_prevision_facebook_url" value="<?php echo($this->_settings['wp3s_prevision_facebook_url']); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('ex. <code>http://www.facebook.com/WP3Sqrd</code>','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_linkedin_url"><?php echo __('LinkedIn Profile URL','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_linkedin_url" type="text" id="wp3s_prevision_linkedin_url" value="<?php echo($this->_settings['wp3s_prevision_linkedin_url']); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('ex. <code>http://www.linkedin.com/profile</code>','wp3s_prevision'); ?></p>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="wp3s_prevision_primary_font"><?php echo __('Primary Font','wp3s_prevision'); ?></label>
						</th>
						<td id="section-primary_font" class="option">
							<select name="wp3s_prevision_primary_font" id="wp3s_prevision_primary_font">
								<?php
								$typography_mixed_fonts = array_merge(
									wp3s_prevision_typography_get_os_fonts(),
									wp3s_prevision_typography_get_google_fonts());
								asort($typography_mixed_fonts);
								
								foreach ($typography_mixed_fonts as $key => $option) {
									//if ($this->_settings['wp3s_prevision_primary_font'] = esc_attr($key) {
									echo '<option ';
									if (stripslashes($this->_settings['wp3s_prevision_primary_font']) == $key) { echo 'selected'; }
									echo ' value="' . esc_attr($key) . '">' . esc_html($option) . '</option>';
								}
								?>
							</select>
							<?php //echo stripslashes($this->_settings['wp3s_prevision_primary_font']); ?>
						</td>
					</tr>
					
					<tr valign="top" class="wp3s-type-colorpicker">
						<th scope="row">
							<label for="wp3s_prevision_primary_color"><?php echo __('Primary Color','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input class="wp3s_colorpicker wp3s_text_small" type="text" name="wp3s_prevision_primary_color" id="wp3s_prevision_primary_color" value="<?php echo($this->_settings['wp3s_prevision_primary_color']); ?>" /><span class="wp3s_metabox_description"><?php echo __('Choose the color of the text on your coming soon page','wp3s_prevision'); ?></span>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-colorpicker">
						<th scope="row">
							<label for="wp3s_prevision_secondary_color"><?php echo __('Secondary Color','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input class="wp3s_colorpicker wp3s_text_small" type="text" name="wp3s_prevision_secondary_color" id="wp3s_prevision_secondary_color" value="<?php echo($this->_settings['wp3s_prevision_secondary_color']); ?>" /><span class="wp3s_metabox_description"><?php echo __('Choose the color of the time left in the clock circles','wp3s_prevision'); ?></span>
						</td>
					</tr>
					
					<tr valign="top" class="wp3s-type-radio_inline">
						<th scope="row">&nbsp;</th>
						<td>
							<div class="wp3s-type-radio_inline">
								<div class="wp3s_radio_inline_option">
									<?php
									$checked_complete = '';
									if (!empty($this->_settings['wp3s_prevision_overlay'])) {
										$checked_complete = 'checked="checked"';
									}
									?>
									<label for="wp3s_prevision_overlay">
										<input type="checkbox" name="wp3s_prevision_overlay" id="wp3s_prevision_overlay" <?php echo $checked_complete; ?> />
										<?php echo __('Do not show the transparent line overlay.', 'wp3s_prevision'); ?>
									</label>
								</div>
							</div>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_days"><?php echo __('Days text in countdown','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_days" type="text" id="wp3s_prevision_days" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_days'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('ex. Days','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_hours"><?php echo __('Hours text in countdown','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_hours" type="text" id="wp3s_prevision_hours" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_hours'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('ex. Hours','wp3s_prevision'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_minutes"><?php echo __('Minutes text in countdown','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_minutes" type="text" id="wp3s_prevision_minutes" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_minutes'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('ex. Minutes','wp3s_prevision'); ?></p>
						</td>
					</tr>
					
					<tr valign="top" class="wp3s-type-text">
						<th scope="row">
							<label for="wp3s_prevision_seconds"><?php echo __('Seconds text in countdown','wp3s_prevision'); ?></label>
						</th>
						<td>
							<input name="wp3s_prevision_seconds" type="text" id="wp3s_prevision_seconds" value="<?php echo(stripslashes_deep($this->_settings['wp3s_prevision_seconds'])); ?>" class="regular-text code" />
							<p class="wp3s_meta_description"><?php echo __('ex. Seconds','wp3s_prevision'); ?></p>
						</td>
					</tr>
				</table>				
				
                <p class="submit">
                    <input type="submit" name="wp3s_prevision_settings_saved" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
                </p>
			</form>
		</div>
<?php
	}
	
	function plugin_page_js() {
		global $wp_version;
		
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');	
		if (3.5 <= $wp_version) {
			wp_enqueue_script('wp-color-picker');
		} else {
			wp_enqueue_script('farbtastic');
		}
        //wp_enqueue_script('wp3s_prevision-admin-date', $this->location_folder . '/js/jquery-ui-1.7.3.custom.min.js');
		wp_enqueue_script('wp3s_prevision-admin-main', $this->location_folder . '/js/admin.js', array('jquery','wp-color-picker'), true);
    }
	
	function plugin_page_css() {
		global $wp_version;
		
		wp_enqueue_style('thickbox');
		if (3.5 <= $wp_version) {
			wp_enqueue_style('wp-color-picker');
		} else {
			wp_enqueue_style('farbtastic');
		}
?>
        <link rel="stylesheet" href="<?php echo $this->location_folder; ?>/css/jquery-ui-1.7.3.custom.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $this->location_folder; ?>/css/admin.css" type="text/css" />
<?php
    }
	
	function _save_settings_todb($form_settings = '')
	{
		if ($form_settings <> '') {
			unset($form_settings['wp3s_prevision_settings_saved']);			
			
			$this->_settings = $form_settings;
			
			$this->_set_standard_values();
		}
		
		update_option('wp3s_prevision_settings', $this->_settings);
	}
	
	function _set_standard_values() {
		global $shortname;
		$logo = ($shortname <> '' && get_option($shortname . '_logo') <> '') ? get_option($shortname . '_logo') : $this->location_folder . '/images/logo.png';
		
		$standard_values = array(
			'wp3s_prevision_logo' => $logo,
			'wp3s_prevision_logo_id' => $logo,
			'wp3s_prevision_date' => '',
			'wp3s_prevision_date_yes' => '',
			'wp3s_prevision_bg-type' => 'static',
			'wp3s_prevision_video_file' => '',
			'wp3s_prevision_video_file_id' => '',
			'wp3s_prevision_video_image' => '',
			'wp3s_prevision_video_image_id' => '',
            'wp3s_prevision_content-pages' => '',
			'wp3s_prevision_title' => 'Coming Soon',			
			'wp3s_prevision_meetus' => 'Meet Us',
			'wp3s_prevision_copyright' => '&copy; WP-3Sqrd Themes',
			'wp3s_prevision_emailaddress' => 'prevision@domain.com',
			'wp3s_prevision_formtitle' => 'Contact Us',
            'wp3s_prevision_twitter_url' => '',
            'wp3s_prevision_facebook_url' => '',
			'wp3s_prevision_linkedin_url' => '',
			'wp3s_prevision_primary_font' => '"Open Sans", "Helvetica Neue", Hevetica, Arial, sans-serif',
			'wp3s_prevision_primary_color' => '#ffffff',
			'wp3s_prevision_secondary_color' => '#fd991e',
			'wp3s_prevision_overlay' => '',
			'wp3s_prevision_days' => 'DAYS',
			'wp3s_prevision_hours' => 'HOURS',
			'wp3s_prevision_minutes' => 'MINUTES',
			'wp3s_prevision_seconds' => 'SECONDS',
			'wp3s_prevision_form_name' => 'Name',
			'wp3s_prevision_form_email' => 'Email',
			'wp3s_prevision_form_message' => 'Message',
			'wp3s_prevision_form_button' => 'Send',
			'wp3s_prevision_form_sent' => '<strong>Thanks!</strong> Your message has been sent. We\'ll be back to you shortly.',
			'wp3s_prevision_form_validate' => '<strong>Error!</strong> Please validate your fields.',
			'wp3s_prevision_form_error' => '<strong>Error!</strong> Issue sending your email.'
		);
		
		foreach ($standard_values as $key => $value) {
			if (!array_key_exists($key, $this->_settings))
				$this->_settings[$key] = '';
		}
		
		foreach ($this->_settings as $key => $value) {
			if ($value == '') $this->_settings[$key] = $standard_values[$key];
		}
	}
	
	function maintenance_active() {
		if (!$this->check_user_capability() && !$this->is_page_url_excluded()) {
		
			// check date and completion
			$tz = get_option('gmt_offset');
			$end_dt = strtotime($this->_settings['wp3s_prevision_date']);
			$start_dt = time() + ($tz * 60 * 60);
			//echo $tz . '<br>' . date("jS F, Y H:i:s", $end_dt) . '<br>' . date("jS F, Y H:i:s", $start_dt);
			if (empty($this->_settings['wp3s_prevision_date_yes'])) {
			
				nocache_headers();
				header("HTTP/1.0 503 Service Unavailable");
				remove_action('wp_head', 'head_addons', 7);
				add_action('wp_print_scripts', array(&$this, 'remove_all_scripts'), 100);
				add_action('wp_print_styles', array(&$this,'remove_all_styles'), 100);	
				add_action('wp3s_prevision_footer_icons', array(&$this, 'show_social_icons'));
				
				wp_enqueue_script('jquery');
				include('prevision-maintenance-page.php');
				
				exit();	
			} else if (($end_dt >= $start_dt) && (!empty($this->_settings['wp3s_prevision_date_yes']))) {
			
				nocache_headers();
				header("HTTP/1.0 503 Service Unavailable");
				remove_action('wp_head', 'head_addons', 7);
				add_action('wp_print_scripts', array(&$this, 'remove_all_scripts'), 100);
				add_action('wp_print_styles', array(&$this,'remove_all_styles'), 100);	
				add_action('wp3s_prevision_footer_icons', array(&$this, 'show_social_icons'));
				
				include('prevision-maintenance-page.php');
				
				exit();				
			}
		}
	}
	
	function check_user_capability() {
		if (is_super_admin() || current_user_can('manage_options')) return true;
		
		return false;
	}
	
	function is_page_url_excluded() {
		$this->_exception_urls = apply_filters('wp3s_prevision_exceptions', $this->_exception_urls);
		foreach ($this->_exception_urls as $url) {
			if (strstr($_SERVER['PHP_SELF'], $url) || strstr($_SERVER["REQUEST_URI"], $url)) return true;
		}
		if (strstr($_SERVER['QUERY_STRING'], 'feed=')) return true;
		return false;
	}
	
	function get_option($setting) {
		return $this->_settings[$setting];
	}
	
	function show_social_icons() {
	}

	function remove_all_scripts() {
		global $wp_scripts;		
		
		$wp_scripts->queue = array();
		
		// jQuery
		wp_enqueue_script('jquery');
		// jQuery UI
		wp_enqueue_script('wp3s_prevision-jqueryui', $this->location_folder . '/js/plugins/jquery-ui-1.8.22.custom.min.js', array('jquery'), $this->pluginversion, true);
		// BxSlider (to remove)
		wp_enqueue_script('wp3s_prevision-bxslider', $this->location_folder . '/js/plugins/jquery.bxslider.js', array('jquery'), $this->pluginversion, true);	
/*		
		// jQuery Plugin (required by Countdown Plugin)
		//wp_enqueue_script('wp3s_prevision-plugin', $this->location_folder . '/js/plugins/jquery.plugin.min.js', array('jquery'), $this->pluginversion, true);
		// jCanvas
		//wp_enqueue_script('wp3s_prevision-jcanvas', $this->location_folder . '/js/plugins/jcanvas.min.js', array('jquery'), $this->pluginversion, true);
		// Countdown Plugin
		//wp_enqueue_script('wp3s_prevision-countdownplugin', $this->location_folder . '/js/plugins/jquery.countdown.js', array('jquery'), $this->pluginversion, true);
		// (to remove)
		//wp_enqueue_script('wp3s_prevision-countdown', $this->location_folder . '/js/plugins/countdown/countdown.php', array('jquery'), $this->pluginversion, true);
		//wp_enqueue_script('wp3s_prevision-countdown-plugins', $this->location_folder . '/js/plugins/countdown/countdown_plugins.js', array('jquery', 'wp3s_prevision-countdown'), $this->pluginversion, true);
*/

/*
		wp_enqueue_script('wp3s_prevision-kinetic', $this->location_folder . '/js/plugins/kinetic.js', array('jquery'), $this->pluginversion, true);
		wp_enqueue_script('wp3s_prevision-countdown', $this->location_folder . '/js/plugins/jquery.final-countdown.js', array('jquery'), $this->pluginversion, true);
*/

		// Knob
		wp_enqueue_script('wp3s_prevision-knob', $this->location_folder . '/js/plugins_new/jquery.knob.js', array('jquery'), $this->pluginversion, true);
		// Circular Countdown
		wp_enqueue_script('wp3s_prevision-countdown', $this->location_folder . '/js/plugins_new/jquery.ccountdown.js', array('jquery'), $this->pluginversion, true);
		// Circular Countdown Initialize
		wp_enqueue_script('wp3s_prevision-countdownInit', $this->location_folder . '/js/plugins_new/init.js', array('jquery'), $this->pluginversion, true);
		
		// Validate
		wp_enqueue_script('wp3s_prevision-validate', $this->location_folder . '/js/plugins/jquery.validate.min.js', array('jquery'), $this->pluginversion, true);		
		// Easing
		wp_enqueue_script('wp3s_prevision-easing', $this->location_folder . '/js/plugins/jquery.easing.js', array('jquery'), $this->pluginversion, true);
		// Retina
		wp_enqueue_script('wp3s_prevision-retina', $this->location_folder . '/js/plugins/retina-1.1.0.min.js', array('jquery'), $this->pluginversion, true);
		
		if ($this->_settings['wp3s_prevision_bg-type'][0] != 'video') :			
			// Flexslider
			wp_enqueue_script('wp3s_prevision-flexslider', $this->location_folder . '/js/plugins/flexslider/jquery.flexslider-min.js', array('jquery'), $this->pluginversion, true);
		else :
			// Video
			wp_enqueue_script('wp3s_prevision-video', $this->location_folder . '/js/plugins/video.js', array('jquery'), $this->pluginversion, true);
			// Big Video
			wp_enqueue_script('wp3s_prevision-bigvideo', $this->location_folder . '/js/plugins/bigvideo.js', array('jquery'), $this->pluginversion, true);
			// Transit
			wp_enqueue_script('wp3s_prevision-transit', $this->location_folder . '/js/plugins/jquery.transit.min.js', array('jquery'), $this->pluginversion, true);
			// ImagesLoaded
			wp_enqueue_script('wp3s_prevision-imagesloaded', $this->location_folder . '/js/plugins/jquery.imagesloaded.min.js', array('jquery'), $this->pluginversion, true);
		endif;
		
		wp_enqueue_script('wp3s_prevision-main', $this->location_folder . '/js/wp3s-main.js', array('jquery'), $this->pluginversion, true);	
		
		if ($this->_settings['wp3s_prevision_bg-type'][0] == 'video') :
			// WP3S Video Script
			wp_enqueue_script('wp3s_prevision-mainvideo', $this->location_folder . '/js/main-video.js', array('jquery'), $this->pluginversion, true);
		endif;

		// Localize Scripts
		$wp3s_date = '';
		$wp3s_date = $this->_settings['wp3s_prevision_date'];
		$wp3s_date = new DateTime($wp3s_date);
		//$wp3s_date->modify('-1 month');
		wp_localize_script( 'wp3s_prevision-main', 'wp3s_prevision', array(
			'date_year' => $wp3s_date->format("Y"),
			'date_month' => $wp3s_date->format("m"),
			'date_day' => $wp3s_date->format("d"),
			'date_hour' => $wp3s_date->format("H"),
			'date_minute' => $wp3s_date->format("i"),
			'date_second' => $wp3s_date->format("s"),
			'plugin_uri' => $this->location_folder
		) );
		
		// modernizr
		wp_enqueue_script('wp3s_prevision-modernizr', $this->location_folder . '/js/plugins/modernizr-2.5.3.min.js', false, $this->pluginversion, false);
		add_action('wp_head', array(&$this, 'add_ie_html5_shim'));
	}

	function remove_all_styles() {
		global $wp_styles;
		$font = wp3s_prevision_typography_google_fonts();
		
		$wp_styles->queue = array();
		
		wp_enqueue_style('wp3s_prevision-reset', $this->location_folder . '/css/reset.css', false, $this->pluginversion);
		wp_enqueue_style('wp3s_prevision-fontawesome', $this->location_folder . '/css/font-awesome/css/font-awesome.min.css', false, $this->pluginversion);
		wp_enqueue_style('wp3s_prevision-countdown', $this->location_folder . '/css/countdown.css', false, $this->pluginversion);
		
		if ($this->_settings['wp3s_prevision_bg-type'][0] != 'video') :
			wp_enqueue_style('wp3s_prevision-flexslider', $this->location_folder . '/js/plugins/flexslider/flexslider.css', false, $this->pluginversion);
		else :
			wp_enqueue_style('wp3s_prevision-bigvideo', $this->location_folder . '/css/bigvideo.css', false, $this->pluginversion);
		endif;
		
		wp_enqueue_style('wp3s_prevision-style', $this->location_folder . '/css/style.css', false, $this->pluginversion);
		wp_enqueue_style('wp3s_prevision_typography_' .$font, 'http://fonts.googleapis.com/css?family='.$font.':300,400,400:italic,500,600,700,800', false, null, 'all');
		add_action('wp_head', 'wp3s_prevision_typography_styles');
		add_action('wp_head', array(&$this, 'wp3s_prevision_styles'));
	}
	
	function add_ie_html5_shim () {
		echo '<!--[if lt IE 9]>';
		echo '<script src="' . $this->location_folder . '/js/plugins/html5.js"></script>';
		echo '<script src="' . $this->location_folder . '/js/plugins/respond.js"></script>';
		echo '<![endif]-->';
	}
	
	/* Convert hex color to rgba */
	function hex2rgba($color, $opacity = false) {

	$default = 'rgb(0,0,0)';

	//Return default if no color provided
	if(empty($color))
          return $default; 

	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
	}
	
	function wp3s_prevision_styles() {
		$primary_color = $this->_settings['wp3s_prevision_primary_color'];
		$secondary_color = $this->_settings['wp3s_prevision_secondary_color'];
		$overlay = $this->_settings['wp3s_prevision_overlay'];
		
		if (strlen($primary_color)) :
?>
		<style>
		.wp3s_prevision #title_container,
		.wp3s_prevision #title_container h1,
		.wp3s_prevision #quote_container,
		.wp3s_prevision #quote_container li,
		.wp3s_prevision #quote_container .bxslider li a { color: <?php echo $primary_color; ?>!important; }
		.wp3s_prevision #quote_container .bxslider li a:hover { background: <?php echo $primary_color;?>!important; }
		</style>
<?php
		endif;
		
		if (strlen($secondary_color)) :
?>
		<style>
		.wp3s_prevision .form-click button,
		.wp3s_prevision #quote_container .bxslider li a { background: <?php echo $secondary_color; ?>!important; }		
		.wp3s_prevision #quote_container .bxslider li a:hover { color: <?php echo $secondary_color;?>!important; }
		</style>
<?php
		endif;
		
		if (strlen($overlay)) :
?>
		<style>
		.wp3s_prevision .slideoverlay { display: none; }
		</style>
<?php
		endif;
	}

} // [END] WP3S_Prevision class

add_action('init', 'wp3s_prevision_Init', 5);
function wp3s_prevision_init() {
	global $WP3S_Prevision;
	$WP3S_Prevision = new WP3S_Prevision();
}

// PressTrends WordPress Action
add_action('admin_init', 'wp3s_prevision_presstrends_plugin');
function wp3s_prevision_presstrends_plugin() {
    // PressTrends Account API Key
    $api_key = 'vy8gdagq3p390sm23wr02ad97ff50b1scwb9';
    $auth    = '5bqvry7etmqayuv3zxd8f1rdsimpmbc0m';
    // Start of Metrics
    global $wpdb;
    $data = get_transient( 'presstrends_cache_data' );
    if ( !$data || $data == '' ) {
        $api_base = 'http://api.presstrends.io/index.php/api/pluginsites/update?auth=';
        $url      = $api_base . $auth . '&api=' . $api_key . '';
        $count_posts    = wp_count_posts();
        $count_pages    = wp_count_posts( 'page' );
        $comments_count = wp_count_comments();
        if ( function_exists( 'wp_get_theme' ) ) {
            $theme_data = wp_get_theme();
            $theme_name = urlencode( $theme_data->Name );
        } else {
            $theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
            $theme_name = $theme_data['Name'];
        }
        $plugin_name = '&';
        foreach ( get_plugins() as $plugin_info ) {
            $plugin_name .= $plugin_info['Name'] . '&';
        }
        // CHANGE __FILE__ PATH IF LOCATED OUTSIDE MAIN PLUGIN FILE
        $plugin_data         = get_plugin_data( __FILE__ );
        $posts_with_comments = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type='post' AND comment_count > 0" );
        $data                = array(
            'url'             => base64_encode(site_url()),
            'posts'           => $count_posts->publish,
            'pages'           => $count_pages->publish,
            'comments'        => $comments_count->total_comments,
            'approved'        => $comments_count->approved,
            'spam'            => $comments_count->spam,
            'pingbacks'       => $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_type = 'pingback'" ),
            'post_conversion' => ( $count_posts->publish > 0 && $posts_with_comments > 0 ) ? number_format( ( $posts_with_comments / $count_posts->publish ) * 100, 0, '.', '' ) : 0,
            'theme_version'   => $plugin_data['Version'],
            'theme_name'      => $theme_name,
            'site_name'       => str_replace( ' ', '', get_bloginfo( 'name' ) ),
            'plugins'         => count( get_option( 'active_plugins' ) ),
            'plugin'          => urlencode( $plugin_name ),
            'wpversion'       => get_bloginfo( 'version' ),
        );
        foreach ( $data as $k => $v ) {
            $url .= '&' . $k . '=' . $v . '';
        }
        wp_remote_get( $url );
        set_transient( 'presstrends_cache_data', $data, 60 * 60 * 24 );
	}
}