<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.5,initial-scale=1.0" />
<script type="text/javascript">
	$(document).ready(function()
{
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.hd');   
	for(var i=0; i < images.length; i++)
	{
	images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/logo.png', '/media/images/tt-new/aec-logo.png'))
	$('.hd').css('width','42px');
	$('.hd').css('height','29px');
	}
	}
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.hd1');   
	for(var i=0; i < images.length; i++)
	{
	images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/logo2.png', '/media/images/tt-new/lock.png'))
	$('.hd1').css('width','14px');
	$('.hd1').css('height','19px');
	}
	}
});

// Hiding plan by default. 
$(document).ready(function()
{
	$(".toggle-slide-confirm").hide();
});

// Change of plan.
function changePlan(chk, plan_id)
{
	var Id=($(chk).attr("id"));
	$("#plan_check").val(plan_id);
	if ( ($("#" + Id).hasClass('change-plan-inactive') == true) && ($("#" + Id).hasClass('change-plan-active') == true))
	{
		$("#" + Id).next(".toggle-slide-confirm").toggle("slow");
		$("#" + Id).css("border-bottom","1px solid #e6e6e6");
	}
	else if ( $("#" + Id).hasClass('change-plan-inactive') == true)
	{
		$(".change-plan-active").removeClass('change-plan-active');
		$("#" + Id).removeClass('change-plan-inactive');
		$(".change-plan-inactive").css("border-bottom","1px solid #E6E6E6");
		$("#" + Id).addClass('change-plan-active');
		$("#" + Id).addClass('change-plan-inactive');
		$("#" + Id).css("border-bottom","1px solid #c0c0c0");
		$(".toggle-slide-confirm").hide();
		$("#" + Id).next(".toggle-slide-confirm").toggle("slow");
	}
}

// Initialising  Change of Plan.
$(document).ready(function() {
	var num = <?php echo $cur_plan['plan_id']; ?>
	;
	$('#plan-logo-' + num).css("background", "url('/media/images/new-signup/active-badge.png') 50% 50% no-repeat");
	$('#enable-image-' + num).css("display", "inline");
	$('#upgrade-label-' + num).text("You are already subscribed to this plan");
	$('#no-thanks-btn-' + num + ' a').text("Ok, Thanks");
	$('#upgrade-btn-' + num).css("display", "none");
	});
</script>

<style>
	body {
		background: white;
	}
	.row-fluid {
		height: 320px;
	}
	.container {
		width: 600px;
		text-align: center;
		background-color: #FFFFFF;
	}
	.login-container-fluid{
		padding-top: 7%;
	}
</style>

<div class="login-container-fluid">
  	<div class="row-fluid">
	  	<div id="container" class="container">
			<div class="image_logo_aec" id="image_logo_aec">
			<img class="login-logo" id="login-logo" onclick="window.location = '<?php echo SITEURL?>'" src="<?php echo SITEURL?>/media/images/login/logo_n_text.png">
			</div>
			
			<!-- Upgrade Company Account HTML -->
			<div id="normal-view" >
			<?php if(!empty($rerun_error)) {?>
			<div class="mtop20 email_exists" >Email Exists in Rerun. Please contact the Administrator.</div>
			<?php }?>
			<div class="mtop20 upgrade_error_page">
				<div class="portion1">
				<?php
				if (!empty($error)){
					echo "<div class='error_message'>" . $error . "</div><br/>";
				}			
		        ?>
				<div id="subscription-plan" class="subscription-plan">
					 <?php if(isset($plan_change_message)) {?>
					     <?php echo $plan_change_message; ?><span class="bold"><?php echo $cur_plan['plan_name']; ?></span>
					     <?php echo $plan_change_message1; ?>
					  <?php }
							else {
						?>
					<label class="subscription-label">You have subscribed to the <?php echo $cur_plan['plan_name']; ?> Plan. It allows you to have <?php echo $cur_plan_max_user[0]['user_limit']; ?> active users.
					</label>
					<?php } ?>
				</div>
				
				
				<form action='<?php echo SITEURL?>/admin/changeplan?flag=1' method="post" id="change_plan" name="admin_form">
				<?php 
				if(!empty($payment_stream)) 
				{
				    foreach($payment_stream as $plan) 
				    {
						if($plan['price'] != 0) 
						{
				?>
				<div id="plan-trial-<?php echo $plan['plan_id']; ?>" class="plan-trial change-plan-inactive" onclick="changePlan(this, '<?php echo $plan['plan_id']; ?>');check_downgrade_process('<?php echo SITEURL; ?>', '<?php echo $plan['plan_id']; ?>');">
					<a href="##" class="open-toggle">
					<?php if(($plan['plan_id'] == $cur_plan['plan_id'])&&(empty($_POST['selected_new_plan']))) {?>
					<?php } else {
						if(isset($_POST['selected_new_plan']) && ($_POST['selected_new_plan'] == $plan['plan_id'])) {
					?>
						<?php
								} else {
						?>
						<?php }
							}
					?>
					<div id="plan-type" class="plan-type">
					<!--Normal View INNER 1 starts-->
					<?php $new_plan_value = isset($_POST['selected_new_plan']) ? $_POST['selected_new_plan'] : ""; ?>
					<input type="hidden" name="new_plan" id="new_plan" value="<?php echo $new_plan_value; ?>" />
					<div id="plan-type-inner1" class="plan-type-inner1-normal" >
					<div id="plan-logo-<?php echo $plan['plan_id']; ?>" class="plan-logo-inactive">
					<label class="logo-plan-type-label"><?php echo ucfirst(substr($plan['name'], 0, 1)); ?></label></div>
					<label class="plan-type-label"><?php echo $plan['name']; ?> <img id="enable-image-<?php echo $plan['plan_id']; ?>" class="plan-enable-image" src="/media/images/tt-new/enable.png" style="display:none;"></label>
					<label class="plan-type-users"><?php echo $plan['user_limit']; ?> Users</label> 
					</div>
					<!--Normal View Ends-->
					
					<!--Normal View INNER 2 Starts-->
					<div id="plan-type-inner2" class="plan-type-inner2-normal">
					<label class="plan-charge-label">$<?php echo $plan['price']; ?><span id="span-plan-charge">/month</span></label>
					<?php if($plan['price'] == 0) {?>
						<label class="plan-type-validity">Trial (30 days)&nbsp;<span class="small-font">No credit card required</span></label>
					<?php } ?>
					<!-- <label class="plan-type-validity">For 30 days</label> -->
					</div>
					<!--Normal View Ends-->
					</div>
					</a>
				</div>
			
				<div id="toggle-slide-confirm" class="toggle-slide-confirm">
				<label id="upgrade-label-<?php echo $plan['plan_id']; ?>" class="upgrade">Upgrade your <?php echo $cur_plan['plan_name']; ?> Plan to <?php echo $plan['name']; ?>.</label>
				<div id="upgrade-btn-<?php echo $plan['plan_id']; ?>" class="upgrade-btn">
				<label class="label-upgrade-link"><a href="#" onclick="submit_upgrade_form();" id="plan_option_button" class="upgrade-link">Upgrade</a></label>
				</div>
				<a href="<?php echo SITEURL; ?>" class="no-thanks-link">
				<div id="no-thanks-btn-<?php echo $plan['plan_id']; ?>" class="no-thanks-btn">
				<label class="label-no-thanks-link">No, Thanks</label>
				</div>
				</a>
				</div> 
				<?php
					}
					}
					}
			    ?>
			    <input type="hidden" name="upgrade_current_plan" value="0" id="upgrade_current_plan" />
			    <input type="hidden" name="plan" value="" id="plan_check" />
				</form></div>
				<?php
					//echo "<pre>";print_r($cur_plan);die;
					if (isset($_POST['search'])) {
						$popup_display = "display:block;";
						$selected_plan = $_POST['selected_new_plan'];
						$message = "You want to downgrade from " . $cur_plan['plan_name'] . " to " . $new_company_info['name'] . " Warning: number of active users has exceeded the limit of " . $new_company_info['user_limit'] . " people. Please choose " . $new_company_info['user_limit'] . " users you would like to keep active.";
					} else {
						$popup_display = "display:none;";
						$selected_plan = "";
						$message = "";
					}
		       ?>        
		       	<div class="change-plan-popup" id="company-user-list" style="<?php echo $popup_display; ?>" >
		        	   <div class="upgrade-downgrade-parent">
							<div class="upgrade-downgrade-child">
								<div id="upgrade_plan_popup_message" class="upgrade-downgrade-col1"><?php echo $message; ?></div>
								<a class="cancel-subs-lbl mright10" href="<?php echo SITEURL?>/admin/changeplan">
								<div class="upgrade-downgrade-col2" ><div class="pad-3 cancel-subs" >
								<label class="act-dact-label">Cancel</label>
								</div>
								</a>
								</div>
							</div>
						</div>
		          <div class="clear"></div>
					<div class="margin0auto width100 mtopb20 add-border">
						<form class="jqtransform" id="" action="<?php echo SITEURL?>/admin/changeplan" method="post" name="change_plan_search_form" id="change_plan_search_form">
							
							<div id="" class="">
							  
						  		<div class="left deselect-msg mtop20 pad-3" id="change_plan_deselect_message_top"><span id="total_user_top"><?php echo $total_users[0]['total_users']; ?></span> Selected. Please deselect <span id="user_to_deactive_top"><?php echo($total_users[0]['total_users'] - $new_company_info['user_limit']); ?></span> more.
						  		</div>
						  			
								<div class="right mtop20">
									<input type="hidden" name="search_field" value="all" />
									
									<input type="hidden" name="selected_new_plan" id="slected_new_plan" value="<?php echo $selected_plan; ?>"/>
								</div>
								<a href="#" class="activate-all right mtop30" onclick="activate_all_users('<?php echo SITEURL; ?>',1);">
									<div class="deactivate-all-btn pad-3">
										<label class="act-dact-label">Activate All</label>
									</div>
								</a>
								<a href="#" class="deactivate-all right mtop30" onclick="deactivate_all_users('<?php echo SITEURL; ?>',1);">
									<div class="activate-all-btn pad-3">
										<label class="act-dact-label">Deactivate All</label>
									</div>
								</a>
							 </div>
							<div class="clear"></div>
						</form>
					</div>
					<div class="sortable-data mtop30">
						<form id="radioDemo" class="" action="" method="post">
							<div class="table-wrapper2">
								<?php $i = 1; ?>
								<?php foreach($company_users as $user) {?>
									<div class="select-deselect-users">
                                    	<div class="select-deselect-container4">
                                    		<div class="select-deselect-container3">
                                    			<div class="select-deselect-container2">
                                    				<div class="select-deselect-container1">
                                    					<div class="select-deselect-col1"><label class="label-select-deselect-titles1 dark"><?php echo $user['first_name']?></label></div>
                                    					<div class="select-deselect-col2" ><label class="label-select-deselect-titles1 light"><?php echo $user['email']?></label></div>
                                    					<div class="select-deselect-col3"><label class="label-select-deselect-titles1 light"><?php echo $user['type']?></label></div>
                                    					<div class="select-deselect-col4">
                                    					<?php if($user['status'] == 1) {?>
                                    					<button class="active-btn btn-inac" data-toggle="buttons-checkbox" checked="true" name="user_status[]" value="1" id="radio-<?php echo $i; ?>" type="button" onclick="change_user_status(this,'<?php echo $user['id']; ?>', '<?php echo SITEURL; ?>', '<?php echo $i; ?>')"></button>
                                    					<?php } else { ?>
                                    					<button class="inactive-btn btn-inac" data-toggle="buttons-checkbox" name="user_status[]" value="0" id="radio-<?php echo $i; ?>" type="button" onclick="change_user_status(this,'<?php echo $user['id']; ?>', '<?php echo SITEURL; ?>', '<?php echo $i; ?>')"></button>
                                    					<?php } ?>
                                    					</div>
                                    				</div>
                                    			</div>
                                    		</div>
                                    	</div>
	                            <?php $i++; ?>
								<?php } ?>
								 </div>
							</div>
							<div class="clear"></div>
							<div class="">
								<a class="downgrade-subs-lbl buttons-new" href="#" onclick="check_downgrade_process('<?php echo SITEURL; ?>');" id="change_plan_downgrade_button" style="display:none;float:right;">
									<div class="pad-3 downgrade-subs" >
										<label class="downgrade-subs-lbl">
											Downgrade
										</label>
									</div>
								</a>
							</div>
						</form>
					</div>
				</div>
			</div>
			</div>
			<div class="overlay-upgrade" id="change_plan_overlay"></div>
			<!-- Upgrade Company Account HTML Ends-->
	   </div>	
   </div>
</div>
<style>
	.popup, .popup_admin {
		top: auto;
		margin-left: 0;
		padding: 0;
		margin-top: 2%;
		width: 55%;
		background: none;
	}
	.btn-inac {
		margin-top: 6%;
		margin-left: 2% !important;
	}
	.right {
		margin-top: -2px;
		padding-right: 2%;
	}
	.left {
		padding-left: 2%;
	}
</style>
<script>
	$(document).ready(function() {
		if ($(window).width() < 400) {
			$('.table-wrapper2').css("width", "100%");
			//100
			$('.select-deselect-col1').css("width", "33%");
			//18
			$('.select-deselect-col1').css("left", "66%");
			//81
			$('.select-deselect-col2').css("width", "59%");
			//38
			$('.select-deselect-col2').css("left", "70%");
			//83
			$('.select-deselect-container1').css("right", "64%");
			//38
			$('.select-deselect-col3').css("width", "33%");
			//20
			$('.select-deselect-col3').css("left", "66%");
			//83
			$('.select-deselect-container2').css("right", "0");
			//22
			$('.select-deselect-container3').css("right", "0");
			//20
			$('.select-deselect-container2').css("border-right", "none");
			//1px solid #d4d4d4
			$('.select-deselect-container3').css("border-right", "none");
			//1px solid #d4d4d4
			$('.select-deselect-col4').css("width", "42%");
			//18
			$('.select-deselect-col4').css("left", "80%");
			//85
			$('.upgrade-downgrade-parent').css("width", "100%");
			//100
			$('.upgrade-downgrade-parent').css("margin-top", "25%");
			//0
			$('.upgrade-downgrade-col1').css("left", "20%");
			//21
			$('.upgrade-downgrade-col1').css("width", "100%");
			//79
			$('.upgrade-downgrade-col2').css("width", "100%");
			//19
			$('.jqtransform').css("width", "100%");
			//100
			$('.jqtransform').css("margin-bottom", "6%");
			//0
			$('.left').css("padding-right", "0%");
			//0
			$('.toggle-slide-confirm').css("height", "108px");
			$('.plan-charge-label').css("font-size", "17px");
			$('.plan-type-inner2-normal').css("width", "34%");
		} else if (($(window).width() > 400) && ($(window).width() < 600)) {
			$('.table-wrapper2').css("width", "100%");
			$('.select-deselect-col1').css("width", "33%");
			$('.select-deselect-col1').css("left", "66%");
			$('.select-deselect-col2').css("width", "59%");
			$('.select-deselect-col2').css("left", "70%");
			$('.select-deselect-container1').css("right", "64%");
			$('.select-deselect-col3').css("width", "33%");
			$('.select-deselect-col3').css("left", "66%");
			$('.select-deselect-container2').css("right", "0");
			$('.select-deselect-container3').css("right", "0");
			$('.select-deselect-container2').css("border", "none");
			$('.select-deselect-container3').css("border", "none");
			$('.select-deselect-col4').css("width", "42%");
			$('.select-deselect-col4').css("left", "80%");
			$('.upgrade-downgrade-parent').css("width", "100%");
			$('.upgrade-downgrade-parent').css("margin-top", "25%");
			$('.upgrade-downgrade-col1').css("left", "20%");
			$('.upgrade-downgrade-col1').css("width", "100%");
			$('.upgrade-downgrade-col2').css("width", "100%");
			$('.jqtransform').css("width", "100%");
			$('.jqtransform').css("margin-bottom", "6%")
			$('.left').css("padding-right", "10%");
			$('.toggle-slide-confirm').css("height", "106px");
			//$('.plan-type').css("padding-top","2%");
			$('.plan-charge-label').css("font-size", "17px");
			$('.plan-type-inner2-normal').css("width", "34%");
		} else if (($(window).width() > 600) && ($(window).width() < 900)) {
			$('.table-wrapper2').css("width", "100%");
			$('.select-deselect-col1').css("width", "33%");
			$('.select-deselect-col1').css("left", "66%");
			$('.select-deselect-col2').css("width", "59%");
			$('.select-deselect-col2').css("left", "70%");
			$('.select-deselect-container1').css("right", "64%");
			$('.select-deselect-col3').css("width", "33%");
			$('.select-deselect-col3').css("left", "66%");
			$('.select-deselect-container2').css("right", "0");
			$('.select-deselect-container3').css("right", "0");
			$('.select-deselect-container2').css("border", "none");
			$('.select-deselect-container3').css("border", "none");
			$('.select-deselect-col4').css("width", "42%");
			$('.select-deselect-col4').css("left", "80%");
			$('.upgrade-downgrade-parent').css("width", "100%");
			$('.upgrade-downgrade-parent').css("margin-top", "2%");
			$('.upgrade-downgrade-col1').css("left", "20%");
			$('.upgrade-downgrade-col1').css("width", "100%");
			$('.upgrade-downgrade-col2').css("width", "100%");
			$('.jqtransform').css("width", "100%");
			$('.jqtransform').css("margin-bottom", "6%")
			$('.left').css("padding-right", "10%");
			$('.toggle-slide-confirm').css("height", "106px");
			//$('.plan-type').css("padding-top","2%");
			$('.plan-charge-label').css("font-size", "17px");
			$('.plan-type-inner2-normal').css("width", "34%");

		} else {
			$('.table-wrapper2').css("width", "100%");
			//100
			$('.select-deselect-col1').css("width", "18%");
			//18
			$('.select-deselect-col1').css("left", "81%");
			//81
			$('.select-deselect-col2').css("width", "38%");
			//38
			$('.select-deselect-col2').css("left", "83%");
			//83
			$('.select-deselect-container1').css("right", "38%");
			//38
			$('.select-deselect-col3').css("width", "20%");
			//20
			$('.select-deselect-col3').css("left", "83%");
			//83
			$('.select-deselect-container2').css("right", "22%");
			//22
			$('.select-deselect-container3').css("right", "20%");
			//20
			$('.select-deselect-container2').css("border-right", "1px solid #d4d4d4");
			//1px solid #d4d4d4
			$('.select-deselect-container3').css("border-right", "1px solid #d4d4d4");
			//1px solid #d4d4d4
			$('.select-deselect-col4').css("width", "18%");
			//18
			$('.select-deselect-col4').css("left", "85%");
			//85
			$('.upgrade-downgrade-parent').css("width", "100%");
			//100
			$('.upgrade-downgrade-parent').css("margin-top", "0%");
			//0
			$('.upgrade-downgrade-col1').css("left", "21%");
			//21
			$('.upgrade-downgrade-col1').css("width", "79%");
			//79
			$('.upgrade-downgrade-col2').css("width", "19%");
			//19
			$('.jqtransform').css("width", "100%");
			//100
			$('.jqtransform').css("margin-bottom", "0%");
			//0
			$('.left').css("padding-right", "0%");
			//0
			$('.toggle-slide-confirm').css("height", "96px");
			$('.plan-type').css("padding-top", "0");
			$('.plan-charge-label').css("font-size", "22px");
			$('.plan-type-inner2-normal').css("width", "24%");
		}

		$(window).resize(function() {
			if ($(window).width() < 400) {
				$('.table-wrapper2').css("width", "100%");
				//100
				$('.select-deselect-col1').css("width", "33%");
				//18
				$('.select-deselect-col1').css("left", "66%");
				//81
				$('.select-deselect-col2').css("width", "59%");
				//38
				$('.select-deselect-col2').css("left", "70%");
				//83
				$('.select-deselect-container1').css("right", "64%");
				//38
				$('.select-deselect-col3').css("width", "33%");
				//20
				$('.select-deselect-col3').css("left", "66%");
				//83
				$('.select-deselect-container2').css("right", "0");
				//22
				$('.select-deselect-container3').css("right", "0");
				//20
				$('.select-deselect-container2').css("border-right", "none");
				//1px solid #d4d4d4
				$('.select-deselect-container3').css("border-right", "none");
				//1px solid #d4d4d4
				$('.select-deselect-col4').css("width", "42%");
				//18
				$('.select-deselect-col4').css("left", "80%");
				//85
				$('.upgrade-downgrade-parent').css("width", "100%");
				//100
				$('.upgrade-downgrade-parent').css("margin-top", "25%");
				//0
				$('.upgrade-downgrade-col1').css("left", "20%");
				//21
				$('.upgrade-downgrade-col1').css("width", "100%");
				//79
				$('.upgrade-downgrade-col2').css("width", "100%");
				//19
				$('.jqtransform').css("width", "100%");
				//100
				$('.jqtransform').css("margin-bottom", "6%");
				//0
				$('.left').css("padding-right", "0%");
				//0
				$('.toggle-slide-confirm').css("height", "108px");
				//$('.plan-type').css("padding-top","2%");
				$('.plan-charge-label').css("font-size", "17px");
				$('.plan-type-inner2-normal').css("width", "34%");
			} else if (($(window).width() > 400) && ($(window).width() < 600)) {
				$('.table-wrapper2').css("width", "100%");
				$('.select-deselect-col1').css("width", "33%");
				$('.select-deselect-col1').css("left", "66%");
				$('.select-deselect-col2').css("width", "59%");
				$('.select-deselect-col2').css("left", "70%");
				$('.select-deselect-container1').css("right", "64%");
				$('.select-deselect-col3').css("width", "33%");
				$('.select-deselect-col3').css("left", "66%");
				$('.select-deselect-container2').css("right", "0");
				$('.select-deselect-container3').css("right", "0");
				$('.select-deselect-container2').css("border", "none");
				$('.select-deselect-container3').css("border", "none");
				$('.select-deselect-col4').css("width", "42%");
				$('.select-deselect-col4').css("left", "80%");
				$('.upgrade-downgrade-parent').css("width", "100%");
				$('.upgrade-downgrade-parent').css("margin-top", "25%");
				$('.upgrade-downgrade-col1').css("left", "20%");
				$('.upgrade-downgrade-col1').css("width", "100%");
				$('.upgrade-downgrade-col2').css("width", "100%");
				$('.jqtransform').css("width", "100%");
				$('.jqtransform').css("margin-bottom", "6%")
				$('.left').css("padding-right", "10%");
				$('.toggle-slide-confirm').css("height", "106px");
				//	$('.plan-type').css("padding-top","2%");
				$('.plan-charge-label').css("font-size", "17px");
				$('.plan-type-inner2-normal').css("width", "34%");
			} else if (($(window).width() > 600) && ($(window).width() < 900)) {
				$('.table-wrapper2').css("width", "100%");
				$('.select-deselect-col1').css("width", "33%");
				$('.select-deselect-col1').css("left", "66%");
				$('.select-deselect-col2').css("width", "59%");
				$('.select-deselect-col2').css("left", "70%");
				$('.select-deselect-container1').css("right", "64%");
				$('.select-deselect-col3').css("width", "33%");
				$('.select-deselect-col3').css("left", "66%");
				$('.select-deselect-container2').css("right", "0");
				$('.select-deselect-container3').css("right", "0");
				$('.select-deselect-container2').css("border", "none");
				$('.select-deselect-container3').css("border", "none");
				$('.select-deselect-col4').css("width", "42%");
				$('.select-deselect-col4').css("left", "80%");
				$('.upgrade-downgrade-parent').css("width", "100%");
				$('.upgrade-downgrade-parent').css("margin-top", "2%");
				$('.upgrade-downgrade-col1').css("left", "20%");
				$('.upgrade-downgrade-col1').css("width", "100%");
				$('.upgrade-downgrade-col2').css("width", "100%");
				$('.jqtransform').css("width", "100%");
				$('.jqtransform').css("margin-bottom", "6%")
				$('.left').css("padding-right", "10%");
				$('.toggle-slide-confirm').css("height", "106px");
				//$('.plan-type').css("padding-top","2%");
				$('.plan-charge-label').css("font-size", "17px");
				$('.plan-type-inner2-normal').css("width", "34%");
			} else {
				$('.table-wrapper2').css("width", "100%");
				//100
				$('.select-deselect-col1').css("width", "18%");
				//18
				$('.select-deselect-col1').css("left", "81%");
				//81
				$('.select-deselect-col2').css("width", "38%");
				//38
				$('.select-deselect-col2').css("left", "83%");
				//83
				$('.select-deselect-container1').css("right", "38%");
				//38
				$('.select-deselect-col3').css("width", "20%");
				//20
				$('.select-deselect-col3').css("left", "83%");
				//83
				$('.select-deselect-container2').css("right", "22%");
				//22
				$('.select-deselect-container3').css("right", "20%");
				//20
				$('.select-deselect-container2').css("border-right", "1px solid #d4d4d4");
				//1px solid #d4d4d4
				$('.select-deselect-container3').css("border-right", "1px solid #d4d4d4");
				//1px solid #d4d4d4
				$('.select-deselect-col4').css("width", "18%");
				//18
				$('.select-deselect-col4').css("left", "85%");
				//85
				$('.upgrade-downgrade-parent').css("width", "100%");
				//100
				$('.upgrade-downgrade-parent').css("margin-top", "0%");
				//0
				$('.upgrade-downgrade-col1').css("left", "21%");
				//21
				$('.upgrade-downgrade-col1').css("width", "79%");
				//79
				$('.upgrade-downgrade-col2').css("width", "19%");
				//19
				$('.jqtransform').css("width", "100%");
				//100
				$('.jqtransform').css("margin-bottom", "0%");
				//0
				$('.left').css("padding-right", "0%");
				//0
				$('.toggle-slide-confirm').css("height", "96px");
				$('.plan-type').css("padding-top", "0");
				$('.plan-charge-label').css("font-size", "22px");
				$('.plan-type-inner2-normal').css("width", "30%");
			}
		});
	}); 
</script>
