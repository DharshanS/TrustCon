<?php 
/**
 * Verses Functions
 *
 * Post type, taxonomy, meta boxes, admin columns, widget, etc.
 */
 
/**********************************
* POST TYPE
**********************************/
function wp3s_pvquote_post_type() {

	// register it
	register_post_type(
		'wp3s_pvquote',
		array(
			'labels' 	=> array(
				'name'					=> _x( 'PV Quotes', 'post type general name', 'wp3s' ),
				'singular_name'			=> _x( 'Quote', 'post type singular name', 'wp3s' ),
				'add_new' 				=> _x( 'Add New', 'quote', 'wp3s' ),
				'add_new_item' 			=> __( 'Add Quote', 'wp3s' ),
				'edit_item' 			=> __( 'Edit Quote', 'wp3s' ),
				'new_item' 				=> __( 'New Quote', 'wp3s' ),
				'all_items' 			=> _x( 'All Quotes', 'staff', 'wp3s' ),
				'view_item' 			=> __( 'View Quote', 'wp3s' ),
				'search_items' 			=> __( 'Search Quotes', 'wp3s' ),
				'not_found' 			=> __( 'No quotes found', 'wp3s' ),
				'not_found_in_trash' 	=> __( 'No quotes found in Trash', 'wp3s' )
			),
			'public' 			=> true,
			'has_archive' 		=> false,
			'show_in_nav_menus' => true,
			'rewrite'			=> array(
				'slug' 	=> 'wp3s-pv-quote', // best not to use slug likely to be used by a Page (such as gallery) to avoid URL rewrite conflicts
				'with_front' => false
			),
			'supports' 			=> array( 'title', 'editor', 'page-attributes', 'revisions' )
		)
	);

}

/**********************************
 * ADMIN COLUMNS
 **********************************/

/**
 * Add/remove slide list columns
 * Add thumbnail, type, order
 */
function wp3s_pvquote_columns($columns) {
		
	// insert caption after title
	$insert_array = array(
		'wp3s_pvquote_caption' => __('Quotes:','wp3s_prevision')
	);
	$columns = wp3s_prevision_array_merge_after_key($columns, $insert_array, 'title');	
	
	return $columns;
}

/**
 * Change slide list column content
 * Add content to new columns
 */
function wp3s_pvquote_columns_content($column) {
	
	global $post;
	
	switch ($column) {
		
		// Caption
		case 'wp3s_pvquote_caption' :
			
			$page_object = get_page($post->ID);
			$quote = strip_tags($page_object->post_content, '<b><strong><i><em>');
			
			if (empty($caption)) {
				$caption = __('No Quote', 'wp3s_prevision');
			}
			
			echo $quote;
			
			break;
	}
}