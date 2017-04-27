<?php
/**
 * @package Hotel Bookings
 * @version 1.6
 */
/*
Plugin Name: My Hotel Bokings
Plugin URI: http://wordpress.org/plugins/news/
Description: Book your hotels.
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

add_action( 'admin_menu', 'my_booking_menu' );


## WP - Menu & Submenu Code
//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
## WP - Menu & Submenu Code



/** Step 1. */

function my_booking_menu() 
{
    global $wpdb;
	$myquery = $wpdb->get_results("SELECT COUNT(*) as cnt FROM hotel ");
	$totHotel = $myquery[0]->cnt;
	$myquery = $wpdb->get_results("SELECT COUNT(*) as cnt FROM city ");
	$totCity = $myquery[0]->cnt;
	$myquery = $wpdb->get_results("SELECT COUNT(*) as cnt FROM tour ");
	$totTour = $myquery[0]->cnt;
	$myquery = $wpdb->get_results("SELECT COUNT(*) as cnt FROM room ");
	$totRoom = $myquery[0]->cnt;
	$myquery = $wpdb->get_results("SELECT COUNT(*) as cnt FROM booking ");
	$totBooking = $myquery[0]->cnt;
	//$myquery = $wpdb->get_results("SELECT COUNT(*) as cnt FROM trash ");
	//$totTrash = $myquery[0]->cnt;
	## Top Menu
	/*add_menu_page( 'Online Hotel Booking System', 'Holiday Booking', 'manage_options', 'hotel-master', 'hotel_master','http://localhost/clickmybooking/wp-content/plugins/hotelbooking/img/sleeping.png',1 );*/
	/*add_menu_page( 'Online Hotel Booking System', 'Holiday Booking', 'manage_options', 'hotel-master', 'hotel_master',$_SERVER['HOST_NAME'].'/wp-content/plugins/hotelbooking/img/sleeping.png',1 );*/
	add_menu_page( 'Online Hotel Booking System', 'Holiday Booking', 'manage_options', 'hotel-master', 'hotel_master','http://localhost:8888/travel'.'/wp-content/plugins/hotelbooking/img/sleeping.png',1 );
	## Submenus
	add_submenu_page( 'hotel-master', 'Manage Hotels', "Manage Hotels <span class='update-plugins count-1'><span class='update-count' >$totHotel</span></span>", 'manage_options', 'hotel-master', 'hotel_master');
	add_submenu_page( 'hotel-master', 'Add Hotel', 'Add Hotel', 'manage_options', 'add-hotel', 'add_hotel');
	add_submenu_page( 'hotel-master', 'Manage City', "Manage City <span class='update-plugins count-1'><span class='update-count' >$totCity</span></span>", 'manage_options', 'city-master', 'city_master');
	add_submenu_page( 'hotel-master', 'Add City', 'Add City', 'manage_options', 'add-city', 'add_city');
	add_submenu_page( 'hotel-master', 'Manage Tour', "Manage Tour <span class='update-plugins count-1'><span class='update-count' >$totTour</span></span>", 'manage_options', 'tour-master', 'tour_master');
	add_submenu_page( 'hotel-master', 'Add Tour', 'Add Tour', 'manage_options', 'add-tour', 'add_tour');
	add_submenu_page( 'hotel-master', 'Manage Room', "Manage Room <span class='update-plugins count-1'><span class='update-count' >$totRoom</span></span>", 'manage_options', 'room-master', 'room_master');
	add_submenu_page( 'hotel-master', 'Add Room', 'Add Room', 'manage_options', 'add-room', 'add_room');
	//add_submenu_page( 'hotel-master', 'Trash Manager', 'Trash Manager', 'manage_options', 'manage-trash', 'manage_trash');
	## Booking Report
	add_submenu_page( 'hotel-master', 'Booking Report', "Booking Report <span class='update-plugins count-1'><span class='update-count' >$totBooking</span></span>", 'manage_options', 'booking-report', 'booking_report');
}



function hotel_master()
{
	include('list_hotel.php');	
	
}

function add_hotel()
{
	include('add_hotel.php');	
	
}

function city_master()
{
	include('list_city.php');	
	
}

function add_city()
{
	include('add_city.php');	
	
}

function room_master()
{
	include('list_room.php');	
	
}

function add_room()
{
	include('add_room.php');	
	
}

function tour_master()
{
	include('list_tour.php');	
	
}

function add_tour()
{
	include('add_tour.php');	
	
}

function booking_report()
{
	include('bookings_report.php');	
	
}

function manage_trash()
{
	include('list_trash.php');	
	
}


/**************************************************************/


/********************function and shortcode to show in front end********************/

function show_my_hotels()
{
	global $wpdb;
	$tableName = 'hotel';
	
?>
<script language="javascript">

</script>	
<form id="form1" name="form1" method="post" action="">
  <table width="200" border="1">
    <tr>
      <td>Hotel Name:-</td>
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



add_shortcode('my-hotel', 'show_my_hotels');


/*************************** STyle & script **************************************/

function theme_name_scripts() {
	wp_enqueue_style( 'style-name', '/css/style' );
	//wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
