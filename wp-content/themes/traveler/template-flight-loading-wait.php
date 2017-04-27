<?php
/*
Template Name: Loading
*/
/*
Abdul Manashi
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Page.php
 *
 * Created by ShineTheme
 *
 */
					
get_header();

    $sidebar_id=apply_filters('st_blog_sidebar_id','blog');
?>
    <div class="container">
        <h1 class="page-title"><?php //the_title()?></h1>
        <div class="row mb20">
            <?php $sidebar_pos=apply_filters('st_blog_sidebar','right');
            if($sidebar_pos=="left"){
                get_sidebar('blog');
            }
            ?>
            <div class="<?php echo apply_filters('st_blog_sidebar','right')=='no'?'col-sm-12':'col-sm-9'; ?>">
                <?php while(have_posts()){
                    the_post();
                    ?>
                    <div <?php post_class()?>>
                        <div class="entry-content">
                           
                            <div class="row">
                            <div class="col-sm-3">
                              <div align=center><img src="http://www.clickmybooking.com/wp-content/uploads/2015/08/skyscraper_160x600.jpg" width="160px" height="600"></div>
                 
                            </div>
                            <div class="col-sm-6">
                          
                           <div align="center">
                            
  <div class="row">
<div class="col-sm-4">    
                      
<b>CMB</b><br>
Colombo
</div>
<div class="col-sm-4">  
14 Aug 2015 , Fri<br>
16 Aug 2015 , Sun
</div>
<div class="col-sm-4">  
<b>BKK</b><br>
Bangkok
</div>
</div>
<br>
<br>
<br>
1 Adult(s) | Economy<br>
<br>
                         </div>    
                              <div class="prgbar">
                              <?php echo do_shortcode('[wppb progress="100/100"]'); ?><br>
                              <p align="center">Searching for the best fares..</p>
                              </div>
                      <br>
                      <br>
                      <br>
                      <div align=center><img src="http://www.clickmybooking.com/wp-content/uploads/2015/08/hutch_SearchPage522x282.jpg" width="522px" height="282"></div>
                 
                           
                               
                            </div>
                           
                         
                            <div class="col-sm-3">
                                
                              <div align=center><img src="http://www.clickmybooking.com/wp-content/uploads/2015/08/skyscraper_160x600_2.jpg" width="160px" height="600"></div>
                 
                             
                             
                 
                               
                            </div>
                        </div>
                        </div>
                        <div class="entry-meta">
                            <?php
                            wp_link_pages( );
                           // edit_post_link(st_get_language('edit_this_page'), '<p>', '</p>');
                            ?>
                        </div>
                    </div>
                <?php
                }?>
            </div>
            <?php
            if($sidebar_pos=="right"){
                get_sidebar('blog');
            }
            ?>
        </div>
    </div>


<?php
get_footer();