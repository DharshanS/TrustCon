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

