<script src="/media/scripts/jquery.switch.js" type="text/javascript"></script>

   <script type="text/javascript" src="/media/scripts/jquery.js"></script>
    
    <!-- Le syntax highlighting -->
    <link href="/media/css/prettify.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="/media/scripts/prettify.js"></script>
    <script>$(function () { prettyPrint() })</script>
    
    <!-- Le jquery switch-->
    <link href="/media/css/jquery.switch.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="/media/scripts/jquery.switch.min.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function() {

		$('select#select_rate').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
	        if(type == "off") {
				$("#rate-display").val("0");
	        } else {
	        	$("#rate-display").val("1");
	        }
	    });

		 $('select#select_jobs').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
		        if(type == "off") {
		        	$("#show-jobs").val("0");
		        } else {
		        	$("#show-jobs").val("1");
		        }
		    });

 
});
	</script>
<script type="text/javascript">

$(document).ready(function(){
	 //location.href="#user-content-section";
	 if($(window).width() < 600) {
			$('#input-medium-jobs').text("Show jobs");
		//	$('#sidebar-main').css("min-height", "0px");
			$('#user_content_section_back').show();
		}
	 else if($(window).width() > 600 && $(window).width() < 800) {
			$('#input-medium-jobs').text("Show jobs");
		//	 $('#sidebar-main').css("min-height", "664px");
		}
	 else if($(window).width() > 800 && $(window).width() < 1150) {
			$('#input-medium-jobs').text("Show jobs");
	//		 $('#sidebar-main').css("min-height", "650px");
		}
	 else
	 {
		 $('#input-medium-jobs').text("Show jobs for selected customers");
	//	 $('#sidebar-main').css("min-height", "650px");
		 // $('#user-content-section-back').hide();
	 }
		 $(window).resize(function(){
			  if($(window).width() < 600) {
				$('#input-medium-jobs').text("Show jobs");
			//	$('#sidebar-main').css("min-height", "0");
				$('#user_content_section_back').show();
			  } 
			  else if($(window).width() > 600 && $(window).width() < 800) {
					$('#input-medium-jobs').text("Show jobs");
				//	 $('#sidebar-main').css("min-height", "664px");
				}
			  else if($(window).width() > 800 && $(window).width() < 1150) {
					$('#input-medium-jobs').text("Show jobs");
			//		 $('#sidebar-main').css("min-height", "650px");
				}
			  else {
				   $('#input-medium-jobs').text("Show jobs for selected customers");
			//	   $('#sidebar-main').css("min-height", "650px");
				   $('#user_content_section_back').hide();
			  }
		 });
	
});

function changePic(chk,pic){
	if (chk.checked){
		document.pic.src="images/newimage.png";
	}else{
		document.pic.src="images/oldimage.png";
	}
}

function swap_button(chk, field)
{
	if(field == 1) {
		if ($('#rate-display-image').hasClass('on') == true) {
			$('#rate-display-image').removeClass('on');
			$('#rate-display-image').addClass('off');
			$('#rate-display').val('0');
		}
		else {
			$('#rate-display-image').removeClass('off');
			$('#rate-display-image').addClass('on');
			$('#rate-display').val('1');
		}
	} else if(field == 2) {
		if ($('#show-jobs-image').hasClass('off') == true) {
			$('#show-jobs-image').removeClass('off');
			$('#show-jobs-image').addClass('on');
			$('#show-jobs').val('1');
		}
		else {
			$('#show-jobs-image').removeClass('on').addClass('off');
			$('#show-jobs').val('0');
		}
	}

}

</script>

		<div id="text-container4" class="span10">
			<label id="name">
				<?php
					if(empty($firstname))
						echo $user_name;
					else 
						echo ($firstname)."&nbsp;".($lastname);
				?>
			</label>
			<label id="emp"><?php echo $type; ?></label>
		</div>		
		
		<form action='<?php echo SITEURL?>/admin/create' method="post" id="register" class='text_normal'>
			<div class="span10" id="text-container">
				<div class="span12" id="text-container1">
	 				<div class="input-prepend bor-bot" id="input-prepend">
	  					<span class="add-on light-font" id="input-medium-1" >Email Address</span>
	  					<input class="prependedInput2 span7" id="UserEmail" style="box-shadow : none !important;border:none !important;" name="UserEmail"  value="<?php echo stripslashes($email);?>" type="text" >
	 					
					</div>
					<div class="input-prepend bor-bot" id="input-prepend-btn">
						<span class="add-on light-font" id="input-medium-rate">Display Rate/Amount</span>
						<?php 
							if($rate_display == 1 || empty($id)) {
								$checked_rate_class =	"on";
								$rate_value			=	1;
							} else {
								$checked_rate_class =	"off";
								$rate_value			=	0;
							}
						?>
						<input type="hidden" name="rate_display" id="rate-display" value="<?php echo $rate_value;?>" />										
						<!-- <button class="btn-mini <?php echo $checked_rate_class;?>" data-toggle="buttons-checkbox" id="rate-display-image"  name="n1" type="button" onclick="swap_button(this, 1)"></button> -->
						<select style="display:none;" id="select_rate">
                        <option value="1" <?php if($rate_display == 1 || empty($id)) echo " selected='selected'"; ?>>On</option>
                        <option value="0" <?php if($rate_display == 0) echo " selected='selected'"; ?>>Off</option>
                        </select>
					</div>
					<div class="input-prepend" id="input-prepend-btn">
	  					<span class="add-on light-font" id="input-medium-jobs">Show jobs for selected customer</span>
	  					<?php
							if($show_jobs == 1 || empty($id)) {
								$show_job_class 	=	"on";
								$show_job_value		=	1;
							} else {
								$show_job_class		=	"off";
								$show_job_value		=	0;
							}
						?>
						<input type="hidden" name="show_job" id="show-jobs" value="<?php echo $show_job_value;?>" />
	  				<!--	<button class="btn-mini <?php echo $show_job_class;?>" data-toggle="buttons-checkbox" id="show-jobs-image"  name="n2" onclick="swap_button(this, 2)"  type="button" ></button>  -->
					 	<select style="display:none;" id="select_jobs">
                        <option value="1" <?php if($show_jobs == 1 || empty($id)) echo " selected='selected'"; ?>>On</option>
                        <option value="0" <?php if($show_jobs == 0) echo " selected='selected'"; ?>>Off</option>
                        </select>
					</div>
	
				</div>
				<br/>
				<?php if(isset($id)) {?>
					<div class="span12" id="text-container2">
						<div class="input-prepend bor-bot" id="input-prepend">
							<span class="add-on light-font" id="input-medium2">Total Slips Processed</span>
							<span class="add-on" id="input-medium-label1"><?php echo $user_slip_info[0]['total_slips'];?></span> 
					 
							
						</div>
						<div class="input-prepend bor-bot" id="input-prepend1">
							<span class="add-on light-font" id="input-medium1">Total Synced Slips</span>
							<span class="add-on" id="input-medium-label1"><?php echo $user_slip_info[0]['total_synced_slips'];?></span> 
					
						
						</div>
						<div class="input-prepend" id="input-prepend1">
							<span class="add-on light-font" id="input-medium1">Last Synced Date</span>
							<span class="add-on" id="input-medium-label1">
								<?php 
			                	if($user_slip_info[0]['last_sync_date'] != "") {
									echo date("dS M Y", strtotime($user_slip_info[0]['last_sync_date']));
									}
								?>
							</span> 
					
						</div>
					</div>
				<?php }?>
				<div class="clear"></div>
				<?php if(isset($id)) {?>
					<div class="reset-pass-link"><a id="reset_password" href="javascript:void(0);" onclick="reset_password_confirm();">Reset Password</a></div>
				<?php }?>
	 			<div id="controls123" class="controls pad-bot">
	 			
	 				<button class="btn btn-small margin_right" id="cancel-btn" type="button" onclick="location.href='/admin'"><span id="cancel_span">Cancel</span></button>
					<button class="btn btn-small" id="save-btn" type="button" onclick="validateRegistration()"><span id="save_span">Save</span></button> 
				</div>
				
			</div>
			 <?php 
				if(!empty($id))
				{
				?>
					<input type="hidden" name="id" value="<?php echo $id;?>" />
				<?php 
				}
				?>
				<input type='hidden' name='user_id' value='<?php echo $recordID; ?>' id="user_id" />
				<input type="hidden" name="type" value="<?php echo $type;?>" />
				<input type="hidden" autocomplete="off" class="input-1" name="FirstName" id="FirstName" maxlength='60' value="<?php echo stripslashes($firstname);?>" size="25" />
				<input type="hidden" autocomplete="off" class="input-1" name="LastName" id="LastName" maxlength='30' value="<?php echo stripslashes($lastname);?>" size="25" />
		</form>
		<div class="clearboth"></div>
	

    
			<div class="popup_admin" id='cancel_user_edit'>
				<h3 class="question">Do you want to cancel editing this card?</h3>
				<div class="alert-message">All changes made by you will be lost. This operation cannot be undone. <br/>If you want to save the changes you made, click "Save" 
					 button instead.
				</div>
				<a href="javascript:void(0);" class="radius-5 button-1 left" onclick="cancel_edit_user_alert('cancel_user_edit');">Cancel</a>
				<a href="javascript:void(0);" class="radius-5 alert-save right" onclick="save_user_form()">Save</a>
				<a href="javascript:void(0);" class="radius-5 button-1 right" onclick="do_not_save_redirect();">Don't Save</a>
			</div>
			<div class="popup_admin" id='delete_user'> 	
				<h3 class="question">Do you want to delete <?php echo stripslashes($firstname);?>&nbsp;<?php echo stripslashes($lastname);?>?</h3>
				<div class="alert-message">Deleting this user will remove his records from the system. This operation cannot be undone.</div>
				<a href="javascript:void(0);" class="radius-5 button-1 right" onclick="cancel_gray_out('delete_user');">Cancel</a>
				<a href="javascript:void(0);" class="radius-5 button-1 right" onclick="delete_user_submit('<?php echo SITEURL?>/admin/delete');">Delete</a>
			</div>
		