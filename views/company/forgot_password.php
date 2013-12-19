<div class="wrapper forgot-page" id="wrapper">
	<div class="header">
		<h1 class="logo"><a href="<?php echo SITEURL; ?>">Time Tracker</a></h1>
	</div>
	<div class="login-container">
		<div class="login-tab">Forgot Password</div>
		<div class="clear"></div>
		<div class="inner-wrapper-login">
		<?php
			if(isset($_GET['e_flag']))
			{
				echo '<div class="error_message" >';
				if($_GET['e_flag']	==	'1' || $_GET['e_flag']	==	'2' || $_GET['e_flag']	==	'3')
				{
					echo Kohana::message('error', 'fgtpass_auth_fail');//'Invalid email or company.';
				}
				else if($_GET['e_flag']	==	'4')
				{
					echo 'Error occured, please try again.';
				}
				/* else if($_GET['e_flag']	==	'5')
				{
					echo 'Please enter the required field.';
				} */
				echo '<br/></div><br/>';
			}
			if(isset($_GET['msg']) && $_GET['msg'] == 1)
			{
				echo '<div style="padding-top:2px;">&nbsp;</div><div class="success_message">Password has been succesfully mailed to your email address.</div>';
			}
		?>
			<form method='post' action='/company/forgot_save1' id="myform">
				<p class="">
					<span class="enter-email">Enter Email:</span>
						<input type='text' name='email' class="inp" /><br />
				</p>
				<p class="forgot-content">
					<input type="button" value="Cancel" class="cancel-button" onclick="location.href='<?php echo SITEURL; ?>/login'"/>
					<input type="submit" value="Submit" class="submit-button"/>
				</p>
			</form>
		</div>
	</div>
</div>