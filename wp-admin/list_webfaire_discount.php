	<?php
	global $wpdb;
    $tableName = 'airlines';
	
	if($_REQUEST['del_id']!="")
	{
	$sql_delete="UPDATE  $tableName SET `TRASH`='1'  WHERE id='".$_REQUEST['del_id']."'";

$res=$wpdb->query($sql_delete);	
  if($res>0)
    {
	?>
    <script>
function myFunction() 
{
    location.reload();
}
</script>
    <?php
	}
	}
	
	if($_REQUEST['avail_id']!="")
	{
	$sql_avail="UPDATE  $tableName SET `STATUS`='0'  WHERE id='".$_REQUEST['avail_id']."'";

$res_avail=$wpdb->query($sql_avail);	
  if($res_avail>0)
    {
	?>
    <script>
function myFunction() 
{
    location.reload();
}
</script>
    <?php
	}
	}
	else
	{
	$sql_avail="UPDATE  $tableName SET `STATUS`='1'  WHERE id='".$_REQUEST['notavail_id']."'";

$res_avail=$wpdb->query($sql_avail);	
  if($res_avail>0)
    {
	?>
    <script>
function myFunction() 
{
    location.reload();
}
</script>
    <?php
	}	
	}
	
	?>
    
    <div class="wrap">
<h1>Webfaire Discount Manager - Listing</h1>
<h2><a href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=add-webfaire-discount" class="add-new-h2">Add New</a></h2>
<h2 style="float:right;"><a href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=trash-master" class="add-new-h2">Total Airlines(<?php $count_trash=$wpdb->get_results("SELECT * FROM $tableName "); echo count($count_trash);?>)</a></h2>
<form id="posts-filter" action="" method="get">

<input type="hidden" name="post_status" class="post_status_page" value="all" />
<input type="hidden" name="post_type" class="post_type_page" value="post" />

<input type="hidden" id="_wpnonce" name="_wpnonce" value="264f468d66" /><input type="hidden" name="_wp_http_referer" value="/buddypress/wp-admin/edit.php" />	<div class="tablenav top">


		<br class="clear" />
	</div>
<table class="wp-list-table widefat fixed posts">
	<thead>
	<tr>
		<th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox" /></th><th scope='col' id='title' class='manage-column column-title sortable desc'  style=""><span>Airline</span></span></th><th scope='col' id='categories' class='manage-column column-categories'  style="">IATA</th><th scope='col' id='tags' class='manage-column column-tags'  style="">Short Name</th><th scope='col' id='tags' class='manage-column column-tags'  style="">Discount(%)</th><th scope='col' id='comments' class='manage-column column-comments num sortable desc'  style="">Vendor Type</th><th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>Action</span></th></tr>
	</thead>

	<tfoot>
<tr>
		<th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox" /></th><th scope='col' id='title' class='manage-column column-title sortable desc'  style=""><span>Airline</span></span></th><th scope='col' id='categories' class='manage-column column-categories'  style="">IATA</th><th scope='col' id='tags' class='manage-column column-tags'  style="">Short Name</th><th scope='col' id='tags' class='manage-column column-tags'  style="">Discount(%)</th><th scope='col' id='comments' class='manage-column column-comments num sortable desc'  style="">Vendor Type</th><th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>Action</span></th></tr>
	</tfoot>

	<tbody id="the-list">
    <?php 
	## For Pagination
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 10;
	$offset = ( $pagenum - 1 ) * $limit;
	$entries = $wpdb->get_results( "SELECT * FROM $tableName LIMIT $offset, $limit" );
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
	
	//$sql=$wpdb->get_results("SELECT * FROM $tableName ");
if(count($entries)>0)
{
	foreach($entries as $sql_result)
    {
		
	?>
				<tr id="post-1" class="post-1 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">
				<th scope="row" class="check-column">
								
				<input id="cb-select-1" type="checkbox" name="post[]" value="1" />
				<div class="locked-indicator"></div>
							</th>
			<td class="post-title page-title column-title"><strong><?php echo $sql_result->airline;?></strong><img src="<?php  echo bloginfo('url');?>/airimages/<?php echo $sql_result->id.'.GIF';?>" class="icn" />
<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
</td>			
			<td class="categories column-categories"><?php echo $sql_result->iata;?></td><td class="tags column-tags"><?php echo $sql_result->short_name;?></td>			<td class="comments column-comments"><?php echo $sql_result->discount;?></td>
           
			<td class="date column-date"><?php echo $sql_result->vend_type;?></td>
            <td class="date column-date"><h2><a href="<?php echo bloginfo('url');?>/wp-admin/admin.php?page=edit-webfaire-discount&edit_id=<?php echo $sql_result->id;?>" class="add-new-h2">Edit</a>
<?php  
	}
}else{
	echo '<tr><td class="date column-date" colspan="7">Records not Found!</td></tr>';
}

if ( $page_links ) {
    echo '<tr><td class="date column-date" colspan="7">' . $page_links . '</td></tr>';
}
?>      
                
		</tbody >
</table>
	

</form>
</div>