<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User my room
 *
 * Created by ShineTheme
 *
 */
?>
<div class="st-create">
    <h2><?php st_the_language('my_room') ?></h2>
</div>
<ul id="data_whislist" class="booking-list booking-list-wishlist ">
<?php
    if(!empty($_REQUEST['paged'])){
        $paged = $_REQUEST['paged'];
    }else{
        $paged = 1;
    }
    $args = array(
        'post_type' => 'hotel_room',
        'post_status'=>'publish , draft',
        'author'=>$data->ID,
        'posts_per_page'=>10,
        'paged'=>$paged
    );
    $query=new WP_Query($args);
    if ( $query->have_posts() ) {
        while ($query->have_posts()) {
            $query->the_post();
            echo st()->load_template('user/loop/loop', 'room' ,get_object_vars($data));
        }
    }else{
        echo '<h1>'.st_get_language('no_room').'</h1>';
    };
?>
</ul>
<?php st_paging_nav() ?>
<?php  wp_reset_query(); ?>


