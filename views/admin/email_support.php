<script type="text/javascript" src="<?php echo SITEURL?>/media/scripts/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="<?php echo SITEURL?>/media/scripts/custom-form-elements.js"></script>

<div class="wrapper  over-visible" id="wrapper">
			<div class="overlay-admin"></div>
			<div class="header">
				<div class="admin-logo">
					<h1 class="logo"><a href="<?php echo SITEURL?>/admin">Time Tracker</a></h1>
					<div class="clear"></div>
				</div>
				<div class="back-button left"><a href="<?php echo SITEURL?>/admin">Admin</a></div>
				<div class="admin-title left">Email Support</div>
				<!--BEGIN NAVIGATION-->
				
				<div class="right" style="margin-top: 5px;">
					<a href="#" class="btn-1 right mright40 mbottom0" id="account-button">
						<span class="bgleft"><span class="bgright"><span class="bgmid">My Account</span></span></span>
					</a>
					<!--pop up-->
					<div class="popup-menu">
						<div class="pointer"></div>
						<ul>
							<li class=""><a href="<?php echo SITEURL?>/admin">Admin</a></li>
							<li class=""><a href="<?php echo SITEURL?>/admin/settings">Settings</a></li>
							<li class=""><a href="http://support.accountedge.com/kb/time-tracker" target="_blank">FAQ</a></li>
							<li class=""><a href="http://support.accountedge.com/discussions/time-tracker" target="_blank">Forum</a></li>
							<li class=""><a href="<?php echo SITEURL?>/admin/supportadmin">Email Support</a></li>
							<li class=""><a href="<?php echo SITEURL?>/admin/loginasuser">Log in as User</a></li>
							<li class=""><a href="<?php echo SITEURL?>/admin/logout">Log out</a></li>
						</ul>
					</div>
					<!--pop up ends-->
				</div>
				<div class="clear"></div>
			</div>
			<div class="background">
				<div class="middle" id="middle">
					<div class="block">
						
							<div class='error_message' style="display:none;" id="es_error"></div>
							<form action='<?php echo SITEURL;?>/activity/create' method="post" id="create" >
						
							<div class="new-activity">
							
							<div class="clear"></div>
							
							<div class='error_message' style="display:none;" id="es_error"></div>
							<div class="table-wrapper-es">
								<table cellpadding="0" border="0" cellspacing="0">
									
									<tr>
										<td class="es_td_first">First Name</td>
										<td><input type="text" id='es_admin_firstname' name='es_admin_firstname' value=""<?php echo $company_info['firstname']?>" /></td>
										<td class="td-third">&nbsp;</td>
									</tr>
									
									
								</table>
							</div>
							
							<div class="table-wrapper-es">
								<table cellpadding="0" border="0" cellspacing="0">
									
									<tr>
										<td class="es_td_first">Last Name</td>
										<td>
										<input type="text" id='es_admin_lastname' name='es_admin_lastname' value="<?php echo $company_info['lastname']?>" />
						
										
										</td>
										
									</tr>
									
								</table>
							</div>
							
									<div class="table-wrapper-es">
										<table cellpadding="0" border="0" cellspacing="0">
											
											<tr>
												<td class="es_td_first">Email</td>
												<td>
													<input type="text" name="es_admin_email" id="es_admin_email" value="<?php echo $company_info['email']?>" />
												
												
												</td>
															
											</tr>
											
										</table>
									</div>
							
									<div class="table-wrapper-es">
										<table cellpadding="0" border="0" cellspacing="0">
											<tr>
												<td class="es_td_first">AccountEdge Serial Number</td>
												<td>
												<input type="text" name='es_admin_snum' id='es_admin_snum' value="<?php echo $company_info['serialnumber']?>" maxlength='12' />
												</td>
												
											</tr>
											
										</table>
									</div>
							
							
							<div class="table-wrapper-es">
								<table cellpadding="0" border="0" cellspacing="0">
									<tr>
										<td class="es_td_first">Description</td>
										<td>
										<textarea name='es_admin_description' id='es_admin_description' class='notes' /></textarea>
										</td>
										
									</tr>
								</table>
							</div>
							
							<div class="es-admin-button">
								<a class="admin-button button_class" href="javascript:void(0);" name='submit' id='es_submit' onclick="submit_email_supportadmin('<?php echo SITEURL?>')">Send</a>
								<a class="admin-button button_class" href="javascript:void(0);" name='cancel' id='cancel' onclick="cancel_form('cancel_user_edit');">Cancel</a>
								
							</div>
							
						</div>
					</form>
						
					</div>
				</div>
			</div>
		</div>
		<div class="popup_admin" id='submit_process_admin' style="display:none;"> 	
			<div class="wait-message" style="font-weight:bold;text-align:center;">
				<span style="position:relative;top:-19px;">Please wait</span>
				<img src="<?php echo SITEURL?>/media/images/loading.gif" />
			</div>
		</div>
			
		<div class="popup_admin" id='es_success_admin'>
	 		<div class="alert-pop-up">
	 			<p class="message" style="color:">You have successfully submitted the email support form. We will get in touch with you shortly.</p>
	 		</div>	
			<a href="<?php echo SITEURL?>/admin" class="radius-5 alert-save right">OK</a>
		</div>