<!-- 
 * @File : list.php
 * @Author: 
 * @Created: 10-10-2012
 * @Modified:  
 * @Description: List timesheet, create timesheet and view timesheet
 * Copyright (c) 2012 Acclivity Group LLC 
-->

<input type='hidden' id='SITE_URL' value='<?php echo SITEURL;?>' />
<?php 
	$payroll_latest 		=	'';
	$payroll_latest_id		=	'';
	$payroll_name	='';
	
?>
<?php 	if(empty($timesheet_list))
						{
							$count	=	0;
						}else{
							$count	=	count($timesheet_list);	// time sheet element count.	
						}
					?>
<script type="text/javascript">
$(document).ready(function(){
//	$('#employee_add').val($('.emp_field').val());
	$('.date-cust').css('cssText','height:28px !important');
	$('.date-cust').css('cssText','width:164px !important');
	$('a.dp-choose-date').css('background','url("/media/images/tt-new/calender-logo.png") no-repeat scroll 50% 50% transparent');

	$('.search-names-popup').keyup(function() 
	{
		var txtVal 	= this.value; // for lower case
		var txtVal1 = txtVal.toUpperCase(); // for upper case
		var txtVal2 = txtVal.charAt(0).toUpperCase() + txtVal.substr(1).toLowerCase();  //for first character in upper case
		var data 	= <?php if(isset($employee_list) && count($employee_list)>0){
							echo json_encode($employee_list);
						} else { 
							echo "";
						} ?>;
		var result="";
		var result1="";
		<?php if(isset($employee_list)) {?>
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
		<?php }?>
	});
});
</script>
<script type="text/javascript">		
$(document).ready(function(){
	if($(window).width() < 400) 
	{
		$('.normal-screen-version').hide();
		$('.mobile-screen-version').show();
		$('#timesheet-week-hrs-btn').css("float","left");
		$('#timesheet-btn').hide();
		$('.add-timesheet-mobile-version').show();
		$('.mon-div2').hide();
		$('.mon-div3').hide();
		$('.mon-div4').hide();
		$('.mon-div5').hide();
		$('.mon-div6').hide();
		$('.wed-div').hide();
		$('.thu-div').hide();
		$('.fri-div').hide();
		$('.sat-div').hide();
		$('.sun-div').hide();
		$('.timesheet-week-hrs-btn').css("margin-top","4%");
		$('.sync-all-btn1').css("margin-top","4%");
		$('.sync-all-btn1').css("margin-left","0%");
		$('.timesheet-show-synced-btn').css("margin-left","2%");
		$('.timesheet-show-synced-btn').css("margin-top","4%");
		$('#search-nav').css("margin-top","1px");//5px
		$('#search-nav').css("padding-right","4px");//10px
		$('.navbar .nav').css('margin','0');
	}
	else if (($(window).width() > 400) && ($(window).width() <= 700))
	{
		$('.normal-screen-version').hide();
		$('.mobile-screen-version').show();
		$('#timesheet-week-hrs-btn').css("float","left");
		$('#timesheet-btn').hide();
		$('.add-timesheet-mobile-version').show();
		$('.mon-div2').hide();
		$('.mon-div3').hide();
		$('.mon-div4').hide();
		$('.mon-div5').hide();
		$('.mon-div6').hide();
		$('.wed-div').hide();
		$('.thu-div').hide();
		$('.fri-div').hide();
		$('.sat-div').hide();
		$('.sun-div').hide();
		$('.timesheet-week-hrs-btn').css("margin-top","4%");
		$('.sync-all-btn1').css("margin-top","4%");
		$('.sync-all-btn1').css("margin-left","0%");
		$('.timesheet-show-synced-btn').css("margin-left","2%");
		$('.timesheet-show-synced-btn').css("margin-top","4%");
		$('#search-nav').css("margin-top","8px");//5px
		$('#search-nav').css("padding-right","4px");//10px
		$('.navbar .nav').css('margin','0');
	}
	else if (($(window).width() > 700) && ($(window).width() < 900))
	{
		$('.normal-screen-version').show();
		$('.mobile-screen-version').hide();
		$('#timesheet-week-hrs-btn').css("float","right");
		$('#timesheet-btn').show();
		$('.add-timesheet-mobile-version').hide();
		$('.timesheet-week-hrs-btn').css("margin-top","0");
		$('.sync-all-btn1').css("margin-top","0");
		$('.sync-all-btn1').css("margin-left","0");
		$('.timesheet-show-synced-btn').css("margin-left","0");
		$('.timesheet-show-synced-btn').css("margin-top","0");
		$('#search-nav').css("margin-top","2px");//5px
		$('#search-nav').css("padding-right","10px");//10px
		
		$('.navbar .nav').css('margin','0 10px 0 0');
	}
	else
	{
		$('.normal-screen-version').show();
		$('.mobile-screen-version').hide();
		$('#timesheet-week-hrs-btn').css("float","right");
		$('#timesheet-btn').show();
		$('.add-timesheet-mobile-version').hide();
		$('.timesheet-week-hrs-btn').css("margin-top","0");
		$('.sync-all-btn1').css("margin-top","0");
		$('.sync-all-btn1').css("margin-left","0");
		$('.timesheet-show-synced-btn').css("margin-left","0");
		$('.timesheet-show-synced-btn').css("margin-top","0");
		$('#search-nav').css("margin-top","0px");//5px
		$('#search-nav').css("padding-right","10px");//10px
		$('.navbar .nav').css('margin','0 10px 0 0');
	}

	$(window).resize(function()
	{
		if($(window).width() < 400)
		{
			$('.normal-screen-version').hide();
			$('.mobile-screen-version').show();
			$('#timesheet-week-hrs-btn').css("float","left");
			$('#timesheet-btn').hide();
			$('.add-timesheet-mobile-version').show();
			$('.mon-div2').hide();
			$('.mon-div3').hide();
			$('.mon-div4').hide();
			$('.mon-div5').hide();
			$('.mon-div6').hide();
			$('.wed-div').hide();
			$('.thu-div').hide();
			$('.fri-div').hide();
			$('.sat-div').hide();
			$('.sun-div').hide();
			$('.timesheet-week-hrs-btn').css("margin-top","4%");
			$('.sync-all-btn1').css("margin-top","4%");
			$('.sync-all-btn1').css("margin-left","0%");
			$('.timesheet-show-synced-btn').css("margin-left","2%");
			$('.timesheet-show-synced-btn').css("margin-top","4%");
			$('#search-nav').css("margin-top","1px");//5px
			$('#search-nav').css("padding-right","4px");//10px
			$('.navbar .nav').css('margin','0');
		}
		else if (($(window).width() > 400) && ($(window).width() <= 700))
		{
			$('.normal-screen-version').hide();
			$('.mobile-screen-version').show();
			$('#timesheet-week-hrs-btn').css("float","left");
			$('#timesheet-btn').hide();
			$('.add-timesheet-mobile-version').show();
			$('.mon-div2').hide();
			$('.mon-div3').hide();
			$('.mon-div4').hide();
			$('.mon-div5').hide();
			$('.mon-div6').hide();
			$('.wed-div').hide();
			$('.thu-div').hide();
			$('.fri-div').hide();
			$('.sat-div').hide();
			$('.sun-div').hide();
			$('.timesheet-week-hrs-btn').css("margin-top","4%");
			$('.sync-all-btn1').css("margin-top","4%");
			$('.sync-all-btn1').css("margin-left","0%");
			$('.timesheet-show-synced-btn').css("margin-left","2%");
			$('.timesheet-show-synced-btn').css("margin-top","4%");
			$('#search-nav').css("margin-top","8px");//5px
			$('#search-nav').css("padding-right","4px");//10px
			$('.navbar .nav').css('margin','0');
		}
		else if (($(window).width() > 700) && ($(window).width() < 900))
		{
			$('.normal-screen-version').show();
			$('.mobile-screen-version').hide();
			$('#timesheet-week-hrs-btn').css("float","right");
			$('#timesheet-btn').show();
			$('.add-timesheet-mobile-version').hide();
			$('.timesheet-week-hrs-btn').css("margin-top","0");
			$('.sync-all-btn1').css("margin-top","0");
			$('.sync-all-btn1').css("margin-left","0");
			$('.timesheet-show-synced-btn').css("margin-left","0");
			$('.timesheet-show-synced-btn').css("margin-top","0");
			$('#search-nav').css("margin-top","2px");//5px
			$('#search-nav').css("padding-right","10px");//10px
			$('.navbar .nav').css('margin','0 10px 0 0');
		}
		else
		{
			$('.normal-screen-version').show();
			$('.mobile-screen-version').hide();
			$('#timesheet-week-hrs-btn').css("float","right");
			$('#timesheet-btn').show();
			$('.add-timesheet-mobile-version').hide();
			$('.timesheet-week-hrs-btn').css("margin-top","0");
			$('.sync-all-btn1').css("margin-top","0");
			$('.sync-all-btn1').css("margin-left","0");
			$('.timesheet-show-synced-btn').css("margin-left","0");
			$('.timesheet-show-synced-btn').css("margin-top","0");
			$('#search-nav').css("margin-top","0px");//5px
			$('#search-nav').css("padding-right","10px");//10px
			$('.navbar .nav').css('margin','0 10px 0 0');
		}
	});
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.image-plus-mobile');   
		for(var i=0; i < images.length; i++)
		{
			images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/activity-plus.png', '/media/images/tt-new/plus.png'))
			$('.image-plus-mobile').css('width','14px')
			$('.image-plus-mobile').css('height','14px')
		}
	}
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.arrow_hd');   
		for(var i=0; i < images.length; i++)
		{
			images.eq(i).attr('src', images.eq(i).attr('src').replace('media/images/tt-new/left-arr.png', '/media/images/tt-new/retina-prev-active.png'))
			$('.arrow_hd').css('width','9px')
			$('.arrow_hd').css('height','14px')
		}
	}
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.arrow_hd1');   
		for(var i=0; i < images.length; i++)
		{
			images.eq(i).attr('src', images.eq(i).attr('src').replace('media/images/tt-new/right-arrow.png', '/media/images/tt-new/retina-next-active.png'))
			$('.arrow_hd1').css('width','9px')
			$('.arrow_hd1').css('height','14px')
		}
	}
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
	{
		var images = $('img.img-arr');   
		for(var i=0; i < images.length; i++)
		{
			images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/timesheet-arrow-left.png', '/media/images/tt-new/retina-timesheet-arrow.png'))
			$('.img-arr').css('width','16px')
			$('.img-arr').css('height','16px')
		}
	}
});
</script>

<script>
$(document).ready(function(){
	$('.add-customer-toggle').hide();
	$('.mon-div6').removeClass('date');
	$('.mon-div6').addClass('date1');
	$('.mon-div2').removeClass('date');
	$('.mon-div3').removeClass('date');
	$('.mon-div4').removeClass('date');
	$('.mon-div5').removeClass('date');
});
</script>
	
<script><!--
function toggleCustomerAdd(chk,num,flag)
{
	var emp = $('.emp_field').val();
	if(flag==0){
    	if($("#n-c-c").hasClass('add-customer-toggle') == true)
		{
			$('#n-c-c').toggle("slow");
		}
    	$('#hourly-details-span1').focus();
    	/*$('#hourly-details-span1 .img-arr1').attr("src","/media/images/tt-new/retina-timesheet-arrow1.png");
    	$('.img-arr1').css('width','16px');
    	$('.img-arr1').css('height','16px');*/
    }
    else if(flag==1 && emp!=''){
    	if($("#n-c-c").hasClass('add-customer-toggle') == true)
		{
			$('#n-c-c').toggle("slow");
		}
    	$('#employee_add').val($('.emp_field').val());
    	$('#hourly-details-span1').focus();
    	/*$('#hourly-details-span1 .img-arr1').attr("src","/media/images/tt-new/retina-timesheet-arrow1.png");
    	$('.img-arr1').css('width','16px');
    	$('.img-arr1').css('height','16px');*/
    }
    else
    {
		 $('.timesheetValidation').css('display','block');  
		 $('.filter_employees').css('height','75px'); 
		 $('.filter_employees').removeClass('grey_background');
		 $('.emp_field').css('cssText','background:#FFFCE8 !important');
     
    }
}
</script>
<!-- Script for motion of calendar-->
	
<script>
function calenderMotionLeftMobile(chk)
{
    if(($('.mon-div6').hasClass('date1')) && ($('.mon-div6').hasClass('date')))
    {
	    $('.mon-div4').addClass('date');
	    $('.mon-div5').addClass('date');
	    $('.date').slideToggle("slow");
	    $('.mon-div6').removeClass('date');
    }
    else if (($('.mon-div4').hasClass('date')) && ($('.mon-div5').hasClass('date')))
    {
	    $('.mon-div2').addClass('date');
	    $('.mon-div3').addClass('date');
	    $('.date').slideToggle("slow");
	    $('.mon-div4').removeClass('date');
	    $('.mon-div5').removeClass('date');
     }
    else if (($('.mon-div2').hasClass('date')) && ($('.mon-div3').hasClass('date')))
    {
	    $('.mon-div0').addClass('date');
	    $('.mon-div1').addClass('date');
	    $('.date').slideToggle("slow");
	    $('.mon-div2').removeClass('date');
	    $('.mon-div3').removeClass('date');
    }
	else if (($('.mon-div0').hasClass('date')) && ($('.mon-div1').hasClass('date')))
	{
		week_submit('-1');
	}
}

function calenderMotionRightMobile(chk)
{
	if($('.mon-div0').hasClass('date'))
	{
		$('.mon-div2').addClass('date');
		$('.mon-div3').addClass('date');
		$('.date').slideToggle("slow");
		$('.mon-div0').removeClass('date');
		$('.mon-div1').removeClass('date');
		
	}
	else if($('.mon-div2').hasClass('date'))
	{
		$('.mon-div4').addClass('date');
		$('.mon-div5').addClass('date');
		$('.date').slideToggle("slow");
		$('.mon-div2').removeClass('date');
		$('.mon-div3').removeClass('date');
		
	}
	else if($('.date').hasClass('mon-div4'))
	{
		$('.mon-div6').addClass('date');
		$('.date').slideToggle("slow");
		$('.mon-div4').removeClass('date');
		$('.mon-div5').removeClass('date');	
	}
	else if($('.mon-div6').hasClass('date'))
	{
		week_submit('1');
	}
}

</script>
		
<!-- Function for add customer mobile div starts -->
<script>
$(document).ready(function(){
	$('.add-customer-name-details-mobile').hide();
});
</script>

<script>
function toggleCustomerAddMobile(chk)
{
	if  ($("#add-customer-name-details-mobile").hasClass('add-customer-name-details-mobile') == true)
	{
		$('#add-customer-name-details-mobile').toggle("slow");
	} 
}
</script>
	
<!-- Function for add customer mobile div ends -->
<script>
function toggleCustomerphone(chk,num)
{
	var Id=($(chk).attr("id"));
	if (($("#" + Id).hasClass('toggle-inactive') == true) && ($("#" + Id).hasClass('toggle-active') == true))
	{
		$('#toggle-customer-details-phone'+num).toggle("slow");
		$('.toggle-active .img-arr').attr("src","/media/images/tt-new/timesheet-arrow-left.png");
		$("#" + Id).removeClass('toggle-active');
	}
	else if  ($("#" + Id).hasClass('toggle-inactive') == true)
	{
		$(".toggle-active").removeClass('toggle-active');
		$("#" + Id).removeClass('toggle-inactive');
		$('.toggle-inactive .img-arr').attr("src","/media/images/tt-new/timesheet-arrow-left.png");
		$("#" + Id).addClass('toggle-active');
		$("#" + Id).addClass('toggle-inactive');
		$('.toggle-customer-details').hide();
		$('#toggle-customer-details-phone'+num).toggle("slow");
		$("#"+Id+' .img-arr').attr("src","/media/images/tt-new/timesheet-arrow-down.png");
	}
}
</script>
	
<script>
$(document).ready(function(){
	$('.toggle-customer-details').hide();
});
</script>
	
<script>
function toggleCustomer(chk,num)
{
	var Id=($(chk).attr("id"));
	if (($("#" + Id).hasClass('toggle-inactive') == true) && ($("#" + Id).hasClass('toggle-active') == true))
	{
		
		$('#toggle-customer-details'+num).toggle("slow");
	
		/*$('.toggle-active .img-arr1').attr("src","/media/images/tt-new/retina-timesheet-arrow.png");
		$('.img-arr1').css('width','16px');
		$('.img-arr1').css('height','16px');*/
		$("#" + Id).removeClass('toggle-active');
	}
	else if  ($("#" + Id).hasClass('toggle-inactive') == true)
	{
		$(".toggle-active").removeClass('toggle-active');
		$("#" + Id).removeClass('toggle-inactive');

		$("#" + Id).addClass('toggle-active');
		$("#" + Id).addClass('toggle-inactive');
		
		$('.toggle-customer-details').hide();
		
		$('#toggle-customer-details'+num).toggle("slow");

		/*$('#'+Id+' .img-arr1').attr("src","/media/images/tt-new/retina-timesheet-arrow1.png");
		$('.img-arr1').css('width','16px');
		$('.img-arr1').css('height','16px');*/
	}
}
</script> 

	 
<script type="text/javascript">
  
$(function() {
	$("#timesheet_date").datepicker({
		showOtherMonths: true,
		showOn: 'button',
		//buttonImage: '../media/images/calendar-icon.png',
		buttonImageOnly: true,
		onSelect: function(date) {
	        submit_form(date);
	    }
	});
	
});
function submit_form(date) { 
	form	=	document.getElementById('week_list_menu');
	field = document.createElement('input');
	field.setAttribute('name', 'week_list');
	field.setAttribute('type', 'hidden');
	field.setAttribute('value', "1");
	field2 = document.createElement('input');
	field2.setAttribute('name', 'timesheet_date');
	field2.setAttribute('type', 'hidden');
	field2.setAttribute('value', date);
	form.appendChild(field);
	form.appendChild(field2);
	$("#week_list_menu").submit();
}
/*table sorter*/


$(document).ready(function() { 
	$('.overlay, .popup a').click(function(){
        $('.popup, .overlay').hide();
    });
	
	$('.popup-list li').click(function(){
        $('.popup-list li').removeClass('selected');
		$(this).addClass('selected');
    });
	
	$('.ui-datepicker-trigger').click(function() {
		$('#ui-datepicker-div').css({'z-index':'200'});
		
	});

	$('#ui-datepicker-div').wrap('<div class="cal-popup"></div>');
	$('.ui-datepicker-trigger').click(function() {
		$('.cal-popup').toggle();
	});

	$('#wrapper').click(function(){
		$('.cal-popup').hide();
	});
	
	$('#date_pick_timesheet').click(function() {
		$('.ui-datepicker').css({'position':'absolute'});
		$('#timesheet_date').css({'right': '-27.7% !important'});
		$('#timesheet_date').css({'top': '37px !important'});
		$('#timesheet_date').toggle();
	});

});

function focus_save(id) {//alert(id);
	$('#'+id).css("backgroundPosition","-288px -234px");
}
function blur_save(id) {
	$('#'+id).css("backgroundPosition","-288px -202px");
}
</script>

<script type="text/javascript">		
$(document).ready(function(){
	if($(window).width() <= 400) 
	{
		$('.normal-screen-version').hide();
		$('.mobile-screen-version').show();
		$('#timesheet-week-hrs-btn').css("float","left");
		$('#timesheet-btn').hide();
		$('.add-timesheet-mobile-version').show();
		$('.wed-div').hide();
		$('.thu-div').hide();
		$('.fri-div').hide();
		$('.sat-div').hide();
		$('.sun-div').hide();
		$('.timesheet-show-synced-btn').css("margin-left","2%");
	
	}
	else if(($(window).width() > 400) && ($(window).width() <= 700)) 
	{
		$('.normal-screen-version').hide();
		$('.mobile-screen-version').show();
		$('#timesheet-week-hrs-btn').css("float","left");
		$('#timesheet-btn').hide();
		$('.add-timesheet-mobile-version').show();
		$('.wed-div').hide();
		$('.thu-div').hide();
		$('.fri-div').hide();
		$('.sat-div').hide();
		$('.sun-div').hide();
		$('.timesheet-show-synced-btn').css("margin-left","2%");
	
	}
	else
	{
		$('.normal-screen-version').show();
		$('.mobile-screen-version').hide();
		$('#timesheet-week-hrs-btn').css("float","right");
		$('#timesheet-btn').show();
		$('.add-timesheet-mobile-version').hide();
		$('.timesheet-show-synced-btn').css("margin-left","0");
	
	}

	$(window).resize(function()
	{
		if($(window).width() <= 400)
		{
			$('.normal-screen-version').hide();
			$('.mobile-screen-version').show();
			$('#timesheet-week-hrs-btn').css("float","left");
			$('#timesheet-btn').hide();
			$('.add-timesheet-mobile-version').show();
			$('.wed-div').hide();
			$('.thu-div').hide();
			$('.fri-div').hide();
			$('.sat-div').hide();
			$('.sun-div').hide();
			$('.timesheet-show-synced-btn').css("margin-left","2%");
		
		}
		else if(($(window).width() > 400) && ($(window).width() <= 700)) 
		{
			$('.normal-screen-version').hide();
			$('.mobile-screen-version').show();
			$('#timesheet-week-hrs-btn').css("float","left");
			$('#timesheet-btn').hide();
			$('.add-timesheet-mobile-version').show();
			$('.wed-div').hide();
			$('.thu-div').hide();
			$('.fri-div').hide();
			$('.sat-div').hide();
			$('.sun-div').hide();
			$('.timesheet-show-synced-btn').css("margin-left","2%");
		}
		else
		{
			$('.normal-screen-version').show();
			$('.mobile-screen-version').hide();
			$('#timesheet-week-hrs-btn').css("float","right");
			$('#timesheet-btn').show();
			$('.add-timesheet-mobile-version').hide();
			$('.timesheet-show-synced-btn').css("margin-left","0");
		}
	});
});
</script>

<!-- New Contents Starts -->

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
				<a href="<?php echo SITEURL?>/activitysheet" id="" class="nav-link"><div>Activity Slips</div></a>
			</li>
			<li class="nav-li mob-nav-sheets">
				<a href="<?php echo SITEURL?>/timesheet" id="" class="nav-link-selected" ><div>Timesheets</div></a>
			</li>
			<li class="right search-bar">
    			<form class="jqtransform" id="activity_slip_search" name="activity_slip_search" action="<?php echo SITEURL?>/activitysheet" method="post">
        			<?php 
        		   	$seach_value	=	isset($_POST['act_search'])?$_POST['act_search']:"";
        		   	?>
        			<input class="" id="userappendedInputButton" placeholder="Search..." size="16" type="text" name="act_search" style="height:16px !important;" value="<?php echo $seach_value;?>">
        			<a href="javascript:void(0);" onclick="clear_search_results('<?php echo SITEURL?>','5')" class="clear_search">X</a>
        		</form>
        	</li>
		</ul>
	</div>
</div>

<div class="row-fluid" id="account-section-container">
	<div class="span1"></div>
		<!-- Middle Contents Starts -->
	<div class="span10" id="content-middle">
			<div class="calender-timesheet-buttons" >
                
                
		<!-- Employee list popup -->
			<div class="add-timesheet-mobile-version" onclick="toggleCustomerAddMobile(this)">
    			<img src="media/images/tt-new/activity-plus.png" class="image-plus-mobile">
    		</div>
    		<div style="float:right">    
                <a href="javascript:void(0);" >
                    <div id="timesheet-btn" class="calender-timesheet" onclick="toggleCustomerAdd(this,0,'<?php echo $_SESSION['admin_user']?>');get_show_jobs('async','<?php if(isset($_SESSION['selected_emp_id'])) {echo $_SESSION['selected_emp_id'];} else if(isset($_SESSION['admin_user']) && isset($employee_list) && $_SESSION['admin_user']== 0) { echo $employee_list[0]['record_id']; } else echo '000'; ?>','null');">
                        <span class="sync-all-list">Timesheet</span>
                    </div>
                </a>
            </div>  
        
            <div style="float:left">
                <form method="post" action="" id="week_list_menu" class="left">
                    <div class="calender-btn" id="date_pick_timesheet">
                    </div>
                </form>
                <div class="timesheet_date_calender">
                    <form id="chooseDateForm"  name="chooseDateForm">
                        <input type="text" id="datepicker" class="date-label date-cust" readonly="readonly" value="<?php echo $months['start']."&nbsp;".$dates[1]." - ".$months['end']."&nbsp;".$dates[7].",&nbsp;".$year;?>" />
                    </form>
                </div>         
            </div>
        
        	<?php if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){?>
	 			<div class="search-contents filter_employees grey_background" >
					<div class="row search-contents-row">
        				<div id="" class="activity-form-validator">
        					<div class="span3 emp_label" id="" >Employee</div> 
        					<input type="text" readonly class="emp_field grey_background" name="" value="<?php if(isset($_SESSION['selected_emp_id'])){echo $_SESSION['selected_employee_name'].' '.$_SESSION['selected_employee_lastname'];}?>"> 
        					<div class="cancel_selection">
        					<a href="javascript:void(0);" class="employee_arrow" onclick='clear_user(1)' name="" id="">
        					X
        					</a>
        					</div>
        					
        				</div>
        				<div class="timesheetValidation" style="display:none;" id="">
        					<div class="error-desc" style="position:relative;top:-26px;">
        						<img src="/media/images/tt-new/reset_error.png" />Please select employee first.
        					</div>
        				</div>
					</div>
				</div>
				<?php }?>
		<!-- Employee list popup -->
				<?php if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']== 1) {?>
        		<div class="employee-popup" style="display:none !important;">
        			<div class="layout-names">
        				<label class="layout-label-names dull">Employees</label>
        				<input type="text" id="employee_names_popup" class="search-names-popup" placeholder="search"/>
        			</div>
        			<div class="popup-items2">
        				<?php if(isset($employee_list)){
        						foreach($employee_list as $e) {
        				?>
        					<div class="names-popup-list first names-list-row" onclick="get_show_jobs('sync','<?php echo $e['record_id']?>','<?php echo $e['name']?>');selected_employee('<?php echo $e['name']?>','<?php echo $e['record_id']?>','1');" id="names-list-row-<?php echo $e['record_id']?>" >
        						<label class="timesheet-popup-list-label heavy"><?php echo $e['name']?></label>
        					</div>
        				<?php }
        				} ?>
        			</div>
        		</div>
        		<?php }?>
        	<input type="hidden" class="is_admin" value="<?php if(isset($_SESSION['admin_user'])) echo $_SESSION['admin_user']; else echo 1;?>">
  
		</div>
		
		<div class="normal-screen-version">
		<form method="post" action="" id='week_timesheet_form'>
			<div class="name-client-customer" >
    			<div class="day7">
    				<div class="day6">
    					<div class="day5">
    						<div class="day4">
    							<div class="day3">
    								<div class="day2">
    									<div class="day1">
    											
    									<a href="javascript:void(0);" id="calender-left-arrow-normal" onclick="week_submit('-1')"><img src="media/images/tt-new/left-arr.png" class="arrow_hd"></a>
    										<div class="day-1-field-customer"><label class="client-name-field-customer">Customer</label></div>
    										<div class="day-2-field"><label class="client-days-field"><?php echo $dates[1]; ?></label><label class="client-days-field1">Mon</label></div>
    										<div class="day-3-field"><label class="client-days-field"><?php echo $dates[2]; ?></label><label class="client-days-field1">Tue</label></div>
    										<div class="day-4-field"><label class="client-days-field"><?php echo $dates[3]; ?></label><label class="client-days-field1">Wed</label></div>
    										<div class="day-5-field"><label class="client-days-field"><?php echo $dates[4]; ?></label><label class="client-days-field1">Thu</label></div>
    										<div class="day-6-field"><label class="client-days-field"><?php echo $dates[5]; ?></label><label class="client-days-field1">Fri</label></div>
    										<div class="day-7-field"><label class="client-days-field"><?php echo $dates[6]; ?></label><label class="client-days-field1">Sat</label></div>
    										<div class="day-8-field"><label class="client-days-field"><?php echo $dates[7]; ?></label><label class="client-days-field1">Sun</label>
    										<a href="javascript:void(0);" id="calender-right-arrow-normal" onclick="week_submit('1')"><img src="media/images/tt-new/right-arrow.png" class="arrow_hd1"></a>
    										</div>
    										
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
				
			</div>
			
			<input type="hidden" name="change_week" value="1" />
							<input type="hidden" name="next" value="<?php echo $next_start_week.":".$next_end_week; ?>" />
							<input type="hidden" name="prev" value="<?php echo $prev_start_week.":".$prev_end_week; ?>" />
		</form>
			<form method="post" action="/timesheet/sync" id='form_sync_activity' >
				<input type="hidden" name="week_start_date_sync" value="<?php echo $week_start_date?>" />
			</form>
			<!-- Add Timesheet Portion with its toggle starts -->
			<form action='../timesheet/create' method="post" id="create_time_0" >	
			
			<div class="customer-name-details">
				<div class="name-client add-customer-toggle" id="n-c-c">
    				<div class="day7">
    					<div class="day6">
    						<div class="day5">
    							<div class="day4">
    								<div class="day3">
    									<div class="day2">
    										<div class="day1-client">
    											<div class="day-1-field day-1-field-customer toggle-inactive" >
    												<input class="client-name-field-add start-typing" readonly autocomplete='off' size="12" class='customer_name' id='customer_name_0' name='customer_name_0'
								onkeyup = 'openPopup(this, event, "customer" , <?php echo json_encode($data_names['customers_name']); ?>,"_0", "down")'
								onfocus = 'display_down_arrow("down_customer_0","customer")'				
								onblur='check_field_selection("customer", "<?php echo SITEURL; ?>","_0")' placeholder="Customer Name" />
								
								<input type='hidden' id='customer_0' name ='customer1_0'  />
								<input type='hidden' id='customer_val_0' name ='customer_0'  />
								<a href="javascript:void(0);" id='down_customer_0' class="view_more right" tabIndex="-1" 	
								onclick = 'openPopup(this, event, "customer" , <?php echo json_encode($data_names['customers_name']); ?>,"_0")'
								><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
					
								</div>
								
    											<div class="day-2-field">
    											<input type="text" class="hourly-details-field number" id='unit_1_0' name='unit_1_0' onkeyup="setTotalUnits('_0')"  autocomplete='off' />
    											<input type='hidden' id='date_1_0' name='date_1_0' value='<?php echo $days[1]; ?>'  size="4"  />
												<input type='hidden'  name ='slip_action_1_0' value='insert' />
    											</div>
    											<div class="day-3-field">
    											<input type="text" class="hourly-details-field number" id='unit_2_0' name='unit_2_0' onkeyup="setTotalUnits('_0')"  autocomplete='off' />
    											<input type='hidden' id='date_2_0' name='date_2_0' value='<?php echo $days[2]; ?>'  size="4"  />
												<input type='hidden'  name ='slip_action_2_0' value='insert' />
    											</div>
    											<div class="day-4-field"  >
    											<input type="text" class="hourly-details-field number" id='unit_3_0' name='unit_3_0' onkeyup="setTotalUnits('_0')"  autocomplete='off' />
    											<input type='hidden' id='date_3_0' name='date_3_0' value='<?php echo $days[3]; ?>'  size="4"  />
												<input type='hidden'  name ='slip_action_3_0' value='insert' />
    											</div>
    											<div class="day-5-field" >
    											<input type="text" class="hourly-details-field number" id='unit_4_0' name='unit_4_0' onkeyup="setTotalUnits('_0')"  autocomplete='off' />
    											<input type='hidden' id='date_4_0' name='date_4_0' value='<?php echo $days[4]; ?>'  size="4"  />
												<input type='hidden'  name ='slip_action_4_0' value='insert' />
    											</div>
    											<div class="day-6-field" >
    											<input type="text" class="hourly-details-field number" id='unit_5_0' name='unit_5_0' onkeyup="setTotalUnits('_0')"  autocomplete='off' />
    											<input type='hidden' id='date_5_0' name='date_5_0' value='<?php echo $days[5]; ?>'  size="4"  />
												<input type='hidden'  name ='slip_action_5_0' value='insert' />
    											</div>
    											<div class="day-7-field" >
    											<input type="text" class="hourly-details-field number" id='unit_6_0' name='unit_6_0' onkeyup="setTotalUnits('_0')"  autocomplete='off' />
    											<input type='hidden' id='date_6_0' name='date_6_0' value='<?php echo $days[6]; ?>'  size="4"  />
												<input type='hidden'  name ='slip_action_6_0' value='insert' />
    											</div>
    											<div class="day-8-field" >
    											<input type="text" class="hourly-details-field number" id='unit_7_0' name='unit_7_0' onkeyup="setTotalUnits('_0')"  autocomplete='off' />
    											<input type='hidden' id='date_7_0' name='date_7_0' value='<?php echo $days[7]; ?>'  size="4"  />
												<input type='hidden'  name ='slip_action_7_0' value='insert' />
    											</div>
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
    				</div>
    				
    				<div class="entire-toggle no-border-left" id="toggle-customer-details1">
    					<div class="cont5">
    						<div class="cont4">
    							<div class="cont3">
    								<div class="cont2">
    									
    										
    										<input type="hidden" name="week_start_date_0" value="<?php echo $week_start_date?>" />
    										<div class="hourly-column add-fields">
    										<input type='hidden' size="12"  id='slip_number_0' class='text_border'  name='slip_number_0'  autocomplete='off' />
    										<input type="text" class="hourly-text-fields-column start-typing" placeholder="Add Activity" autocomplete='off' size="12" class='activity_name' readonly id='activity_name_0' name='activity_name_0'
            								onkeyup = 'openPopup(this, event, "activity" , <?php echo json_encode($data_names['activites_name']); ?>,"_0","down" )'
            								onfocus = 'display_down_arrow("down_activity_0", "activity")'
            								onblur	= 'check_field_selection("activity", "<?php echo SITEURL; ?>","_0")' />
            								<input type='hidden' id='activity_0' name ='activity_0' autocomplete='off' />
            								<input type='hidden' id='is_non_hourly_0' name ='is_non_hourly_0'  value="" autocomplete='off' />
            								<input type='hidden' id='rate_0' name ='rate_0' value='' autocomplete='off' />
            								<a href="javascript:void(0);" class="view_more right" tabIndex="-1" 	 id='down_activity_0'
            								onclick = 'openPopup(this, event, "activity" , <?php echo json_encode($data_names['activites_name']); ?>,"_0")'
            								><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
            								</div>
    										<?php
									        if($payroll_flag) { // payroll activity is on
					                        ?>
    										<div class="overtime-column add-fields">
    										<input type="text" class="hourly-text-fields-column" placeholder="Add Payroll" autocomplete='off' class='payroll_name' id='payroll_name_0' name='payroll_name_0'  value='<?php // echo $latest_payroll;?>'
            								onkeyup = 'openPopup(this, event, "payroll" , [<?php echo $payroll_name; ?>],"_0","down")'
            								onfocus = 'display_down_arrow("down_payroll_0", "payroll")'
            								onblur='check_field_selection("payroll", "<?php echo SITEURL; ?>","_0")' />
            								<input type='hidden' id='payroll_0' value='<?php echo $payroll_latest_id;?>' name ='payroll_0' autocomplete='off' />
								
            								<a href="javascript:void(0);" class="view_more right" tabIndex="-1" 	id="down_payroll_0" 
            								onclick = 'openPopup(this, event, "payroll" , [<?php echo $payroll_name; ?>],"_0")'
            								><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
    										</div>
    										<?php 
									        } else {
									    	?>
									    	<div class="overtime-column add-fields">
									    	<input type="text" class="hourly-text-fields-column" placeholder="Payroll" />
											<input type='hidden' value='' name ='payroll_0' autocomplete='off' />
											</div>
											<?php 
									       }
								            ?>
								           
    										<div class="detail-column add-fields">
    										<input type="text" class="hourly-text-fields-column start-typing" readonly placeholder="Add Job" autocomplete='off' class='job_name' id='job_name_0'  name='job_name_0' 
            								onkeyup = 'openPopup(this, event, "job" , <?php echo json_encode($data_names['jobs_name']); ?>,"_0","down" )'
            								onfocus = 'display_down_arrow("down_job_0", "job")'
            								onblur='check_field_selection("job", "<?php echo SITEURL; ?>","_0")' />
            								<input type='hidden' id='job_0' name ='job_0'  />
                							<input type='hidden' value='<?php echo $payroll_flag?>' id='payroll_setting_0' name='payroll_setting_0'/>
                							<a href="javascript:void(0);" class="view_more right" tabIndex="-1"  id = "down_job_0"	
                							onclick = 'openPopup(this, event, "job" , <?php echo json_encode($data_names['jobs_name']); ?>,"_0")'
                							><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
            								</div>
    										
    										<div class="emp-column add-fields">
    										<input type="text" class="hourly-text-fields-column employee_name start-typing" name="" value="<?php if(isset($_SESSION['admin_user']) && isset($employee_list) && $_SESSION['admin_user']== 0) { echo $employee_list[0]['name']; }?>" id="employee_add" readonly />
    										</div>
    										<input type="hidden" name="emp_name" value="" class="employee_name" value="" />
        									<input type="hidden" name="emp_id" value="" class="employee_record_id" value="" />
    										
    										<div class="total-column">
    											<div class="total_values">
    												<span class="total-span">Total</span>
        											<span id="total_0" class="hourly-text-fields-column"></span>
        										</div>
        									</div>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
    					<div class="clear"></div>
    					<div class="note_field">
    					<input type='hidden' id='start_time_0' name ='start_time_0' value='<?php echo date('H:i:s');?>'   autocomplete='off'/>
    					<input type="text" class="notes_field" name='notes_0'  id='notes_0' onfocus="expand_notes_field('notes_0')" onblur="contract_notes_field('notes_0')" placeholder="Add a note . . ." />
    					<input type="hidden" class="notes_full_0" value="" name="notes_0s" />
    					<input type='button' value='Save' class="save-btn-new right mar-right-8" name='record_0' id='record_0' onclick='timesheet_validation("_0","0")' onfocus="focus_save('record_0')" onblur="blur_save('record_0');"/>
    					</div>
					</div>
				</div>
				
			<!-- Add Timesheet Portion with its toggle ends -->
			
		 
		 	<!-- Display Timesheet Portion starts -->       
   			<div class="customer-name-details">
   			
			<?php
					$m	= 1;
					if(!empty($timesheet_list)) {
						
						$week_total_perdays	=	array();
						$row_change	=	1;
						$row_index	=	1;
					    foreach($timesheet_list as $timesheet) 
						{
							
							if($row_change % 2==0) $row_class = "evenrow";
							else $row_class = "oddrow";
							$row_class = "";
							$row_change++;
							$readonly_day			=	false;//testing
							if(isset($timesheet['multiple_entry']))
							{
								$hourly_activity	=	array();
								$hourly_activity[0]	=	$timesheet;
							}
							else
							{
								$hourly_activity		=	$Model_timesheet->get_timesheet_per_day($timesheet); // get all the records of the same format
							}
							//var_dump($hourly_activity);die;
							$timesheet['sync_flag']	=	0; // testing 
							$count_hourly_activity	=	count($hourly_activity);
							if($timesheet['sync_flag']) {
								$readonly	=	"readonly = 'true'";
							} else {
								$readonly	=	false;
							}
					//echo "<pre>";print_r($timesheet);echo "</pre>";die;
					
					?>
				<div class="name-client">
            		<div class="day7">
            			<div class="day6">
            				<div class="day5">
            					<div class="day4">
            						<div class="day3">
            							<div class="day2">
            								<div class="day1-client">
            								
            									<div class="day-1-field day-1-field-customer toggle-inactive" >
            									<a href="javascript:void(0);" class="arrow-tag toggle-inactive left" id="hourly-details-span<?php echo $m+1;?>" onclick="toggleCustomer(this,'<?php echo $m+1;?>')" >
        										<img class="img-arr1" src="/media/images/tt-new/timesheet-arrow-left.png"></a>
					                            <?php	
								        	    $customer_name = strlen($timesheet['CustomerCompanyOrLastName'])>17?substr(html_entity_decode($timesheet['CustomerCompanyOrLastName']),0,17)."...":html_entity_decode($timesheet['CustomerCompanyOrLastName']);
									            ?>
                									<input type="hidden" class="customer_full_data_<?php echo $m;?>" value="<?php echo html_entity_decode($timesheet['CustomerCompanyOrLastName']); ?>" autocomplete='off' />
    												<input type="hidden" class="customer_part_data_<?php echo $m;?>" value="<?php echo $customer_name; ?>" autocomplete='off' />
    										
                									<input type="text" class="client-name-field left" id='customer_name_<?php echo $m;?>' name='customer_name_<?php echo $row_index?>' value='<?php echo $customer_name;?>'	
    													 onkeyup = 'openPopup(this, event, "customer" , <?php echo json_encode($data_names['customers_name']); ?>,"_<?php echo $m;?>", "down"  )'							
    													 onfocus = 'display_down_arrow("down_customer_<?php echo $m;?>","customer")'
    													 onblur='check_field_selection("customer", "<?php echo SITEURL; ?>", "_<?php echo $m;?>")'  <?php echo $readonly;?>  autocomplete='off' />
												 	<input type='hidden' autocomplete='off' id='customer_val_<?php echo $m;?>' name ='customer_<?php echo $row_index?>' value='<?php echo $timesheet['CustomerRecordID'];?>' />
            										<input type='hidden' autocomplete='off' id='customer_<?php echo $m;?>' name ='customer1_<?php echo $row_index?>' value='<?php echo $timesheet['CustomerCompanyOrLastName'];?>' />
            										<a  href="javascript:void(0);" id='down_customer_<?php echo $m;?>' class="view_more right" tabIndex="-1" 	
            										onclick = 'openPopup(this, event, "customer" , <?php echo json_encode($data_names['customers_name']); ?>,"_<?php echo $m;?>")'
            										><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
            										
												 </div>
												 <?php 
												 $l = 1;
            									$total_hours_per_task 		=	0;
            									
            									for($i=0;$i<$count_hourly_activity;$i++)  // read records one by one and check if added date is in current week days
            									{
            									    $hourly_activity_syn_total 	=	1;
										            for($j=$l;$j<=7;$j++)
										            {
										                if(!empty($hourly_activity[$i]['SlipDate'])) {
            												$slip_date	=	$hourly_activity[$i]['SlipDate']." 00:00:00";
            												$date_num	=	date("j",strtotime($slip_date));
            												$day_num	=	date("D",strtotime($slip_date));
											            }
											         
											            if(!empty($date_num) && $date_num == $dates[$j]) 
											            {	
    											            if(!empty($hourly_activity[$i]['ActivitySync']['RecordID'])) {
            													echo "<div class='day-".($j+1)."-field'><label class='text_none text-area client-days-field1 mar-top-15 text-right-2'>".number_format(doubleval($hourly_activity[$i]['total_units']),2, '.', '')."</label><input type='hidden' id='unit_".$j."_".$m."' size=4  value='".$hourly_activity[$i]['total_units']."'  /></div>";
            												} elseif($hourly_activity[$i]['total_slips'] > 1) {
            													$hourly_activity_syn_total = 0;
            													echo "<div class='day-".($j+1)."-field'><label class='text_none text-area client-days-field1 mar-top-15 text-right-2'>".number_format(doubleval($hourly_activity[$i]['total_units']),2, '.', '')."</label><input type='hidden' id='unit_".$j."_".$m."' size=4  value='".$hourly_activity[$i]['total_units']."'  /> </div>";
            												} else {
            													$hourly_activity_syn_total = 0;
            													//echo "<input type='text' class='text_none text-area text-right-2' id='unit_".$j."_".$m."' name='unit_".$j."_".$row_index."'  size='4'  value='".number_format(doubleval($hourly_activity[$i]['total_units']),2, '.', '')."' onblur=setTotalUnits('_$m')  autocomplete='off'  />";
            												
            													?><a><div class="number day-<?php echo $j+1;?>-field days-field-0"><input type="text" id="unit_<?php echo $j;?>_<?php echo $m;?>" name='<?php echo "unit_".$j."_".$row_index;?>' value="<?php 
            													echo number_format(doubleval($hourly_activity[$i]['total_units']),2, '.', '');
            													?>" onkeyup="setTotalUnits('_<?php echo $m;?>')"  autocomplete='off' class="hourly-details-field"></div></a>
            															
            													<?php 									
            												}
            												$total_hours_per_task	+=	$hourly_activity[$i]['total_units'];
            												switch($day_num) 
            												{
            													case 'Mon'	:	$week_total_perdays[0][] = $hourly_activity[$i]['total_units'];break;
            													case 'Tue'	:	$week_total_perdays[1][] = $hourly_activity[$i]['total_units'];break;
            													case 'Wed'	:	$week_total_perdays[2][] = $hourly_activity[$i]['total_units'];break;
            													case 'Thu'	:	$week_total_perdays[3][] = $hourly_activity[$i]['total_units'];break;
            													case 'Fri'	:	$week_total_perdays[4][] = $hourly_activity[$i]['total_units'];break;
            													case 'Sat'	:	$week_total_perdays[5][] = $hourly_activity[$i]['total_units'];break;
            													case 'Sun'	:	$week_total_perdays[6][] = $hourly_activity[$i]['total_units'];break;
            												}
            												
            												$slip_each_id = explode(',',$hourly_activity[$i]['RecordID']);
            												// for holding the slip number, for new time sheet auto id will be incremented.
            												echo "<input type='hidden'  name ='slip_number_".$j."_".$row_index."' value='".$hourly_activity[$i]['SlipIDNumber']."'  />";
            												echo "<input type='hidden'  name ='slip_action_".$j."_".$row_index."' value='update' />";
            												foreach($slip_each_id as $slip_id)
            												{
            													if(!empty($slip_id))
            													{	
            														echo "<input type='hidden'  name ='slip_id_".$j."_".$row_index."' value='".$slip_id."'  />";
            														echo "<input type='hidden' id='slip_id_".$m."_".$j."' name ='slip_hourly_check_".$m."_[]' value='".$slip_id."' />";
            													}
            												}
            												$i++;
    												    }
											            else 
            											{
            												echo "<input type='hidden'  name ='slip_action_".$j."_".$row_index."' value='insert' />";
            												
            											/*	echo "<input type='text' class='text_none text-area text-right-2' id='unit_".$j."_".$m."' name='unit_".$j."_".$row_index."'  size='4'  value='' onblur=setTotalUnits('_$m') $readonly />";
            											*/?>
            											
            											<a>
                											<div class="day-<?php echo $j+1?>-field">
                												<input type="text" value="<?php echo "0.00";?>" id="unit_<?php echo $j;?>_<?php echo $m;?>" name='<?php echo "unit_".$j."_".$row_index;?>' onkeyup=setTotalUnits('_<?php echo $m;?>') class="hourly-details-field">
                											</div>
            											</a>
													<?php 
            												}
            											
            											$l++;
            											echo "<input type='hidden' id='date_$j' name='date_".$j."_".$row_index."' value='".$days[$j]."'  size='4'  />";
            											
										            }
                									if($hourly_activity_syn_total == 1)
            										{
            											echo "<input type='hidden' value='1' id='sync_all_activities_".$m."' />";
            											?>
            											<script type="text/javascript">
            												lock_timesheet("<?php echo SITEURL;?>", "<?php echo $m?>");
            											</script>
            											<?php
            										}
            									}
            									?>
            								</div>
            							</div>
            						</div>
            					</div>
            				</div>
            			</div>
            		</div>
            		<?php
										$activity_id	=	empty($timesheet['ActivityID'])?"":html_entity_decode($timesheet['ActivityID']);
										$activity_name	=	empty($timesheet['ActivityID'])?"":html_entity_decode($timesheet['ActivityID']);
										//$is_non_horuly	=	empty($timesheet['Activities']['IsActivityNonHourly'])?'false':$timesheet['Activities']['IsActivityNonHourly'];
										$job_name		=	empty($timesheet['JobNumber'])?"":html_entity_decode($timesheet['JobNumber']);
										$job_number		=	empty($timesheet['JobNumber'])?"":html_entity_decode($timesheet['JobNumber']);
										
										if(isset($timesheet['RecordID']) && $timesheet['Is_non_hourly']) {
											$is_non_hourly = 1;//'true';
										} else {
											$is_non_hourly = 0;//'false';
										}
									?>
    			<div class="entire-toggle toggle-customer-details" id="toggle-customer-details<?php echo $m+1;?>">
            		<div class="cont5">
                    	<div class="cont4">
                    		<div class="cont3">
                    			<div class="cont2">
                    			<div class="hourly-column add-fields">
                    					<?php
											//echo "activity ".$activity_name;
											
											$activity_display_name = strlen($activity_name)>15?substr(html_entity_decode($activity_name),0,15)."...":html_entity_decode($activity_name);

										?>
										<input type="hidden" class="activity_full_data_<?php echo $m;?>" value="<?php echo $activity_name; ?>" autocomplete='off' />
										<input type="hidden" class="activity_part_data_<?php echo $m;?>" value="<?php echo $activity_display_name; ?>" autocomplete='off' />
										
										<input type='hidden' class="text-area" size="12" class='text_none' id='slip_number_<?php echo $m;?>' name='slip_number_<?php echo $row_index?>' value='<?php echo $timesheet['SlipIDNumber'];?>'  <?php echo $readonly;?> autocomplete='off' />
										
                    					<input type="text" class="hourly-text-fields-column start-typing" readonly id='activity_name_<?php echo $m;?>'
										       name='activity_name_<?php echo $row_index?>' value='<?php echo $activity_display_name;?>'  
										       onkeyup = 'openPopup(this, event, "activity" , <?php echo json_encode($data_names['activites_name']); ?>,"_<?php echo $m;?>", "down"  )'
										       onfocus = 'display_down_arrow("down_activity_<?php echo $m;?>","activity")'
										       onblur='check_field_selection("activity", "<?php echo SITEURL; ?>", "_<?php echo $m;?>")'  <?php echo $readonly;?>  autocomplete='off' />
                    					
                    					
										<input type='hidden' id='activity_<?php echo $m;?>' name ='activity_<?php echo $row_index?>' value='<?php echo $activity_id;?>' autocomplete='off' />
										<input type='hidden' id='is_non_hourly_<?php echo $m;?>' name ='is_non_hourly_<?php echo $row_index?>'  value="<?php echo $is_non_hourly;?>" autocomplete='off' />
										<input type='hidden' id='rate_<?php echo $m;?>' name ='rate_<?php echo $row_index?>' value='<?php echo $timesheet['Rate'];?>' autocomplete='off' />
										
										<a href="javascript:void(0);" id='down_activity_<?php echo $m;?>' class="view_more right" tabIndex="-1" 	
										onclick = 'openPopup(this, event, "activity" , <?php echo json_encode($data_names['activites_name']); ?>,"_<?php echo $m;?>")'
										><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
                    					</div>
                    				<?php
										if($payroll_flag) { // payroll activity is on
										$payroll_category	=	empty($timesheet['PayrollCategory'])?"":$timesheet['PayrollCategory'];								
								    	?>
                    					<div class="overtime-column add-fields">
                    					<?php
											
												$payroll_display_name = strlen($payroll_category)>15?substr($payroll_category,0,15)."...":$payroll_category;
												if($payroll_display_name == "Null") {
													$payroll_display_name = "";
													$payroll_category	=	"";
												}
											?>
											<input type="hidden" class="payroll_full_data_<?php echo $m;?>" value="<?php echo $payroll_category; ?>" />
											<input type="hidden" class="payroll_part_data_<?php echo $m;?>" value="<?php echo $payroll_display_name; ?>" />
											
                    					<input type="text" placeholder="payroll" class="hourly-text-fields-column" id='payroll_name_<?php echo $m;?>' name='payroll_name_<?php echo $row_index?>' 
											onkeyup = 'openPopup(this, event, "payroll" , [<?php echo $payroll_name; ?>],"_<?php echo $m;?>", "down" )'
											onfocus = 'display_down_arrow("down_payroll_<?php echo $m;?>","payroll")'
											value='<?php echo $payroll_display_name;?>' 
											<?php
												if(!empty($payroll_latest) OR !empty($slip['payroll_category_name'])){
													echo 'readonly = true';
												}
											?>	
											 onblur='check_field_selection("payroll", "<?php echo SITEURL; ?>", "_<?php echo $m;?>")' <?php echo $readonly;?>  autocomplete='off' />
                    					<input type='hidden' id='payroll_<?php echo $m;?>' value='<?php echo $payroll_latest_id;?>' name ='payroll_<?php echo $row_index?>'  />
											<a href="javascript:void(0);" id='down_payroll_<?php echo $m;?>' class="view_more right" tabIndex="-1" 	
											onclick = 'openPopup(this, event, "payroll" , [<?php echo $payroll_name; ?>],"_<?php echo $m;?>")'
											><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>	
                    					</div>
                    					<?php } 
										else {
										?>
										<div class="overtime-column add-fields">
    										<input type="text" class="hourly-text-fields-column" value="Not Applicable" readonly style="cursor:help;" autocomplete='off' class='payroll_name' id='payroll_name_0' name='payroll_name_0'  value='<?php // echo $latest_payroll;?>'
            								 />
            							
    										</div>
										<input type='hidden' value='' name ='payroll_<?php echo $row_index?>' autocomplete='off' />
									
										<?php 
										}
								    	?>
								    	<div class="detail-column add-fields">
                    					<?php
										    $job_display_name = strlen($job_name)>15?substr(html_entity_decode($job_name),0,15)."...":html_entity_decode($job_name);
										?>
										<input type="hidden" class="jobname_full_data_<?php echo $m;?>" value="<?php echo $job_name; ?>"  autocomplete='off' />
										<input type="hidden" class="jobname_part_data_<?php echo $m;?>" value="<?php echo $job_display_name; ?>"  autocomplete='off' />
										
                    					<input type="text" class="hourly-text-fields-column start-typing" readonly placeholder="Add Job" id='job_name_<?php echo $m;?>'  name='job_name_<?php echo $row_index?>' value='<?php echo $job_display_name;?>'
										onkeyup = 'openPopup(this, event, "job" , <?php echo json_encode($data_names['jobs_name']); ?>,"_<?php echo $m;?>" , "down" )'
										onfocus = 'display_down_arrow("down_job_<?php echo $m;?>","job")'
										onblur  = 'check_field_selection("job", "<?php echo SITEURL; ?>", "_<?php echo $m;?>")'  <?php echo $readonly;?>  autocomplete='off' />
                    					<input type='hidden' id='job_<?php echo $m;?>' name ='job_<?php echo $row_index?>' value='<?php echo $job_number;?>' />
										
										<input type='hidden' value='<?php echo $payroll_flag?>' id='payroll_setting_<?php echo $m;?>' name='payroll_setting_<?php echo $row_index?>'/>
										<input type='hidden' value='update' name='timesheet_flag_<?php echo $row_index?>'/>
										
										<a href="javascript:void(0);" id='down_job_<?php echo $m;?>' class="view_more right" tabIndex="-1" 	
										onclick = 'openPopup(this, event, "job" , <?php echo json_encode($data_names['jobs_name']); ?>,"_<?php echo $m;?>")'
										><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>	
                    					</div>
                    					        
									    <?php
										$activity_id	=	empty($timesheet['ActivityID'])?"":html_entity_decode($timesheet['ActivityID']);
										$activity_name	=	empty($timesheet['ActivityID'])?"":html_entity_decode($timesheet['ActivityID']);
										//$is_non_horuly	=	empty($timesheet['Activities']['IsActivityNonHourly'])?'false':$timesheet['Activities']['IsActivityNonHourly'];
										$job_name		=	empty($timesheet['JobNumber'])?"":html_entity_decode($timesheet['JobNumber']);
										$job_number		=	empty($timesheet['JobNumber'])?"":html_entity_decode($timesheet['JobNumber']);
										
										if(isset($timesheet['RecordID']) && $timesheet['Is_non_hourly']) {
											$is_non_hourly = 1;//'true';
										} else {
											$is_non_hourly = 0;//'false';
										}
							    		?>
                    					
                    				    <div class="emp-column add-fields">
    									<input type="text" class="hourly-text-fields-column start-typing" value="<?php if(isset($timesheet['EmployeeFirstName'])) {echo $timesheet['EmployeeFirstName'].' '.$timesheet['EmployeeCompanyOrLastName'];} else {echo $timesheet['EmployeeCompanyOrLastName'];}?>" name='' id="" readonly />
    									</div>
                    					<input type="hidden" name="week_start_date_<?php echo $row_index?>" value="<?php echo $week_start_date?>" />
                    					
                    					<div class="total-column">
                    					<div class="total_values">
                    					<span class="total-span">Total</span>
                        					
                        					<span id="total_<?php echo $m;?>" ><?php echo number_format(doubleval($total_hours_per_task),2)?> hrs</span>
                        					</div>
                        					
                        				</div>	
                    			</div>
                    		</div>
                    	</div>
            	</div>
				<div class="clear"></div>
    			<div class="note-column-view">
        			<input type='hidden' id='start_time' name ='start_time_<?php echo $row_index?>' value='<?php echo date('H:i:s');?>'  />
        			<?php 
        
        			$notes_value_line = str_replace("\n", " ", $timesheet['Notes']);
        			$notes_value 	  = strlen($notes_value_line)>13?substr($notes_value_line,0,13)." ...":$notes_value_line;
        			
        		    ?>
        		  
        			<input type="text" class="notes_field_view" placeholder="Add a note..." name='notes_<?php echo $row_index?>' id='notes_<?php echo $m;?>' cols="20" rows="1" <?php echo $readonly;?> <?php if(strlen($timesheet['Notes']) > 15) { ?> onmouseover="show_notes('<?php echo $m; ?>')" onmouseout="hide_notes('<?php echo $m; ?>')"  <?php }?> spellcheck="false"
        			onfocus="enlarge_notes('<?php echo $m;?>','<?php echo $count;?>')" onblur="minimize_notes('<?php echo $m;?>')" value="<?php echo $notes_value?>"/>
                	<input type='button' value='Save' class='save-btn-new right mar-right-8 mar-top-7'  onclick='timesheet_validation("<?php echo "_".$m;?>", "<?php echo $row_index?>")' name='record_<?php echo $row_index;?>' id='record_<?php echo $m;?>'/>
                </div>
			</div>
		</div>
		<?php  $row_index++;
							$m++;
						}
					} else { ?>
					<div class="clear"></div>
					<div class="no-activity"><label class="no-activity-label">No activities found</label></div>
					<?php }
					?>
	</div>

			<!-- Display Timesheet Portion ends -->
		 <input type="hidden" class="linked_job" value="" />
					<div class="name-client-total">
    					<div class="day7">
    						<div class="day6">
    							<div class="day5">
    								<div class="day4">
    									<div class="day3">
    										<div class="day2">
    											<div class="day1">
    													<div class="day-1-field-total"><label class="client-name-field-total">Total</label></div>
    													<div class="day-2-field"><label class="client-total-field"><?php 
								$day_total[0]	=	empty($week_total_perdays[0])?0:array_sum($week_total_perdays[0]);
								echo number_format(doubleval($day_total[0]),2); 
						?> hrs</label></div>
    													<div class="day-3-field"><label class="client-total-field"><?php 
								$day_total[1]	=	empty($week_total_perdays[1])?0:array_sum($week_total_perdays[1]);
								echo number_format(doubleval($day_total[1]),2); 
						?> hrs</label></div>
    													<div class="day-4-field"><label class="client-total-field"><?php 
								$day_total[2]	=	empty($week_total_perdays[2])?0:array_sum($week_total_perdays[2]);
								echo number_format(doubleval($day_total[2]),2); 
						?> hrs</label></div>
    													<div class="day-5-field"><label class="client-total-field"><?php 
								$day_total[3]	=	empty($week_total_perdays[3])?0:array_sum($week_total_perdays[3]);
								echo number_format(doubleval($day_total[3]),2); 
						?> hrs</label></div>
    													<div class="day-6-field"><label class="client-total-field"><?php 
								$day_total[4]	=	empty($week_total_perdays[4])?0:array_sum($week_total_perdays[4]);
								echo number_format(doubleval($day_total[4]),2); 
						?> hrs</label></div>
    													<div class="day-7-field"><label class="client-total-field"><?php 
								$day_total[5]	=	empty($week_total_perdays[5])?0:array_sum($week_total_perdays[5]);
								echo number_format(doubleval($day_total[5]),2); 
						?> hrs</label></div>
    													<div class="day-8-field"><label class="client-total-field"><?php 
								$day_total[6]	=	empty($week_total_perdays[6])?0:array_sum($week_total_perdays[6]);
								echo number_format(doubleval($day_total[6]),2); 
						?> hrs</label></div>
    											</div>
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
					</div>
			</form>
				</div>
				
				<!-- Mobile div starts -->
					
	<!-- Mobile Screen Div Starts -->
	<div class="mobile-screen-version">
        	<div class="customer-hourly-details-mobile-version1" >
        		<div class="row hourly-details-row" id="hourly-details-row">
        			<div class="span3" id="hourly-details-span-mobile-version-1" >Customer
        			<a href="javascript:void(0);" id="calender-left-arrow-mobile" onclick="calenderMotionLeftMobile(this)"><img src="/media/images/tt-new/left-arr.png" class="arrow_hd"></a>
        			</div> 
        			<div id="details-field-mobile-version" > 
        				<div id="hourly-details-field-customer-mobile-version1" class="mon-div0 date"><label class="label-date-mobile mon"><?php echo $dates[1]; ?></label>
        				<span>Mon</span>
        				</div>
        				<div id="hourly-details-field-customer-mobile-version1" class="mon-div1 date"><label class="label-date-mobile tue"><?php echo $dates[2]; ?></label>
        				
        				<span>Tue</span>
        				</div>
        				<div id="hourly-details-field-customer-mobile-version1" class="mon-div2"><label class="label-date-mobile wed"><?php echo $dates[3]; ?></label>
        				<span>Wed</span>
        				</div>
        				<div id="hourly-details-field-customer-mobile-version1" class="mon-div3"><label class="label-date-mobile thu"><?php echo $dates[4]; ?></label>
        				<span>Thu</span>
        				</div>
        				<div id="hourly-details-field-customer-mobile-version1" class="mon-div4"><label class="label-date-mobile fri"><?php echo $dates[5]; ?></label>
        				<span>Fri</span>
        				</div>
        				<div id="hourly-details-field-customer-mobile-version1" class="mon-div5"><label class="label-date-mobile sat"><?php echo $dates[6]; ?></label>
        				<span>Sat</span>
        				</div>
        				
        				<div id="hourly-details-field-customer-mobile-version1" class="mon-div6 date1"><label class="label-date-mobile sun"><?php echo $dates[7]; ?></label>
        				<span>Sun</span>
        						
        				</div>
        					
        				<a href="javascript:void(0);" id="calender-right-arrow-mobile" onclick="calenderMotionRightMobile(this)"><img src="/media/images/tt-new/right-arrow.png" class="arrow_hd1"></a>	
        			</div> 
        			
        		</div>
        	</div>
	
			<form action='../timesheet/create' method="post" id="phone_create_time_0" >	
    		<!-- Adding timesheet details in mobile mode starts -->
    	
        	<div class="customer-name-details add-customer-name-details-mobile" id="add-customer-name-details-mobile">
        		<div class="cust-hourly-details-mobile-version" >
        			<div class="row hourly-details-row" id="hourly-details-row">
        				<div class="hourly-details-span-mobile-version toggle-inactive">
        					<input class="client-name-field-add cust_0 start-typing" readonly autocomplete='off' size="12" id='customer_name_0' name='customer_name_0'
            					onkeyup = 'openPopup(this, event, "customer" , <?php echo json_encode($data_names['customers_name']); ?>,"_0", "down")'
            					onfocus = 'display_down_arrow("down_Mobilecustomer_0","customer")'				
            					onblur='check_field_selection("customer", "<?php echo SITEURL; ?>","_0")' />
            								
            				<input type='hidden' id='customer_0' name ='customer1_0'  />
            				<input type='hidden' id='customer_val_0' name ='customer_0'  />
            				<a href="javascript:void(0);" id='down_Mobilecustomer_0' class="view_more_phone right" tabIndex="-1" 	
            				onclick = 'openPopup(this, event, "customer" , <?php echo json_encode($data_names['customers_name']); ?>,"_0")'
            				><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
        				</div> 
        				
        				<div id="details-field-mobile-version1" class="mon-div0 date" > 
        					<input type="text" class="hourly-details-field-mobile-version" id='unit_1_0' name='unit_1_0' onkeyup="setTotalUnits('_0')"  autocomplete='off' />
        					<input type='hidden' id='date_1_0' name='date_1_0' value='<?php echo $days[1]; ?>'  size="4"  />
    						<input type='hidden'  name ='slip_action_1_0' value='insert' />
        				</div>
        				<div id="details-field-mobile-version1" class="mon-div1 date" > 
        					<input type="text" class="hourly-details-field-mobile-version" id='unit_2_0' name='unit_2_0' onkeyup="setTotalUnits('_0')"  autocomplete='off'  />
        					<input type='hidden' id='date_2_0' name='date_2_0' value='<?php echo $days[2]; ?>'  size="4"  />
    						<input type='hidden'  name ='slip_action_2_0' value='insert' />
    					</div>		
    					<div id="details-field-mobile-version1" class="mon-div2" > 					
        					<input type="text" class="hourly-details-field-mobile-version" id='unit_3_0' name='unit_3_0' onkeyup="setTotalUnits('_0')"  autocomplete='off'  />
        					<input type='hidden' id='date_3_0' name='date_3_0' value='<?php echo $days[3]; ?>'  size="4"  />
    						<input type='hidden'  name ='slip_action_3_0' value='insert' />
        				</div>		
    					<div id="details-field-mobile-version1" class="mon-div3" > 
        					<input type="text" class="hourly-details-field-mobile-version" id='unit_4_0' name='unit_4_0' onkeyup="setTotalUnits('_0')"  autocomplete='off' />
        					<input type='hidden' id='date_4_0' name='date_4_0' value='<?php echo $days[4]; ?>'  size="4"  />
    						<input type='hidden'  name ='slip_action_4_0' value='insert' />
        				</div>		
    					<div id="details-field-mobile-version1" class="mon-div4"> 	
        					<input type="text"  class="hourly-details-field-mobile-version" id='unit_5_0' name='unit_5_0' onkeyup="setTotalUnits('_0')"  autocomplete='off'  />
        					<input type='hidden' id='date_5_0' name='date_5_0' value='<?php echo $days[5]; ?>'  size="4"  />
    						<input type='hidden'  name ='slip_action_5_0' value='insert' />
        				</div>		
    					<div id="details-field-mobile-version1" class="mon-div5"> 	
        					<input type="text"  class="hourly-details-field-mobile-version" id='unit_6_0' name='unit_6_0' onkeyup="setTotalUnits('_0')"  autocomplete='off'  />
        					<input type='hidden' id='date_6_0' name='date_6_0' value='<?php echo $days[6]; ?>'  size="4"  />
    						<input type='hidden'  name ='slip_action_6_0' value='insert' />
        				</div>		
    					<div id="details-field-mobile-version1" class="mon-div6 date1"> 	
        					<input type="text"  class="hourly-details-field-mobile-version" id='unit_7_0' name='unit_7_0' onkeyup="setTotalUnits('_0')"  autocomplete='off'  />
        					<input type='hidden' id='date_7_0' name='date_7_0' value='<?php echo $days[7]; ?>'  size="4"  />
    						<input type='hidden'  name ='slip_action_7_0' value='insert' />
        					
        				</div> 
        			</div>
        		</div>
    <style>
    .mon-div2,.mon-div3,.mon-div4,.mon-div5,.mon-div6{
    display:none;
    }
    @media all and (min-width: 700px) and (max-width: 1090px) {
	.client-name-field{
	text-overflow: ellipsis;
	white-space: nowrap;
	overflow:hidden;
	width:58% !important;
	}
	.client-name-field-add{
	text-overflow: ellipsis;
	white-space: nowrap;
	overflow:hidden;
	width:80% !important;
	}
	.span1{display:none !important;}
	#account-section-container #content-middle{width:100% !important;}
	}
    </style>
   				<div class="toggle-customer-details" id="toggle-customer-details-phone1" >
    				<div class="cust-toggle-details" >
    					<div class="row cust-toggle-details-row" id="cust-toggle-details-row">
    						<div class="span3" id="customer-data-span-phone" >Total
    						</div> 
    						<div class="data-input-fields-phone add-fields" >  
    							<span id="total_0" class="tot-span data-name-input">
    							</span>
    						</div>
    						
    						<input type="hidden" name="week_start_date_0" value="<?php echo $week_start_date?>" />
    							<?php
								 if($payroll_flag) { // payroll activity is on
					            ?>
    						<div class="data-input-fields-overtime-phone add-fields" > 
    						<input type="text" class="data-name-input payroll_0" placeholder="Add Payroll" autocomplete='off' id='payroll_name_0' name='payroll_name_0'  value='<?php // echo $latest_payroll;?>'
            					onkeyup = 'openPopup(this, event, "payroll" , [<?php echo $payroll_name; ?>],"_0","down")'
            					onfocus = 'display_down_arrow("down_Mobilepayroll_0", "payroll")'
            					onblur='check_field_selection("payroll", "<?php echo SITEURL; ?>","_0")' />
            					<input type='hidden' id='payroll_0' value='<?php echo $payroll_latest_id;?>' name ='payroll_0' autocomplete='off' />
								
            					<a href="javascript:void(0);" class="view_more right" style="margin-top:14px !important" tabIndex="-1" 	id="down_Mobilepayroll_0" 
            					onclick = 'openPopup(this, event, "payroll" , [<?php echo $payroll_name; ?>],"_0")'
            					><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
    						</div>
    						    <?php 
								} else {
						    	?>
								<input type='hidden' value='' name ='payroll_0' autocomplete='off' />
								<?php 
								 }
								?>
								            
    						<div class="data-input-fields-detail-phone add-fields" > 
    						<input type="text" class="data-name-input job_0 start-typing" placeholder="Add Job" autocomplete='off' readonly id='job_name_0'  name='job_name_0' 
            					onkeyup = 'openPopup(this, event, "job" , <?php echo json_encode($data_names['jobs_name']); ?>,"_0","down" )'
            					onfocus = 'display_down_arrow("down_Mobilejob_0", "job")'
            					onblur='check_field_selection("job", "<?php echo SITEURL; ?>","_0")' />
            					<input type='hidden' id='job_0' name ='job_0'  />
                				<input type='hidden' value='<?php echo $payroll_flag?>' id='payroll_setting_0' name='payroll_setting_0'/>
                				<a href="javascript:void(0);" class="view_more_phone right" tabIndex="-1"  id = "down_Mobilejob_0"	
                				onclick = 'openPopup(this, event, "job" , <?php echo json_encode($data_names['jobs_name']); ?>,"_0")'
                				><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
    						</div>
    						
    						<div class="data-input-fields-hourly-phone add-fields" > 
    							<input type='hidden' size="12"  id='slip_number_0' class='text_border'  name='slip_number_0'  autocomplete='off' />
    							<input type="text" class="data-name-input act_0" readonly placeholder="Add Activity" autocomplete='off' size="12" id='activity_name_0' name='activity_name_0'
            					onkeyup = 'openPopup(this, event, "activity" , <?php echo json_encode($data_names['activites_name']); ?>,"_0","down" )'
            					onfocus = 'display_down_arrow("down_Mobileactivity_0", "activity")'
            					onblur	= 'check_field_selection("activity", "<?php echo SITEURL; ?>","_0")' />
            					<input type='hidden' id='activity_0' name ='activity_0' autocomplete='off' />
            					<input type='hidden' id='is_non_hourly_0' name ='is_non_hourly_0'  value="" autocomplete='off' />
            					<input type='hidden' id='rate_0' name ='rate_0' value='' autocomplete='off' />
            					<a href="javascript:void(0);" class="view_more_phone right" tabIndex="-1" 	 id='down_Mobileactivity_0'
            					onclick = 'openPopup(this, event, "activity" , <?php echo json_encode($data_names['activites_name']); ?>,"_0")'
            					><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
    						</div>
    						
    						<div id="add-note-field-phone" class="add-fields"  >
    							<input type='hidden' id='start_time_0' name ='start_time_0' value='<?php echo date('H:i:s');?>'   autocomplete='off'/>
    							<input type="text" class="hourly-text-fields-column" name='notes_0'  id='notes_0' onfocus="expand_notes_field('notes_0')" onblur="contract_notes_field('notes_0')" placeholder="Add a note . . ." />
    							<input type="hidden" class="notes_full_0" value="" name="notes_0s" />
    							<input type='button' value='Save' class="save-btn-new_phone" name='record_0' id='record_0' onclick='timesheet_validation("_0","0")' onfocus="focus_save('record_0')" onblur="blur_save('record_0');" />  
    						</div>
    						</div>
    					</div>
    				</div>
    			</div>  
    		
    			<!-- Adding timesheet details in mobile mode ends -->

            	<div class="customer-name-details">
            		<?php
            			$m	= 1;
					if(!empty($timesheet_list)) {
						
						$week_total_perdays	=	array();
						$row_change	=	1;
						$row_index	=	1;
					    foreach($timesheet_list as $timesheet) 
						{
							if($row_change % 2==0) $row_class = "evenrow";
							else $row_class = "oddrow";
							$row_class = "";
							$row_change++;
							$readonly_day			=	false;//testing
							if(isset($timesheet['multiple_entry']))
							{
								$hourly_activity	=	array();
								$hourly_activity[0]	=	$timesheet;
							}
							else
							{
								$hourly_activity		=	$Model_timesheet->get_timesheet_per_day($timesheet); // get all the records of the same format
							}
							$timesheet['sync_flag']	=	0; // testing 
							$count_hourly_activity	=	count($hourly_activity);
							if($timesheet['sync_flag']) {
								$readonly	=	"readonly = 'true'";
							} else {
								$readonly	=	false;
							}
						//echo "<pre>";print_r($timesheet);echo "</pre>";die;
					?>
					
			<div class="cust-hourly-details-mobile-version" >
				<div class="row hourly-details-row" id="hourly-details-row">
					<div class="hourly-details-span-mobile-version toggle-inactive" >
					<a href="javascript:void(0);" class="arrow-tag toggle-inactive" id="hourly-details-span-<?php echo $m+1;?>" onclick="toggleCustomerphone(this,'<?php echo $m+1;?>')" ><img class="img-arr1" src="/media/images/tt-new/timesheet-arrow-left.png"></a>
					<?php	
								        	    $customer_name = strlen($timesheet['CustomerCompanyOrLastName'])>17?substr(html_entity_decode($timesheet['CustomerCompanyOrLastName']),0,17)."...":html_entity_decode($timesheet['CustomerCompanyOrLastName']);
									            ?>
                									<input type="hidden" class="customer_full_data_<?php echo $m;?>" value="<?php echo html_entity_decode($timesheet['CustomerCompanyOrLastName']); ?>" autocomplete='off' />
    												<input type="hidden" class="customer_part_data_<?php echo $m;?>" value="<?php echo $customer_name; ?>" autocomplete='off' />
    										
                									<input type="text" class="client-name-field-mobile cust_<?php echo $m;?>" id='customer_name_<?php echo $m;?>' name='customer_name_<?php echo $row_index?>' value='<?php echo $customer_name;?>'	
    													 onkeyup = 'openPopup(this, event, "customer" , <?php echo json_encode($data_names['customers_name']); ?>,"_<?php echo $m;?>", "down"  )'							
    													 onfocus = 'display_down_arrow("down_Mobilecustomer_<?php echo $m;?>","customer")'
    													 onblur='check_field_selection("customer", "<?php echo SITEURL; ?>", "_<?php echo $m;?>")'  <?php echo $readonly;?>  autocomplete='off' />
												 	<input type='hidden' autocomplete='off' id='customer_val_<?php echo $m;?>' name ='customer_<?php echo $row_index?>' value='<?php echo $timesheet['CustomerRecordID'];?>' />
            										<input type='hidden' autocomplete='off' id='customer_<?php echo $m;?>' name ='customer1_<?php echo $row_index?>' value='<?php echo $timesheet['CustomerCompanyOrLastName'];?>' />
            										<a href="javascript:void(0);" id='down_Mobilecustomer_<?php echo $m;?>' class="view_more_phone right" tabIndex="-1" 	
            										onclick = 'openPopup(this, event, "customer" , <?php echo json_encode($data_names['customers_name']); ?>,"_<?php echo $m;?>")'
            										><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
                            					</div> 
                            					
                            					<?php 
												 $l = 1;
            									$total_hours_per_task 		=	0;
            									
            									for($i=0;$i<$count_hourly_activity;$i++)  // read records one by one and check if added date is in current week days
            									{
            									    $hourly_activity_syn_total 	=	1;
										            for($j=$l;$j<=7;$j++)
										            {
										                if(!empty($hourly_activity[$i]['SlipDate'])) {
            												$slip_date	=	$hourly_activity[$i]['SlipDate']." 00:00:00";
            												$date_num	=	date("j",strtotime($slip_date));
            												$day_num	=	date("D",strtotime($slip_date));
											            }
											            if(!empty($date_num) && $date_num == $dates[$j]) 
											            {
    											            if(!empty($hourly_activity[$i]['ActivitySync']['RecordID'])) {
            													echo "<div id='details-field-mobile-version1' class='mon-div".($i)." date'><label class='text_none text-area client-days-field1 mar-top-15 text-right-2'>".number_format(doubleval($hourly_activity[$i]['total_units']),2, '.', '')."</label><input type='hidden' id='unit_".$j."_".$m."' size=4  value='".$hourly_activity[$i]['total_units']."'  /></div>";
            												} elseif($hourly_activity[$i]['total_slips'] > 1) {
            													$hourly_activity_syn_total = 0;
            													echo "<div id='details-field-mobile-version1' class='mon-div".($i)." date'><label class='text_none client-days-field1 mar-top-15 text-area text-right-2'>".number_format(doubleval($hourly_activity[$i]['total_units']),2, '.', '')."</label><input type='hidden' id='unit_".$j."_".$m."' size=4  value='".$hourly_activity[$i]['total_units']."'  /></div>";
            												} else {
            													$hourly_activity_syn_total = 0;
            													//echo "<input type='text' class='text_none text-area text-right-2' id='unit_".$j."_".$m."' name='unit_".$j."_".$row_index."'  size='4'  value='".number_format(doubleval($hourly_activity[$i]['total_units']),2, '.', '')."' onblur=setTotalUnits('_$m')  autocomplete='off'  />";
            													?>
            													<div id="details-field-mobile-version1" class="mon-div<?php echo $j-1;?> date" >
            													<a>
            													<input type="text" class="hourly-details-field-mobile-version" onkeyup="setTotalUnits('_<?php echo $m;?>')" id="unit_<?php echo $j;?>_<?php echo $m;?>" name='<?php echo "unit_".$j."_".$row_index;?>' value="<?php echo number_format(doubleval($hourly_activity[$i]['total_units']),2, '.', '');?>">
            													<!-- <div class="day-2-field days-field-<?php echo $i;?>"><input type="text" value="<?php echo number_format(doubleval($hourly_activity[$i]['total_units']),2, '.', '');?>" class="hourly-details-field"></div> -->
            													</a>
            													</div>
            													<?php
            																						
            												}
            												$total_hours_per_task	+=	$hourly_activity[$i]['total_units'];
            												switch($day_num) 
            												{
            													case 'Mon'	:	$week_total_perdays[0][] = $hourly_activity[$i]['total_units'];break;
            													case 'Tue'	:	$week_total_perdays[1][] = $hourly_activity[$i]['total_units'];break;
            													case 'Wed'	:	$week_total_perdays[2][] = $hourly_activity[$i]['total_units'];break;
            													case 'Thu'	:	$week_total_perdays[3][] = $hourly_activity[$i]['total_units'];break;
            													case 'Fri'	:	$week_total_perdays[4][] = $hourly_activity[$i]['total_units'];break;
            													case 'Sat'	:	$week_total_perdays[5][] = $hourly_activity[$i]['total_units'];break;
            													case 'Sun'	:	$week_total_perdays[6][] = $hourly_activity[$i]['total_units'];break;
            												}
            												
            												$slip_each_id = explode(',',$hourly_activity[$i]['RecordID']);
            												// for holding the slip number, for new time sheet auto id will be incremented.
            												echo "<input type='hidden'  name ='slip_number_".$j."_".$row_index."' value='".$hourly_activity[$i]['SlipIDNumber']."'  />";
            												echo "<input type='hidden'  name ='slip_action_".$j."_".$row_index."' value='update' />";
            												foreach($slip_each_id as $slip_id)
            												{
            													if(!empty($slip_id))
            													{	
            														echo "<input type='hidden'  name ='slip_id_".$j."_".$row_index."' value='".$slip_id."'  />";
            														echo "<input type='hidden' id='slip_id_".$m."_".$j."' name ='slip_hourly_check_".$m."_[]' value='".$slip_id."' />";
            													}
            												}
            												$i++;
    												    }
											            else 
            											{
            												echo "<input type='hidden'  name ='slip_action_".$j."_".$row_index."' value='insert' />";
            												/*echo "<input type='text' class='text_none text-area text-right-2' id='unit_".$j."_".$m."' name='unit_".$j."_".$row_index."'  size='4'  value='' onblur=setTotalUnits('_$m') $readonly />";
            												*/
            												?>
            												<div id="details-field-mobile-version1" class="mon-div<?php echo $j-1;?> date" >
            												<a>
            												<input type="text" class="hourly-details-field-mobile-version mon-div<?php echo $j-1;?> date" id="unit_<?php echo $j;?>_<?php echo $m;?>" name='<?php echo "unit_".$j."_".$row_index;?>' value="0.00">
            												</a>
            												</div>
            											<?php 
														}
            											
            											$l++;
            											echo "<input type='hidden' id='date_$j' name='date_".$j."_".$row_index."' value='".$days[$j]."'  size='4'  />";
            											
										            }
                									if($hourly_activity_syn_total == 1)
            										{
            											echo "<input type='hidden' value='1' id='sync_all_activities_".$m."' />";
            											?>
            											<script type="text/javascript">
            												lock_timesheet("<?php echo SITEURL;?>", "<?php echo $m?>");
            											</script>
            											<?php
            										}
            									}
            									?>
            									
					
				</div>
			</div>
		 
			<div class="toggle-customer-details" id="toggle-customer-details-phone<?php echo $m+1;?>">
				<div class="cust-toggle-details" >
					<div class="row cust-toggle-details-row" id="cust-toggle-details-row">
						<div class="span3" id="customer-data-span-phone" >Total
						</div> 
						<div class="data-input-fields-phone add-fields" style="margin-top:0;" >  
								<label id="total_<?php echo $m;?>" class="mar-top-12"><?php echo number_format(doubleval($total_hours_per_task),2)?> hrs</label>
    					</div>
    						<?php
										if($payroll_flag) { // payroll activity is on
										$payroll_category	=	empty($timesheet['PayrollCategory'])?"":$timesheet['PayrollCategory'];								
								    	?>
    								<div class="data-input-fields-overtime-phone add-fields" > 
						                    <?php
											
												$payroll_display_name = strlen($payroll_category)>15?substr($payroll_category,0,15)."...":$payroll_category;
												if($payroll_display_name == "Null") {
													$payroll_display_name = "";
													$payroll_category	=	"";
												}
											?>
											<input type="hidden" class="payroll_full_data_<?php echo $m;?>" value="<?php echo $payroll_category; ?>" />
											<input type="hidden" class="payroll_part_data_<?php echo $m;?>" value="<?php echo $payroll_display_name; ?>" />
											
											<input type="text" placeholder="payroll" class="data-name-input payroll_<?php echo $m;?>" id='payroll_name_<?php echo $m;?>' name='payroll_name_<?php echo $row_index?>' 
											onkeyup = 'openPopup(this, event, "payroll" , [<?php echo $payroll_name; ?>],"_<?php echo $m;?>", "down" )'
											onfocus = 'display_down_arrow("down_Mobilepayroll_<?php echo $m;?>","payroll")'
											value='<?php echo $payroll_display_name;?>' 
											<?php
												if(!empty($payroll_latest) OR !empty($slip['payroll_category_name'])){
													echo 'readonly = true';
												}
											?>	
											 onblur='check_field_selection("payroll", "<?php echo SITEURL; ?>", "_<?php echo $m;?>")' <?php echo $readonly;?>  autocomplete='off' />
											 <input type='hidden' id='payroll_<?php echo $m;?>' value='<?php echo $payroll_latest_id;?>' name ='payroll_<?php echo $row_index?>'  />
											 <a href="javascript:void(0);" id='down_Mobilepayroll_<?php echo $m;?>' class="view_more_phone right" tabIndex="-1" 	
											onclick = 'openPopup(this, event, "payroll" , [<?php echo $payroll_name; ?>],"_<?php echo $m;?>")'
											><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a> 
									</div>
						                <?php } 
										 else {
										?>
											<input type='hidden' value='' name ='payroll_<?php echo $row_index?>' autocomplete='off' />
										<?php 
										}
								    	?>
								    	
								    	
						<div class="data-input-fields-detail-phone add-fields" > 
						        <?php
								$job_display_name = strlen($job_name)>15?substr(html_entity_decode($job_name),0,15)."...":html_entity_decode($job_name);
								?>
								<input type="hidden" class="jobname_full_data_<?php echo $m;?>" value="<?php echo $job_name; ?>"  autocomplete='off' />
								<input type="hidden" class="jobname_part_data_<?php echo $m;?>" value="<?php echo $job_display_name; ?>"  autocomplete='off' />
							 	<input type="text" class="data-name-input job_<?php echo $m;?>" readonly id='job_name_<?php echo $m;?>'  name='job_name_<?php echo $row_index?>' value='<?php echo $job_display_name;?>'
								onkeyup = 'openPopup(this, event, "job" , <?php echo json_encode($data_names['jobs_name']); ?>,"_<?php echo $m;?>" , "down" )'
								onfocus = 'display_down_arrow("down_Mobilejob_<?php echo $m;?>","job")'
								onblur  = 'check_field_selection("job", "<?php echo SITEURL; ?>", "_<?php echo $m;?>")'  <?php echo $readonly;?>  autocomplete='off' />
                    		<input type='hidden' id='job_<?php echo $m;?>' name ='job_<?php echo $row_index?>' value='<?php echo $job_number;?>' />
								
								<input type='hidden' value='<?php echo $payroll_flag?>' id='payroll_setting_<?php echo $m;?>' name='payroll_setting_<?php echo $row_index?>'/>
								<input type='hidden' value='update' name='timesheet_flag_<?php echo $row_index?>'/>
										
								<a href="javascript:void(0);" id='down_Mobilejob_<?php echo $m;?>' class="view_more_phone right" tabIndex="-1" 	
								onclick = 'openPopup(this, event, "job" , <?php echo json_encode($data_names['jobs_name']); ?>,"_<?php echo $m;?>")'
								><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a> 	
						</div>
						
						    <?php
										$activity_id	=	empty($timesheet['ActivityID'])?"":html_entity_decode($timesheet['ActivityID']);
										$activity_name	=	empty($timesheet['ActivityID'])?"":html_entity_decode($timesheet['ActivityID']);
										//$is_non_horuly	=	empty($timesheet['Activities']['IsActivityNonHourly'])?'false':$timesheet['Activities']['IsActivityNonHourly'];
										$job_name		=	empty($timesheet['JobNumber'])?"":html_entity_decode($timesheet['JobNumber']);
										$job_number		=	empty($timesheet['JobNumber'])?"":html_entity_decode($timesheet['JobNumber']);
										
										if(isset($timesheet['RecordID']) && $timesheet['Is_non_hourly']) {
											$is_non_hourly = 1;//'true';
										} else {
											$is_non_hourly = 0;//'false';
										}
							    		?>
                    					
						
						<div class="data-input-fields-hourly-phone add-fields" >
						    <?php
							    $activity_display_name = strlen($activity_name)>15?substr(html_entity_decode($activity_name),0,15)."...":html_entity_decode($activity_name);
                            ?>
                            <input type="hidden" class="activity_full_data_<?php echo $m;?>" value="<?php echo $activity_name; ?>" autocomplete='off' />
										<input type="hidden" class="activity_part_data_<?php echo $m;?>" value="<?php echo $activity_display_name; ?>" autocomplete='off' />
										
										<input type='hidden' class="text-area" size="12" class='text_none' id='slip_number_<?php echo $m;?>' name='slip_number_<?php echo $row_index?>' value='<?php echo $timesheet['SlipIDNumber'];?>'  <?php echo $readonly;?> autocomplete='off' />
										
                    					<input type="text" readonly class="data-name-input act_<?php echo $m;?>" id='activity_name_<?php echo $m;?>'
										       name='activity_name_<?php echo $row_index?>' value='<?php echo $activity_display_name;?>'  
										       onkeyup = 'openPopup(this, event, "activity" , <?php echo json_encode($data_names['activites_name']); ?>,"_<?php echo $m;?>", "down"  )'
										       onfocus = 'display_down_arrow("down_Mobileactivity_<?php echo $m;?>","activity")'
										       onblur='check_field_selection("activity", "<?php echo SITEURL; ?>", "_<?php echo $m;?>")'  <?php echo $readonly;?>  autocomplete='off' />
                    					
										<input type='hidden' id='activity_<?php echo $m;?>' name ='activity_<?php echo $row_index?>' value='<?php echo $activity_id;?>' autocomplete='off' />
										<input type='hidden' id='is_non_hourly_<?php echo $m;?>' name ='is_non_hourly_<?php echo $row_index?>'  value="<?php echo $is_non_hourly;?>" autocomplete='off' />
										<input type='hidden' id='rate_<?php echo $m;?>' name ='rate_<?php echo $row_index?>' value='<?php echo $timesheet['Rate'];?>' autocomplete='off' />
										
										<a href="javascript:void(0);" id='down_Mobileactivity_<?php echo $m;?>' class="view_more_phone right" tabIndex="-1" 	
										onclick = 'openPopup(this, event, "activity" , <?php echo json_encode($data_names['activites_name']); ?>,"_<?php echo $m;?>")'
										><img src="/media/images/tt-new/down-arrow.png" class="img-arr1" style=""></a>
						 </div>
						
						<div id="add-note-field-phone" class="add-fields" >
							<input type='hidden' id='start_time' name ='start_time_<?php echo $row_index?>' value='<?php echo date('H:i:s');?>'  />
                    					<?php 

										$notes_value_line = str_replace("\n", " ", $timesheet['Notes']);
										$notes_value 	  = strlen($notes_value_line)>13?substr($notes_value_line,0,13)." ...":$notes_value_line;
										
									    ?>
									  <!--   <input type="hidden" name="notes_<?php echo $row_index?>" class="notes_full_data_<?php echo $m;?>" value="<?php echo $timesheet['Notes']; ?>" />
										<input type="hidden" class="notes_part_data_<?php echo $m;?>" value="<?php echo $notes_value; ?>" />
									 -->
                    					<input type="text" class="data-name-input" name='notes_<?php echo $row_index?>' id='notes_<?php echo $m;?>' cols="20" rows="1" <?php echo $readonly;?> <?php if(strlen($timesheet['Notes']) > 15) { ?> onmouseover="show_notes('<?php echo $m; ?>')" onmouseout="hide_notes('<?php echo $m; ?>')"  <?php }?> spellcheck="false"
										onfocus="enlarge_notes('<?php echo $m;?>','<?php echo $count;?>')" onblur="minimize_notes('<?php echo $m;?>')" value="<?php echo $notes_value?>"/>
                    					<input type='button' value='Save' class='save-btn-new_phone'  onclick='timesheet_validation("<?php echo "_".$m;?>", "<?php echo $row_index?>")' name='record_<?php echo $row_index;?>' id='record_<?php echo $m;?>'/>
                    </div>
                    <input type="hidden" name="week_start_date_<?php echo $row_index?>" value="<?php echo $week_start_date?>" />
					</div>
				</div>
		</div> 
		<?php   $row_index++;
							$m++;
						}
					} else {
						echo "<tr class='evenrow'><td colspan='14' align='center'>No activities found.</td></tr>";
					}
					?>
		</div>
	
	<div class="cust-hourly-details-total-mobile-version" >
		<div class="row hourly-details-row" id="hourly-details-row">
			<div class="span3" id="hourly-details-span-mobile-version-2" >Total
			</div> 
			<div id="details-field-mobile-version" > 
				<div id="hourly-details-field-mobile-version1" class="mon-div0 date"><?php 
								$day_total[0]	=	empty($week_total_perdays[0])?0:array_sum($week_total_perdays[0]);
								echo number_format(doubleval($day_total[0]),2); 
						?></div>
				<div id="hourly-details-field-mobile-version1" class="mon-div1 date"><?php 
								$day_total[1]	=	empty($week_total_perdays[1])?0:array_sum($week_total_perdays[1]);
								echo number_format(doubleval($day_total[1]),2); 
						?></div>
				<div id="hourly-details-field-mobile-version1" class="mon-div2"><?php 
								$day_total[2]	=	empty($week_total_perdays[2])?0:array_sum($week_total_perdays[2]);
								echo number_format(doubleval($day_total[2]),2); 
						?></div>
				<div id="hourly-details-field-mobile-version1" class="mon-div3"><?php 
								$day_total[3]	=	empty($week_total_perdays[3])?0:array_sum($week_total_perdays[3]);
								echo number_format(doubleval($day_total[3]),2); 
						?></div>
				<div id="hourly-details-field-mobile-version1" class="mon-div4"><?php 
								$day_total[4]	=	empty($week_total_perdays[4])?0:array_sum($week_total_perdays[4]);
								echo number_format(doubleval($day_total[4]),2); 
						?></div>
				<div id="hourly-details-field-mobile-version1" class="mon-div5"><?php 
								$day_total[5]	=	empty($week_total_perdays[5])?0:array_sum($week_total_perdays[5]);
								echo number_format(doubleval($day_total[5]),2); 
						?></div>
				<div id="hourly-details-field-mobile-version1" class="mon-div6 date1"><?php 
								$day_total[6]	=	empty($week_total_perdays[6])?0:array_sum($week_total_perdays[6]);
								echo number_format(doubleval($day_total[6]),2); 
						?></div>
				
				
			</div> 
		</div>
	</div>
	
		</form>
	</div>

	<!-- Mobile Screen Div Ends -->
				<!-- Mobile div ends -->
				
				
				<div class="calender-timesheet-buttons-down" >
				<?php 	if(empty($timesheet_list))
						{
							$count	=	0;
						}else{
							$count	=	count($timesheet_list);	// time sheet element count.	
						}
					?>
		<div id="timesheet-week-hrs-btn" class="timesheet-week-hrs-btn">
			
				<label class="calender-timesheet">
				Week Total <?php echo number_format(doubleval(array_sum($day_total)),2);?> hrs
				</label>
			
		</div>
			<a class="sync1" href="javascript:void(0);" >
				<div class="sync-all-btn1 calender-timesheet" id="div_sync_2" onclick="alert_syncall(this)">
					 <span class="sync-all-list">Sync All</span>
				</div>
			</a>
			<?php	if($_SESSION['synced_slips_view'] == 0)
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
					<a href="<?php echo SITEURL;?>/activity/sync/<?php echo $sync_url_id?>/1">
		<div id="timesheet-show-synced-btn" class="<?php echo $synced_text;?> calender-timesheet timesheet-show-synced-btn">
		<?php echo $synced_text;?>
				</div>
			</a>
	</div>
		
<div class="clear"></div>
	  <div class="notice-slips">  
		<?php if(isset($_SESSION['free_user']) && $_SESSION['free_user'] == 1) {?>
					<div class="free-expire-alert">
						You have <?php echo $_SESSION['days_left']?> day(s) left. Please contact your company
						Administrator to upgrade your plan.
					</div>
			<?php }?>
	</div>  </div>
	
		<!-- Middle Contents ends -->
</div>
	<div class="popup_alert" id='refresh_customers_warn_timesheet'>
	 		<div class="alert-pop-up">
	 			<p class="question">Do you want to save the changes you made in this page?</p>
	 			<p class="message">Your changes will be lost if you refresh the lists. To save the changes please click on Save.</p>
	 		</div>
			
			<a href="javascript:void(0);" onclick="close_popup('refresh_customers_warn_timesheet')" class="radius-5 button-1 left">Cancel</a>
			<a href="javascript:void(0);" onclick="save_edited_timesheet_row('<?php echo SITEURL;?>');" class="radius-5 alert-save right">Save</a>
			<a href="<?php echo SITEURL;?>/activity/importcustomer/3"  class="radius-5 button-1 right">Don't Save</a>
		</div>

	<div class="popup_alert" id='timesheet_change_alert' >
		<div class="alert-pop-up">
 			<p class="question">Do you want to save the changes you made in this row?</p>
 			<p class="message">Your changes will be lost if you move from this row. To save the changes please click on Save button.</p>
 		</div>
	
		<a href="javascript:void(0);" onclick="save_edited_timesheet_row('<?php echo SITEURL;?>');" class="radius-5 alert-save right">Save</a>
		<a href="javascript:void(0);" onclick="focus_clicked_timesheet_row('timesheet_change_alert')" class="radius-5 button-1 right">Don't Save</a>
	
	</div>

		
	<div class="popup_alert" id='timesheet_save_alert' >
		<div class="alert-pop-up">
 			<p class="question">Do you want to save the changes you made in this page?</p>
 			<p class="message">Your changes will be lost if you move from this page. To save the changes please click on Save button.</p>
 		</div>
	
		<a href="javascript:void(0);" onclick="close_popup('timesheet_save_alert')" class="radius-5 button-1 left">Cancel</a>
		<a href="javascript:void(0);" onclick="save_edited_timesheet_row('<?php echo SITEURL;?>');" class="radius-5 alert-save right">Save</a>
		<a href="<?php echo SITEURL;?>/activitysheet" class="radius-5 button-1 right">Don't Save</a>
	
	</div>

	<div class="popup_alert" id='timesheet_save_alert_week_change' >
		<div class="alert-pop-up">
 			<p class="question">Do you want to save the changes you made in this page?</p>
 			<p class="message">Your changes will be lost if you move from this page. To save the changes please click on Save button.</p>
 		</div>
	
		<a href="javascript:void(0);" onclick="close_popup('timesheet_save_alert_week_change')" class="radius-5 button-1 left">Cancel</a>
		<a href="javascript:void(0);" onclick="save_edited_timesheet_row('<?php echo SITEURL;?>');" class="radius-5 alert-save right">Save</a>
		<a href="javascript:void(0);" class="radius-5 button-1 right" id="dont-save-ts-week-change" onclick="week_submit('2','1');">Don't Save</a>
	
	</div>

	<div class="popup_alert" id='sync_selected_timesheet'>
		 		<div class="alert-pop-up">
		 			<p class="question">You are about to Sync <span class="sync-selected-count">x</span>&nbsp;<span class="sync-alert-title-ts">Timesheet</span></p>
		 			<p class="message">Syncing selected timesheets will make them non-editable. Be careful
						as this action cannot be undone.</p>
 				</div>
				<a href="javascript:void(0);" onclick="sync_activity('<?php echo $count;?>')"  class="radius-5 button-1 right">Sync Selected</a>
				<a href="javascript:void(0);" onclick="close_popup('sync_selected_timesheet')" class="radius-5 button-1 right">Don't Sync</a>
				
			
	</div>

	<div class="popup_alert" id='sync_selected_timesheet_error'>
 		<div class="alert-pop-up">
 			<p class="message">Please select any timesheet from the list.</p>
		</div>
		<a href="javascript:void(0);" onclick="close_popup('sync_selected_timesheet_error')" class="radius-5 button-1 right">Ok</a>
	</div>
	
	<div class="popup_alert" id='slip_button'  >
 		<div class="alert-pop-up">
 			<p class="question">Do you want to save the changes you made in this activity slip?</p>
 			<p class="message">Your changes will be lost if you don't save them.</p>
 		</div>
		<a href="javascript:void(0);" onclick="close_popup('slip_button')" class="radius-5 button-1 left">OK</a>
	</div>
			

<div class="span1" ></div>

