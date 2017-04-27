<?php
	global $WP3S_Prevision;
	global $post;
	if (isset($_POST['prevision_email'])) $WP3S_Prevision->add_email($_POST['prevision_email']);
?>
<!DOCTYPE html>
<!--[if IE 8]><html class="desktop ie8 no-js" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 9]><html class="desktop ie9 no-js" <?php language_attributes(); ?>><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->

	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		
		<?php if (is_search()) { ?>
		<meta name="robots" content="noindex, nofollow"/>
		<?php } ?>
		
		<title><?php bloginfo('name'); ?></title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		
		
		
		<?php wp_head(); ?>
	</head>
	
	<body class="wp3s_prevision">
		<?php $bg_type = $WP3S_Prevision->get_option('wp3s_prevision_bg-type'); ?>
		<?php if ($bg_type[0] == 'static') { ?>
		<?php /** [START] Slider Background **/ ?>
		<?php
			
			$wp3s_content_pages = $WP3S_Prevision->get_option('wp3s_prevision_content-pages');
			
		if (!empty($wp3s_content_pages)) {
		
			$wp3s_prevision_query_args = array(
				'post_type' => 'wp3s_pvslider',
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'post__in' => $wp3s_content_pages,
				'posts_per_page' => count($wp3s_content_pages)
			);
			$wp3s_prevision_query_args = apply_filters('wp3s_prevision_query_args', $wp3s_prevision_query_args);
			$wp3s_prevision_query = new WP_Query($wp3s_prevision_query_args);
			
			if ($wp3s_prevision_query->have_posts()) : 
		?>
			<div id="slides" class="flexslider">
				<ul class="slides-container slides">
		<?php
			while ($wp3s_prevision_query->have_posts()) : $wp3s_prevision_query->the_post();
		?>
				
			
					<li style="background-image: url(<?php if (has_post_thumbnail()) {
						$src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
						echo $src[0]; } ?>);">			
						<div class="slideoverlay"></div>
						
						<!--<img src="<?php if (has_post_thumbnail()) {
						$src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
						echo $src[0]; } ?>" alt="">-->
						
						
					</li>
			<?php endwhile; ?>
				</ul>
			</div>
		<?php endif; ?>
		<?php } ?>
		<?php /** [END] Slider Background **/ ?>
		<?php } ?>
		
		<?php /** [START] wrapper **/ ?>
		<div class="wrapper">
		
			<?php if ($bg_type[0] == 'video') { ?>
			<!-- [START] Background Video -->
			<div class="slideoverlay"></div>
			<div class="screen" id="screen-1" data-video="<?php echo $WP3S_Prevision->_settings['wp3s_prevision_video_file']; ?>">
				
				<img src="<?php echo $WP3S_Prevision->_settings['wp3s_prevision_video_image']; ?>" class="big-image" />
			</div>
			<!-- [END] Background Video -->
			<?php } ?>
			
			<?php /** [START] sidebar **/ ?>
			<div class="sidebar" data-smoothscrolling>
				<header class="site-header">
					<div class="site-header_branding">
						<div class="site-logo">
							<img src="<?php echo $WP3S_Prevision->_settings['wp3s_prevision_logo']; ?>" alt="<?php bloginfo('name'); ?>">
						</div>
					</div>
				</header>
				
				
			</div>
			<?php /** [END] sidebar **/ ?>
			
			<?php /** [START] content **/ ?>
			<div id="main" class="content">
				
				<div class="content-inner">					
					<?php /** [START] Timer **/ ?>
					<div id="countdown_container" class="ccounter">
						<input class="knob days" data-name="days" data-timetype="<?php echo $WP3S_Prevision->_settings['wp3s_prevision_days']; ?>" data-thickness=".2" data-width="160" data-height="160" data-min="0" data-max="365" data-displayPrevious=true data-fgColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_secondary_color'],'1'); ?>" data-bgColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_primary_color'],'0.5'); ?>" data-inputColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_primary_color'],'1'); ?>" data-readOnly="true" value="1">

						<input class="knob hour" data-name="hour" data-timetype="Hours" data-thickness=".2" data-width="160" data-height="160" data-min="0" data-max="24" data-displayPrevious=true data-fgColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_secondary_color'],'1'); ?>" data-bgColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_primary_color'],'0.5'); ?>" data-inputColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_primary_color'],'1'); ?>" data-readOnly="true" value="1">

						<input class="knob minute" data-name="minute" data-timetype="Minutes" data-thickness=".2" data-width="160" data-height="160" data-min="0" data-max="60" data-displayPrevious=true data-fgColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_secondary_color'],'1'); ?>" data-bgColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_primary_color'],'0.5'); ?>" data-inputColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_primary_color'],'1'); ?>" data-readOnly="true" value="1">

						<input class="knob second" data-name="second" data-timetype="Seconds" data-thickness=".2" data-width="160" data-height="160" data-min="0" data-max="60" data-displayPrevious=true data-fgColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_secondary_color'],'1'); ?>" data-bgColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_primary_color'],'0.5'); ?>" data-inputColor="<?php echo $WP3S_Prevision->hex2rgba($WP3S_Prevision->_settings['wp3s_prevision_primary_color'],'1'); ?>" data-readOnly="true" value="1">
					</div>
					<?php /** [END] Timer **/ ?>
					
					<?php /** [START] Title **/ ?>
					<div id="title_container">
						<h1><?php echo stripslashes_deep($WP3S_Prevision->_settings['wp3s_prevision_title']); ?></h1>
					</div>
					<?php /** [END] Title **/ ?>
					
					<?php /** [START] Slider Quotes **/ ?>
					<?php
					$quote_args = array();
					$quote_args = array(
						'post_type' => 'wp3s_pvquote',
						'posts_per_page' => -1,
						'orderby' => 'menu_order',
						'order' => 'DESC'
					);
					
					$quote_query = new WP_Query($quote_args);
					
					global $post;
					if ($quote_query->have_posts()) :
					?>
					<div id="quote_container">
						<ul class="bxslider">
							<?php while ($quote_query->have_posts()) : $quote_query->the_post(); ?>
							<?php $page_object = get_page($post->ID); ?>
							<li><?php echo strip_tags($page_object->post_content, '<b><strong><i><em><a><br>'); ?></li>
							<?php endwhile; ?>
						</ul>
						<div id="bx-pager"></div>
						<div id="bx-next"></div>
						<div id="bx-prev"></div>
					</div>
					<?php endif; ?>
					<?php /** [END] Slider Quotes **/ ?>
				</div>
			
			</div>
			<?php /** [END] content **/ ?>
			
			<?php /** [START] Contact Us **/ ?>
			<div id="contact-us">
				
				<?php /** [START] Contact Form **/ ?>
				<div id="contact-form" class="dark clearfix">
					<a href="#" class="menu-close"><i class="fa fa-arrow-circle-left"></i></a>
					<h3><?php echo $WP3S_Prevision->_settings['wp3s_prevision_formtitle']; ?></h3>
					
					<form action="#" method="post" class="contactForm wp3sThemeContactForm container" id="contactform">
						<fieldset>
							<div class="form-field grid-half control-group">
								<label for="name"><?php echo stripslashes_deep($WP3S_Prevision->_settings['wp3s_prevision_form_name']); ?></label>
								<span><input type="text" class="required" name="name" id="name" /></span>
							</div>
							<div class="form-field grid-half  control-group">
								<label for="email"><?php echo stripslashes_deep($WP3S_Prevision->_settings['wp3s_prevision_form_email']); ?></label>
								<span><input type="email" class="required" name="email" id="email" /></span>
							</div>
							<div class="form-field grid-full control-group">
								<label for="message"><?php echo stripslashes_deep($WP3S_Prevision->_settings['wp3s_prevision_form_message']); ?></label>
								<span><textarea name="message" class="required" id="message"></textarea></span>
							</div>
						</fieldset>
						<div class="form-click grid-full">
							<span><button name="send" type="submit" dir="ltr" lang="en" class="submit" id="submit"><?php echo stripslashes_deep($WP3S_Prevision->_settings['wp3s_prevision_form_button']); ?></button></span>
						</div>
						<div id="contactFormSent" class="grid-full formSent alert success">
							<?php echo stripslashes_deep($WP3S_Prevision->_settings['wp3s_prevision_form_sent']); ?>
						</div>
						<div id="contactFormError" class="grid-full formError alert error">
							<?php echo stripslashes_deep($WP3S_Prevision->_settings['wp3s_prevision_form_validate']); ?>
						</div>
						<div id="contactFormError2" class="grid-full formError alert error">
							<?php echo stripslashes_deep($WP3S_Prevision->_settings['wp3s_prevision_form_error']); ?>
						</div>
					</form>	
				</div>
				<?php /** [END] Contact Form **/ ?>
				
			</div>
			<?php /** [END] Contact Us **/ ?>
		
		</div>
		<?php /** [END] wrapper **/ ?>
		
		<?php /** [START] scripts **/ ?>		
		<?php wp_footer(); ?>
		<?php /** [END] scripts **/ ?>
	</body>
</html>