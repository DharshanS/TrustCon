<?php

/*

Template Name: Transport Result Load

*/

/*

Rajib Ganguly - 18-11-20155

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

 

## Rajib test

/*echo '<pre>';

print_r($_POST);

die('Submited');*/

 # Rajib test

 

/*$dt='';	

if(isset($_POST)){

$dt=base64_encode(serialize($_POST));

} */

get_header();

    $sidebar_id=apply_filters('st_blog_sidebar_id','blog');

?>

<div id="resultloader">
	<div class="container">
		<h1 class="page-title">
			<?php //the_title()?>
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
									<div class="row">
										<div class="col-sm-4"> <?php echo $_POST['start'];?>
											<input type="hidden" id="start" value="<?php echo $_POST['start'];?>"  />
										</div>
										<div class="col-sm-4"> <?php echo $_POST['end'];?><br>
											<input type="hidden" id="end" value="<?php echo $_POST['end'];?>"  />
										</div>
										<div class="col-sm-4"> <?php echo $_POST['from'];?>
											<input type="hidden" id="from" value="<?php echo $_POST['from'];?>"  />
										</div>
										<div class="col-sm-4"> <?php echo $_POST['to'];?>
											<input type="hidden" id="to" value="<?php echo $_POST['to'];?>"  />
										</div>
										<div class="col-sm-4"> <?php echo $_POST['person'];?>
											<input type="hidden" id="person" value="<?php echo $_POST['person'];?>"  />
										</div>
										<div class="col-sm-4"> <?php echo $_POST['vehicle'];?>
											<input type="hidden" id="vehicle" value="<?php echo $_POST['vehicle'];?>"  />
										</div>
									</div>
									<br>
									<br>
									<br>
									<?php echo $_POST['person'];?> Adult(s) <?php echo $_POST['child'];?> Child(s) <?php echo $_POST['infant'];?> Infant(s) <br>
									<br>
								</div>
								<div class="progressbarsearch"> <?php echo do_shortcode('[wppb progress="75/100"]'); ?><br>
									Searching Transportation .. </div>
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

                //get_sidebar('blog');

            }

            ?>
		</div>
	</div>
</div>
<script>

function Loadresult(){  



var start = jQuery('#start').val();

var end = jQuery('#end').val();

var person = jQuery('#person').val();

var vehicle = jQuery('#vehicle').val();

var from = jQuery('#from').val();

var to = jQuery('#to').val();



jQuery.ajax({

	type: 'post',

	//dataType:'json',

	data:'start='+start+'&end='+end+'&person='+person+'&vehicle='+vehicle+'&from='+from+'&to='+to,

	url: 'http://www.clickmybooking.com/transport_result.php',

	//url: 'http://localhost/clickmybooking/transport_result.php',

	chache: false,

	success: function(json) { //alert(json);

		//jQuery('#flt-dtl').html(json['flights']);

		<!-- Rajib Added -->

		//alert(json); //return false;

		var errCnt = 0;



		if(errCnt == 0)

		{

			jQuery('#resultloader').html(json);

		}

		else

		{

			alert(msg);

			//location.href = "http://localhost/clickmybooking/";

			location.href = "http://www.clickmybooking.com/";

		}

	}

});	

}

Loadresult();

</script>
<?php

get_footer();
