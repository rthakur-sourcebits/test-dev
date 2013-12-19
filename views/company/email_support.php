<div class="wrapper" id="wrapper">
			<div class="header">
				<div class="nav-left">
					<a href="javascript:void(0);" onclick="cancel_email_support()" class="slips">Slips</a>
					<span class="clear"></span>
				</div>
					<div class="page-title">Email Support</div>
				<div class="right-nav">
					<div class="add-activity-slip" style="margin-right:0 !important;"><a href="javascript:void(0);" onclick= "cancel_email_support()"><span>+</span> Activity Slip</a></div>
					<span class="new-span cancel-view"><a href="javascript:void(0);" class="cancel" onclick="cancel_email_support()" >Cancel</a></span>
					<span class="es-send"><a href="javascript:void(0);"  onclick='submit_email_support("<?php echo SITEURL;?>")' class="es-send-a">Send</a></span>
				</div>
				<div class="clear"></div>
			</div>
			<div class="background">
					<div class="middle" id="middle">
						<div class="block">
						<form action='<?php echo SITEURL;?>/activity/create' method="post" id="create" >
						
							<div class="new-activity">
							
							<div class="clear"></div>
							
							<div class='error_message' style="display:none;" id="es_error"></div>
							<div class="table-wrapper">
								<table cellpadding="0" border="0" cellspacing="0">
									<?php 
										if(empty($user_info[0]['first_name'])) {
											$firstname	=	"";
										} else {
											$firstname	=	$user_info[0]['first_name'];
										}
									?>
									<tr>
										<td class="td-first">First Name</td>
										<td><input type="text" id='es_firstname' name='es_firstname' value="<?php echo $firstname;?>" /></td>
										<td class="td-third">&nbsp;</td>
									</tr>
									
									
								</table>
							</div>
							
							<div class="table-wrapper">
								<table cellpadding="0" border="0" cellspacing="0">
									<?php 
										if(empty($user_info[0]['last_name'])) {
											$lastname	=	"";
										} else {
											$lastname	=	$user_info[0]['last_name'];
										}
									?>
									<tr>
										<td class="td-first">Last Name</td>
										<td>
										<input type="text" id='es_lastname' name='es_lastname' value="<?php echo $lastname;?>" />
						
										
										</td>
										
									</tr>
									
								</table>
							</div>
							
									<div class="table-wrapper">
										<table cellpadding="0" border="0" cellspacing="0">
											<?php 
												if(empty($user_info[0]['email'])) {
													$email	=	"";
												} else {
													$email	=	$user_info[0]['email'];
												}
											?>
											<tr>
												<td class="td-first">Email</td>
												<td>
													<input type="text" name="es_email" id="es_email" value="<?php echo $email;?>" />
												
												
												</td>
															
											</tr>
											
										</table>
									</div>
							
									<div class="table-wrapper">
										<table cellpadding="0" border="0" cellspacing="0">
											<tr>
												<td class="td-first">AccountEdge Serial Number</td>
												<td>
												<input type="text" name='es_serial_number' id='es_serial_number' value="<?php echo $serial_number;?>" maxlength='12' />
												</td>
												
											</tr>
											
										</table>
									</div>
							
							
							<div class="table-wrapper">
								<table cellpadding="0" border="0" cellspacing="0">
									<tr>
										<td class="td-first">Description</td>
										<td>
										<textarea name='es_description' id='es_description' class='notes' /></textarea>
										</td>
										
									</tr>
								</table>
							</div>
						</div>
					</form>
			</div>
					</div>
			</div>
			<?php if(isset($_SESSION['free_user']) && $_SESSION['free_user'] == 1) {?>
					<div class="free-expire-alert">
						You have <?php echo $_SESSION['days_left']?> day(s) left. Please contact your company
						Administrator to upgrade your plan.
					</div>
			<?php }?>
		</div>

	<!--  display of slips in activity add page. -->
	<div class="popup_alert" id='es_cancel_confirm'>
 		<div class="alert-pop-up">
 			<p class="question">Do you want to save the changes you made in this page?</p>
 			<p class="message">Your changes will be lost if you don't save them.</p>
 		</div>
		
		<a href="javascript:void(0);" onclick="close_popup('es_cancel_confirm')" class="radius-5 button-1 left">Cancel</a>
		<a href="javascript:void(0);" onclick="submit_email_support('<?php echo SITEURL;?>')"  class="radius-5 alert-save right">Save</a>
		<a href='<?php echo SITEURL.'/activitysheet';?>' class="radius-5 button-1 right" style="margin-left: 0px;" >
		Don't Save
		</a>
	
	</div>
	
	<div class="popup_alert" id='es_success'>
 		<div class="alert-pop-up">
 			<p class="message" style="color:">You have successfully submitted the email support form. We will get in touch with you shortly.</p>
 		</div>	
		<a href="<?php echo SITEURL?>/activitysheet" class="radius-5 alert-save right">OK</a>
	</div>
	
	<div class="popup_alert" id='submit_process' style="display:none;"> 	
		<div class="wait-message" style="font-weight:bold;text-align:center;">
			<span style="position:relative;top:-19px;">Please wait</span>
			<img src="<?php echo SITEURL?>/media/images/loading.gif" />
		</div>
	</div>