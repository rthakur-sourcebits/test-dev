<div id="container" class="container">
	<div class="image_logo_aec"  id="image_logo_aec">
		<img class="login-logo" id="login-logo" src="<?php echo SITEURL;?>/media/images/login/logo_n_text.png" class="" />
	</div>
	<div class="content_inner_login">
		<div class="login-signup">
					
			<label class="login-text1">
			<?php if(isset($result)&&$result){ ?>
					<div>
						<img class="login-logo" id="login-logo" src="<?php echo SITEURL;?>/media/images/icon-registersucess.png" class="" />
					</div>
					Your Password is been successfully reseted.<br />
					Please login with your new password. <a href="<?php echo SITEURL; ?>" >Login</a><br /> 
			<?php } else {?>
					Your Password is not been rested.<br />
					Please try again. <a href="<?php echo SITEURL; ?>" >Login</a><br />
			<?php }?>
			</label>
		</div>
	</div>
	<div class="clear"></div>
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

