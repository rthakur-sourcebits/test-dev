<?php echo HTML::style('media/scripts/jquery-ui/css/ui-lightness/jquery-ui-1.8.4.custom.css'); ?>
<?php echo HTML::script('media/scripts/jquery-ui/js/jquery-1.4.2.min.js');?>
<?php echo HTML::script('media/scripts/jquery-ui/js/jquery-ui-1.8.4.custom.min.js');?>	
<?php echo HTML::script('media/scripts/main.js');?>
<script type="text/javascript">
d = new Date();
$(function() {
	$("#dob_date").datepicker({
		showOn: 'button',
		buttonImage: '../media/images/calendar.gif',
		buttonImageOnly: true,
		dateFormat: 'mm-dd-yy',
		changeMonth: true,
		changeYear: true,
		yearRange: (d.getFullYear()-70)+":"+(d.getFullYear())
	});
	$("#termination_date").datepicker({
		showOn: 'button',
		buttonImage: '../media/images/calendar.gif',
		buttonImageOnly: true,
		dateFormat: 'mm-dd-yy',
		changeMonth: true,
		changeYear: true,
		yearRange: (d.getFullYear())+":"+(d.getFullYear()+50)
	});
	
});
</script>
<form action='../admin/create' method="post" id="register" class='text_normal'>
	<?php
	if(!empty($_GET['statusMessage'])) echo "<div class='error_message'>".$_GET['statusMessage']."</div>";
	?>
	<div align='center'>
		<fieldset >
			<legend>Account-Details</legend>
			<table width='100%' align='center' class='text_normal' id="admin_register">
				<tr>
					<td class="right">Firstname:</td>
					<td class="left"><input type="text" name="FirstName" id="FirstName" maxlength='30' value="<?php echo $user['FirstName']; ?>" /></td>
				</tr>
				
				<tr>
					<td class="right">BirthDate:</td>
					<td class="left">
					<?php
						if( isset($_GET['flag']) AND  $_GET['flag']=='e' )
						{
							$birth_date = $user['BirthDate']['Month']."-".$user['BirthDate']['Day']."-".$user['BirthDate']['Year'];
						}
						else {
							$birth_date = "";
						}
					?>
						<input type="text" name="dob_date" value="<?php echo $birth_date;?>" id='dob_date' readonly="true" />
					</td>
				</tr>
				
				<tr>
					<td class="right">Residence Code:</td>
					<td class="left"><input type="text" name="ResidenceCode" id="ResidenceCode" maxlength='30' value="<?php echo $user['ResidenceCode']; ?>" /></td>
				</tr>
				
				<tr>
					<td class="right">Salary Rate:</td>
					<td class="left"><input type="text" name="SalaryRate" id="SalaryRate" maxlength='30' value="<?php echo $user['SalaryRate']; ?>" /></td>
				</tr>
				
				<tr>
					<td class="right">Social Security Number:</td>
					<td class="left"><input type="text" name="SocialSecurityNumber" id="SocialSecurityNumber" maxlength='30' value="<?php echo $user['SocialSecurityNumber']; ?>" /></td>
				</tr>
				
				<tr>
					<td class="right">State Allowances:</td>
					<td class="left"><input type="text" name="StateAllowances" id="StateAllowances" maxlength='30' value="<?php echo $user['StateAllowances']; ?>" /></td>
				</tr>
				
				<tr>
					<td class="right">Termination Date:</td>
					<td class="left">
					<?php
						if( isset($_GET['flag']) AND  $_GET['flag']=='e' )
						{
							$term_date = $user['TerminationDate']['Month']."-".$user['TerminationDate']['Day']."-".$user['TerminationDate']['Year'];
						}
						else {
							$term_date = "";
						}
					?>
						<input type="text" name="termination_date" value="<?php echo $term_date;?>" id='termination_date' readonly="true" />
					</td>
				</tr>
				<?php
				if( isset($_GET['flag']) AND  $_GET['flag']=='e' )
				{
				?>
					<tr>
						<td class="right">Active/Inactive:</td>
						<td class="left">
							<input type="radio" name="status" value="" id="activate" 
							<?php
								if( $user['IsCardInactive'] == "" || $user['IsCardInactive'] == 0 ) echo "checked='true'";
							?>/>Activate<br/>
							<input type="radio" name="status" value="1" id="activate"
							<?php
								if( $user['IsCardInactive'] == 1 ) echo "checked='true'";
							?> />Inactivate
						</td>
					</tr>
				<?php
				}	
				?>
			</table>					
		</fieldset>
	</div>
	<div align='center' style='padding-top:10px'>
		<input type='button' value='submit'  class='button_class' name='save' id='save'
		<?php if(isset($_GET['flag']) AND  $_GET['flag']=='e'){
			 	echo "onclick=validateRegistration('e')"; 
		 	}else{
		 		echo "onclick=validateRegistration()";
		 	} 	
		?>
		/>
		<input type="button" name="cancel"  class='button_class' value="Cancel" onclick="location.href='/admin/users'" />
	</div>
	<input type="hidden" name="Company" value="<?php echo $_SESSION['admin_company_id']; ?>" />
	<?php
	if(isset($_GET['flag']) AND  $_GET['flag']=='e')
	{
	?>
		<input type='hidden' name='employee_id' value='<?php echo $user['RecordID']; ?>'>
	<?php
	}
	?>
</form>