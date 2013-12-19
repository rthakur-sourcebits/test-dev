<!-- 
 * @File : view.php
 * @view : view for Activity slips
 * @Author: 
 * @Created: 20-11-2012
 * @Modified:  
 * @Description: View file for Activity slips.
   Copyright (c) 2012 Acclivity Group LLC 
-->
<div class="navi-bar">
	<div class="login-image" id="login-image">
	<!--  <img src="images/abc1.jpg" width="133" height="20" alt="abc2"> -->
		<div class="my-account" id="my-account">
		</div>
	</div>
</div>
<div style="margin-top:25px; " id="fix-ie" class="navbar">
	<div id="inn" class="navbar-inner" style="clear:none; !important">
		
		<ul class="nav mob-nav" id="navbar-btns">
			<li class="activity-slip">
			<a class="nav-link-admin1" id="activity-slip-link" href="<?php echo SITEURL;?>/activitysheet" style="padding-left: 32px; background: url(<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png) no-repeat scroll 50% 60% transparent;"><div class="act-slip-mar" style="margin-left: 0px;">Activity Slips</div></a>
			<a class="phone-nav-link-admin1"  id="activity-slip-link" href="<?php echo SITEURL;?>/activitysheet"   style="padding-left: 10px; background: url(<?php echo SITEURL; ?>/media/images/tt-new/slips-image.png) no-repeat scroll 46% 48% transparent;"><div class="act-slip-mar" >Slips</div></a>
			
			</li>

			<li class="up-down-navigation">
			<div class="up-down-bar" id="activity-view">
			<?php
				if ($prev != 0) {
			?>
			<a id="up-arr" href="<?php echo SITEURL.'/activitysheet/view/'.$prev;?>"></a>
			<?php } else { ?>
			<a id="up-arr" class="disab" href="javascript:void(0)"></a>
			<?php } if($next != 0) { ?>
			<a id="down-arr" href="<?php echo SITEURL.'/activitysheet/view/'.$next;?>"></a>
			<?php } else { ?>
			<a id="down-arr" class="disab" href="javascript:void(0)"></a>
			<?php } ?>
			</div>
			</li>	

			<li class="nav-list-item3">
				<div class="activity-slips-view">Activity Slip</div>
			</li>
			<li class="right search-bar">
			<input type="text" size="16" placeholder="search" id="userappendedInputButton" style="height:16px !important;" class="span3 search-field">
			<a href="javascript:void(0);" onclick="clear_search_results('<?php echo SITEURL?>','2')" class="clear_search">X</a>
			</li>
		</ul>
    </div> 
</div>
<div id="account-section-container" class="row-fluid">
	<div id="sidebar" class="span3">
	</div>
	<script type="text/javascript">			 
		$(document).ready(function(){
		if($(window).width() <= 400) {
		$(".hid").css({"display":"none"});
	//	$(".nav-link-settings").css("padding-left","0px");
		$("#activity-slip-link").css("padding-left","0px");
		$("#up-arr-down-arr").show();
		$(".act-slip-mar").css("margin-left","22px");
		$(".nav-link-admin1").css("background","url('<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png') 0% 60% no-repeat");
		$(".add-Activity-cust").css("margin-top","0px");
		$(".up-down-navigation").hide();
		
		$("#search-nav").css({"paddingLeft":"0"});

		$(".nav-link-admin1").hide();
		$(".phone-nav-link-admin1").show();

		//$("#userappendedInputButton").css("width","96px");
		//$(".nav").css("width","71%");
	
		$('.navbar .nav').css('margin','0');
		$('.payroll').text('Payroll');
		}
		else if(($(window).width() > 400)  && ($(window).width() <= 700)) 
		{
		$(".up-down-navigation").hide();
	//	$(".nav-link-settings").css("padding-left","0px");
		$("#activity-slip-link").css("padding-left","0px");
		$(".add-Activity-cust").css("margin-top","0px");
		//$(".act-slip-mar").css("margin-left","15px");
		$(".nav-link-admin1").css("background","url('<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png') 0% 60% no-repeat");
		$("#up-arr-down-arr").show();
		$('.payroll').text('Payroll');
		//$(".nav").css("width","103%");
		
		$(".nav-link-admin1").hide();
		$(".phone-nav-link-admin1").show();

		//$("#userappendedInputButton").css("width","100px");
	//	$(".nav").css("width","70%");
		
		$('.navbar .nav').css('margin','0 10px 0 0');
		}
		

		
	
		else if (($(window).width() > 700) && $(window).width() <= 1000) {
		//	$(".nav").css("width","59%");
	//		$(".nav-link-settings").css("padding-left","18px");

			$(".nav-link-admin1").show();
			$(".phone-nav-link-admin1").hide();

			$('.payroll').text('Payroll');
			$("#up-arr-down-arr").hide();
			$(".up-down-navigation").show();

		//	$("#userappendedInputButton").css("width","100px");
		//	$(".nav").css("width","75%");
		
			$('.navbar .nav').css('margin','0 10px 0 0');
			$(".nav-link-admin1").css("background","url('<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png') 50% 60% no-repeat");
			$("#activity-slip-link").css("padding-left","18px");
		

			
		}
		else if (($(window).width() > 1000) && ($(window).width() <= 1050)) 
		{

	//	$(".nav-link-settings").css("padding-left","115px");
		$("#activity-slip-link").css("padding-left","32px");
		$(".add-Activity-cust").css("margin-top","0px");
		$("#up-arr-down-arr").hide();
		$(".act-slip-mar").css("margin-left","0px");
		$(".nav-link-admin1").css("background","url('<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png') 50% 60% no-repeat");
		$(".up-down-navigation").show();
		$('.payroll').text('Payroll Category');
		$('.navbar .nav').css('margin','0 10px 0 0');
		$(".nav-link-admin1").show();
		$(".phone-nav-link-admin1").hide();

	//	$("#userappendedInputButton").css("width","185px");
	//	$(".nav").css("width","70%");
		

		
		}
		else 
		{

	//	$(".nav-link-settings").css("padding-left","200px");
		$("#activity-slip-link").css("padding-left","32px");
		$("#up-arr-down-arr").hide();

		$(".act-slip-mar").css("margin-left","0px");
		$(".nav-link-admin1").css("background","url('<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png') 50% 60% no-repeat");
		$(".up-down-navigation").show();
		$(".add-Activity-cust").css("margin-top","0px");
	//	$(".nav").css("width","70%");
		$('.payroll').text('Payroll Category');
		$('.navbar .nav').css('margin','0 10px 0 0');
		$(".nav-link-admin1").show();
		$(".phone-nav-link-admin1").hide();

		

		
	

		
		}

		$(window).resize(function()
		{
			if($(window).width() <= 400) {
				$(".hid").css({"display":"none"});
			//	$(".nav-link-settings").css("padding-left","0px");
				$("#activity-slip-link").css("padding-left","0px");
				$("#up-arr-down-arr").show();
				$(".act-slip-mar").css("margin-left","22px");
				$(".nav-link-admin1").css("background","url('<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png') 0% 60% no-repeat");
				$(".add-Activity-cust").css("margin-top","0px");
			
				//$(".nav").css("width","103%");
				$(".up-down-navigation").hide();
				$("#search-nav").css({"paddingLeft":"0"});
				$('.payroll').text('Payroll');
				$(".nav-link-admin1").hide();
				$(".phone-nav-link-admin1").show();

				//$("#userappendedInputButton").css("width","96px");
				//$(".nav").css("width","71%");
			
				$('.navbar .nav').css('margin','0');
				
				}
				else if(($(window).width() > 400)  && ($(window).width() <= 700)) 
				{
				$(".up-down-navigation").hide();
			//	$(".nav-link-settings").css("padding-left","0px");
				$("#activity-slip-link").css("padding-left","0px");
				$(".add-Activity-cust").css("margin-top","0px");
				$(".nav-link-admin1").css("background","url('<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png') 0% 60% no-repeat");
				$("#up-arr-down-arr").show();
				$('.payroll').text('Payroll');
				$(".nav-link-admin1").hide();
				$(".phone-nav-link-admin1").show();

				//$("#userappendedInputButton").css("width","100px");
			
				$('.navbar .nav').css('margin','0 10px 0 0');
				}
				

				
			
				else if (($(window).width() > 700) && $(window).width() <= 1000) {
				//	$(".nav").css("width","59%");
				//	$(".nav-link-settings").css("padding-left","18px");

					$(".nav-link-admin1").show();
					$(".phone-nav-link-admin1").hide();
					$("#up-arr-down-arr").hide();
					$(".up-down-navigation").show();

				//	$("#userappendedInputButton").css("width","100px");
				//	$(".nav").css("width","75%");
					$('.payroll').text('Payroll');
					$('.navbar .nav').css('margin','0 10px 0 0');
					$(".nav-link-admin1").css("background","url('<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png') 50% 60% no-repeat");
					$("#activity-slip-link").css("padding-left","18px");


					
				}
				else if (($(window).width() > 1000) && ($(window).width() <= 1050)) 
				{

			//	$(".nav-link-settings").css("padding-left","115px");
				$("#activity-slip-link").css("padding-left","32px");
				$(".add-Activity-cust").css("margin-top","0px");
				$("#up-arr-down-arr").hide();
				$(".act-slip-mar").css("margin-left","0px");
				$(".nav-link-admin1").css("background","url('<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png') 50% 60% no-repeat");
				$(".up-down-navigation").show();
				$('.payroll').text('Payroll Category');
				$('.navbar .nav').css('margin','0 10px 0 0');
				$(".nav-link-admin1").show();
				$(".phone-nav-link-admin1").hide();

			//	$("#userappendedInputButton").css("width","185px");
			//	$(".nav").css("width","70%");
				

				
				}
				else 
				{

				
				$("#activity-slip-link").css("padding-left","32px");
				$("#up-arr-down-arr").hide();

				$(".act-slip-mar").css("margin-left","0px");
				$(".nav-link-admin1").css("background","url('<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png') 50% 60% no-repeat");
				$(".up-down-navigation").show();
				$(".add-Activity-cust").css("margin-top","0px");
				$('.payroll').text('Payroll Category');
				$('.navbar .nav').css('margin','0 10px 0 0');
				$(".nav-link-admin1").show();
				$(".phone-nav-link-admin1").hide();

				}
		});
		});
	</script>		
	<!-- rateDisplay-on - showJobs-on script end-->
	<div id="user-right-content" class="span6">
	<!-- Center contents starts-->
		<div class="clear"></div>
		<div class="add-Activity-cust">
			<div id="up-arr-down-arr">
			
			<?php
				if ($prev != 0) {
			?>
				<a id="up-arr" href="<?php echo SITEURL.'/activitysheet/view/'.$prev;?>"></a>
			<?php } else { ?>
				<a id="up-arr" class="disab" href="javascript:void(0)"></a>
			
				<?php } if($next != 0) { ?>
				<a id="down-arr" href="<?php echo SITEURL.'/activitysheet/view/'.$next;?>">
				<!--  <img src="<?php echo SITEURL; ?>/media/images/tt-new/right-arrow.png" style="margin-top:11px; padding-right:12px;">  --> 
				</a> 				
				<?php } else { ?>
				<a id="down-arr" class="disab" href="javascript:void(0)"></a>
				<?php } ?>
			</div>
			<a href="<?php echo SITEURL;?>/activity/add"><button type="button" id="sync-btn-cust-add1" class="btn sync-btn-cust-add1 activity_slip_span" style="margin-top:22px;">Activity Slip</button></a>
		</div>
				<div class="clear"></div>
		<div class="Cust-name" id="Cust-name"><label class="customer">Customer</label></div> 
		
		<div class="cust-name-fields" >
			<div class="row Customer-name-block" id="Customer-name-block" >
			<div class="span3 Customer-name-field" id="Customer-name-field" ><label class="label-name-cust"><?php echo html_entity_decode($details['CustomerCompanyOrLastName']);?></label></div>

			<div id="customer-span" ><Span id="Date-field">
			<?php
				$slipDate = date("F d, Y", strtotime($details['SlipDate']));
				/*$day = explode('.',$slipDate);
				switch($day[0]%10) {
					case 1: 
					if ($day[0] == 11)
						$day[0] = $day[0]."th";
					else
						$day[0] = $day[0]."st";
					break;
					case 2:
						if ($day[0] == 12)
						$day[0] = $day[0]."th";
					else
						$day[0] = $day[0]."nd";
					break;
					case 3:
					if ($day[0] == 13)
						$day[0] = $day[0]."th";
					else
						$day[0] = $day[0]."rd";
					break;
					default: $day[0] = $day[0]."th"; break;
				}*/
				//echo $day[0]." ".$day[1];
				echo $slipDate;
			?>
			</span></div>
			</div>
		</div>

		
		<div class="search-contents">
			<div id="row search-contents-row" class="row search-contents-row">
				<div id="users-activity-field" class="span3 users-activity-field"><label>Employee</label></div> 
	  
				<div id="label-span"><span id="customer-textfield1"><?php echo $details['EmployeeFirstName'].' '.$details['EmployeeCompanyOrLastName']; ?></span></div>
			</div>
		</div>
		
		<div class="search-contents">
			<div id="row search-contents-row" class="row search-contents-row">
				<div id="users-activity-field" class="span3 users-activity-field"><label>Slip</label></div> 
	  
				<div id="label-span"><span id="customer-textfield1"><?php echo $details['SlipIDNumber']; ?></span></div>
			</div>
		</div>

		<div class="search-contents">
			<div id="row search-contents-row" class="row search-contents-row">
				<div id="users-activity-field" class="span3 users-activity-field">Activity</div> 
				<!-- <div class="span9 act-units" id="act-units">Hourly Activity  -->
				<!-- <input type="text"  id="customer-textfield" > -->
				<div id="label-span"><span id="customer-textfield1"><?php echo isset($details['ActivityID'])?html_entity_decode($details['ActivityID']):''; ?></span></div>
				<!-- </div> -->
			</div>
			<div id="search-contents-margin">
			</div>
			<div id="search-contents-row" class="row search-contents-row no-mar-left">
				<div id="hourly-activity1" class="span3 hourly-activity1">Units</div> 
				<!-- <input type="text"  id="customer-textfield1" > -->
				<div id="label-span">  <span id="customer-textfield1"><?php echo $details['Units']; ?></span></div>
			</div>
		</div>
	  
		<?php 
			if(isset($_SESSION['display_rate']) && $_SESSION['display_rate'] == 1) {
		?>
		<div class="search-contents">
			<div id="row search-contents-row" class="row search-contents-row">
				<div id="users-activity-field" class="span3 users-activity-field">Rate</div> 
				<div id="label-span"> <span id="customer-textfield1">
				<?php 
					if(!empty($details['Rate'])) {
						$activity_rate	=	number_format(doubleval($details['Rate']),2);	
					}
					else {
						$activity_rate	=	$details['Rate'];	
					}
					echo $_SESSION['CurrencySymbol']."&nbsp;".$activity_rate;
					echo isset($details['Activities']['UnitOfMeasure'])?' per '.$details['Activities']['UnitOfMeasure']:'';
				?>
				</span></div> 
			</div>
			<div id="search-contents-margin">
			</div>
			<div id="row search-contents-row" class="row search-contents-row">
				<div id="hourly-activity" class="span3 hourly-activity total-view">Total</div> 
				<div id="label-span"><span id="customer-textfield1">
				<?php  
					if(!isset($details['Activities']['UnitOfMeasure']) || $details['Activities']['UnitOfMeasure'] == 'Hour')
					{
					}
					else
					{
						echo ucwords($details['Activities']['UnitOfMeasure']);
					}
				?>
				<?php 
					echo $_SESSION['CurrencySymbol']."&nbsp;".number_format(doubleval($details['Units']*$details['Rate']),2);
				?>
				</span></div>
			</div>
		</div>
		<?php } ?>
	  
		<div class="search-contents">
			<div id="row search-contents-row" class="row search-contents-row">
				<div id="users-activity-field" class="span3 users-activity-field">Job</div> 
				<div id="label-span"><span id="customer-textfield1"><?php echo isset($details['JobNumber'])?html_entity_decode($details['JobNumber']):''; ?></span></div>
			</div>
			
		</div>

		<div class="search-contents">
			<div id="row search-contents-row-notes" class="row search-contents-row-notes no-mar-left">
				<div id="users-activity-field-notes" class="span3 users-activity-field-notes"><label class="notes-label">Notes</label></div> 
				<div id="label-span"><span id="customer-textfield1"><?php echo $details['Notes']; ?></span></div>
			</div>
		</div>
	   <?php 				
		if(empty($sync_flag))
		{	?>
			<div class="cancel-save-buttons">
				<form method="post" action="/activitysheet/sync/1"  id='sync_slip_form'>
					<input type='hidden' value='<?php echo $slip_id;?>' name="slip_id" />
					<input type='hidden' value='1' name="sync" />
					<button href="javascript:void(0);" onclick="sync_slip_confirm(this, '<?php echo $slip_id;?>', 1);" type="button" id="sync-btn-cust-add" class="btn sync-btn-cust-add sync_span">Sync</button>
				</form>  
					<button type="button" id="edit-btn" class="btn btn-small edit-btn right edit_span" style="margin-right: 0%; margin-top: 0%;" onclick='location.href="<?php echo SITEURL; ?>/activity/add/edit/<?php echo isset($slip_id)?$slip_id:0; ?>"'>Edit</button>
				<button type="button" id="cancel-btn" class="btn btn-small right cancel_span" onclick="delete_slip_warn(this, '<?php echo $slip_id;?>');">Delete</button>
			
			</div>
			<div class="clear"></div>
		<?php } ?>
		<!-- Center contents ends -->
	  
	 	 <?php echo View::factory("company/footer_trial_message");?>
	</div>
	<div id="sidebar" class="span3">
	</div>
</div>

<div class="popup_alert" id='delete_slip' >
	<div class="alert-pop-up">
		<p class="question">Do you really want to delete this slip?</p>
		<p class="message">This operation cannot be undone.</p>
	</div>
	<a href="javascript:void(0);" onclick="close_popup('delete_slip')" class="radius-5 button-1 left">Cancel</a>
	<a href="<?php echo SITEURL?>/activity/delete/<?php echo $slip_id;?>" class="radius-5 alert-save right">Delete</a>	
</div>

<style type="text/css">
		.navbar-inner:after{clear:none;}
		</style>