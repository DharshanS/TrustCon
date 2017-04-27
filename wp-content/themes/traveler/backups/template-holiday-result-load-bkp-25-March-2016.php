<?php
/*
Template Name: Holiday Result Load
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

## Data COnversion
/*$cityArr = explode(',',$_POST['city']);
$city = $cityArr[0];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'] ;
*/
/*$dt='';	
if(isset($_POST)){
$dt=base64_encode(serialize($_POST));
} */


get_header();
    $sidebar_id=apply_filters('st_blog_sidebar_id','blog');
?>
<div id="resultloader">   
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
                                    <?php echo $_POST['city'];?>
                                    <input type="hidden" id="city" value="<?php echo $_POST['city'];?>"  />
                                    </div>
                                    <div class="col-sm-4">  
                                    <?php echo $_POST['checkin'];?><br>
                                    <input type="hidden" id="checkin" value="<?php echo $_POST['checkin'];?>"  />
                                    </div>
                                    <div class="col-sm-4">  
                                    <?php echo $_POST['checkout'];?>
                                    <input type="hidden" id="checkout" value="<?php echo $_POST['checkout'];?>"  />
                                    <input type="hidden" id="adult" value="<?php echo $_POST['adult'];?>"  />
                                    <input type="hidden" id="child" value="<?php echo $_POST['child'];?>"  />
                                    <input type="hidden" id="infant" value="<?php echo $_POST['infant'];?>"  />
                                    </div>
                                    </div>
                            <br>
                            <br>
                            <br>
                            <?php echo $_POST['person'];?> Adult(s) <?php echo $_POST['child'];?> Child(s) <?php echo $_POST['infant'];?> Infant(s)					<br>
                            <br>
                      </div>    
                      <div class="progressbarsearch">
                      <?php echo do_shortcode('[wppb progress="75/100"]'); ?><br>
                      Searching Hotels..
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
 </div>
 <script>
function Loadresult(){ 

var city = jQuery('#city').val();
var adult = jQuery('#adult').val();
var child = jQuery('#child').val();
var infant = jQuery('#infant').val();
var checkin = jQuery('#checkin').val();
var checkout = jQuery('#checkout').val();

//alert('city='+city+'&person='+person+'&checkin='+checkin+'&checkout='+checkout);
 
jQuery.ajax({
	type: 'post',
	//dataType:'json',
	//data:'city='+city+'&person='+person,
	//data:{dt:dt},
	data:'city='+city+'&adult='+adult+'&child='+child+'&infant='+infant+'&checkin='+checkin+'&checkout='+checkout,
	url: 'http://www.clickmybooking.com/holiday_result.php',
	//url: 'http://localhost/clickmybooking/holiday_result.php',
	chache: false,
	success: function(rdata) { 
		//alert(rdata);
		var errCnt = 0;
		
		if(errCnt == 0)
		{
			jQuery('#resultloader').html(rdata);
		}else{
			alert(rdata);
			//location.href = "http:/localhost/clickmybooking/";
			location.href = "http://www.clickmybooking.com/";
		}
	}
});	
} // End Function
Loadresult();
</script>
<?php
get_footer();