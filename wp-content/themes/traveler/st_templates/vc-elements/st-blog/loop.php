<?php
 $col = 12 / $st_blog_style;
?>
<div class="col-md-<?php echo esc_attr($col) ?>">
    <div class="thumb text-center">
        <header class="thumb-header">
            <a class="hover-img curved" href="<?php the_permalink() ?>">
                <?php
                $img = get_the_post_thumbnail( get_the_ID() , array(800,600,'bfi_thumb'=>true)) ;
                if(!empty($img)){
                    echo balanceTags($img);
                }else{
                    echo '<img width="800" height="600" alt="no-image" class="wp-post-image" src="'.get_template_directory_uri().'/img/no-image.png">';
                }
                ?>
               
            </a>
        </header>
        <div class="thumb-caption text-center">

	
	
		 <?php 
if ( in_category( 'flights' )) {?>
 <h5 class="hover-title-top-left hover-hold"><font color="#FF7707"><i class="fa fa-plane"></i></font> <?php the_title() ?></h5>
<p align="center"><?php echo do_shortcode( '[types field="airline-name" id="$airline-name"]'); ?> | LKR <?php echo do_shortcode( '[types field="flight-sell-price" id="$flight-sell-price"]'); ?></p>
	<a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink() ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                               return false;" target="_blank" title="Share on Facebook">

                                                      <img src="http://366.370.myftpupload.com/wp-content/uploads/2015/07/1437688112_square-facebook.png" height="32px" width="32px"> 
                                                    </a> <a href="https://twitter.com/share?url=<?php the_permalink() ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                               return false;" target="_blank" title="Share on Twitter">

                                                        <img src="http://366.370.myftpupload.com/wp-content/uploads/2015/07/1437688132_square-twitter.png"  height="32px" width="32px"> 
                                                    </a>  <a href="https://plus.google.com/share?url=<?php the_permalink() ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');
                                                               return false;" target="_blank" title="Share on Google+">

                                                       <img src="http://366.370.myftpupload.com/wp-content/uploads/2015/07/1437688150_square-google-plus.png"  height="32px" width="32px">                                                 

                                                    </a>  <a class="btn btn-default btn-ghost mt10" href="<?php the_permalink() ?>">
                <?php STLanguage::st_the_language('More Details') ?>
                <i class="fa fa-angle-right"></i>
				
				  
            </a>
 <?php } elseif ( in_category( array( 'hotels') )) {?>
  <h5 class="hover-title-top-left hover-hold"><?php the_title() ?></h5>
( <font color="#16E23F"><i class="fa fa-map-marker"></i></font> <?php echo do_shortcode( '[types field="hotel-location" id="$hotel-location"]'); ?> )
<p align="center"><font color="#F00"> <del>LKR <?php echo do_shortcode( '[types field="normal-price" id="$normal-price"]'); ?></del></font> | LKR <?php echo do_shortcode( '[types field="hotel-offer-price" id="$hotel-offer-price"]'); ?></p>
	                                            

                                                   <a class="btn btn-default btn-ghost mt10" href="http://www.clickmybooking.com/holidays/">
                <?php STLanguage::st_the_language('Book Now') ?>
                <i class="fa fa-angle-right"></i>
				 </a>
<?php } else { ?>
 <h5 class="hover-title-top-left hover-hold"><?php the_title() ?></h5>
	 <a class="btn btn-default btn-ghost mt10" href="<?php the_permalink() ?>">
                <?php STLanguage::st_the_language('Read More') ?>
				 <i class="fa fa-angle-right"></i>
				 </a>
<?php } ?>
		 
		 
		 
		 
		 

        </div>
    </div>
</div>



                                              

                                             
                                         