<?php echo HTML::script('media/scripts/main.js');?>
<h2>Registration</h2>  

<form action='../admin/create' method="post" id="register" class='text_normal'>
		<?php
			if(!empty($_GET['statusMessage'])) echo "<div class='error_message'>".$_GET['statusMessage']."</div>";
		?>
		<div align='center'>
		<fieldset id="login" >
			<legend>Accounr-Details</legend>
		
		    <table width='100%' align='center' class='text_normal'>
		    <tr>
		    	<td width='50%' align='right' >
					<label>Email*</label>
				</td>
				<td width='50%' >
					<input type="text" name="email" id="email" maxlength='40' value="" />
				</td>
			</tr>
		    <tr>
		    	<td width='50%' align='right'>
					<label>Password*</label>
				</td>
				<td width='50%' >
					<input type="password" name="password" id="password" maxlength='15' value="" />
				</td>
			</tr>
			<tr>
		    	<td width='50%' align='right'>
					<label>Retype-Password*</label>
				</td>
				<td width='50%'>
					<input type="password" name="re_password" id="re_password"  maxlength='15' value="" />
				</td>
			</tr>
			</table>			
					
		</fieldset>
		</div>
		<div align='center'>
		<fieldset id="login">
			<legend>Personal-Details</legend>
			<table width='100%' align='center' class='text_normal' >
		    <tr>
		    	<td width='50%' align='right'>
					<label>First Name*</label>
				</td>
				<td width='50%' >
					<input type="text" name="first_name" id="first_name" maxlength='40' value="" />
				</td>
			</tr>
		    <tr>
		    	<td align='right'>
					<label>Last Name*</label>
				</td>
				<td>
					<input type="text" name="last_name" id="last_name" maxlength='40' value="" />
				</td>
			</tr>
			<tr>
		    	<td align='right'>
		    		<label>Male</label>
		    		<input type="radio" name="gender" id="male" value="male"  checked />
				
				</td>
				<td align='left' >
					<label>Female</label>
					<input type="radio" name="gender" id="female"  value="female"  />
				</td>
			</tr>
			</table>
		</fieldset>
		</div>
		<div align='center' style='padding-top:10px' >
				<input type='button' value='submit'  class='button_class' name='save' id='save' onclick='validateRegistration()' />
		</div>
		
	</form>
