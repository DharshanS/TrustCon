	<?php
	$pluginurl  = plugins_url( '', __FILE__ );
	$plugin_dir_path = dirname(__FILE__);
	/*function admin_load_js(){
		wp_enqueue_script( 'custom_js', plugins_url( '/js/jquery-1.11.3.min.js', __FILE__ ), array('jquery') );
	}
	add_action('admin_enqueue_scripts', 'admin_load_js');*/
	//wp_enqueue_script('test', plugin_dir_url(__FILE__) . 'js/jquery-1.11.3.min.js');
	
	global $wpdb;
    $booking = 'booking_transport';
	$room = 'room';
	$tour = 'tour';
	$hotel = 'hotel';
	
	if($_REQUEST['cancel_id']!="")
	{
	$sql_delete="UPDATE  $booking SET `is_cancel`='1',cancel_dt = '".date('Y-m-d')."'  WHERE id='".$_REQUEST['cancel_id']."'";

	$res=$wpdb->query($sql_delete);	
	
	  if($res>0)
		{
			$msg = "<span style='color:red'>Booking Canceled!</span>";
		}
	}

	if($_REQUEST['del_id']!="")
	{
	$sql_delete="UPDATE  $booking SET `status`='1'  WHERE id='".$_REQUEST['del_id']."'";

	$res=$wpdb->query($sql_delete);	
	
	  if($res>0)
		{
			$msg = "<span style='color:red'>Booking Deleted!</span>";
		}
	}

	if($_REQUEST['trash_id']!="")
	{
	$sql_delete="DELETE FROM $booking WHERE id='".$_REQUEST['trash_id']."'";

	$res=$wpdb->query($sql_delete);	
	
	  if($res>0)
		{
			$msg = "<span style='color:red'>Booking Trashed Forever!</span>";
		}
	}
		
	if($_REQUEST['avail_id']!="")
	{
		$sql_avail="UPDATE  $booking SET `is_cancel`='0',cancel_dt = '0000-00-00'  WHERE id='".$_REQUEST['avail_id']."'";

		$res_avail=$wpdb->query($sql_avail);	

	  if($res_avail>0)
		{
			$msg = "<span style='color:green'>Booking Un Canceled!</span>";
		}
	
	}
	
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $pluginurl;?>/css/style.css"  />
<script src="<?php echo $pluginurl;?>/js/jquery-1.11.3.min.js"></script>
<!-- DatePicker -->
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $pluginurl;?>/jsDatePick_ltr.css" />
<script type="text/javascript" src="<?php echo $pluginurl;?>/jsDatePick.full.1.3.js"></script>
<!-- DatePicker -->
<!--lightbox-->
<link href="<?php echo $pluginurl;?>/lightbox/bootstrap.min.css" rel="stylesheet" />
<link type="text/css" rel="stylesheet" href="<?php echo $pluginurl;?>/lightbox/featherlight.min.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $pluginurl;?>/lightbox/featherlight.gallery.min.css" />
<!--lightbox-->
</head>
<body>
<h1>Transport Booking System</h1>
<h2>Booking Report</h2> 
<h3><?php echo $msg;?></h3>
   
<div class="wrap">
<!-- Search-->
<div style="float:right;">
<form id="form1" name="form1" method="post" action="" >
Report From :- <input type="text" name="from" id="from" placeholder="Date From" value="" />
To :- <input type="text" name="to" id="to" placeholder="Date To" value="" />
<input type="submit" name="sub" id="sub" class="add-new-h2" value="Go" />
</form>
</div>
<!-- Bluk Action-->
<form id="form1" name="form1" method="post" action="" >
<select name="item" id="item">
<option value="">Bulk Actions</option>
<option value="cancel">Cancel</option>
<option value="movetrash">Move to Trash</option>
</select>
<input type="submit" name="action" id="action" class="add-new-h2" value="Apply" />
<input type="submit"  class="add-new-h2" value="Reset" />
</form>
<div style="float:right;"><a href="<?php echo $pluginurl;?>/create_csv.php" title="Download Report"><img src="<?php echo $pluginurl;?>/img/excel.gif" border="0" /></a></div>
<!-- Bluk Action-->
<div style="height:50px;"></div>
<table class="wp-list-table widefat fixed posts">
	<thead>
	<tr>
		<th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox" /></th><th scope='col' id='title' class='manage-column column-title sortable desc'  style=""><span>Customer</span></span></th><th scope='col' id='categories' class='manage-column column-categories'  style="">Booking ID</th><th scope='col' id='categories' class='manage-column column-categories'  style="">Booking Dt</th><th scope='col' id='categories' class='manage-column column-categories'  style="">Email</th><th scope='col' id='categories' class='manage-column column-categories'  style="">Price</th><th scope='col' id='categories' class='manage-column column-categories'  style="">Payment TRN ID</th><th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>Action</span></th></tr>
	</thead>

	<tfoot>
    <tr>
		<th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox" /></th><th scope='col' id='title' class='manage-column column-title sortable desc'  style=""><span>Customer</span></span></th><th scope='col' id='categories' class='manage-column column-categories'  style="">Booking ID</th><th scope='col' id='categories' class='manage-column column-categories'  style="">Booking Dt</th><th scope='col' id='categories' class='manage-column column-categories'  style="">Email</th><th scope='col' id='categories' class='manage-column column-categories'  style="">Price</th><th scope='col' id='categories' class='manage-column column-categories'  style="">Payment TRN ID</th><th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>Action</span></th></tr>
	</tfoot>

	<tbody id="the-list">
    <?php 
	## For Pagination
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 10;
	$offset = ( $pagenum - 1 ) * $limit;
	if($_REQUEST['from'] !='' && $_REQUEST['to'] !='')
	{
	$sql = $wpdb->get_results( "SELECT * FROM $booking WHERE booking_dt >= '".$_REQUEST['from']."' AND booking_dt <= '".$_REQUEST['to']."'  ORDER BY name ASC LIMIT $offset, $limit" );
	
	$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM $booking WHERE booking_dt >= '".$_REQUEST['from']."' AND booking_dt <= '".$_REQUEST['to']."' " );
	}else{
	$sql = $wpdb->get_results( "SELECT * FROM $booking  ORDER BY name ASC LIMIT $offset, $limit" );
	
	$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM $booking  " );
	}
	$num_of_pages = ceil( $total / $limit );
	## Pagination links
	$page_links = paginate_links( array(
    'base' => add_query_arg( 'pagenum', '%#%' ),
    'format' => '',
    'prev_text' => __( '&laquo;', 'aag' ),
    'next_text' => __( '&raquo;', 'aag' ),
	'prev_next' => True,
	'prev_text' => __('« Previous'),
	'next_text' => __('Next »'),
	'total' => $num_of_pages,
    'current' => $pagenum
	) );
	## Pagination links
	
	
	
	
  if(count($sql)>0)
  {
	foreach($sql as $sql_result)
    {
		
	?>
            <tr id="post-1" class="post-1 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">
            <th scope="row" class="check-column">
                            
            <input id="cb-select-1" type="checkbox" name="post[]" value="1" />
            <div class="locked-indicator"></div>
			</th>
            
            <td class="tags column-tags"><?php echo $sql_result->name;?></td>	
            <td class="comments column-comments"><strong><?php echo $sql_result->booking_id;?></strong></td>
            <td class="comments column-comments"><?php echo $sql_result->booking_dt;?></td>
            <td class="tags column-tags "><?php echo $sql_result->email;?></td>	
            <td class="tags column-tags">LKR <?php echo $sql_result->price;?></td>
            <td class="tags column-tags"><?php echo $sql_result->payment_trn_id;?></td>
            <td class="date column-date">
            <?php if($sql_result->is_cancel==1)
			{
			?>
            &nbsp;<a title="Uncancel?" href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-booking-report&avail_id=<?php echo $sql_result->id;?>" class="xxadd-new-h2">Un Cancel</a><?php 
			} else { ?>&nbsp;<a title="Cancel?" onclick="return confirm('Are You Sure To CANCEL ?');" href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-booking-report&cancel_id=<?php echo $sql_result->id;?>" class="xxadd-new-h2">Cancel</a><?php 
			} 
			?>
            <a title="Trash?" onclick="return confirm('Are You Sure To TRASH forever ?');" href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-booking-report&trash_id=<?php echo $sql_result->id;?>" class="xcadd-new-h2">Trash</a>&nbsp;
            </td>	
            </tr>
            
      <?php } 
	  } else { ?>
      <tr id="post-1" class="post-1 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">
            <th scope="row" >
                <td colspan="8" style="float:left; alignment-adjust:middle">No Booking Data! </td>		
            </th>
				
      </tr>
			<?php  }  
			if ( $page_links ) 
			{
				echo '<tr id="post-1" class="post-1 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0"><td class="date column-date" colspan="8">' . $page_links . '</td></tr>';
			}
			?>      
                
	</tbody>
</table>
	

</form>
</div>
</body>
</html>
<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"from",
			dateFormat:"%Y-%m-%d"
			/*selectedDate:{				This is an example of what the full configuration offers.
				day:5,						For full documentation about these settings please see the full version of the code.
				month:9,
				year:2006
			},
			yearsRange:[1978,2020],
			limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
		new JsDatePick({
			useMode:2,
			target:"to",
			dateFormat:"%Y-%m-%d"
			/*selectedDate:{				This is an example of what the full configuration offers.
				day:5,						For full documentation about these settings please see the full version of the code.
				month:9,
				year:2006
			},
			yearsRange:[1978,2020],
			limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
	};
</script>
<script>
jQuery(document).ready( function () {
	
	jQuery('#pagelimit').change( function ()
	{ 
		jQuery('#form1').submit();
		jQuery('#pagelimit').val(<?php echo $_REQUEST['pagelimit'];?>);
	});
	
	jQuery('#sub').click( function () { 
		
      if(jQuery('#sub').val() !='')
	  {
			var table = 'city';
			var s = jQuery('#s').val();
			
			jQuery.ajax({
			type: "POST",
			url: "ajaxpages/searchresult.php",
			data: 's='+s+'&table='+table,
			success: function(data) {
				//alert(data); //return false; 
				if(data == 1)
				{
					jQuery('#loader').fadeOut('slow'); 
					location.href = 'myaccount';
				}else{
					alert('Invalid Email OR Password !!');
					jQuery('#loader').fadeOut('slow'); 
					jQuery('#user').val('');
					jQuery('#pass').val('');
					window.reload();
					jQuery('#user').focus();
				}
			}
			
			});	
	  }
	  
	});
	
});
</script>
<!-- Light box-->
<script src="<?php echo $pluginurl;?>/lightbox/jquery-1.7.0.min.js"></script>
<script src="<?php echo $pluginurl;?>/lightbox/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $pluginurl;?>/lightbox/featherlight.gallery.min.js" type="text/javascript" charset="utf-8"></script>
<script>
	jQuery(document).ready(function(){
		jQuery('.gallery').featherlightGallery({
			gallery: {
				fadeIn: 300,
				fadeOut: 300
			},
			openSpeed:    300,
			closeSpeed:   300
		});
		jQuery('.gallery2').featherlightGallery({
			gallery: {
				next: 'next »',
				previous: '« previous'
			},
			variant: 'featherlight-gallery2'
		});
	});

	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//stats.g.doubleclick.net/dc.js','ga');

	ga('create', 'UA-5342062-6', 'noelboss.github.io');
	ga('send', 'pageview');
</script>
<!-- Light box-->