<!-- 
 * @File : create.php
 * @view : Activity Create
 * @Author: 
 * @Created: 27-11-2012
 * @Modified:  
 * @Description: View file for activity create page
   Copyright (c) 2012 Acclivity Group LLC 
-->
<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.ui-datepicker-trigger').click(function() { 
			$('#ui-datepicker-div').css({'z-index':'200'});
		});

		$('#ui-datepicker-div').wrap('<div class="cal-popup"></div>');

		$('.ui-datepicker-trigger').click(function() {
			$('.cal-popup').toggle();			
		});
		
		$('.cal-popup').css("right","23%");

		$('#wrapper').click(function(){
			$('.cal-popup').hide();
		});
	    
	    $('.overlay, .popup a').click(function(){		  
	        $('.popup, .overlay').hide();
	    });
		
		$('.popup-list li').click(function(){
	        $('.popup-list li').removeClass('selected');
			$(this).addClass('selected');
	    });

		/* to add-remove the selected class for header navigation */
		$('ul.navigation li').click(function () {
			$('ul.navigation li').removeClass('selected');
			$(this).addClass('selected');	
		});		
	});

	function clear_popup_window()
	{
		$('.cal-popup').hide();
	}

</script>

<?php if(empty($slip['RecordID']) && empty($slip_last_date)) {?>
<?php } ?>
<script type="text/javascript" >
$(document).ready(function() {

$('.ui-datepicker-trigger').click(function() { 
	$('#ui-datepicker-div').css({'z-index':'200'});
});

$('#ui-datepicker-div').wrap('<div class="cal-popup"></div>');				

$('.ui-datepicker-trigger').click(function() {			
	$('.cal-popup').toggle();			
});

$('.cal-popup').css("right","23%");

$('#wrapper').click(function(){
	$('.cal-popup').hide();
});		


    $('#notes').blur(function()	{
		$('#cancel-btn').focus();
    });
    $('#cancel-btn').blur(function(){
		$('#save-btn').focus();
    });
});

</script>

<?php 
	$imp_cust_url	=	isset($slip['RecordID'])?"/activity/importcustomer/1/".$slip['RecordID']:"/activity/importcustomer/1";
?>

<div class="navi-bar">
	<div class="login-image" id="login-image">
		<div class="my-account" id="my-account">
		</div>
	</div>
</div>
<div style="margin-top:25px; " id="fix-ie" class="navbar">
	<div id="inn" class="navbar-inner" style="clear:none; !important">
		<ul class="nav mob-nav" id="navbar-btns">
			<li class="activity-slip">
			<a class="nav-link-admin1" id="activity-slip-link" href="<?php echo SITEURL;?>/activitysheet" style="padding-left: 32px; background: url(<?php echo SITEURL; ?>/media/images/tt-new/add-activity-hover.png) no-repeat scroll 50% 60% transparent;"><div class="act-slip-mar" style="margin-left: 0px;">Activity Slips</div></a>
			<a class="phone-nav-link-admin1" style="display:none;" id="activity-slip-link" href="<?php echo SITEURL;?>/activitysheet"   style="padding-left: 10px; background: url(<?php echo SITEURL; ?>/media/images/tt-new/slips-image.png) no-repeat scroll 46% 48% transparent;"><div class="act-slip-mar" >Slips</div></a>
			</li>
			<li class="refresh-slip">
			<a href="javascript:void(0);" onclick="refresh_list_confirm(this);"  id="" class="nav-link-admin2"><div class="list-refresh">Refresh Lists</div></a>
			<div class="page-title">
			</div>
			</li>
			<li class="nav-list-item3 activity-label">
				<div class="activity-slips-view">Activity Slip</div>
			</li>
			<li class="right search-bar">
    			<form class="jqtransform" id="activity_slip_search" name="activity_slip_search" action="<?php echo SITEURL?>/activitysheet" method="post">
    			<?php 
    			$seach_value	=	isset($_POST['act_search'])?$_POST['act_search']:"";
    			?>
    			<input class="span3 search-field" id="userappendedInputButton" placeholder="search" size="16" type="text" name="act_search" style="height:17px !important;" value="<?php echo $seach_value;?>">
    			<a href="javascript:void(0);" onclick="clear_search_results('<?php echo SITEURL?>','2')" class="clear_search">X</a>
    			</form>  
			</li>
		</ul>
    </div> 
</div>
<div class="row-fluid" id="account-section-container" > 
	<!-- Sidebar Starts-->
    <div class="span3 minHeight1" id="sidebar" >
	</div>
   
   <!-- For retina display -->
   <style>
      @media only screen and (-webkit-min-device-pixel-ratio: 2) {
          a.dp-choose-date
          {
          background: url("/media/images/tt-new/retina-calender-btn.png") no-repeat scroll 0 0 transparent;
          background-size: 33px 33px;
          }
      }
  </style>
	
<script type="text/javascript">			 
$(document).ready(function(){
	
	var wid=0;
	wid-=($('#slip_button').width())/2;
	var hgt=($(window).height()) / 2;
	hgt-=($('#slip_button').height())/2;
	$("#slip_button").css("left",wid);
	$("#slip_button").css("top",hgt);
	$("#slip_button").css("width",'270px');
	
	if($(window).width() <= 400) {
    $(".nav-link-settings").css("padding-left","0px");
    $("#activity-slip-link").css("padding-left","20px");
    $(".date-cust").css("margin-left","-30px");
    $(".nav-link-admin2").hide();
    $(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 0% 60% no-repeat");
    $(".btn-refresh").show();
    $(".search-field").css("box-shadow","none");
    
    
    $(".act-slip-mar").css("margin-left","0px");
    $("#activity-slip-link").css("padding-left","20px");
    $("#activity-slip-link").css("padding-right","36px");
    $(".nav-link-admin1").hide();
    $(".phone-nav-link-admin1").show();
    
    $("#datepicker").css("font-size","16px");
    $("#datepicker").css("width","130px");
    
    $(".refresh-btn-label").text('Refresh');
    $(".popup_alert").css("left","-135px");
    $(".popup_alert").css("width","270px");
    $('.activity-slips-view').text("Add Slip");
	}
else if(($(window).width() > 400)  && ($(window).width() <= 500)) 
    {	
    	$(".nav-link-settings").css("padding-left","70px");
    	$("#activity-slip-link").css("padding-left","0px");
    	$(".act-slip-mar").css("margin-left","30px");
    	$(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 54% 60% no-repeat");
    	$(".btn-refresh").show();
    	$(".nav-link-admin2").hide();
    	$(".refresh-btn-label").text('Refresh');
    	$(".nav-link-admin1").show();
    	$(".phone-nav-link-admin1").hide();
    	$("#datepicker").css("font-size","18px");
    	$("#datepicker").css("width","140px");
    	$('.activity-slips-view').text("Add Slip");
    }
else if(($(window).width() > 500)  && ($(window).width() <= 600)) 
{	
	$(".nav-link-settings").css("padding-left","70px");
	$("#activity-slip-link").css("padding-left","0px");
	$(".act-slip-mar").css("margin-left","30px");
	$(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 54% 60% no-repeat");
	$(".btn-refresh").show();
	$(".nav-link-admin2").hide();
	$(".refresh-btn-label").text('Refresh');
	$(".nav-link-admin1").show();
	$(".phone-nav-link-admin1").hide();
	$("#datepicker").css("font-size","18px");
	$("#datepicker").css("width","140px");
	$('.activity-slips-view').text("Add Activity Slip");
}
else if(($(window).width() > 600)  && ($(window).width() <= 720)) 
    {
    	$(".nav-link-settings").css("padding-left","70px");
    	$("#activity-slip-link").css("padding-left","0px");
    	$(".act-slip-mar").css("margin-left","30px");
    	$(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 54% 60% no-repeat");
    	$(".btn-refresh").show();
    	$(".nav-link-admin2").hide();
    	$(".refresh-btn-label").text('Refresh');
    	$(".nav-link-admin1").show();
    	$(".phone-nav-link-admin1").hide();
    	$('.activity-slips-view').text("");
    	$("#datepicker").css("font-size","18px");
    	$("#datepicker").css("width","140px");
    }
else if (($(window).width() > 720) && ($(window).width() <= 1250)) 
    {
    $(".nav-link-settings").css("padding-left","35px");
    $("#activity-slip-link").css("padding-left","32px");
    $(".act-slip-mar").css("margin-left","0px");
    $(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 50% 60% no-repeat");
    $(".btn-refresh").hide();
    $(".nav-link-admin2").show();
    $("body").css("padding-left","0%");
    $("body").css("padding-right","0%");
    $('.activity-slips-view').text("Add Activity Slip");
    $(".search-field").css("box-shadow","none");
    $(".nav-link-admin1").show();
    $(".phone-nav-link-admin1").hide();
    $("#datepicker").css("font-size","18px");
    $("#datepicker").css("width","140px");
    }
else if($(window).width() > 1250) 
    {
    //alert($(window).width());
    $(".nav-link-settings").css("padding-left","72px");
    $("#activity-slip-link").css("padding-left","32px");
    $(".act-slip-mar").css("margin-left","0px");
    $(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 50% 60% no-repeat");
    $(".btn-refresh").hide();
    $(".nav-link-admin2").show();
    $(".nav-link-admin1").show();
    $(".phone-nav-link-admin1").hide();
    $('.activity-slips-view').text("Add Activity Slip");
    $("#datepicker").css("cssText","font-size:18px !important");
    $("#datepicker").css("width","170px");
    $(".search-field").css("box-shadow","none");
    }

$(window).resize(function()
{
if($(window).width() <= 400) 
{
	$(".nav-link-settings").css("padding-left","0px");
	$("#activity-slip-link").css("padding-left","20px");
	$(".date-cust").css("margin-left","-30px");
	$(".nav-link-admin2").hide();
	$(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 0% 60% no-repeat");
	$(".btn-refresh").show();

	$(".search-field").css("box-shadow","none");


	$('.activity-slips-view').text("Add Slip");
	$(".act-slip-mar").css("margin-left","0px");
	$("#activity-slip-link").css("padding-left","20px");
	$("#activity-slip-link").css("padding-right","36px");
	$(".nav-link-admin1").hide();
	$(".phone-nav-link-admin1").show();

	$("#datepicker").css("font-size","16px");
	$("#datepicker").css("width","130px");
	$(".refresh-btn-label").text('Refresh');
	$(".popup_alert").css("left","-135px");
	$(".popup_alert").css("width", "270px");

}

else if(($(window).width() > 400)  && ($(window).width() <= 500)) 
{

$(".nav-link-settings").css("padding-left","70px");
$("#activity-slip-link").css("padding-left","0px");
$(".act-slip-mar").css("margin-left","30px");
$(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 54% 60% no-repeat");
$(".btn-refresh").show();
$(".nav-link-admin2").hide();


$('.activity-slips-view').text("Add Slip");
 $(".refresh-btn-label").text('Refresh');
$(".nav-link-admin1").show();
$(".phone-nav-link-admin1").hide();

//$(".act-slip-lbl").css("width","100%");
$("#datepicker").css("font-size","18px");
$("#datepicker").css("width","140px");

}
else if(($(window).width() > 500)  && ($(window).width() <= 600)) 
{

$(".nav-link-settings").css("padding-left","70px");
$("#activity-slip-link").css("padding-left","0px");
$(".act-slip-mar").css("margin-left","30px");
$(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 54% 60% no-repeat");
$(".btn-refresh").show();
$(".nav-link-admin2").hide();


$('.activity-slips-view').text("Add Activity Slip");
 $(".refresh-btn-label").text('Refresh');
$(".nav-link-admin1").show();
$(".phone-nav-link-admin1").hide();

$("#datepicker").css("font-size","18px");
$("#datepicker").css("width","140px");

}
else if(($(window).width() > 600)  && ($(window).width() <= 720)) 
{

$(".nav-link-settings").css("padding-left","70px");
$("#activity-slip-link").css("padding-left","0px");
$(".act-slip-mar").css("margin-left","30px");
$(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 54% 60% no-repeat");
$(".btn-refresh").show();
$(".nav-link-admin2").hide();


$('.activity-slips-view').text("Add Activity Slip");
 $(".refresh-btn-label").text('Refresh');
$(".nav-link-admin1").show();
$(".phone-nav-link-admin1").hide();

$("#datepicker").css("font-size","18px");
$("#datepicker").css("width","140px");

}
else if(($(window).width() > 720) && $(window).width() <= 1250) 
{
$(".nav-link-settings").css("padding-left","35px");
$("#activity-slip-link").css("padding-left","32px");
$(".act-slip-mar").css("margin-left","0px");
$(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 50% 60% no-repeat");
$(".btn-refresh").hide();
$(".nav-link-admin2").show();

$('.activity-slips-view').text("Add Activity Slip");
$(".nav-link-admin1").show();
$(".phone-nav-link-admin1").hide();

$("#datepicker").css("font-size","18px");
$("#datepicker").css("width","140px");

$(".search-field").css("box-shadow","none");

$(".popup_alert").css("width","270px");

}
else if(($(window).width() > 1250)) 
{
$(".nav-link-settings").css("padding-left","72px");
$("#activity-slip-link").css("padding-left","32px");
$(".act-slip-mar").css("margin-left","0px");
$(".nav-link-admin1").css("background","url('<?php echo SITEURL?>/media/images/tt-new/add-activity-hover.png') 50% 60% no-repeat");
$(".btn-refresh").hide();
$(".nav-link-admin2").show();

$('.activity-slips-view').text("Add Activity Slip");
$(".nav-link-admin1").show();
$(".phone-nav-link-admin1").hide();

$("#datepicker").css("cssText","font-size:18px !important");

$("#datepicker").css("width","170px");
$(".search-field").css("box-shadow","none");


}
var wid=0;
wid-=($('#slip_button').width())/2;
var hgt=($(window).height()) / 2;
hgt-=($('#slip_button').height())/2;
$("#slip_button").css("left",wid);
$("#slip_button").css("top",hgt);
	
});
if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
{
var images = $('img.arrow-down-retina');   
for(var i=0; i < images.length; i++)
{
images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/down-arrow.png', '/media/images/tt-new/retina-arrow-down.png'))
$('.arrow-down-retina').css('width','13px')
$('.arrow-down-retina').css('height','8px')
}
}
});
	</script>		
                  <!-- rateDisplay-on - showJobs-on script end-->
   			<div class="span6" id="user-right-content" class="user-right-content">
				 <!-- Center contents starts-->
				 <?php if(!isset($employee_list)){?>
				<div class="no_employee_activated">Please activate your employees by clicking <a href="<?php echo SITEURL.'/admin'?>" class="click_here">here</a></div>
				<?php }?>
				<div class="calender-field">
					<form id="chooseDateForm" action="#" name="chooseDateForm">
						<input type="text" id="datepicker" class="date-label date-cust text-align-right" readonly="readonly" value="<?php  if(isset($slip['SlipDate'])) { echo date("F d, Y", strtotime($slip['SlipDate']));} elseif(isset($slip_last_date)) { echo date("F d, Y", strtotime($slip_last_date));} else { echo date("F d, Y"); }?>" />
					</form>
				</div>
				<div class="clear"></div>
			<a href="javascript:void(0);" onclick="refresh_list_confirm(this);"  id="" ><div class="btn-refresh">Refresh Lists</div>  </a>
				
				<form action='<?php echo SITEURL;?>/activity/create' method="post" id="create" >
						<input type='hidden' id='SITE_URL' value='<?php echo SITEURL;?>' />
						<input type='hidden' value='<?php echo $slip['RecordID']?>' name='slip_id' id='slip_id' />
						<input type='hidden' value='0' name='save_create' id='save_create' />
						<input type='hidden' value='<?php echo $slip['SlipDate']['Day'].'-'.$slip['SlipDate']['Month'].'-'.$slip['SlipDate']['Year']?>' name='created_date' id='create_date'/>
						<input type="hidden" class="is_admin" value="<?php if(isset($_SESSION['admin_user'])) echo $_SESSION['admin_user']; else echo 1;?>">
			<div class="row customer-row">
			
			<input type="hidden" value="<?php if(isset($_SESSION['admin_user']) && isset($employee_list) && $_SESSION['admin_user']== 0) { echo $linked_job;}?> " class="linked_job" />
			<div class="span3 customer-tag" id="customer-tag" >
			
			<p> 
			 Customer
			<input type="hidden" name="timesheet_date" id="timesheet_date" value="" />
			</p> 
			<div class="clear"></div>
		</div> 
	</div>
	<div class="new-activity">
		<?php   
		if(isset($error))
	    {
		echo "<div class='error_message'>".$error."</div>";
		}						
	    ?>		 
	 	<div class="row">
	 		<div class="left customer-tag1 height-45">
				<div id="customer_section" class="activity-form-validator">
	    			<input type="text" readonly tabindex="1" class="customer-tag1-textfield height-31 start-typing customer-name" id='customer_name' 
	    				onkeyup = 'openPopup(this, event, "customer" , <?php echo json_encode($data_names['customers_name']); ?>, "customer-keydown" )'   onfocus="clear_message(this.id)" name='customer_name' value="<?php echo isset($slip['CustomerCompanyOrLastName'])?html_entity_decode($slip['CustomerCompanyOrLastName']):'Enter name...';//start typing...'; ?>"  onblur='check_field_selection("customer","<?php echo SITEURL; ?>")' autocomplete="off" />
	    			<input type='hidden' id='customer' name ='customer1' value='<?php echo isset($slip['CustomerRecordID'])?$slip['CustomerRecordID']:'';?>'  / >
	    			<input type='hidden' id='customer_val' name ='customer' value='<?php echo isset($slip['CustomerRecordID'])?$slip['CustomerRecordID']:'';?>'  / >
	   				<a href="javascript:void(0);" class="down-arrow-customer cust-arrow" class="seperator popup-display"
	   					onclick = 'openPopup(this, event, "customer" , <?php echo json_encode($data_names['customers_name']); ?>)'							
	   					name='a_customer_name' id="cust-down-arrow" > 
	   					<img class="arrow-down-retina arrow_customer_retina" src="<?php echo SITEURL?>/media/images/tt-new/down-arrow.png"> 
	   				</a>
   				</div>
   				<div class="validation-error error-background last-row relative-pos" id="customer_error">
					<div class="error-desc">
						<img src="/media/images/tt-new/reset_error.png" />Please select a customer from the list.
					</div>
				</div>
			</div>
	 		<div class="search-contents slip-height-45 mar-top-0 width-47 right" >
				<div id="slipnumber_section" class="activity-form-validator">
				  	<div class="span3 users-activity auto-width" id="users-activity">Slip Number</div> 
				      	<input type="text"  class="customer-textfield slip"   id='slip_number' name='slip_number' tabindex="2" onfocus="clear_message(this.id)" value='<?php 
						if(!empty($slip_number))	// system generated auto ID.
						{
							echo $slip_number;
						} 
						else
						{	
							echo isset($slip['SlipIDNumber'])?$slip['SlipIDNumber']:"type slip number"; 
						}
						
						?>'   maxlength='30'>
				</div>
				<div class="validation-error error-background last-row relative-pos" id="slipnumber_error">
					<div class="error-desc">
						<img src="/media/images/tt-new/reset_error.png" />Please enter slip number.
					</div>
				</div>
    		</div>
	  </div>
			<div class="clear"></div>	

		<div class="search-contents height-45 width-100 left" >
			<div class="row search-contents-row">
				<div id="emp_field_activity" class="activity-form-validator">
					<div class="span3 users-activity" id="users-activity" >Employee</div> 
					<input type="text" readonly tabindex="3" class="employee_field" name="" value="<?php if(isset($slip['EmployeeFirstName'])){echo $slip['EmployeeFirstName'].' '.$slip['EmployeeCompanyOrLastName'];} else if(isset($_SESSION['admin_user']) && isset($employee_list) && $_SESSION['admin_user']== 0) { echo $employee_list[0]['name']; } else {echo '';}?>"> 
					<input type="hidden" name="emp_rec_id" class="employee_record_id" value="<?php if(isset($slip['EmployeeRecordID'])){echo $slip['EmployeeRecordID'];} else if(isset($_SESSION['admin_user']) && isset($employee_list) && $_SESSION['admin_user']== 0) { echo $employee_list[0]['record_id']; } else {echo '';}?>" />
					<input type="hidden" name="emp_name" class="employee_name" value="<?php if(isset($slip['EmployeeFirstName'])){echo $slip['EmployeeFirstName'].' '.$slip['EmployeeCompanyOrLastName'];} else if(isset($_SESSION['admin_user']) && isset($employee_list) && $_SESSION['admin_user']== 0) { echo $employee_list[0]['name']; } else {echo '';}?>" />
					<?php if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']== 1) {?>
					<a href="javascript:void(0);" class="down-arrow-3 employee_arrow selected_emp" onclick='' name="" id="">
						<img class="emp-arrow-down-retina" src="<?php echo SITEURL?>/media/images/tt-new/down-arrow.png"> 
					</a>
					<?php }?>
					<?php if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']== 0 && isset($employee_list)) {?>
					<script type="text/javascript">
					$(document).ready(function(){
						get_show_jobs('sync','<?php echo $employee_list[0]['record_id']?>','<?php echo $employee_list[0]['name']?>');
					});
					</script>
					<?php }?>
					<?php if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']== 1) {?>
					<div class="employee-popup" style="display:none;">
    					<div class="layout-names">
    					<label class="layout-label-names dull">Employee</label>
    					<input type="text" id="employee_names_popup" class="search-names-popup" placeholder="search"/>
    					</div>
    					<div class="popup-items2 popup-list">
    					<?php if(isset($employee_list)) foreach($employee_list as $e) {?>
    					<div class="names-popup-list first names-list-row" onclick=" get_show_jobs('sync','<?php echo $e['record_id']?>','<?php echo $e['name']?>');select_emp_field('<?php echo $e['record_id']?>','<?php echo $e['name']?>');set_billing_rate();" id="names-list-row-<?php echo $e['record_id']?>" >
    						<label class="timesheet-popup-list-label heavy"><?php echo $e['name']?></label>
    					</div>
    					<?php } ?>
    					</div>
    				</div>
    				<?php }?>
				</div>
				<div class="validation-error error-background last-row relative-pos" id="emp_error">
					<div class="error-desc">
						<img src="/media/images/tt-new/reset_error.png" />Please select an Employee name.
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="clear"></div>
				
	 <div class="search-contents activity height-90" >
	 	<div class="row search-contents-row" >
	  	<div id="activity_section" class="activity-form-validator height-45">
			 <div class="span3 users-activity" id="users-activity" >Activity</div> 
			 <input type="text" tabindex="4" readonly class="customer-textfield activity_tt_field" id='activity_name' name='activity_name' onfocus="clear_message(this.id)" 
			onkeyup='openPopup(this, event,"activity", <?php echo json_encode($data_names['activites_name']); ?>, "activity-keydown" )' 
			value='<?php echo isset($slip['ActivityID'])?html_entity_decode($slip['ActivityID']):"";//'start typing activity name'; ?>'  onblur='check_field_selection("activity", "<?php echo SITEURL; ?>")' />
			<input type='hidden' id='activity' name ='activity'  value='<?php echo isset($slip['ActivityID'])?$slip['ActivityID']:'';?>'  />
			<input type='hidden' id='which_rate'  value='<?php echo isset($slip['which_rate'])?$slip['which_rate']:'';?>'  />
			<?php
				if(isset($slip['RecordID']) && $slip['Is_non_hourly']) {
					$is_non_hourly = 1;//'true';
				} else {
					$is_non_hourly = 0;//'false';
				}
			?>
			<input type='hidden' id='is_non_hourly' name ='is_non_hourly' value="<?php echo $is_non_hourly;?>"  /> 
			<a href="javascript:void(0);" class="down-arrow-3 activity_arrow" onclick='return openPopup(this, event,"activity", <?php echo json_encode($data_names['activites_name']); ?>)' name='a_activity_name' id="down-arrow">
				<img class="arrow-down-retina activity_arrow_down" src="<?php echo SITEURL?>/media/images/tt-new/down-arrow.png" /> 
			</a>
		</div>
		<div class="validation-error error-background relative-pos" id="activity_error">
			<div class="error-desc">
				<img src="/media/images/tt-new/reset_error.png" />Please select an activity from the list.
			</div>
		</div>
	  </div>
	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row">
	  <div id="unit_section" class="activity-form-validator">
		  <div class="span3 hourly-activity" id="hourly-activity">Units</div> 
		   <input type="text"  name='units' tabindex="5" id='units' maxlength='20' onfocus="clear_message(this.id)" onblur="setTotal('1')" value="<?php echo isset($slip['Units'])?$slip['Units']:"";?>" class="number customer-textfield1" >
		   <?php 
		    $isnonHourly = isset($slip['Activities']['IsActivityNonHourly'])?$slip['Activities']['IsActivityNonHourly']:false; 
		   ?>
			<span id="set_time"
			<?php 
				if($isnonHourly == true){
					echo " style='display:none;' ";
				}												
			?>
		 	>
		  <a href="javascript:void(0);" onclick="startTime()" class="measure-time">
		  <img src="<?php echo SITEURL?>/media/images/tt-new/clock.png" id="clock-img"></a>
		  </span>
		 <div class="timer-display" id="timmer">
		 	<div class="time-disp" id='start_time' >00.00</div>
				<div class="buttons">
					<span class="new-span pause" id='start_pause'><a href="javascript:void(0);" onclick="startTime('Pause')" class="" >Pause</a></span>
					<span class="new-span submit-time"><a href="javascript:void(0);" onclick="startTime('stop')" class="" >Submit</a></span>
				</div>
			<a href="javascript:void(0);" onclick="close_timmer()" ><span class="close-button">Close</span></a>
		</div>
	  
	 	</div>
	 	<div class="validation-error error-background last-row relative-pos" id="unit_error">
			<div class="error-desc">
				<img src="/media/images/tt-new/reset_error.png" />Please enter units as a numeral (no letters or special characters).
			</div>
		</div>
	  </div>
	  </div>

	  <?php if(isset($_SESSION['display_rate']) && $_SESSION['display_rate'] == 1) {?>
	  <div class="search-contents height-90" >
	  <div class="row search-contents-row" >
		  <div id="rate_section" class="activity-form-validator height-45">
			   <div class="span3 users-activity" id="users-activity" >Rate</div> 
			   <input type="text" tabindex="6" class="customer-textfield rate-total number" name='rate' id='rate' onblur="setTotal()" maxlength='20' value='<?php echo $slip['Rate']?>' >
		  </div>
		  <div class="validation-error error-background relative-pos" id="rate_error">
				<div class="error-desc">
					<img src="/media/images/tt-new/reset_error.png" />Please enter rate.
				</div>
			</div>
	  </div>
	  <div id="search-contents-margin" >
	  </div>
	  <div class="row search-contents-row" >
	  <div id="total_section" class="activity-form-validator">
		  <div class="span3 hourly-activity" id="hourly-activity">Total</div> 
		  <input type="text"  class="customer-textfield1 rate-total" class="slip-dollar" name='total' id='total' value='<?php if(isset($slip['Units']) && isset($slip['Rate'])){ echo $slip['Units']*$slip['Rate']; }?>' readonly='readonly' maxlength='20' class="bold">
	  </div>
	  <div class="validation-error error-background last-row relative-pos" id="total_error">
			<div class="error-desc">
				<img src="/media/images/tt-new/reset_error.png" />Please enter total with out alphabets and special characters.
			</div>
	  </div>
	  </div>
	  </div>
	  
	  <input type="hidden" value="0" name="display_rate_check" id="display_rate_check" />
							<?php } else {?>
									<input type="hidden" name='rate' id='rate' maxlength='20' value='<?php echo $slip['Rate']?>' />
									<input type="hidden" name='total' onfocus=setTotal()  id='total' value='<?php if(isset($slip['Units']) && isset($slip['Rate'])){ echo $slip['Units']*$slip['Rate']; }?>' readonly='readonly' maxlength='20' class="bold">
									<input type="hidden" value="1" name="display_rate_check" id="display_rate_check" />
							<?php }?>
					
	  <div class="search-contents job" >
		  <div class="row activity-form-validator height-45" >
		  	<div class="span3 users-activity" id="users-activity" >Job</div> 
		  		<input type="text" readonly tabindex="7" class="customer-textfield customer_job" id='job'  name='job' onfocus="clear_message(this.id)" 
											onkeyup = 'openPopup(this, event, "job" , <?php echo json_encode($data_names['jobs_name']); ?>, "job-keydown")'					
											value='<?php echo isset($slip['JobNumber'])?html_entity_decode($slip['JobNumber']):"";//'Start typing job name'; ?>' onblur='check_field_selection("job", "<?php echo SITEURL; ?>")' >
				<input type='hidden' id='job_name' name ='job_name' value='<?php echo isset($slip['JobNumber'])?$slip['JobNumber']:''; ?>'   / >
		 		<a href="javascript:void(0);" onclick = 'openPopup(this, event, "job" , <?php echo json_encode($data_names['jobs_name']); ?>)' class="down-arrow-job" name='a_job_name' id="down-arrow">
					<img class="arrow-down-retina customer_job_arrow" src="<?php echo SITEURL?>/media/images/tt-new/down-arrow.png"> 
		   		</a>
		  </div>
	 </div>
	  
	  <div class="search-contents hidden-overflow" >
	  	<div class="row search-contents-row">
	  		<div class="span3 users-activity" id="users-activity" >Notes</div> 
	  		<textarea class="cust-textArea" tabindex="8" rows="3" name='notes' id='notes' class='notes' onfocus="clear_message(this.id)" onkeyup='autoExpand(this, event)' onblur="jump_to_save_button('slip_save_button');"><?php echo isset($slip['Notes'])?$slip['Notes']:"";//'Enter notes';?></textarea>
	  	</div>
	  </div>
	   
	   
	    <div class="cancel-save-buttons" >
		   <button class="btn btn-small" tabindex="9" id="cancel-btn" type="button"  name="act_slip_cancel"  onclick="check_changes('<?php echo SITEURL; ?>')" >Cancel</button>
		  <button class="btn btn-small" tabindex="10" id="save-btn" type="button"  name="act_slip_save"  onclick='activity_validation("<?php echo SITEURL;?>")' onfocus="highlight_slip_save_button('slip_save_button');" onblur="show_normal_save_button('slip_save_button');" id="slip_save_button">Save</button>
		</div>		
		</div>
		</form>
				<!-- Center contents ends -->
	  <div class="notice-period" style="margin-top:9%;color:#A9A9A9;">  
	 <label id="ex1"><?php if(isset($_SESSION['free_user']) && $_SESSION['free_user'] == 1) {?>					
						You have <?php echo $_SESSION['days_left']?> day(s) left. Please contact your company
						Administrator to upgrade your plan.					
			<?php }?>
			</label>
	  </div>
		</div>
	 <div class="span3" id="sidebar" >
   </div>
   </div>   
   <!--  add new activity slip in activity add page. -->
	<div class="popup_alert" id="new_slip_button">
		<div class="alert-pop-up">
 			<p class="question">How do you want to create a new Activity Slip?</p>
 			<p class="message">You can either save or discard currently edited slip and create a
			new one. This operation cannot be undone.</p>
 		</div>
		<a href="javascript:void(0);" onclick="close_popup('new_slip_button')" class="radius-5 button-1 left" >
		Cancel
		</a>
		<a href="javascript:void(0);" onclick="save_slip('new_slip_button','save_create')" class="radius-5 alert-save right">Save and Create</a>
		<a href="<?php echo SITEURL.'/activity/add'?>" class="radius-5 button-1 right">Discard and Create</a>
		

	</div>
	<!--  display of slips in activity add page. -->
	<div class="popup_alert" id='slip_button'  >
 		<div class="alert-pop-up">
 			<p class="question">Do you want to save the changes you made in this activity slip?</p>
 			<p class="message">Your changes will be lost if you don't save them.</p>
 		</div>
		
		<a href="javascript:void(0);" onclick="close_popup('slip_button')" class="radius-5 button-1 left">Cancel</a>
		<a href="javascript:void(0);" onclick="save_slip('slip_button')"  class="radius-5 alert-save right">Save</a>
		<a href='<?php echo SITEURL.'/activitysheet';?>' class="radius-5 button-1 right" style="margin-left: 0px;" >
		Don't Save
		</a>
	
	</div>
	
	<div class="popup_alert" id='refresh_customers_warn' >
 		<div class="alert-pop-up">
 			<p class="question">Do you want to save this activity slip before refreshing?</p>
 			<p class="message">Your changes will be lost if you don't save them.</p>
 		</div>
		<a href="javascript:void(0);" onclick="close_popup('refresh_customers_warn')" class="radius-5 button-1 left">Cancel</a>
		<a href="javascript:void(0);" onclick="save_slip('refresh_customers_warn')"  class="radius-5 alert-save right">Save</a>
		<a href='<?php echo SITEURL.$imp_cust_url;?>' class="radius-5 button-1 right" style="margin-left: 0px;" >
		Refresh without save
		</a>
		
	
	</div>
	
	<script type="text/javascript">
	window.onload	=	focus_customer;

	</script>
	
   <script type="text/javascript">
$(document).bind('click', function(e) {
	var $clicked = $(e.target);
if (($clicked.hasClass("search")) || ($clicked.hasClass("popup-content")) || ($clicked.hasClass("customer_job"))||($clicked.hasClass("activity_arrow_down"))||($clicked.hasClass("customer-tag1-textfield")) || ($clicked.hasClass("activity_tt_field"))|| ($clicked.hasClass("popup-title")) || ($clicked.hasClass("txt-search")) || ($clicked.hasClass("popup-list")) || ($clicked.hasClass("search_text")) || ($clicked.hasClass("arrow-down-retina"))){
	$('.popup').show();
} 
else{
	$('.popup').hide();
}
});

$(document).ready(function() 
{
$('.search-names-popup').keyup(function() 
{
	var txtVal = this.value; // for lower case
	var txtVal1 = txtVal.toUpperCase(); // for upper case
	var txtVal2 = txtVal.charAt(0).toUpperCase() + txtVal.substr(1).toLowerCase();  //for first character in upper case
	var data = <?php if(isset($employee_list)){echo json_encode($employee_list);}else{	echo "NULL";}?>;
	var result="";
	var result1="";
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
	
});
</script>
  