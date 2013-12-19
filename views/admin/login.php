<script type="text/javascript">

$(document).ready(function(){
	if($(window).width() < 400) {
		$(".container").css("margin-left","-20px");
	}
	$(window).resize(function(){
		if($(window).width() < 400) {
		$(".container").css("margin-left","-20px");
	}
	});
});
</script>

<div class="login-container-fluid">
  <div class="row-fluid">
    <div id="container" class="container">
		<div id="back" class="back">
			<div id="background1" class="background1" align="center">
				<div id="logo1" class="logo1"></div>
				<img src="<?php echo SITEURL?>/media/images/tt-new/new_logo.png" alt="logo1">
				<div id="bg" class="bg"></div>
			</div>
			
			<form action="<?php echo SITEURL?>/admin/login" method="post" id="myform">
				<div id="logo" class="logo" align="center">
					<img src="<?php echo SITEURL?>/media/images/tt-new/logo2.jpg" width="147" height="24" alt="logo2">
				</div>
				<?php
				//echo "<div class='error_message login_error'>Invalid Email or Password.</div><div style='clear:both;height:0'>&nbsp;</div>";
				if(isset($error_flag) && $error_flag != 0)
				{
					if($error_flag == '1')
					{
						echo "<div class='error_message login_error'>Invalid Email or Password.</div><div style='clear:both;height:0'>&nbsp;</div>";
					}
					else if($error_flag == '2')
					{
						echo "<div class='error_message login_error'>Invalid Email or Password.</div><div style='clear:both;height:0;'>&nbsp;</div>";
					}
					else if($error_flag == '3')
					{
						echo "<div class='error_message login_error'>Please sync your account from AccountEdge Desktop.</div><div style='clear:both;height:0;'>&nbsp;</div>";
					}
					else if($error_flag == '4')
					{
						echo "<div class='error_message login_error'>Unable to login dropbox. Please check the credentials and try again.</div><div style='clear:both;height:0;'>&nbsp;</div>";
					}
					else if($error_flag == '7')
					{
						echo "<div class='error_message login_error'>Please sync your account from AccountEdge Desktop.</div><div style='clear:both;height:0;'>&nbsp;</div>";
					}
				}
				if(isset($_GET['f'])) {
					echo "<div class='success_message' style='padding-top:5px;'>Password has been succesfully mailed to your email address.</div><div style='clear:both;height:0;'>&nbsp;</div>";
					$style= "top:26px !important;";
				}
				?>
				<div id="row" class="row">
					<div id="row1" class="row1">
						<div class="controls controls-row">
							<div id="leftimage" class="leftimage">
								<div id="rightimage" class="rightimage">
									<div id="login-frame">
										 <div class="login-email"><span>Email</span><input class="span8" type="text" id="inp_username" name="username" autocomplete="off"></div>  
										 <div class="login-middle-border">&nbsp;</div>
										
									  	 <div class="login-pass"><span >Password</span><input class="span8" type="password" name="password" id="inp_password"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="controls" class="controls">
					<button class="btn btn-large btn-block" id="field2" type="submit" name="submit"></button>
				</div>
			</form>
			
			<div id="login-lost-pass" class="background2">
				<div class="bg1"></div>
				<br/>
			</div>
		</div>
    </div>	
   </div>
</div>
