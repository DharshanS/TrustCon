<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * function
 *
 * Created by ShineTheme
 *
 */

if(!defined('ST_TEXTDOMAIN'))
define ('ST_TEXTDOMAIN','traveler');

$status=load_theme_textdomain(ST_TEXTDOMAIN,get_template_directory().'/language');


//require get_template_directory().'/inc/class.traveler.php';

get_template_part('inc/class.traveler');

st();
get_template_part('demo/demo_functions');
if ( ! isset( $content_width ) ) $content_width = 900;


// hide admin bar in front end ABDUL MANASHI
function hide_admin_bar_from_front_end(){
  if (is_blog_admin()) {
    return true;
  }
  return false;
}
add_filter( 'show_admin_bar', 'hide_admin_bar_from_front_end' );



## Rajib Added - 31/10/15

/*******************************add Commission**********************/

add_action( 'admin_menu', 'my_commission_menu' );

function my_commission_menu() 
{
	add_menu_page('My Plugin Options', 'Agent Commission', 'manage_options','my-hotel-booking','my_hotel_bokings');
	add_submenu_page('my-hotel-booking', 'Add Commission', 'Add Commission', 'manage_options','add-commission', 'add_commission');
	
}

function add_commission()
{
  include('add_commission.php'); 
}

/*******************************add Webfaire Discount**********************/

add_action( 'admin_menu', 'my_webfaire_discount_menu' );

function my_webfaire_discount_menu() 
{
	add_menu_page('My Webfaire Options', 'Webfaire Discount', 'manage_options','my-webfaire-discount','my_webfaire_discount');
	add_submenu_page('my-webfaire-discount', 'List Webfaire Discount', 'List Webfaire Discount', 'manage_options','list-webfaire-discount', 'list_webfaire_discount');
	add_submenu_page('my-webfaire-discount', 'Add Webfaire Discount', 'Add Webfaire Discount', 'manage_options','add-webfaire-discount', 'add_webfaire_discount');
	add_submenu_page('my-webfaire-discount', 'Edit Webfaire Discount', 'Edit Webfaire Discount', 'manage_options','edit-webfaire-discount', 'edit_webfaire_discount');
	
}

function list_webfaire_discount()
{
  include('list_webfaire_discount.php'); 
}

function add_webfaire_discount()
{
  include('add_webfaire_discount.php'); 
}

function edit_webfaire_discount()
{
  include('add_webfaire_discount.php'); 
}

## Rajib - Custom Login Page

function redirect_login_page() {
    $login_page  = home_url( '/login/' );
    $page_viewed = basename($_SERVER['REQUEST_URI']);
 
    if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }
}
add_action('init','redirect_login_page');

## Rajib - Custom Login Page

## Rajib - Custom User Role ( Agent )
//add_role( 'Agent', 'Agent', array('read'=>true, 'edit_posts'=>true, 'upload_files'=>true, 'delete_posts'=>true, 'remove_users'=>true, 'edit_users'=>true) );
add_role( 'Agent', 'Agent', array('read'=>true, 'edit_posts'=>true, 'upload_files'=>true));
//remove_role('Agent');



