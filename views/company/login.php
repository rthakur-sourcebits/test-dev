<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.5,initial-scale=1.0" />
<script type="text/javascript">
$(document).ready(function()
{
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.hd');   
	for(var i=0; i < images.length; i++)
	{
	images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/logo.png', '/media/images/tt-new/aec-logo.png'))
	$('.hd').css('width','42px');
	$('.hd').css('height','29px');
	}
	}
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.hd1');   
	for(var i=0; i < images.length; i++)
	{
	images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/logo2.png', '/media/images/tt-new/lock.png'))
	$('.hd1').css('width','14px');
	$('.hd1').css('height','19px');
	}
	}
}); 
</script>
<?php 
//echo html::style('media/css/time_tracker_user.css');
?>
<div class="login-container-fluid">
  	<div class="row-fluid">
	  	<div id="container" class="container">
		<div id="background1" class="background1">
			<label class="aec-logo-label"><img src="<?php echo SITEURL;?>/media/images/tt-new/logo.png"  class="" id="account-edge-logo-login" /></label>
		</div>
		<form action="<?php echo SITEURL;?>/login" method="post" id="myform" >
			<label class="login-label-user"><img src="<?php echo SITEURL;?>/media/images/tt-new/logo2.png"  alt="logo2" class="login-logo hd1">Login</label>
			<?php
				$style= "top:42px !important;";
				if(!empty($auth_error)) echo "<div class='error_message'>".$auth_error."</div>";
				if(isset($_GET['f'])) {
					echo "<div class='success_message' style='padding-top:5px;'>Password has been succesfully mailed to your email address.</div><div style='clear:both;height:0;'>&nbsp;</div>";
					$style= "top:26px !important;";
				}
			?>
			<div class="clear"></div>
				<div id="row" class="row">
					<div id="row1" class="row1">
						<div class="controls controls-row">
							<div id="leftimage" class="leftimage">
								<div id="rightimage" class="rightimage">
									<div id="login-frame">
										 <div class="login-email <?php if(isset($error)) { echo "error-label"; } ?> first-row">
										 <?php if(isset($error)) { 	?>
										 			
										 <?php } else {?>
										 			
										 <?php }?>
										 	<input class="span8" type="text" placeholder="Email" id="inp_username" name="username" autocomplete="on">
										 </div>  
										 <div class="login-middle-border  <?php if(isset($error)) { echo "no-height"; } ?>">&nbsp;</div>
										 <div class="login-pass <?php if(isset($error)) { echo "error-label"; } ?>">
										  <?php if(isset($error)) { 	?>
										 			
										 <?php } else {?>
										 			
										 <?php }?>
										 <input class="span8" type="password" name="password" placeholder="Password" id="inp_password"></div>
										 <?php if(isset($error)) {?>
										 		<div class="login-middle-border">&nbsp;</div>
												<div class="row reset-pass-error error-background last-row">
													<div class="error-desc">
														<img src="/media/images/tt-new/reset_error.png" />
															<?php if($error == '1') {
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
																  }
															 ?>
													</div>
												</div>
										<?php }?>
									</div>
								</div>
							</div>
							
						</div>
						
	        		</div>
	        	<div id="controls" class="controls">
					<button class="btn btn-large btn-block login-label" id="field2" type="submit" name="submit">Log In</button>
				</div>
				<div id="logged-in">
						<button class="btn-mini remember-user-disable" data-toggle="buttons-checkbox" type="button" id="checkbox-enable"  onclick="rememebr_login(this);"> </button>
						<span>Keep me logged in for 2 weeks</span>
						<input type="hidden" id='login_checkbox_remember_me' name="remember" class="styled" value="0" />
					</div>
				</div>
		</form>
		<div id="login-lost-pass" class="background2" style="margin:0px;">
			<br/>
			<p>
				<a href="javascript:void(0);" id="forgot_pass" onclick="open_forgot_password_window();">Lost Password</a>
			</p>
		</div>
		<div id="login-singup" class="background3">
			<div id="controls" class="controls">
				<button class="btn btn-large btn-block" id="field3" type="button" onclick="location.href='<?php echo SITEURL.'/admin/signup'?>'">Sign Up</button>
				<div id="signup-text">
					<p>
						Create an account for your company, then add as many users as you need whenever you want.
					</p>
				</div>
			</div>
		</div>
	   </div>	
   </div>
</div>
