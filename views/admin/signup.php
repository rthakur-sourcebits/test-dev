<!-- <?php 
//echo HTML::script('media/scripts/custom-form-elements-2.js');
echo html::style('media/css/signup.css');
echo HTML::script('media/scripts/jquery.validate.pack.js');
//echo html::script('media/scripts/jquery.form.js');
//echo html::script('media/scripts/jquery-ui-1.8.4.custom.min.js');
echo html::script('media/scripts/json_parse.js');
echo html::script('media/scripts/jquery.autocomplete.js');
echo html::style('media/css/jquery.autocomplete.css');
echo html::script('media/scripts/passwordmeter.js');
echo HTML::script('media/scripts/css-browser-selector.js');

echo html::script('media/scripts/prettyCheckboxes.js');
echo html::style('media/css/prettyCheckboxes.css');
$json_country_list = json_encode($country_list);
$json_country_list = addslashes($json_country_list);
//echo "<pre>";print_r($country_list);echo "</pre>";
?>	
<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
			//	$('td.choose-plan').hide();
				$('#checkboxDemo input[type=checkbox],#radioDemo input[type=radio]').prettyCheckboxes();
				$('.inlineRadios input[type=radio]').prettyCheckboxes({'display':'inline'});
	
				});
			function show(){
		alert('hi');
		
				
			}
			
		</script>
		    <div class="header">
        <h1 class="logo"><a href="<?php echo SITEURL?>/admin">Time Tracker</a></h1>
     </div>
 <div class="account-wrapper">

     <ul class="arrow-nav">
     <?php if(isset($registered_free)) {?>
     	<li id="signup-plan" class="plan"><span>Choose Plan</span></li>   
     <?php } else {?>
		<li id="signup-plan" class="plan active"><span>Choose Plan</span></li>
	<?php }?>
		<li id="signup-info" class="info"><span>Account Info</span></li>
		   
		<?php if(isset($registered_free)) {?>  
			<li id="signup-address" class="address active1"><span>Address</span></li>
		<?php } else {?> 
		<li id="signup-address" class="address"><span>Address</span></li>  
		<?php }?>    
		<li id="signup-payment" class="method"><span>Payment Method</span></li>     
		 <li id="signup-confirm" class="confirm"><span>Confirmation</span></li>       	
	 </ul>
		<div class="clear"></div>
		 <form action='<?php echo SITEURL?>/register/submit' method="post" id="company_create" class='text_normal jqtransform' name="admin_form">
			<?php
					if(isset($error))
					{
						echo "<div class='error_message' id='error_service'>".$error."</div><br/>";
					}
					if(isset($resignup)) {
						echo "<div class='success_message'>We found this record in our system. Please select plan and do resignup for activating the account.</div><br/>";
					}
			?>	
			<div class='success_message' id='remember_user' style="display:none;">We found this record in our system. Please select plan and do resignup for activating the account.</div>
			<div class='error_message' id="error_signup" style="display:none"></div>
			<?php 
                       if(isset($registered_free)) {
	                       $plan_style	=	"display:none;";
	                   } else {
	                       $plan_style	=	"";
	                   }
            ?>
			<div class="account-content" id="payment_stream" style="<?php echo $plan_style;?>" >
			<h2>Choose Your Plan</h2>
			<p>Upgrade, downgrade or cancel at any time.</p>
			<div class="table-container" id="radioDemo">
							<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<?php 
								if(!empty($payment_stream)) {
									$i=1;
									foreach($payment_stream as $plan) { ?>
									<?php if($plan['price'] == 0) {
										$td_class = "free-signup-border";		
									} else {
										$td_class = "";	
									}?>
								<tr>
									<td width="5%" class="<?php echo $td_class;?>" id="td-lefttop-<?php echo $i;?>">
									<label for="radio-<?php echo $i;?>" tabindex="1"></label>
									<input type="radio" style="display:none;" name="plan_check" id = "radio-<?php echo $i;?>" value="<?php echo $plan['plan_id']; ?>" onclick="showPlan(<?php echo $plan['plan_id']; ?>, <?php echo $i;?>, <?php echo $plan['price'];?>);"/>
									</td>
									<td width="25%" nowrap class="<?php echo $td_class;?>" id="td-top-1-<?php echo $i;?>"><span class="second-td"><?php echo $plan['name'];?></span></td>
									<td width="25%" class="<?php echo $td_class;?> right-td" id="td-top-2-<?php echo $i;?>"><span class="third-td">$<?php echo $plan['price']; ?>/month</span></td>
									<td class="<?php echo $td_class;?>" id="td-top-3-<?php echo $i;?>" width="3%"><div class="dotted-border"></div></td>
									<?php if($plan['price'] == 0) {?>
									<td width="30%" class="<?php echo $td_class;?> right-td" id="td-righttop-<?php echo $i;?>" nowrap><span class="fourth-td">Unlimited trial (30 days)&nbsp;<br/><span class="small-font">No credit card required</span></span></td>
									<?php } else {?>
									<td width="30%" class="right-td"><span class="fourth-td"><?php echo $plan['user_limit'];?> user</span></td>
									<?php }?>
									<input type="hidden" name="plan" id="plan" value="" />
								</tr>
								<?php if($plan['price'] == 0) {?>
										<tr class="free-signup-<?php echo $i;?>" style="display:none;" id="free-signup-form">
											<td colspan="5" class="choose-plan">
												<fieldset>
		    										<label class="left">Email</label>
		    										   <input type="text" value="" name="free_UserEmail" id="free_UserEmail" class="left  required email shadow-input" />
													<label class="left">Password</label>
		    										<input type="password" value="" id="free_myPassword" name="free_password" class=" left required shadow-input" />
		    										<div class="clear"></div>
		    									</fieldset>	
		    									<fieldset style="float:right;margin-right:8px;">
		    										
													<label class="left">Confirm Password</label>
		    										<input type="password" value="" id="free_myPassword" name="free_password" class=" left required shadow-input" />
		    										<div class="clear"></div>
		    									</fieldset>
											</td>
										</tr>
								<?php }?>
							<?php 
									$i++;
									}
								}
							?>
								
							
							</table>
						</div>
					
					<a class="blue-button " href="#" onclick="check_plan('<?php echo SITEURL;?>');">Next</a>
						<div class="clear"></div>
						
					
			</div>
			
			
			<?php 
	                       if(isset($registered_free)) {
	                       		$plan_style	=	"display:none;";
	                       } else {
	                       		$plan_style	=	"display:none";
	                       }
            ?>
            
          
            
			<div class="account-content"  id="account_info" style="<?php echo $plan_style?>">
                <h2>Account Information</h2>
             
                <div class="clear">
                </div>
                <div class="table-container">
                        
                        <fieldset>
                            <label class="left">
                                Your AccountEdge Serial Number
                            </label>
                            <input type="text" value="" id="serialnumber" name="serialnumber" class="left  required shadow-input" tabindex="2" />
                            <p>
                                Find your serial number <a href="http://support.accountedge.com/kb/general-accountedge/locating-your-serial-number" class="blue" target="_blank">here</a>
                            </p>
                            <div class="clear">
                            </div>
                        </fieldset>
                        <fieldset>
                            <label class="left">
                                Your Email
                            </label>
                            <?php 
                            if(isset($registered_free)) {
                            	$free_user_email	=	$form['UserEmail'];
                            } else {
                            	$free_user_email	=	"";
                            }
                            ?>
                            <input type="text" value="<?php echo $free_user_email;?>" name="UserEmail" id="UserEmail" class="left  required email shadow-input" tabindex="3" />
                            <p>
                                Your email address will be used for login purposes.
                            </p>
                            <div class="clear">
                            </div>
                        </fieldset>
                        
                        <fieldset>
                            <label class="left">
                                Desired Password (6-15 Characters)
                            </label>
                            <input type="password" value="" id="myPassword" name="password" class="password left  required width200 shadow-input " onkeyup="testPassword($(this).val())" tabindex="4"/>
                            <div class="password-container left">
                                <div class="pass-sider">
                                </div>
                            </div>
                            <label class="right" style="font-size: 11px; margin-right: 20px;">Password Strength</label>
                            <p class="right mright88 pstrength">
                            </p>
                            <div class="clear">
                            </div>
                        </fieldset>
                        <fieldset>
                            <label class="left">
                                Confirm Password
                            </label>
                            <input type="password" value="" name="confirmpass" id="confirmpass" class="left required shadow-input" tabindex="5"/>
                            <div class="clear">
                            </div>
                        </fieldset>
                    
                </div>
                <a class="blue-button " href="#" onclick="return submit_signup_form('<?php echo SITEURL;?>');">Next</a>
                <div class="clear">
                </div>
            </div>	
			 <?php 
                        if(isset($registered_free)) {
                            $free_user_email	=	$form['UserEmail'];
                        } else {
                            $free_user_email	=	"";
                        }
            ?>
			<?php 
                       if(isset($registered_free)) {
	                       $plan_style	=	"display:block;";
	                   } else {
	                       $plan_style	=	"display:none;";
	                   }
            ?>
			
			<div id="billing_info" style="<?php echo $plan_style;?>" class="account-content">
				<h2>Billing Address</h2>
				
				<div class="clear"></div>
				<div class="table-container">
						 
						<?php 
                       if(isset($registered_free)) {
                       	?>
                       
							<fieldset>
	                            <label class="left addr">
	                                Your AccountEdge Serial Number
	                            </label>
	                            <input type="text" value="" id="free_reg_serialnumber" name="free_reg_serialnumber" class="left  required shadow-input  width320"" />
	                            <p style="margin-left:100px !important">
	                                Find your serial number <a href="http://support.accountedge.com/kb/general-accountedge/locating-your-serial-number" class="blue" target="_blank">here</a>
	                            </p>
	                            <div class="clear">
	                            </div>
                       		</fieldset>
                       		<input type="hidden" value="<?php echo $free_user_email;?>" id="free_reg_UserEmail" name="free_reg_UserEmail"  class="left  required email shadow-input" tabindex="3" />
                       		<input type="hidden" value="<?php echo $form['password'];?>" id="free_reg_myPassword" name="password" class="password left  required width200 shadow-input " onkeyup="testPassword($(this).val())" tabindex="4"/>
                       	<?php } ?>
	    					<fieldset>
	    						<label class="left addr">First Name</label>
	    						<input type="text" value="" name="name" id="name" class="left  required shadow-input width320" />
	    						<div class="clear"></div>
	    					</fieldset>
	    					<fieldset>
	    						<label class="left addr">Last Name</label>
	    						<input type="text" value="" name="lastname" id="lastname" class="left  required shadow-input width320" />
	    						<div class="clear"></div>
	    					</fieldset>
	    					<fieldset>
	    						<label class="left addr">Your Company Name</label>
	    						<input type="text" value="" name="cname" id="cname" class="left  required shadow-input width320" />
	    						<div class="clear"></div>
	    					</fieldset>
							<div class="addr-bg">
	    					<fieldset>
	    						<label class="left addr">Street1</label>
	    						<input type="text" value="" name="address" id="address" class="left  required email shadow-input width320" />
	    						<div class="clear"></div>
	    					</fieldset>
							<fieldset>
	    						<label class="left addr">Street2</label>
	    						<input type="text" value="" name="address2" id="address2" class="left  required email shadow-input width320" />
	    						<div class="clear"></div>
	    					</fieldset>
							<fieldset>
	    						<label class="left addr">City</label>
	    						<input type="text" value="" name="city" id="city" class="left  required email shadow-input width320" />
	    						<div class="clear"></div>
	    					</fieldset>
							<fieldset>
	    						<label class="left addr">State/Province</label>
	    						<input type="text" value="" name="state" id="state" class="left  required email shadow-input width320" />
	    						<div class="clear"></div>
	    					</fieldset>
							<fieldset>
	    						<label class="left addr">Zip Code/Postal Code</label>
	    						<input type="text" value="" name="zipcode" id="zipcode" class="left  required email shadow-input width320" />
	    						<div class="clear"></div>
	    					</fieldset>
							<fieldset>
	    						<label class="left addr">Country</label>
	    						<select id="city-selection" class="width320" name="country">
										<option value="0" class="none">-Select-</option>
										<?php foreach($country_list as $country) {?>
												<option value="<?php echo $country['id']?>" class="in"><?php echo $country['country']?></option>
										<?php }?>
								</select>
	    						
	    						<div class="clear"></div>
	    					</fieldset>
							</div>
							<fieldset>
	    						<label class="left addr">Phone Number</label>
	    						<input type="text" value="" name="phone" id="phone" class="left  required email shadow-input width320" />
	    						<div class="clear"></div>
	    					</fieldset>
						
							</div>
						<a class="blue-button " href="#" onclick="return billing_form_validate('<?php echo SITEURL;?>');">Next</a>
							<div class="clear"></div>
			</div>
			
			
			<div id="payment_form" style="display:none;" class="account-content">
                       
	                 <h2>Payment Method</h2>
						<div class="clear"></div>
						<div class="table-container">
							
	                              
	                         <iframe src="" id="payment_frame" height="446" width="654" style="border:0px solid #000000; "></iframe>
	                                   
	                      <div class="clear">
	                </div>
	             </div>
	         </div>
	                       
	                     
           
            
			 <?php if(isset($registered_free)) {?>
                   		<input type="hidden" id="plan" name="plan" value="<?php echo $plan;?>" />
                   		<input type="hidden" id="free_conmpany_id" name="free_company_id" value="<?php echo $company_id;?>" />
             <?php } else { ?>
             			<input type="hidden" id="free_conmpany_id" name="free_company_id" value="" />
             <?php }?>
             <?php if(isset($resignup)) {?>
                  		<input type="hidden" id="resignup_ref_id" name="resignup_ref_id" value="<?php echo $resignup_ref_id;?>" />
             <?php } else { ?>
                   		<input type="hidden" id="resignup_ref_id" name="resignup_ref_id" value="" />
             <?php } ?>
		</form>
   </div>
<div class="popup_admin" id='signup_status' style="display:none;margin-top:10%;"> 	
	<div class="wait-message" style="font-weight:bold;text-align:center;">
		<span style="position:relative;top:-19px;">Please wait</span>
		<img src="<?php echo SITEURL?>/media/images/loading.gif" />
	</div>
</div> 
-->



<!-- New Signup contents starts -->

<?php 

//echo html::style('media/css/signup.css');
echo HTML::script('media/scripts/jquery.validate.pack.js');

echo html::script('media/scripts/json_parse.js');
echo html::script('media/scripts/jquery.autocomplete.js');
echo html::style('media/css/jquery.autocomplete.css');
//echo html::script('media/scripts/passwordmeter.js');
echo HTML::script('media/scripts/css-browser-selector.js');

echo html::script('media/scripts/prettyCheckboxes.js');
//echo html::style('media/css/prettyCheckboxes.css');
$json_country_list = json_encode($country_list);
$json_country_list = addslashes($json_country_list);

?>	



<link href="/media/css/tt-signup.css" rel="stylesheet">
	<!-- Outer div Starts -->
	<div id="outer-div" class="outer-div">
		<div id="account-edge-img" style="text-align:center !important;"><!-- <img src="/media/images/new-signup/new_logo.png" >   -->
			<img src="<?php echo SITEURL;?>/media/images/login/logo_n_text.png"  width="194px" class="" id="">
		</div>
		<div id="signup-menu">
		    <?php 
              if(isset($registered_free)) { 
              $topBar="/media/images/new-signup/address_bar.png" ;
             
              }
              else
              {
                  $topBar="/media/images/new-signup/signup-select.png" ;
              }
              ?>
            <!-- <img src="/media/images/new-signup/signup-select.png" class="cloud" >  -->  
             <img src="<?php echo $topBar;?>" class="cloud" >               
		</div>
		
		<div class="clear">
		</div>
		
		<form action='<?php echo SITEURL?>/register/submit' method="post" id="company_create" class='text_normal jqtransform' name="admin_form">
			<?php
					if(isset($error))
					{
						echo "<div class='error_message' id='error_service'>".$error."</div><br/>";
					}
					if(isset($resignup)) {
						echo "<div class='success_message'>We found this record in our system. We will activate your account with your selected plan</div><br/>";
					}
			?>	
			<div class='success_message' id='remember_user' style="display:none;">We found this record in our system. We will activate your account with your selected plan.
			</div>
			<div class='error_message' id="error_signup" style="display:none">
			</div>
			
    			<?php 
                           if(isset($registered_free)) {
    	                       $plan_style	=	"display:none;";
    	                   } else {
    	                       $plan_style	=	"";
    	                   }
                ?>
		
		
		<div class="central-contents payment_stream" id="payment_stream" style="<?php echo $plan_style;?>">
			<div class="choose-plan">
				<label class="choose-plan-label">Choose Your Plan</label>
				<label class="plan-change-label">You can upgrade, downgrade or cancel your account at any time.</label>
			</div>
							
			<div id="select-plans-users">
			<?php if(empty($registered_free)) {?>
    			<input type="hidden"  name="plan_check" id = "plan" value="" />
    			<?php }?>
    			<?php
    			if(!empty($payment_stream)) {
    									$i=1;
    									foreach($payment_stream as $plan) { ?>
    									<?php if($plan['price'] == 0) {
    										$td_class = "free-signup-border";		
    									} else {
    										$td_class = "";	
    									}?>
    				
    				
    				<div class="select-plans <?php echo $td_class;?>" id="s-plan-<?php echo $i;?>" onclick="choosePlan(this, '<?php echo $plan['plan_id'];?>' , '<?php echo $plan['price'];?>')">
    					<div id="plan-type-inner1" class="plan-type-inner1-normal" >
    						<div id="plan-logo-inactive" class="plan-logo-inactive">
    							<label class="logo-plan-type-label"><?php echo ucfirst(substr($plan['name'],0,1));?></label>
    						</div>
    						<div id="plan-user-admin">
    							<label class="plan-type-label"><?php echo $plan['name'];?></label>
    						</div>
    						<div class="plan-user-admin1">
    							<label class="plan-type-users"><?php echo $plan['user_limit'];?> User, 1 Admin</label> 
    						</div>
    					</div>
    					<div id="plan-type-inner2" class="plan-type-inner2-normal">
    						<div class="plan-feature-price">
    							<label class="plan-type-feature plan-price">$<?php echo $plan['price']; ?><span id="per-month">/month</span></label>
    						</div>
    						
    					</div>
    				
            				
            		
    				</div>
    				
    				<?php if($plan['price'] == 0) {?>
										<!-- toggle for free plan starts-->
            				<div class="toggle-choose-free-plan">
            					<div class="free-plan-container4">
            						<div class="free-plan-container3">
            							<div class="free-plan-container2">
            								<div class="free-plan-container1">
            								 	<div class="free-plan-col1"><label class="label-free-plan-titles">Email</label></div>
            									<div class="free-plan-col2 input-field-free-plan">
            									<input type="text" value="" name="free_UserEmail" id="free_UserEmail" class="free-plan-fields left  required email shadow-input"></div>
            									<div class="free-plan-col3 pad-top"><label class="label-free-plan-titles">Password</label></div>
            									<div class="free-plan-col4 pad-top input-field-free-plan">
            									<input type="password" value="" id="confirm_password" name="free_password" class="free-plan-fields1 left width-84 required shadow-input"></div>  
            								</div>
            							</div>
            						</div>
            					</div>
            					
            			<!--  	 <div class="free-plan-container5">
            						<div class="free-plan-container6">
            							 <div class="free-plan-col5"><label class="label-free-plan-titles">Confirm Password</label></div>
            							<div class="free-plan-col6 input-field-free-plan">
            							<input type="password" value="" id="free_myPassword" name="free_password" class="free-plan-fields left required width-84 shadow-input"></div> 
            						</div>
            					</div> -->
            					
            					<div class="free-plan-container5">
            						<div class="free-plan-container3">
            							<div class="free-plan-container2">
            								<div class="free-plan-container1">
            								 	<div class="free-plan-col1 no-display"></div>
            									<div class="free-plan-col2 no-display input-field-free-plan">
            									
            									</div>
            									<div class="free-plan-col3"><label class="label-free-plan-titles no-pad">Confirm Password</label></div>
            									<div class="free-plan-col4 input-field-free-plan">
            									<input type="password" value="" id="free_myPassword" name="free_password" class="free-plan-fields left required width-84 shadow-input"></div>
            									</div>  
            								</div>
            							</div>
            						</div>
            					
            				</div>
            			<!-- toggle for free plan ends -->
								<?php }?>
    				
        		    <?php 
        				$i++;
        				}
        				  }
        			?>
			</div>

				<div class="next-page-btn">
					<a class="blue-button " href="#" id="next-link" onclick="check_plan('<?php echo SITEURL;?>');">
						<div class="next-button next-label">
						Next
						</div>
					</a>
				</div>
		</div>
						
            <?php 
	                       if(isset($registered_free)) {
	                       		$plan_style	=	"display:none;";
	                       } else {
	                       		$plan_style	=	"display:none";
	                       }
            ?>
<!-- End of central contents of choose plan -->

<!-- Starting of Central Contents Account Info -->


<div class="central-contents account_info"  id="account_info" style="<?php echo $plan_style;?>" >
		<div class="acc-info">
			<label class="choose-plan-label">Account Information</label>
		</div>

		<div class="find-serial">
			<label class="serial-side-label-email">Find your serial number <a href="http://support.accountedge.com/kb/general-accountedge/locating-your-serial-number" class="here-link" target="_blank">here</a></label>
		</div>

		<div class="search-contents" id="contents-serial" >
			<div class="row acc-information-row" id="acc-information-row">
				<div class="span3" id="account-serial-span" >
				</div> 
				<div id="ac-field" class="ac-field" > 
				<input type="text" class="serial-number-field left  required shadow-input" value="" id="serialnumber" name="serialnumber" >
				 
				</div> 
			</div>
		</div>

 

		<div class="use-email">
			<label class="serial-side-label-email">Use your email to login</label>
		</div>
  
  						<div class="find-serial-phone">
							<label class="serial-side-label-email">Find your serial number <a href="http://support.accountedge.com/kb/general-accountedge/locating-your-serial-number" target="_blank" class="here-link">here</a></label>
						</div>
  
		<div class="search-contents" >
			<div class="search-contents-row" id="search-contents-row">
				<div class="users-activity-mail" id="users-activity-mail" >
					<label>Email</label>
				</div> 
				 <?php 
                            if(isset($registered_free)) {
                            	$free_user_email	=	$form['UserEmail'];
                            } else {
                            	$free_user_email	=	"";
                            }
                            ?>
				<input type="text" value="<?php echo $free_user_email;?>" name="UserEmail" id="UserEmail" class="password left required shadow-input">
				
				<div class="email-validator">
				</div>
			</div>
			<div class="search-contents-row" id="search-contents-row"   >
				<div class="users-activity-mail" id="users-activity-password" >
					<label>Password</label>
				</div> 
				<input type="password" value="" name="password" class="password left required shadow-input " id="myPassword">
				
				<span id="result">
				</span>
				<div class="password-strength"></div>
			</div>
			<div>
				<div class="row acc-information-row" id="acc-information-row">
					<div class="span3" id="account-serial-span" >Confirm Password
					</div> 
					<div id="ac-field" class="ac-field" > 
					<input type="password" value="" name="confirmpass" id="confirmpass" class="serial-number-field left required shadow-input">
					  
					</div> 
				</div>
			</div>
	    </div>

 			
  
		<div class="next-page-btn btn-nxt">
			<a href="#" id="next-link" onclick="return submit_signup_form('<?php echo SITEURL;?>');">
				<div class="next-button next-label">
						Next
				</div>
			</a>
		</div>
	
</div>


					 <?php 
                        if(isset($registered_free)) {
                            $free_user_email	=	$form['UserEmail'];
                        } else {
                            $free_user_email	=	"";
                        }
                    ?>
			        <?php 
                       if(isset($registered_free)) {
	                       $plan_style	=	"display:block;";
	                   } else {
	                       $plan_style	=	"display:none;";
	                   }
                    ?>
<!-- Ending of Central Contents Account Info -->

<!-- Starting of Central Contents Billing -->
	<div id="billing_info" class="central-contents billing_info" style="<?php echo $plan_style;?>">
		<div class="acc-info">
		<label class="choose-plan-label">Billing Address</label>
		</div>
		
		        
		          	  <?php 
                       if(isset($registered_free)) {
                      ?>
                      
                      	<div class="find-serial">
							<label class="serial-side-label-email">Find your serial number <a href="http://support.accountedge.com/kb/general-accountedge/locating-your-serial-number" target="_blank" class="here-link">here</a></label>
						</div>

                		<div class="search-contents free-signup-contents" id="contents-serial">
                			<div class="row acc-information-row" id="acc-information-row">
                				<div class="span3" id="account-serial-span" >
                				</div> 
                				<div id="ac-field" class="ac-field"> <input type="text" name="free_reg_serialnumber" class="free_reg_serialnumber" id="free_reg_serialnumber" style="margin-top:2%;font-family:HelveticaNeueMedium !important;color:#515151;Font-size:14px;">
                				</div> 
                			</div>
                		</div>

                		<div class="use-email">
                			<label class="serial-side-label-email">&nbsp;</label>
                		</div>
                   		
                	<!-- 	<div class="search-contents" > -->
                		<!--	<div class="search-contents-row" id="search-contents-row">
                				<div class="users-activity-mail" id="users-activity-mail" >
                					<label>Email</label>
                				</div> -->
                				<input type="hidden" value="<?php echo $free_user_email;?>" name="free_reg_UserEmail"  id="free_reg_UserEmail">
                		<!--		<div class="email-validator">
                				</div>
                			</div>-->
                			<!-- <div class="search-contents-row" id="search-contents-row"   >
                				<div class="users-activity-mail" id="users-activity-password" >
                					<label>Password</label>
                				</div> -->
                				<input type="hidden" value="<?php echo $form['password'];?>" id="free_reg_myPassword" name="password" >
                			<!-- 	<span id="result">
                				</span>
                				<div class="password-strength"></div>
                			</div> -->
                			
                	<!--    </div> -->
                      
                      	<?php } ?> 
                      
                      
		
		<div class="search-contents-bill" >
			<div class="first-last-name">
				<div class="row">
					<div class="span1" id="fname"><label class="name-label">First Name</label></div>
					<input type="text" value="" name="name" id="name"  />
				
					<div class="span1" id="lname"><label class="name-label">Last Name</label></div>
					<input type="text" value="" name="lastname" id="lastname"  />
						
				</div>
			</div>
			  
			<div class="search-contents-row-bill" id="search-contents-row-bill"   >
				<div class="users-activity-company company-label" id="users-activity-company" ><label>Company Name</label></div> 
				<input type="text" value="" name="cname" id="cname"  />
				
			</div>
		</div>
	  
		<div class="search-contents-bill" >
			<div class="street">
				<div id="street1"><label>Street 1</label></div>
				<input type="text" value="" name="address" id="address"  />
				
			</div>
			<div class="street">
			<div id="street1"><label>Street 2</label></div>
			<input type="text" value="" name="address2" id="address2"  />
			</div>
	  
			<div class="first-last-name">
				<div class="row">
					<div class="span1" id="fname"><label class="name-label">City</label></div>
					<input type="text" value="" name="city" id="city">
					<div class="span1" id="lname"><label class="name-label">State/Province</label></div>
					<input type="text" value="" name="state" id="state">
				</div>
			</div>
  
			<div class="first-last-name1">
				<div class="row">
					<div class="span1" id="city-zip"><label class="name-label">Zip/Postal Code</label></div>
					<input type="text" value="" name="zipcode" id="zipcode">
					<div class="span1" id="state-country"><label class="name-label">Country</label></div>
					<!-- <input type="text" id="state-txt"> -->
					<select id="city-selection"  name="country">
						<option value="0" class="none">-Select-</option>
								<?php foreach($country_list as $country) {?>
						<option value="<?php echo $country['id']?>" class="in"><?php echo $country['country']?></option>
								<?php }?>
					</select>
					
				</div>
			</div>
  
			<div class="search-contents-row-bill" id="search-contents-row-bill"   >
				<div class="users-activity-company" id="users-activity-company" ><label>Phone Number</label></div> 
					<input type="text" value="" name="phone" id="phone" >
			</div>
	  
		</div>


	<!-- Phone Version Starts -->
		<!-- Phone Version Outer Div Starts-->
		<div class="search-contents-bill-phone-version" id="search-contents-bill-phone-version">
			<!-- Phone Version inner1 Div Starts -->
			<div class="search-contents-bill-phone-inner" >
				<div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
					<div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >First Name</div> 
					<div id="label-span-billing" > <input type="text" name="name" id="billing-fname-textfields-phone-version">
					</div> 
				</div>
				<div id="search-contents-margin" >
				</div>
				<div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
					<div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Last Name</div> 
					<div id="label-span-billing" > <input type="text" name="lastname" id="billing-lname-textfields-phone-version">
					</div>
				</div>
				<div id="search-contents-margin" >
				</div>
				<div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
					<div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Company Name</div> 
					<div id="label-span-billing" >  <input type="text" name="cname" id="billing-company-textfields-phone-version">
					</div>
				</div>
			</div>
			<!-- Phone Version Inner1 Div Ends -->
	 
			<!-- Phone Version inner2 Div Starts -->
			<div class="search-contents-bill-phone-inner" >
				<div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
					<div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Street 1</div> 
					<div id="label-span-billing" >  <input type="text" id="billing-street1-textfields-phone-version">
					</div> 
				</div>
				<div id="search-contents-margin" >
				</div>
				<div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
					<div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Street 2</div> 
					<div id="label-span-billing" >  <input type="text" id="billing-street2-textfields-phone-version">
					</div>
				</div>
			    <div id="search-contents-margin" >
				</div>
				<div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
					<div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >City</div> 
					<div id="label-span-billing" >  <input type="text" id="billing-city-textfields-phone-version">
					</div>
				</div>
				<div id="search-contents-margin" >
				</div>
				<div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
					<div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >State / Province</div> 
					<div id="label-span-billing" >  <input type="text" id="billing-state-textfields-phone-version">
					</div>
				</div>
				<div id="search-contents-margin" >
				</div>
				<div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
					<div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Zip / Postal Code</div> 
					<div id="label-span-billing" >  <input type="text" id="billing-zip-textfields-phone-version">
					</div>
				</div>
				<div id="search-contents-margin" >
				</div>
				<div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
					<div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Country</div> 
					<div id="label-span-billing" >  <!--<input type="text" id="billing-country-textfields-phone-version"> -->
					<select id="country-selection" name="country" style="width:90%;">
					<option value="0" class="none">-Select-</option>
							<?php foreach($country_list as $country) {?>
					<option value="<?php echo $country['id']?>" class="in"><?php echo $country['country']?></option>
							<?php }?>
					</select>
					
					</div>
				</div>
				<div id="search-contents-margin" >
				</div>
				<div class="row search-contents-row-billing-phone" id="search-contents-row-billing-phone"   >
					<div class="span3 users-activity-field-billing-phone" id="users-activity-field-billing-phone" >Phone Number</div> 
					<div id="label-span-billing" >  <input type="text" id="billing-phone_number-textfields-phone-version" class="billing-textfields-phone-version">
					</div>
				</div>
			</div>
			<!-- Phone Version Inner2 Div Ends -->
		
		</div>
		<!-- Phone Version outer Div Ends -->
<!-- Phone Version ends -->		



		<div class="next-page-btn btn-nxt1">
			<a class="blue-button " href="#" onclick="return billing_form_validate('<?php echo SITEURL;?>');" id="next-link">
				<div class="next-button next-label">
					Next
				</div>
			</a>
		</div> 
	</div>
<!-- Ending of Central Contents Billing -->

<!-- Starting of central contents payment -->
<div id="payment_form" class="central-contents payment_form" style="display:none;">
  	<div class="acc-info">
  		<label class="choose-plan-label">Payment</label>
  	</div>
  	<div class="error_description"></div>
  	<div class="clear"></div>
  	<iframe src="" id="payment_frame" name="payment_frame" height="446" width="654" style="border:0px solid #000000; "></iframe>
	<div class="clear"></div>
</div>
<!-- End of central contents payment -->
 <?php if(isset($registered_free)) {?>
                   		<input type="hidden" id="plan" name="plan" value="<?php echo $plan;?>" />
                   		<input type="hidden" id="free_conmpany_id" name="free_company_id" value="<?php echo $company_id;?>" />
             <?php } else { ?>
             			<input type="hidden" id="free_conmpany_id" name="free_company_id" value="" />
             <?php }?>
             <?php if(isset($resignup)) {?>
                  		<input type="hidden" id="resignup_ref_id" name="resignup_ref_id" value="<?php echo $resignup_ref_id;?>" />
             <?php } else { ?>
                   		<input type="hidden" id="resignup_ref_id" name="resignup_ref_id" value="" />
             <?php } ?>
             
	</form>
	</div>
<div class="popup_admin" id='signup_status' style="display:none;margin-top:10%;"> 	
	<div class="wait-message" style="font-weight:bold;text-align:center;">
		<span style="position:relative;top:1px;margin-right:6px;">Please wait</span>
		<img src="<?php echo SITEURL?>/media/images/tt-new/load.gif" style="margin-top:-1px;"/>
	</div>
</div> 
	<!-- Outer div ends -->
 
	<script>
	$(document).ready(function(){
	$('.toggle-choose-free-plan').hide();
	$('.toggle-choose-free-plan').css("display","none");
	});
	</script>
  
<script>
function choosePlan(chk,plan_id,plan_price)
{
var Id=($(chk).attr("id"));
$("#plan").val(plan_id);

if ($("#" + Id).hasClass('select-plans') == true)
{	$(".plan-selected").removeClass('plan-selected');
	$("#" + Id).removeClass('select-plans');
	
	$("#" + Id).addClass('plan-selected');
	$(".plan-selected").addClass('select-plans');
	$('.plan-user-admin1 label').css("color","#858585");
	if($("#" + Id).hasClass('plan-selected')==true && $("#" + Id).hasClass('select-plans')==true)
	{
	
	$('.select-plans').find('.plan-logo-inactive').css("background","url('/media/images/new-signup/inactive-badge.png') 30% 50% no-repeat");	
	$("#" + Id).find('.plan-logo-inactive').css("background","url('/media/images/new-signup/active-badge.png') 30% 50% no-repeat");	
	$('.select-plans').find('#plan-user-admin label').css("color","#515151");
	$("#" + Id).find('#plan-user-admin label').css("color","#fafafa");
	$('.select-plans').find('.plan-feature-price label').css("color","#515151");
	$("#" + Id).find('.plan-feature-price label').css("color","#fafafa");
	$('.select-plans').find('.plan-feature label').css("color","#515151");
	$("#" + Id).find('.plan-feature label').css("color","#fafafa");
	$('.select-plans').find('.plan-user-admin1').css("color","#858585");
	$("#" + Id).find('.plan-user-admin1 label').css("color","#fafafa");
	}
	
}

    if (plan_price==0)
    {
    	$('.toggle-choose-free-plan').toggle("slow");
    }
    else
    {
    
    	$('.toggle-choose-free-plan').hide();
    }
}


$(document).ready(function(){
//alert($(window).width());
if($(window).width() < 400 )
{/*first page start*/
$('.central-contents').css("margin","26px 0 5%");
$('#select-plans-users').css("margin-left","1%");
$('.plan-type-feature').css("font-size","18px");
$('#select-plans-users').css("margin-right","1%");
$('.next-page-btn').css("margin","6% 1% 5%");
$('.outer-div').css("padding","0");
$('.plan-type-label').css("padding-top","0");
/*first page ends*/

/*second page start*/
$('.find-serial').css("display","none");
$('.use-email').css("display","none");
$('.search-contents').css("margin-left","1%");
$('.search-contents').css("width","97%");
$('.find-serial-phone').show();
$('.ac-field').css("padding-left","8px");
/*second page ends*/

/*third page start*/
$('.search-contents-bill-phone-version').show();
$('.search-contents-bill').hide();
/*third page ends*/
$('.billing_info .free_reg_serialnumber').css("margin-top","6%");
$('.billing_info .free_reg_serialnumber').css("width","100%");

}
else if (($(window).width() > 400) && ($(window).width() < 600))
{/*first page start*/
	$('.central-contents').css("margin","26px 0 5%");
	$('#select-plans-users').css("margin-left","1%");
	$('#select-plans-users').css("margin-right","1%");
	$('.outer-div').css("padding","0");
	$('.plan-type-feature').css("font-size","18px");
	$('.next-page-btn').css("margin","6% 1% 5%");
	$('.plan-type-label').css("padding-top","0");
	/*first page ends*/
	
	/*second page start*/
	$('.find-serial').css("display","none");
	$('.use-email').css("display","none");
	$('.search-contents').css("margin-left","2%");
	$('.search-contents').css("width","96%");
	$('.find-serial-phone').show();
	$('.ac-field').css("padding-left","8px");
	$('.btn-nxt').css("margin","3% 19% 5%");
	/*second page ends*/
	
	/*third page start*/
	$('.search-contents-bill-phone-version').show();
	$('.search-contents-bill').hide();
	/*third page ends*/
	$('.billing_info .free_reg_serialnumber').css("margin-top","6%");
	$('.billing_info .free_reg_serialnumber').css("width","100%");
	
}
else if (($(window).width() >= 600) && ($(window).width() < 1000))
{/*first page start*/
	$('.central-contents').css("margin","26px 0 5%");
	$('#select-plans-users').css("margin","3% 11% 5%");
	$('.outer-div').css("padding","0");
	$('.plan-type-feature').css("font-size","22px");
	$('.next-page-btn').css("margin","6% 11% 5%");
	$('.plan-type-label').css("padding-top","3%");
	/*first page ends*/
	
	/*second page start*/
	$('.find-serial').css("display","block");
	$('.use-email').css("display","block");
	$('.search-contents').css("margin-left","20%");
	$('.search-contents').css("width","60%");
	$('.find-serial-phone').hide();
	$('.ac-field').css("padding-left","8px");
	$('.btn-nxt').css("margin","6% 20% 5%");
	
	/*second page ends*/
	
	/*third page start*/
	$('.search-contents-bill-phone-version').hide();
	$('.search-contents-bill').show();
	$('.search-contents-bill').css("margin","2% 8% 2%");
	$('.btn-nxt1').css("margin","6% 8% 5%");
	/*third page ends*/
	$('.billing_info .use-email').css("display","none");
	$('.billing_info .free_reg_serialnumber').css("margin-top","3%");
}
else
{/*first page start*/
$('.central-contents').css("margin","26px 10% 5%");
$('#select-plans-users').css("margin-left","19%");
$('#select-plans-users').css("margin-right","19%");

$('.next-page-btn').css("margin","4% 19% 5%");
$('.plan-type-feature').css("font-size","22px");
$('.plan-type-label').css("padding-top","3%");
/*first page ends*/

/*second page start*/
$('.find-serial').css("display","block");
$('.use-email').css("display","block");
$('.search-contents').css("margin-left","20%");
$('.search-contents').css("width","60%");
$('.find-serial-phone').hide();
$('.ac-field').css("padding-left","8px");
/*second page ends*/

/*third page start*/
$('.search-contents-bill-phone-version').hide();
$('.search-contents-bill').show();
$('.search-contents-bill').css("margin","2% 17% 2%");
$('.btn-nxt1').css("margin","4% 17% 5%");
/*third page ends*/
$('.billing_info .search-contents').css("width","66%");
$('.billing_info .search-contents').css("margin-left","17%");
$('.billing_info .find-serial').css("margin-right","2%");
}
$(window).resize(function()
{
if($(window).width() < 400 )
{/*first page start*/
$('.central-contents').css("margin","26px 0 5%");
$('#select-plans-users').css("margin-left","1%");
$('.plan-type-feature').css("font-size","18px");
$('#select-plans-users').css("margin-right","1%");
$('.next-page-btn').css("margin","6% 1% 5%");
$('.outer-div').css("padding","0");
$('.plan-type-label').css("padding-top","0");
/*first page ends*/

/*second page start*/
$('.find-serial').css("display","none");
$('.use-email').css("display","none");
$('.search-contents').css("margin-left","1%");
$('.search-contents').css("width","97%");
$('.find-serial-phone').show();
$('.ac-field').css("padding-left","8px");
/*second page ends*/

/*third page start*/
$('.search-contents-bill-phone-version').show();
$('.search-contents-bill').hide();
/*third page ends*/
$('.billing_info .free_reg_serialnumber').css("margin-top","6%");
$('.billing_info .free_reg_serialnumber').css("width","100%");

}
else if (($(window).width() > 400) && ($(window).width() < 600))
{/*first page start*/
	$('.central-contents').css("margin","26px 0 5%");
	$('#select-plans-users').css("margin-left","1%");
	$('#select-plans-users').css("margin-right","1%");
	$('.outer-div').css("padding","0");
	$('.plan-type-feature').css("font-size","18px");
	$('.next-page-btn').css("margin","6% 1% 5%");
	$('.plan-type-label').css("padding-top","0");
	/*first page ends*/
	
	/*second page start*/
	$('.find-serial').css("display","none");
	$('.use-email').css("display","none");
	$('.search-contents').css("margin-left","2%");
	$('.search-contents').css("width","96%");
	$('.find-serial-phone').show();
	$('.ac-field').css("padding-left","8px");
	$('.btn-nxt').css("margin","3% 19% 5%");
	/*second page ends*/
	
	/*third page start*/
	$('.search-contents-bill-phone-version').show();
	$('.search-contents-bill').hide();
	/*third page ends*/
	$('.billing_info .free_reg_serialnumber').css("margin-top","6%");
	$('.billing_info .free_reg_serialnumber').css("width","100%");
	
}
else if (($(window).width() >= 600) && ($(window).width() < 1000))
{/*first page start*/
	$('.central-contents').css("margin","26px 0 5%");
	$('#select-plans-users').css("margin","3% 11% 5%");
	$('.outer-div').css("padding","0");
	$('.plan-type-feature').css("font-size","22px");
	$('.next-page-btn').css("margin","6% 11% 5%");
	$('.plan-type-label').css("padding-top","3%");
	/*first page ends*/
	
	/*second page start*/
	$('.find-serial').css("display","block");
	$('.use-email').css("display","block");
	$('.search-contents').css("margin-left","20%");
	$('.search-contents').css("width","60%");
	$('.find-serial-phone').hide();
	$('.ac-field').css("padding-left","8px");
	$('.btn-nxt').css("margin","6% 20% 5%");
	
	/*second page ends*/
	
	/*third page start*/
	$('.search-contents-bill-phone-version').hide();
	$('.search-contents-bill').show();
	$('.search-contents-bill').css("margin","2% 8% 2%");
	$('.btn-nxt1').css("margin","6% 8% 5%");
	/*third page ends*/
	$('.billing_info .use-email').css("display","none");
	$('.billing_info .free_reg_serialnumber').css("margin-top","3%");
}

else
{/*first page start*/
$('.central-contents').css("margin","26px 10% 5%");
$('#select-plans-users').css("margin-left","19%");
$('#select-plans-users').css("margin-right","19%");

$('.next-page-btn').css("margin","4% 19% 5%");
$('.plan-type-feature').css("font-size","22px");
$('.plan-type-label').css("padding-top","3%");
/*first page ends*/

/*second page start*/
$('.find-serial').css("display","block");
$('.use-email').css("display","block");
$('.search-contents').css("margin-left","20%");
$('.search-contents').css("width","60%");
$('.find-serial-phone').hide();
$('.ac-field').css("padding-left","8px");
/*second page ends*/

/*third page start*/
$('.search-contents-bill-phone-version').hide();
$('.search-contents-bill').show();
$('.search-contents-bill').css("margin","2% 17% 2%");
$('.btn-nxt1').css("margin","4% 17% 5%");
/*third page ends*/
$('.billing_info .search-contents').css("width","66%");
$('.billing_info .search-contents').css("margin-left","17%");
$('.billing_info .find-serial').css("margin-right","2%");
}
});
});

</script>


<!-- Script for email validator Starts -->
	<script>
			 $(document).ready(function() {
			 $("#UserEmail").blur(function() {
			 var email = $("#UserEmail").val();
			 $(".email-validator").css("background","url('/media/images/tt-new/load.gif') 50% 50% no-repeat");
			 $.post("/admin/emailCheck", {email:email},
			 function(result) {
			 if((result == 1 ) || (result == 2 )) {
			 //the email is not available
			 $(".email-validator").css("background","url('/media/images/new-signup/disable.png') 50% 50% no-repeat");
			 if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
			 {
				 $(".email-validator").css("background","url('/media/images/new-signup/inactive_retina.png') 50% 50% no-repeat"); 
				 $(".email-validator").css("background-size","18px 18px"); 
			 }
			 }
			 else {
				 //the email is available
				 var atpos=email.indexOf("@");
				 var dotpos=email.lastIndexOf(".");
				if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length){
					 $(".email-validator").css("background","url('/media/images/new-signup/disable.png') 50% 50% no-repeat");
				} else {
					 $(".email-validator").css("background","url('/media/images/new-signup/enable.png') 50% 50% no-repeat");
					 if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
			 		{
						 $(".email-validator").css("background","url('/media/images/new-signup/active_retina.png') 50% 50% no-repeat"); 
						 $(".email-validator").css("background-size","18px 18px"); 
					 }
				}
			 }
			 });
			 });
			 $("#UserEmail").focus(function() {
				 $(".email-validator").css("background","url('/media/images/new-signup/disable.png') 50% 50% no-repeat");
				 if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
				 {
					 $(".email-validator").css("background","url('/media/images/new-signup/inactive_retina.png') 50% 50% no-repeat"); 
					 $(".email-validator").css("background-size","18px 18px"); 
				 }
				 });
			 });

			 if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
				{
					var images = $('img.hd');   
				for(var i=0; i < images.length; i++)
				{
				images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/logo.png', '/media/images/tt-new/aec-logo.png'))
				$('.hd').css('width','42px')
				$('.hd').css('height','29px')
				}
				}
					</script>

<!-- Script for email validator Ends -->