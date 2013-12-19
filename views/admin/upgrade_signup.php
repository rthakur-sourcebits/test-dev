<script type="text/javascript">
$(document).ready(function(){
    applyHeight();
    $(window).resize(function(){
        applyHeight();
    });
    
    function applyHeight(){
        var wrapheight = $(window).height();
		var listheight = $(window).height() - 40;
        $('#wrapper').css('height', wrapheight + 'px');
		$('#pane1, .jScrollPaneContainer').css('height', listheight);

        $('.scroll-pane').jScrollPane({
            scrollbarWidth: 13,
            scrollbarMargin: 0
        });
    }
	
	$('table tr:last-child td').css('border-bottom','0px');
});

</script>
<div class="wrapper1 admin" id="wrapper">
    <?php  //echo $header_user_list; ?>
    <div class="container" style="float: none; margin: 0 auto; position: relative; left: 0;"> 
                <form action='<?php echo SITEURL?>/admin/updatecard' method="post" id="updatecard" class='text_normal' name="updatecard">
					<?php
					if(!empty($message)) echo "<div class='success_message'>".$message."</div><br/>";
					if(!empty($error)) echo "<div class='error_message'>".$error."</div><br/>";
					?>
					<div class='error_message' id="error_signup" style="display:none"></div><br/>
					
					<div class="table-wrapper">
					
							<div class="plan-box radius">
	                            <!-- pan1 -->
	                            <div class="plan-row">
	                                <div class="choose-plan">
	                                   Tip
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
	                                    <span class="txt-field"><input autocomplete="off" class="large required" type="text" name="company_name" id="company_name" maxlength='50' value="<?php echo $company['name']; ?>" /></span>
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                                <!-- txt end-->
									<!-- txt -->
	                                <label>
	                                    Your AccountEdge Serial Number
	                                </label>
	                                <div class="edit-con">
	                                    <span class="txt-field"><input autocomplete="off" class="large required" type="text" id="serialnumber" name="serialnumber" maxlength='15' value="" /></span><span class="txt-msg">Find your serial number <a href="http://support.accountedge.com/kb/general-accountedge/locating-your-serial-number" class="serial-no-link" target="_blank">here</a>.</span>
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                                <!-- txt end-->
									<!-- txt -->
	                                <label>
	                                    Your email
	                                </label>
	                                <div class="edit-con">
	                                    <span class="txt-field"><input autocomplete="off" class="large required" type="text" name="UserEmail" id="UserEmail" maxlength='50' value="<?php echo $company['email']; ?>" /></span><span class="txt-msg">Your email address be used for log in purpose. </span>
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                                <!-- txt end--><!-- txt -->
	                                <label>
	                                    Desired password (6-15 Characters)
	                                </label>
	                                    <div class="edit-con">
											<span class="txt-field"><input id="myPassword" name="password" class="large" type="password" maxlength='15' onkeyup="testPassword($(this).val())"/></span>
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
	                                    <span class="txt-field"><input autocomplete="off" class="large required" type="password" name="confirmpass" id="confirmpass" maxlength='15' value="" /></span>
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
	                                    <span class="txt-field2"><input autocomplete="off" class="large required" type="text" name="name" id="name" maxlength='50' value="" /></span>
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                                <!-- txt end--><!-- txt -->
	                                <div class="edit-con">
	                                    <span class="txt-label">Company name</span>
	                                    <span class="txt-field2"><input autocomplete="off" name="cname" id="cname" class="required large" type="text" value="" /></span>
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                                <!-- txt end--><!-- txt -->
	                                <div class="edit-con">
	                                    <span class="txt-label">Address</span>
	                                    <span class="txt-field2"><input autocomplete="off" class="required large" type="text" name="address" id="address" maxlength='50' value="" /></span>
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
	                                    	<input class="required large" autocomplete="off" type="text" name="city" id="city" maxlength='50' value="" /></span>
	                                      </span>
	                                    <div class="clear"></div>
	                                </div>
	                                
	                                <!-- txt end--><!-- txt -->
	                                 <div class="edit-con">
	                                    <span class="txt-label">State</span>
	                                    <span class="txt-field2"><input autocomplete="off" class="required large" type="text" name="state" id="state" maxlength='50' value="" /></span>
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                                 <div class="edit-con">
	                                    <span class="txt-label">Zip Code</span>
	                                    <span class="txt-field2"><input autocomplete="off" class="required large" type="text" name="zipcode" id="zipcode" maxlength='50' value="" /></span>
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                                 <div class="edit-con drop-down-pos">
	                                    <span class="txt-label">Country</span>
	                                    <span class="txt-field2">
	                                    <input style="" type="text" name="country_input" id="signup_country" 
											value="United States" class="required large"  onblur="if (this.value == ''){this.value = 'United States';}"  
											onfocus="if (this.value == 'United States') {this.value = '';}"/>
										<span class="drop-arrow"  id="signup_all_countries" title="Click for all countries">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>
										<div class="clear"></div>
										<input style="" type="hidden" name="country" id="signup_country_id" value="1"/>
	                                    <div id="signup_country_list" class="autocomplete" style="display:none;"></div>
	                                    
	                                </div>
	                               
	                                <!-- txt end--><!-- txt -->
	                                <div class="edit-con">
	                                    <span class="txt-label">Phone number</span>
	                                    <span class="txt-field2"><input autocomplete="off" class="required large" type="text" name="phone" id="phone" maxlength='50' value="" /></span>
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
	                        <!-- inner con --><!-- inner con -->
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
										<span class="right"><button class=" jqTransformButton" type="button" name="" id="" onclick="return submit_signup_form(this,'<?php echo SITEURL;?>');"><span><span>Sign Up</span></span></button></span> <!-- onclick="return submit_signup_form(this,'url');" -->
										
	                                    <div class="clear">
	                                    </div>
	                                </div>
	                                <!-- txt end-->
	                            </div>
	                        </div>
						
					</div>
					<div style="height:9px;"></div>
					<div class="btn-block">
					
						<a class="admin-button button_class" href="javascript:void(0);" name='update' id='save' onclick="updatecard.submit();">Update</a>
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
					<input type="hidden" name="company_id" value="<?php echo $_SESSION['company_id']?>" />
				</form>
       		
	</div>
    <div class="clear"></div>
</div>