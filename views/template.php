<!-- 
  @File : template.php View
  @Description: This file acts as a template for timetracker module.
  Copyright (c) 2012 Acclivity Group LLC 
 -->
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />
		<title>AccountEdge Cloud</title>
		<?php 
		//echo html::style('media/css/default.css',array('screen'),false);
		echo HTML::script('media/scripts/jquery-1.7.1.min.js');
		echo html::style('media/css/bootstrap.css');
		echo html::style('media/css/bootstrap.min.css');
		echo html::style('media/css/bootstrap-responsive.css');
		echo html::style('media/css/jScrollPane.css');
		echo html::style('media/css/jqtransform.css');
		echo html::script('media/scripts/jquery.mousewheel.min.js');
		echo html::script('media/scripts/jScrollPane.js');
		echo html::script('media/scripts/main.js');
		echo html::script('media/scripts/common.js');
		echo html::script('media/scripts/custom-form-elements.js');
		echo html::style('media/css/time_tracker_user.css');
		echo html::style('media/css/jquery.autocomplete.css');
		echo HTML::script('media/scripts/bootstrap.js');
		echo HTML::script('media/scripts/bootstrap.min.js');
		echo html::style('media/css/reset.css');
		echo HTML::style('media/scripts/jquery-ui/css/ui-lightness/jquery-ui-1.8.4.custom.css'); 	
		echo HTML::script('media/scripts/jquery-ui/development-bundle/ui/jquery-ui-1.8.4.custom.js');
		?>
		<!-- New Date Picker CSS and JS starts -->
        <?php 
        echo html::style('media/css/datePicker.css');
        ?>
        <script type="text/javascript" src="/media/scripts/date.js"></script>
        <script type="text/javascript" src="/media/scripts/jquery.autocomplete.js"></script>
        <script type="text/javascript" src="/media/scripts/jquery.datePicker.js"></script>
		<!-- New Date Picker CSS and JS ends -->
		<link rel="shortcut icon" href="/media/images/tt-new/Logo.ico" />
		<script src="<?php echo SITEURL?>/media/scripts/bootstrap.min.js"></script>
		
		<script type="text/javascript">
			$(function (){
				$.Global.SITEURL = '<?php echo SITEURL; ?>';
				<?php //$_SESSION['country'] = 0; // US 
				//$_SESSION['country'] = 1; // non-US
				if(isset($_SESSION['country']) ){?>
					$.Company.COUNTRY = <?php echo $_SESSION['country']; ?>;
				<?php } else {  //default is US ?>
					$.Company.COUNTRY = $.USA;
				<?php } ?>
			});
		</script>
		
			<script type="text/javascript">
				$(document).ready(function() {
					$('input').attr('autocomplete','off');
					if($(window).width() < 390) {
					//$(".tick-mark1").hide();
					$(".tick-mark2").hide();
					$(".phone-account-edge-outer-header").show();
					$(".account-edge-outer-header").hide();
					$(".ac-identifier-label-phone").show();
					$(".ac-identifier-label").hide();
					$("#my-account").hide();
					
				}
				else if(($(window).width() > 390) && ($(window).width() < 500)  ) {
					//$(".tick-mark1").hide();
					$(".tick-mark2").hide();
					$(".phone-account-edge-outer-header").show();
					$(".account-edge-outer-header").hide();
					$(".ac-identifier-label-phone").show();
					$(".ac-identifier-label").hide();
					$("#my-account").hide();
					
				}
				else if(($(window).width() > 500) && ($(window).width() <= 700)  ) {
					//$(".tick-mark1").hide();
					$(".tick-mark2").hide();
					$(".phone-account-edge-outer-header").show();
					$(".account-edge-outer-header").hide();
					$(".ac-identifier-label-phone").show();
					$(".ac-identifier-label").hide();
					$("#my-account").hide();
					
				}
				else {
					$(".phone-account-edge-outer-header").hide();
					$(".account-edge-outer-header").show();
					$(".ac-identifier-label-phone").hide();
					$(".ac-identifier-label").show();
					$("#my-account").show();
				}
				$(window).resize(function() {
					var width = $(window).width();
					width = (width-175)+'px';
					$('#dropdown-menu-bottom1').css('left',width);
					if($(window).width() < 390) {
						//$(".tick-mark1").hide();
						$(".tick-mark2").hide();
						$(".phone-account-edge-outer-header").show();
						$(".account-edge-outer-header").hide();
						$(".ac-identifier-label-phone").show();
						$(".ac-identifier-label").hide();
						$("#my-account").hide();
						
					}
					else if(($(window).width() > 390) && ($(window).width() < 500)  ) {
						//$(".tick-mark1").hide();
						$(".tick-mark2").hide();
						$(".phone-account-edge-outer-header").show();
						$(".account-edge-outer-header").hide();
						$(".ac-identifier-label-phone").show();
						$(".ac-identifier-label").hide();
						$("#my-account").hide();
					}
					else if(($(window).width() > 500) && ($(window).width() <= 700)  ) {
						//$(".tick-mark1").hide();
						$(".tick-mark2").hide();
						$(".phone-account-edge-outer-header").show();
						$(".account-edge-outer-header").hide();
						$(".ac-identifier-label-phone").show();
						$(".ac-identifier-label").hide();
						$("#my-account").hide();
						
					}
					else {
						$(".phone-account-edge-outer-header").hide();
						$(".account-edge-outer-header").show();
						$(".ac-identifier-label-phone").hide();
						$(".ac-identifier-label").show();
						$("#my-account").show();
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
			 	if(isset($_SESSION['company_id'])) {
			?>			
					<div id="account-edge-outer-header" class="account-edge-outer-header" >
					<div id="container4">
						<div id="container3">
						    <div id="container2">
						        <div id="container1">
						            <div id="first-column" <?php if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "0") { echo "style = 'width:73%;'";}?>><a href="<?php echo SITEURL?>"><img src="<?php echo SITEURL?>/media/images/tt-new/header_logo.png" class="highDefinition inner-logo" id="account-edge-logo" /></a></div>
						            <a href="<?php echo SITEURL?>/activitysheet" onclick="header(this,1)" >
    						            <div id="second-column" <?php if(empty($tab)) echo 'class="timetracker-active"';?>>
    						            	<label class="header-label1">Time Tracker</label>
    						            </div>
						            </a>
						            <a href="<?php echo SITEURL?>/sales" onclick="header(this,2)">
    						            <div id="third-column" <?php if(isset($tab) && $tab == 2) echo 'class="timetracker-active"';?>>
    						            	<label class="header-label2" <?php if(isset($_SESSION['group']) && !empty($_SESSION['group']) && $_SESSION['group']==2) echo "onmouseover=show_versioning_message(this);";?> >Sales</label>
    						            </div>
						            </a>
						            <?php if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {?>
										<a href="<?php echo SITEURL?>/admin">
											<div id="col4">
												<label class="header-label2">Admin</label>
											</div>
										</a>
									<?php }?>
								<!-- 	<a href="<?php SITEURL?>/sales/tobesynced" class="to_be_synced" onclick="">
										<div id="col5" class="col5_synced">
    										<label class="header-label2">To Be Synced
        										<div class="synced-value-field-header">
                                                19
                                                </div>
    										</label>
										</div>
									</a> -->
								</div>
						    </div>
						</div>
					</div>
				</div>
				
				<div id="phone-account-edge-outer-header" class="phone-account-edge-outer-header navbar-fixed-top" style="display:none">
					<div id="cloud-img"><a href="<?php echo SITEURL?>"><img src="<?php echo SITEURL?>/media/images/tt-new/aec-logo.png" width="33" height="23" class="cloud cloud-small" /></a>
						<label class="ac-identifier-label-phone">
							<?php 
							if(isset($tab) && $tab == 2) {
								echo "Sales";
							} else {
								echo "Time Tracker";
							}
							?>
						</label>
						 <div id="phone-toggle-bar" >
							<div class="btn-group">
								<a class="dropdown-toggle"  href="#">
									<button class="drop-btn timetracker">
										<img src="<?php echo SITEURL?>/media/images/tt-new/lines-img.png" id="icon-list">
									</button>
								</a>
				  
								<ul class="dropdown-menu dropdown-menu-bottom" id="dropdown-menu-bottom1">
								<img src="<?php echo SITEURL?>/media/images/tt-new/account-arrow.png" class="my-account-popup-arrow">
									<li id="time-tracker-ltem" class="ac-timetracker-active" onclick="headerPhone(this,1)" ><a href="<?php echo SITEURL?>/activitysheet" class="slips-number">
										<label class="ac-slips-phone">
										<?php if(empty($tab)) {?>											
											<img src="<?php echo SITEURL?>/media/images/tt-new/tick.png" class="tick-mark">
										<?php } ?>
										Time Tracker</label></a>
									</li>
									<li id="sales-item" <?php if(isset($_SESSION['group']) && !empty($_SESSION['group']) && $_SESSION['group']==2) echo "onmouseover=show_versioning_message(this);";?> onclick="headerPhone(this,2)"><a href="<?php echo SITEURL?>/sales"class="slips-number">
										<label class="ac-slips-phone">
										<?php if(isset($tab) && $tab == 2) {?>
											<img src="<?php echo SITEURL?>/media/images/tt-new/tick.png" class="tick-mark1">
										<?php }?>
										Sales</label></a>
									</li>
									<?php if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {?>
										<li id="admin-item" onclick="headerPhone(this,3)"><a href="<?php echo SITEURL?>/admin"class="slips-number"><label class="ac-slips-phone"><img src="<?php echo SITEURL?>/media/images/tt-new/tick.png" class="tick-mark2">Admin</label></a></li>
									<?php }?>
								</ul>
							</div>
	  					</div> 
	  					<?php echo View::factory("company/user_myaccount_phone");
	  					?>
					</div>
				</div>
			
				<div id="outer-div">
					<div id="ac-identifier">
						<?php if(isset($tab) && $tab == 2) { ?>
								<label class="ac-identifier-label">Sales</label>
						<?php } else {?>
								<label class="ac-identifier-label">Time Tracker</label>
						<?php }?>
						<?php 
						//echo View::factory("company/user_menu");
			 			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == 1){
							echo View::factory("admin/myaccount_menu");	
			 			} else {
							echo View::factory("company/user_menu");
						}
						?>
					</div>
			<?php 
			 	} else { ?>
			 		<div id="outer-div">
			 <?php }?>
					<?php
						if(isset($content)){ 
							echo $content;
						}?>
			</div>
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
				$('#phone-account-edge-outer-header').append($('#dropdown-menu-bottom1'));
				var width = $(window).width();
				width = (width-175)+'px';
				$('#dropdown-menu-bottom1').css('left',width);
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
			<div id="grayout" class='grayBG'></div>
			<!--  display of slips in activity add page. -->
			<div class="popup_alert" id='sync_all_popup'>
		 		<div class="alert-pop-up">
		 			<p class="question">Do you want to sync all unsynchronized activity slips?</p>
		 			<p class="message">Syncing all activity slips will make them non-editable. Be careful
						as this action cannot be undone.</p>
 				</div>
				<a href="javascript:void(0);" onclick="sync_submit('syncall')"  class="radius-5 button-1 right">Sync All</a>
				<a href="javascript:void(0);" onclick="close_popup('sync_all_popup')" class="radius-5 button-1 right">Don't Sync</a>
			</div>
					
			<div class="popup_alert" id='sync_selected'>
		 		<div class="alert-pop-up">
		 			<p class="question">You are about to Sync this&nbsp;<span class="sync-alert-title">Activity Slips</span></p>
		 			<p class="message">Syncing selected activity slips will make them non-editable. Be careful
						as this action cannot be undone.</p>
 				</div>
				<a href="javascript:void(0);" onclick="sync_submit('sync')"  class="radius-5 button-1 right">Sync Selected</a>
				<a href="javascript:void(0);" onclick="close_popup('sync_selected')" class="radius-5 button-1 right">Don't Sync</a>
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
				<input type="password" name="new_password" value="" class="change_fields" id="new_password" />
			</div>
			
			<div class="layout">
				<label class="layout-label dull">Confirm Password</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" onclick="" align="center">
				<input type="password" name="confirm_password" value="" class="change_fields" id="confirm_password" />
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
			
			
			
			<div class="popup_alert" id='sync_selected_error'>
		 		<div class="alert-pop-up">
		 			
		 			<p class="message">Please select any slip from the list.</p>
 				</div>
				
				<a href="javascript:void(0);" onclick="close_popup('sync_selected_error')" class="radius-5 button-1 right">Ok</a>
				
			
			</div>
			
		<div class="overlay"></div>
		<div class="popup">
	<!-- 	<img src="/media/images/tt-new/popup-arrow.png" class="popup-arrow-image">  -->
			<div class="popup-content radius-5">
				
				<div class="txt-search radius-154" id='div_search_text' ><input type="text" id='search_text' class="" /></div>
				<h2 class="popup-title" id='popup_title' >Jobs</h2>
				<div class="clear"></div>
				
				<ul class="popup-list scroll-pane" id='popup_content' style="background-color: #ffffff;">
					
				</ul>
			</div>
		</div>
	
		<div class="confirmation-box" id="delete_sales_confirm" style="display:none">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="popup-arrow-input">
			<div class="sales-type">
				<label class="select-sales-label heavy">Delete Selected</label>
			</div>
			<div class="confirmation_buttons">
				<div class="cnf-delete-btn" onclick="delete_after_confirm_sales();">
					Delete
				</div>
				
				<div class="cnf-cancel-btn" onclick="cancel_confirmation_box();">
					Cancel
				</div>
			</div>
			<input type="hidden" id="delete_functionality" value="" />
			<input type="hidden" id="delete_box_sale_id" value="" />
		</div>
		
		<div class="confirmation-box" id="sync_sales_confirm" style="display:none">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="popup-arrow-input">
			<div class="sales-type">
				<label class="select-sales-label heavy">Synchronize</label>
			</div>
			<div class="confirmation_buttons">
				<div class="sync-confirm pointer btn2" onclick="sync_after_confirm();">
					Sync
				</div>
				
				<div class="sync-confirm-cancel edit-label pointer btn1" onclick="cancel_confirmation_box();">
					Cancel
				</div>
			</div>
			<input type="hidden" id="sync_functionality" value="" />
			<input type="hidden" id="sale_id" value="" />
		</div>
		
		<div class="sync-slip-confirm" id="sync_slip_confirm" style="display:none">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="popup-arrow-input1">
			<div class="sync-slips-label">Sync Slip</div>
			<div class="confirmation_buttons">
				<div class="sync-confirm" onclick="sync_selected_slip();" onclick="sync_submit('syncall');">
					Sync
				</div>
				
				<div class="sync-cancel"  onclick="cancel_confirmation_box();">
					Cancel
				</div>
			</div>
			<input type="hidden" id="sync_slip_id" value="" />
			<input type="hidden" id="sync_slip_view_page" value="0" />
		</div>
		
		
		<div class="confirmation-box" id="delete_slips_confirm" style="display:none">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="popup-arrow-input">
			<div class="sales-type">
				<label class="select-sales-label heavy">Delete Selected</label>
			</div>
			<div class="confirmation_buttons">
				<div class="delete-btn" onclick="delete_activity_slip();">
					<label class="delete-label">Delete</label>
				</div>
				
				<div class="edit-btn" onclick="cancel_confirmation_box();">
					<label class="edit-label">Cancel</label>
				</div>
			</div>
			<input type="hidden" id="delete_slip_id" value="" />
		</div>
		
		<div class="confirmation-box" id="delete_customer_jobs_confirm" style="display:none">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="popup-arrow-input">
			<div class="sales-type">
				<label class="select-sales-label heavy">Delete</label>
			</div>
			<div class="confirmation_buttons">
				<div class="delete-btn" onclick="delete_after_confirm();">
					<label class="delete-label">Delete</label>
				</div>
				
				<div class="edit-btn" onclick="cancel_confirmation_box();">
					<label class="edit-label">Cancel</label>
				</div>
			</div>
			<input type="hidden" id="delete_functionality" value="" />
			<input type="hidden" id="field_id" value="" />
			<input type="hidden" id="type" value="" />
		</div>
		
		<div class="popup-menu popup-password" id="forgot_password" onclick="show_admin_menu();">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="my-account-popup-arrow">
			
			<div class="my-acc-type bor-rad">
				<label class="select-sales-label heavy">Forgot Password</label>
			</div>
			<div class="layout">
				<label class="layout-label dull">Enter Email</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" align="center">
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
		
		
		<div class="sync-slip-confirm refresh" id="refresh_list_confirm" style="display:none">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="popup-arrow-input1">
			<div class="sync-slips-label">Refresh List</div>
			<div class="confirmation_buttons">
				<a href="<?php echo SITEURL?>/activity/importcustomer/1">
					<div class="sync-confirm">
						<label>Refresh</label>
					</div>
				</a>
				<div class="sync-confirm-cancel" onclick="cancel_confirmation_box();" >
					Cancel
				</div>
			</div>
			
		</div>
		
		
		
	</body>
</html>

