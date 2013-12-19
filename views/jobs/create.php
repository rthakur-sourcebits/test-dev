<style>
@media all and (min-width: 600px) and (max-width: 1000px){
.dp-popup{
	left:43px !important;
}
}
</style>

	<link href="/media/css/sales.css" rel="stylesheet">
	<?php echo $header;?>
	<?php 
	echo $popups_sales; 
	?>
	<div class="row-fluid" id="row-fluid" >
		<form method="post" action="#" id="create_jobs">
		<?php if(isset($error)) {?>
		<div class="error_job heavy">
			<?php echo $error?>
		</div>
		<?php } ?>
		<div class="add-info-column-1 width-header margin-left-1 white-background white-shadow">
				<div class="outer_div_header bor-right">
					<label class="header-job-line1 regular-font left">Header Job</label>
					<a class="job_select header right"></a>
				</div>
				<div class="outer_div_header width-50">
					<a class="job_select detail right"></a>
					<label class="header-job-line1 width-70 regular-font left">Detail Job</label>
				</div>
				<input type="hidden" value="<?php echo $is_header;?>" id="is_header" name="is_header" />
			</div>
			
			<div class="add-info-column-2 white-shadow margin-right-1" >
				<div class="desc-add-sales-info"><label class="regular-font left ">Description</label>
					<textarea tabindex="8" class="description-area" rows="5" name="description"><?php echo trim($description);?></textarea>
				</div>
			</div>
				
			<div class="info-column-1 margin-left-1" style="height:228px;margin-top:20px;">
				<div class="info sub-job">
					<label class="subject_job medium-font">Number</label>
					<input type="text" tabindex="1" class="add-jobs-input-field dark" maxlength="15" value="<?php echo $job_number;?>" name="job_number" />
				</div>
				<div class="info sub-job sub_job_outer">
					<label class="subject_job medium-font">Sub-Job of</label>
					<input type="text" tabindex="2" readonly popup_id="<?php echo SUB_JOB_POPUP;?>" class="sub_job add-jobs-input-field dark ac_input" value="<?php echo $sub_job_of;?>" name="sub_job_of"/>
					<a class="service-down-arrow1 right" href="javascript:void(0)" id="opensubjobPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
				<div class="info sub-job">
					<label class="subject_job medium-font">Name</label>
					<input type="text" tabindex="3" class="add-jobs-input-field dark" maxlength="27" value="<?php echo $job_name;?>" name="job_name" />
				</div>
				
				
				<div class="add-sales-info mar-7 white-background track_reimb" style="display:none;">
					<div class="left width-50 bor-right height-46 inac-job">
						<label class="job-line1-inactive regular-font">Inactive Job</label>
						<?php $active_jobs= $is_inactive;
						if($active_jobs == 1)
						{
						    $link_active="enable";
						    $class_job="job-disable1 enable1";
						}
						else
						{
						    $link_active="disable";
						    $class_job="enable1";
						}
						?>
						<a class="job-line-enable-disable inactive-job-enable" href="javascript: void(0)"><img tabindex="4" src="/media/images/tt-new/<?php echo $link_active;?>.png" class="create-job-enable-disable1 <?php echo $class_job;?>"></a>
						<input type="hidden" value="<?php echo $is_inactive; ?>" id="is_inactive" name="is_inactive" />
					</div>
					<label class="job-line2 regular-font text-align-left pad-left-12 pad-top-create-mobile">Track Reimbursables</label>
					<?php $track_reimburse= $track_reimbursable;
						if($track_reimburse == 1)
						{
						    $track="enable";
						     $class_track="job-disable enable";
						}
						else
						{
						    $track="disable";
						     $class_track="enable";
						}
						?>
					 <a class="job-line-enable-disable track-reimburse-enable" href="javascript: void(0)"><img tabindex="5" src="/media/images/tt-new/<?php echo $track;?>.png" class="create-job-enable-disable <?php echo $class_track;?>"></a>
					<input type="hidden" value="<?php echo $track_reimbursable; ?>" id="track_reimbursable" name="track_reimbursable" />
				</div>
			
					<input type="hidden" class="s-date" id="datestart" value="<?php echo $start_date;?>" name="start_date">
					<input type="hidden" id="dateend"class="e-date" value="<?php echo $finish_date;?>" name="finish_date">
			</div>
			
			<div class="clear"></div>
			
			<div class="info-column-2 margin-right-1" >
				<div class="info sub-job">
					<label class="line1 medium-font">Contact</label>
					<input type="text" tabindex="9" class="add-jobs-input-field dark" value="<?php echo $contact;?>" name="contact"/>
				</div>
				<div class="info sub-job percentComplete" style="display:none;">
					<label class="line1 medium-font">Percent Complete</label>
					<input type="text" tabindex="10" class="add-jobs-input-field dark" value="<?php echo $percentage;?>" name="percentage" />
				</div>
				
				<div class="info sub-job">
					<label class="line1 medium-font">Manager</label>
					<input type="text" tabindex="11" class="add-jobs-input-field dark" value="<?php echo $manager;?>" name="manager" />
				</div>
				
				<div class="linked info sub-job linkedCustomer" style="display:none;">
					<label class="line1 medium-font">Linked Customer</label>
					<div class ="change-color-custom4 left">
						<input type="text" readonly popup_id="<?php echo SALESPERSON_POPUP;?>" tabindex="12" id="linked" class="linked-cust-field add-jobs-input-field dark" value="<?php echo $linked_customer_name;?>">
						<input type="hidden" name="linked_customer" id="linked_customer" class="linked-field add-jobs-input-field dark" value="<?php echo $linked_customer?>">
						<a class="service-down-arrow1 right" href="javascript:void(0)" id="openLinkedPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a>
					</div>
				</div>
				
			<div class="cancel-save-buttons">
    				<div class="save"><input id="save" type="submit" class="submit-save-button" value="Save" name="submit" onclick="start_end_convert();"></div>
    				<a href="<?php echo SITEURL?>/jobs" class="">
        				<div class="cancel-btn cancel-save-labels">Cancel
        				</div>
    				</a>
				</div>
			</div>
			 <?php if(isset($job_id)) {?>
					<input type="hidden" value="<?php echo $job_id;?>" name="job_id" />
	         <?php }?>
			</form>
			
			<div class="clear"></div>
				<div class="add-sales-info white-background dates">
					<div class="left inac-job width-50 bor-right height-46 start_date">
						<label class="job-line1-inactive regular-font">Start Date</label>  
						<form id="chooseDateForm" name="chooseDateForm" class=""> 
						<input type="text" tabindex="" id="datepicker" class="s-date date-label" readonly="readonly" value="<?php if($add_field == '1'){echo "";} else if($start_date=='0000-00-00'){echo '';}else {echo date("F d, Y", strtotime($start_date));}?>" style="margin-top:0 !important;height:36px !important;text-align:left;"/>
						</form>  
					</div> 
					<label class="job-line2-end regular-font text-align-left pad-left-12">Finish Date</label> 
					<form id="chooseDateForm" name="chooseDateForm" class="end_date"> 
						<input type="text" tabindex="" id="datepicker" class="e-date date-label" readonly="readonly" value="<?php  if($add_field == '1'){echo "";} else if($finish_date=='0000-00-00'){echo '';}else {echo date("F d, Y", strtotime($finish_date));}?>" style="margin-top:2px !important;height:36px !important;text-align:left;"/>
					 </form>  
				</div> 
				<div class="clear"></div>
				<div class="mar-bot4"></div>
		</div>

<script type="text/javascript">
		$(document).ready(function()
		{
			$('.dp-choose-date').css('cssText','background:url("/media/images/tt-new/allsales-arrow-down.png") no-repeat scroll 0 90% transparent');
		//	$('.ui-switch-labels .ui-switch-on').text('Yes');
		//	$('.ui-switch-labels .ui-switch-off').text('No');
		//	$('.ui-switch-labels a').css('cssText','font-family:HelveticaNeueMedium !important');
			if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
			{
				$('.dp-choose-date').css('cssText','background:url("/media/images/tt-new/arrow-down-retina.png") no-repeat scroll 0 90% transparent');
				$('.dp-choose-date').css('background-size','10px 6px');
			}
		});
</script>

	<script>
		$(document).ready(function()
		{
		if(location.href.indexOf('edit')!=-1){
			if($('#is_header').val()=='1'){
				$('.header').addClass('select_job');
				$('.dates').css('margin-top','0px');
			}else{
				$('.detail').addClass('select_job');
				$('.track_reimb,.percentComplete,.linkedCustomer').show();
				$('.dates').css('margin-top','-60px');
			}
		}
		if(location.href.indexOf('create')!=-1){
			$('.header').addClass('select_job');
			$('#is_header').val('1');
		}
		var start_date_width = $('.start_date').width()-124;
		$('.s-date').css('cssText','width:'+start_date_width+'px !important');
		$('.e-date').css('cssText','width:'+start_date_width+'px !important');
		
		if($(window).width() < 600) 
		{
		$('.info-column-1').css("width","100%");//42
		$('.info-column-2').css("width","100%");//42		
		$('.info').css("padding-bottom","2px");//2			
		$('.details-button').css("margin-right","0");//8
		$('.cancel-save-buttons').css("padding-left","0");//38
		$('.cancel-save-buttons').css("padding-right","0");//8
		// $('.font-synced').text('Sync');
		}

		else if (($(window).width() > 600) && ($(window).width() < 780))
		{
		$('.info-column-1').css("width","98%");//42
		$('.info-column-2').css("width","98%");//42		
		$('.info').css("padding-bottom","4px");//2	
		$('.details-button').css("margin-right","0");//8
		$('.cancel-save-buttons').css("padding-left","0");//38
		$('.cancel-save-buttons').css("padding-right","0");//8
		// $('.font-synced').text('To Be Synced');
		
		}
		
		else if (($(window).width() > 780) && ($(window).width() < 1000))
		{
		$('.info-column-1').css("width","44%");//42
		$('.info-column-2').css("width","44%");//42		
		$('.info').css("padding-bottom","4px");//2	
		$('.details-button').css("margin-right","0");//8
		$('.cancel-save-buttons').css("padding-left","0");//38
		$('.cancel-save-buttons').css("padding-right","0");//8
		// $('.font-synced').text('To Be Synced');
		
		}
		else if (($(window).width() > 1000) && ($(window).width() < 1050))
		{
		$('.info-column-1').css("width","44%");//42
		$('.info-column-2').css("width","44%");//42		
		$('.info').css("padding-bottom","2px");//2	
		$('.details-button').css("margin-right","8%");//8
		$('.cancel-save-buttons').css("padding-left","38%");//38
		$('.cancel-save-buttons').css("padding-right","0");//8
		// $('.font-synced').text('To Be Synced');
		
		}
		else
		{
		$('.info-column-1').css("width","44%");//42
		$('.info-column-2').css("width","44%");//42		
		$('.info').css("padding-bottom","2px");//2	
		$('.details-button').css("margin-right","8%");//8
		$('.cancel-save-buttons').css("padding-left","38%");//38
		$('.cancel-save-buttons').css("padding-right","0%");//8
		// $('.font-synced').text('To Be Synced');
		
		}
		$(window).resize(function()
		{
			var start_date_width = $('.start_date').width()-124;
			$('.s-date').css('cssText','width:'+start_date_width+'px !important');
			$('.e-date').css('cssText','width:'+start_date_width+'px !important');
		if($(window).width() < 600) 
		{
			$('.info-column-1').css("width","100%");//42
			$('.info-column-2').css("width","100%");//42		
			$('.info').css("padding-bottom","2px");//2			
			$('.details-button').css("margin-right","0");//8
			$('.cancel-save-buttons').css("padding-left","0");//38
			$('.cancel-save-buttons').css("padding-right","0");//8
			// $('.font-synced').text('Sync');
			}

			else if (($(window).width() > 600) && ($(window).width() < 780))
			{
			$('.info-column-1').css("width","98%");//42
			$('.info-column-2').css("width","98%");//42		
			$('.info').css("padding-bottom","4px");//2	
			$('.details-button').css("margin-right","0");//8
			$('.cancel-save-buttons').css("padding-left","0");//38
			$('.cancel-save-buttons').css("padding-right","0");//8
			// $('.font-synced').text('To Be Synced');
			
			}
			
			else if (($(window).width() > 780) && ($(window).width() < 1000))
			{
			$('.info-column-1').css("width","44%");//42
			$('.info-column-2').css("width","44%");//42		
			$('.info').css("padding-bottom","4px");//2	
			$('.details-button').css("margin-right","0");//8
			$('.cancel-save-buttons').css("padding-left","0");//38
			$('.cancel-save-buttons').css("padding-right","0");//8
			// $('.font-synced').text('To Be Synced');
			
			}
			else if (($(window).width() > 1000) && ($(window).width() < 1050))
			{
			$('.info-column-1').css("width","44%");//42
			$('.info-column-2').css("width","44%");//42		
			$('.info').css("padding-bottom","2px");//2	
			$('.details-button').css("margin-right","8%");//8
			$('.cancel-save-buttons').css("padding-left","38%");//38
			$('.cancel-save-buttons').css("padding-right","0");//8
			// $('.font-synced').text('To Be Synced');
			
			}
			else
			{
			$('.info-column-1').css("width","44%");//42
			$('.info-column-2').css("width","44%");//42		
			$('.info').css("padding-bottom","2px");//2	
			$('.details-button').css("margin-right","8%");//8
			$('.cancel-save-buttons').css("padding-left","38%");//38
			$('.cancel-save-buttons').css("padding-right","0%");//8
			// $('.font-synced').text('To Be Synced');
			
			}
		});
		});
		
/*********Script for enable disable button**************/

		$(".track-reimburse-enable").click(function(){ //normal view
			if($(".create-job-enable-disable").hasClass('job-disable')== true && $(".create-job-enable-disable").hasClass('enable')== true)
			{
				$('.enable').attr("src","/media/images/tt-new/disable.png");
				$('.enable').removeClass('job-disable');
				document.getElementById("track_reimbursable").value = '0';
    				if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
    				{
    				$('.enable').attr("src","/media/images/tt-new/inactive_retina.png");
        			$('.enable').css('width','18px')
    				$('.enable').css('height','18px')
    				}
			}
			else if($(".create-job-enable-disable").hasClass('job-disable')== false )
    		{
    			$('.enable').attr("src","/media/images/tt-new/enable.png");
    			$('.enable').addClass('job-disable');
    			document.getElementById("track_reimbursable").value = '1';
        			if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
    				{
    				$('.enable').attr("src","/media/images/tt-new/active_retina.png");
        			$('.enable').css('width','18px')
    				$('.enable').css('height','18px')
    				}
    		}
			
		});

		$(".inactive-job-enable").click(function(){ //normal view
			if($(".create-job-enable-disable1").hasClass('job-disable1')== true && $(".create-job-enable-disable1").hasClass('enable1')== true )
			{
				$('.enable1').attr("src","/media/images/tt-new/disable.png");
				$('.enable1').removeClass('job-disable1');
				document.getElementById("is_inactive").value = '0';
    				if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
    				{
    				$('.enable1').attr("src","/media/images/tt-new/inactive_retina.png");
        			$('.enable1').css('width','18px')
    				$('.enable1').css('height','18px')
    				}
			}
			else if($(".create-job-enable-disable1").hasClass('job-disable1')== false )
			{
				$('.enable1').attr("src","/media/images/tt-new/enable.png");
				$('.enable1').addClass('job-disable1');
				document.getElementById("is_inactive").value = '1';
    				if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
    				{
    				$('.enable1').attr("src","/media/images/tt-new/active_retina.png");
        			$('.enable1').css('width','18px')
    				$('.enable1').css('height','18px')
    				}
			}
		});

		$('.job_select').live('click',function(){
			if($(this).hasClass('header')){
				if(!$(this).hasClass('select_job')){
					$(this).addClass('select_job');
					$('.detail').removeClass('select_job');
					$('#is_header').val('1');
					$('.track_reimb').hide();
					$('.percentComplete,.linkedCustomer').hide();
					$('.info-column-2').css('margin-top','1%');
					$('#create_jobs .info-column-2').css('top','70px');
					$('.dates').css('margin-top','0px');
				}
			}else{
				if(!$(this).hasClass('select_job')){
					$(this).addClass('select_job');
					$('.header').removeClass('select_job');
					$('#is_header').val('0');
					$('.track_reimb').slideDown(1000);
					$('.percentComplete,.linkedCustomer').slideDown(1000);
					$('.dates').css('top','-300px;');
					$('.info-column-2').css('margin-top','-4%');
					$('#create_jobs .info-column-2').css('top','150px');
					$('.dates').css('margin-top','0px');
				}
			}
		});
		
	</script>
