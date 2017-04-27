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


// admin menu for Embacy Letter Fee Sep 21 2016
add_action( 'admin_menu', 'letterprice_menu' );
function letterprice_menu() 
{
	add_menu_page('Embacy Letter Fee', 'Embacy Letter Fee', 'manage_options','letter-price','add_letterprice');
	add_submenu_page('letter-price', 'Embacy Letter Fee', 'Embacy Letter Fee', 'manage_options','add-letterprice', 'add_letterprice');
}
function add_letterprice()
{
  include('add_letterprice.php'); 
}
// admin menu for CWB price August 29 2016
add_action( 'admin_menu', 'cwb_menu' );
function cwb_menu() 
{
	add_menu_page('Child with Bed CWB Price', 'Child with Bed Price', 'manage_options','cwb-price','add_cwbprice');
	add_submenu_page('cwb-price', 'Child with Bed Price', 'Child with Bed Price', 'manage_options','add-cwbprice', 'add_cwbprice');
}

function add_cwbprice()
{
  include('add_cwbprice.php'); 
}
// Aug 30 2016
add_action( 'admin_menu', 'transport_menu' );
function transport_menu() 
{
	add_menu_page('Tour transport Price', 'Tour transport Price', 'manage_options','transport-price','add_transportprice');
	add_submenu_page('transport-price', 'Tour transport Price', 'Tour transport Price', 'manage_options','add-transportprice', 'add_transportprice');
}

function add_transportprice()
{
  include('add_transportprice.php'); 
}

// Aug 31 2016
add_action( 'admin_menu', 'airtransport_menu' );
function airtransport_menu() 
{
	add_menu_page('Air Transport Price', 'Air Transport Price', 'manage_options','airtransport-price','add_airtransportprice');
	add_submenu_page('airtransport-price', 'Air Transport Price', 'Air Transport Price', 'manage_options','add-airtransportprice', 'add_airtransportprice');
}

function add_airtransportprice()
{
  include('add_airtransportprice.php'); 
}

## 31/10/15

/*******************************add Commission**********************/

add_action( 'admin_menu', 'my_commission_menu' );

function my_commission_menu() 
{
	add_menu_page('My Commission Options', 'Agent Commission', 'manage_options','my-commission','add_commission');
	add_submenu_page('my-commission', 'Add Commission', 'Add Commission', 'manage_options','add-commission', 'add_commission');
	
}

function add_commission()
{
  include('add_commission.php'); 
}

/*******************************add Webfaire Discount**********************/

add_action( 'admin_menu', 'my_webfaire_discount_menu' );

function my_webfaire_discount_menu() 
{
	
	add_menu_page('My Webfaire Options', 'Webfaire Discount', 'manage_options','my-webfaire-discount','list_webfaire_discount');
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

## - Custom Login Page

function redirect_login_page() { 
    $login_page  = home_url( '/login/' );
    $page_viewed = basename($_SERVER['REQUEST_URI']);
 
    if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }
}
add_action('init','redirect_login_page');

##  Custom Login Page

##  Custom User Role ( Agent )
//add_role( 'Agent', 'Agent', array('read'=>true, 'edit_posts'=>true, 'upload_files'=>true, 'delete_posts'=>true, 'remove_users'=>true, 'edit_users'=>true) );
add_role( 'agent', 'Agent', array('read'=>true, 'edit_posts'=>true, 'upload_files'=>true));
//remove_role('Agent');


## Get Current Logged User Role
function get_user_role() {
	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	return $user_role;
}

## Login Role Authentication

add_filter( 'authenticate', 'my_custom_authenticate', 10, 3 );
function my_custom_authenticate( $user, $username, $password )
{
    //Get POSTED value
    $Role = $_POST['Role'];

    //Get user object
    $user = get_user_by('login', $username );
	// Get user Role
	//echo $user->roles[0]; die;

    //Get stored value
        //$stored_value = get_user_meta($user->ID, 'wp_capabilities', true);

    if(!$user || empty($Role) || $Role !=$user->roles[0]){
        //User note found, or no value entered or doesn't match stored value - don't proceed.
            remove_action('authenticate', 'wp_authenticate_username_password', 20); 

        //Create an error to return to user
            $user = new WP_Error( 'denied', __("<strong>ERROR</strong>: You're unique identifier was invalid.") );
    }

    //Make sure you return null 
    return null;
}

function register_script()
{

 wp_register_script( 'search-v1',TRAVELER__PLUGIN_URL . 'tr-search.js', 'jquery' );
 wp_enqueue_script ( 'search-v1' );
}
//add_action( 'wp_enqueue_scripts', 'register_script' );
add_action( 'init', 'wpse26388_rewrites_init' );
function wpse26388_rewrites_init(){
    add_rewrite_rule(
        '^travel/free$',
        'index.php?/travel/wp-content/plugins/traveler/free-booking.php',
        'top' );
}