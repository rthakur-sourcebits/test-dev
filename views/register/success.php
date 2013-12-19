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
<style type="text/css">
.safari .freeuser-confirm .confirm-input-w49 {
	padding-top:19px !important;
}
</style>
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
				<h2>Confirmation</h2>
				<div class="clear"></div>
				<div class="table-container">
				<?php if($company[0]['price'] != 0) {?>
							<h2 class="center ptop20"><?php echo $company[0]['name'];?>&nbsp;-&nbsp;$<?php echo $company[0]['price'];?>/mo.</h2>
	    					<div class="success-confirm">Please review and click Sign Up to complete your order</div>
	    					<fieldset >
	    						<label class="left addr-w43">  Name</label>
								
	    					<input type="text" value="<?php echo $company[0]['user_name']." ".$company[0]['last_name'];?>" class="confirm-input-w49" readonly="true">
	    						<div class="clear"></div>
	    					</fieldset>
	    					<fieldset  >
	    						<label class="left addr-w43">  Company</label>
	    					 	<input type="text" value="<?php echo $company[0]['company_name'];?>" class="confirm-input-w49" readonly="true">
								
	    						<div class="clear"></div>
	    					</fieldset>
	    					<fieldset>
	    						<label class="left addr-w43"> Country</label>
	    						<input type="text" value="<?php echo $company[0]['country'];?>" class="confirm-input-w49" readonly="true">
	    						<div class="clear"></div>
	    					</fieldset>
							<fieldset>
	    						<label class="left addr-w43">Address</label>
	    						<input type="text" value="<?php echo $company[0]['address'];?>" class="confirm-input-w49" readonly="true">
	    						<div class="clear"></div>
	    					</fieldset>
	    					
	    					<fieldset>
	    						<label class="left addr-w43">&nbsp;</label>
	    						<input type="text" value="<?php echo $company[0]['city'];?>" class="confirm-input-w49" readonly="true">
	    						<div class="clear"></div>
	    					</fieldset>
	    					
	    					<fieldset>
	    						<label class="left addr-w43">&nbsp;</label>
	    						<input type="text" value="<?php echo $company[0]['state'];?>" class="confirm-input-w49" readonly="true">
	    						<div class="clear"></div>
	    					</fieldset>
	    					
	    					<fieldset>
	    						<label class="left addr-w43">&nbsp;</label>
	    						<input type="text" value="<?php echo $company[0]['zipcode'];?>" class="confirm-input-w49" readonly="true">
	    						<div class="clear"></div>
	    					</fieldset>
	    					
							<fieldset>
	    						<label class="left addr-w43">Telephone</label>
	    						<input type="text" value="<?php echo $company[0]['phone'];?>" class="confirm-input-w49" readonly="true">
	    						<div class="clear"></div>
	    					</fieldset>
							
							<fieldset>
	    						<label class="left addr-w43">Email</label>
	    						<input type="text" value="<?php echo $company[0]['email'];?>" class="confirm-input-w49" readonly="true">
	    						<div class="clear"></div>
	    					</fieldset>
							<fieldset>
	    						<label class="left addr-w43">Credit Card</label>
	    						<input type="text" value="<?php echo $company[0]['cardinfo']['credit_card_type'];?>&nbsp;<?php echo $company[0]['cardinfo']['credit_card_number'];?>&nbsp;exp&nbsp;<?php echo $company[0]['cardinfo']['exp_date_month'];?>/<?php echo $company[0]['cardinfo']['exp_date_year'];?>" class="confirm-input-w49" readonly="true">
	    						<div class="clear"></div>
	    					</fieldset>
	    			<?php } else {?>
	    				<h2 class="center ptop20">Unlimited Free Trial (30 days)</h2>
	    				<fieldset style="padding:3px 0 !important;" class="freeuser-confirm">
	    						<label class="left addr-w43" style="padding-top: 8px !important;">Email</label>
	    						<input type="text" value="<?php echo $company[0]['email'];?>" class="confirm-input-w49" readonly="true">
	    						<div class="clear"></div>
	    				</fieldset>
	    			<?php }?>
							</div>
							<p class="center bottom">By clicking Sign Up  you agree to the <a href="http://accountedge.com/timetracker/tos" target="_blank" class="terms-link">Terms of  Service</a></p>
							<?php 
							if(!empty($upgrade)) { 
								$button_url	=	SITEURL."/admin";
							} else {
								$button_url	=	SITEURL."/admin/activation/".$company[0]['activation_id'];
							}
							?>
						
						<a class="blue-button blue-btn-pad " href="<?php echo SITEURL?>/register/sucessregister">Sign Up</a>
							<div class="clear"></div>
			</div>
        </div>
        
 <div class="popup_admin" id='success_signup_message'>
	<div class="success_message" align="center" style="font-weight:bold;">API key successfully Updated</div>
	<div align="center" style="margin-top:15px;"><a href="#" class="radius-5 ok-button-api" onclick="go_back_to_welcome();">OK</a></div>
</div>
-->

<!-- Central Contents div starts -->
	

<link href="/media/css/tt-signup.css" rel="stylesheet">

<div id="outer-div" class="outer-div">

		<div id="signup-menu">
		<img src="<?php echo SITEURL;?>/media/images/login/logo_n_text.png"  width="194px" class="" id="">
		</div>
		
		<div class="clear">
		</div>
	<div class="central-contents" >
			<div class="acc-info-confirmation">
				<label class="choose-plan-label">Confirmation</label>
				<label class="plan-change-label">Review and confirm your order below.</label>
			</div>
			<?php if($company[0]['price'] != 0) {?>
			<div id="subscription-plan-confirm" class="subscription-plan-confirm">
				<label class="subscription-label-confirm"><?php echo $company[0]['name'];?>&nbsp;&nbsp;<span id="cost-plan">$<?php echo $company[0]['price'];?><span id="per-month-span">/month<span></span>
				</label>
			</div>
			
			<div class="search-contents-bill" >
				<div class="first-last-name">
					<div class="row">
						<div class="span1" id="fname"><label class="name-label">First Name</label></div>
						<span id="fname-cnf"><?php echo $company[0]['user_name'];?></span>
						<div class="span1" id="lname"><label class="name-label">Last Name</label></div>
						<span id="lname-cnf"><?php echo $company[0]['last_name'];?></span>
					</div>
				</div>
			  
				<div class="search-contents-row-confirm" id="search-contents-row-confirm"   >
					<div class="users-activity-company-cnf" id="users-activity-company-cnf" ><label>Company Name</label></div> 
					<label id="confirm-company"><?php echo $company[0]['company_name'];?></label>
				</div>

			</div>
	  
			<div class="search-contents-bill" >
				<div class="street-cnf">
					<div id="street1-cnf"><label>Address</label></div>
						<label  id="customer-textfield-street-cnf"  ><?php echo $company[0]['address'];?></label>
				</div>
				<div class="street-cnf">
					<div id="street1-cnf"><label>Street 2</label></div>
						<label  id="customer-textfield-street-cnf"  >Charlotte Boulevard</label>
				</div> 
				
				<div class="first-last-name">
					<div class="row">
						<div class="span1" id="fname"><label class="name-label">City</label></div>
						<span id="fname-cnf"><?php echo $company[0]['city'];?></span>
						<div class="span1" id="lname"><label class="name-label">State/Province</label></div>
						<span id="lname-cnf"><?php echo $company[0]['state'];?></span>
					</div>
				</div>
  
				<div class="first-last-name">
					<div class="row">
						<div class="span1" id="fname"><label class="name-label">Zip/Postal code</label></div>
						<span id="fname-cnf"><?php echo $company[0]['zipcode'];?></span>
						<div class="span1" id="lname"><label class="name-label">Country</label></div>
						<span id="lname-cnf"><?php echo $company[0]['country'];?></span>
					</div>
				</div>
	  
				<div class="first-last-name no-bor-bottom">
					<div class="row">
						<div class="span1" id="fname"><label class="name-label">Phone Number</label></div>
						<span id="fname-cnf"><?php echo $company[0]['phone'];?></span>
						<div class="span1" id="lname"><label class="name-label">Email</label></div>
						<span id="lname-cnf"><?php echo $company[0]['email'];?></span>
					</div>
				</div>

			</div>
	  
			<div class="search-contents-bill" >
				<div class="first-last-name no-bor-bottom">
					<div class="row">
						<div class="span1" id="fname"><label class="name-label">Credit Card</label></div>
						<span id="credit-card-number"><?php echo $company[0]['cardinfo']['credit_card_type'];?>&nbsp;<?php echo $company[0]['cardinfo']['credit_card_number'];?></span>
						<div class="span1" id="expiry-cc"><label class="name-label">Expiry</label></div>
						<span id="credit-card-expiry"><?php echo $company[0]['cardinfo']['exp_date_month'];?>/<?php echo $company[0]['cardinfo']['exp_date_year'];?></span>
					</div>
				</div>
			</div>
			
			 	<!-- Phone Version Starts -->
		<!-- Phone Version Outer Div Starts-->
 <div class="search-contents-bill-phone-version" id="search-contents-bill-phone-version">
 	<!-- Phone Version inner1 Div Starts -->
  <div class="search-contents-bill-phone-inner" >
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >First Name</div> 
	<div id="label-span-billing" > <label class="top-3" id="customer-billing-phone"><?php echo $company[0]['user_name'];?></label>
	</div> 
	
	  </div>
	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Last Name</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['last_name'];?></span>
	</div>
	  </div>
	   <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Company Name</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['company_name'];?></span>
	</div>
	  </div>
	  
	  </div>
	  	<!-- Phone Version Inner1 Div Ends -->
	 
	  <!-- Phone Version inner2 Div Starts -->
  <div class="search-contents-bill-phone-inner" >
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Address</div> 
	<div id="label-span-billing" > <label class="top-3" id="customer-billing-phone"><?php echo $company[0]['address'];?></label>
	</div> 
	
	  </div>
<!-- 	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Street 2</div> 
	<div id="label-span-billing" > <Span id="customer-billing-phone">$60 per hour</span>
	</div>
	  </div> -->
	   <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >City</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['city'];?></span>
	</div>
	  </div>
	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >State / Province</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['state'];?></span>
	</div>
	  </div>
	  
	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Zip/Postal Code</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['zipcode'];?></span>
	</div>
	  </div>
	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Country</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['country'];?></span>
	</div>
	  </div>
	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Phone Number</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['phone'];?></span>
	</div>
	  </div>
	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Email</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['email'];?></span>
	</div>
	  </div>
	   <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Card Type</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['cardinfo']['credit_card_type'];?></span>
	</div>
	  </div>
	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Credit Card</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['cardinfo']['credit_card_number'];?></span>
	</div>
	  </div>
	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
	   <div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Expiry</div> 
	<div id="label-span-billing" > <Span class="top-3" id="customer-billing-phone"><?php echo $company[0]['cardinfo']['exp_date_month'];?>/<?php echo $company[0]['cardinfo']['exp_date_year'];?></span>
	</div>
	  </div>
	  
	  </div>
	  	<!-- Phone Version Inner2 Div Ends -->
		
	  </div>
	  
	  
	   <!-- Phone Version Outer Div Ends -->
			
			<?php } else {?>
			<div id="subscription-plan-confirm" class="subscription-plan-confirm">
				<label class="subscription-label-confirm">Unlimited Free Trial <span id="cost-plan">(30 days)</span>
				</label>
			</div>
			<div class="search-contents-bill email-success" >
				<div class="search-contents-row-confirm" id="search-contents-row-confirm"   >
					<div class="users-activity-company-cnf" id="users-activity-company-cnf" ><label>Email</label></div> 
					<label id="confirm-company"><?php echo $company[0]['email'];?></label>
				</div>
			</div>
			<?php }?>
			<form method="post" action="<?php echo SITEURL?>/register/sucessregister" id="confirm_order">
	 		<div class="next-page-btn mar-button">
				<div class="confirm-order" >
					<label class="confirm-order-label">
					By confirming your order you agree to the <a href="http://accountedge.com/timetracker/tos" target="_blank" class="terms-of-service">Terms of Service</a>
					</label>
					    <?php 
							if(!empty($upgrade)) { 
								$button_url	=	SITEURL."/admin";
							} else {
								$button_url	=	SITEURL."/admin/activation/".$company[0]['activation_id'];
							}
						?>
				</div>
				 
				<input type="hidden" name="email" value="<?php echo $company[0]['email'];?>" class="confirm-input-w49">
				 <a onclick="$('#confirm_order').submit();" id="next-link">  
				
					<div class="confirm-button next-label" style="cursor:pointer;">
						Confirm Order
					</div>
				</a>
			</div>  
			</form>
	 </div>
<!-- Central contents div ends -->

	 </div>
<!-- Outer div ends -->

 <div class="popup_admin" id='success_signup_message'>
	<div class="success_message" align="center" style="font-weight:bold;">API key successfully Updated</div>
	<div align="center" style="margin-top:15px;"><a href="#" class="radius-5 ok-button-api" onclick="go_back_to_welcome();">OK</a></div>
</div>

<script>
$(document).ready(function(){
	
	$('.central-contents').css("border-radius","5px 5px 5px 5px");
	if($(window).width() < 400) 
	{
		$('.search-contents-bill-phone-version').show();
		$('.search-contents-bill').hide();
		$('.outer-div').css("padding","0");
		$('.central-contents').css("margin","5% 0 0");
		$('.subscription-plan-confirm').css("margin","4% 2% 4%");
		$('.mar-button').css("margin","4% 2% 25%");
		$('.email-success').show();
	}
	else if (($(window).width() > 400) && ($(window).width() <= 760))
	{
		$('.search-contents-bill-phone-version').show();
		$('.search-contents-bill').hide();
		$('.outer-div').css("padding","0");
		$('.central-contents').css("margin","5% 0% 0");
		$('.subscription-plan-confirm').css("margin","4% 2% 4%");
		$('.mar-button').css("margin","4% 2% 25%");
		$('.email-success').show();
	}
	else if (($(window).width() > 760) && ($(window).width() < 1200))
	{
		$('.search-contents-bill-phone-version').hide();
		$('.search-contents-bill').show();
		$('.outer-div').css("padding","2%");
		$('.central-contents').css("margin","26px 2% 5%");
		$('.subscription-plan-confirm').css("margin","2% 1% 2%");
		$('.mar-button').css("margin","3% 1%");
		$('.search-contents-bill').css("margin","2% 1% 2%");
		$('.email-success').show();
	}
	
	else
	{	
		
		$('.search-contents-bill-phone-version').hide();
		$('.search-contents-bill').show();
		$('.outer-div').css("padding","2%");
		$('.central-contents').css("margin","26px 10% 5%");
		$('.subscription-plan-confirm').css("margin","2% 10% 2%");
		$('.mar-button').css("margin","3% 10%");
		$('.email-success').show();
		$('.search-contents-bill').css("margin","2% 10% 2%");
		
	}
	
	$(window).resize(function()
	{$('.central-contents').css("border-radius","5px 5px 5px 5px");
	if($(window).width() < 400 )
	{
		$('.search-contents-bill-phone-version').show();
		$('.search-contents-bill').hide();
		$('.outer-div').css("padding","0");
		$('.central-contents').css("margin","5% 0 0");
		$('.subscription-plan-confirm').css("margin","4% 2% 4%");
		$('.mar-button').css("margin","4% 2% 25%");
		$('.email-success').show();
		
	}
	else if (($(window).width() > 400) && ($(window).width() <= 760))
	{
		$('.search-contents-bill-phone-version').show();
		$('.search-contents-bill').hide();
		$('.outer-div').css("padding","0");
		$('.central-contents').css("margin","5% 0% 0");
		$('.subscription-plan-confirm').css("margin","4% 2% 4%");
		$('.mar-button').css("margin","4% 2% 25%");
		$('.email-success').show();
	}
	else if (($(window).width() > 760) && ($(window).width() < 1200))
	{
		$('.search-contents-bill-phone-version').hide();
		$('.search-contents-bill').show();
		$('.outer-div').css("padding","2%");
		$('.central-contents').css("margin","26px 2% 5%");
		$('.subscription-plan-confirm').css("margin","2% 1% 2%");
		$('.mar-button').css("margin","3% 1%");
		$('.search-contents-bill').css("margin","2% 1% 2%");
		$('.email-success').show();
	}
	
	else
	{
		$('.search-contents-bill-phone-version').hide();
		$('.search-contents-bill').show();
		$('.outer-div').css("padding","2%");
		$('.central-contents').css("margin","26px 10% 5%");
		$('.subscription-plan-confirm').css("margin","2% 10% 2%");
		$('.mar-button').css("margin","3% 10%");
		$('.email-success').show();
		$('.search-contents-bill').css("margin","2% 10% 2%");
		
	}
	});
	});
</script>