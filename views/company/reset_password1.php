<?php
echo HTML::script('media/scripts/jquery-1.7.1.min.js');
?>
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
			if($('#reset-input2').val().length >0){ // the 2nd input box base 
				if(!check_equal()){
					return false;
				}
			}
		}else{ 
			if($('#reset-input1').val().length >0){ // the 1st input box base 
				if(!check_equal()){
					return false;
				}
			}
		}
		$('.invalid_msg').css("display", "none");
		return true;
	}

	function check_equal(){
		if(!check_basic_pwd($('#reset-input1').val())){
			return false;
		}
		if($('#reset-input1').val().length != $('#reset-input2').val().length){
			// write the error log of mismatch charcater lenth;
			$('.invalid_msg').css("display", "block");
			$("#err_msg").text("Password character length mismatch.");
			return false;
			
		} else if($('#reset-input1').val() != $('#reset-input2').val()) { // check if each character is same.
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
	<div class="content_inner_login">
		<div class="invalid_msg" style="display: none;"><img src="/media/images/tt-new/reset_error.png" />
			<span id="err_msg"></span>
	 	</div>
	 	<br/>	
		<form action="<?php echo SITEURL;?>/company/password_reset" method="post" id="aecResetPwdForm" onsubmit="return check_equal();">
			<br />
			<br />
			<div class="reset_pwd">
				<input type="password" id="reset-input1"  value="" placeholder="Password" class="reset-input" id="inp_username" name="reset-input1" autocomplete="off"  onblur="javascript: check_onblur(1, this.value);"/>				
				<div class="password-strength"></div>
			</div>
			<div class="clear"></div>
			<br />
			<div class="reset_pwd" >
				<input type="password" id="reset-input2" value="" placeholder="Password Again" class="reset-input" id="inp_username" name="reset-input2" autocomplete="off" onblur="javascript: check_onblur(2, this.value);" />
				<input type="hidden" id="auth_code" value="<?php echo $auth_code; ?>" name="auth_code" />			
				<div class="confirm_password"></div>
			</div>
			<div class="clear"></div>
			<br />
			<div class="reset-shadow">
				<button class="login-button" type="submit" name="submit" >
					<span class="login-button-value">Save Password</span>
				</button>
			</div>
			<div class="clear"></div>
		</form>
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
<script type="text/javascript">
	$(document).ready(function(){		
    	var input = document.getElementById ("reset-input1");
  		input.focus ();
  		$('.login-button').live('click',function(){
			$('#aecResetPwdForm').submit();
  	  	});
  	});	
</script>
