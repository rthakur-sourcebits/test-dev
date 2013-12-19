<!-- 
 * @File : change_user_password.php
 * @Author: 
 * @Created: 11-11-2012
 * @Modified:  
 * @Description: View for Change user password
   Copyright (c) 2012 Acclivity Group LLC 
-->
 <meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />

<?php 
echo html::style('media/css/time_tracker_user.css');
?>

<!-- 
<div class="reset-password-container-fluid">
  	<div class="row-fluid">
	  	<div id="container" class="reset-container">
			<?php if(isset($error))
			{
			 $height_error_form='300px';
			} 
			else if(isset($success))
			{
			 $height_error_form='320px';
			}
			else
			{
			 $height_error_form='272px';
			}
			?>
			<form action="<?php echo SITEURL;?>/admin/change_user_password" method="post" id="reset_password_form" style="height:<?php echo $height_error_form;?>" name="reset_password" >
				<div><br/></div>	
				<label class="reset-password-label">Reset Password</label>
			<div class="clear"></div>
			<?php if(isset($success)){ ?>
					<div class='success_message'>You have successfully changed your password.<br/>
                    Please <a href="<?php echo SITEURL?>" class="reset-pass">click here</a> to login as user</div><br/>
                    <?php }?>
				<div id="row" class="row">
					<div id="row1" class="row1">
						<div class="controls controls-row">
							<div id="reset-password-frame">
										 <div class="reset-cur-password <?php if(isset($error)) { echo "error-label"; } ?> ">
										 <?php if(isset($error)) { 	?>
										 			<div class="reset-password">Current Password</div>
										 <?php } else {?>
										 			<span>Current Password</span>
										 <?php }?>
										 	<input class="span8 password-margin" type="password" id="cur_password" name="cur_password" autocomplete="off">
										 </div>  
										 <div class="login-middle-border  <?php if(isset($error)) { echo "no-height"; } ?>">&nbsp;</div>
										 <div class="reset-new-password <?php if(isset($error)) { echo "error-label"; } ?>">
										  <?php if(isset($error)) { 	?>
										 			<div class="reset-password">New Password</div>
										 <?php } else {?>
										 			<span>New Password</span>
										 <?php }?>
										 <input class="span8 password-margin" type="password" name="new_password" id="new_password"></div>
										 <div class="login-middle-border  <?php if(isset($error)) { echo "no-height"; } ?>">&nbsp;</div>
										 <div class="reset-confirm-password <?php if(isset($error)) { echo "error-label"; } ?> ">
										 <?php if(isset($error)) { 	?>
										 			<div class="reset-password">Confirm Password</div>
										 <?php } else {?>
										 			<span >Confirm Password</span>
										 <?php }?>
										 	<input class="span8 password-margin" type="password" id="confirm_password" name="confirm_password" autocomplete="off">
										 </div>
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
				</div><br/>
				
				
				<div class="next-page-reset-btn" style="margin: 2% 5% 20%;">
					<a class="blue-button " href="#" id="next-link" onclick="reset_password.submit();">
						<div class="reset-password-button">
							<label class="next-label">
							Reset Password
							</label>
						</div>
					</a>
				</div>
				<input type="hidden" name="activate_id" value="<?php echo $activate_id;?>" />
			</form>
			</div>
	   </div>	
   </div>
-->

<script type="text/javascript">
	
	$('#aecResetPwdForm').keydown(function(event) {
        if (event.keyCode == 13) {
        	if(check_equal()){
            	this.form.submit();
            	return false;
           	}
         }
	});
	
	function check_onblur(type, str){
		if(!check_basic_pwd(str)){
			return false;
		}
		if(type==1){ 
			if($('#confirm_password').val().length >0){ // the 2nd input box base 
				if(!check_equal()){
					return false;
				}
			}
		}else{ 
			if($('#new_password').val().length >0){ // the 1st input box base 
				if(!check_equal()){
					return false;
				}
			}
		}
		$('.invalid_msg').css("display", "none");
		return true;
	}

	function check_equal(){
		if(!check_basic_pwd($('#new_password').val())){
			return false;
		}
		if($('#new_password').val().length != $('#confirm_password').val().length){
			// write the error log of mismatch charcater lenth;
			$('.invalid_msg').css("display", "block");
			$("#err_msg").text("Password character length mismatch.");
			return false;
			
		} else if($('#new_password').val() != $('#confirm_password').val()) { // check if each character is same.
			// write the error log of mismatch charcater 
			$('.invalid_msg').css("display", "block");
			$("#err_msg").text("Password character mismatch.");
			return false;
		}
		$('.invalid_msg').css("display", "none");
		return true;
	}

	function check_basic_pwd(str){
		if(str.length<6){
			// write the error log of min lenght
			$('.invalid_msg').css("display", "block");
			$("#err_msg").text("Your password must be at least 6 characters.");
			return false;
		} else if(str.length>20){
			// write the error log of max lenght
			$('.invalid_msg').css("display", "block");
			$("#err_msg").text("Password maximum length is 20 characters.");
			return false; 
		}else if (str.search(/[a-z]/i) < 0) {
		    $('.invalid_msg').css("display", "block");
			$("#err_msg").text("Your password must contain at least one letter.");
			return false; 
		}		
		return true;
	}

</script>
<div id="container" class="container">
	<div class="image_logo_aec"  id="image_logo_aec">
		<img class="login-logo" id="login-logo" src="<?php echo SITEURL;?>/media/images/login/logo_n_text.png" class="" />
	</div>
	<div class="">
		
			<?php if(isset($success)){ ?>
			 <br/> <br/> 
				<div class='success_message_emp'>You have successfully changed your password.<br/>
                	Please <a href="<?php echo SITEURL?>" class="reset-pass">click here</a> to login as user</div><br/>
            <?php }?>
            
            <?php if(isset($error)) {?>
          
	 		<div class="invalid_msg" ><img src="/media/images/tt-new/reset_error.png" />
				<span id="err_msg">Incorrect password. Please try again.</span>
	 		</div>
			  <br/>  <br/>
			<?php }?>
			 <?php if(!isset($error)) {?>
				<br/>
			<div class="invalid_msg" style="top:0;display: none;"><img src="/media/images/tt-new/reset_error.png" />
				<span id="err_msg"></span>
	 		</div>
	 		
	 		<?php } ?>
	 		
	 	
		<form action="<?php echo SITEURL;?>/admin/change_user_password" method="post" id="reset_password" style="height:<?php echo $height_error_form;?>" name="reset_password" >
			<br />
			<div class="reset_pwd" >
				<input type="password" placeholder="Old Password" class="reset-input" id="cur_password" name="cur_password" autocomplete="off"  />
			</div>
			<div class="clear"></div>
			<br />
			<div class="reset_pwd">
				<input type="password"  value="" placeholder="Password" class="reset-input" name="new_password" id="new_password" autocomplete="off"  onblur="javascript: check_onblur(1, this.value);"/>				
				<div class="password-strength"></div>
			</div>
			<div class="clear"></div>
			<br />
			<div class="reset_pwd" >
				<input type="password" value="" placeholder="Confirm Password" class="reset-input" id="confirm_password" name="confirm_password" autocomplete="off" onblur="javascript: check_onblur(2, this.value);" />
				<div class="confirm_password"></div>
			</div>
			<div class="clear"></div>
			<br />
			
			<div class="next-page-reset-btn">
				<a class="blue-button " href="#" id="next-link" onclick="reset_password.submit();">
					<div class="reset-password-button-employee">
						Reset Password
					</div>
				</a>
			</div>
			<input type="hidden" name="activate_id" value="<?php echo $activate_id;?>" />
			
			<div class="clear"></div>
		</form>
	</div>
	<div class="clear"></div>
</div>
<!-- Giving white background to only login page  -->
<style>
body{
background:#ffffff;
}
.container{
background:#ffffff;
position:relative;
top:100px;
}
</style>
<script type="text/javascript">
	$(document).ready(function(){		
    	var input = document.getElementById ("cur_password");
  		input.focus ();
  		
  	});	
</script>

