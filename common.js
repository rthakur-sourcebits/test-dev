/*
 *	@Description	: Script for Password Strength Checking
*/
$(document).ready(function() {
	$('#myPassword,#reset-input1,#new_password').keyup(function() 
	{ 
		var txtVal = this.value;
		var strength = 0;
		if (txtVal.length <= 6) 
		{
		strength=0;
		}
   		if (txtVal.length >= 7) 
		 {
		 strength=1;
		 } 
		if (txtVal.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  
			{
			strength++;
			}
		if (txtVal.match(/([a-zA-Z])/) && txtVal.match(/([0-9])/))
			{
			strength++;
			}
		if (txtVal.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) 
			{
			strength++;
			}
		if (txtVal.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) 
			{
			strength++;
			}
		if (txtVal.length ==0) 
			{
			strength=-1;
			}
		if(strength==-1)
		{
		$('.password-strength').css("background","url('/media/images/new-signup/email-strength.png') 50% 50% no-repeat");
		}
		if(strength==0)
			{
			$('.password-strength').css("background","url('/media/images/new-signup/password_red.png') 50% 50% no-repeat");
			}
		if(strength==1)
			{
			$('.password-strength').css("background","url('/media/images/new-signup/password_yellow.png') 50% 50% no-repeat");
			}
		if(strength==2)
			{
			$('.password-strength').css("background","url('/media/images/new-signup/password_yellow.png') 50% 50% no-repeat");
			}
		if(strength==3)
			{
			$('.password-strength').css("background","url('/media/images/new-signup/password_green.png') 50% 50% no-repeat");
			}
		if(strength==4)
			{
			$('.password-strength').css("background","url('/media/images/new-signup/password_green.png') 50% 50% no-repeat");
			}
	});
	$('#reset-input2').keyup(function() 
	{
		if($('#reset-input2').val()!=''){
			if($('#reset-input2').val() == $('#reset-input1').val()){
				$('.confirm_password').css("background","url('/media/images/new-signup/enable.png') 50% 50% no-repeat");
			}else{
				$('.confirm_password').css("background","url('/media/images/new-signup/disable.png') 50% 50% no-repeat");
			}
		}
	});
	$('#confirm_password').keyup(function() 
	{
		if($('#confirm_password').val()!=''){
			if($('#confirm_password').val() == $('#new_password').val()){
				$('.confirm_password').css("background","url('/media/images/new-signup/enable.png') 50% 50% no-repeat");
			}else{
				$('.confirm_password').css("background","url('/media/images/new-signup/disable.png') 50% 50% no-repeat");
			}
		}
	});
});
