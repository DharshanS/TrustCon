<?php
/*
Template Name: Flight Result Load
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
 
$dt='';	
if(isset($_POST)){
$dt=base64_encode(serialize($_POST));
} 
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
                                   <?php if($_POST['mode']=='oneway'){?>
                                    <div class="row">
                                    <div class="col-sm-4">    
                                    <?php echo $_POST['from_city'];?>
                                    </div>
                                    <div class="col-sm-4">  
                                    <?php echo $_POST['depart_date'];?><br>
                                    </div>
                                    <div class="col-sm-4">  
                                    <?php echo $_POST['to_city'];?>
                                    </div>
                                    </div>
                                   <?php }else if($_POST['mode']=='roundtrip'){ ?>
                                    <div class="row">
                                    <div class="col-sm-4">    
                                    <?php echo $_POST['from_city'];?>
                                    </div>
                                    <div class="col-sm-4">  
                                    <?php echo $_POST['depart_date'];?><br>
                                    <?php echo $_POST['return_date'];?>
                                    </div>
                                    <div class="col-sm-4">  
                                    <?php echo $_POST['to_city'];?>
                                    </div>
                                    </div>
                                   <?php } ?>
                            <br>
                            <br>
                            <br>
                            <?php echo $_POST['adult'];?> Adult(s) <?php echo $_POST['child'];?> Child(s) <?php echo $_POST['infant'];?> Infant(s) | <?php echo $_POST['cabinclass'];?><br>
                            <br>
                      </div>    
                      <div class="progressbarsearch">
                      <?php echo do_shortcode('[wppb progress="75/100"]'); ?><br>
                      Searching for the best fares..
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
function Loadresult(dt){  
jQuery.ajax({
	type: 'post',
	//dataType:'json',
	data:{dt:dt},
	url: 'http://www.clickmybooking.com/search_result.php',
	//url: 'http://localhost/clickmybooking/search_result.php',
	chache: false,
	success: function(json) { //alert(json);
		//jQuery('#flt-dtl').html(json['flights']);
		<!-- Rajib Added -->
		//alert(json); //return false;
		/*var msg = '';
		var errCnt = 0;
		var erroR = json ; 
		var res = erroR.split(" ");
		//alert(res[0]);
		
		if(res[0] == "[Error]")
		{
				msg = 'An Error Has Been Occured. Please Resubmit with Proper Valid Entry';
				errCnt = errCnt +1 ;
		}else if(json == "NO AVAILABILITY FOR THIS REQUEST")
		{
				msg = 'Airport Name/Code is Invalid. No Availability. Please Resubmit with Proper Valid Entry';
				errCnt = errCnt +1 ;
		}else if(json == "Preferred date-time is before the current departure city date-time.")
		{
				msg = 'Invalid DateTime Mentioned.Please Resubmit with Proper Valid Entry';
				errCnt = errCnt +1 ;
		}else if(res[0] == "Unknown")
		{
				msg = 'Unknown City/Airport OR Else.Please Resubmit with Proper Valid Entry';
				errCnt = errCnt +1 ;
		}else if(json == "queryDR")
		{
				msg = 'API WSDL Server is not Responding';
				errCnt = errCnt +1 ;
		}else if(json == "UNABLE TO FARE QUOTE")
		{
				msg = 'API WSDL Server is not Responding';
				errCnt = errCnt +1 ;
		}else if(json == "HOST TEMPORARILY UNAVAILABLE")
		{
				msg = 'API WSDL Server is not Responding';
				errCnt = errCnt +1 ;
		}
		//alert(res[0]);
		//alert('mm'.errCnt);
		<!-- Rajib Added -->
		if(errCnt == 0)
		{
			jQuery('#resultloader').html(json);
		}
		else
		{*/
			//jQuery( "#resultloader" ).resizable();
			//alert(json); //return false;
			jQuery('#resultloader').html(json);
			/*location.href = "http://www.clickmybooking.com/";
		}*/
	}
});	
}
Loadresult('<?php echo $dt;?>');
</script>
<?php
get_footer();