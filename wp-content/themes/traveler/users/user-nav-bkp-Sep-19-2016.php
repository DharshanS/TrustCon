<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Custom user menu
 *
 * Created by ShineTheme
 *
 */

$login_fb=st()->get_option('social_fb_login','on');
$login_gg=st()->get_option('social_gg_login','on');
$login_tw=st()->get_option('social_tw_login','on');
if(function_exists('icl_get_languages'))
{
    $langs=icl_get_languages('skip_missing=0');
}
else{
    $langs=array();
}

?>
<div class="col-md-9">

    <div class="top-user-area clearfix">
        <ul class="top-user-area-list list list-horizontal list-border ">
<li><img src="http://clickmybooking.com/wp-content/uploads/2015/07/new-deals.png" height="18px" width="45px"> Deals |</li>
            <?php if(is_user_logged_in()):?>
            <li class="top-user-area-avatar">
                <?php
                $account_dashboard = st()->get_option('page_my_account_dashboard');
                $location='#';
                if(!empty($account_dashboard)){
                    $location = esc_url(add_query_arg( 'page_id', $account_dashboard, home_url() ));
                }
                ?>
                <a href="<?php echo esc_url($location) ?>">
                    <?php
                    $current_user = wp_get_current_user();
                     echo st_get_profile_avatar($current_user->ID,40);
                    echo st_get_language('hi').', '.$current_user->display_name;
                    ?>
                </a>
            </li>
            <li>
                <a href="<?php echo wp_logout_url(home_url())?>"><?php st_the_language('sign_out')?></a>
            </li>
            <?php else: ?>
                 <li class="nav-drop"> 
                    <?php
                    $page_login = st()->get_option('page_user_login');
                    ?>
                   <a href="#" onclick="return false;"><i class="fa fa-user"></i> <?php st_the_language('sign_in')?><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i></a>
                    <ul class="list nav-drop-menu user_nav_big social_login_nav_drop" >
                        <li><a  class="" href="<?php echo get_permalink($page_login) ?>"><?php st_the_language('sign_in')?></a></li>

                        <?php if($login_fb=="on"): ?>
                        <li><a onclick="return false" class="btn_login_fb_link login_social_link" href="<?php echo STSocialLogin::get_provider_login_url('Facebook') ?>"><?php st_the_language('connect_with')?> <i class="fa fa-facebook"></i></a></li>
                        <?php endif;?>

                        <?php if($login_gg=="on"): ?>
                        <li><a onclick="return false" class="btn_login_gg_link login_social_link" href="<?php echo STSocialLogin::get_provider_login_url('Google') ?>"><?php st_the_language('connect_with')?> <i class="fa fa-google-plus"></i></a></li>

                        <?php endif;?>

                        <?php if($login_tw=="on"): ?>
                        <li><a onclick="return false" class="btn_login_tw_link login_social_link" href="<?php echo STSocialLogin::get_provider_login_url('Twitter') ?>"><?php st_the_language('connect_with')?> <i class="fa fa-twitter"></i></a></li>
                        <?php endif;?>
                    </ul>
</li>
<li class="nav-drop"><a href="<?php echo get_permalink($page_login) ?>"><i class="fa fa-user-plus"></i> Sign Up</a>
				</li>
            <?php endif;?>
            <li><img src="http://www.clickmybooking.com/wp-content/uploads/2015/07/hotline.png" width="160px" height="33px" ></li>
           
        </ul>
       
    </div>
</div>