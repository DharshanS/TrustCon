<?php

/*

Template Name: Flight Request

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

Rajib Ganguly 

 */

 

## Rajib test

/*echo '<pre>';

print_r($_POST);

die('Submited');*/

 # Rajib test


//error_log(" ------------------- > Flight Request ");


$dt='';	

if(isset($_POST)){

$dt=base64_encode(serialize($_POST));

} 

get_header();

    $sidebar_id=apply_filters('st_blog_sidebar_id','blog');

?>

<div id="resultloader">

	<div class="container">
		<h1 class="page-title">
			
		</h1>
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
								<div align=center><!--<img src="http://www.clickmybooking.com/wp-content/uploads/2015/08/skyscraper_160x600.jpg" width="160px" height="600">--></div>
							</div>
							<div class="col-sm-6">
								<div align="center">
									<?php if($_POST['mode']=='oneway'){?>
									<div class="row">
										<div class="col-sm-4"> <?php echo $_POST['from_city'];?> </div>
										<div class="col-sm-4"> <?php echo $_POST['depart_date'];?><br>
										</div>
										<div class="col-sm-4"><?php echo $_POST['to_city'];?> </div>
									</div>
									<?php }else if($_POST['mode']=='roundtrip'){ ?>
									<div class="row">
										<div class="col-sm-4"> <?php echo $_POST['from_city'];?> </div>
										<div class="col-sm-4"> <?php echo $_POST['depart_date'];?><br>
											<?php echo $_POST['return_date'];?> </div>
										<div class="col-sm-4"><?php echo $_POST['to_city'];?> </div>
									</div>
									<?php } ?>
									<br>
									<br>
									<br>
									<?php echo $_POST['adult'];?> Adult(s) <?php echo $_POST['child'];?> Child(s) <?php echo $_POST['infant'];?> Infant(s) | <?php echo $_POST['cabinclass'];?><br>
									<br>
								</div>
								<div class="progressbarsearch"> <?php /*?><?php echo do_shortcode('[wppb progress="75/100"]'); ?><?php */?> <img src="http://www.clickmybooking.com//wp-content/themes/traveler/images/pleasewait.gif"><br>
									Searching for the best fares.. </div>
								<br>
								<br>
								<br>
								<div align=center><!--<img src="http://www.clickmybooking.com/wp-content/uploads/2015/08/hutch_SearchPage522x282.jpg" width="522px" height="282">--></div>
							</div>
							<div class="col-sm-3">
								<div align=center><!--<img src="http://www.clickmybooking.com/wp-content/uploads/2015/08/skyscraper_160x600_2.jpg" width="160px" height="600">--></div>
							</div>
						</div>
					</div>
					<div class="entry-meta">
						<?php


                          ?>
					</div>
				</div>
				<?php

                } ?>
			</div>
			<?php

           ?>
		</div>
	</div>
</div>
<script>

function Loadresult(dt)
{  

jQuery.ajax({

	type: 'post',

	//dataType:'json',
data: {},
	data:{
            action: 'search_flights',
            dt:dt},

	//url: 'http://www.clickmybooking.com/search_result.php',

	url: '/travel/wp-admin/admin-ajax.php',

	chache: false,

	success: function(json) { 


			jQuery('#resultloader').html(json);


	}
});	

}

Loadresult('<?php echo $dt;?>');

</script>
<?php           wp_footer();
