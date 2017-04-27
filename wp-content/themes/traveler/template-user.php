<?php
    /*
     * Template Name: User Dashboard
    */
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Template Name : User template
     *
     * Created by ShineTheme
     *
     */

    get_header();
    global $current_user;

    $lever = $current_user->roles;
    $url_id_user = '';
    if (!empty($_REQUEST['id_user'])) {
        $id_user_tmp = $_REQUEST['id_user'];
        $current_user = get_userdata($id_user_tmp);
        $url_id_user = $id_user_tmp;
    }
    if (!empty($_REQUEST['sc'])) {
        $sc = $_REQUEST['sc'];
    } else {
        $sc = 'setting';
    }
?>
    <div class="container">
        <h1 class="page-title">
            <?php st_the_language('account_settings') ?>
        </h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <aside class="user-profile-sidebar">
                    <div class="user-profile-avatar text-center">
                        <?php echo st_get_profile_avatar($current_user->ID, 300); ?>
                        <h5><?php echo esc_html($current_user->display_name) ?></h5>

                        <p><?php echo st_get_language('user_member_since') . mysql2date(' M Y', $current_user->data->user_registered); ?></p>
                    </div>
                    <ul class="list user-profile-nav">
                        <?php
                            if (empty($_REQUEST['id_user'])) {
                                ?>
                                <li <?php if ($sc == 'overview') echo 'class="active"' ?>>
                                    <a href="<?php echo esc_url(add_query_arg('sc', 'overview'));

                                    ?>"><i class="fa fa-user"></i><?php st_the_language('user_overview') ?></a>
                                </li>
                                <li <?php if ($sc == 'setting') echo 'class="active"' ?>>
                                    <a href="<?php echo esc_url(add_query_arg('sc', 'setting')) ?>"><i
                                            class="fa fa-cog"></i><?php st_the_language('user_settings') ?></a>
                                </li>
                                <!--<li <?php /*if($sc == 'photos')echo 'class="active"' */
                                ?>>
                                <a href="<?php /*echo get_permalink().'&sc=photos' */
                                ?>"><i class="fa fa-camera"></i><?php /*st_the_language('user_my_travel_photos') */
                                ?></a>
                            </li>-->
                                <li <?php if ($sc == 'booking-history') echo 'class="active"' ?>>
                                    <a href="<?php echo esc_url(add_query_arg('sc', 'booking-history')) ?>"><i
                                            class="fa fa-clock-o"></i><?php st_the_language('user_booking_history') ?>
                                    </a>
                                </li>
                                <li <?php if ($sc == 'wishlist') echo 'class="active"' ?>>
                                    <a href="<?php echo esc_url(add_query_arg('sc', 'wishlist')) ?>"><i
                                            class="fa fa-heart-o"></i><?php st_the_language('user_wishlist') ?></a>
                                </li>

                                    <li class="menu cursor">
                                        <a id="menu_partner" class="cursor" style="cursor: pointer !important"><i
                                                class="fa fa-steam "></i><?php _e('Manage Booking',ST_TEXTDOMAIN) ?> <i
                                                class="icon_partner fa fa-angle-left"></i></a>
                                        <ul id="sub_partner" class="list user-profile-nav" style="display: none">
                                            <?php $df = array(
                                                array(
                                                    'title'      => 'Hotel',
                                                    'id_partner' => 'hotel',
                                                ),
                                                array(
                                                    'title'      => 'Rental',
                                                    'id_partner' => 'rental',
                                                ),
                                                array(
                                                    'title'      => 'Car',
                                                    'id_partner' => 'car',
                                                ),
                                                array(
                                                    'title'      => 'Tour',
                                                    'id_partner' => 'tour',
                                                ),
                                                array(
                                                    'title'      => 'Activity',
                                                    'id_partner' => 'activity',
                                                ),
                                            )
                                            ?>
                                            <?php $list_partner = st()->get_option('list_partner', $df); ?>
                                            <?php foreach ($list_partner as $k => $v): ?> 
											
										
											  <?php if ($v['id_partner'] == 'car'): ?>
                                                    <li <?php if ($sc == 'inside-cancellation') echo 'class="active"' ?>>
                                                        <a href="<?php echo esc_url(add_query_arg('sc', 'inside-cancellation')) ?>"><i
                                                                class="fa fa-times-circle"></i><?php st_the_language('Inside Cancellation') ?>
                                                        </a>
                                                    </li>
                                                    <li <?php if ($sc == 'online-checking') echo 'class="active"' ?>>
                                                        <a href="<?php echo esc_url(add_query_arg('sc', 'online-checking')) ?>"><i
                                                                class="fa fa-check-square-o"></i><?php st_the_language('Online Checking') ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ($v['id_partner'] == 'activity'): ?>
                                                    <li <?php if ($sc == 'user-print-e-ticket') echo 'class="active"' ?>>
                                                        <a href="<?php echo esc_url(add_query_arg('sc', 'print-e-ticket')) ?>"><i
                                                                class="fa fa-print"></i><?php st_the_language('Print E-Ticket') ?>
                                                        </a>
                                                    </li>
                                                   
                                                <?php endif; ?>
                                                <?php if ($v['id_partner'] == 'activity'): ?>
                                                    <li <?php if ($sc == 'user-hotel-booking') echo 'class="active"' ?>>
                                                        <a href="<?php echo esc_url(add_query_arg('sc', 'hotel-booking')) ?>"><i class="fa fa-bed" aria-hidden="true"></i><?php st_the_language('Hotel Bookings') ?>
                                                        </a>
                                                    </li>
                                                   
                                                <?php endif; ?>
                                              

                                               

                                            <?php endforeach; ?>





                                            <!--<li <?php /*if($sc == 'create-cruise')echo 'class="active"' */ ?>>
                                            <a href="<?php /*echo add_query_arg('sc','create-cruise') */ ?>"><i class="fa fa-ship"></i><?php /*st_the_language('user_create_cruise') */ ?></a>
                                        </li>
                                        <li <?php /*if($sc == 'my-cruise')echo 'class="active"' */ ?>>
                                            <a href="<?php /*echo add_query_arg('sc','my-cruise') */ ?>"><i class="fa fa-ship"></i><?php /*st_the_language('user_my_cruise') */ ?></a>
                                        </li>
                                        <li <?php /*if($sc == 'create-cruise-cabin')echo 'class="active"' */ ?>>
                                            <a href="<?php /*echo add_query_arg('sc','create-cruise-cabin') */ ?>"><i class="fa fa-ship"></i><?php /*st_the_language('user_create_cruise_cabin') */ ?></a>
                                        </li>
                                        <li <?php /*if($sc == 'my-cruise-cabin')echo 'class="active"' */ ?>>
                                            <a href="<?php /*echo add_query_arg('sc','my-cruise-cabin') */ ?>"><i class="fa fa-ship"></i><?php /*st_the_language('user_my_cruise_cabin') */ ?></a>
                                        </li>-->
                                        </ul>
                                    </li>
                            
                            <?php } else { ?>
                                <li <?php if ($sc == 'setting-info') echo 'class="active"' ?>>
                                    <a href="<?php echo esc_url(add_query_arg(array('sc'=>'setting-info','id_user'=>$url_id_user)));?>"><i
                                            class="fa fa-cog"></i><?php st_the_language('user_settings') ?>
                                    </a>
                                </li>
                            <?php } ?>
                    </ul>
                </aside>
            </div>
            <div class="col-md-9">
                <?php
                    if (!empty($_REQUEST['sc'])) {
                        $sc = $_REQUEST['sc'];
                    } else {
                        $sc = 'setting';
                    }
                    if (!empty($_REQUEST['id_user'])) {
                        echo st()->load_template('user/user', 'setting-info', get_object_vars($current_user));
                    } else {
                        echo st()->load_template('user/user', $sc, get_object_vars($current_user));
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="gap"></div>
<?php
    get_footer();
?>