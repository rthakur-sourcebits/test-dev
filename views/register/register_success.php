<!--<?php 
echo HTML::script('media/scripts/custom-form-elements-2.js');
echo html::style('media/css/signup.css');
echo HTML::script('media/scripts/jquery.validate.pack.js');

echo html::script('media/scripts/json_parse.js');
echo html::script('media/scripts/jquery.autocomplete.js');
echo html::style('media/css/jquery.autocomplete.css');
echo html::script('media/scripts/passwordmeter.js');
echo HTML::script('media/scripts/css-browser-selector.js');

?>
 <div class="header">
        <h1 class="logo"><a href="<?php echo SITEURL?>/admin">Time Tracker</a></h1>
     </div>
        <div class="account-wrapper">
        	<ul class="arrow-nav">
				<li class="plan "><span>Choose Plan</span></li>   
				<li class="info"><span>Account Info</span></li>      
				<li class="address "><span>Address</span></li>      
				<li class="method"><span>Payment Method</span></li>     
				 <li class="confirm active2"><span>Confirmation</span></li>       	
        	</ul>
			<div class="clear"></div>
			<div class="account-content">
				<h2>Thanks</h2><br/>
				<div class="register-success-text">You'll now receive an email with detailed instructions on getting set up. 
					If you have questions, you can check our <a href="http://support.accountedge.com/kb/time-tracker" target="_blank">FAQs</a>, <a href="http://support.accountedge.com/discussions/time-tracker" target="_blank">Forum</a> or send us an <a href="http://accountedge.com/help" target="_blank">email</a>. We're here to help.</div>
				<div class="clear"></div>
				
        </div>
        </div>
-->

<!-- New Contents Starts -->

<link href="/media/css/tt-signup.css" rel="stylesheet">

<div id="outer-div">
		<div id="signup-menu">
		<img src="<?php echo SITEURL;?>/media/images/login/logo_n_text.png"  width="194px" class="" id="">
		</div>
		
		<div class="clear">
		</div>
	<!-- Central contents starts -->
		<div class="central-contents confirm_success_central_contents" >
			<div class="acc-info-confirmation">
				<label class="choose-plan-label">Thank You</label>
				<img src="/media/images/new-signup/cloud-tick.png" class="cloud-tick">
			</div>
			<div class="verify-email">
				<label class="verify-email-label">
					Please select the link sent to <span class="email-success-page"><?php echo $email;?></span>, to activate your account and verify your email address.
				</label>
				<!--<label class="verify-email-label">
					Didn't receive your activation email?
				</label>
			--></div>
			<!--<div class="left-resend-btn">
				<a href="#" id="next-link">
					<div class="resend-button">
						<label class="next-label">
							Resend Mail
						</label>
					</div>
				</a>
			</div>  
			<br/>
			-->
			<br/>
			<div class="customer-queries">
				<label class="verify-email-label">
					If you have any questions you can check our <a href="http://support.accountedge.com/kb/time-tracker" target="_blank" class="anchor-query">FAQ's</a>, <a href="http://support.accountedge.com/discussions/time-tracker" target="_blank" class="anchor-query">Forum </a>or send us an <a href="http://accountedge.com/help" target="_blank" class="anchor-query">email</a>.We're here to help.
				</label>
			</div>
		</div>
  <!-- Central contents ends -->
  
<!-- End of outer div -->		
</div>

<script>
$(document).ready(function(){
$('.central-contents').css("border-radius","5px 5px 5px 5px");
	if($(window).width() < 400) 
	{
		$('.outer-div').css("padding","0");
		$('.verify-email-label').css("margin-bottom","12px");
	}
	else if (($(window).width() > 400) && ($(window).width() < 600))
	{
		$('.outer-div').css("padding","0");
		$('.verify-email-label').css("margin-bottom","12px");
	}
	else if (($(window).width() > 600) && ($(window).width() < 1000))
	{
		$('.outer-div').css("padding","0");
		$('.verify-email-label').css("margin-bottom","5px");
	}
	else if (($(window).width() > 1000) && ($(window).width() < 1050))
	{
		$('.outer-div').css("padding","4%");
		$('.verify-email-label').css("margin-bottom","5px");
	}
	else
	{
		$('.outer-div').css("padding","4%");
		$('.verify-email-label').css("margin-bottom","5px");
	}
$(window).resize(function()
	{
		$('.central-contents').css("border-radius","5px 5px 5px 5px");
		if($(window).width() < 400 )
		{
			$('.outer-div').css("padding","0");
			$('.verify-email-label').css("margin-bottom","12px");
		}
		else if (($(window).width() > 400) && ($(window).width() < 600))
		{
			$('.outer-div').css("padding","0");
			$('.verify-email-label').css("margin-bottom","12px");
		}
		else if (($(window).width() > 600) && ($(window).width() < 1000))
		{
			$('.outer-div').css("padding","0");
			$('.verify-email-label').css("margin-bottom","5px");
		}
		else if (($(window).width() > 1000) && ($(window).width() < 1050))
		{
			$('.outer-div').css("padding","4%");
			$('.verify-email-label').css("margin-bottom","5px");
		}
		else
		{
			$('.outer-div').css("padding","4%");
			$('.verify-email-label').css("margin-bottom","5px");
		}
	});
});
</script>
