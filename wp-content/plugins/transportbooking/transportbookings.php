<?php
/**
 * @package Transport Bookings
 * @version 1.6
 */
/*
Plugin Name: My Transport Bookings
Plugin URI: http://wordpress.org/plugins/news/
Description: Book your transport.
Author: Rajib Ganguly
Version: 1.6
Author URI: http://rajib.net/
*/


/*************************table install***************/


 /*function installhotelTables() 
 {
 global $wpdb;
 $tableName = $wpdb->prefix .'hotel_master';
 $tableName2 = $wpdb->prefix .'hotel_booking';
 
  $sql = "CREATE TABLE $tableName (
		ID int(11) NOT NULL AUTO_INCREMENT,
		HOTEL_NAME varchar(255) NOT NULL,
		ROOM_NO int(11)  NOT NULL,
		ROOM_TYPE   varchar(255)  NOT NULL,
		ACCOMODATION_TYPE int(11) NOT NULL,
		ROOM_PRICE int(11) NOT NULL,
		STATUS ENUM('0','1') NOT NULL DEFAULT '0',
		TRASH ENUM('0','1') NOT NULL DEFAULT '0',
		UNIQUE KEY ID (ID)
	) $charset_collate";  
	
	$wpdb->query($sql);
	
	
$sql_set="CREATE TABLE $tableName2 (
BOOK_ID int(11) NOT NULL AUTO_INCREMENT,
USER_ID int(11) NOT NULL,
HOTEL_ID int(11) NOT NULL,
ROOM_ID int(11) NOT NULL,
ROOM_TYPE_ID int(11) NOT NULL,
ROOM_ACC_ID int(11) NOT NULL,
ROOM_PRICE_ID int(11) NOT NULL,
BOOKINGDATE_FROM varchar(255) NOT NULL,
BOOKINGDATE_TO varchar(255) NOT NULL,
STATUS ENUM('0','1') NOT NULL DEFAULT '0',
UNIQUE KEY BOOK_ID (BOOK_ID)
) $charset_collate"; 

$wpdb->query($sql_set);


}

add_action( 'admin_notices', 'installhotelTables' );*/

/****************************table install end*******************/


/*******************************add menu**********************/

add_action( 'admin_menu', 'my_transport_menu' );


## WP - Menu & Submenu Code
//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
## WP - Menu & Submenu Code



/** Step 1. */

function my_transport_menu() 
{
    global $wpdb;
	$myquery = $wpdb->get_results("SELECT COUNT(*) as cnt FROM vehicle ");
	$totVehicle = $myquery[0]->cnt;
	$myquery = $wpdb->get_results("SELECT COUNT(*) as cnt FROM city_transport ");
	$totCity = $myquery[0]->cnt;
	$myquery = $wpdb->get_results("SELECT COUNT(*) as cnt FROM booking_transport ");
	$totBooking = $myquery[0]->cnt;
	## Top Menu
	add_menu_page( 'Online Transport Booking System', 'Transport Booking', 'manage_options', 'transport-master', 'list_vehicle','http://www.clickmybooking.com/wp-content/plugins/transportbooking/img/logistic.png',4 );
	/*add_menu_page( 'Online Transport Booking System', 'Transport Booking', 'manage_options', 'transport-master', 'list_vehicle',$_SERVER['HOST_NAME'].'/wp-content/plugins/transportbooking/img/logistic.png',4 );*/
	## Submenus
	add_submenu_page( 'transport-master', 'Manage Vehicle', "Manage Vehicle <span class='update-plugins count-1'><span class='update-count' >$totVehicle</span></span>", 'manage_options', 'transport-master', 'list_vehicle');
	add_submenu_page( 'transport-master', 'Add Vehicle', 'Add Vehicle', 'manage_options', 'add-vehicle', 'add_vehicle');
	add_submenu_page( 'transport-master', 'Manage City', "Manage City <span class='update-plugins count-1'><span class='update-count' >$totCity</span></span>", 'manage_options', 'transport-city-master', 'transport_city_master');
	add_submenu_page( 'transport-master', 'Add City', 'Add City', 'manage_options', 'add-transport-city', 'add_transport_city');
	## Booking Report
	add_submenu_page( 'transport-master', 'Booking Report', "Booking Report <span class='update-plugins count-1'><span class='update-count' >$totBooking</span></span>", 'manage_options', 'transport-booking-report', 'transport_booking_report');
}



function list_vehicle()
{
	include('list_vehicle.php');	
	
}

function add_vehicle()
{
	include('add_vehicle.php');	
	
}

function transport_city_master()
{
	include('list_city.php');	
	
}

function add_transport_city()
{
	include('add_city.php');	
	
}

function transport_booking_report()
{
	include('transport_bookings_report.php');	
	
}


/**************************************************************/


/********************function and shortcode to show in front end********************/

function show_my_transports()
{
	global $wpdb;
	$tableName = 'vehicel';
	
?>
<script language="javascript">

</script>	
<form id="form1" name="form1" method="post" action="">
  <table width="200" border="1">
    <tr>
      <td>Vehicel Name:-</td>
      <td><label for="select"></label>
       <select name="hotel" id="hotel" onchange="get_aldata()">
       <option value="-5">Select Hotel</option>
       <?php 
	   $book_my_hotel=$wpdb->get_results("SELECT * FROM $tableName WHERE status='0' AND STATUS='1' ORDER BY ID DESC");
	   foreach($book_my_hotel as $hotels)
	   {
	   ?>
       <option value="<?php echo $hotels->ID; ?>"><?php echo $hotels->HOTEL_NAME; ?></option>
       <?php } ?>
      </select></td>
    </tr>
   
    <tr>
      <td>Room No:-</td>
      <td><label for="select"></label>
         <select name="rno" id="rno">
        <option value="-4">Select Room No</option>
      </select></td>
    </tr>
    <tr>
      <td>Room Type:-</td>
      <td><label for="select"></label>
        <select name="rtype" id="rtype">
        <option value="-3">Select Room Type</option>
      </select></td>
    </tr>
    <tr>
      <td>Accomodation  Type:-</td>
      <td><label for="select"></label>
        <select name="acctype" id="acctype">
        <option value="-2">Select Accomodation</option>
      </select></td>
    </tr>
    <tr>
      <td>Room Price:-</td>
      <td><label for="select"></label>
        <select name="price" id="price">
        <option value="-1">Select Price</option>
      </select></td>
    </tr>
    <tr>
      <td>Booking Date(From):-</td>
      <td>
        <input type="text" name="sdate" id="sdate" value="" />
      </td>
    </tr>
    
     <tr>
      <td>Booking Date(To):-</td>
      <td>
        <input type="text" name="edate" id="edate" value="" />
     </td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" name="sub" id="sub" value="Submit" /></td>
     
    </tr>
  </table>
</form>	
<?php	
}



add_shortcode('my-transport', 'show_my_transports');


/*************************** STyle & script **************************************/

function my_theme_name_scripts() {
	wp_enqueue_style( 'style-name', '/css/style' );
	//wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'my_theme_name_scripts' );
