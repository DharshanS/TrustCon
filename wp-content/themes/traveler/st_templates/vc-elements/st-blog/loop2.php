<div class="col-md-12">
    <div class="row">
        <div class="col-md-3">
            <header class="thumb-header">
                    <?php
                    $img = get_the_post_thumbnail( get_the_ID() , array(800,600,'bfi_thumb'=>true)) ;
                    if(!empty($img)){
                        echo balanceTags($img);
                    }else{
                        echo '<img width="800" height="600" alt="no-image" class="wp-post-image" src="'.get_template_directory_uri().'/img/no-image.png">';
                    }
                    ?>
            </header>
        </div>
        <div class="col-md-5">
            <div class="thumb text-left">
                <a href="http://www.clickmybooking.com/holidays/">
                    <h5><font color="#0b378f"><?php the_title() ?></font></h5>
                </a>
                <div class="thumb-caption">
                    <p class="thumb-desc"><?php echo st_get_the_excerpt_max_charlength(70) ?></p>
                  
                     <?php if ( in_category( array( 'hotels') )) {?>

<p class="thumb-desc">( <font color="#16E23F"><i class="fa fa-map-marker"></i></font> <?php echo do_shortcode( '[types field="hotel-location" id="$hotel-location"]'); ?> )</p>
<p class="thumb-desc"><font color="#F00"> <del>LKR <?php echo do_shortcode( '[types field="normal-price" id="$normal-price"]'); ?></del></font> | LKR <?php echo do_shortcode( '[types field="hotel-offer-price" id="$hotel-offer-price"]'); ?></p>
	
             
                    <?php } else { ?>
                    <?php } ?>
                </div>
               
            </div>
        </div>
         <div class="col-md-4">
            <?php if ( in_category( array( 'hotels') )) {?>

<a class="btn btn-default btn-ghost mt10" href="<?php the_permalink() ?>">
                <?php STLanguage::st_the_language('Book Now') ?>
                <i class="fa fa-angle-right"></i>
				 </a>
          
                    <?php } else { ?>
                    <?php } ?>
            
                </div>
    </div>
</div>