<?php 
	echo HTML::script('media/scripts/custom-form-elements.js');
	echo HTML::script('media/scripts/jquery.validate.pack.js');
	echo html::script('media/scripts/jquery.form.js');
	echo html::script('media/scripts/jquery.color.js');
	echo html::script('media/scripts/jqtransform.js');
	echo html::script('media/scripts/passwordmeter.js');
	echo html::script('media/scripts/json_parse.js');
	echo html::script('media/scripts/jquery.autocomplete.js');
	echo html::style('media/css/jquery.autocomplete.css');

?>
<?php 
	$json_country_list = json_encode($country_list);
	$json_country_list = addslashes($json_country_list);
?>
<script type="text/javascript">

var country_array;
			$(document).ready(function(){
				//$('#myPassword').simplePassMeter();
				var country_list_array = [];
				country_list_array = '<?php echo $json_country_list?>';
				var result = json_parse(country_list_array);
				var html = recursive_parse(result);
				country_array = recursive_parsed_array(result);
				$("form.jqtransform").jqTransform();
				$("#company_create").validate({
					onfocusout: false,
					onclick: false,
					onkeyup: false,

			highlight: function(element, errorClass) {
				$(element).animate( { backgroundColor: 'pink' }, 300)
				.animate( { backgroundColor: '#fff' }, 300)
				.animate( { backgroundColor: 'pink' }, 300)
				.animate( { backgroundColor: '#fff' }, 300);
				$('.valid').css("background-color", "white");			
			}
		
		});	
				$('#all_countries').click(function() { 
					$('#country_list').toggle();
					$('#country_list').html('<ul>'+html+'</ul>');
					//$('.autocomplete').css('left', '340px');
				});			
				
				$('#country').autocomplete(country_array);
				
													
				$('.country_name').live('click',function() {
					$('#country').val($(this).text());
					$('#country_id').val($(this).attr('id'));
					//alert($(this).attr('id'));
					$('#country_list').hide();
				});
				$('#country').live('click',function() {
					$('#country_list').hide();
				});

				Array.prototype.findIndex = function(value){
					var ctr = "";
					for (var i=0; i < this.length; i++) {
					// use === to check for Matches. ie., identical (===), ;
					if (this[i] == value) {
					return i;
					}
					}
					return ctr;
				};
	});
</script>
<div class='error_message' style="display:none;"></div><br/>
<?php
if(isset($error))
{
	echo "<div class='error_message' id='error_service'>".$error."</div><br/>";
}
if(!empty($message) && $message == 2)
{
	echo "<div class='success_message'>Thank you for siging up with AccountEdge Cloud.
				<p>We appreciate your interest. Please check the email address you provided for instructions on how to log in.</p>.</div><br/>";
}
if(!empty($message) && $message == 4)
{
	echo "<div class='success_message'>Company details has been updated.</div><br/>";
}
if($edit && $form['active_status'] == 0)
{
	echo "<div class='success_message' style='color:#405259;'>Please activate this company by clicking on the link sent by AccoundEdge team.</div><br/>";
}
if($edit && isset($_GET['s']) && $_GET['s'] == 1)
{
	echo "<div class='account-reset-alert'>Your account has been suspended successfully</div><br/>";
}
if($edit && isset($_GET['r']) && $_GET['r'] == 1)
{
	echo "<div class='account-reset-alert'>Your account has been resumed successfully</div><br/>";
}
?>
<!-- <form action='<?php echo SITEURL?>/admin/company_add' method="post" id="company_create" class='text_normal' name="admin_form">-->
	<?php
	/* if(!empty($_GET['statusMessage'])) echo "<div class='error_message'>".$_GET['statusMessage']."</div>";
	if(!empty($error)) echo "<div class='error_message'>".$error."</div>"; */
	?><!-- 
	<div class="table-wrapper">
	<table>
		<tr>
			<td class="td-first">Company Name</td>
			<td><input type="text" class="input-1" name="company_name" id="company_name" maxlength='50' value="<?php //echo $company_info['name'];?>"/></td>
		</tr>
		<tr>
			<td class="td-first">Serial Number</td>
			<td><input type="text" class="input-1" name="serialnumber" id="serialnumber" maxlength='50' value="<?php //echo $company_info['serialnumber'];?>"/></td>
		</tr>
		<tr>
			<td class="td-first">Email Address</td>
			<td><input type="text" class="input-1" name="UserEmail" id="UserEmail" maxlength='50' value="<?php //echo $company_info['email'];?>"/></td>
		</tr>
		<tr>
			<td class="td-first">Password</td>
			<td><input type="password" class="input-1" name="Password" id="Password" maxlength='15' value="" /></td>
		</tr>
	</table>
	</div>-->
	
	<div style="float: none; margin: 0 auto; position: relative; left: 0;margin:0 auto 20px; min-height:100%;"> 
                <form action='<?php echo SITEURL?>/admin/company_add' method="post" id="company_create" class='text_normal jqtransform' name="admin_form">
					<div class='error_message' id="error_signup" style="display:none"></div><br/>
                    <!-- MAIN CONTENTS BEGINS -->
                    <div class="merchant-register">
                        <!-- inner con -->
                       
                       <div class="coloum-con">
                            
                            <div class="clear">
                            </div>
                        </div>
                        
                        <?php 
                        if(!$edit) {
                        
                        ?>
                        <div id="payment_stream">
                       
                       
                        <div class="inbox-container-dd">
                                    <div class="inbox-top-left"><div class="inbox-top-right"><div class="inbox-top-mid"></div></div></div>
                                    <div class="inbox-mid-left">
                                        <div class="inbox-mid-right">
                                            <div class="inbox-mid-center">
                                            	<div class="plan-box">
													<h3>Choose Your Plan</h3>
													<div id="err"></div>
													
													<?php 
													if(!empty($payment_stream)) {
														foreach($payment_stream as $plan) { ?>
															<div class="plan-row">
																<div class="choose-plan-signup">
																	<input class="" type="radio" name="plan_check" id = "plan_check" value="<?php echo $plan['plan_id']; ?>" onclick="javascript:showPlan('<?php echo $plan['plan_id']; ?>');"/>
																	<input type="hidden" name="plan" id="plan" value="" />
																</div>
																<div class="plan-details">
																	<div class="plan-title">
																		<div class="left" id="plan_name_<?php echo $plan['plan_id'];?>">
																			<?php echo $plan['name']; ?>
																		</div>
																		<div class="right">
																			$ <?php echo $plan['price']; ?>
																		</div>
																		<div class="clear"></div>
																	</div>
																	<p>Sample text</p>
																</div>
																<div class="clear"></div>
															</div>
														<?php   
														}
													}
													?><div style="float:right;">
														<button class=" jqTransformButton" type="button" name="" id="" onclick="check_plan();"><span><span>Next</span></span></button>
													</div>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="inbox-bottom-left"><div class="inbox-bottom-right"><div class="inbox-bottom-mid"></div></div></div>
                                </div>
                       
                       <!-- 
	                       <div class="inbox-container-dd radius">
	                            <h3>Payment Plan</h3>
	                            <div class="information-box">
	                                <label>Select Signup Plan</label>
	                                <div class="edit-con">
	                                    <span class="txt-field">
	                                    	<select name="plan" class="large required" id="plan">
	                                    		<option value="0">-Select-</option>
		                                    	<?php 
		                                    	/*if(!empty($payment_stream)) {
			                                    	foreach($payment_stream as $plan) {
		                                    			echo "<option value='".$plan['plan_id']."'>".$plan['name']."</option>";
		                                    		}
		                                    	}*/
		                                    	?>
	                                    	</select>
	                                    </span>
	                                    <button class=" jqTransformButton" type="button" name="" id="" onclick="check_plan();"><span><span>Next</span></span></button>
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                         -->
                       </div>
                       
                       <div id="payment_form" style="display:none;">
                       
	                       <div class="inbox-container-dd radius">
	                            <h3>Credit Card Information</h3>
	                            <div class="information-box">
	                                  <div class="edit-con" id="payment_form_layer">
	                                   
	                                    	<iframe src="" id="payment_frame" height="200" width="420" style="border:0px solid #000000; "></iframe>
	                                    
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        
                       </div>
                       
                       
                       <div id="signup_success" style="display:none;">
                       
	                       <div class="inbox-container-dd radius">
	                            <h3>Payment Success</h3>
	                            <div class="information-box">
	                                  <div class="edit-con" id="payment_form_layer">
	                                   <div class='success_message'>Thank you for siging up with AccountEdge Cloud.
										<p>We appreciate your interest. Please check the email address you provided for instructions on how to log in.</p>.</div>								
                                    
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        
                       </div>
                        
                         <div id="free_user_form" style="display:none;">
                       
	                       <div class="inbox-container-dd radius">
	                            <h3>Account Information</h3>
	                            <div class="information-box">
	                                  <div class="edit-con" id="payment_form_layer">
	                                  
	                                    <label>
	                                    	Company name
		                                </label>
		                                <div class="edit-con">
		                                    <span class="txt-field"><input autocomplete="off" class="large" type="text" name="free_company_name" id="free_company_name" maxlength='50' value="<?php echo $form['company_name']; ?>" /></span>
		                                    <div class="clear">
		                                    </div>
		                                </div>
	                                  
	                                   	<label>
	                                    	Your email
	                                	</label>
	                                <div class="edit-con">
	                                    <span class="txt-field"><input autocomplete="off" class="large" type="text" name="free_UserEmail" id="free_UserEmail" maxlength='50' value="<?php echo $form['UserEmail']; ?>" /></span><span class="txt-msg">Your email address be used for log in purpose. </span>
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                                <!-- txt end--><!-- txt -->
	                                <label>
	                                    Desired password (6-15 Characters)
	                                </label>
	                                    <div class="edit-con">
											<span class="txt-field"><input id="free_myPassword" name="free_password" class="large" type="password" maxlength='15' onkeyup="testPassword($(this).val())"/></span>
												<span class="txt-msg">
													<div class="password-con">
														<div class="pass-sider"></div>
													</div>
													<span class="pass-txt">Password Strength</span>
												</span>
											<div class="clear"></div>
										</div>
	                                    
	                                   <!--  <span class="txt-field"><input autocomplete="off" class="large required" id="pass" type="password" name="Password" maxlength='15' value="" onkeyup="passwordStrength(this.value)"/></span>
	                                        <div class="password-con">
	                                            <div class="pass-sider">
	                                            </div>
	                                        </div>
	                                        <div class="pass-meter">
	                                        <div id="passwordStrength" class="strength0"></div>
	                       					 <div id="passwordDescription">Password not entered</div>
											</div>
	                                    <div class="clear">
	                                    </div>-->
	                                <!-- txt end--><!-- txt -->
		                                <label>Confirm password</label>
		                                <div class="edit-con">
		                                    <span class="txt-field"><input autocomplete="off" class="large" type="password" name="free_confirmpass" id="free_confirmpass" maxlength='15' value="" /></span>
		                                    <div class="clear">
		                                    </div>
		                                </div>
	                                    <input type="hidden" name="free_user" id="free_user" value="0" />
	                                    <span class="right"><button class=" jqTransformButton" type="submit" name="" id="" onclick="return submit_signup_form(this,'<?php echo SITEURL;?>');"><span><span>Sign Up</span></span></button></span>
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        
                       </div>
                        
                        <?php 
                        	$style_company_info	=	"display:none";
                        }
                        else {
                        	$style_company_info	=	"";
                        }
                        ?>
                        
                        
                        
                        <!-- inner con -->
                        <div id="company_info" style="<?php echo $style_company_info;?>;">
                        <div class="plan-box radius">
                            <!-- pan1 -->
                            <div class="plan-row">
                                <div class="choose-plan">
                                    <img src="../media/images/icon-tip.png" alt="tip" />
                                </div>
                                <div class="plan-details">
                                    <p class="note-txt">
                                        Tip: all fields are mandatory, unless marked otherwise. 
                                        
                                    </p>
                                </div>
                                <div class="clear">
                                </div>
                            </div>
                            <!-- pan1 end -->
                        </div>
                        <!-- inner con -->
                        <div class="inbox-container-dd radius">
                            <h3>Account Information</h3>
                            <div class="information-box">
                             
                                <!-- txt -->
                                <label>
                                    Your Company's name
                                </label>
                                <div class="edit-con">
                                    <span class="txt-field"><input autocomplete="off" class="large required" type="text" name="company_name" id="company_name" maxlength='50' value="<?php echo html_entity_decode($form['company_name']); ?>" /></span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt end-->
								<!-- txt -->
                                <label>
                                    Your AccountEdge Serial Number
                                </label>
                                <div class="edit-con">
                                    <span class="txt-field"><input autocomplete="off" class="large required" type="text" id="serialnumber" name="serialnumber" maxlength='15' value="<?php echo $form['serialnumber']; ?>" /></span><span class="txt-msg">Find your serial number <a href="http://support.accountedge.com/kb/general-accountedge/locating-your-serial-number" class="serial-no-link" target="_blank">here</a>.</span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt end-->
								<!-- txt -->
                                <label>
                                    Your email
                                </label>
                                <div class="edit-con">
                                    <span class="txt-field"><input autocomplete="off" class="large required" type="text" name="UserEmail" id="UserEmail" maxlength='50' value="<?php echo $form['UserEmail']; ?>" /></span><span class="txt-msg">Your email address be used for log in purpose. </span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt end--><!-- txt -->
                                <label>
                                    Desired password (6-15 Characters)
                                </label>
                                     <div class="edit-con">
										<span class="txt-field"><input id="myPassword" name="password" class="large" type="password" maxlength='15' value="<?php echo $form['password']; ?>" onkeyup="testPassword($(this).val())"/></span>
											<span class="txt-msg">
												<div class="password-con">
													<div class="pass-sider"></div>
												</div>
												<span class="pass-txt">Password Strength</span>
											</span>
										<div class="clear"></div>
									</div>
                                <!-- txt end--><!-- txt -->
                                <label>Confirm password</label>
                                <div class="edit-con">
                                    <span class="txt-field"><input autocomplete="off" class="large required" type="password" name="confirmpass" id="confirmpass" maxlength='15' value="<?php echo $form['password']; ?>" /></span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt end-->
                            </div>
                        </div>
                        <!-- inner con -->
                        <div class="inbox-container-dd radius">
                            <h3>Billing &amp; Mailing Address</h3>
                            <div class="information-box">
                                <!-- txt -->
                                <div class="edit-con">
                                    <span class="txt-label">Your name</span>
                                    <span class="txt-field2"><input autocomplete="off" class="large required" type="text" name="name" id="name" maxlength='50' value="<?php echo $form['name']; ?>" /></span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <div class="edit-con">
                                    <span class="txt-label">Last name</span>
                                    <span class="txt-field2"><input autocomplete="off" name="lastname" id="lastname" class="required large" type="text" value="<?php echo $form['lastname']; ?>" /></span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt end--><!-- txt -->
                                <div class="edit-con">
                                    <span class="txt-label">Company name</span>
                                    <span class="txt-field2"><input autocomplete="off" name="cname" id="cname" class="required large" type="text" value="<?php echo $form['cname']; ?>" /></span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt end--><!-- txt -->
                                <div class="edit-con">
                                    <span class="txt-label">Address</span>
                                    <span class="txt-field2"><input autocomplete="off" class="required large" type="text" name="address" id="address" maxlength='50' value="<?php echo $form['address']; ?>" /></span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt end--><!-- txt -->
                                <!--  <div class="edit-con">
                                    <span class="txt-label">&nbsp;</span>
                                    <span class="txt-field2"><input name="yname" class="large required" type="text" value="" /></span>
                                    <div class="clear">
                                    </div>
                                </div>-->
                                <!-- txt end--><!-- txt -->
                                <div class="edit-con">
                                    <span class="txt-label">City</span>
                                    <span class="txt-field2">
                                    	<input class="required large" autocomplete="off" type="text" name="city" id="city" maxlength='50' value="<?php echo $form['city']; ?>" /></span>
                                      </span>
                                    <div class="clear"></div>
                                </div>
                                
                                <!-- txt end--><!-- txt -->
                                 <div class="edit-con">
                                    <span class="txt-label">State</span>
                                    <span class="txt-field2"><input autocomplete="off" class="required large" type="text" name="state" id="state" maxlength='50' value="<?php echo $form['state']; ?>" /></span>
                                    <div class="clear">
                                    </div>
                                </div>
                                 <div class="edit-con">
                                    <span class="txt-label">Zip Code</span>
                                    <span class="txt-field2"><input autocomplete="off" class="required large" type="text" name="zipcode" id="zipcode" maxlength='50' value="<?php echo $form['zipcode']; ?>" /></span>
                                    <div class="clear">
                                    </div>
                                </div>
                                 <div class="edit-con drop-down-pos">
                                    <span class="txt-label">Country</span>
                                    <span class="txt-field2">
                                    <select name="country" id="country">
                                    	<?php 
                               
                                    	foreach($country_list as $country) {
                                    		?>
                                    		<option value="<?php echo $country['id'];?>" <?php if($country['id'] == $form['country']) echo "selected='selected'";?>><?php echo $country['country'];?></option>
                                    		<?php 
                                    	}
                                    	?>
                                    </select>
                                   
									<div class="clear"></div>
									
                                    <div id="country_list" class="autocomplete" style="display:none;"></div>
                                </div>
                               
                                <!-- txt end--><!-- txt -->
                                <div class="edit-con">
                                    <span class="txt-label">Phone number</span>
                                    <span class="txt-field2"><input autocomplete="off" class="required large" type="text" name="phone" id="phone" maxlength='50' value="<?php echo $form['phone']; ?>" /></span>
                                    <span style="position: relative; left: 107px;">(xxx-xxx-xxxx)</span>
                                    <div class="clear">
                                    </div>
                                </div>
                               
                                <!-- txt end-->
                            </div>
                            <div class="inbox-bottom-left">
                                <div class="inbox-bottom-right">
                                    <div class="inbox-bottom-mid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                         <?php 
                                if($edit)
                                {
                                ?>
                                <div class="inbox-container-dd radius">
                                	<h3>Plan Details</h3> 
                                	<div class="information-box">
                                		<div class="edit-con">
			                                    <span class="serial">Created date</span>
			                                    <span class=""><?php echo date("M-d-Y H:i:s", strtotime($form['date_created']));?></span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
                                		<?php 
                                		if(empty($offline) && $dropbox['active_status'])
                                		{
                                		?>
                                			 <div class="edit-con">
			                                    <span class="serial">AccountEdge Device Name</span>
			                                    <span class=""><?php echo $dropbox['device_name'];?></span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
			                                 
			                                 <div class="edit-con">
			                                    <span class="serial">Dropbox Email Address</span>
			                                    <span class=""><?php echo $dropbox['dropbox_email'];?></span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
                                		
                                		
                                			<div class="edit-con">
			                                    <span class="serial">Current Plan</span>
			                                    <span class=""><?php echo ucfirst($current_plan['plan_name']);?></span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
			                                 
			                                 <div class="edit-con">
			                                    <span class="serial">Total Users</span>
			                                    <span class=""><?php echo $company_user_info['total_users'];?></span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
			                                 <div class="edit-con">
			                                    <span class="serial">Active Users</span>
			                                    <span class=""><?php echo $company_user_info['total_users'];?></span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
			                                 <div class="edit-con">
			                                    <span class="serial">Inactive Users</span>
			                                    <span class=""><?php echo $company_user_info['inactive_users'];?></span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
			                               <?php 
                                		} else {
                                		?>
                                			<div class="edit-con">
			                                    <span class="serial">Current Plan</span>
			                                    <span class=""><?php echo ucfirst($current_plan['plan_name']);?></span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
			                                 
			                                 <div class="edit-con">
			                                    <span class="serial">Total Users</span>
			                                    <span class="">0</span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
			                                 <div class="edit-con">
			                                    <span class="serial">Active Users</span>
			                                    <span class="">0</span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
			                                 <div class="edit-con">
			                                    <span class="serial">Inactive Users</span>
			                                    <span class="">0</span>
			                                    <div class="clear">
			                                    </div>
			                                 </div>
                                		<?php }?>
                                		<?php if($form['active_status'] == 1 && $current_plan['plan_price'] != 0) {?>
                                	          		<?php if($form['suspend_status'] == 1) {?>
                                       					 <div class="edit-con">
							                                    <span style="color: red;width: 400px;font-weight:bold;">This user has been suspended on <?php echo date("M-d-Y", strtotime($form['suspend_resume_date']));?></span>
							                                    
							                                    <div class="clear">
							                                    </div>
							                             </div>
                                       					
                                       		<?php }?>
                                		<?php }?>
                                		<?php if($current_plan['plan_price'] == 0) {?>
	                                			 <div class="edit-con">
				                                    <span class="serial">Modify User expire date</span>
				                                    <?php if($form['active_status'] == 0) {?>
				                                    	<span class="">Please activate this company by entering dropbox credentials.</span>
				                                    <?php } else {?>
				                                    	<span class=""><a class="modify_expire" href="#" onclick="show_modify_expire_date_form('<?php echo $form['company_id'];?>');">Click here to modify expire date of this user</a></span>
				                                    <?php }?>
				                                    <div class="clear">
				                                    </div>
				                                 </div>
				                                 
				                                 <div class="edit-con">
				                                    <span class="serial">Free plan expire date</span>
				                                    <?php 
				                                    if(isset($form['expire_date_modify']) && $form['expire_date_modify'] == 0) {
				                                    	$company_end_date	=	$form['default_end_date'];	
				                                    } else {
				                                    	$company_end_date	=	$form['end_date'];
				                                    }
				                                    ?>
				                                    <!-- <input type="label" name="expire_end_date" value="" id="expire_end_date" readonly="true" /> -->
				                                    <span class=""><?php echo date("M-d-Y H:i:s", strtotime($company_end_date));?></span>
				                                    
				                                    <div class="clear">
				                                    </div>
				                                 </div>
			                            <?php } ?>
			                            	
                                			<div class="edit-con">
	                                			<span class="right"><button class=" jqTransformButton" type="submit" name="" id=""><span><span>Update</span></span></button></span>
	                                			<span class="right"><button class=" jqTransformButton" type="button" name="" id="" onclick="location.href='<?php echo SITEURL?>/admin'"><span><span>Cancel</span></span></button></span>
	                                       		<span class="right"><a href="#"><button class=" jqTransformButton" type="button" name="" id="" onclick="delete_alert('delete_company');"><span><span>Delete</span></span></button></a></span>
	                                       		<?php if($form['active_status'] == 1 && $current_plan['plan_price'] != 0) {?>
	                                       					<?php if($form['suspend_status'] == 0) {?>
	                                       							<span class="right"><button class=" jqTransformButton" type="button" name="" id="" onclick="resume_suspend_user_plan('<?php echo SITEURL?>', '<?php echo $form['company_id'];?>', 0);"><span><span>Suspend</span></span></button></span>
	                                       					<?php } else {?>
	                                       							<span class="right"><button class=" jqTransformButton" type="button" name="" id="" onclick="resume_suspend_user_plan('<?php echo SITEURL?>', '<?php echo $form['company_id'];?>', 1);"><span><span>Resume</span></span></button></span>
	                                       					<?php }?>
	                                       		<?php }?>
	                                			<div class="clear"></div>
                                			</div>
                                		
                                	</div> 
                                </div>
                                <?php 
                                }
                                ?>
                        <!-- inner con --><!-- inner con -->
                        <?php 
                        if(!$edit)
                        {
                        ?>
                        <div class="inbox-container-dd radius">
                            <h3>Come On In</h3>
                            <div class="information-box">
                                <!-- txt -->
                                <div class="edit-con">
                                	<span class="txt-field2"><input class="styled" type="checkbox" value="1" id="terms" name="terms" />
	                                    <label class="term-txt" style="margin-left:30px;">
                                            By clicking Sign Up you agree to the <a href="http://accountedge.com/timetracker/tos" target="_blank" style="font-weight:bold">Terms of Service</a>
                                        </label>
	                                </span>
                                    <span class="right"><button class=" jqTransformButton" type="button" name="" id="" onclick="return submit_signup_form(this,'<?php echo SITEURL;?>');"><span><span>Sign Up</span></span></button></span> <!-- onclick="return submit_signup_form(this,'url');"-->
									<div class="clear">
                                    </div>
                                </div>
                                <!-- txt end-->
                            </div>
                        </div>
                        <?php 
                        }
                        ?>
                        
                        <!-- inner con -->
                        <!-- MAIN CONTENTS ENDS -->
                        </div>
                        <div class="clear">
                        </div>
                    </div>
                    <input type="hidden" name="signup_admin" value="1" />
                    <?php 
                    if($edit)
                    {
                    	?>
                    		<input type="hidden" name="company_id" value="<?php echo $form['company_id'];?>" id="company_id" />
                    	<?php 
                    }
                    ?>
                    <?php if(isset($offline)) {?>
                    		<input type="hidden" name="offline" value="1" />
                    <?php }?>
                </form>
                <!-- MAIN CONTAINER ENDS -->
                <div class="empty">
                </div>
            </div>
<div class="popup_admin" id='signup_status' style="display:none;margin-top:10%;"> 	
	<div class="wait-message" style="font-weight:bold;text-align:center;">Please wait
		<img src="<?php echo SITEURL?>/media/images/loading.gif" />
	</div>
</div>
