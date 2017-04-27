<?php 
/*
Plugin Name:Nice Animation Progress bar
Plugin URI: http://codertanvir.com/projects/btp_hair/animation-progressbar/
Description: This plugin will enable animation Progress bar in your wordpress theme. You can embed  Progress bar via shortcode in everywhere you want, even in theme files. 
Author: sumon
Version: 1.0
Author URI: http://codertanvir.com/projects/btp_hair
*/


function progressbar_wp_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'progressbar_wp_latest_jquery');



function progressbar_anumation_plugin_main_js() {
    wp_enqueue_script( 'progressbar-new-js', plugins_url( '/js/jqbar.js', __FILE__ ), array('jquery'), 1.0, false);
    wp_enqueue_style( 'progressbar-new-css', plugins_url( '/css/style.css', __FILE__ ));
	  wp_enqueue_style( 'progressbar-news-css', plugins_url( '/css/jqbar.css', __FILE__ ));
}

add_action('init','progressbar_anumation_plugin_main_js');


function progressbar_list_shortcode($atts){
	extract( shortcode_atts( array(
		'id' => 'progressbar',
		'title' => '',
		'titlecolor' => '',
		'speed' => '3000',
		'width' => '400',
		'value' => '50',
		'color' => 'red',
		'percent_color' => '',
		'height' => '20',
		'titlesize' => '20',
		'bg' => '',
	), $atts, 'projects' ) );
	
	
    $q = new WP_Query(
        array('posts_per_page' => $count, 'post_type' => 'post', $category_slug => $category)
        );		
		

	$list = '

	<script type="text/javascript">
		jQuery(document).ready(function(){
			 jQuery("#bar-'.$id.'").jqbar({
			 label: "'.$title.'", 
			 value: "'.$value.'",
			 barColor: "'.$color.'", 
			 animationSpeed: "'.$speed.'",
             barLength:"'.$width.'",
			 barWidth: '.$height.',
			 
			 });
		}); 	
	</script>
	
	
		<style type="text/css">
		#bar-'.$id.' span.bar-label {
		  color: '.$titlecolor.';
		  font-size:'.$titlesize.';
		}
		#bar-'.$id.' span.bar-percent {
		  color: '.$percent_color.';
		}
		</style>


	 <div class="bars_area"style="background:'.$bg.';"> 
	';
	while($q->have_posts()) : $q->the_post();
		$idd = get_the_ID();
		$list .= '   
                    <div id="bar-'.$id.'">
                    </div>

		';        
	endwhile;
	$list.= '</div>';
	wp_reset_query();
	return $list;
}
add_shortcode('progressbar_list', 'progressbar_list_shortcode');	


?>