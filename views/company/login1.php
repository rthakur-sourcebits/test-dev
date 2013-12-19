	<div id="container" class="container">
		<div class="image_logo_aec"  id="image_logo_aec">
			<img class="login-logo" id="login-logo" onclick="window.location = '<?php echo SITEURL;?>'" src="<?php echo SITEURL;?>/media/images/login/logo_n_text.png" class="" />
		</div>
		<div class="clear"></div>
		<div class="content_inner_login">
			<!-- Logout Message -->
			<form action="<?php echo SITEURL;?>/login" method="post" id="aecLoginForm" >
				<div class="login_email" >
					<input type="text" value="" placeholder="Email" class="login-input" id="inp_username" name="username" autocomplete="on" />				
				</div>
				<div class="clear"></div>
				<div class="login_email1 mar-top-20">
					<div class="login-password" >
						<input type="password" value="" placeholder="Password" class="login-input" id="inp_password" name="password"/>
					</div>		
					<div class="login-shadow">
						<button class="login-button" type="submit" name="submit" >
							<span class="login-button-value">Log In</span>
						</button>
					</div>
				</div>
				<div class="clear"></div>
				<div class="login-remember">
					<div class="remember-me">
						<input type="hidden" id='login_checkbox_remember_me' name="remember"  value="0" />
						<button class="login-img remember-user-disable" onclick="rememebr_login(this);" id="checkbox-enable" type="button" data-toggle="buttons-checkbox"></button><label class="login-text left" >Remember Me </label>		
						<img src=<?php echo SITEURL.'/media/images/login/remember-chk.png'?> style="display:none !important;" />
					</div>
					<div class="forgot-passwd" >
						<label class="login-text right" id="forgot_pass" onclick="open_forgot_password_window();">Forgot your Password?</label>
					</div>
				</div>
				<div class="clear"></div>
				
				<?php if(isset($_GET['e_flag']) && $_GET['e_flag'] == 22) {
					$margin = "margin-top:150px";
				?>
				<div class="invalid_msg" ><img src="/media/images/tt-new/reset_error.png" />
					Someone has logged in as this user from a different computer or browser window. Only one person may log in at a time.
					<br/><br/>
					Please Login Again.
				</div>
				<div class="clear"></div>
				<?php } else {
					$margin = "margin-top:80px";
				}?>
				<?php 
				if(isset($error)) {
				$style = 'display:block';
				}else{
				$style = 'display:none';
				}
				?>
				<div class="invalid_msg" style="<?php echo $style;?>"><img src="/media/images/tt-new/reset_error.png" />
				<?php
				if(isset($error)) {
					if($error == '1') {
						echo Kohana::message('error', 'invalid_company');
					} else if($error == '2'){
						echo Kohana::message('error', 'auth_fail');
				 	} else if($error == '3'){
						echo Kohana::message('error', 'sync_error');
				  	} else if($error == '4'){
						echo "Unable to login dropbox. Please check the dropbox credentials and try again.";
				  	} else if($error == '6'){
						echo Kohana::message('error', 'dropbox_sync_error');
				  	} else if($error == '7'){
						echo Kohana::message('error', 'user_account_inactive');
				  	} else if($error == '8'){
						echo Kohana::message('error', 'user_account_expired');
				  	} else if($error == '9'){
						echo Kohana::message('error', 'suspend_error');
				  	} else if($error == '10'){
				  		echo Kohana::message('error', 'pwd_expired');
				  	} else if($error == '11'){
						echo Kohana::message('error', 'invalid_usr');
				  	} else if($error == '12'){
						echo Kohana::message('error', 'pwd_reset_successfully');
				  	}
					
				}
				?>
			 	</div>
			</form>
		</div>
		<div class="clear"></div>
		<div class="login-signup" style="<?php if(isset($margin)) echo $margin; else echo "margin-top:80px";?>">
			<label class="login-text1">
			Create an account for your company, then 
			add as many users as you need whenever you 
			want.
			</label>
			<div class="sign-up-div" >
				<button class="signup-button large" onclick="location.href='<?php echo SITEURL.'/admin/signup'?>'">
					<span class="signup-button-value">Sign Up</span>					
				</button>
			</div>
		</div>
	</div>	

<!-- Giving white background to only login page  -->
<style>
#outer-div{
background:none;
padding-top:52px;
}
body{
background:none;
}
</style>