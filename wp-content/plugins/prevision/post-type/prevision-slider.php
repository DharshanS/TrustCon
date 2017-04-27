<?php 
/**
 * Previsions Slider Functions
 *
 * Post type, taxonomy, meta boxes, admin columns, widget, etc.
 */
 
/**********************************
* POST TYPE
**********************************/
function wp3s_pvslider_post_type() {

	// register it
	register_post_type(
		'wp3s_pvslider',
		array(
			'labels' 	=> array(
				'name'					=> _x( 'PV Slider', 'post type general name', 'wp3s_prevision' ),
				'singular_name'			=> _x( 'PV Slider', 'post type singular name', 'wp3s_prevision' ),
				'add_new' 				=> _x( 'Add New', 'pvslider', 'wp3s_prevision' ),
				'add_new_item' 			=> __( 'Add Slide', 'wp3s_prevision' ),
				'edit_item' 			=> __( 'Edit Slide', 'wp3s_prevision' ),
				'new_item' 				=> __( 'New Slide', 'wp3s_prevision' ),
				'all_items' 			=> _x( 'All Slides', 'pvslider', 'wp3s_prevision' ),
				'view_item' 			=> __( 'View Slide', 'wp3s_prevision' ),
				'search_items' 			=> __( 'Search Slides', 'wp3s_prevision' ),
				'not_found' 			=> __( 'No slides found', 'wp3s_prevision' ),
				'not_found_in_trash' 	=> __( 'No slides found in Trash', 'wp3s_prevision' )
			),
			'public' 			=> true,
			'has_archive' 		=> false,
			'show_in_nav_menus' => true,
			'rewrite'			=> array(
				'slug' 	=> 'wp3s-pv-slide', // best not to use slug likely to be used by a Page (such as gallery) to avoid URL rewrite conflicts
				'with_front' => false
			),
			'supports' 			=> array( 'title', 'thumbnail', 'page-attributes', 'revisions' )
		)
	);

}

/** 
* Initialize Home Slider Meta Boxes
*/
function wp3s_pvslider_metabox_init() {

	// This post type only
	$screen = get_current_screen();
	if ('wp3s_pvslider' == $screen->post_type) {
	
		// Add Meta Boxes
		add_action('add_meta_boxes', 'wp3s_pvslider_metaboxes_change');
		
	}

}
/**
* Change featured Image Box
**/
function wp3s_pvslider_metaboxes_change() {
	remove_meta_box('postimagediv','wp3s_pvslider', 'side');
	add_meta_box('postimagediv', __('Image (Required)', 'wp3s_prevision'), 'post_thumbnail_meta_box', 'wp3s_pvslider', 'normal', 'high');
}

/**
* Add note below Featured Image
*/
function wp3s_pvslider_featured_image_note($content) {

	// only on this post type
	$screen = get_current_screen();
	if (!empty($screen) && 'wp3s_pvslider' == $screen->post_type) {
		$content .= '<p class="description">' . esc_html('Please provide an image that has a size greater than 1920x1080.','wp3s_prevision') . '</p>';
	}
	
	return $content;
}

/**********************************
 * ADMIN COLUMNS
 **********************************/

/**
 * Add/remove slide list columns
 * Add thumbnail, type, order
 */
function wp3s_pvslider_columns($columns) {
	
	// insert thumbnail after checkbox
	$insert_array = array(
		'wp3s_pvslider_thumbnail' => __('Image','wp3s_prevision'),
	);
	$columns = wp3s_prevision_array_merge_after_key($columns, $insert_array, 'cb');
	
	return $columns;
}

/**
 * Change slide list column content
 * Add content to new columns
 */
function wp3s_pvslider_columns_content($column) {
	
	global $post;
	
	switch ($column) {
		// Thumbnail
		case 'wp3s_pvslider_thumbnail' :
			if (has_post_thumbnail()) {
				echo '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_post_thumbnail($post->ID, 'wp3s_pvslider', array('style' => 'width: 280px; height: auto')) . '</a>';
			}
			
			break;
	}
}


/**
 * Merge an array into another array after a specific key
 *
 * Meant for one dimensional associative arrays
 * Used to insert post type overview columns
 */

if (!function_exists( 'wp3s_prevision_array_merge_after_key')) {
	 
	function wp3s_prevision_array_merge_after_key($original_array, $insert_array, $after_key) {

		$modified_array = array();

		// loop original array items
		foreach($original_array as $item_key => $item_value) {
		
			// rebuild the array one item at a time
			$modified_array[$item_key] = $item_value;
			
			// insert array after specific key
			if ($item_key == $after_key) {
				$modified_array = array_merge($modified_array, $insert_array);
			}
		
		}

		return $modified_array;

	}
	
}