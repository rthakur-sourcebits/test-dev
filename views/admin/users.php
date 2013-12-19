<?php echo HTML::style('media/scripts/jquery-ui/css/ui-lightness/jquery-ui-1.8.4.custom.css'); ?>
<?php echo HTML::script('media/scripts/jquery-ui/js/jquery-1.4.2.min.js');?>
<?php echo HTML::script('media/scripts/jquery-ui/js/jquery-ui-1.8.4.custom.min.js');?>	
<?php echo HTML::script('media/scripts/main.js');?>
<?php echo HTML::script('media/jquery.tablesorter/tablesorter/jquery.tablesorter.js');?>
<?php echo HTML::script('media/jquery.tablesorter/tablesorter/jquery.tablesorter.pager.js');?>
<?php echo HTML::style('media/jquery.tablesorter/tablesorter/themes/blue/style.css'); ?>
<?php
	// [employee_id] => 2 [employee_name] => Ram
	//echo "<pre>";print_r($users);echo "</pre>";die;
	if(isset($_GET['statusMessage']))
	{
		if($_GET['statusMessage'] ==1)
		{
 			echo '<span class="success_message" >Successfully saved</span>';
		}
		else if($_GET['statusMessage'] ==2)
		{
 			echo '<span class="success_message" >Successfully updated</span>';
		}
		else if($_GET['statusMessage'] ==3){
			echo '<span class="error_message" >Failed to update dropbox file.</span>';
		}
	}	

?>
<script type="text/javascript">
	$(document).ready(function() { 
			$(".tablesorter").tablesorter(); 
		}	 
	);
	$(document).ready(function() { 
		$("table.tablesorter").tablesorterPager({container: $("#pager")});
	})
</script>
<div class="controller_header">User List</div>
<div><a href="<?php echo SITEURL?>/admin/users/1">Employee</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo SITEURL?>/admin/users/2">Contract</a></div><br/>
<table class="tablesorter">
	<thead>
	  <tr>
		<th>First Name</th>
		<th>Last Name</th>
		<td>Action</td>
	 </tr>	
	</thead>
	<tbody>
		<?php
		if(!empty($users)) 
		{
		    foreach($users as $user) 
			{
		?>
				<tr>
					<td><?php echo stripslashes($user['FirstName']);?></td>
					<td><?php echo stripslashes($user['CompanyOrLastName']);?></td>
					<td><input type="image" src="<?php echo SITEURL;?>/media/images/button.png" onclick="location.href='<?php echo SITEURL; ?>/admin/edit/<?php echo $user['RecordID'];?>'" />
					<input type="image" src="<?php echo SITEURL;?>/media/images/grey-view.png" onclick="location.href='<?php echo SITEURL; ?>/admin/view/<?php echo $user['RecordID'];?>'" /></td>
				</tr>
		<?php
			}
		}
		else {
			echo "<tr><td colspan='3' align='center'>No users found.</td></tr>";
		}
		?>
	</tbody>
	<tfoot>
		<tr><td id="pager" class="pager" colspan="3">
			<img src="<?php echo SITEURL ?>/media/jquery.tablesorter/tablesorter/addons/pager/icons/first.png" class="first" />
			<img src="<?php echo SITEURL ?>/media/jquery.tablesorter/tablesorter/addons/pager/icons/prev.png" class="prev" />
			<input class="pagedisplay" type="text" readonly="true"/>
			<img src="<?php echo SITEURL ?>/media/jquery.tablesorter/tablesorter/addons/pager/icons/next.png" class="next" />
			<img src="<?php echo SITEURL ?>/media/jquery.tablesorter/tablesorter/addons/pager/icons/last.png" class="last" />
			<select class="pagesize">
				<option selected="selected" value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
			</select>
		</td>
		</tr>
	</tfoot>
</table>
<div class="clearboth">&nbsp;</div>