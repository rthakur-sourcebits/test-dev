<!-- 
  @File : admintemplate.php View
  @Description: This file acts as a template for admin and sales module.
  Copyright (c) 2012 Acclivity Group LLC 
 -->

<!DOCTYPE html>
<html>
	<head>
	    <title>AccountEdge Cloud</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />
	    <!-- Bootstrap -->
		<?php 
		if(isset($_SESSION['superadmin_session'])) {
			echo html::style('media/css/admin.css');
			echo HTML::script('media/scripts/jquery-ui/js/jquery-1.4.2.min.js');
			echo html::script('media/scripts/jqtransform.js');
			echo html::style('media/css/jqtransform.css');
		} else {
			echo html::style('media/css/bootstrap.css');
			echo html::style('media/css/bootstrap-responsive.css');
			echo html::style('media/css/time_tracker.css');
			echo HTML::script('media/scripts/jquery-1.7.1.min.js');
			echo HTML::script('media/scripts/bootstrap.js');
		}
		echo html::style('media/css/jScrollPane.css');
		echo HTML::script('media/scripts/admin.js');
		echo HTML::script('media/scripts/jquery.mousewheel.min.js');
		echo HTML::script('media/scripts/jScrollPane.js');
		echo HTML::script('media/scripts/main.js');
		echo HTML::script('media/scripts/common.js');
		
		?>
		<?php 
        echo html::style('media/css/datePicker.css');
        ?>
        <script type="text/javascript" src="/media/scripts/date.js"></script>
        <script type="text/javascript" src="/media/scripts/jquery.datePicker.js"></script>
        
        <script src="/media/scripts/jquery.switch.js" type="text/javascript"></script>
		<script src="/media/scripts/jquery.nicescroll.js" type="text/javascript"></script>
   		<link rel="shortcut icon" href="/media/images/tt-new/Logo.ico" />
    <meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />
    <!-- Le syntax highlighting -->
    <link href="/media/css/prettify.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="/media/scripts/prettify.js"></script>
    <script>$(function () { prettyPrint() })</script>
      
    <!-- Le jquery switch-->
    <link href="/media/css/jquery.switch.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="/media/scripts/jquery.switch.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				var nicesx = $("#sidebar-main").niceScroll({touchbehavior:false,cursorcolor:"#b6b6b6",cursoropacitymax:0.4,cursorwidth:6});
				if($(window).width() < 390) {
					$(".phone-account-edge-outer-header").show();
					$(".account-edge-outer-header").hide();
					$(".ac-identifier-label-phone").show();
					$(".ac-identifier-label").hide();
					$("#my-account").hide();
					
					$("#cloud-img").css("padding-top","2%");
				}
				else if(($(window).width() > 390) && ($(window).width() < 500)  ) {
					$(".phone-account-edge-outer-header").show();
					$(".account-edge-outer-header").hide();
					$(".ac-identifier-label-phone").show();
					$(".ac-identifier-label").hide();
					$("#my-account").hide();
					
					$("#cloud-img").css("padding-top","2%");
				}
				else if(($(window).width() > 500) && ($(window).width() <= 700)  ) {
					$(".phone-account-edge-outer-header").show();
					$(".account-edge-outer-header").hide();
					$(".ac-identifier-label-phone").show();
					$(".ac-identifier-label").hide();
					$("#my-account").hide();
					$("#cloud-img").css("padding-top","1.3%");
				}
				else {
					$(".phone-account-edge-outer-header").hide();
					$(".account-edge-outer-header").show();
					$(".ac-identifier-label-phone").hide();
					$(".ac-identifier-label").show();
					$("#my-account").show();
					$("#cloud-img").css("padding-top","2%");
				}
				$(window).resize(function() {
					var width = $(window).width();
					width = (width-175)+'px';
					$('#dropdown-menu-bottom').css('left',width);
					if($(window).width() < 390) {
						$(".phone-account-edge-outer-header").show();
						$(".account-edge-outer-header").hide();
						$(".ac-identifier-label-phone").show();
						$(".ac-identifier-label").hide();
						$("#my-account").hide();
						$("#cloud-img").css("padding-top","2%");
					
					}
					else if(($(window).width() > 390) && ($(window).width() < 500)  ) {
						$(".phone-account-edge-outer-header").show();
						$(".account-edge-outer-header").hide();
						$(".ac-identifier-label-phone").show();
						$(".ac-identifier-label").hide();
						$("#cloud-img").css("padding-top","2%");
						$("#my-account").hide();
					
					}
					else if(($(window).width() > 500) && ($(window).width() <= 700)  ) {
						$(".phone-account-edge-outer-header").show();
						$(".account-edge-outer-header").hide();
						$(".ac-identifier-label-phone").show();
						$(".ac-identifier-label").hide();
						$("#my-account").hide();
						$("#cloud-img").css("padding-top","1.3%");
					
					}
					else {
						$(".phone-account-edge-outer-header").hide();
						$(".account-edge-outer-header").show();
						$(".ac-identifier-label-phone").hide();
						$(".ac-identifier-label").show();
						$("#my-account").show();
						$("#cloud-img").css("padding-top","2%");
					}
				});
			}); 
			function headerPhone(chk,field)
			{
				if(field == 1) 	{
					if ($('#time-tracker-ltem').hasClass('ac-timetracker-active') == true) 	{
	
					}
					else {
						$('#time-tracker-ltem').addClass('ac-timetracker-active');
						$('#admin-item').removeClass('ac-timetracker-active');
						$('#sales-item').removeClass('ac-timetracker-active');
						$(".tick-mark").show();
						$(".tick-mark1").hide();
						$(".tick-mark2").hide();
					}
				}
				if(field == 2) {
					if ($('#sales-item').hasClass('ac-timetracker-active') == true)
					{
		
					}
					else {
						$('#sales-item').addClass('ac-timetracker-active');
						$('#time-tracker-ltem').removeClass('ac-timetracker-active');
						$('#admin-item').removeClass('ac-timetracker-active');
						$(".tick-mark").hide();
						$(".tick-mark1").show();
						$(".tick-mark2").hide();
					}
				}
				if(field == 3) {
					if ($('#admin-item').hasClass('ac-timetracker-active') == true)
					{
		
					}
					else {
						$('#admin-item').addClass('ac-timetracker-active');
						$('#time-tracker-ltem').removeClass('ac-timetracker-active');
						$('#sales-item').removeClass('ac-timetracker-active');
						$(".tick-mark").hide();
						$(".tick-mark1").hide();
						$(".tick-mark2").show();
					}
				}
			}
			function header(chk,field)
			{
				if(field == 1) {
	
					if ($('#second-column').hasClass('timetracker-active') == false) {
						$('#second-column').addClass('timetracker-active');
						$('#col4').removeClass('timetracker-active');
						$('#third-column').removeClass('timetracker-active');
					}
				}
				if(field == 2) {
					if ($('#third-column').hasClass('timetracker-active') == false) {
						$('#third-column').addClass('timetracker-active');
						$('#second-column').removeClass('timetracker-active');
						$('#col4').removeClass('timetracker-active');
					}
				}
				if(field == 3) 	{
					if ($('#col4').hasClass('timetracker-active') == false) {
						$('#col4').addClass('timetracker-active');
						$('#second-column').removeClass('timetracker-active');
						$('#third-column').removeClass('timetracker-active');
					}
				}
			}
		</script>
  	</head>
	<body>
		<?php 
		if(isset($_SESSION['superadmin_session'])) {
			echo $content; 
		} else {
		?>
			
			 	<?php 
			 	if(isset($_SESSION['admin_email'])) {
			 	?>
			 	
			 	<div id="account-edge-outer-header" class="account-edge-outer-header">
					<div id="container4">
						<div id="container3">
						    <div id="container2">
						        <div id="container1">
						            <div id="first-column"><a href="<?php echo SITEURL?>"><img src="<?php echo SITEURL?>/media/images/tt-new/header_logo.png" class="highDefinition inner-logo" id="account-edge-logo" /></a></div>
						            <a href="<?php echo SITEURL?>/activitysheet" onclick="header(this,1)" >
    						            <div id="second-column">
    						            	<label class="header-label1">Time Tracker</label>
    						            </div>
						            </a>
						            <a href="<?php echo SITEURL?>/sales" onclick="header(this,2)" >
    						            <div id="third-column">
    						            	<label class="header-label2" <?php if(isset($_SESSION['group']) && !empty($_SESSION['group']) && $_SESSION['group']==2) echo "onmouseover=show_versioning_message(this);";?> >Sales</label>
    						            </div>
						            </a>
						            <a href="<?php echo SITEURL?>/admin" onclick="header(this,3)">
    									<div id="col4" class="timetracker-active">
    										<label class="header-label2">Admin</label>
    									</div>
									</a>
								</div>
						    </div>
						</div>
					</div>
				</div>
				
				<div id="phone-account-edge-outer-header" class="phone-account-edge-outer-header navbar-fixed-top" style="display:none">
					<div id="cloud-img"><a href="<?php echo SITEURL?>"><img src="<?php echo SITEURL?>/media/images/tt-new/aec-logo.png" width="33" height="23" class="cloud cloud-small" /></a>
						<label class="ac-identifier-label-phone">ADMIN</label>
						<div id="phone-toggle-bar" >
							<div class="btn-group">
								<a class="dropdown-toggle"  href="#">
									<button class="drop-btn">
										<img src="<?php echo SITEURL?>/media/images/tt-new/lines-img.png" id="icon-list">
									</button>
								</a>
				  				
								<ul class="dropdown-menu" id="dropdown-menu-bottom">
								<img src="<?php echo SITEURL?>/media/images/tt-new/account-arrow.png" class="my-account-popup-arrow">
									<li id="time-tracker-ltem" class="tt-admin"><a href="<?php echo SITEURL?>/activitysheet" class="tt-admin1 slips-number"><label class="ac-slips-phone non-selected-menu">TimeTracker</label></a></li>
									<li id="sales-item" <?php if(isset($_SESSION['group']) && !empty($_SESSION['group']) && $_SESSION['group']==2) echo "onmouseover=show_versioning_message(this);";?> class="tt-admin"><a href="<?php echo SITEURL?>/sales"class="tt-admin1 slips-number"><label class="ac-slips-phone non-selected-menu">Sales</label></a></li>
									<li id="admin-item" class="tt-admin ac-timetracker-active" ><a href="<?php echo SITEURL?>/admin"class="tt-admin1 slips-number"><label class="ac-slips-phone"><img src="<?php echo SITEURL?>/media/images/tt-new/tick.png" class="tick-mark2"><span class="selected-menu">Admin</span></label></a></li>
								</ul>  
							</div>
	  					</div>
	  					<?php echo View::factory("admin/myaccount_menu_phone");?>
	  				</div>
				</div>
								
			 	<div id="outer-div">
			 	
			 		<div id="ac-identifier">
    					<label class="ac-identifier-label">Admin</label>
    					<?php echo View::factory("admin/myaccount_menu");?>
					</div>
			 	<?php 
			 	}
			 	?>
				<?php 
				if(isset($content)){
					echo $content;
				}?>
				</div>
			
		 <?php 
		}
		?>
		
		<div id="grayout" class='grayBG'></div>
		<div class="popup_admin" id='change_user_password'>
			<div class="error_message" style="margin-bottom:3px;"></div> 
			<table cellpadding="0" cellspacing="0" class="change_admin_pass_table">
				<tr>
					<td>Current Password</td>
					<td><input type="password" name="user_current_password" value="" id="user_current_password" /></td>
				</tr>
				<tr colspan="2"><td>&nbsp;</td></tr>
				<tr>
					<td>New Password</td>
					<td><input type="password" name="user_new_password" value="" id="user_new_password" /></td>
				</tr>
				<tr colspan="2"><td>&nbsp;</td></tr>
				<tr>
					<td>Confirm Password</td>
					<td><input type="password" name="user_confirm_password" value="" id="user_confirm_password" /></td>
				</tr>
				<tr colspan="2"><td>&nbsp;</td></tr>
				<tr>
					<td colspan="2" align="center">
						<a href="#" class="radius-5 button-1 left" onclick="cancel_admin_popup('change_user_password');">Cancel</a>
						<a href="#" class="radius-5 alert-save right" onclick="change_user_password('<?php echo SITEURL;?>')">Save</a>
					</td>
				</tr>
			</table>
		</div>
		
		
		
		
		<div class="popup-menu popup-password admin-password" id="reset_password_confirm" onclick="show_admin_menu();">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="my-account-popup-arrow">
			
			<div class="my-acc-type bor-rad">
				<label class="select-sales-label heavy">Reset Password</label>
			</div>
			<div class="layout">
				<label class="layout-label dull">Admin Password</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="password" name="admin_password" value="" id="admin_password" />
			</div>
			<div class="status-message">&nbsp;
				<span class="loader" style="display:none;"><img src="/media/images/tt-new/ajax-loader-2.gif" /></span>
				<span class="status" style="display:none;"></span>
			</div>
			<div id="controls123" class="controls">
 				<button class="btn btn-small left mar-left-10" id="cancel-btn" type="button" onclick="cancel_form_admin();"><span id="cancel_span">Cancel</span></button>
				<button class="btn btn-small right mar-right-10" id="save-btn" type="button" onclick="reset_user_password('<?php echo SITEURL;?>');"><span id="save_span">Submit</span></button> 
			</div>
		</div>
		
		<div class="popup-menu popup-password" id="change_admin_password" onclick="show_admin_menu();">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="my-account-popup-arrow">
			
			<div class="my-acc-type bor-rad">
				<label class="select-sales-label heavy">Change Password</label>
			</div>
			<div class="layout">
				<label class="layout-label dull">Current Password</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="password" class="change_fields" name="current_password" value="" id="current_password" />
			</div>
			
			<div class="layout">
				<label class="layout-label dull">New Password</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="password" class="change_fields" name="new_password" value="" id="new_password" />
			</div>
			
			<div class="layout">
				<label class="layout-label dull">Confirm Password</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="password" class="change_fields" name="confirm_password" value="" id="confirm_password" />
			</div>
			
			
			<div class="status-message">&nbsp;
				<span class="loader" style="display:none;"><img src="/media/images/tt-new/ajax-loader-2.gif" /></span>
				<span class="status" style="display:none;"></span>
			</div>
			<div id="controls123" class="controls">
 				<button class="btn btn-small" id="cancel-btn" type="button" onclick="cancel_form_admin();"><span id="cancel_span">Cancel</span></button>
				<button class="btn btn-small" id="save-btn" type="button" onclick="change_admin_password('<?php echo SITEURL;?>')"><span id="save_span">Submit</span></button> 
			</div>
		</div>
		
		<div class="popup-menu popup-payment-details" id="payment_gateway_details" onclick="show_admin_menu();">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="my-account-popup-arrow">
			
			<div class="my-acc-type bor-rad">
				<label class="select-sales-label heavy">Merchant Details</label>
			</div>
			<div class="layout">
				<label class="layout-label normal">ACH Gateway Id</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="password" name="ach_gateway_id" class="payment-fields" value="" id="ach_gateway_id" />
			</div>
			
			<div class="layout">
				<label class="layout-label normal">ACH Gateway Password</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="password" name="ach_gateway_password" class="payment-fields" value="" id="ach_gateway_password" />
			</div>
			
			<div class="layout">
				<label class="layout-label normal">Confirm Gateway Password</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="password" name="confirm_gateway_password" class="payment-fields" value="" id="confirm_gateway_password" />
			</div>
			
			<div class="layout">
				<label class="layout-label normal">APLI Login Id</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="password" name="apli_login_id" class="payment-fields" value="" id="apli_login_id" />
			</div>
			
			<div class="layout">
				<label class="layout-label normal">Transaction Key</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="password" name="transaction_key" class="payment-fields" value="" id="transaction_key" />
			</div>
			
			<div class="status-message-payment">&nbsp;
				<span class="loader1" style="display:none;"><img src="/media/images/tt-new/ajax-loader-2.gif" /></span>
				<span class="status1" style="display:none;"></span>
			</div>
			<div id="" class="controls-payment">
 				<button class="btn btn-payment btn-small cancel_span" id="cancel-btn" type="button" onclick="cancel_payment_form();">Cancel</button>
				<button class="btn btn-payment btn-small" id="save-btn" type="button" onclick='payment_gateway_details("<?php echo SITEURL;?>")'><span id="save_span">Submit</span></button> 
			</div>
		</div>
		
		<div class="popup-menu popup-password" id="forgot_password" onclick="show_admin_menu();">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="my-account-popup-arrow">
			
			<div class="my-acc-type bor-rad">
				<label class="select-sales-label heavy">Forgot Password</label>
			</div>
			<div class="layout">
				<label class="layout-label dull">Enter Email</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="text" name="email" id="email-forgot-pass" value="" class="popup-email-input" />
			</div>
			<div class="status-message">&nbsp;
				<span class="loader" style="display:none;"><img src="/media/images/tt-new/ajax-loader-2.gif" /></span>
				<span class="status" style="display:none;"></span>
			</div>
			<div id="controls123" class="controls">
 				<button class="btn btn-small" id="cancel-btn" type="button" onclick="cancel_form_admin();"><span id="cancel_span">Cancel</span></button>
				<button class="btn btn-small" id="save-btn" type="button" onclick="submit_forgot_password();"><span id="save_span">Submit</span></button> 
			</div>
		</div>
		
		<div class="popup_admin" id="reset_password_success">
			<div class="success_message" align="center" style="font-weight:bold;">Password successfully updated and sent via email</div>
			<div align="center" style="margin-top:15px;"><a href="#" class="radius-5 ok-button-api" onclick="cancel_admin_popup('reset_password_success');">OK</a></div>
		</div>
		
		<div class="popup_admin" id="change_password_success">
			<div class="success_message" align="center" style="font-weight:bold;">Password successfully updated</div>
			<div align="center" style="margin-top:15px;"><a href="#" class="radius-5 ok-button-api" onclick="cancel_admin_popup('change_password_success');">OK</a></div>
		</div>
		<!-- 
		<?php  if(empty($_SESSION['company_id'])) {?>
				<div class="adm-footer-trust-wave" style="right: 5px; float: right; clear: both; position: relative; bottom: 0pt; margin-top: 30px;"><script type="text/javascript" src="https://sealserver.trustwave.com/seal.js?style=normal"></script></div>
		<?php } else {?>
				<div style="right: 5px; float: right; clear: both; position: relative; bottom: 0pt; margin-top: 180px;"><script type="text/javascript" src="https://sealserver.trustwave.com/seal.js?style=normal"></script></div>
		<?php } ?>
		
		
			<div class="clear"></div>
		<div class="ae-webapp  clear-trust-logo">an AccountEdge web app</div>
		-->
		<script>
		var i=0;
		$(".dropdown-toggle").click(function(){
			if(i==0)
			{
				$('.dropdown-menu').show();
				i++;
				
			}
			else
			{
				$('.dropdown-menu').hide();
				i--;
			}
		});
		$(document).bind('click', function(e) {
			var $clicked = $(e.target);
			/******Hide Admin/Timetracker Popup******/
			if (($clicked.hasClass("tt-admin")) ||($clicked.hasClass("drop-btn"))) {
				$('.dropdown-menu').show();
				$('#phone-account-edge-outer-header').append($('#dropdown-menu-bottom'));
				var width = $(window).width();
				width = (width-175)+'px';
				$('#dropdown-menu-bottom').css('left',width);
			} 
			else if (($clicked.hasClass("selected-menu"))||($clicked.hasClass("ac-slips-phone"))||($clicked.hasClass("non-selected-menu"))||($clicked.hasClass("tt-admin1")) )
			{
				$('.dropdown-menu').hide();
				i=0;
			}
			else {
				$('.dropdown-menu').hide();
				i=0;
			}
			
		});
		</script>
	</body>
</html>
  