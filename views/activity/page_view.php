<!-- 
 * @File : page_view.php
 * @view : Page view for Activity sheets
 * @Author: 
 * @Created: 01-12-2012
 * @Modified:  
 * @Description: View file for pagination if Activitysheets.
   Copyright (c) 2012 Acclivity Group LLC 
-->
<script type="text/javascript">
$(document).ready(function(){

	if($(window).width() >= 1218) {
	
		
		$(".synced-btn").css("margin-top","-8px");
		$(".label_units").show();
		$(".label_rate").show();
		$(".arrow-right-date").hide();
		$(".arrow-right").show();
		$(".sync-btn-page3").css("margin-top","0px");
		$(".bor-rig").show();
		$(".ver-no2").show();
		$(".hidden-area").hide();
		$(".iphoneView").hide();
		$(".normal-view").show();
		
		$(".name-activity-first").css("border-right","1px solid #dfdfdf");
		$(".name-activity-first").css("width","24%");
		$(".name-activity-first").css("float","left");
		$(".name-activity-date").css("border-right","1px solid #dfdfdf");
		$(".name-activity-date").css("width","16%");
		$(".name-activity-date").css("float","left");
		$(".name-activity-date").css("margin","0 28%");
		$(".name-activity-date").css("min-height","100%");
		$(".name-activity-date").css("position","absolute");
		$(".name-activity-first1").css("border-right","1px solid #dfdfdf");
		$(".name-activity-first1").css("width","24%");
		$(".name-activity-first1").css("float","left");
		
		$(".name-activity-date1").css("border-right","1px solid #dfdfdf");
		$(".name-activity-date1").css("width","16%");
		$(".name-activity-date1").css("float","left");
		$(".name-activity-date1").css("margin","0 28%");
		$(".name-activity-date1").css("min-height","100%");
		$(".name-activity-date1").css("position","absolute");
		
		$(".name-activity-right1").css("border","none");
		$(".name-activity-right1").css("float","left");
		$(".name-activity-right1").css("width","20%");
		$(".name-activity-right1").css("margin","0 48%");
		$(".name-activity-right1").css("min-height","100%");
		$(".name-activity-right1").css("border-right","1px solid #dfdfdf");
		$(".name-activity-right1").css("position","absolute");
		
		$(".name-activity-right").css("border","none");
		$(".name-activity-right").css("float","left");
		$(".name-activity-right").css("width","20%");
		$(".name-activity-right").css("margin","0 48%");
		$(".name-activity-right").css("min-height","100%");
		$(".name-activity-right").css("border-right","1px solid #dfdfdf");
		$(".name-activity-right").css("position","absolute");
		$(".name-activity-right").css("min-height","100%");
		$(".name-activity").css("border","none");
		$(".name-activity").css("width","24%");
		$(".name-activity").css("float","left");
		$(".name-activity").css("margin","0 70%");
		$(".name-activity").css("min-height","100%");
		$(".name-activity").css("position","absolute");
		
		$(".name-activity1").css("border","none");
		$(".name-activity1").css("width","24%");
		$(".name-activity1").css("float","left");
		$(".name-activity1").css("margin","0 70%");
		$(".name-activity1").css("min-height","100%");
		$(".name-activity1").css("position","absolute");
		$(".sync-btn-page3").css("margin-top","0px");
		$(".synced-button").css("margin-top","-1.5%");
		$(".nav-li").css("margin-top","");
		$('.navbar .nav').css('margin','0 10px 0 0');
		$('#slips-show').css('width','100%');
	}
	
	
	else if(($(window).width() > 680) && $(window).width() < 1218) 
	{
	
		$(".name-activity-first").css("border-right","1px solid #dfdfdf");
		$(".name-activity-first").css("width","24%");
		$(".name-activity-first").css("float","left");
		
		
		
		$(".name-activity-date").css("border-right","1px solid #dfdfdf");
		$(".name-activity-date").css("width","16%");
		$(".name-activity-date").css("float","left");
		$(".name-activity-date").css("margin","0 28%");
		$(".name-activity-date").css("min-height","100%");
		$(".name-activity-date").css("position","absolute");
		
		
		$(".name-activity-first1").css("border-right","1px solid #dfdfdf");
		$(".name-activity-first1").css("width","24%");
		$(".name-activity-first1").css("float","left");
		
		
		
		$(".name-activity-date1").css("border-right","1px solid #dfdfdf");
		$(".name-activity-date1").css("width","16%");
		$(".name-activity-date1").css("float","left");
		$(".name-activity-date1").css("margin","0 28%");
		$(".name-activity-date1").css("min-height","100%");
		$(".name-activity-date1").css("position","absolute");
		
		
		
		$(".border-margin-2").css("border-left","1px solid");
		$(".border-margin-2").css("width","0px");
		$(".border-margin-2").css("border-color","#dfdfdf");
		$(".border-margin-2").css("float","left");
		$(".border-margin-2").css("margin-left","0px");
		$(".border-margin-2").css("height","40px");
		
		$(".name-activity-right").css("border","none");
		$(".name-activity-right").css("float","left");
		$(".name-activity-right").css("width","23%");
		$(".name-activity-right").css("margin","0 47%");
		$(".name-activity-right").css("min-height","100%");
		$(".name-activity-right").css("border-right","1px solid #dfdfdf");
		$(".name-activity-right").css("position","absolute");
		$(".name-activity-right").css("min-height","100%");
		
		$(".name-activity-right1").css("border","none");
		$(".name-activity-right1").css("float","left");
		$(".name-activity-right1").css("width","23%");
		$(".name-activity-right1").css("margin","0 47%");
		$(".name-activity-right1").css("min-height","100%");
		$(".name-activity-right1").css("border-right","1px solid #dfdfdf");
		$(".name-activity-right1").css("position","absolute");
		
		
		$(".name-activity").css("border","none");
		$(".name-activity").css("width","24%");
		$(".name-activity").css("float","left");
		$(".name-activity").css("margin","0 72%");
		$(".name-activity").css("min-height","100%");
		$(".name-activity").css("position","absolute");
		
		$(".name-activity1").css("border","none");
		$(".name-activity1").css("width","24%");
		$(".name-activity1").css("float","left");
		$(".name-activity1").css("margin","0 72%");
		$(".name-activity1").css("min-height","100%");
		$(".name-activity1").css("position","absolute");
		

		//$(".act-btn").css("border-right","1px solid");
		//$(".act-btn").css("border-color","#red");
	
		//$(".sync-btn-page3").css("border-right","1px solid");
		//$(".sync-btn-page3").css("border-color","#c7c7c7");
		$(".synced-btn").css("margin-top","-8px");
		
		$(".label_units").show();
		$(".label_rate").show();
		//$(".arrow-right").css("float","right");
		//$(".arrow-right").css("margin-top","6px");
		$(".arrow-right-date").hide();
		$(".arrow-right").show();
		$(".sync-btn-page3").css("margin-top","0px");
		$(".bor-rig").show();
		$(".ver-no2").show();
		$(".hidden-area").hide();
		$(".iphoneView").hide();
		$(".normal-view").show();
		$(".sync-btn-page3").css("margin-top","0px");
		$(".synced-button").css("margin-top","-1%");
		$(".nav-li").css("margin-top","");
		$('.navbar .nav').css('margin','0 10px 0 0');
		$('#slips-show').css('width','100%');
	}
	
	else if ($(window).width() <= 680) 
	{
		$(".iphoneView").show();
		$(".normal-view").hide();

		//$(".sync-btn-page3").css("margin-top","2px");
		$(".search-field").css("margin-right","-5px");
		$(".search-field").css("margin-top","0px");
		$(".search-field").css("box-shadow","none");
	//	$(".search-field").css("background","url('images2/search_lens.png') 3% 8px no-repeat");
		$(".search-field").css("background-color","#fafafa");
		//$(".synced-button").css("margin-top","-15%");
		$(".sync-btn-page3").css("margin-left","0%");
		// search field changes
		$("#search-nav").css("float","right");
		$("#search-nav").css("margin-top","8px");
		$("#search-nav").css("padding-left","0%");

		$("#search-nav #userappendedInputButton").css("min-height","25px");
		$("#search-nav #userappendedInputButton").css("width","251px");
		//$(".nav-li").css("margin-top","3px");
		$("#slips-show").css("padding-top","6px");
		$(".activity-text").hide();

		$('.navbar .nav').css('margin','0');
		$('#slips-show').css('width','100%');
	}
	
	$(window).resize(function(){
	 
		if($(window).width() >= 1218) {
		

			$(".synced-btn").css("margin-top","-8px");
			$(".label_units").show();
			$(".label_rate").show();
			$(".arrow-right-date").hide();
			$(".arrow-right").show();
			$(".sync-btn-page3").css("margin-top","0px");
			$(".bor-rig").show();
			$(".name-activity-date1").show();
			$(".name-activity-right1").show();
			$(".name-activity1").show();
			$(".ver-no2").show();
			$(".hidden-area").hide();
			$(".iphoneView").hide();
			$(".normal-view").show();
			$(".sync-btn-page3").css("margin-top","0px");
			$(".synced-button").css("margin-top","-1.5%");
			$("#search-nav #userappendedInputButton").css("width","213px");
			$(".search-field").css("margin-top","3px");
			$("#search-nav").css("float","right");
			$(".nav-li").css("margin-top","");
			$("#search-nav").css("margin-top","");
			$("#search-nav #userappendedInputButton").css("min-height","");
			$("#slips-show").css("padding-top","");
			$(".activity-text").show();

			$('.navbar .nav').css('margin','0 10px 0 0');
			$('#slips-show').css('width','100%');
		}
		
		else if(($(window).width() > 680) && $(window).width() < 1218) 
		{
		
			$(".name-activity-first").css("border-right","1px solid #dfdfdf");
			$(".name-activity-first").css("width","24%");
			$(".name-activity-first").css("float","left");
			
			$(".name-activity-date").css("border-right","1px solid #dfdfdf");
			$(".name-activity-date").css("width","16%");
			$(".name-activity-date").css("float","left");
			$(".name-activity-date").css("margin","0 28%");
			$(".name-activity-date").css("min-height","100%");
			$(".name-activity-date").css("position","absolute");
			
			$(".name-activity-first1").css("border-right","1px solid #dfdfdf");
			$(".name-activity-first1").css("width","24%");
			$(".name-activity-first1").css("float","left");
			
			$(".name-activity-date1").css("border-right","1px solid #dfdfdf");
			$(".name-activity-date1").css("width","16%");
			$(".name-activity-date1").css("float","left");
			$(".name-activity-date1").css("margin","0 28%");
			$(".name-activity-date1").css("min-height","100%");
			$(".name-activity-date1").css("position","absolute");
			
			$(".name-activity-right").css("border","none");
			$(".name-activity-right").css("float","left");
			$(".name-activity-right").css("width","23%");
			$(".name-activity-right").css("margin","0 47%");
			$(".name-activity-right").css("min-height","100%");
			$(".name-activity-right").css("border-right","1px solid #dfdfdf");
			$(".name-activity-right").css("position","absolute");
			$(".name-activity-right").css("min-height","100%");
			
			$(".name-activity-right1").css("border","none");
			$(".name-activity-right1").css("float","left");
			$(".name-activity-right1").css("width","23%");
			$(".name-activity-right1").css("margin","0 47%");
			$(".name-activity-right1").css("min-height","100%");
			$(".name-activity-right1").css("border-right","1px solid #dfdfdf");
			$(".name-activity-right1").css("position","absolute");
			
			$(".name-activity").css("border","none");
			$(".name-activity").css("width","24%");
			$(".name-activity").css("float","left");
			$(".name-activity").css("margin","0 72%");
			$(".name-activity").css("min-height","100%");
			$(".name-activity").css("position","absolute");
			
			$(".name-activity1").css("border","none");
			$(".name-activity1").css("width","24%");
			$(".name-activity1").css("float","left");
			$(".name-activity1").css("margin","0 72%");
			$(".name-activity1").css("min-height","100%");
			$(".name-activity1").css("position","absolute");
			

			$(".synced-btn").css("margin-top","-8px");
			
			$(".label_units").show();
			$(".label_rate").show();
			$(".arrow-right-date").hide();
			$(".arrow-right").show();
			$(".sync-btn-page3").css("margin-top","0px");
			$(".bor-rig").show();
			$(".ver-no2").show();
			$(".hidden-area").hide();
			$(".iphoneView").hide();
			$(".normal-view").show();
			$(".sync-btn-page3").css("margin-top","0px");
			$(".synced-button").css("margin-top","-1%");
			$("#search-nav #userappendedInputButton").css("width","213px");
			$(".search-field").css("margin-top","2px");
			$("#search-nav").css("float","right");
			$(".nav-li").css("margin-top","");
			$("#search-nav").css("margin-top","");
			$("#search-nav #userappendedInputButton").css("min-height","");
			$("#slips-show").css("padding-top","");
			$(".activity-text").show();

			$('.navbar .nav').css('margin','0 10px 0 0');
			$('#slips-show').css('width','100%');
		}
		else if ($(window).width() <= 680) 
		{
			$(".iphoneView").show();
			$(".normal-view").hide();

			$(".search-field").css("margin-right","-5px");
			$(".search-field").css("margin-top","0px");
			$(".search-field").css("box-shadow","none");
		//	$(".search-field").css("background","url('images2/search_lens.png') 3% 8px no-repeat");
			$(".search-field").css("background-color","#fafafa");
			//$(".synced-button").css("margin-top","-15%");
			$(".sync-btn-page3").css("margin-left","0%");
			// search field changes
			$("#search-nav").css("float","right");
			$("#search-nav").css("margin-top","8px");
			$("#search-nav").css("padding-left","0%");

			$("#search-nav #userappendedInputButton").css("min-height","25px");
			$("#search-nav #userappendedInputButton").css("width","251px");
			//$(".nav-li").css("margin-top","3px");
			$("#slips-show").css("padding-top","6px");
			$(".activity-text").hide();

			$('.navbar .nav').css('margin','0');
			$('#slips-show').css('width','100%');
		}
	});

});
</script>
<form method="post" action="/activitysheet/sync" class="activity_sheet_form" id='form_sync_activity'>
 		<?php 
  		$i=0;
  		$total_hourly_units = 0;
  		//$count_slips	=	$total_result;
  		if(!empty($activity_list)) {
	  		foreach($activity_list as $key=>$activity) {
	  	?>
				
			
			
			
			<div id="search-contents-<?php echo $activity['RecordID']; ?>" class="normal-view" >
				<div class="row" id="view-row1">
					<div id="container4">
						<div id="container3">
	    					<div id="container2">
								<div id="container1">
	    							<div id="col-name">
	    								<label id="ver">
	    									<?php
	    									if($activity['sync_status'] == "0") {
											?>
												<a href="javascript:void(0);" id="active-slip-<?php echo $activity['RecordID'];?>" onclick="sync_slip_confirm(this, '<?php echo $activity['RecordID'];?>', 0);"><img src="<?php echo SITEURL;?>/media/images/tt-new/refresh1.png" class="refresh-img"></a>
												<a href="javascript:void(0);" style="display:none;" id="onfly-synced-slip-<?php echo $activity['RecordID'];?>"><img src="<?php echo SITEURL?>/media/images/lock.png" class="refresh-img locked-slip"></a>
												<a href="javascript:void(0);" style="display:none;" id="update-process-<?php echo $activity['RecordID'];?>"><img src="<?php echo SITEURL?>/media/images/tt-new/ajax-loader.gif" class="refresh-img locked-slip"></a>
											<?php } else{ ?>
												<a href="#"><img src="<?php echo SITEURL?>/media/images/lock.png" class="refresh-img locked-slip"></a>
											<?php }	?>
	    									<?php 
	    									if(strlen($activity['CustomerCompanyOrLastName']) > 25) {
	    										echo html_entity_decode(substr($activity['CustomerCompanyOrLastName'], 0, 25))."...";	
	    									} else {
	    										echo html_entity_decode($activity['CustomerCompanyOrLastName']);
	    									}
	    									?>
										</label>
										<?php 
						  				if(!empty($activity['Rate'])) {
											$activity_rate	=	number_format($activity['Rate'],2);	
										}
										else {
											$activity_rate	=	$activity['Rate'];	
										}
										?>
									</div>
	    							<div id="col-date">
	    								<label class="date-lbl"><?php echo date("dS M 'y",strtotime($activity['SlipDate']));?></label>
	    							</div>
	    							<div id="col-hour">
	    								<label class="date-lbl"><?php echo isset($activity['ActivityID'])?html_entity_decode($activity['ActivityID']):'';?></label>
	    							</div>
		 							<div id="col-job">
		 								<label class="date-lbl"> <?php echo !empty($activity['JobNumber'])?html_entity_decode($activity['JobNumber']):'';?></label>
									</div>
									<div id="col-arrow">
										<a href="<?php echo SITEURL; ?>/activitysheet/view/<?php echo $activity['RecordID']; ?>"><img src="<?php echo SITEURL;?>/media/images/tt-new/arrow.png"></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="search-contents-margin"></div>
				<div class="row" id="view-row1"  >
					<div id="container4">
						<div id="container3">
	   						<div id="container2">
								<div id="container1">
	   								<div id="col-name">	
	   									<label id="ver-no"><?php echo (strlen($activity['SlipIDNumber']) > 10)?substr(html_entity_decode($activity['SlipIDNumber']),0,10).'..':substr(html_entity_decode($activity['SlipIDNumber']),0,10); ?></label>
	   								</div>
	   								<div id="col-date">
										<label class="units">
											<span class="label_units">Units</span>
											<span class="hours"><?php echo number_format($activity['Units'],2);?> hrs</span>
										</label>
									</div>
	   								<div id="col-hour">
	   									<label id="rate">
											<span class="label_rate">Rate</span>
											<?php if(isset($_SESSION['display_rate']) && $_SESSION['display_rate'] == 1) {?>
													<span class="hours per"><?php echo $_SESSION['CurrencySymbol']."&nbsp;".$activity_rate;?>
														<?php echo isset($activity['Activities']['UnitOfMeasure'])?' per '.strtolower($activity['Activities']['UnitOfMeasure']):''; ?>
													</span>
											<?php } else { echo ""; }?>	
										</label>
									</div>
	 								<div id="col-job1">
	 									<label id="total">Total
	 										<?php if(isset($_SESSION['display_rate']) && $_SESSION['display_rate'] == 1) {?>
													<span class="hours"><?php echo $_SESSION['CurrencySymbol']."&nbsp;".number_format($activity['Units']*$activity['Rate'],2);?></span>
											<?php } else { echo ""; }?>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<?php if($activity['Is_non_hourly'] == 0) {
						$total_hourly_units	+=	$activity['Units'];
					?>
						<input type="hidden" class="slip-hours" value="<?php echo ($activity['Units'])?>" id="slip_total_hours_<?php echo $i;?>" />
			<?php }?>
			
			
			
			
			<div id="search-contents-phone-<?php echo $activity['RecordID']; ?>" class="iphoneView"  style="display:none">
				<div class="row" id="view-row1">
	    			<div id="container-iphone-top2">
						<div id="container-iphone-top1">
	    					<div id="col-name-phone">
	    						<label id="ver" >
	    							<?php
											if($activity['sync_status'] == "0"  && ($_SESSION['synced_slips_view'] == 0)) {
										?>
											<a href="javascript:void(0);" id="active-slip-phone-<?php echo $activity['RecordID'];?>" onclick="sync_slip_confirm(this, '<?php echo $activity['RecordID'];?>', 0);"><img src="<?php echo SITEURL;?>/media/images/tt-new/refresh1.png" class="refresh-img"></a>
											<a href="javascript:void(0);" style="display:none;" id="onfly-synced-slip-phone-<?php echo $activity['RecordID'];?>"><img src="<?php echo SITEURL?>/media/images/lock.png" class="refresh-img locked-slip"></a>
											<a href="javascript:void(0);" style="display:none;" id="update-process-phone-<?php echo $activity['RecordID'];?>"><img src="<?php echo SITEURL?>/media/images/tt-new/ajax-loader.gif" class="refresh-img locked-slip"></a>
										<?php } else{ ?>
											<a href="#"><img src="<?php echo SITEURL?>/media/images/lock.png" class="refresh-img locked-slip"></a>
										<?php }	?>
									<?php 
	    									if(strlen($activity['CustomerCompanyOrLastName']) > 22) {
	    										echo html_entity_decode(substr($activity['CustomerCompanyOrLastName'], 0, 22))."...";	
	    									} else {
	    										echo html_entity_decode($activity['CustomerCompanyOrLastName']);
	    									}
	    							?>
								</label>
							</div>
	    					<div id="col-date-phone">
	    						<label class="name-act-lbl"><?php echo date("dS M 'y",strtotime($activity['SlipDate']));?> </label>
	    					</div>
							<div id="col-arrow-phone"><a href="<?php echo SITEURL; ?>/activitysheet/view/<?php echo $activity['RecordID']; ?>"><img src="<?php echo SITEURL;?>/media/images/tt-new/arrow.png"></a></div>
						</div>
					</div>
				</div>
				<div id="contents-margin"></div>
				<div class="row" id="view-row1"  >
					<div id="container-iphone-3">
	    				<div id="container-iphone-2">
							<div id="container-iphone-1">
	    						<div id="col1"><label id="ver-no"><?php echo (strlen($activity['SlipIDNumber']) > 10)?substr(html_entity_decode($activity['SlipIDNumber']),0,10).'..':substr(html_entity_decode($activity['SlipIDNumber']),0,10); ?></label></div>
	    						<div id="col2"><label class="name-act-lbl"><?php echo isset($activity['ActivityID'])?html_entity_decode($activity['ActivityID']):'';?></label></div>
	    						<div id="col3"><label class="name-act-lbl"><?php echo !empty($activity['JobNumber'])?html_entity_decode($activity['JobNumber']):'';?></label></div>
							</div>
						</div>
					</div>
				</div>
	
				<div id="contents-margin"></div>
				<div class="row" id="view-row1"  >
					<div id="container-iphone-3">
	    				<div id="container-iphone-2">
							<div id="container-iphone-1">
	    						<div id="col1">
	    							<label class="units">
	    								<span class="hours"><?php echo number_format($activity['Units'],2);?> hrs</span>
									</label>
								</div>
	    						<div id="col2">
									<label id="rate">
										<?php if(isset($_SESSION['display_rate']) && $_SESSION['display_rate'] == 1) {?>
												<span class="hours"><?php echo $_SESSION['CurrencySymbol']."&nbsp;".$activity_rate;?>
													<?php echo isset($activity['Activities']['UnitOfMeasure'])?' per '.strtolower($activity['Activities']['UnitOfMeasure']):''; ?>
												</span>
										<?php } else { echo ""; }?>
									</label>
								</div>
	    						<div id="col3">
	    							<label id="total">Total 
										<?php if(isset($_SESSION['display_rate']) && $_SESSION['display_rate'] == 1) {?>
											<span class="hours"><?php echo $_SESSION['CurrencySymbol']."&nbsp;".number_format($activity['Units']*$activity['Rate'],2);?></span>
										<?php } else { echo ""; }?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		
			<?php 
				$i++;
	  		}
  		} else {
  			?>
  			<div class="no-slips"><div class="row" id="view-row1">No slips available</div></div>
  			<?php 
  		}
		?>
		</form>