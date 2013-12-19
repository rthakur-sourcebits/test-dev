<!-- 
 * @File : edit_company.php
 * @view : view for edit company
 * @Author: 
 * @Created: 03-11-2012
 * @copyright:Acclivity LLC
 * @Modified:  
 * @Description: View file for company edit
-->
<form action='<?php echo SITEURL?>/admin/company_add' method="post" id="company_create" class='text_normal' name="admin_form">
	<?php
	if(isset($error))
	{
		echo "<div class='error_message'>".$error."</div><br/>";
	}
	if(!empty($message) && $message == 2)
	{
		echo "<div class='success_message'>New company has beed updated.</div><br/>";
	}
	if(!$company_info['active_status'])
	{
		echo "<div class='success_message' style='color:#405259;'>Please activate this company by clicking on the link sent by AccoundEdge team.</div><br/>";
	}
	?>
	<div class="table-wrapper">
	<table>
		<tr>
			<td class="td-first">Company Name</td>
			<td><input type="text" class="input-1 inp-width" name="company_name" id="company_name" maxlength='50' value="<?php echo $company_info['name'] ?>" autocomplete="off"/></td>
		</tr>
		<tr>
			<td class="td-first">Serial Number</td>
			<td><input type="text" class="input-1 inp-width" name="serialnumber" id="serialnumber" maxlength='50' value="<?php echo $company_info['serialnumber'] ?>" autocomplete="off"/></td>
		</tr>
		<tr>
			<td class="td-first">Email Address</td>
			<td><input type="text" class="input-1 inp-width" name="UserEmail" id="UserEmail" maxlength='50' value="<?php echo $company_info['email'] ?>" autocomplete="off"/></td>
		</tr>
		<tr>
			<td class="td-first">Password</td>
			<td><input type="password" class="input-1 inp-width" name="Password" id="Password" maxlength='15' value="" /></td>
		</tr>
		<?php 
		if(isset($edit) && $company_info['active_status'])
		{
			?>
			<tr>
				<td class="td-first">AccountEdge Device Name</td>
				<td style="font-weight:bold;"><?php echo $company_info['device_name'] ?><!-- <input type="text" class="input-1 inp-width" name="device_name" id="device_name" maxlength='50' value="" autocomplete="off"/>--></td>
			</tr>
			<tr>
				<td class="td-first">Dropbox Email Address</td>
				<td style="font-weight:bold;"><?php echo $company_info['dropbox_email'] ?><!-- <input type="text" class="input-1 inp-width" name="DropboxEmail" id="dropbox_email" maxlength='50' value="" autocomplete="off"/>--></td>
			</tr>
			<!-- <tr>
				<td class="td-first" style="border-bottom: none;">DropBox Password</td>
				<td style="border-bottom: none;"><input type="password" class="input-1 inp-width" name="DropboxPassword" id="dropbox_password" maxlength='15' value="" /></td>
			</tr>
			-->
			<input type="hidden" name="activate_id" value="<?php echo $company_info['activation_id'] ?>" />
			<?php 
		}
		?>
		<!--<tr>
			<td class="td-first">Consumer Key</td>
			<td><input type="text" class="input-1" name="consumer_key" id="consumer_key" maxlength='15' value="<?php //echo $company_info['consumer_key'] ?>" autocomplete="off" /></td>
		</tr>
		<tr>
			<td class="td-first">Consumer Secret</td>
			<td><input type="text" class="input-1" name="consumer_secret" id="consumer_secret" maxlength='15' value="<?php //echo $company_info['consumer_secret'] ?>" autocomplete="off" /></td>
		</tr>-->
	</table>
	</div>
	<div class="btn-block">
		<a class="admin-button" href="javascript:void(0);" class='button_class' name='save' id='save' onclick="return submit_admin_form();">Save</a>
		<a class="admin-button" href="#" name="delete"  class='button_class' onclick="delete_alert('delete_company');">Delete</a>
		<!-- <a class="admin-button" href="<?php echo SITEURL?>/admin/home" name="cancel"  class='button_class'>Cancel</a>-->
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<input type="hidden" name="company_id" value="<?php echo $company_info['id'] ?>" />
</form>