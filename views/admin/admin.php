<!-- 
 * @File : admin.php
 * @Author: 
 * @Created: 15-11-2012
 * @Modified:  
 * @Description: Admin view for AccountEdge Cloud
   Copyright (c) 2012 Acclivity Group LLC 
-->
<div class="wrapper" id="wrapper">
	<div class="header">
		<h1 class="logo"><a href="#">Time Tracker</a></h1>
	</div>
	<div class="login-container">
		<ul class="navigation">
			<li class="selected"><span class="dashboard">&nbsp;&nbsp;Log In to Admin Web&nbsp;&nbsp;</span></li>
		</ul>
		
		<div class="inner-wrapper-login">
			<?php
				//if(!empty($auth_error)) echo "<div class='error_message'>".$auth_error."</div>";
				if(isset($_GET['e_flag']))
				{
					if($_GET['e_flag'] == '1')
					{
						echo "<div class='error_message'>Invalid Company Name.</div><div style='clear:both;'>&nbsp;</div>";
					}
					else if($_GET['e_flag'] == '2')
					{
						echo "<div class='error_message'>Invalid User Email or Password.</div><div style='clear:both;'>&nbsp;</div>";
					}
					else if($_GET['e_flag'] == '3')
					{
						echo "<div class='error_message'>Unable to read data from dropbox.</div><div style='clear:both;'>&nbsp;</div>";
					}
					else if($_GET['e_flag'] == '4')
					{
						echo "<div class='error_message'>Unable to process request, please try once again.</div><div style='clear:both;'>&nbsp;</div>";
					}
				}
			?>
			<form action="" method="post" id="myform">	
				<?php
						//if(!empty($auth_error)) echo "<div class='error_message'>".$auth_error."</div>";
				?>
				<p>
					<input type="text" value="username" name="username"  class="inp" id="inp_username">
					<input type="password" value="password" name="password" class="inp" id="inp_password">
				</p>
				<br />	
				<p>
					<input type="submit"  name="submit" value="Login" class="login-button">
					<input type="hidden" name="company" value="<?php echo $company;?>" />
				</p>
			</form>
		</div>
	</div>
</div>
