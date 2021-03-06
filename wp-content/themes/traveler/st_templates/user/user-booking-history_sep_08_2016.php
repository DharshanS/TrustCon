<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User booking history
 *
 * Created by ShineTheme
 *
 */
?>
<div class="st-create">
    <h2><?php STUser_f::get_title_account_setting() ?></h2>
</div>
<?php
$class_user = new STUser_f();
//$html = $class_user->get_book_history();
$html = $class_user->get_book_history_loz();
if(!empty($html)){
?>
    <table class="table table-bordered table-striped table-booking-history">
        <thead>
        <tr>
            <?php /*?><th><?php st_the_language('user_type')?></th>
            <th><?php st_the_language('user_title')?></th>
            <th><?php st_the_language('user_location') ?></th>
            <th><?php st_the_language('user_order_date')?></th>
            <th><?php st_the_language('user_execution_date') ?></th>
            <th><?php st_the_language('user_cost') ?></th>
            <th><?php st_the_language('action') ?></th><?php */?>
            <th>TRIPID</th>
            <th>PNR</th>
            <th>Provider Code</th>
            <th>From City</th>
            <th>To City</th>
            <th>Depart</th>
            <th>Arrive</th>
            <th>Cost</th>
            <th>Booking Date</th>
            <th>Reason</th>
            <th>Type</th>
            <th>Tran ID</th>
            <th>Payment Status</th>
        </tr>
        </thead>
        <tbody id="data_history_book">
        <?php
        echo balanceTags($html);
        ?>
        </tbody>
    </table>
    <span class="btn btn-primary btn_load_his_book" data-per="2" ><?php st_the_language('user_load_more') ?></span>
<?php }else{ ?>
    <table class="table table-bordered table-striped table-booking-history">
        <thead>
        <tr>
            <th>TRIPID</th>
            <th>PNR</th>
            <th>Provider Code</th>
            <th>From City</th>
            <th>To City</th>
            <th>Depart</th>
            <th>Arrive</th>
            <th>Cost</th>
            <th>Booking Date</th>
            <th>Reason</th>
            <th>Type</th>
            <th>Tran ID</th>
            <th>Payment Status</th>
        </tr>
        </thead>
    </table>
    <h5><?php st_the_language('user_no_booking_history') ?></h5>
<?php } ?>