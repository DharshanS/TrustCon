	<?php
	$pluginurl  = plugins_url( '', __FILE__ );
	$plugin_dir_path = dirname(__FILE__);
	/*function admin_load_js(){
		wp_enqueue_script( 'custom_js', plugins_url( '/js/jquery-1.11.3.min.js', __FILE__ ), array('jquery') );
	}
	add_action('admin_enqueue_scripts', 'admin_load_js');*/
	//wp_enqueue_script('test', plugin_dir_url(__FILE__) . 'js/jquery-1.11.3.min.js');
	
	global $wpdb;
    $tableName = 'city';
	
	if($_REQUEST['del_id']!="")
	{
	$sql_delete="UPDATE  $tableName SET `status`='1'  WHERE id='".$_REQUEST['del_id']."'";

	$res=$wpdb->query($sql_delete);	
	
	  if($res>0)
		{
			$msg = "<span style='color:red'>City Deleted!</span>";
		}
	}

	if($_REQUEST['trash_id']!="")
	{
	$sql_delete="DELETE FROM $tableName WHERE id='".$_REQUEST['trash_id']."'";

	$res=$wpdb->query($sql_delete);	
	
	  if($res>0)
		{
			$msg = "<span style='color:red'>City Trashed Forever!</span>";
		}
	}
		
	if($_REQUEST['avail_id']!="")
	{
	$sql_avail="UPDATE  $tableName SET `status`='0'  WHERE id='".$_REQUEST['avail_id']."'";

$res_avail=$wpdb->query($sql_avail);	
  if($res_avail>0)
    {
		$msg = "<span style='color:green'>City Undeleted!</span>";
	}
	}
	else
	{
	$sql_avail="UPDATE  $tableName SET `status`='1'  WHERE id='".$_REQUEST['notavail_id']."'";

	$res_avail=$wpdb->query($sql_avail);	
  if($res_avail>0)
    {
	?>
		<!--<script>
        function myFunction() 
        {
            location.reload();
        }
        </script>-->
    <?php
	}	
	}
	
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $pluginurl;?>/css/style.css"  />
<script src="<?php echo $pluginurl;?>/js/jquery-1.11.3.min.js"></script>
</head>
<body>
<h1>Hotel Booking System</h1>
<h2>Manage City</h2> 
<h3><?php echo $msg;?></h3>
   
<div class="wrap">
<h2><a href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=add-city" class="add-new-h2">Add New</a></h2>
<h2 style="float:right;"><a href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=trash-master" class="add-new-h2">City Unpublished(<?php $count_trash=$wpdb->get_results("SELECT * FROM $tableName WHERE status='1' ORDER BY city ASC"); echo count($count_trash);?>)</a></h2><!-- Search-->
<div style="float:right;">
<form id="form1" name="form1" method="post" action="" >
<input type="text" name="s" id="s" placeholder="Enter Search Text" value="" />
<input type="submit" name="sub" id="sub" class="add-new-h2" value="Search" />
<input type="submit"  class="add-new-h2" value="Reset" />
</form>
</div>
<!-- Search-->
<!-- Bluk Action-->
<form id="form1" name="form1" method="post" action="" >
<select name="item" id="item">
<option value="">Bulk Actions</option>
<option value="edit">Edit</option>
<option value="movetrash">Move to Trash</option>
</select>
<input type="submit" name="action" id="action" class="add-new-h2" value="Apply" />
<input type="submit"  class="add-new-h2" value="Reset" />
</form>
<!-- Bluk Action-->

<form id="posts-filter" action="" method="get">

<input type="hidden" name="post_status" class="post_status_page" value="all" />
<input type="hidden" name="post_type" class="post_type_page" value="post" />

<input type="hidden" id="_wpnonce" name="_wpnonce" value="264f468d66" /><input type="hidden" name="_wp_http_referer" value="/buddypress/wp-admin/edit.php" />	<div class="tablenav top">


		<br class="clear" />
	</div>
<table class="wp-list-table widefat fixed posts">
	<thead>
	<tr>
		<th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox" /></th><th scope='col' id='title' class='manage-column column-title sortable desc'  style=""><span>City Name</span></span></th><th scope='col' id='categories' class='manage-column column-categories'  style="">Country</th><th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>Action</span></th></tr>
	</thead>

	<tfoot>
    <tr>
		<th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox" /></th><th scope='col' id='title' class='manage-column column-title sortable desc'  style=""><span>City Name</span></span></th><th scope='col' id='categories' class='manage-column column-categories'  style="">Country</th><th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>Action</span></th></tr>
	</tfoot>

	<tbody id="the-list">
    <?php 
	## For Pagination
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 2;
	$offset = ( $pagenum - 1 ) * $limit;
	## If Search
	if($_REQUEST['s'] !='')
	$search = ' WHERE city LIKE "%'.$_REQUEST['s'].'%" OR country LIKE "%'.$_REQUEST['s'].'%" ';
	else
	$search = '';
	$sql = $wpdb->get_results( "SELECT * FROM $tableName  $search ORDER BY city ASC LIMIT $offset, $limit" );
	$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM $tableName " );
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
	
	
	## Msg for Search Result
	if($search)
	echo '<tr ><td class="date column-date" colspan="4" style="color:#0C367D;font-weight:bold">Search Result of - ' . $_REQUEST['s']. '</td></tr>';
	
	
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
            
            <td class="tags column-tags"><?php echo $sql_result->city;?></td>	
            <td class="comments column-comments"><?php echo $sql_result->country;?></td>
            <td class="date column-date"><a href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=add-city&edit_id=<?php echo $sql_result->id;?>" class="xcadd-new-h2">Edit</a>&nbsp;
            <?php if($sql_result->status==1)
			{
			?>
            &nbsp;<a title="Undelete?" href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=city-master&avail_id=<?php echo $sql_result->id;?>" class="xxadd-new-h2">Unpublished</a><?php 
			} else { ?>&nbsp;<a title="Delete?" onclick="return confirm('Are You Sure To UNPUBLISH ?');" href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=hotel-master&del_id=<?php echo $sql_result->id;?>" class="xxadd-new-h2">Published</a><?php 
			} 
			?>
            <a title="Trash?" onclick="return confirm('Are You Sure To TRASH forever ?');" href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=city-master&trash_id=<?php echo $sql_result->id;?>" class="xcadd-new-h2">Trash</a>&nbsp;
            </td>	
            </tr>
            
      <?php } 
	  } else { ?>
      <tr id="post-1" class="post-1 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">
            <th scope="row" >
                <td colspan="4" style="float:left; alignment-adjust:middle">No City Found! </td>		
            </th>
				
      </tr>
			<?php  }  
			if ( $page_links ) 
			{
				echo '<tr id="post-1" class="post-1 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0"><td class="date column-date" colspan="4">' . $page_links . '</td></tr>';
			}
			?>      
                
	</tbody>
</table>
	

</form>
</div>
</body>
</html>
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