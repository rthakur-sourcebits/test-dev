<?php 
	echo HTML::script('media/scripts/custom-form-elements.js');
	echo HTML::script('media/scripts/jquery.validate.pack.js');
	echo html::script('media/scripts/jquery.form.js');
	echo html::script('media/scripts/jquery.color.js');
	echo html::script('media/scripts/jqtransform.js');
	echo html::script('media/scripts/jquery-ui-1.8.4.custom.min.js');
	echo html::script('media/scripts/jquery.autocomplete.js');
	echo html::style('media/css/jquery.autocomplete.css');
	
?>
<script type="text/javascript">
function register(site_url)
{
	var fname 		= $("#fname").val();
	var lname 		= $("#lname").val();
	var email 	 	= $("#email").val();
	var password   	= $("#password").val();
	
	$.post(site_url+"/register/submit", { fname: fname, lname: lname, email: email,
		password: password},
	    function(data){ alert(data);
	        $("payment_form").show();
	        document.getElementById("payment_form").innerHTML=	"<iframe src='"+data+"' />";
	       
	    }, "xml");
}
</script>





<div class="wrapper" id="wrapper" style="overflow: visible;">
     <div class="header">
        <h1 class="logo"><a href="<?php echo SITEURL?>/admin">Time Tracker</a></h1>
     </div>
     <div class="container" style="float: none; margin: 0 auto; position: relative; left: 0;"> 
         <form action='<?php echo SITEURL?>/register/submit' method="post" id="company_create" class='text_normal jqtransform' name="admin_form">
			<?php
					if(isset($error))
					{
						echo "<div class='error_message'>".$error."</div><br/>";
					}
					?>
					<div class='error_message' style="display:none"></div><br/>
               <!-- MAIN CONTENTS BEGINS -->
                    <div class="merchant-register">
                        <!-- inner con -->
                       
                       <div class="coloum-con">
                            
                            <div class="clear">
                            </div>
                        </div>
                        <!-- inner con -->
                        <div class="plan-box radius">
                            <!-- pan1 -->
                            <div class="plan-row">
                                
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
                            
                                             
                            
                            	<label>
                                    Select Signup Plan
                                </label>
                                <div class="edit-con">
                                    <span class="txt-field">
                                    	 <select name="plan" class="large required" id="plan">
                                    		<option value="0">-Select-</option>
	                                    	<?php 
	                                    	foreach($payment_stream as $plan) {
	                                    		echo "<option value='".$plan['plan_id']."'>".$plan['name']."</option>";
	                                    	}
	                                    	?>
                                    	</select>
                                    </span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt -->
                                <label>
                                    Your Company's name
                                </label>
                                <div class="edit-con">
                                    <span class="txt-field"><input autocomplete="off" class="large required" type="text" name="company_name" id="company_name" maxlength='50' value="" /></span>
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
                                    <span class="txt-field"><input autocomplete="off" class="large required" type="text" name="UserEmail" id="UserEmail" maxlength='50' value="" /></span><span class="txt-msg">Your email address be used for log in purpose. </span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt end--><!-- txt -->
                                <label>
                                    Desired password (6-15 Characters)
                                </label>
                                    <div class="edit-con">
										<span class="txt-field"><input id="myPassword" name="password" class="large" type="password" maxlength='15'/></span>
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
                                    <span class="txt-label">First name</span>
                                    <span class="txt-field2"><input autocomplete="off" class="large required" type="text" name="name" id="name" maxlength='50' value="" /></span>
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt end--><!-- txt -->
                                <div class="edit-con">
                                    <span class="txt-label">Last name</span>
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
									<span class="right"><button class=" jqTransformButton" type="submit" name="" id=""><span><span>Sign Up</span></span></button></span> <!-- onclick="return submit_signup_form(this,'url');" -->
									
                                    <div class="clear">
                                    </div>
                                </div>
                                <!-- txt end-->
                            </div>
                        </div>
                        <!-- inner con -->
                        <!-- MAIN CONTENTS ENDS -->
                        <div class="clear">
                        </div>
                    </div>
                    <input type="hidden" name="country" value="2" />
                </form>
                <!-- MAIN CONTAINER ENDS -->
            </div>
            <div class="clear"></div>
</div>