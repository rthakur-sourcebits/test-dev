<!-- 
 * @File : list.php
 * @view : List view for Activity sheets
 * @Author: 
 * @Created: 24-11-2012
 * @Modified:  
 * @Description: View file for List activity sheets created by Admin/Employee
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
		$(".synced-button").css("margin-top","-8px");
		$(".nav-li").css("margin-top","");
		//$('.navbar .nav').css('margin','0 10px 0 0');
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
		//$('.navbar .nav').css('margin','0 10px 0 0');
		$('#slips-show').css('width','100%');
	}
	
	else if ($(window).width() <= 680) 
	{
		$(".iphoneView").show();
		$(".normal-view").hide();
		

		//$(".sync-btn-page3").css("margin-top","2px");
		//$(".search-field").css("margin-right","-5px");
	//	$(".search-field").css("margin-top","0px");
	//	$(".search-field").css("box-shadow","none");
		//$(".search-field").css("background","url('images2/search_lens.png') 3% 8px no-repeat");
	//	$(".search-field").css("background-color","#fafafa");
		//$(".synced-button").css("margin-top","-15%");
		$(".sync-btn-page3").css("margin-left","0%");
		// search field changes
		$("#search-nav").css("float","right");
		//$("#search-nav").css("margin-top","8px");
		$("#search-nav").css("padding-left","0%");

	//	$("#search-nav #userappendedInputButton").css("min-height","25px");
	//	$("#search-nav #userappendedInputButton").css("width","296px");
		//$(".nav-li").css("margin-top","3px");
		$("#slips-show").css("padding-top","6px");
		//$(".activity-text").hide();
		
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
			$(".synced-button").css("margin-top","-8px");
	//		$("#search-nav #userappendedInputButton").css("width","213px");
			//$(".search-field").css("margin-top","3px");
			$("#search-nav").css("float","right");
			$(".nav-li").css("margin-top","");
			//$("#search-nav").css("margin-top","");
	//		$("#search-nav #userappendedInputButton").css("min-height","");
			$("#slips-show").css("padding-top","");
			//$(".activity-text").show();
			
			//$('.navbar .nav').css('margin','0 10px 0 0');
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
		//	$("#search-nav #userappendedInputButton").css("width","213px");
		//	$(".search-field").css("margin-top","2px");
			$("#search-nav").css("float","right");
			$(".nav-li").css("margin-top","");
			//$("#search-nav").css("margin-top","");
		//	$("#search-nav #userappendedInputButton").css("min-height","");
			$("#slips-show").css("padding-top","");
			//$(".activity-text").show();
			
			//$('.navbar .nav').css('margin','0 10px 0 0');
			$('#slips-show').css('width','100%');
		}
		else if ($(window).width() <= 680) 
		{
			$(".iphoneView").show();
			$(".normal-view").hide();
			

			//$(".sync-btn-page3").css("margin-top","2px");
			//$(".search-field").css("margin-right","-5px");
		//	$(".search-field").css("margin-top","0px");
			//$(".search-field").css("box-shadow","none");
		//	$(".search-field").css("background","url('images2/search_lens.png') 3% 8px no-repeat");
		//	$(".search-field").css("background-color","#fafafa");
			//$(".synced-button").css("margin-top","-15%");
			$(".sync-btn-page3").css("margin-left","0%");
			// search field changes
			$("#search-nav").css("float","right");
			//$("#search-nav").css("margin-top","8px");
			$("#search-nav").css("padding-left","0%");

			//$("#search-nav #userappendedInputButton").css("min-height","25px");
			//$("#search-nav #userappendedInputButton").css("width","296px");
			//$(".nav-li").css("margin-top","3px");
			$("#slips-show").css("padding-top","6px");
			//$(".activity-text").hide();
			$('.navbar .nav').css('margin','0');
			$('#slips-show').css('width','100%');
		}
	});
	/****For Retins Display*****/
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.refresh-img');   
	for(var i=0; i < images.length; i++)
	{
	images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/refresh1.png', '/media/images/tt-new/sync-btn-retina.png'))
	$('.refresh-img').css('width','23px')
	$('.refresh-img').css('height','23px')
	}
	}
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.image-retina');   
	for(var i=0; i < images.length; i++)
	{
	images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/arrow.png', '/media/images/tt-new/retina_arrow-right.png'))
	$('.image-retina').css('width','8px')
	$('.image-retina').css('height','13px')
	}
	}

	$('.search-names-popup').keyup(function() 
	{
		var txtVal 	= this.value; // for lower case
		var txtVal1 = txtVal.toUpperCase(); // for upper case
		var txtVal2 = txtVal.charAt(0).toUpperCase() + txtVal.substr(1).toLowerCase();  //for first character in upper case
		<?php if(isset($employee_list) && count($employee_list)>0){?>
							var data 	= 	<?php echo json_encode($employee_list);	}?>;
		var result="";
		var result1="";
		<?php //if(isset($employee_list)) {?>
			for(var i=0;i<data.length;i++)
			{
				result += data[i].name+'|';
				result1 += data[i].record_id+'|';
			}
			var temp = new Array();
			temp = result.split("|");
			var temp1 = new Array();
			temp1 = result1.split("|");
			$('.names-list-row').hide();
			for (var j=0;j<temp.length-1;j++) 
			{
				if ((temp[j].match(txtVal)) || (temp[j].match(txtVal1)) || (temp[j].match(txtVal2)))
				 {	
					$('#names-list-row-'+temp1[j]).show();
				 }
				else if(txtVal=='')
				{
					$('.names-list-row').show();
				}
			}
			});
		<?php //}?>
});

</script>

<div class="navi-bar">
	<div id="login-image" class="login-image">
 		<div id="my-account" class="my-account">
   		</div>
   	</div>
</div>
<div class="navbar">
	<div class="navbar-inner" id="inn" >
		<ul class="nav mob-nav" id="navbar-btns">
			<li class="nav-li mob-nav-slips">
				<a href="<?php echo SITEURL?>/activitysheet" id="" class="nav-link-selected"><div>Activity Slips</div></a>
			</li>
			<li class="nav-li mob-nav-sheets">
				<a href="<?php echo SITEURL?>/timesheet" id="" class="nav-link" ><div>Timesheets</div></a>
			</li>
			<li class="right search-bar">
    			<form class="jqtransform" id="activity_slip_search" name="activity_slip_search" action="<?php echo SITEURL?>/activitysheet" method="post">
        			<?php 
        		   	$seach_value	=	isset($_POST['act_search'])?$_POST['act_search']:"";
        		   	?>
        			<input class="span3 search-field" id="userappendedInputButton" placeholder="search" size="16" type="text" name="act_search" style="height:16px !important;" value="<?php echo $seach_value;?>">
        			<a href="javascript:void(0);" onclick="clear_search_results('<?php echo SITEURL?>','4')" class="clear_search">X</a>
        		</form>
        	</li>
		</ul>
	</div>
</div>
<div class="row-fluid" id="account-section-container">
	<div class="span1 minHeight"></div>
	<div class="span10" id="content-middle">
		<form method="post" action="" id="form-pagination">
			<div id="activity-buttons">
			<?php 
	 			$page_count	=	$count_slips/$per_slip;
				if($count_slips%$per_slip != 0) $total_pages	=	$page_count+1;
				else $total_pages	=	$page_count;
				$total_pages	=	intval($total_pages);
				//die("here".$total_pages);
	 			?>
			
	 			<div id="prev-next">
	 			<?php 
	 			$page_count	=	$count_slips/$per_slip;
				if($count_slips%$per_slip != 0) $total_pages	=	$page_count+1;
				else $total_pages	=	$page_count;
				$total_pages	=	intval($total_pages);
				//die("here".$total_pages);
	 			?>
	 				<a href="javascript:void(0);" id="prev-inact" class="form-page"></a>
	 				<a href="javascript:void(0);" id="prev" style="display:none;" class="form-page"></a>
	 				<!-- <img src="<?php echo SITEURL;?>/media/images/tt-new/divider.png" class="divider-img" >   -->
					<a href="javascript:void(0);" id="next" style="<?php if(($count_slips > 10)) { echo "display:block;"; } else { echo "display:none;";}?> class="form-page"></a> 
					<a href="javascript:void(0);" id="next-inact" style="<?php if(($count_slips <= 10)) { echo "display:block;"; } else { echo "display:none;";}?> class="form-page"></a>
	 			</div>
	 			
	 			
	  			<button class="btn act-btn left" id="act-btn" type="button" onclick="location.href='<?php echo SITEURL;?>/activity/add'"><span class="activity-text">Activity</span> Slip</button>
	 			<?php if($_SESSION['synced_slips_view'] == 0) { ?>
	 			<button class="btn sync-btn-page3 left" id="sync-btn-page3" type="button" onclick="alert_syncall(this);"><span class="activity-text">Sync All</span></button>
	 			<?php }?>
	 			<?php if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){?>
	 			<div class="search-contents filter_employees grey_background" >
					<div class="row search-contents-row">
        				<div id="" class="activity-form-validator">
        					<div class="span3 emp_label" id="" >Employee</div> 
        					<input type="text" readonly class="emp_field grey_background" name="" value="<?php if(isset($_SESSION['selected_emp_id'])){echo $_SESSION['selected_employee_name'].' '.$_SESSION['selected_employee_lastname'];}?>"> 
							<div class="cancel_selection" >
        					<a href="javascript:void(0);" class="cancel_employee" onclick='clear_user(0)' name="" id="">
        					X
        					</a>
        					</div>
        					<input type="hidden" name="" class="employee_name" value="" />
        					<input type="hidden" name="" class="employee_record" value="" />
        				</div>
        				<div class="validation-error error-background last-row relative-pos" id="">
        					<div class="error-desc">
        						<img src="/media/images/tt-new/reset_error.png" />Please select a filter option.
        					</div>
        				</div>
					</div>
				</div>
				<?php }?>
				<div class="clear"></div>
			<!-- Employee list popup -->
        		<div class="employee-popup" style="display:none !important;">
        			<div class="layout-names">
        				<label class="layout-label-names dull">Employees</label>
        				<input type="text" id="employee_names_popup" class="search-names-popup" placeholder="search"/>
        			</div>
        			<div class="popup-items2">
        				<?php if(isset($employee_list)){
        						foreach($employee_list as $e) {
        				?>
        					<div class="names-popup-list first names-list-row" onclick="selected_employee('<?php echo $e['name']?>','<?php echo $e['record_id']?>');" id="names-list-row-<?php echo $e['record_id']?>" >
        						<label class="names-popup-list-label heavy"><?php echo $e['name']?></label>
        					</div>
        				<?php }} ?>
        			</div>
        		</div>
		<!-- Employee list popup -->
			<div class="clear"></div>	
				
	  		</div>
	  		<input type="hidden" id="page-number" value="1" />
			<input type="hidden" id="view-per-page" value="10" />
			<input type="hidden" id="total-slips" value="<?php echo $count_slips;?>" />
  		</form>
  		<div id="slips_list">
  		<form method="post" action="/activitysheet/sync" class="activity_sheet_form" id='form_sync_activity'>
  		<?php 
  		$i=0;
  		$total_hourly_units = 0;
  		//$count_slips	=	$total_result;
  		if(!empty($activity_list)) {
	  		foreach($activity_list as $key=>$activity) {
	  	?>
		
		<div id="search-contents-<?php echo $activity['RecordID'];?>" class="normal-view" >
				<div class="row" id="view-row1">
					<div id="container4">
						<div id="container3">
	    					<div id="container2">
								<div id="container1">
	    							<div id="col-name">
	    								<label id="ver" class="ver" title="<?php echo html_entity_decode($activity['CustomerCompanyOrLastName']);?>">
	    									<?php
	    									if(($activity['sync_status'] == "0") && ($_SESSION['synced_slips_view'] == 0)) {
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
	    								<label class="date-lbl"><?php echo date("F d, Y",strtotime($activity['SlipDate']));?></label>
	    							</div>
	    							<div id="col-hour">
	    								<label class="date-lbl"><?php echo isset($activity['ActivityID'])?html_entity_decode($activity['ActivityID']):'';?></label>
	    							</div>
		 							<div id="col-job">
		 								<label class="date-lbl"> <?php echo !empty($activity['JobNumber'])?html_entity_decode($activity['JobNumber']):'';?></label>
									</div>
									<div id="col-arrow">
										<a href="<?php echo SITEURL; ?>/activitysheet/view/<?php echo $activity['RecordID']; ?>"><img class="image-retina" src="<?php echo SITEURL;?>/media/images/tt-new/arrow.png"></a>
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
	    						<label class="ver" id="ver" >
    							    <?php
										if(($activity['sync_status'] == "0") && ($_SESSION['synced_slips_view'] == 0)) {
									?>
										<a href="javascript:void(0);" id="active-slip-phone-<?php echo $activity['RecordID'];?>" onclick="sync_slip_confirm(this, '<?php echo $activity['RecordID'];?>', 0);"><img id="activeslip-phone-<?php echo $activity['RecordID'];?>" src="<?php echo SITEURL;?>/media/images/tt-new/refresh1.png" class="refresh-img"></a>
										<a href="javascript:void(0);" style="display:none;" id="onfly-synced-slip-phone-<?php echo $activity['RecordID'];?>"><img id="onflysynced-slip-phone-<?php echo $activity['RecordID'];?>" src="<?php echo SITEURL?>/media/images/lock.png" class="refresh-img locked-slip"></a>
										<a href="javascript:void(0);" style="display:none;" id="update-process-phone-<?php echo $activity['RecordID'];?>"><img id="updateprocess-phone-<?php echo $activity['RecordID'];?>" src="<?php echo SITEURL?>/media/images/tt-new/ajax-loader.gif" class="refresh-img locked-slip"></a>
									<?php } else{ ?>
										<a id="active-slip-phone-<?php echo $activity['RecordID'];?>" href="#"><img id="activeslip-phone-<?php echo $activity['RecordID'];?>" src="<?php echo SITEURL?>/media/images/lock.png" class="refresh-img locked-slip"></a>
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
	    						<label class="name-act-lbl"><?php echo date("F d, Y",strtotime($activity['SlipDate']));?> </label>
	    					</div>
							<div id="col-arrow-phone"><a href="<?php echo SITEURL; ?>/activitysheet/view/<?php echo $activity['RecordID']; ?>"><img src="<?php echo SITEURL;?>/media/images/tt-new/arrow.png"></a></div>
						</div>
					</div>
				</div>
				<div id="contents-margin"></div>
				<div class="row" id="view-row1">
	    			<div id="container-iphone-top2">
						<div id="container-iphone-top1">
	    					<div id="col-name-phone">
	    						<label id="ver-no"><?php echo (strlen($activity['SlipIDNumber']) > 10)?substr(html_entity_decode($activity['SlipIDNumber']),0,10).'..':substr(html_entity_decode($activity['SlipIDNumber']),0,10); ?></label>
							</div>
	    					<div id="col-add-phone">
	    						<label class="name-act-lbl"><?php echo isset($activity['ActivityID'])?html_entity_decode($activity['ActivityID']):'';?></label>
	    					</div>
							
						</div>
					</div>
				</div>
				<div id="contents-margin"></div>
				<div class="row" id="view-row1">
	    			<div id="container-iphone-top2">
						<div id="container-iphone-top1">
	    					<div id="col-name-phone">
	    						<label class="name-act-lbl"><?php echo !empty($activity['JobNumber'])?html_entity_decode($activity['JobNumber']):'';?></label>
							</div>
	    					<div id="col-add-phone">
	    						<label class="units_phone" id="ver">Units
	    								<span class="hours"><?php echo number_format($activity['Units'],2);?> hrs</span>
									</label>
	    					</div>
							
						</div>
					</div>
				</div>
				<div id="contents-margin"></div>
				<div class="row" id="view-row1">
	    			<div id="container-iphone-top2">
						<div id="container-iphone-top1">
	    					<div id="col-name-phone">
	    						<label class="name-act-lbl" id="rate">
										<?php if(isset($_SESSION['display_rate']) && $_SESSION['display_rate'] == 1) {?>
												<span class="hours"><?php echo $_SESSION['CurrencySymbol']."&nbsp;".$activity_rate;?>
													<?php echo isset($activity['Activities']['UnitOfMeasure'])?' per '.strtolower($activity['Activities']['UnitOfMeasure']):''; ?>
												</span>
										<?php } else { echo ""; }?>
									</label>
							</div>
	    					<div id="col-add-phone">
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
				<!--
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
				
				 -->
	
				<!--
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
		-->
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
		</div>
		<?php
								
		//if( $count_slips > 10)
		//{
			
		//}
		?>
		<div id="slips"style="padding-top:30px;">
		<?php if(!empty($activity_list)) { ?>
			<form method="post" action="" id="form_pager">
			<div class="span3" id="slips-view">
				<div class="btn-group" style="width: 100px;">
  					<a class="dropdown-toggle" onclick="openViewPopup();" data-toggle="dropdown" href="javascript:void(0);">
  						<label class="slips_label_list ajax_page">View <span style="color:#515151;font-family:HelveticaNeueBold !important;"><span class="slips-selected">10</span> Slips</span><span class="caret"></span>
  						<img class="ajax_loader_show" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
  						</label>
  					</a>
  					
	  					<ul class="dropdown-menu" id="dropdown-menu-bottom">
	  						<?php
				  			
								for($j=1;$j<=$total_pages;$j++) {
							?>
							<li class="slips_result_per_page" id="slips_<?php echo $j*10;?>"><a href="javascript:void(0);" class="slips-number"><?php echo $j*10; ?> Slips</a></li>
	    					<?php
								}
							?>
							<li class="slips_result_per_page all_views" id="slips_<?php echo $j*10;?>"><a href="javascript:void(0);" class="slips-number">All Slips</a></li>
					</ul>
					
				</div>
			</div>
			</form>
		<?php } ?>
			<?php 
				if($_SESSION['synced_slips_view'] == 0)
				{
					$synced_text = "Show Synced";
					$sync_url_id  = 1;
				}
				else {
					$synced_text = "Hide Synced";
					$sync_url_id  = 0;
				}
				//$synced_class	=	$_SESSION['synced_slips_view'] == 0 ? "show-synced":"hide-synced";
			?>
			<a href="<?php echo SITEURL;?>/activity/sync/<?php echo $sync_url_id?>">
			<div class="synced-button"><label id="label-synced" class="label-synced"><?php echo $synced_text;?></label></div>	
			</a>
			<div class="clear"></div>
			<?php if(!empty($activity_list)) {?>
					<div class="span6" id="slips-show">
						<label class="slips_label" id="slips_label_pages" style="color:#A9A9A9;">Showing <span class="pagination-info">1-<?php echo ($count_slips>10)?10:$count_slips;?></span> of <?php echo $count_slips;?></label>
					</div>
			<?php }?>
		</div>	  
		
	  	<?php echo View::factory("company/footer_trial_message");?>
	</div>
</div>
<div class="span1" ></div>
