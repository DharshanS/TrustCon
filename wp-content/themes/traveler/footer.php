<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Footer
 *
 * Created by ShineTheme
 *
 */
    $footer_template=st()->get_option('footer_template');
    if(is_singular())
    {
        if($meta=get_post_meta(get_the_ID(),'footer_template',true)){
            $footer_template=$meta;
        }
    }
    if($footer_template){
        echo '<footer id="main-footer">';
        echo STTemplate::get_vc_pagecontent($footer_template);
        echo ' </footer>';
    }else
    {
?>
<!--        Default Footer -->
    <footer id="main-footer">
        <div class="container text-center">
            <div align="left">Copy © 2015 Click My Booking. All Rights Reserved</div><div align="right"> About Us | Deals | Visa Service | Privacy Policy | Contact Us </div>
      
</div>
    </footer>
<?php }?>
        </div><!--End Row-->
    </div>
<!--    End #Wrap-->

<!-- Gotop -->
<div id="gotop" title="<?php _e('Go to top',ST_TEXTDOMAIN)?>">
    <i class="fa fa-chevron-up"></i>
</div>
<!-- End Gotop -->
<?php wp_footer(); ?>
</body>
</html>
