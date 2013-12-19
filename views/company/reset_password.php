<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />

<script type="text/javascript">
$(document).ready(function()
{
	if($(window).width() < 400) 
	{
		$(".container").css("width","100%");
		$("#signup-text").css("width","87%");
		$("#signup-text").css("margin-left","7%");
		$('#login-frame').css("width","90%");
		$('#login-frame').css("margin-left","4.2%");
		$('#logged-in').css("margin-left","-6%");
		$('.login-pass').css("padding-right","0px");
		$('.login-email').css("padding-right","0px");
		$('.login-container-fluid').css("padding-top","0");
	}
	else if(($(window).width() > 400)  && ($(window).width() < 490)) 
	{
		$('.login-container-fluid').css("padding-top","0");
		$(".container").css("width","360px");
		$("#signup-text").css("width","315px");
		$("#signup-text").css("margin-left","6.3%");
		$('#login-frame').css("width","83%");
		$('#login-frame').css("margin-left","8%");
		$('#logged-in').css("margin-left","0px");
		$('.login-pass').css("padding-right","33px");
		$('.login-email').css("padding-right","33px");
	
	}
	else if(($(window).width() > 780) && $(window).width() < 1218) 
	{
		$('.login-container-fluid').css("padding-top","6%")
		$(".container").css("width","360px");
		$("#signup-text").css("width","315px");
		$("#signup-text").css("margin-left","6.3%");
		$('#login-frame').css("width","83%");
		$('#login-frame').css("margin-left","8%");
		$('#logged-in').css("margin-left","0px");
		$('.login-pass').css("padding-right","33px");
		$('.login-email').css("padding-right","33px");
	
	}
	else
	{
		$(".container").css("width","360px");
		$("#signup-text").css("width","315px");
		$("#signup-text").css("margin-left","6.3%");
		$('#login-frame').css("width","83%");
		$('#login-frame').css("margin-left","8%");
		$('#logged-in').css("margin-left","0px");
		$('.login-pass').css("padding-right","33px");
		$('.login-email').css("padding-right","33px");
		$('.login-container-fluid').css("padding-top","6%");
	}

	$(window).resize(function(){
		if($(window).width() < 400) {
			$(".container").css("width","100%");
			$("#signup-text").css("width","87%");
			$("#signup-text").css("margin-left","7%");
			$('#login-frame').css("width","90%");
			$('#login-frame').css("margin-left","4.2%");
			$('#logged-in').css("margin-left","-6%");
			$('.login-pass').css("padding-right","0px");
			$('.login-email').css("padding-right","0px");
		}
		else if(($(window).width() > 400)  && ($(window).width() < 490)) {
			$('.login-container-fluid').css("padding-top","0");
			$(".container").css("width","360px");
			$("#signup-text").css("width","315px");
			$("#signup-text").css("margin-left","6.3%");
			$('#login-frame').css("width","83%");
			$('#login-frame').css("margin-left","8%");
			$('#logged-in').css("margin-left","0px");
			$('.login-pass').css("padding-right","33px");
			$('.login-email').css("padding-right","33px");
		
		}
		else if(($(window).width() > 780) && $(window).width() < 1218) {
			$('.login-container-fluid').css("padding-top","6%")
			$(".container").css("width","360px");
			$("#signup-text").css("width","315px");
			$("#signup-text").css("margin-left","6.3%");
			$('#login-frame').css("width","83%");
			$('#login-frame').css("margin-left","8%");
			$('#logged-in').css("margin-left","0px");
			$('.login-pass').css("padding-right","33px");
			$('.login-email').css("padding-right","33px");
		
		}
		else {
			$(".container").css("width","360px");
			$("#signup-text").css("width","315px");
			$("#signup-text").css("margin-left","6.3%");
			$('#login-frame').css("width","83%");
			$('#login-frame').css("margin-left","8%");
			$('#logged-in').css("margin-left","0px");
			$('.login-pass').css("padding-right","33px");
			$('.login-email').css("padding-right","33px");
			$('.login-container-fluid').css("padding-top","6%")
		}
	});
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.hd');   
	for(var i=0; i < images.length; i++)
	{
	images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/logo.png', '/media/images/tt-new/aec-logo.png'))
	$('.hd').css('width','42px')
	$('.hd').css('height','29px')
	}
	}
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.hd1');   
	for(var i=0; i < images.length; i++)
	{
	images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/logo2.png', '/media/images/tt-new/lock.png'))
	$('.hd1').css('width','14px')
	$('.hd1').css('height','19px')
	}
	}
}); 
</script>
<div class="login-container-fluid">
  	<div class="row-fluid">
	  	<div id="container" class="container">
			<div id="background1" class="background1">
				
				<img src="<?php echo SITEURL;?>/media/images/tt-new/logo.png"  class="hd" id="account-edge-logo-login">
				<label class="aec-logo-label">AccountEdge<span class="aec-label-cloud"> Cloud</span></label>
			</div>
			<form action="<?php echo SITEURL;?>/login" method="post" id="myform" >
	<!-- changes to be made-->				
				 <img src="<?php echo SITEURL;?>/media/images/tt-new/logo2.png"  alt="logo2" class="login-logo hd1">
				<label class="login-label-user">Login</label>
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
										 			<div class="login-left-label">Email</div>
										 <?php } else {?>
										 			<span>Email</span>
										 <?php }?>
										 	<input class="span8" type="text" id="inp_username" name="username" autocomplete="off">
										 </div>  
										 <div class="login-middle-border  <?php if(isset($error)) { echo "no-height"; } ?>">&nbsp;</div>
										 <div class="login-pass <?php if(isset($error)) { echo "error-label"; } ?>">
										  <?php if(isset($error)) { 	?>
										 			<div class="login-left-label">Password</div>
										 <?php } else {?>
										 			<span>Password</span>
										 <?php }?>
										 <input class="span8" type="password" name="password" id="inp_password"></div>
										 <?php if(isset($error)) {?>
										 		<div class="login-middle-border">&nbsp;</div>
												<div class="row reset-pass-error error-background last-row">
													<div class="error-desc">
														<img src="/media/images/tt-new/reset_error.png" />Incorrect password. Please try again.
													</div>
												</div>
										<?php }?>
									</div>
								</div>
							</div>
							
						</div>
						
	        		</div>
	        		<div id="logged-in">
						<button class="btn-mini remember-user-disable" data-toggle="buttons-checkbox" type="button" id="checkbox-enable"  onclick="rememebr_login(this);"> </button>
						<span>Keep me logged in for 2 weeks</span>
						<input type="hidden" id='login_checkbox_remember_me' name="remember" class="styled" value="0" />
					</div>
				</div>
				<div id="controls" class="controls">
					<button class="btn btn-large btn-block login-label" id="field2" type="submit" name="submit">Log In</button>
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
