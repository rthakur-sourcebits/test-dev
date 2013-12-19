/**
 * @Function : setTime(flag, site_url)
 * @Desc : set the start time and end tme from the server.
 * @Param : flag - differentiate start time and end time.
 * site_url - url of the site. 
 */

//------------------------------------------------- GLOBAL SECTION ---------------------------------------------------------

// Item Global Variable
$(document).ready(function(){
	$.Item_Sales		= {}; 
	$.Service_Sales 	= {}; 
	$.Timebilling_Sales 	= {}; 
	$.Company 			= {};
	$.Global			={};
	
	$.USA 		= 1;
	$.UK 		= 2;
	$.AUSTRALIA = 3;
	$.CANADA 	= 4;
	$.NZ 		= 5;
	
	$.CASH 			= 0;
	$.CHECK 		= 1;
	$.CREDIT_CARD 	= 2;
	$.DEBIT_CARD 	= 3;
	$.OTHER 		= 4;
	
	//Company
	// define in template file.
});



//------------------------------------------------- GLOBAL SECTION ENDS ---------------------------------------------------------

var free_plan_id ="";
function setTime(flag, site_url)
{
	$.ajax({
		  url		:	site_url+'/data/time',
		  dataType	:	'json',
		  success	:	function(data)
		  				{
						    if (flag == 'start')
						    {
						    	document.getElementById('start_time').value 	=	data['time'];
						    	document.getElementById('set_time').innerHTML 	= '<span style="border: '+
						    													'1px solid black;padding:0px;cursor:pointer;"'+
						    													' onclick=setTime("stop","'+site_url+'") >'+
						    													'<span class="clock">&nbsp;&nbsp;&nbsp;&nbsp;'+
						    													'&nbsp;&nbsp;</span>Stop</span>';
						    }else{
						    	document.getElementById('end_time').value 		=	data['time'];
						    	document.getElementById('set_time').innerHTML 	=	"";
						    	document.getElementById('set_time').style.display	=	"none";
						    }	    
						 }				
			});
}

/**
 * @Function	:	getId(element, site_url, m)
 * 
 * @Desc		:	set the start time and end tme from the server.		
 * @Param 		: 	element - html element that hold the value of the name.
 * 					site_url - url of the site. 
 * 					m - in time sheet there are multiple rows to differentiate between then m value is used.
 */
 
var select_pop_result	=	"";
//var val_auto_fill		=	"";
function getId(element, site_url, m)
{
//	alert("get id");	
	if (typeof(m) == 'undefined')
	m='';
	
	var name 		=	document.getElementById(element+"_name"+m).value;
	var customer	=	document.getElementById("customer_name"+m).value;
	var customer_id	=	document.getElementById("customer_val"+m).value;
	var activity_id	=	document.getElementById("activity_name"+m).value;
	var employee_rec_id	=	$('.employee_record_id').val();
	if(typeof(activity_id) == 'undefined')
	{
		ajax_url	=	site_url+'/data/id?table='+element+'&name='+name+'&c_id='+customer_id;
	}
	else
	{
		ajax_url	=	site_url+'/data/id?table='+element+'&name='+name+'&c_id='+customer_id+'&activity='+activity_id+'&employee_id='+employee_rec_id;
	}
	if (trim(name) != '')
	{
		$.ajax({
		  
			url			:	ajax_url,
			dataType	:	'json',
			success		:	function(data)
							{
								if (data)
								{ 	if (data['id'] == 'other')          // if the name is not matched with any record then other value is returned.
									{ 
										if(select_pop_result == 0)
											document.getElementById(element+"_name"+m).value = '';
										if (element == 'activity'){
											document.getElementById('rate'+m).value ='';
										}
										return;
									}
											   			
									if (element == 'activity')							// if the element activity is selected, then rate value is also set.
									{	
										if (document.getElementById('rate'+m))
										{										      
											if (m != '' && typeof(m) == 'undefined')
											{
												setTotal('1');
											}
											
											if (typeof(data['rate']) != 'undefined')
											{
												if($("#slip_id").val() == "") {
													document.getElementById('rate'+m).value = to2DecWithComma(data['rate']);
													setTotal();
												} else if(m != "" && m != 'undefined'){
													document.getElementById('rate'+m).value = to2DecWithComma(data['rate']);													
												}
												$('#which_rate').val(data['which_rate']);
												if(document.getElementById('total'))	// setting the total value.
												{
													//if(unitValidator(document.getElementById('units').value))
													//document.getElementById('total').value = document.getElementById('units').value * document.getElementById('rate').value;
												}
											}
											
											
																						
											if (data['id'] == 'other')
											{
												document.getElementById('rate'+m).readOnly = false;
											}
											else
											{
												document.getElementById('rate'+m).readOnly = false;
											}
											
											if(data['IsActivityNonHourly'] == true) // displaying fourly and non hourly sheet.
											{		
												document.getElementById('is_non_hourly'+m).value  = '1';//'true';
												
												if(document.getElementById('set_time'))
												{
													document.getElementById('set_time').style.display	=	'none';
													document.getElementById('timmer').style.display		=	'none';
													s2	=	0;
													if(typeof(t) != 'undefined' )
													clearTimeout(t);
												}												
											}
											else
											{
												document.getElementById('is_non_hourly'+m).value  = '0';
												if(document.getElementById('set_time'))
												{
													document.getElementById('set_time').style.display = 'inline';
												}
											}
										}
									}
									else if (element == 'customer')								// for the element customer.
									{
										if (data['id'] == 'other')
										{
											document.getElementById(element+m).value = '';
											return;
										}
										if (typeof(data['rate']) != 'undefined')
										{
											document.getElementById('rate'+m).value = data['rate'];
											
											if(document.getElementById('total'))	// setting the total value.
											{
												if(unitValidator(document.getElementById('units').value))
												document.getElementById('total').value = document.getElementById('units').value * document.getElementById('rate').value;
											}
										}
									}
								
									document.getElementById(element+m).value = data['id'];
								}
								else
								{
									if(document.getElementById('rate'+m))
										document.getElementById('rate'+m).value = '';										
									if(document.getElementById('total'))
										document.getElementById('total').value = '';	
								}
							}				
		});
	}else
	{
		document.getElementById(element+m).value = '';
	}
}

auto_fill_value = "";

function check_field_selection(id, url, m){
	
	if (typeof(m) == 'undefined'){
		m='';
	}
	if(auto_fill_value != id) { 
		if(m == '' && $("#slip_id").val() != "") {
			getId(id, url, m);
		} else {
			if(id == "job") {
				document.getElementById(id+m).value="";
			} else {
				document.getElementById(id+"_name"+m).value="";
			}
		}
	}
	else {
		if($("#slip_id").val() != "") {
			select_pop_result = 1;
		}
		getId(id, url, m);
	}
	//$(".popup").hide();
}

/**
 * @Function	:	getId(element, site_url, m)
 * @Desc		:	This will validates the activity slips data.		
 **/
function activity_validation()
{ 
	var customer_value	=	trim(document.getElementById('customer_name').value) ;
	var customer_id 	=	trim(document.getElementById('customer').value) ;
	var activity_value 	=	trim(document.getElementById('activity_name').value) ;
	var activity_id 	=	trim(document.getElementById('activity').value) ;
	var employee_value  =	$('.employee_name').val();
	var units			=	trim(document.getElementById('units').value);
	var rate			=	trim(document.getElementById('rate').value);
	var total			=	trim(document.getElementById('total').value);
	var rate_check		=	$("#display_rate_check").val();

	if(rate_check == 1 && rate == '') {
		$("#rate").val(0);
		total	=	0;
		$("#total").val(0);
	}
	var is_non_hourly   =	0;
	
	if(document.getElementById('is_non_hourly').value)
    {
    	is_non_hourly	=	document.getElementById('is_non_hourly').value;
    }
    
    $(".validation-error").hide();
    $(".activity-form-validator").removeClass("error-label");
    $(".activity-form-validator div.span3").removeClass("login-left-label");
	if ((customer_value == 'Enter name...' || customer_value == ''))
	{
		$("#customer_error").show();
		$(".customer-tag1").css("height", "auto");
		$("#customer_section").addClass("error-label");
		$('.customer-tag1').removeClass('height-45');
		return false;		
	}
	else if (trim(document.getElementById('slip_number').value) == '' || trim(document.getElementById('slip_number').value) == "type slip number")
	{
		$("#slipnumber_error").show();
	//	$(".customer-tag1").css("height", "auto");
		$("#slipnumber_section").addClass("error-label");
		$("#slipnumber_section .users-activity").addClass("login-left-label");
		$('.slip-height-45').css('cssText','height:81px !important');
		return false;		
	}
	else if(employee_value==''){
		$("#emp_field_activity").addClass("error-label");
		$("#emp_field_activity .users-activity").addClass("login-left-label");
		$('.employee_field').addClass('yellow');
		$('#emp_error').show();	
		return false;
	}
	else if(activity_id == '' || activity_value == '')
	{
		$("#activity_error").show();
		//	$(".customer-tag1").css("height", "auto");
		$("#activity_section").addClass("error-label");
		$("#activity_section .users-activity").addClass("login-left-label");
		$('.activity').removeClass('height-90');
		//$('#activity_name').css('cssText','background:#fffce8 !important');
		return false;
	}
	
	else if(trim(units) == '' || trim(units)== 'type in units or click timer icon to measure time.')
	{
		$("#unit_error").show();
		//	$(".customer-tag1").css("height", "auto");
		$("#unit_section").addClass("error-label");
		$("#unit_section .hourly-activity").addClass("login-left-label");
		$('.activity.height-90').css('height','127px');
		return false;
	}
	else if(!unitValidator(units))
	{
		$("#unit_error").show();
		//	$(".customer-tag1").css("height", "auto");
		$("#unit_section").addClass("error-label");
		$("#unit_section .hourly-activity").addClass("login-left-label");
		return false;
	}
	else if(rate == '' && rate_check == 0)
	{
		$("#rate_error").show();
		//	$(".customer-tag1").css("height", "auto");
		$("#rate_section").addClass("error-label");
		$("#rate_section .users-activity").addClass("login-left-label");
		return false;
	}
	else if( ! unitValidator(rate))
	{
		$("#rate_error").show();
		//	$(".customer-tag1").css("height", "auto");
		$("#rate_section").addClass("error-label");
		$("#rate_section .users-activity").addClass("login-left-label");
		return false;
	}
	else if( ! unitValidator(total))
	{
		$("#total_error").show();
		//	$(".customer-tag1").css("height", "auto");
		$("#total_section").addClass("error-label");
		$("#total_section .hourly-activity").addClass("login-left-label");
		return false;
	}
	
	convertDate();
	document.getElementById('create').submit();

}

/*
 * @Function	:	timesheet_validation(m)
 * @param		:	m - value of time sheet row.
 * @Desc		:	This will validates the activity slips data.		
 *
 */
function timesheet_validation(m, row_index){
	
	if(!($('.normal-screen-version').css('display') == 'none')) {
		timesheet_form_id	=	"create_time_0";
	} else {
		timesheet_form_id	=	"phone_create_time_0";
	}
	var customer_value	=	$('#'+timesheet_form_id+' #customer_name'+m).val() ;
	var activity_name 	=	$('#'+timesheet_form_id+' #activity_name'+m).val() ;
	var activity_id 	=	$('#'+timesheet_form_id+' #activity'+m).val() ;
	var rate_id 		=	$('#'+timesheet_form_id+' #rate'+m).val() ;

	var is_non_hourly   =	'';	
	
	
	if($('#'+timesheet_form_id+' #is_non_hourly'+m))
    {
    	is_non_hourly	=	$('#'+timesheet_form_id+' #is_non_hourly'+m).val();
    }
	
	if ((customer_value == 'Enter name...' || customer_value == ''))
	{
		alert('Please select a customer from the list');
		$('#'+timesheet_form_id+' #customer_name'+m).focus();
		return false;		
	}
	else if(activity_id == '' || activity_name == '')
	{
		alert('Please select an Activity from the list.');
		clear_tex(document.getElementById('activity_name'+m));
		return false;	
	}
	
	var flag = 0;
	for(var i=1; i<8; i++)
	{
		if($('#'+timesheet_form_id+' #unit_'+i+''+m))
		{
			if($('#'+timesheet_form_id+' #unit_'+i+''+m).val()!='')
			{
				flag = 1;
				if(!unitValidator($('#'+timesheet_form_id+' #unit_'+i+''+m).val()))
				{
					alert('Please enter units as a numeral (no letters or special characters).');
					return false;	
				}
				
			}
		}
	}
	if(flag == 0)
	{
		alert('All unit fields are empty, please enter units.');
		return false;
	}	
	
	if($('.normal-screen-version').css('display') == 'none') {
		qw	=	document.getElementById('phone_create_time_0');
	} 
	else if (!($('.normal-screen-version').css('display') == 'none'))
	{
		qw	=	document.getElementById('create_time_0');
	}
	field = document.createElement('input');
	field.setAttribute('name', 'timesheet_row_index');
	field.setAttribute('type', 'hidden');
	field.setAttribute('value', row_index);
	qw.appendChild(field);
	document.getElementById(timesheet_form_id).submit();
	
}
/**
 * @Function	:	clear_tex(element)
 * @param		:	element - textbox.
 * @Desc		:	This will makes the textbox empty and focus on textbox.		
 **/
function clear_tex(element)
{ 
	element.value = '';
	element.focus();
}

/**
 * @Function	:	validateRegistration(flag)
 * @param		:	flag - e means editing the record.
 * @Desc		:	validates the registration fields while inserting and editting.		
 **/
function validateRegistration()
{
	return_value  = true;
	if(!return_value) return return_value;

	else document.getElementById('register').submit();
}

/*
 * 
 */
function validate_focus(element, message)
{
	alert(message);
	element.focus();
	return;
}

/*
 * 
 */
function unitValidator(str)
{
	var pat=/\D/g;
	
	if(/^([0-9\-\.]*)$/.test(str)) {
		
		return true;
	} else { 
		return false;
	}	
}


/*
 * @function selectBox_other
 * @param select_id: id of the select box selected.
 * @description : The date formate should be DD-MM-YYYY
 */
function selectBoxOther(select_element)
{
	if(select_element.id == 'activity')
	{
		var activity_value = trim(select_element.options[select_element.selectedIndex].value);
		
		if(activity_value != 'other')
		{
			var activity_rate =  activity_value.split(",")[1];
			
			document.getElementById('rate').value=activity_rate;
					
			document.getElementById('rate').readOnly = false;
		}else if(activity_value == 'other')
		{
			document.getElementById('rate').readOnly = false;
		}
	}
	
	if(document.getElementById(select_element.id+'_text'))
	{
		if(select_element.options[select_element.selectedIndex].value == 'other')
		{
			document.getElementById(select_element.id+'_text').style.display='block';
		}else{
			document.getElementById(select_element.id+'_text').style.display='none';
		}
	}
}

/*
 * 
 */
function selectBoxValidate(element)
{
	select_object = document.getElementById(element);
	var element_value = trim(select_object.options[select_object.selectedIndex].text);
	
	if(element_value == 'other'){
		if(document.getElementById(element+'_text'))
		{
			element_value = trim(document.getElementById(element+'_text').value);
		}
	}
	
	return element_value;	
}

function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}
 
function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}
 
function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}
/*
 * 
 */
var s=0;
var s2=0;
var prev_entered_time = "";
var prev_time	=	"";
function startTime(param)
{
	var today=new Date();
	
	if(!param || document.getElementById('timmer').style.display=='none')
	{
		if(prev_time != ""){
			if(confirm("Do you want to add more time?")) {
				cu_time	=  today.getTime();
				s = cu_time-s2;
				s2 = cu_time-s;
			} else {
				prev_entered_time = prev_time = "";
				s = today.getTime();
				s2=0;
			}
		} else {
			s = today.getTime();
		}
	
	}else if(param=='cancel'){
		document.getElementById('timmer').style.display = 'none';
		document.getElementById('units').readOnly = false;
		clearTimeout(t);
		return;
	}else if(param == 'start'){
		s = today.getTime();
		s = s-s2;
	}else{
		s2 = (today.getTime()-s);
	}
	$('#unit_section').append($('#timmer'));
	$('#timmer').show();

	if(!param)
	{
		if(prev_entered_time != "") {
			document.getElementById('start_time').innerHTML=prev_entered_time['h']+":"+prev_entered_time['m']+":"+prev_entered_time['s'];
		} else {
			document.getElementById('start_time').innerHTML="00"+":"+"00"+":"+"00";
		}
		
	}else{
		var time = secondsToTime((s2/1000));
		document.getElementById('start_time').innerHTML=time['h']+":"+time['m']+":"+time['s'];
	}
	if(param!='stop')
	{
		if(param == 'pause')	// converts pause button to start button.
		{
			clearTimeout(t);
			document.getElementById('start_pause').innerHTML = '<a href="javascript:void(0);" onclick=startTime("start") class="" >Start</a>';
			
		}else{					// converts start button to pause button.
			t = setTimeout('startTime("ok")',500);
			//clearTimeout(t);
			document.getElementById('start_pause').innerHTML = '<a href="javascript:void(0);" onclick=startTime("pause") class="" >Pause</a>';
		}
	
	}else if(param == 'stop'){
		var mins=s2/(60000*60);
		//document.getElementById('set_time').innerHTML = '<span style="border: 1px solid black;padding:0px;cursor:pointer;" onclick=startTime() ><span class="clock">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>';
		document.getElementById('units').value=roundNumber(mins, 2);
		document.getElementById('timmer').style.display = 'none';
		document.getElementById('units').readOnly = false;
		prev_time			=	s;
		prev_entered_time	=	time;
		clearTimeout(t);
		if(unitValidator(document.getElementById('units').value) && trim( document.getElementById('rate').value) != '') {
			document.getElementById('total').value = to2DecWithComma(roundNumber(document.getElementById('rate').value*(document.getElementById('units').value), 2));
		}
	}
}

function close_timmer()
{
	document.getElementById('timmer').style.display = 'none';
	document.getElementById('units').readOnly = false;
	if(t)
	clearTimeout(t);
}



function secondsToTime(secs)
{
	
	var hr  = Math.floor(secs / 3600);
	var min = Math.floor((secs - (hr * 3600))/60);
	var sec = Math.floor(secs - (hr * 3600) -  (min * 60));

	if (hr < 10)   { hr    = "0" + hr; }
	if (min < 10) { min = "0" + min; }
	if (sec < 10)  { sec  = "0" + sec; }
	//if (hr)            { hr   = "00"; }
	var obj = {
	        "h": hr,
	        "m": min,
	        "s": sec
	    };
	return obj;    
}

/*
 * calculate total and save insert into total field.
 */
function setTotal(flag){
	
	if(unitValidator(document.getElementById('units').value) && trim( document.getElementById('rate').value) != '') {
		document.getElementById('total').value = to2DecWithComma(roundNumber(document.getElementById('rate').value*(document.getElementById('units').value), 2));
	}
}


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function print_preview(siteurl)
{
		myWindow=window.open(siteurl+'/activitysheet?print','_blank','fullscreen=no,resizable=yes');
		myWindow.focus();
		myWindow.print();
	
}
function setTotalUnits(m)
{	
	
	if(typeof(m) == 'undefined')
	m='';
	
	if(!($('.normal-screen-version').css('display') == 'none')) {
		timesheet_form_id	=	"create_time_0";
	} else {
		timesheet_form_id	=	"phone_create_time_0";
	}
	
	//message 		= $('#'+timesheet_form_id+' #'+element+"_name"+m);
	
	var total=0;
	for(var i=1;i<8;i++)
	{
		
		if($('#'+timesheet_form_id+' #unit_'+i+''+m))
		if(! unitValidator($('#'+timesheet_form_id+' #unit_'+i+''+m).val()))
		{
			document.getElementById('unit_'+i+''+m).value='';
			alert('Please enter units as a numeral (no letters or special characters)');
			break;
			return;
		}
		
		else if($('#'+timesheet_form_id+' #unit_'+i+''+m).val())
		{
			total+= parseFloat($('#'+timesheet_form_id+' #unit_'+i+''+m).val()); 
		}
	}
	
	$('#'+timesheet_form_id+' #total'+m).text(to2DecWithComma(total));
	
}

/*
 * function enable fields to be editted
 */
var prev_row="";
var timesheet_entry_focus	=	false;
var timesheet_edited_row_id	=	"";
var edited_row_id		=	"";
var click_sync_check	=	false;
function editEnable(m, total, flag)
{
	if(edited_row_id != m && timesheet_entry_focus) {
		edited_row_id			=	m;
		warn_lost_changes_timesheet(timesheet_edited_row_id);
		return true;
	}
	edited_row_id			=	m;
	timesheet_entry_focus	=	true;
	if(m == "") {
		timesheet_edited_row_id	= "_0";
	} else {
		timesheet_edited_row_id	= "_"+m;
	}
	if(click_sync_check) {
		click_sync_check = false;
		return;
	}
	if(m == "")
		$("#record_0").show();
	else $("#record_0").hide();
	if(document.getElementById("sync_all_activities_"+m))	// checking wheather all fields are synced or not.
	{	
		
		for(k=1;k<=8;k++)
		{
			
			if(document.getElementById("unit_"+k+"_"+m))		// not displaying the remaining empty text boxes.
			{
				document.getElementById("unit_"+k+"_"+m).style.display = 'none';	
			}
		}
		expand_row_content(m, total);
		return false;
		
	}
	for(var j=1; j<=total;j++){
		var bValue = '0';
		
		if(j==m)
		{
			bValue = '1';
		}else{
			bValue = '0';
		}
			
			if(bValue == '1'){
				if(document.getElementById('record_'+j))
				document.getElementById('record_'+j).style.display = 'inline-block';
			}else{
				
				if(document.getElementById('record_'+j))
				document.getElementById('record_'+j).style.display = 'none';
			}
	}
	
	/* *************Code to replace partial text to full text and reverse************** */
	for(j=1;j<=total;j++)
	{
		
		if(j == m)
		{
			if(prev_row == m)
			{
			}
			else
			{
				$('#customer_name_'+m).val($('.customer_full_data_'+m).val());
				$('#activity_name_'+m).val($('.activity_full_data_'+m).val());
				$('#job_name_'+m).val($('.jobname_full_data_'+m).val());
				$('#payroll_name_'+m).val($('.payroll_full_data_'+m).val());
			}
		}
		else
		{
			$('#customer_name_'+j).val($('.customer_part_data_'+j).val());
			$('#activity_name_'+j).val($('.activity_part_data_'+j).val());
			$('#job_name_'+j).val($('.jobname_part_data_'+j).val());
			$('#payroll_name_'+j).val($('.payroll_part_data_'+j).val());
		}
	}
	prev_row = m;
}

function sync_check_disable_save()
{
	click_sync_check = true;
}

/**
	@Function	 :	expand_row_content
	@Description :	Show full row content if onclick and if activity is synced.
**/
	function expand_row_content(m, total)
	{
		for(j=1;j<=total;j++)
		{
			
			if(j == m)
			{
				$('#customer_name_'+m).val($('.customer_full_data_'+m).val());
				$('#activity_name_'+m).val($('.activity_full_data_'+m).val());
				$('#job_name_'+m).val($('.jobname_full_data_'+m).val());
				$('#payroll_name_'+m).val($('.payroll_full_data_'+m).val());
				$('#notes_'+m).val($('.notes_full_data_'+m).val());
			}
			else
			{
				$('#customer_name_'+j).val($('.customer_part_data_'+j).val());
				$('#activity_name_'+j).val($('.activity_part_data_'+j).val());
				$('#job_name_'+j).val($('.jobname_part_data_'+j).val());
				$('#payroll_name_'+j).val($('.payroll_part_data_'+j).val());
				$('#notes_'+j).val($('.notes_part_data_'+j).val());
			}
		}
	}
/**
	function Check and uncheck all the list items
**/
	function check_all_activities(id,pID)
	{
		$( "#" + pID + " :checkbox").attr('checked', $('#' + id).is(':checked'));
	}
	
/**
	Timesheet sync submit form
**/
	function sync_activity(count)
	{ 
		form	=	document.getElementById('timesheet_form');
		for(i=1;i<=count;i++) {
			
			if(document.getElementById('timesheet_check_'+i) != undefined && 
			   document.getElementById('timesheet_check_'+i).checked)
			{
				var text_data	=	document.getElementsByName('slip_hourly_check_'+i+'_[]');
				
				for(j=0;j<text_data.length;j++) { 
					if(text_data[j] != undefined) { 
						field = document.createElement('input');
						field.setAttribute('name', 'timesheet_check_list[]');
						field.setAttribute('type', 'hidden');
						field.setAttribute('value', text_data[j].value);
						form.appendChild(field);
					}
				}
			}
		}
		field = document.createElement('input');
		field.setAttribute('name', 'sync');
		field.setAttribute('type', 'hidden');
		field.setAttribute('value', '1');
		form.appendChild(field);
		form.submit();
	}
	
/*
* this function allows the user to enter username with _ and numbers.
* and block all other special characters
*/
function checkUserName(str)
{
	var patt1=/\W/g;
	if(str.match(patt1))
	{
		if(str.match(patt1)!='_')
		{
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	}
}

/*
* this function allows the user to enter username with ' and alphabets.
* and block all other special characters and numbers.
*/
function checkName(str)
{
	var patt1=/\W/g;
	var patt2=/\d/g;
	if(str.match(patt1) || str.match(patt2))
	{
		if(str.match(patt2)){
			return false;
		}
		if(str.match(patt1)!="'")
		{
			return false;
		}else{
			return true;
		} 
	}else{
		return true;
	}
}

/*
* this function allows the user to enter username with ' and alphabets.
* and block all other special characters and numbers.
*/
function checkPassword(str)
{
	var patt1=/\W/g;
	var match_string = str.match(patt1);
	if(match_string){
		for(i=0; i<match_string.length;i++){
			if(match_string[i] == ' ' ){
				return false;
				break;
			}
		}
	}
	
	return true;
}

/*
 * 
 */
function changePassword(check_box){
	if(check_box.checked == true)
	{
		document.getElementById('tr_password').style.display = 'block';
	}else{
		document.getElementById('tr_password').style.display = 'none	';
	}
}


function autoExpand(txtBox, event)
{
	var thetext = document.getElementById(txtBox.id).value;
	if(thetext.length >1000)          // limit for notes.
	{
		document.getElementById(txtBox.id).value = trim(thetext.substr(0,1000));
		alert('Notes is limited to 1000 characters.');
		return;
	}
	
    //if (event.keyCode == "13" || event.keyCode == "8") {
        var therows = 0;
        var newtext = thetext.split("\n");
        therows += newtext.length;
        if(therows<10)
        document.getElementById(txtBox.id).rows = therows;
        return false;
    //}else{
    
    //}
}



/**
function for hide notes
**/

function hide_notes(id)
{
	document.getElementById('notes_display_'+id).style.display = "none";
}

/*
 * display a popup (customer, activity, job list) in activity and timesheet page.
 */
function display_popup(popup){
	document.getElementById(popup).style.display = 'block'; 
	var grayout = document.getElementById('grayout');
	
	if (navigator.appName.indexOf("Microsoft")!=-1) 
	{
		grayout.style.width		=	document.body.offsetWidth-21;
		grayout.style.height	=	document.body.offsetHeight-4;
	}
	else
	{
		grayout.style.width		=	window.innerWidth;
		grayout.style.height	=	window.innerHeight;
	}
	grayout.style.display	=	'block';
}

/*
 * close pop-up.
 */
function close_popup(popup){
	document.getElementById(popup).style.display = 'none'; 
	document.getElementById('grayout').style.display = 'none';
}

function save_slip(popup, flag){
	if(flag)
	{
		document.getElementById(flag).value = '1';
	}
	close_popup(popup);
	activity_validation();
}

/*
 * function check the page is editted or not.
 */
function check_changes(site_url)
{
	
	if((document.getElementById('customer_name').value == 'Enter name...' || document.getElementById('customer_name').value == '') &&
	   (document.getElementById('activity_name').value == '') &&
	   (document.getElementById('units').value == '') &&
	   (document.getElementById('job_name').value == '') &&
	   (document.getElementById('notes').value == ''))
	{ 
		location.href = site_url+'/activitysheet';
		return;
	}
	display_popup('slip_button');
}
/**
 *  check all he actvity in timesheet locked or not, if locked mark row as locked.
 */
function lock_timesheet(site_url, m)
{
	if(document.getElementById('sync_lock_'+m))
	{
		if(document.getElementById('customer_name_'+m))
		{
			document.getElementById('customer_name_'+m).className = 'text_disabled bold';
			document.getElementById('customer_name_'+m).readOnly  = true;
		}
		
		if(document.getElementById('activity_name_'+m))
		{
			document.getElementById('activity_name_'+m).className = 'text_disabled';
			document.getElementById('activity_name_'+m).readOnly  = true;
		}
	
		if(document.getElementById('job_name_'+m))
		{
			document.getElementById('job_name_'+m).className = 'text_disabled';
			document.getElementById('job_name_'+m).readOnly  = true;
		}
	
		if(document.getElementById('payroll_name_'+m))
		{
			document.getElementById('payroll_name_'+m).className = 'text_disabled';
			document.getElementById('payroll_name_'+m).readOnly  = true;
		}
	
		if(document.getElementById('notes_'+m))
		{
			//document.getElementById('notes_'+m).style.background = '#F2F7F8';
			document.getElementById('notes_'+m).readOnly  = true;
		} 
				
		for(k=0; k<=8; k++)
		{
			if(document.getElementById('unit_'+k+'_'+m))
			{
				//document.getElementById('unit_'+k+'_'+m).style.background = '#F2F7F8';
				document.getElementById('unit_'+k+'_'+m).readOnly  = true;
			}
		}
		
		document.getElementById('cust-check-lock-'+m).innerHTML = "<img style='margin-left:10px;' src='"+site_url+"/media/images/lock.png' alt='Lock activity' />";
	}
}
/**
 * Function :  change_user_form
 * Change admin create user form. If user is employee then personal details will not be editable and else its editable.
*/

function change_user_register_form(val)
{
	if(val == "Contract")
	{
		$("#user_contract").show();
		$('#FirstName').attr("readonly", false); 
		$('#LastName').attr("readonly", false);
	}
	else {
		$("#user_contract").hide();
		$('#FirstName').attr("readonly", true); 
		$('#LastName').attr("readonly", true);
	}
}
/**
@function	:	cancel_editing_user
@description:	Cancel editing form and display alert.
*/
function cancel_editing_user()
{
	document.getElementById('cancel_user_edit').style.display = 'block'; 
	document.getElementById('grayout').style.display = 'block';
}
/**
@function	:	save_user_form
@description:	Submit edit form and update the user.
*/
function save_user_form()
{
	document.getElementById('cancel_user_edit').style.display = 'none'; 
	document.getElementById('grayout').style.display = 'none';
	validateRegistration();
}
function syn_later()
{
	$('.sync-alert').hide();
}
/**
 * Sync all button - get auto id of all activity in a timesheet
 * @param element
 * @return
 */
function sync_submit(element) {

	form	=	document.getElementById('form_sync_activity');
	field = document.createElement('input');
	field.setAttribute('name', element);
	field.setAttribute('type', 'hidden');
	field.setAttribute('value', '1');
	form.appendChild(field);
	form.submit();
}

 /**
  * Sync slip confirmation box- common for sync all, list page and view page
  * @param slip_id
  * @return
  */
function sync_slip_confirm(t, slip_id, view_page) {
	var left = $(t).offset().left;
	var top  = $(t).offset().top; 
	$('.sync-slips-label').text('Sync Slip');
	$('#sync_slip_confirm').css({"left":left-12,"top":top+30});
	$("#sync_slip_confirm").show();
	$("#sync_slip_id").val(slip_id);
	if(view_page == "1") {
		$("#sync_slip_view_page").val(view_page);
	}
}

function sync_selected_slip() {
	slip_id		=	$("#sync_slip_id").val();
	view_page	=	$("#sync_slip_view_page").val();
	if(slip_id == "") {
		sync_submit('syncall');
	} else {
		if(view_page == "1") {
			$("#sync_slip_form").submit();
		} else {
			sync_slip(slip_id);
			$(".sync-slip-confirm").hide();
		}
	}
}

function alert_syncall(t)
{
	var left = $(t).offset().left;
	var top  = $(t).offset().top; 
	$('.sync-slips-label').text('Sync All Slips');
	$('#sync_slip_confirm').css({"left":left-12,"top":top+50});
	$("#sync_slip_confirm").show();
}

//CSS Browser selector
function css_browser_selector(u){var ua=u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1;},g='gecko',w='webkit',s='safari',o='opera',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?'mobile':is('iphone')?'iphone':is('ipod')?'ipod':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win':is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js'];c=b.join(' ');h.className+=' '+c;return c;};css_browser_selector(navigator.userAgent);


function clear_message(id)
{
   
	if(id == 'slip_number' && document.getElementById(id).value == 'type slip number'){
		document.getElementById(id).value = '';
	}else if(id == 'activity_name' && document.getElementById(id).value == 'start typing activity name'){
		document.getElementById(id).value = '';
	}else if(id == 'units' && document.getElementById(id).value == 'type in units or click timer icon to measure time'){
		document.getElementById(id).value = '';
	}else if(id == 'job_name' && document.getElementById(id).value == 'Start typing job name'){
		document.getElementById(id).value = '';
	}else if(id == 'payroll_name' && document.getElementById(id).value == 'Start typing category'){
		document.getElementById(id).value = '';
	}else if(id == 'notes' && document.getElementById(id).value == 'Enter notes'){
		document.getElementById(id).value = '';
	}else if(id == 'customer_name' && document.getElementById(id).value == 'Enter name...'){
		document.getElementById(id).value = '';
	}
	
	
}

function check_boxes(element, input_element)
{
	if(document.getElementById(input_element).checked)
	{
		element.style.backgroundPosition				=	'0 -36';
		document.getElementById(input_element).checked  = true;		
	}else{	
		element.style.backgroundPosition				=	'0 0';
		document.getElementById(input_element).checked  = false;
	}
	var inputs = document.getElementsByName('slip_id[]');
	flag = '0';
	for(i=0;i<inputs.length;i++)
	{
		if(inputs[i].checked){
			flag = '1';
		}
	}

	if(flag == '0'){
		if(document.getElementById('div_sync_1') && document.getElementById('div_sync_2') &&
		   document.getElementById('div_sync_3') && document.getElementById('div_sync_4'))
		{
			document.getElementById('div_sync_1').className	=	'sync-fade';
			document.getElementById('div_sync_2').className	=	'sync-all-fade';
			document.getElementById('div_sync_3').className	=	'sync-fade';
			document.getElementById('div_sync_4').className	=	'sync-all-fade';
		}
	}else{
		if(document.getElementById('div_sync_1') && document.getElementById('div_sync_2') &&
		   document.getElementById('div_sync_3') && document.getElementById('div_sync_4'))
		{
			document.getElementById('div_sync_1').className	=	'sync';
			document.getElementById('div_sync_2').className	=	'sync-all';
			document.getElementById('div_sync_3').className	=	'sync';
			document.getElementById('div_sync_4').className	=	'sync-all';
		}
	}
	
}

/**
	@function :	Delete user alert
**/
function delete_alert(id)
{
	document.getElementById(id).style.display = 'block';
	document.getElementById('grayout').style.display = 'block';
}
/**
	@function :	Delete user from json
**/
function delete_user_submit(form_action)
{
	document.getElementById('register').action	=	form_action;
	$("#register").submit();
}

/**
	@function :	Cancel delete user operation
*/
function cancel_gray_out(id)
{
	document.getElementById(id).style.display = 'none'; 
	document.getElementById('grayout').style.display = 'none';
}

var week_change_index	=	"";
function week_submit(input_ele, flag)
{
	if(timesheet_entry_focus && flag != 1) {
		week_change_index	=	input_ele;
		$("#timesheet_save_alert_week_change").show();
		$("#grayout").show();
	} else {
		if(input_ele == 2) {
			input_ele	=	week_change_index;
		}
		if(input_ele == '1'){
			element = 'next_button';
		}else if(input_ele == '-1'){
			element = 'prev_button';
		}
		week_change_index	=	input_ele;
		form	=	document.getElementById('week_timesheet_form');
		field = document.createElement('input');
		field.setAttribute('name', element);
		field.setAttribute('type', 'hidden');
		field.setAttribute('value', '1');
		form.appendChild(field);
		form.submit(); 
	}
}

/**
Function to clear username and password in login box on focus
**/
var clearMePrevious = '';
$(document).ready(function(){

	// clear username
	// clear input on focus
	
	$('#inp_company').focus(function()
	{	
		if($(this).val()=="Company")
		{
			clearMePrevious = $(this).val();
			$(this).val('');
		}
	});

	// if field is empty afterward, add text again
	$('#inp_company').blur(function()
	{
		if($(this).val()=='')
		{
			$(this).val(clearMePrevious);
		}
	});
	
});


/*
 * Auto complete functionality.
 */
var global_names = [];
var auto_complete_keycode	=	"";
var click_down_arrow	=	false;
/*** get the search string ***/
function main_autocomplete(element, str, names, flag,m, e)
{	
	if(typeof(m) == 'undefined' || m == 'undefined'|| m == 'customer-keydown'|| m == 'payroll-keydown'|| m == 'activity-keydown'|| m == 'job-keydown'){
		m = '';
	}
	names_array = [];
	var  html = '';
	if(typeof(flag) != 'undefined' && flag == 'search'){
		names = global_names;
	}
	if(typeof(str) != 'undefined' && str != ''){
		for(j=0; j<names.length; j++){
		
			if(element == 'payroll')
			{
				
				if(isNaN(names[j])) {
					name_str	=	names[j].toLowerCase();
				} else {name_str	=	names[j]+"";}
				if(name_str.indexOf(str) == 0) {
					names_array.push(names[j]);
				}
			}else if(element == 'customer'){
				
				if(isNaN(names[j]['name'])) {
					name_str	=	names[j]['name'].toLowerCase();
				} else {name_str	=	names[j]['name']+"";}
				
				if(name_str.indexOf(str.toLowerCase()) == 0) {
					names_array.push(names[j]);
				}
			}else {
				if(isNaN(names[j]['id'])) {
					name_str	=	names[j]['id'].toLowerCase();
				} else {name_str	=	names[j]['id']+"";}
				if(name_str.indexOf(str.toLowerCase()) == 0) {
					names_array.push(names[j]);
				}
			}

		}		
	}else{
		names_array = names;
	}
	if(names_array.length <=0 ){
		select_pop_result = 0;
		html += "<li><div class='left col-left'>No result available</div></li>";
	}
	else if( auto_complete_keycode != 8 && auto_complete_keycode != 46 ) { // delete key and backspace
		select_pop_result = 1;
		//auto_fill(escape(element),escape(names_array[0]),'',m);
		if(element == 'payroll' && str.length >= 1){
			
			if( str.length == 1 ) {
					auto_fill(escape(element),escape(names_array[0]),'',m, 1);
				
			} else {
				auto_fill(escape(element),escape(names_array[0]),'',m, 1);
			}
			
		if(!($('.normal-screen-version').css('display') == 'none')) {
			timesheet_form_id	=	"create_time_0";
		} else {
			timesheet_form_id	=	"phone_create_time_0";
		}
		
			message 		= $('#'+timesheet_form_id+' #'+element+"_name"+m);
			
			filled_value	=	escape(names_array[0]);
			
			createSelection(message, str.length, filled_value.length);
			
		}
		else if(str.length >= 1) {
			if( str.length == 1 ) {
				auto_fill(escape(element),escape(names_array[0]['name']),escape(names_array[0]['id']),m, 1);
				
			} else {
				auto_fill(escape(element),escape(names_array[0]['name']),escape(names_array[0]['id']),m, 1);
			}
			
			if(element == "job" && m == "") {
				if(!($('.normal-screen-version').css('display') == 'none')) {
					timesheet_form_id	=	"create_time_0";
				} else {
					timesheet_form_id	=	"phone_create_time_0";
					
				}
				
				message = $('#'+timesheet_form_id+' #'+element+m);
				
			} else {
				if(!($('.normal-screen-version').css('display') == 'none')) {
					timesheet_form_id	=	"create_time_0";
				} else {
					timesheet_form_id	=	"phone_create_time_0";
				}
				
				message = $('#'+timesheet_form_id+' #'+element+"_name"+m);
				
			}
	        // Select a portion of text
			if(element == "customer") {
				filled_value	=	escape(names_array[0]['name']);
			} else {
				filled_value	=	escape(names_array[0]['id']);
			}
	        createSelection(message, str.length, filled_value.length);
		}
		val_length	=	($('#search_text').val()).length;
		field_id 	= 	document.getElementById("search_text");
		foucs_notes_end(field_id, val_length);
	}
			
	var rec_id 				= $('#customer_val').val();
	var rec_id_timesheet 	= $('#customer_val_0').val();
	var linked_job 			= $('.linked_job').val();
	
	for( i=0; i<names_array.length; i++ ){
		if(element == 'payroll'){
			html += "<li class='data-act' onclick=auto_fill('"+escape(element)+"','"+escape(names_array[i])+"','','"+m+"') >";
		}
		else if(element == 'job'){
			if(((rec_id == names_array[i]['customer_id']) && (linked_job=='1')) || ((rec_id_timesheet == names_array[i]['customer_id']) && (linked_job=='1'))){
				html += "<li class='data-act' onclick=auto_fill('"+escape(element)+"','"+escape(names_array[i]['name'])+"','"+escape(names_array[i]['id'])+"','"+m+"') >";
			}
			else if(linked_job=='0'){
				html += "<li class='data-act' onclick=auto_fill('"+escape(element)+"','"+escape(names_array[i]['name'])+"','"+escape(names_array[i]['id'])+"','"+m+"') >";
			}
			
		}
		/*else if (element == 'customer'){
			html += "<li class='data-act' onclick=get_show_jobs('sync','','');auto_fill('"+escape(element)+"','"+escape(names_array[i]['name'])+"','"+escape(names_array[i]['id'])+"','"+m+"') >";
		}*/
		else{
			html += "<li class='data-act' onclick=auto_fill('"+escape(element)+"','"+escape(names_array[i]['name'])+"','"+escape(names_array[i]['id'])+"','"+m+"') >";
		}
		if(element == 'customer')
		{
			html += "<div class='left col-left'>"+names_array[i]['name']+"</div>";
		}else if(element == 'payroll'){
			html += "<div class='left col-left'>"+names_array[i]+"</div>";
						
		}
		else if(element == 'job'){
			if(((rec_id == names_array[i]['customer_id']) && (linked_job=='1')) || ((rec_id_timesheet == names_array[i]['customer_id']) && (linked_job=='1'))){
				html += "<div class='left col-left1'>"+names_array[i]['id']+"</div>";
				html += "<div class='right col-right1'>"+names_array[i]['name']+"</div>";
			}
			else if(linked_job=='0'){
				html += "<div class='left col-left1'>"+names_array[i]['id']+"</div>";
				html += "<div class='right col-right1'>"+names_array[i]['name']+"</div>";
			}
			
		}
		else{
			html += "<div class='left col-left1'>"+names_array[i]['id']+"</div>";
			html += "<div class='right col-right1'>"+names_array[i]['name']+"</div>";
		}
		
		html += "</li>";
	}	
	if(flag != 'search')
	global_names = names_array;
	
	if(names_array.length>0){
		html += "";
		if(flag != 'search')		// not update the search box.
		{
			document.getElementById('div_search_text').innerHTML = "<input type='text' class='search' id='search_text' "+
																" onkeyup= main_autocomplete('"+element+"',this.value,'','search','"+m+"') "+
																"/>";		
		}
	}
	if(click_down_arrow) {
		document.getElementById('popup_content').innerHTML = html;
		if((document.getElementById('popup_content').innerHTML) == ''){
			document.getElementById('popup_content').innerHTML = "<li class='center-aligned line-height-36 heavy'>No Linked Jobs</li>";
		}
	}
	$('#popup_content li:last').css('cssText','background:#ffffff');
	$('#popup_content li:last').css('cssText','border-bottom:1px solid #CED1D2');
}

function createSelection(field, start, end) {
    if( field.createTextRange ) {
        var selRange = field.createTextRange();
        selRange.collapse(true);
        selRange.moveStart('character', start);
        selRange.moveEnd('character', end);
        selRange.select();
    } else if( field.setSelectionRange ) {
        field.setSelectionRange(start, end);
    } else if( field.selectionStart ) {
        field.selectionStart = start;
        field.selectionEnd = end;
    }
    field.focus();
}

/**
 * Autofill the customer or activity popup value in input field
 * @param element: element name - popup
 * @param value 
 * @param id : element id
 * @param m : element identifier
 * @return
 */
function auto_fill(element, value, id, m, flag)
{
	if(typeof(m) == 'undefined' || m == 'undefined'|| m == 'customer-keydown'|| m == 'payroll-keydown'|| m == 'activity-keydown'|| m == 'job-keydown'){
		m = '';
	}
	if(!($('.normal-screen-version').css('display') == 'none')) {
		timesheet_form_id	=	"create_time_0";
	} else {
		timesheet_form_id	=	"phone_create_time_0";
	}
	if(element == "customer")
	{
		document.getElementById('customer_val'+m).value = unescape(id); //fixed
		document.getElementById(unescape(element)+"_name"+m).value = unescape(value);  //fixed
		$("#"+timesheet_form_id+" #customer_val"+m).val(unescape(id));
		$("#"+timesheet_form_id+" #"+unescape(element)+"_name"+m).val(unescape(value));
		if($('.is_admin').val() == 0 && $('.employee-popup').is(":visible")){
			$('.employee-popup').find('.names-popup-list').click();
		}
	}
	else if(element != 'payroll') {
		document.getElementById(unescape(element)+""+m).value = unescape(id);			//fixed // store the id.
		document.getElementById(unescape(element)+"_name"+m).value = unescape(id);	//fixed
		$("#"+timesheet_form_id+" #"+unescape(element)+m).val(unescape(id));
		$("#"+timesheet_form_id+" #"+unescape(element)+"_name"+m).val(unescape(id));
	}
	else {
		document.getElementById(unescape(element)+"_name"+m).value = unescape(value);	//fixed  // store the name.
		$("#"+timesheet_form_id+" #"+unescape(element)+"_name"+m).val(unescape(value));
	}
	if(element == 'activity'){
		getId("activity", document.getElementById('SITE_URL').value, m);
	}
	
	/**********IE issue changes************/
	if(element == 'job' && m == ''){
		document.getElementById("job").focus();
	} else {
		document.getElementById(unescape(element)+"_name"+m).focus();
	}
	auto_fill_value	=	element;
	//$('.popup, .overlay').hide();
	if(flag != 1) {
	//	$('.popup, .overlay').hide();
	}
	
}
function applyHeight(){
	var wrapheight = $(document).height();
}


function openPopup(el, e, element_name, data_array,m, flag){
	if(e.keyCode != "")
		auto_complete_keycode	=	e.keyCode;
	if(element_name == "customer" || typeof(m)== "undefined" ||  m==0)
		auto_fill_value = "";
	var popupX = popupY = 0;
	if(m == ''){
		document.getElementById("down_"+element_name+m).style.display ='block';
	}
	if(typeof(m)== "undefined" || m == "") {
		non_hourly_id = "#is_non_hourly";
	} else {
		non_hourly_id = "#is_non_hourly"+m;
	}
	
		if (window.event) {  
			   target = window.event.srcElement;  
		} else if (e) {
			   target = e.target;  
		} else return;
		linkCont = $(el).closest('table').width();
		
		linkParentPos = $(el).position();
		
		if( m != 'customer-keydown' && m != 'payroll-keydown' && m != 'activity-keydown' && m != 'job-keydown' && flag != 'down') {
		click_down_arrow = true;
		
		if(typeof(m) == 'undefined'){					// create page	top and left.
			m = '';	
			activityslip_popup(flag,element_name);
		}
		else											// time sheet page top and left
		{
			timesheet_popup(m,flag,element_name);
		}
		
		$('.popup, .overlay').show();
		} else {
			click_down_arrow	=	false;
		}
		
		if(typeof(el.value) != 'undefined'){
			str = el.value; 
		}else{
			str = '';	
		}  
	   
		if(element_name == 'activity'){	
			main_autocomplete('activity', str, data_array,'', m, this);
			$('#popup_title').html('Activities');
		}
		else if(element_name == 'job'){
			main_autocomplete('job', str, data_array,'', m,this);			
			$('#popup_title').html('Jobs');
		}else if(element_name == 'payroll'){
			main_autocomplete('payroll', str, data_array ,'', m,this);			
			$('#popup_title').html('Payroll');
		}else if(element_name == 'customer'){ 
			main_autocomplete('customer', str, data_array ,'', m,this);
			$('#popup_title').html('Customer');
		}

}

function display_down_arrow(element, inp_elem)
{
	arr_element = element.split("_");
	if(document.getElementById('notes_'+arr_element[2]).readOnly)
		return true;
}

function timesheet_popup(rowid,flag,element_name){
	
	rowid = rowid.split('_')[1];
	var popupX = popupY = 0;
	/******For Mobile Screen******/
	if ($('.mobile-screen-version').is(":visible"))
	{
	if(element_name == 'payroll')
		{
		popupX  = ($('.payroll_'+rowid).offset().left-30); 
		popupY  = ($('.payroll_'+rowid).offset().top+24); 
		}
	else if(element_name == 'job')
		{
		popupX  = ($('.job_'+rowid).offset().left-30);
		popupY  = ($('.job_'+rowid).offset().top+24); 
		}
	else if(element_name == 'activity')
		{
		popupX  = ($('.act_'+rowid).offset().left-10);
		popupY  = ($('.act_'+rowid).offset().top+24); 
		}
	else if(element_name == 'customer')
		{
		popupX  = ($('.cust_'+rowid).offset().left-30);
		popupY  = ($('.cust_'+rowid).offset().top+24); 
		}
	$('.popup').css({
		'position': 'absolute',
		'top': popupY,
		'left': popupX,
		'margin':'0 0'
	});
	}
	
	/******For Normal Screen******/
	else
	{
		if(element_name == 'payroll')
		{
		popupX  = ($('#payroll_name_'+rowid).offset().left-30); 
		popupY  = ($('#payroll_name_'+rowid).offset().top+24); 
		}
	else if(element_name == 'job')
		{
		popupX  = ($('#job_name_'+rowid).offset().left-30);
		popupY  = ($('#job_name_'+rowid).offset().top+24); 
		}
	else if(element_name == 'activity')
		{
		popupX  = ($('#activity_name_'+rowid).offset().left-30);
		popupY  = ($('#activity_name_'+rowid).offset().top+24); 
		}
	else if(element_name == 'customer')
		{
		popupX  = ($('#customer_name_'+rowid).offset().left-30);
		popupY  = ($('#customer_name_'+rowid).offset().top+24); 
		}
		$('.popup').css({
			'position': 'absolute',
			'top': popupY,
			'left': popupX,
			'margin':'0 0'
		});
	}
}
$('.data-act').live('click',function(){
	$('.popup').hide();
});

function activityslip_popup(flag,element_name){
}

$(document).ready( function() {
	wrapheightglobal = $(document).height();
	
	$('#search-category-button').click(function(){
		$('.popup-menu-category').show();
		$('.overlay-admin').show();
	});
	

	$('#account-button1').click(function(){
		$('.popup-menu').show();
		$('.overlay-admin').show();
	});

	$('#category').click(function(){
		$('.popup-menu-category').show();
		$('.overlay-admin').show();
	});
	$('.overlay-admin, .popup-menu-category li').click(function(){
		$('.popup-menu-category').hide();
		$('.overlay-admin').hide();
	});
	
	$('#search_user_category').blur(function()
	{
		if($(this).val()=='')
		{
			$(this).val(clearMePrevious);
		}
	});
	
	
	$('.activity_arrow_down').click(function(e) {
		$('#activity_section').append($('.popup'));
		$(this).parent().parent().addClass('yellow');
		$(this).parent().parent().find('.activity_tt_field').addClass('yellow');
		$(this).parent().parent().find('.users-activity').addClass('grey');
		});
	
	$('.down-arrow-job').click(function(e) {
		$('.job .activity-form-validator').append($('.popup'));
		$(this).parent().addClass('yellow');
		$(this).parent().find('.customer_job').addClass('yellow');
		$(this).parent().find('.users-activity').addClass('grey');
	});

	$('.cust-arrow').click(function(e) {
	$('#customer_section').append($('.popup'));
	$(this).parent().parent().addClass('yellow');
	$(this).parent().parent().find('.customer-tag1-textfield').addClass('yellow');
	});
	
	$(document.documentElement).keyup( function(event) {
		if (event.keyCode == 27) { 
			$('.popup').hide();
			hidePopup();
			$('.activity #activity_section').css('background','#ffffff');
			$('#activity_name').removeClass('yellow');
			$('.activity #activity_section #users-activity').css('background','#ffffff');
			$('.job .activity-form-validator').css('background','#ffffff');
			$('#job').css('cssText','background:#ffffff !important');
			$('.job .activity-form-validator #users-activity').css('background','#ffffff');
			$('#customer_section').css('background','#ffffff');
			$('.customer-tag1-textfield').removeClass('yellow');
			
		} else if(event.keyCode == 9 && $('.popup, .overlay').css("display") != "none") {
			//$('.popup').hide();
			//hidePopup();
		}
	});

	$('.overlay, .popup a').click( function() {
		//hidePopup();
	});

	$('.popup-list li').click( function() {
		$('.popup-list li').removeClass('selected');
		$(this).addClass('selected');
	});
	$('.overlay').click( function() { 
		hidePopup();
	});
	var height = $(document).height();
	height = height+'px';
	$('.grayBG').css('cssText','height:'+height);
});


function increaseHeight() {
	var docheight = $(document).height();
	var diffheight = (docheight - wrapheightglobal);
	if (diffheight >= 158){
		var increseheight = (diffheight - 100);
		var wrapheight = wrapheightglobal + increseheight;		
	}
}

function hidePopup() {
//	$('.popup, .overlay').hide();	
}
function applyActivityHeight() {
	var tableheight = $('#form_sync_activity').height();
	var wrapheight = tableheight + 100;
	$('#activity_wrapper, .overlay').css('height', tableheight + 'px');
}

/**
	@Function	:	to2DecWithComma
	@Description:	Convert number to money format
**/
function to2DecWithComma(num)
{
	num="" + Math.floor(num*100.0 + 0.5)/100.0;

	var i=num.indexOf(".");

	if ( i<0 ) num+=".00";
	else {
		num=num.substring(0,i) + "." + num.substring(i + 1);
		i=(num.length - i) - 1;
		if ( i==0 ) num+="00";
		else if ( i==1 ) num+="0";
		else if ( i>2 ) num=num.substring(0,i + 3);
	}
	return num;
}

/**
@Function		:	delete_company();
@Description	:	Display alert message beforte deleting a acompany
**/
function delete_company_alert()
{
	$("#delete_company").show();
	$("#grayout").show();
}

// Added by Manish on 22 Dec 2010 for hiding of popup////

$(document).bind('click', function(e) {
    var $clicked = $(e.target);
    if (!($clicked.parents().attr('id') == "week_list_menu")) { 
    	$('#timesheet_date').hide();
    } 
    if ($clicked.hasClass("ui-icon-circle-triangle-e") || $clicked.hasClass("ui-icon-circle-triangle-w")) { 
    	$('#timesheet_date').show();
    }
});

/**
function for display notes
**/
var notes_enlarge_id="";
function show_notes(id)
{
	if(notes_enlarge_id != id)
		document.getElementById('notes_display_'+id).style.display = "block";
}
/**
 * Expand note textarea while focus on the notes
 * @param id
 * @return
 */
function expand_notes_field(id)
{
	$("#"+id).removeClass('notes-textarea');
	$("#"+id).addClass('notes-expand');
	$('#notes_0').val($('.notes_full_0').val());	
}

/**
 * Auto hide of notes field window
 * @param id
 * @return
 */
function contract_notes_field(id)
{
	$("#"+id).removeClass('notes-expand');
	$("#"+id).addClass('notes-textarea');
	$('.notes_full_0').val($('#notes_0').val());
	notes_value	=	$('.notes_full_0').val();
	if(notes_value != "")
		notes_part_value	=	notes_value.substr(0,15)+"...";
	else notes_part_value = "";
	$('#notes_0').val(notes_part_value);
}

/**
 * 
 * Enlarge notes details in timesheet page
 */
function enlarge_notes(id, count)
{
	editEnable(id, count);
	notes_enlarge_id	=	id;
	$('#notes_'+id).val($('.notes_full_data_'+id).val());
	$("#notes_"+id).removeClass('notes-textarea');
	$("#notes_"+id).addClass('notes-expand');
	val_length	=	($('.notes_full_data_'+id).val()).length;
	field_id = document.getElementById("notes_"+id);
	foucs_notes_end(field_id, val_length);
}

function foucs_notes_end(field, end_pos) {
    if( field.createTextRange ) {
        var selRange = field.createTextRange();
        selRange.collapse(true);
        selRange.moveStart('character', end_pos);
        selRange.moveEnd('character', end_pos);
        selRange.select();
    }
}

/**
 * 
 * 
 */
function minimize_notes(id)
{
	notes_enlarge_id	=	"";
	$('.notes_full_data_'+id).val($('#notes_'+id).val());
	//$('#notes_'+id).val($('.notes_part_data_'+id).val());
	$("#notes_"+id).removeClass('notes-expand');
	$("#notes_"+id).addClass('notes-textarea');
	
	notes_value	=	$('#notes_'+id).val();
	if(notes_value != "")
		notes_part_value	=	notes_value.substr(0,15)+"...";
	else notes_part_value = "";
	notes_part_value_line  = 	notes_part_value.replace(/\n+/g, " "); 
	$('#notes_'+id).val(notes_part_value_line);
}

 /**
  * parse the json to array
  * @param json
  * @return
  */
 function recursive_parse(result,page) {
	    var html = '';
	    var classname='';
	    if(page != '' && page != undefined){
	    	classname =page+"_country_name";
	    }else{
	    	classname ="country_name";
	    }
	    for (var k in result) {
	        html = html + '<li id="'+result[k].id+'" class="'+classname+'">' + result[k].country;// + ' (' + result[k].country + ')';
	        html = html + recursive_parse(result[k].subnav);
	        html = html + '</li>';
	    }
	    
	    return html;
}
function recursive_parsed_array(result) {
	    var array = [];
	    for (var k in result) {
	    	array[k] = result[k].country;
	    	recursive_parsed_array(result[k].subnav);
	    }
	    return array;
}

function customer_refresh_popup(id)
{
	if(id == "refresh_customers_warn") {
		$("#"+id).show();
		var grayout = document.getElementById('grayout');
		
		if (navigator.appName.indexOf("Microsoft")!=-1)
		{
			grayout.style.width		=	document.body.offsetWidth-21;
			grayout.style.height	=	document.body.offsetHeight-4;
		}
		else
		{
			grayout.style.width		=	window.innerWidth;
			grayout.style.height	=	window.innerHeight;
		}
		grayout.style.display	=	'block';
	}
	else if(timesheet_entry_focus) {
		$("#"+id).show();
		var grayout = document.getElementById('grayout');
		
		if (navigator.appName.indexOf("Microsoft")!=-1)
		{
			grayout.style.width		=	document.body.offsetWidth-21;
			grayout.style.height	=	document.body.offsetHeight-4;
		}
		else
		{
			grayout.style.width		=	window.innerWidth;
			grayout.style.height	=	window.innerHeight;
		}
		grayout.style.display	=	'block';
	} else {
		location.href	=	"/activity/importcustomer/3";
	}
}


/***************** signup page functions **************************/

var inp_plan;
var inp_company_name;
var inp_serialnumber;
var inp_UserEmail;

var inp_myPassword;
var inp_confirmpass;
var inp_credit_card_number;
var inp_name;
var inp_last_name;

var inp_cname;
var inp_address;
var inp_address2;
var inp_city;
var inp_state;
var inp_zipcode;
var inp_country;
var inp_phone;
var inp_terms;
var inp_ref_id;
var inp_country_name;
var inp_free_company_id;

function submit_signup_form(site_url)
{
	location.href = "#";
	$("#grayout").show();
	$("#signup_status").show();
	inp_plan 		 = $("#plan").val();
	inp_ref_id		 = $("#resignup_ref_id").val();
	if(inp_plan != free_plan_id) {
		//inp_company_name = $("#company_name").val();
		inp_serialnumber = $("#serialnumber").val();
		inp_UserEmail 	 = $("#UserEmail").val();
		
		inp_myPassword   = $("#myPassword").val();
		inp_confirmpass  = $("#confirmpass").val();
		$.post(site_url+"/admin/signup_validate/1", { plan: inp_plan, serialnumber: inp_serialnumber, UserEmail: inp_UserEmail,
					password: inp_myPassword, confirmpass: inp_confirmpass, ref_id: inp_ref_id},
				    function(data){
				       if(data[0]['error'] == 1) {
				    	    $("#grayout").hide();
				    		$("#signup_status").hide();
				        	$("#error_signup").text(data[0]['error_message']);
			 	        	$("#error_signup").show();
				        	location.href = "#";
				        }
				       else if(data[0]['success'] == 1) { 
				    	   if(data[0]['service_token'] != "") {
				    		   $("#resignup_ref_id").val(data[0]['service_token']);
				    		   inp_ref_id	=	data[0]['service_token'];
				    		   $("#remember_user").show();
				    	   }
				    	   $("#error_signup").hide();
				    	   	$("#grayout").hide();
				    		$("#signup_status").hide();
				    		$("#billing_info").show();
				    		$("#account_info").hide();
				    		$('#signup-info').removeClass('active1');
				    		$('#signup-address').addClass('active1');
				    		$(".cloud").attr("src","/media/images/new-signup/address_bar.png");
				    	   
				       } else {
				    	   alert(data);
				       }
				    }, "json");
	} else {
		inp_UserEmail 	 = $("#free_UserEmail").val();
		inp_myPassword   = $("#free_myPassword").val();
		inp_confirmpass  = $("#confirm_password").val();
		company_name	 = $("#free_UserEmail").val();
		arr_company_name = company_name.split("@");
		inp_company_name = arr_company_name[0];
		inp_company_name = inp_UserEmail;
	//	inp_company_name = $("#free_company_name").val();
		$.post(site_url+"/admin/register_free_user", { plan: inp_plan, company_name:inp_company_name, UserEmail: inp_UserEmail,
			password: inp_myPassword, confirmpass: inp_confirmpass, ref_id: inp_ref_id},
		    function(data){
		       if(data[0]['error'] == 1) {
		    	    $("#grayout").hide();
		    		$("#signup_status").hide();
		        	$("#error_signup").text(data[0]['error_message']);
		        	$("#error_signup").show();
		        	location.href = "#";
		        }
		       else if(data[0]['success'] == 1) { 
		    	   location.href = site_url+"/register/freeuserconfirmation/"+data[0]['active_id'];
		       } else {
		    	   alert(data);
		       }
		    }, "json");
	}
}

	function billing_form_validate(site_url)
	{
		location.href = "#";
		$("#grayout").show();
		$("#signup_status").show();
		if(!($('.search-contents-bill-phone-version').is(':visible')))
		{
		inp_name 		 = $("#name").val();
		inp_last_name	 = $("#lastname").val();
		inp_cname 		 = $("#cname").val();
		inp_address 	 = $("#address").val();
		inp_address2 	 = $("#address2").val();
		inp_city 		 = $("#city").val();
		inp_state 	     = $("#state").val();
		inp_zipcode 	 = $("#zipcode").val();
		inp_country		 = $("#city-selection").val();
		inp_country_name = $("#city-selection option:selected").text();
		inp_phone 		 = $("#phone").val();
		inp_terms 		 = $("#terms").val();
		inp_free_company_id	=	$("#free_conmpany_id").val();
		}
		else if(($('.search-contents-bill-phone-version').is(':visible')))
		{	
			inp_name 		 = $("#billing-fname-textfields-phone-version").val();
			inp_last_name	 = $("#billing-lname-textfields-phone-version").val();
			inp_cname 		 = $("#billing-company-textfields-phone-version").val();
			inp_address 	 = $("#billing-street1-textfields-phone-version").val();
			inp_address2 	 = $("#billing-street2-textfields-phone-version").val();
			inp_city 		 = $("#billing-city-textfields-phone-version").val();
			inp_state 	     = $("#billing-state-textfields-phone-version").val();
			inp_zipcode 	 = $("#billing-zip-textfields-phone-version").val();
			inp_country		 = $("#country-selection").val();
			inp_country_name = $("#country-selection option:selected").text();
			inp_phone 		 = $("#billing-phone_number-textfields-phone-version").val();
			inp_terms 		 = $("#terms").val();
			inp_free_company_id	=	$("#free_conmpany_id").val();

		}
		if(inp_free_company_id != "") {
			inp_serialnumber = $("#free_reg_serialnumber").val();
			inp_UserEmail 	 = $("#free_reg_UserEmail").val();
			inp_myPassword   = $("#free_reg_myPassword").val();
			inp_confirmpass  = $("#free_reg_myPassword").val();
			inp_plan 		 = $("#plan").val();
			inp_ref_id		 = $("#resignup_ref_id").val();
		}
		
		$.post(site_url+"/admin/signup_validate/2", { name: inp_name, serialnumber: inp_serialnumber, lastname:inp_last_name, cname: inp_cname, address: inp_address, address2:inp_address2, 
			city: inp_city, state: inp_state, zipcode: inp_zipcode, country: inp_country, phone: inp_phone, terms: inp_terms},
		    function(data){
		       if(data[0]['error'] == 1) {
		    	    $("#grayout").hide();
		    		$("#signup_status").hide();
		        	$("#error_signup").text(data[0]['error_message']);
		        	$("#error_signup").show();
		        	location.href = "#";
		        }
		       else if(data[0]['success'] == 1) { 
		    	   $("#error_signup").hide();
		    	   $(".cloud").attr("src","/media/images/new-signup/payment_bar.png");
		    	   signup_payment_service(site_url);
		    	} else {
		    	   alert(data);
		    	}
		    }, "json");
	}

	function signup_payment_service(site_url)
	{ 
		$.post(site_url+"/register/submit", { plan: inp_plan, company_name: inp_company_name, serialnumber: inp_serialnumber, UserEmail: inp_UserEmail,
			password: inp_myPassword, confirmpass: inp_confirmpass, name: inp_name, lastname:inp_last_name, cname: inp_cname, address: inp_address, address2:inp_address2,
			city: inp_city, state: inp_state, zipcode: inp_zipcode, country: inp_country, country_name:inp_country_name, phone: inp_phone, terms: inp_terms, ref_id: inp_ref_id, free_company_id:inp_free_company_id},
		    function(data){
				if(data[0]['error'] == 1) {
					if(location.href.indexOf('flag=1') != -1){
		    			location.href = site_url+'/admin/upgrade/rerun_error';
					}
					$("#grayout").hide();
		    		$("#signup_status").hide();
		        	$("#error_signup").text(data[0]['error_message']);
		        	$("#error_signup").show();
		        	$("#billing_info").hide();
		    		$("#account_info").show();
		    		$('#signup-address').removeClass('active1');
		    		$('#signup-info').addClass('active1');
		        	location.href = "#";
		        }
		       else if(data[0]['success'] == 1) {
		    	   // location.href = site_url+"/admin/home/4";
		    	   if(data[0]['free'] == 1) {
		    		   $("#grayout").hide();
			    	   $("#signup_status").hide();
		    		   $("#payment_stream").hide();
			    	   $("#company_info").hide();
			    	   $("#payment_form").hide();
			    	   $("#error_signup").hide();
			    	   $("#error_service").hide();
			    	   $("#signup_success").show();
			    	  
		    	   } else {
			    	   payment_url = data[0]['result']['response_url'];
			    	   $("#grayout").hide();
			    	   $("#signup_status").hide();
			    	   $("#payment_stream").hide();
			    	   $("#company_info").hide();
			    	   $("#billing_info").hide();
			    	   $("#payment_form").show();
			    	   $('#signup-address').removeClass('active1');
			   		   $('#signup-payment').addClass('active1');
			    	   document.getElementById('payment_frame').src = payment_url;
			    	 //  $(".cloud").attr("src","/media/images/new-signup/confirm_bar.png");
		    	   }
		    	  // $("#payment_form_layer").html('<iframe src="'+payment_url+'" id="payment_frame" height="200" width="420" style="border:0px solid #000000; " />');
		       } else {
		    	    $(".error_message").text(data[0]['error_message']);
		        	$(".error_message").show();
		        	$("#grayout").hide();
		    		$("#signup_status").hide();
		        	location.href = "#";
		       }
		    }, "json");
	}
	function update_company(site_url)
	{
		var company_name = $("#company_name").val();
		var serialnumber = $("#serialnumber").val();
		var UserEmail 	 = $("#UserEmail").val();
		
		var myPassword   = $("#myPassword").val();
		var confirmpass  = $("#confirmpass").val();
//		var credit_card_number = $("#credit_card_number").val();
		var name 		 = $("#name").val();
		
		var cname 		 = $("#cname").val();
		var address 	 = $("#address").val();
		var city 		 = $("#city").val();
		var state 	     = $("#state").val();
		var zipcode 	 = $("#zipcode").val();
		var country		 = $("#signup_country_id").val();
		var phone 		 = $("#phone").val();
		var company_id	 = $("#company_id").val();
		
		$.post(site_url+"/admin/register", { company_name: company_name, serialnumber: serialnumber, UserEmail: UserEmail,
					password: myPassword, confirmpass: confirmpass, name: name, cname: cname, address: address,
					city: city, state: state, zipcode: zipcode, country: country, phone: phone, company_id:company_id},
				    function(data){ 
						alert(data);
				        if(data[0]['error'] == 1) {
				        	$(".error_message").text(data[0]['error_message']);
				        	$(".error_message").show();
				        	location.href = "#";
				        }
				       else if(data[0]['success'] == 1) {
				    	   $("#signup_success").show();
				       } else {
				    	    $(".error_message").text(data[0]['error_message']);
				        	$(".error_message").show();
				        	location.href = "#";
				       }
				    }, "json");
	}
	
	function check_plan(siteurl)
	{ 
		if($("#plan").val() == 0)
		{
			$("#error_signup").text("Please select signup plan.");
			$("#error_signup").show();
			$("#error_service").hide();
		}
		else {
			
			$(".error_message").text("");
			$("#payment_stream").hide();
			
			if($("#plan").val() == free_plan_id) { 
				$("#free_user").val(1);
				$("#payment_stream").show();
				
				
				submit_signup_form(siteurl);
			} else {
				$('#signup-plan').removeClass('active');
				$("#account_info").show();
				$('#signup-info').addClass('active1');
				$("#free_user").val(0);
				$(".cloud").attr("src","/media/images/new-signup/acc-info-bar.png");
			}
		}
	}
	
	function showPlan(plan, indx, price)
	{
		$("#plan").val(plan);
		if(price == 0) { 
			$("#free-signup-form").hide();
			$(".free-signup-"+indx).show();
			$('#td-lefttop-'+indx).addClass('border-top border-left');
			$('#td-top-1-'+indx).addClass('border-top');
			$('#td-top-2-'+indx).addClass('border-top');
			$('#td-top-3-'+indx).addClass('border-top');
			$('#td-righttop-'+indx).addClass('border-top border-right');
			
		} else {
			$("#free-signup-form").hide();
			$('.free-signup-border').removeClass('border-top border-left border-right');
		}
	}
	
	function show_billing_form()
	{
		$("#account_info").hide();
		$("#billing_info").show();
		$('#signup-info').removeClass('active1');
		$('#signup-address').addClass('active1');
	}
	
	function change_user_status(chk, userid, site_url, indx)
	{ 
		
		if($("#slected_new_plan").val() != undefined && $("#slected_new_plan").val() != 0) {
			selected_new_plan	=	$("#slected_new_plan").val();
		} else {
			selected_new_plan	=	0;
		}
		
		var statusId=($(chk).attr("id"));
		if ($("#" + statusId).hasClass('active-btn') == true)
		{
			$("#" + statusId).removeClass('active-btn');
			$("#" + statusId).addClass('inactive-btn');
			current_status	=	1;
		}
		else
		{
			$("#" + statusId).removeClass('inactive-btn');
			$("#" + statusId).addClass('active-btn');
			current_status	=	0;
		}
		
		$.post("/admin/changestatus", { user_id: userid, currentstatus: current_status,
												 selected_plan: selected_new_plan},
		    function(data){ 
				//$("#setting_process").hide();
				if(data[0]['success'] == 0) {
					$("#error_display").show();
				} else if(current_status == 1) {
					//$("#error_display").hide();
					$("#total_user_top").text(data[0]['users']);
					$("#total_user_bottom").text(data[0]['users']);
					
					
					if(data[0]['valid_user_count'] == 1) {
						$("#change_plan_downgrade_button").show();
						$("#change_plan_deselect_message_top").hide();
						$("#change_plan_deselect_message_bottom").hide();
						$("#upgrade_plan_popup_message").show();
					} else if(data[0]['valid_user_count'] == 0) {
						$("#change_plan_downgrade_button").hide();
						$("#change_plan_deselect_message_top").show();
						$("#change_plan_deselect_message_bottom").show();
						$("#upgrade_plan_popup_message").show();
						$("#user_to_deactive_top").text(data[0]['plan_user_to_inactive']);
						$("#user_to_deactive_bottom").text(data[0]['plan_user_to_inactive']);
					}
				} else {
					
					//$("#error_display").hide();
					$("#total_user_top").text(data[0]['users']);
					$("#total_user_bottom").text(data[0]['users']);
					if(data[0]['valid_user_count'] == 1) {
						$("#change_plan_downgrade_button").show();
						$("#change_plan_deselect_message_top").hide();
						$("#change_plan_deselect_message_bottom").hide();
						$("#upgrade_plan_popup_message").show();
					} else if(data[0]['valid_user_count'] == 0) {
							$("#change_plan_downgrade_button").hide();
							$("#change_plan_deselect_message_top").show();
							$("#change_plan_deselect_message_bottom").show();
							$("#upgrade_plan_popup_message").show();
							$("#user_to_deactive_top").text(data[0]['plan_user_to_inactive']);
							$("#user_to_deactive_bottom").text(data[0]['plan_user_to_inactive']);
					}
				}
		    }, "json");
	}
	
	function change_rate_status(current_status, userid)
	{ 
		$.post("/admin/change_rate_status", { user_id: userid, currentstatus: current_status},
		    function(data){
				if(data[0]['success'] == 1) {
				}
			}, "json");
	}

	function change_payroll_status(current_status, userid)
	{
		$.post("/admin/change_payroll_status", { user_id: userid, currentstatus: current_status},
		    function(data){
				if(data[0]['success'] == 1) {
				}
			}, "json");
	}
	
	
	function deactivate_user(userid)
	{
		current_status	=	1;
		$.post(site_url+"/admin/changestatus", { user_id: userid, currentstatus: current_status},
		    function(data){ 
				$("#status_"+userid).val(0);
		    }, "json");
	}
	
	function check_downgrade_process(site_url, id)
	{
		$("#setting_process").show();
		if(id == "" || id == undefined) {
			id	=	$("#plan_check").val();
		}
		new_plan	=	id;
		$.post(site_url+"/admin/downgrade", { changed_plan: new_plan},
			    function(data){ 
						$("#setting_process").hide();
						if(data[0]['success'] == 0) {
							$("#error_display").hide();
							$("#company-user-list").show();
							$("#change_plan_overlay").show();
							$("#slected_new_plan").val(new_plan);
							$("#total_user_top").text(data[0]['total_users']);
							$("#total_user_bottom").text(data[0]['total_users']);
							$("#user_limit").text(data[0]['user_limit']);
							$("#user_to_deactive_top").text(data[0]['total_users']-data[0]['user_limit']);
							$("#user_to_deactive_bottom").text(data[0]['total_users']-data[0]['user_limit']);
							$("#upgrade_plan_popup_message").text(data[0]['message']);
							$('#company-user-list').css("display","block");
							$('.portion1').css("display","none");
						} else if(data[0]['success'] == 1) {
							$("#error_display").hide();
							$("#change_plan_confirm_button").show();
							if(data[0]['upgrade'] == 1) {
								$("#plan_option_button").text("Upgrade");
								$("#upgrade_current_plan").val(1);
								$('#company-user-list').css("display","none");
								$('.portion1').css("display","block");
								
							} else {
								$("#plan_option_button").text("Downgrade");
								$("#upgrade_current_plan").val(0);
								$('#company-user-list').css("display","none");
								$('.portion1').css("display","block");
							}
							$("#company-user-list").hide();
							$("#change_plan_overlay").hide();
						}
						$("#change_plan_message").text(data[0]['message']);
			    }, "json");
	}	
	function submit_upgrade_form()
	{
		document.getElementById('change_plan').submit();
	}
	
	function upgrade_plan_service(plan)
	{
		$("#new_plan").val(plan);
	}
	
	function activate_all_users(site_url, changeplan)
	{
		$.post(site_url+"/admin/activate_all_user", { id: 1},
			    function(data){ 
					
						if(data[0]['success'] == 0) {
							$("#error_display").show();
							return false;
						} else if(data[0]['success'] == 1) { //alert(11);
							
							$("td.active-status-label span.span-label-status label.active").text("Active");
							$("td.active-status-label span.span-label-status label.inactive").text("Active");
							$('td.active-status-label span.span-label-status label').removeClass('inactive');
							$('td.active-status-label span.span-label-status label').addClass('active');
							$('.active-status-label span.span-label-check label').addClass('checked');
							$('td.active-status-label input[type=checkbox]').attr('value','1');
							$('.btn-inac').removeClass('inactive-btn');
							$('.check_user_status').attr('checked',true);
							if(changeplan == 1) {
								$("#change_plan_downgrade_button").hide();
								$("#change_plan_deselect_message_top").show();
								$("#change_plan_deselect_message_bottom").show();
								$("#upgrade_plan_popup_message").show();
							}
						}
			    }, "json");
	}
	function deactivate_all_users(site_url, changeplan)
	{
		$.post(site_url+"/admin/deactivate_all_user", { id: 1},
			    function(data){ 
						if(data[0]['success'] == 0) {
							$("#error_display").show();
							return false;
						} else if(data[0]['success'] == 1) {
							$("#success_display").text("All the users are deactivated.");
							
							$("td.active-status-label span.span-label-status label.active").text("Inactive");
							$("td.active-status-label span.span-label-status label.inactive").text("Inactive");
							$('td.active-status-label span.span-label-status label').removeClass('active');
							$('td.active-status-label span.span-label-status label').addClass('inactive');
							$('.active-status-label span.span-label-check label').removeClass('checked');
							$('.btn-inac').addClass('inactive-btn');
							$('td.active-status-label input[type=checkbox]').attr('value','0');
							$('.check_user_status').attr('checked',false);							

							if(changeplan == 1) {
								$("#change_plan_downgrade_button").show();
								$("#change_plan_deselect_message_top").hide();
								$("#change_plan_deselect_message_bottom").hide();
								$("#upgrade_plan_popup_message").show();
							}
						}
			    }, "json");
	}
	
	function search_category_fill(name, field) 
	{
		$("#search_user_category_field").val(field);
		$("#search_user_category_field_name").val(name);
		$("#category_selected_field").text(name);
		$("#category_selected_field").text(name);
		$(".popup-menu-category").hide();
		if($("#search_user_category_field").val() != "" && $("#search_user_category_field").val() != "Search...") {
			$("#search-user").submit();
		}
	}
	
	function clear_search_field()
	{
		$("#search_user_category").val('');
	}
	
	function submit_search_user(formid, searchid)
	{
		if($("#"+searchid).val() != "" && $("#"+searchid).val() != "Search...") 
		{
			$("#"+formid).submit();
		}
	}
/********** Signup page functions till here ****************/
	
	function timesheet_save_warning(url, id)
	{
		if(timesheet_entry_focus) {
			$("#"+id).show();
			$("#grayout").show();
		} else {
			location.href= url+"/activitysheet"; 
		}
	}
	
	
	function cancel_form(id)
	{
		$("#"+id).show();
		$("#grayout").show();
	}
	
	function cancel_edit_user_alert(id)
	{
		$("#"+id).hide();
		$("#grayout").hide();
	}

	function save_edited_timesheet_row(url)
	{
		if(timesheet_edited_row_id == "") {
			location.href= url+"/timesheet"; 
		} else {
			$("#grayout").hide();
			$("#refresh_customers_warn_timesheet").hide();
			timesheet_validation(timesheet_edited_row_id);
		}
	}

	function api_key_form()
	{
		$("#grayout").show();
		$("#api_key_form").show();
	}
	
	function cancel_api_key_form()
	{
		$("#grayout").hide();
		$("#api_key_form").hide();
	}
	
	function save_api_key(site_url)
	{
		api_val	=	$("#api_key").val();
		if(api_val == "") {
			$("#api_error").text("Please enter valid API key");
			$("#api_error").show();
		} else {
			$.post(site_url+"/admin/save_api_key", { api_key: api_val},
				    function(data){
						if(data[0]['error'] == 1) {
							$("#api_error").show();
							$("#api_error").text(data[0]['description']);
						} else if(data[0]['success'] == 1) {
							$("#api_key_form").hide();
							$("#api_key_form_success").show();
						}
				    }, "json");
		}
	}
	
	function go_back_to_welcome()
	{
		$("#grayout").hide();
		$("#api_key_form_success").hide();
	}

	function submit_email_support(site_url)
	{
		fname	=	$("#es_firstname").val();
		lname	=	$("#es_lastname").val();
		email	=	$("#es_email").val();
		snum	=	$("#es_serial_number").val();
		desc	=	$("#es_description").val();
		$("#grayout").show();
		$("#submit_process").show();
		$("#es_cancel_confirm").hide();
		$.post(site_url+"/company/submit_email_support", { firstname:fname,
					lastname:lname, email:email, serialnumber:snum, description: desc},
				    function(data){
						if(data[0]['error'] == 1) {
							$("#es_error").show();
							$("#es_error").text(data[0]['error_message']);
							$("#grayout").hide();
							$("#submit_process").hide();
						} else if(data[0]['success'] == 1) {
							$("#submit_process").hide();
							$("#es_error").hide();
							$("#es_success").show();
						}
				    }, "json");
	}
	
	
	function cancel_email_support()
	{
		$("#grayout").show();
		$("#es_cancel_confirm").show();
	}

	function submit_email_supportadmin(site_url)
	{
		fname	=	$("#es_admin_firstname").val();
		lname	=	$("#es_admin_lastname").val();
		email	=	$("#es_admin_email").val();
		snum	=	$("#es_admin_snum").val();
		desc	=	$("#es_admin_description").val();
		$("#grayout").show();
		$("#submit_process_admin").show();
		$.post(site_url+"/admin/submit_email_supportadmin", { firstname:fname,
					lastname:lname, email:email, serialnumber:snum, description: desc},
				    function(data){
						if(data[0]['error'] == 1) {
							$("#es_error").show();
							$("#es_error").text(data[0]['error_message']);
							$("#grayout").hide();
							$("#submit_process_admin").hide();
						} else if(data[0]['success'] == 1) {
							$("#submit_process_admin").hide();
							$("#es_error").hide();
							$("#es_success_admin").show();
						}
				    }, "json");
	}

	var modify_expire_company_id	=	"";
	function show_modify_expire_date_form(company_id)
	{
		modify_expire_company_id	=	company_id;
		$("#grayout").show();
		$("#modify_expire_form").show();
	}
	
	function modify_expire_date(site_url)
	{
		$("#signup_status").show();
		date_to_add	=	$("#expire_date").val();
		$.post(site_url+"/admin/modify_company_expire_date", { company_id: modify_expire_company_id,
			date_to_add: date_to_add},
		    function(data){
				if(data[0]['error'] == 1) {
					$("#exprie_error").show();
					$("#exprie_error").text(data[0]['error_message']);
					$("#grayout").hide();
					$("#signup_status").hide();
				} else if(data[0]['success'] == 1) {
					$("#signup_status").hide();
					$("#exprie_error").hide();
					$("#modify_expire_form").hide();
					$("#modify_expire_success").show();
				}
		    }, "json");
	}
	
	function cancel_admin_popup(form_id)
	{
		$("#grayout").hide();
		$("#"+form_id).hide();
	}

	function resume_suspend_user_plan(siteurl, company_id, flag)
	{
		$("#grayout").show();
		$("#resume_suspend_plan").show();
		location.href="#";
		if(flag == 1) { //if resume plan
			url		=	siteurl+"/admin/resume/"+company_id;
			$("#resume_suspend_text").text('Resume');
			$("#resume_suspend_button").text('Resume');
			$("#resume_suspend_button").attr("href",url);
		} else { //suspend plan
			url		=	siteurl+"/admin/suspend/"+company_id;
			$("#resume_suspend_text").text('Suspend');
			$("#resume_suspend_button").text('Suspend');
			$("#resume_suspend_button").attr("href",url);
		}
	}

	function delete_slip_warn(t, slip_id)
	{
		var left = $(t).offset().left; 
		var top  = $(t).offset().top; 
		$('#delete_slips_confirm').css({"left":left-110,"top":top+40});
		$("#delete_slips_confirm").show();
		$("#delete_slip_id").val(slip_id);
	}
	
	function refresh_list_confirm(t) {
		if($(window).width()<720){
		var left = $('.btn-refresh').offset().left; 
		var top  = $('.btn-refresh').offset().top; 
		$('#refresh_list_confirm').css({"left":left-2,"top":top+43});
		$("#refresh_list_confirm").show();
		}
		else{
		var left = $('.list-refresh').offset().left; 
		var top  = $('.list-refresh').offset().top; 
		$('#refresh_list_confirm').css({"left":left-20,"top":top+35});
		$("#refresh_list_confirm").show();
		}
	}
	
	
	function delete_activity_slip() {
		var slip_id		=	$("#delete_slip_id").val();
		location.href	=	"/activity/delete/"+slip_id;	
	}
	
	

	function alert_sync_selected()
	{
		$("#grayout").show();
		var $b 		= 	$('input[type=checkbox]');
		total_slip	=	$b.filter(':checked').length;
		$("#sync_selected").show();
		$(".sync-alert-title").text("Activity Slip");
		
	}
	
	function alert_sync_selected_timesheet()
	{
		$("#grayout").show();
		var $b 		= 	$('input[type=checkbox]');
		total_slip	=	$b.filter(':checked').length;
		if(total_slip == 0) {
			$("#sync_selected_timesheet_error").show();
		} else if(total_slip == 1) {
			$("#sync_selected_timesheet").show();
			$(".sync-alert-title-ts").text("Timesheet");
			$(".sync-selected-count").text(total_slip);
		} else {
			$("#sync_selected_timesheet").show();
			$(".sync-alert-title-ts").text("Timesheets");
			$(".sync-selected-count").text(total_slip);
		}
		
	}

	function focus_customer()
	{
		$("#customer_name").focus();
	}

	function prompt_eport_date_selection()
	{	
		$("#grayout").show();
		$("#export_users_date").val("");
		$(".error_message").hide();
		$("#export_date_prompt").show();
	}
	
	function export_user_details()
	{
		if($("#export_users_date").val() == "") {
			$(".error_message").show();
		} else {
			arr_date	=	($("#export_users_date").val()).split('/');
			if(arr_date.lenght < 3) {
				$(".error_message").text("Please enter valid date.");
				$(".error_message").show();
			} else if(arr_date[0]>12 || arr_date[0]<1 || arr_date[1]>31 || arr_date[1] < 1) {
				$(".error_message").text("Please enter valid date.");
				$(".error_message").show();
			} else {
				$("#grayout").hide();
				$("#export_date_prompt").hide();
				$('#export_user_by_date').submit();
			}
		}
	}

	//search user hide and display Search...
	function clear_search_user_text(id)
	{
		if($("#"+id).val()=="Search...")
		{
			clearMePrevious = $("#"+id).val();
			$("#"+id).val('');
		}
	}

	
	function warn_lost_changes_timesheet(edit_row)
	{
		timesheet_save_warning("http://local.timetracker.com", "timesheet_change_alert");
	}
	
	function focus_clicked_timesheet_row(id)
	{
		$("#"+id).hide();
		$("#grayout").hide();
		$("#customer_name_"+edited_row_id).focus();
	}

	
	var input_keys="";// = new Array();
	var date_time_event;
	var cal_months	=	{
							"1" : "Jan",
							"2" : "Feb",
							"3" : "Mar",
							"4" : "Apr",
							"5" : "May",
							"6" : "Jun",
							"7" : "Jul",
							"8" : "Aug",
							"9" : "Sep",
							"10" : "Oct",
							"11" : "Nov",
							"12" : "Dec"
						};
	
	var cal_months_num	=	{
							"Jan" : "1",
							"Feb" : "2",
							"Mar" : "3",
							"Apr" : "4",
							"May" : "5",
							"Jun" : "6",
							"Jul" : "7",
							"Aug" : "8",
							"Sep" : "9",
							"Oct" : "10",
							"Nov" : "11",
							"Dec" : "12" 
						};
				$(document).ready(function(){
					$("#timesheet_date_activity").bind("keyup",function(e){
		    var value = String.fromCharCode(e.keyCode);
		    if(e.keyCode == 38) {
		    	//add 1 day
		    	cur_date		=	this.value;
		    	arr_cur_date	=	cur_date.split(".");
		    	cur_sel_date	=	arr_cur_date[1]+" "+cal_months[arr_cur_date[0]]+" "+arr_cur_date[2];
		    	var dateString = cur_sel_date; // date string
		    	var actualDate = new Date(dateString); // convert to actual date
		    	
		    	var newDate = new Date(actualDate.getFullYear(), actualDate.getMonth(), actualDate.getDate()+1); // create new increased date
		    	// now extract the bits we want to crete the text version of the new date..
		    	var newDateString = (cal_months_num[newDate.toDateString().substr(4,3)])+'.'+('0'+newDate.getDate()).substr(-2) +'.'+ newDate.getFullYear();
		    	this.value = newDateString;

		    }
		    else if(e.keyCode == 40) {
		    	// minus 1 day
		    	cur_date		=	this.value;
		    	arr_cur_date	=	cur_date.split(".");
		    	cur_sel_date	=	arr_cur_date[1]+" "+cal_months[arr_cur_date[0]]+" "+arr_cur_date[2];
		    	var dateString = cur_sel_date; // date string
		    	var actualDate = new Date(dateString); // convert to actual date
		    	
		    	var newDate = new Date(actualDate.getFullYear(), actualDate.getMonth(), actualDate.getDate()-1); // create new increased date
		    	// now extract the bits we want to crete the text version of the new date..
		    	var newDateString = (cal_months_num[newDate.toDateString().substr(4,3)])+'.'+('0'+newDate.getDate()).substr(-2) +'.'+ newDate.getFullYear();
		    	this.value = newDateString;
		    }
		    else if((e.keyCode != 9 && value>=0 && value<=10)) {
		    	input_keys	+=	""+value;
		    } else if(e.keyCode == 191 || e.keyCode == 111) {
		    	input_keys	+=	"/";
		    }
		    if(input_keys != "") {
		    	clearTimeout(date_time_event);
		    	$(".date-process").css("display","inline");
		    	date_time_event = setTimeout("parse_date_values()", 2000);
		    }
		});
	});

	/**
	 * Read input date, parse and display date
	 * @return
	 */
	function parse_date_values()
	{
		var error_flag	=	false;
		arr_input_date	=	input_keys.split("/");
		d				=	new Date();
		
		if(arr_input_date.length > 1) { 
			in1			=	parseInt(arr_input_date[0]);
	    	in2			=	parseInt(arr_input_date[1]);
	    	if(in1 > 12 || isNaN(in1) || isNaN(in2)) {
	    		error_flag	=	true;
	    	}
	    	date_string	=	in1;
	    	inp_date	=	in2;
	    	// check whether user entered year also
	    	if(arr_input_date.length > 2 && arr_input_date[2].length == 4) {
	    		act_year	=	parseInt(arr_input_date[2]);
	    	} else {
	    		act_year	=	d.getFullYear();
	    	}
		} else {
			in1			=	parseInt(input_keys);
	    	inp_date	=	in1;
	    	date_string	=	cal_months_num[d.toDateString().substr(4,3)];
	    	act_year	=	d.getFullYear();
	 	}
		   	
    	if(!error_flag && inp_date!=0) {
    		new_date	=	(date_string)+"."+(inp_date)+"."+act_year;
        	month_days	=	daysInMonth(date_string, act_year);
        	if(inp_date <= month_days) {
        		$("#timesheet_date_activity").val(new_date);
        	}
		}
    	input_keys = "";
    	clearTimeout(date_time_event);
    	$(".date-process").hide();
	}
	
	function daysInMonth(month,year) {
	    return new Date(year, month, 0).getDate();
	}

	var menu_open_flag_admin = false;
	/**
	 * Change user password through admin - form display
	 * @return
	 */
	function reset_password_confirm()
	{
		menu_open_flag_admin	=	true;
		var left = $('#reset_password').offset().left;
		var top  = $('#reset_password').offset().top; 
		$('#reset_password_confirm').css({"left":left-60,"top":top+25});
		$(".error_message").hide();
		$("#pass_reset_process_image").hide();
		$("#reset_password_confirm").show();
		$("#admin_password").val("");
		
	}
	/**
	 * Change user password through admin
	 * @return
	 */
	function reset_user_password()
	{
		var adm_password		=	$("#admin_password").val();
		var	user_id				=	$("#user_id").val();
		$(".loader").show();
		$.post("/admin/reset_user_password", { admin_password: adm_password, 
			 user_id: user_id},
		    function(data){
				if(data[0]['error'] == 1) {
					$(".status").show();
					$(".status").text(data[0]['desc']);
					$(".status").css("color","#FF0000");
					$(".loader").hide();
				} else if(data[0]['success'] == 1) {
					$(".status").hide();
					$(".loader").hide();
					$(".status").show();
					$(".status").text("Password Sent Successfully");
					$(".status").css("color","#4190DD");
				}
		    }, "json");
	}
	
	function change_admin_password_form() {
		$("#payment_gateway_details").hide();
		$(".status").hide();
		menu_open_flag_admin	=	true;
		var left = $('#account-button').offset().left;
		var top  = $('#account-button').offset().top;
		if(left == 0) {
			var left = $('#link-account').offset().left;
			var top  = $('#link-account').offset().top;
			left	-=	50;
		}
		$('#change_admin_password').css({"left":left-70,"top":top+140});
		$(".error_message").hide();
		$("#pass_reset_process_image").hide();
		$("#change_admin_password").show();
	}
	
	function payment_details_form() {
		$("#change_admin_password").hide();
		$(".status1").hide();
		menu_open_flag_admin	=	true;
		var left = $('#account-button').offset().left;
		var top  = $('#account-button').offset().top;
		if(left == 0) {
			var left = $('#link-account').offset().left;
			var top  = $('#link-account').offset().top;
			left	-=	50;
		}
		$('#payment_gateway_details').css({"left":left-120,"top":top+175});
		$(".error_message").hide();
		$("#pass_reset_process_image").hide();
		$("#payment_gateway_details").show();		
	}
		
	function change_admin_password(site_url)
	{
		var cur_password		=	$("#current_password").val();
		var new_password		=	$("#new_password").val();
		var confirm_password	=	$("#confirm_password").val();
		$.post(site_url+"/admin/changepassword", { current_password: cur_password, 
			new_password: new_password, confirm_password: confirm_password, submit:1},
		    function(data){
				if(data[0]['error'] == 1) {
					$(".status").show();
					$(".status").text(data[0]['desc']);
					$(".status").css("color","#FF0000");
					$(".loader").hide();
				} else if(data[0]['success'] == 1) {
					$(".status").hide();
					$(".loader").hide();
					$(".status").show();
					$(".status").text("Password updated");
					$(".status").css("color","#4190DD");
				}
		    }, "json");
	}
	
	function payment_gateway_details(site_url)
	{
		var gateway_id					=	$("#ach_gateway_id").val();
		var	gateway_password			=	$("#ach_gateway_password").val();
		var confirm_gateway_password	=	$("#confirm_gateway_password").val();
		var apli_login_id				=	$("#apli_login_id").val();
		var	transaction_key				=	$("#transaction_key").val();
		$('.loader1').show();
		$.post(site_url+"/admin/enterpaymentdetails", {	ach_gateway_id : gateway_id, 
			ach_gateway_password: gateway_password, confirm_gateway_password: confirm_gateway_password,
			apli_login_id: apli_login_id, transaction_key: transaction_key, submit:1},
		    function(data){
				if(data[0]['error'] == 1) {
					$(".status1").show();
					$(".status1").text(data[0]['desc']);
					$(".status1").css("color","#FF0000");
					$(".loader1").hide();
				} else if(data[0]['success'] == 1) {
					$(".status1").hide();
					$(".loader1").hide();
					$(".status1").show();
					$(".status1").text("Gateway Details Updated");
					$(".status1").css("color","#4190DD");
				}
		    }, "json");
	}
		
	function cancel_form_admin() {
		menu_open_flag_admin			=	false;
		$(".status").show();
		$('.popup-password').hide();
	}
	
	function cancel_payment_form() {
		menu_open_flag_admin			=	false;
		$(".status1").show();
		$('.popup-payment-details').hide();
	}
	
	 /**
	  * 
	  **User remember me function/
	  */
	 function rememebr_login(t) {
		 if($(t).hasClass("remember-user-disable")) {
		 	 $("#login_checkbox_remember_me").val('1');
			 $(t).removeClass("remember-user-disable").addClass("remember-user-enable");			 
		 } else {
		 	 $("#login_checkbox_remember_me").val('0');
			 $(t).removeClass("remember-user-enable").addClass("remember-user-disable");
		 }
	 }
var menu_open_flag	=	false;
$(document).ready(function(){
	$("body").click(function(event){
		if(event.target.id != "account-button" && event.target.id != "link-account" && event.target.id != "reset_password" && event.target.id != "reset_password_confirm" && event.target.id != "forgot_pass" && !menu_open_flag_admin) {
			$('.popup-menu').hide();
			menu_open_flag_admin = false;
		}		
		if(event.target.id != "Add_sales" && event.target.id != "all_sales_label" && event.target.id != "add-item-image" && event.target.id != "all_sales_filter" && event.target.id != "add_sale_menu" && !menu_open_flag
				&& event.target.id != "forgot_pass" && event.target.id != "sales-view-add" && event.target.id != "sales-view-add-anchor" && event.target.id != "sales-view-add-image") {
			$('.add-popup').hide();
			$('.add-popup-input').hide();
			menu_open_flag	=	false;
			menu_open_flag_admin = false;
			
			$('#add-item-image').css("padding-top","4px");
			$('#add-item-image').css("padding-left","8px");
			
			$('.add-option label').css("color","#6a6a6a");
			$('.add-popup').hide();
			$('.add-option').addClass('add-select');
		}
		menu_open_flag	=	false;
		menu_open_flag_admin	=	false;
		
	});
	
	$("#account-button").click(function(){ //normal view
		$('#my-account-menu-normal').show();
	});
	
	$("#link-account").click(function(){ //phone view
		$('#my-account-menu-phone').show();
	});
	
	if(location.href.indexOf('signup')!=-1){
	$.ajax({
		  url		:	'/register/getplanId',
		  dataType	:	'json',
		  success	:	function(data)
		  {
				free_plan_id = data;
		  }
	});
	}
});

function show_menu() {
	$('.add-popup').show();
	menu_open_flag	=	true;
}

function show_admin_menu() {
	$('.add-popup').show();
	menu_open_flag_admin	=	true;
}

function sync_slip(slip_id) {
	$("#active-slip-"+slip_id).hide();
	$("#update-process-"+slip_id).show();
	$("#active-slip-phone-"+slip_id).hide();
	$("#update-process-phone-"+slip_id).show();
	
	$.ajax({
		type: 'POST',
		url : '/activitysheet/sync',
		data: "slip_id="+slip_id+"&sync=1",
		dataType: 'json',
		success: function(r){ 
			if(r[0]['error'] == "1") {
			} else {
				if($("label#label-synced").text() == "Hide Synced") {
					$("#update-process-"+slip_id).hide();
					$("#onfly-synced-slip-"+slip_id).show();
					$("#update-process-phone-"+slip_id).hide();
					$("#onfly-synced-slip-phone-"+slip_id).show();
				} else {
					$("#search-contents-"+slip_id).slideUp();
					$("#search-contents-phone-"+slip_id).slideUp();
				}
			}
			reload_activity_list();
		}
	});
}
var item_index	=	0;
function close_item(id) {
	total_items	=	(total_items-1);
	$("#total_items").val(total_items);
	$('#'+id).remove();
	calculate_sub_total();
}

// Function to calculate item total
function calculate_item_total(id) {
	var qty		=	parseInt(id.find(".quantity").val());
	var price	=	parseFloat(id.find(".item_price").val());
	if(qty == "" || price == "" || isNaN(qty) || isNaN(price)) {
		id.find(".total").val("0.00");
	} else {
		var total = qty*price;
		total = Number(total).toFixed(2);
		id.find(".total").val(total);
	}
	calculate_sub_total();
	return true;
}

//Function to calculate item total
function calculate_item_total1(htmlobj) {
	var obj=$(htmlobj);
	var gparent=obj.parent().parent();	
	var qty		=	gparent.find(".quantity").val();
	var price	=	gparent.find(".item_price").val();
	
	if(qty == "" || price == "" || isNaN(qty) || isNaN(price)) {
		gparent.find(".total").val("0.00");
		
	} else {
		var total = qty*price;
		total = Number(total).toFixed(2);
		gparent.find(".total").val(total);
	}
	calculate_sub_total();
	return true;
}

function calculate_service_total(value,htmlobj){
	var obj=$(htmlobj);
	var gparent=obj.parent();
	gparent.find(".original_total_amount").val(value);
	calculate_sub_total();
	return true;
	
}

function calculate_tb_total(id) {
	var qty		=	parseInt(id.find(".units").val());
	var price	=	parseFloat(id.find(".rate").val());
	if(qty == "" || price == "" || isNaN(qty) || isNaN(price)) {
		id.find(".amount").val("0.00");
	} else {
		var amount = qty*price;
		amount = Number(amount).toFixed(2);
		id.find(".amount").val(amount);
	}
	calculate_sub_total();
	return true;
}
// time billing 
function calculate_tb_total1(htmlobj) {
	var obj=$(htmlobj);
	var gparent=obj.parent().parent();
	var qty		=	gparent.find(".units").val();
	var price	=	gparent.find(".rate").val();
	if(qty == "" || price == "" || isNaN(qty) || isNaN(price)) {
		gparent.find(".amount").val("0.00");
	} else {
		var amount = qty*price;
		amount = Number(amount).toFixed(2);
		gparent.find(".amount").val(amount);
	}
	calculate_sub_total();
	return true;
}

// Function to get customer related tax
function fetch_customer_tax(t) {
	if(t != 0) {
		tr_id		=	$(t).closest("tr").attr("id");
		if(t.checked) {
			$("#"+tr_id+" .tax_check_value").val("1");
		} else {
			$("#"+tr_id+" .tax_check_value").val("0");
		}
	}
	customer	=	$("#customer").val();
	if(customer == "") return true;
	else {
		$.ajax({
			type: 'POST',
			url : '/sales/get_customer_related_tax',
			data: "customer_id="+customer,
			dataType: 'json',
			success: function(r){ 
				if(r[0]['error'] == "1") {
					
				} else {
					$("#tax_percentage").val(r[0]['percentage']);
					$("#tax option").filter(function() {
						return $(this).text() == r[0]['code']; 
					}).attr('selected', true);
					var selected_taxcode =r[0]['code'];
					var selected_taxdescription =r[0]['description'];
					var selected_taxpercentage =r[0]['percentage'];
					var selected_taxid =r[0]['id'];
					var taxval = selected_taxcode+' '+selected_taxdescription+' ('+selected_taxpercentage+'%)';
					$('.popup-list-item-1 .tax-value-field').val(taxval);
					$('.popup-list-item-2 .code-rate-value-field').val(selected_taxcode);
					$('.popup-list-item-2 .rate-value-field').val(selected_taxpercentage+'%');
					$('#tax').val(selected_taxid);
					$('#tax_percentage').val(selected_taxpercentage);
					$('#tax_code').val(selected_taxcode);
					$('#tax_drop_down').hide();
					$('#tax_pop_up_container').show();
					calculate_sub_total();
				}
			}
		});
	}	
}

// Function to calculate grand total
function calculate_sub_total() {
	sale_type	=	$("#sale_type").val();
	switch(sale_type) {
		case '1' :	calculate_item_invoice_total();
		   			break;
		case '2' :	calculate_item_invoice_total();
		   			break;
		case '3' : 	calculate_item_invoice_total();
				   	break;
		case '4' :	calculate_service_invoice_total();
		   			break;
		case '5' :	calculate_service_invoice_total();
		   			break;
		case '6' : 	calculate_service_invoice_total();
		   		   	break;
		case '7' :	calculate_time_billing_total();
		   			break;
		case '8' :	calculate_time_billing_total();
		   			break;
		case '9' : 	calculate_time_billing_total();
		   		   	break;
	}
}



// item tax calculation
function us_item_calculation(){ // doing this based on the algorithm which shanthan given - for now I am doing like that only
	var g_sub_total 		= 0,
		g_tax_total 		= 0,
		g_tax_code 			= 0,
		g_tax_percentage	= 0,
		g_freight 			= 0,
		g_freight_tax_total	= 0,
		g_grand_total 		= 0,
		g_balance_due		= 0;
	var qty,
		price,
		total,
		tax_percentage,
		freight_tax,
		freight_enable_us,
		freight_enable,
		paid_today,
		tax					= 0,
		tax_str				= '', 
		freight_local;
		tax_check			= 0;
	if($('#phone_mode').val() == "1") {
		$.Item_Sales.ITEM_CLASS_STR		=	"item-container-phone";
	} else {
		$.Item_Sales.ITEM_CLASS_STR		=	"item-container";
	}
	
	var orig_sub_total = 0;
	var g_tax_inclusive = $('#is_tax_inclusive').val();
	//console.log($("."+$.Item_Sales.ITEM_CLASS_STR+" .name-salesItem1").html());
	// processing each item.
	$("."+$.Item_Sales.ITEM_CLASS_STR+" .name-salesItem1").each(function(){
		
		// get item quantity and price information and perform the calculation
		qty			=	$(this).find(".quantity").val();
		price		=	$(this).find(".item_price").val();console.log($.Item_Sales.ITEM_CLASS_STR);
		total		=	0;
		tax_check	= 	$(this).find(".tax_check_value").val();
		if(qty == "" || price == "" || isNaN(qty) || isNaN(price)) {
			total 	= 0;						
			tax 	= 0;
		} else {
			total = qty*price;
			$(this).find('.original_total_amount').val(qty*price);
			// Calculate grand total.
			g_grand_total += Number(total);
			orig_sub_total+= Number(total);
			$('.original_subtotal_amount').val(orig_sub_total);
			
			// Calculate TAX.
			tax_percentage 	= $(this).find('.tax_applied_percentage').val();
			if(!isNaN(tax_percentage)) {
				tax	= 	round_total((Number(tax_percentage)/100)*total);
			}			
			if(g_tax_inclusive!==undefined && g_tax_inclusive!==null){
				if(g_tax_inclusive==='1'&&tax_check==1){
					total+=tax;
				} 	
			}
		}
		//show total.
		total = Number(total).toFixed(2);
		$(this).find(".total").val(total);
		// Calculate sub-total
		g_sub_total	+= Number(total);
		
		// updating total tax
		if(tax_check==1){ 
			g_tax_total += tax;
			// Add tax to grand total.
			g_grand_total += Number(tax);
		}
		
		console.log('Total amount : '+Number(total) );
		console.log('Total amount with TAX : '+Number(g_tax_total) );
	});
	
	// Show Subtotal
	g_sub_total		=	g_sub_total.toFixed(2);
	$("#subtotal").val(g_sub_total);
	 
	// Calculate & Show freight
	freight_enable 	= $('#freight_check').val();// global
	g_freight		= parseFloat($("#freight").attr("freight_orginal_amt"));
	
	// adding the freight value
	g_grand_total 	= 	Number(g_grand_total)+Number(g_freight);
	if(freight_enable=='1'){
		freight_tax 	= 	$('.tax_applied_percentage_freight').val();
		freight_local	=	g_freight;
		if(freight_tax!==undefined&&freight_tax!==null){
			g_freight_tax_total = round_total((Number(freight_tax)/100)*g_freight);
			if($.Company.COUNTRY == $.USA){//US
				
			} else { // no US			
				// read the enter freight amount check variable if set then set the rule variable.
				// there are 2 rules:
				// rule1: unchange variable or addition.
				// rule2: substraction or unchange variable.
				var chk_freight_entered = $('#freight').attr("amount_changed");// read this from the variable..... By default it is defined as 0
				if(chk_freight_entered==='1'){					
					// change the rule and // write the rule in html tag.
					$('#freight').attr("amount_changed", '0');										
				} else{				
					// follow the rule.
				}
				if(g_tax_inclusive==='1'){  // Its unchange the value on yes //TAX Inclusive
					freight_local+=g_freight_tax_total; 
				} else { // Its subtract the value on no tax inclusive
					// do nothing			
				}
			}
			g_grand_total 	= Number(g_grand_total) +	Number(g_freight_tax_total);
		}
		// add freight again with decimal
		$("#freight").val((Number(freight_local).toFixed(2)));
	}
	
	// show TAXes
	g_tax_total 	+= 	Number(g_freight_tax_total);
	g_tax_total		=	g_tax_total.toFixed(2);
	
	// set total tax amount in hidden field.
	$('#tax_total_amount').val(g_tax_total);
	
	if($.Company.COUNTRY === $.USA){ // for US
		g_tax_code 		=	$('#tax_code').val(); 
		g_tax_percentage=	$('#tax_percentage').val();
		
		console.log('g_tax_code :'+g_tax_code);
		console.log('g_tax_percentage : '+g_tax_percentage);
		if(g_tax_code!== undefined && g_tax_percentage != undefined){
			tax_str ='$&nbsp;'+g_tax_total+'&nbsp;&nbsp;'+g_tax_percentage+'%&nbsp'+g_tax_code; // TAX format for US
		}
		$('.tax_input').html(tax_str);
	} else {	// for Non-US
		$('.tax_input').html('$&nbsp;'+g_tax_total);
	}
	
	// Show Grand Total
	g_grand_total = g_grand_total.toFixed(2);
	$("#total_payment").val(g_grand_total);
	
	// Calculate & Show balance Due
	paid_today = $('#paid_today').val();
	// we will show the balance due for create and edit page - may be we need to find a better way to do this
	g_balance_due = Number(g_grand_total-paid_today).toFixed(2);
	$("#balance_due").val(g_balance_due);
}

//service tax calculation
function us_service_calculation(){
	var g_sub_total 		= 0,
	g_tax_total 		= 0,
	g_tax_code 			= 0,
	g_tax_percentage	= 0,
	g_freight 			= 0,
	g_freight_tax_total	= 0,
	g_grand_total 		= 0,
	g_balance_due		= 0;
	var total,
	tax_percentage,
	freight_tax,
	freight_enable_us,
	freight_enable,
	paid_today,
	tax					= 0,
	tax_str				= '', 
	freight_local;
	tax_check			= 0;
	if($('#phone_mode').val() == "1") {
		$.Service_Sales.SERVICE_CLASS_STR		=	"item-container-phone";
	} else {
		$.Service_Sales.SERVICE_CLASS_STR		=	"item-container";
	}
	
	var orig_sub_total = 0;
	var g_tax_inclusive = $('#is_tax_inclusive').val();
	//console.log($("."+$.Item_Sales.ITEM_CLASS_STR+" .name-salesItem1").html());
	// processing each item.
	$("."+$.Service_Sales.SERVICE_CLASS_STR+" .name-salesItem1").each(function(){
		// get item quantity and price information and perform the calculation
		total		=	$(this).find(".original_total_amount").val();
		total		=	parseFloat(total);
		
		tax_check	= 	$(this).find(".tax_check_value").val();
		if(total == "" || isNaN(total)) {
			total 	= 0;						
			tax 	= 0;
		} else {
			g_grand_total += Number(total);
			orig_sub_total+= Number(total);
			$('.original_subtotal_amount').val(orig_sub_total);
			// Calculate TAX.
			tax_percentage 	= $(this).find('.tax_applied_percentage').val();
			if(!isNaN(tax_percentage)) {
				tax	= 	round_total((Number(tax_percentage)/100)*total);
			}
			
			if(g_tax_inclusive!==undefined && g_tax_inclusive!==null){
				if(g_tax_inclusive==='1'&&tax_check==1){
					total+=tax;
				} 
			}
			
		}
		//show total.
		total = Number(total).toFixed(2);
		$(this).find(".amount-service").val(total);
	
		tax = parseFloat(tax);
		// Calculate sub-total
		g_sub_total	+= Number(total);
		
		// updating total tax
		if(tax_check==1){
			g_tax_total += tax;
			// Add tax to grand total.
			g_grand_total += Number(tax);
		}
		
		//console.log('Total amount : '+Number(total) );
		//console.log('Total amount with TAX : '+Number(g_tax_total));
	});
	
	g_sub_total		=	g_sub_total.toFixed(2);
	$("#subtotal").val(g_sub_total);
	
	// Calculate & Show freight
	freight_enable 	= $('#freight_check').val();// global
	g_freight		= parseFloat($("#freight").attr("freight_orginal_amt"));

	// adding the freight value
	g_grand_total 	= 	Number(g_grand_total)+Number(g_freight);
	if(freight_enable=='1'){
		freight_tax 	= 	$('.tax_applied_percentage_freight').val();
		freight_local	=	g_freight;
		if(freight_tax!==undefined&&freight_tax!==null){
			g_freight_tax_total = round_total((Number(freight_tax)/100)*g_freight);
			if($.Company.COUNTRY == $.USA){//US
				
			} else { // no US			
				// read the enter freight amount check variable if set then set the rule variable.
				// there are 2 rules:
				// rule1: unchange variable or addition.
				// rule2: substraction or unchange variable.
				var chk_freight_entered = $('#freight').attr("amount_changed");// read this from the variable..... By default it is defined as 0
				if(chk_freight_entered==='1'){					
					// change the rule and // write the rule in html tag.
					$('#freight').attr("amount_changed", '0');										
				} else{				
					// follow the rule.
				}
				if(g_tax_inclusive==='1'){  // Its unchange the value on yes //TAX Inclusive
					freight_local+=g_freight_tax_total; 
				} else { // Its subtract the value on no tax inclusive
					// do nothing			
				}
			}
			g_grand_total 	= Number(g_grand_total) +	Number(g_freight_tax_total);
		}
		// add freight again with decimal
		$("#freight").val((Number(freight_local).toFixed(2)));
	}
	
	// show TAXes
	g_tax_total 	+= 	Number(g_freight_tax_total);
	g_tax_total		=	g_tax_total.toFixed(2);
	
	// set total tax amount in hidden field.
	$('#tax_total_amount').val(g_tax_total);
	
	if($.Company.COUNTRY === $.USA){ // for US
		g_tax_code 		=	$('#tax_code').val(); 
		g_tax_percentage=	$('#tax_percentage').val();
		console.log('g_tax_code :'+g_tax_code);
		console.log('g_tax_percentage : '+g_tax_percentage);
		if(g_tax_code!== undefined && g_tax_percentage != undefined){
			tax_str ='$&nbsp;'+g_tax_total+'&nbsp;&nbsp;'+g_tax_percentage+'%&nbsp'+g_tax_code; // TAX format for US
		}
		$('.tax_input').html(tax_str);
	} else {	// for Non-US
		$('.tax_input').html('$&nbsp;'+g_tax_total);
	}
	
	// Show Grand Total
	g_grand_total = g_grand_total.toFixed(2);
	$("#total_payment").val(g_grand_total);
	
	// Calculate & Show balance Due
	paid_today = $('#paid_today').val();
	// we will show the balance due for create and edit page - may be we need to find a better way to do this
	g_balance_due = Number(g_grand_total-paid_today).toFixed(2);
	$("#balance_due").val(g_balance_due);
}

//time-billing tax calculation
function us_timebilling_calculation(){
	var g_sub_total 		= 0,
	g_tax_total 		= 0,
	g_tax_code 			= 0,
	g_tax_percentage	= 0,
	g_grand_total 		= 0,
	g_balance_due		= 0;
	var hrs,
	rate,
	total,
	tax_percentage,
	paid_today,
	tax					= 0,
	tax_str				= '', 
	tax_check			= 0;
	if($('#phone_mode').val() == "1") {
		$.Timebilling_Sales.ITEM_CLASS_STR		=	"item-container-phone";
	} else {
		$.Timebilling_Sales.ITEM_CLASS_STR		=	"item-container";
	}
	
	var orig_sub_total = 0;
	var g_tax_inclusive = $('#is_tax_inclusive').val();
	$("."+$.Timebilling_Sales.ITEM_CLASS_STR+" .name-salesItem1").each(function(){
		hrs			=	$(this).find(".units").val();
		rate		=	$(this).find(".rate").val();
		total		=	0;
		tax_check	= 	$(this).find(".tax_check_value").val();
		if(rate == "" || hrs == "" || isNaN(rate) || isNaN(hrs)) {
			total 	= 0;						
			tax 	= 0;
		} else {
			total = hrs*rate;
			$(this).find('.original_total_amount').val(hrs*rate);
			// Calculate grand total.
			g_grand_total += Number(total);
			orig_sub_total+= Number(total);
			$('.original_subtotal_amount').val(orig_sub_total);
			
			// Calculate TAX.
			tax_percentage 	= $(this).find('.tax_applied_percentage').val();
			
			if(!isNaN(tax_percentage)) {
				tax	= 	round_total((Number(tax_percentage)/100)*total);
			}			
			if(g_tax_inclusive!==undefined && g_tax_inclusive!==null){
				if(g_tax_inclusive==='1'&&tax_check==1){
					total+=tax;
				} 	
			}
		}
		//show total.
		total = Number(total).toFixed(2);
		$(this).find(".amount").val(total);

		g_sub_total	+= Number(total);

		if(tax_check==1){ 
			g_tax_total += tax;
			// Add tax to grand total.
			g_grand_total += Number(tax);
		}
	
		console.log('Total amount : '+Number(total) );
		console.log('Total amount with TAX : '+Number(g_tax_total) );
	});
	g_sub_total		=	g_sub_total.toFixed(2);
	$("#subtotal").val(g_sub_total);
	
	g_tax_total		=	g_tax_total.toFixed(2);
	
	// set total tax amount in hidden field.
	$('#tax_total_amount').val(g_tax_total);
	
	if($.Company.COUNTRY === $.USA){ // for US
		g_tax_code 		=	$('#tax_code').val(); 
		g_tax_percentage=	$('#tax_percentage').val();
		console.log('g_tax_code :'+g_tax_code);
		console.log('g_tax_percentage : '+g_tax_percentage);
		if(g_tax_code!== undefined && g_tax_percentage != undefined){
			tax_str ='$&nbsp;'+g_tax_total+'&nbsp;&nbsp;'+g_tax_percentage+'%&nbsp'+g_tax_code; // TAX format for US
		}
		$('.tax_input').html(tax_str);
	} else {	// for Non-US
		$('.tax_input').html('$&nbsp;'+g_tax_total);
	}
	
	// Show Grand Total
	g_grand_total = g_grand_total.toFixed(2);
	$("#total_payment").val(g_grand_total);
	
	// Calculate & Show balance Due
	paid_today = $('#paid_today').val();
	// we will show the balance due for create and edit page - may be we need to find a better way to do this
	g_balance_due = Number(g_grand_total-paid_today).toFixed(2);
	$("#balance_due").val(g_balance_due);
}


// AUS/CA TAX calculation
function ausca_item_calculation(){
	
}

// Function to calculate item invoice
function calculate_item_invoice_total() {
	var grand_total = 0;
	var subtotal	= 0;
	var tax_total 	= 0;
	
	// calculating tax for US item.
	us_item_calculation();
	
	resize_input_box();
}

$('#paid_today').live('keyup',function() {
	if($(this).val()!=''){
		$('#balance_due').val(Number(parseFloat($('#total_payment').val()) - parseFloat($('#paid_today').val())).toFixed(2));
	}
	resize_input_box();
});

function toFixed(x) {
  if (Math.abs(x) < 1.0) {
    var e = parseInt(x.toString().split('e-')[1]);
    if (e) {
        x *= Math.pow(10,e-1);
        x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
    }
  } else {
    var e = parseInt(x.toString().split('+')[1]);
    if (e > 20) {
        e -= 20;
        x /= Math.pow(10,e);
        x += (new Array(e+1)).join('0');
    }
  }
  return x;
}

// Function to calculate service total
function calculate_service_invoice_total() {
	var grand_total = 0;
	var subtotal	= 0;
	var tax_total 	= 0;
	
	// calculating tax for US item.
	us_service_calculation();
	
	resize_input_box();
}

// Function to calculate time billing invoice total
function calculate_time_billing_total() {
	var grand_total = 0;
	var subtotal	= 0;
	var tax_total 	= 0;
	
	// calculating tax for US item.
	us_timebilling_calculation();
	
	resize_input_box();
}

// Round value
function round_total(price) {
	var newnumber = new Number(price+'').toFixed(parseInt(2));
	return parseFloat(newnumber); // Output the result to the form field (change for your purposes)
}

// Function to display customer address
function apply_customer(street, city, state, zip) {
	$("#address1").val(street+", "+city+", "+state+", "+zip);
	fetch_customer_tax(0);
}

/**Checking for item row (if empty)**/
function check_item_row_empty(obj){
	if($('.'+obj).find('.service-invoice-field-item').val()=='' 
		&& ($('.'+obj).find('.quantity').val()== '0' || $('.'+obj).find('.quantity').val()== '0.00') 
		&& ($('.'+obj).find('.item_price').val()== '0.00' || $('.'+obj).find('.item_price').val()== '0'))
	{
		return true;
	} else {
		return false;
	}
}

/**Checking for item row (if empty)**/
function check_service_row_empty(obj){
	if($('.'+obj).find('.account-field').val()=='' 
		&& ($('.'+obj).find('.amount-service').val()== '0' || $('.'+obj).find('.amount-service').val()== '0.00'))
	{
		return true;
	} else {
		return false;
	}
}

/**Checking for item row (if empty)**/
function check_tb_row_empty(obj){
	if($('.'+obj).find('.timebilling_activity').val()=='' 
		&& ($('.'+obj).find('.units').val()== '0' || $('.'+obj).find('.units').val()== '0.00') 
		&& ($('.'+obj).find('.rate').val()== '0.00' || $('.'+obj).find('.rate').val()== '0'))
	{
		return true;
	} else {
		return false;
	}
}

// function to check ccp = 1 + payment method = 2 if yes then give pop-up and redirect
function check_submit_item(e, details_flag) {
	var ccp				= $('#ccp').val();
	var payment_method 	= $('.payment_type').val();
	if($('#ccp').val() != undefined && $('.payment_type').val() != undefined
			){
		alert('ccp: '+ccp +', payment_method: '+payment_method );			
	}
}

// Function to validate item invoice form
function submit_item_invoice_form(e, details_flag) {
	var sale_number	= $("#sale_number").val();
	var error 		= false;
	var left_img	= 0; 
	var top_img		= 0;
	var append		= '';
	var added_row	= '';
	
	if($('.item-container-phone').is(":visible")){
		append	= '.item-container-phone';
	} else {
		append	= '.item-container';
	}
	
	/**snippet to remove empty row expect 1 row-item.**/
	/**Checking for item row (if empty)**/
	var total_items=$('#total_items').val();
	if(total_items>1){
		for(row=total_items; row>1; row--){
			added_row=append+' .additional_content_'+row;
			if(check_item_row_empty(added_row)){
				$(added_row).remove();
				total_items = total_items - 1;
				$('#total_items').val(total_items);
			} 
		}
	}
	// check Customer 
	$(append+' .popup_validate').each(function(e) {
		error = checkCustomer(this);
		if(error == true){
			return false;
		}
	});
	
	if(details_flag) {
		if($("#paid_today").val() == "0" || $("#paid_today").val() == "" || $("#paid_today").val() == "0.00" ) {
			left_img = $('.paid_today').offset().left;
			top_img  = $('.paid_today').offset().top;
			$('.error-popup-paid-amount').css({"left":left_img+30,"top":top_img-24});
			$('.error-popup-paid-amount').show();
			error	=	true;
		} else { 
			$('.details_tab').val('1');
			if($('.payment_val').val()=='' || $('.payment_input').val()==''){
				$('#payment_validation').show();
				error=true;
			}
		}
	} else {	
		if(($('.payment_val').val()==''  && $("#paid_today").val() != "0.00" && $("#paid_today").val() != "" && $("#paid_today").val() != "0") || ($('.payment_input').val()==''  && $("#paid_today").val() != "0.00" && $("#paid_today").val() != "0" && $("#paid_today").val() != "")){
			$('#payment_validation').show();
			error=true;
		}
		$('.details_tab').val('0');
	}
	if(error == false && details_flag) {
		db_convert();
		$("#item_invoice").submit();
	}
	else if(error == false) {
		var ccp				= $('#ccp').val();
		var payment_method 	= $('.payment_type').val();
		
		if($('#ccp').val() != undefined && $('.payment_type').val() != undefined
				&& ccp == 1 && payment_method == 2 && 
				($("#paid_today").val() != "0.00" && $("#paid_today").val() != "" && $("#paid_today").val() != "0") ){
			// alert with 2 possible 
			if (confirm('Would you like to process a Credit Card Payment?')) {
			    submit_item_invoice_form('', 1);
			} else {
			    // Do nothing!
			}
		}
		db_convert();
		$("#item_invoice").submit();
	} else {
		// Do Nothing
	}
}

// Function to submit service invoice form
function submit_service_invoice_form(e, details_flag) {
	$('.error-popup-paid-amount').hide();
	var sale_number	=	$("#sale_number").val();
	var error 		= false; 
	var left_img	=	"";
	var top_img		=	"";
	var append		=	'';
	var added_row	=	'';
	
	if($('.item-container-phone').is(":visible")){
		append	= '.item-container-phone';
	} else {
		append	= '.item-container';
	}
	
	/**snippet to remove empty row expect 1 row-item.**/
	/**Checking for item row (if empty)**/
	var total_items=$('#total_items').val();
	if(total_items>1){
		for(row=total_items; row>1; row--){
			added_row=append+' .additional_content_'+row;
			if(check_service_row_empty(added_row)){
				$(added_row).remove();
				total_items = total_items - 1;
				$('#total_items').val(total_items);
			} 
		}
	}
	
	$(append+' .popup_validate').each(function() {
		error = checkCustomer(this);
		if(error == true){
			return false;
		}
	});
	
	if(details_flag) {
		if($("#paid_today").val() == "0" || $("#paid_today").val() == "" || $("#paid_today").val() == "0.00" ) {
			left_img = $('.paid_today').offset().left;
			top_img  = $('.paid_today').offset().top;
			$('.error-popup-paid-amount').css({"left":left_img+30,"top":top_img-24});
			$('.error-popup-paid-amount').show();
			error	=	true;
		} else { 
			$('.details_tab').val('1');
			if($('.payment_val').val()=='' || $('.payment_input').val()=='' || $('.payment_type').val()==''){
				$('#payment_validation').show();
				error=true;
			}
		}
	} else {	
		if(($('.payment_val').val()==''  && $("#paid_today").val() != "0.00" && $("#paid_today").val() != "" && $("#paid_today").val() != "0") || ($('.payment_input').val()==''  && $("#paid_today").val() != "0.00" && $("#paid_today").val() != "0" && $("#paid_today").val() != "")){
			$('#payment_validation').show();
			error=true;
			}
		$('.details_tab').val('0');
	}
	if(error == false && details_flag) {
		db_convert();
		$("#service_invoice").submit();
	}
	else if(error == false){
		var ccp				= $('#ccp').val();
		var payment_method 	= $('.payment_type').val();
		if($('#ccp').val() != undefined && $('.payment_type').val() != undefined
				&& ccp == 1 && payment_method == 2 && 
				($("#paid_today").val() != "0.00" && $("#paid_today").val() != "" && $("#paid_today").val() != "0") ){
			// alert with 2 possible 
			if (confirm('Would you like to process a Credit Card Payment?')) {
			    submit_service_invoice_form('', 1);
			} else {
			    // Do nothing!
			}
		}
		db_convert();
		$("#service_invoice").submit();
	} else {}
}

// Function to add freight value
function add_freight() {
	var sub_toal		=	parseFloat($("#subtotal").val());
	var total_payment	=	parseFloat($("#total_payment").val());
	freight				=	parseFloat($('#freight').val());
	if(!isNaN(freight) || freight != 0) {
		sub_toal		+=	freight;
		if($("#freight_check").prop('checked') == true) {
			var tax		=	parseFloat($("#tax_percentage").val());
			freight		+=	(tax/100)*freight;
		}
		total_payment	+=	freight;
		$("#subtotal").val(sub_toal);
		$("#total_payment").val(total_payment);
	}
}


// submit time billing invoice
function submit_timebilling_invoice_form(e, details_flag) {
	customer		=	$("#customer").val();
	sale_number		=	$("#sale_number").val();
	var error 		=	false;
	var left_img	=	0;
	var top_img		=	0;
	var append		=	'';
	var added_row	=	'';
	if($('.item-container-phone').is(":visible")){
		append	= '.item-container-phone';
	} else {
		append	= '.item-container';
	}
	
	/**snippet to remove empty row expect 1 row-item.**/
	/**Checking for item row (if empty)**/
	var total_items=$('#total_items').val();
	if(total_items>1){
		for(row=total_items; row>1; row--){
			added_row=append+' .additional_content_'+row;
			if(check_tb_row_empty(added_row)){
				$(added_row).remove();
				total_items = total_items - 1;
				$('#total_items').val(total_items);
			} 
		}
	}
	
	$(append+' .popup_validate').each(function() {
		error = checkCustomer(this);
		if(error == true){
			return false;
		}
	});
	
	if(details_flag) {
		if($("#paid_today").val() == "0" || $("#paid_today").val() == "" || $("#paid_today").val() == "0.00" ) {
			left_img = $('.paid_today').offset().left;
			top_img  = $('.paid_today').offset().top;
			$('.error-popup-paid-amount').css({"left":left_img+30,"top":top_img-24});
			$('.error-popup-paid-amount').show();
			error	=	true;
		} else { 
			$('.details_tab').val('1');
			if($('.payment_val').val()=='' || $('.payment_input').val()==''){
				$('#payment_validation').show();
				error=true;
				}
		}
	} else {	
		if(($('.payment_val').val()==''  && $("#paid_today").val() != "0.00" && $("#paid_today").val() != "" && $("#paid_today").val() != "0") || ($('.payment_input').val()==''  && $("#paid_today").val() != "0.00" && $("#paid_today").val() != "0" && $("#paid_today").val() != "")){
			$('#payment_validation').show();
			error=true;
			}
		$('.details_tab').val('0');
	}
	if(error == false && details_flag) {
		db_convert();
		$("#timebilling_invoice").submit();
	}
	else if(error == false) {
		var ccp				= $('#ccp').val();
		var payment_method 	= $('.payment_type').val();
		if($('#ccp').val() != undefined && $('.payment_type').val() != undefined
				&& ccp == 1 && payment_method == 2 && 
				($("#paid_today").val() != "0.00" && $("#paid_today").val() != "" && $("#paid_today").val() != "0") ){
			// alert with 2 possible 
			if (confirm('Would you like to process a Credit Card Payment?')) {
				submit_timebilling_invoice_form('', 1);
			} else {
			    // Do nothing!
			}
		}
		db_convert();
		$("#timebilling_invoice").submit();
	} else {}
}

// Function to show sale list page in edit mode
function list_edit_mode() {
	$("#list_edit").hide();
	$("#quick_add").hide();
	$(".list-view-right-arrow").hide();
	$("#list_delete").show();
	$(".list_check").show();
	$("#list_cancel").show();
} 

function submit_payment_form(authorize_flag) {
	var paid_today	=	$("#paid_today").val();
	if(paid_today == "" || paid_today == "0") {
		alert("Please enter amount");
		return false;
	}
	if(authorize_flag == "1") {
		$('#add_payment').append('<input type="hidden" name="authorize_flag" value="1" />');
	}
	$("#add_payment").submit();
}

function confirm_cash_payment() {
	if($("#total_amount").val() == "0" || $("#total_amount").val() == "") {
		alert("Please enter the amount");
		return false;
	}
	$("#pay_by_cash").submit();
}

function select_sale(t, sale_id) {
	check_box_id	=	"sale_check_"+sale_id;
	if($(t).hasClass("select_disable")) {
		$(t).removeClass("select_disable").addClass("select_enable");
		$("#"+check_box_id).removeAttr('checked');
	} else {
		$(t).removeClass("select_enable").addClass("select_disable");
		$("#"+check_box_id).removeAttr('checked');
		$("#"+check_box_id).attr('checked','checked');
	}
}

// Select customer from list
function select_customer(t, customer_id) {
	check_box_id	=	"sale_check_"+customer_id;
	if($(t).hasClass("select_disable")) {
		$(t).removeClass("select_disable").addClass("select_enable");
		$("#"+check_box_id).removeAttr('checked');
	} else {
		$(t).removeClass("select_enable").addClass("select_disable");
		$("#"+check_box_id).removeAttr('checked');
		$("#"+check_box_id).attr('checked','checked');
	}
}

// Select jobs to delete
function select_jobs(t, job_id) {
	check_box_id	=	"sale_check_"+job_id;
	if($(t).hasClass("select_disable")) {
		$(t).removeClass("select_disable").addClass("select_enable");
		$("#"+check_box_id).removeAttr('checked');
	} else {
		$(t).removeClass("select_enable").addClass("select_disable");
		$("#"+check_box_id).removeAttr('checked');
		$("#"+check_box_id).attr('checked','checked');
	}
}

// Function to open forgot password window
function open_forgot_password_window() {
	$("#forgot_password").css("display","block");
	var left = $("#forgot_pass").offset().left;
	var top  = $("#forgot_pass").offset().top; 
	$('#forgot_password').css({"left":left-55	,"top":top+35});
}

function validateEmail(email)
{
 	var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
 	if (reg.test(email)){
 		return true; 
 	}else{
 		return false;
 	}
} 

function submit_forgot_password() {
	var email	=	$("#email-forgot-pass").val();
	$(".status-message .loader").show();
	$(".status-message .status").hide();
	if(email == "") {
		$(".status-message .loader").hide();
		$(".status-message .status").show();
		$(".status-message .status").html("Please enter email");
		$(".status-message .status").css("color", "#FF0000");
		return false;
	}else if(!validateEmail(email)){
		$(".status-message .loader").hide();
		$(".status-message .status").show();
		$(".status-message .status").html("InValid Email");
		$(".status-message .status").css("color", "#FF0000");
		return false;
	}
	$('#forgot_password').hide();
	$(".invalid_msg").show();
	$(".invalid_msg").addClass('loader');// remove the background
	$(".invalid_msg").html('<img src="/media/images/tt-new/ajax-loader-2.gif" />'); // add loader image

	$.ajax({
		type: 'POST',
		url : '/company/forgot_save1', // rolls new link functionality.
		data: 'email='+email,
		dataType: 'json',
		success: function(r){ 
			msg	=	r[0]['msg'];
			if(r[0]['error'] == "1") {
				$(".status-message .loader").hide();
				$(".status-message .status").show();
				$(".status-message .status").html(msg);
				$(".status-message .status").css("color", "#FF0000");
				$(".invalid_msg").removeClass('loader');
				$(".invalid_msg").show();
				$(".invalid_msg").html('<img src="/media/images/tt-new/reset_error.png" />&nbsp;'+msg);
			} else {
				$(".status-message .loader").hide();
				$(".status-message .status").show();
				$(".status-message .status").html(msg);
				$(".status-message .status").css("color", "#4190DD");
				$(".invalid_msg").removeClass('loader');
				$(".invalid_msg").show();
				$(".invalid_msg").html('<img src="/media/images/tt-new/enable.png" />&nbsp;'+msg);
			}
		}
	});
}
var filter_menu		 = false;
var type_selection   = 0;
var layout_selection = 0;
function selectItem(chk,data)
{
	
	var Id=($(chk).attr("id"));
	if(data==1)
	{
		if (($("#" + Id).hasClass('disabled2') == true))
		{alert(1);
		$('.enabled2').removeClass('enabled2');
		$("#" + Id).removeClass('disabled2');
		$("#" + Id).addClass('enabled2');
		layout_selection	=	parseInt(Id.replace("list-layout-", ""));
		}
		else
		{
		$('.enabled2').removeClass('enabled2');
		$("#" + Id).addClass('enabled2');
		layout_selection	=	parseInt(Id.replace("list-layout-", ""));
		}
	}
	if(data==2)
	{
		if (($("#" + Id).hasClass('disabled1') == true))
		{
		$('.enabled1').removeClass('enabled1');
		$("#" + Id).removeClass('disabled1');
		$("#" + Id).addClass('enabled1');
		type_selection	=	parseInt(Id.replace("list-type-", ""));
		}
		else
		{
		$('.enabled1').removeClass('enabled1');
		$("#" + Id).addClass('enabled1');
		type_selection	=	parseInt(Id.replace("list-type-", ""));
		}
	}
	if(($('.second').hasClass('enabled1') == true) && ($('.first').hasClass('enabled2') == true))
	{
		$('.popup-btn-add').show();
		url_id	=	(type_selection*layout_selection);
		
		if(url_id == 4 || url_id == 5 || url_id == 6) {
			url_id	-=3;
		} else if(url_id == 8 || url_id == 10 || (url_id == 12 && layout_selection == 2)) {
			url_id	/=2;
		} else if(url_id == 12 || url_id == 15 || url_id == 18) {
			url_id	= ((url_id/3)+3);
		}
		if(filter_menu) {
			$("#create_sale_url").text("Filter");
			$("#create_sale_url").attr("href","/sales/filter/"+url_id);
		} else {
			$("#create_sale_url div").text("Add");
			/******Condition for selecting item from popup if from both lists one-one is enabled*****/
			if(url_id=='0'){
			if($('.first').hasClass('enabled2') == true)
			{
				var data_1_id = document.getElementsByClassName('enabled2')[0].id;
				layout_selection	=	parseInt(data_1_id.replace("list-layout-", ""));
			}
			if($('.second').hasClass('enabled1') == true)
			{
				var data_2_id = document.getElementsByClassName('enabled1')[0].id;
				type_selection	=	parseInt(data_2_id.replace("list-type-", ""));
			}
			url_id=type_selection*layout_selection;
			if(url_id == 4 || url_id == 5 || url_id == 6) {
				url_id	-=3;
			} else if(url_id == 8 || url_id == 10 || (url_id == 12 && layout_selection == 2)) {
				url_id	/=2;
			} else if(url_id == 12 || url_id == 15 || url_id == 18) {
				url_id	= ((url_id/3)+3);
			}
			}
			$("#create_sale_url").attr("href","/sales/create/"+url_id);
		}
	}
	else if(($('.second').hasClass('disabled1') == true) && ($('.first').hasClass('enabled2') == true))
		{
		$('.popup-btn-add').hide();
		}
	else if(($('.second').hasClass('enabled1') == true) && ($('.first').hasClass('disabled2') == true))
		{
		$('.popup-btn-add').hide();
		}
}

/*method = 1 -- Add button in sales list page
 *method = 2 -- All sales menu in sales list page (sales list filter)
 *method = 3 -- Add button in sale view or edit page
 */
function open_sales_popup(chk, method,last_sale)
{	
			if(method == "2") {
				filter_menu	=	true;
			 
			var left = $('#'+chk).offset().left;
			var top  = $('#'+chk).offset().top;
			$('.add-popup').css({"left":left-40,"top":top+45});
			$('.add-popup').show();
			if($('.first').hasClass('enabled2')==true){$('.first').removeClass('enabled2');
			$('.popup-btn-add').hide();
			}
			else if($('.first').hasClass('disabled2')==true){$('.first').removeClass('disabled2');
			$('.popup-btn-add').hide();
			}
			if($('.second').hasClass('enabled1')==true){$('.second').removeClass('enabled1');
			$('.popup-btn-add').hide();
			}
			else if($('.second').hasClass('disabled1')==true){$('.second').removeClass('disabled1');
			$('.popup-btn-add').hide();
			}
			return true;
		} else {
			filter_menu	=	false;
		}
		if($('.add-option').hasClass('add-select') == true )
		{	
			if($(window).width()> 984){
				var left = $('#'+chk).offset().left;
				var top  = $('#'+chk).offset().top; 
				//$('.add-select').css("border","1px solid #a1c4e2");
				//$('.add-select label').css("color","#3490df");
				$('.add-popup').css({"left":left-70,"top":top+45});
				$('.add-popup .popup-arrow').css('left','68px');
				$('.add-popup').show();
				$('.add-select label').css("padding-top","9px");
				$('.add-option').removeClass('add-select');
				if($('.first').hasClass('enabled2')==true){$('.first').removeClass('enabled2');}
				else if($('.first').hasClass('disabled2')==true){$('.first').removeClass('disabled2');}
				if($('.second').hasClass('enabled1')==true){$('.second').removeClass('enabled1');}
				else if($('.second').hasClass('disabled1')==true){$('.second').removeClass('disabled1');}
			}
			else if($(window).width() > 584 && $(window).width() <= 984){
				var left = $('#'+chk).offset().left;
				var top  = $('#'+chk).offset().top; 
				//$('.add-select').css("border","1px solid #a1c4e2");
				//$('.add-select label').css("color","#3490df");
				$('.add-popup').css({"left":left-35,"top":top+45});
				$('.add-popup .popup-arrow').css('left','68px');
				$('.add-popup').show();
				$('.add-select label').css("padding-top","9px");
				$('.add-option').removeClass('add-select');
				if($('.first').hasClass('enabled2')==true){$('.first').removeClass('enabled2');}
				else if($('.first').hasClass('disabled2')==true){$('.first').removeClass('disabled2');}
				if($('.second').hasClass('enabled1')==true){$('.second').removeClass('enabled1');}
				else if($('.second').hasClass('disabled1')==true){$('.second').removeClass('disabled1');}
			}
			else{
				var left = $('#'+chk).offset().left;
				var top  = $('#'+chk).offset().top; 
				//$('.add-select').css("border","1px solid #a1c4e2");
				//$('.add-select label').css("color","#3490df");
				$('.add-popup .popup-arrow').css('left','8px');
				$('.add-popup').css({"left":left-16,"top":top+38});
				$('.add-popup').show();
				$('.add-select label').css("padding-top","9px");
				$('.add-option').removeClass('add-select');
				if($('.first').hasClass('enabled2')==true){$('.first').removeClass('enabled2');}
				else if($('.first').hasClass('disabled2')==true){$('.first').removeClass('disabled2');}
				if($('.second').hasClass('enabled1')==true){$('.second').removeClass('enabled1');}
				else if($('.second').hasClass('disabled1')==true){$('.second').removeClass('disabled1');}
			}
			$(window).resize(function(){
				if($(window).width()>984){
					var left = $('#'+chk).offset().left;
					var top  = $('#'+chk).offset().top; 
					//$('.add-select').css("border","1px solid #a1c4e2");
					//$('.add-select label').css("color","#3490df");
					$('.add-popup .popup-arrow').css('left','68px');
					$('.add-popup').css({"left":left-70,"top":top+45});
					$('.add-popup').show();
					$('.add-select label').css("padding-top","9px");
					$('.add-option').removeClass('add-select');
					if($('.first').hasClass('enabled2')==true){$('.first').removeClass('enabled2');}
					else if($('.first').hasClass('disabled2')==true){$('.first').removeClass('disabled2');}
					if($('.second').hasClass('enabled1')==true){$('.second').removeClass('enabled1');}
					else if($('.second').hasClass('disabled1')==true){$('.second').removeClass('disabled1');}
				}
				else if($(window).width() > 584 && $(window).width() <= 984){
					var left = $('#'+chk).offset().left;
					var top  = $('#'+chk).offset().top; 
					//$('.add-select').css("border","1px solid #a1c4e2");
					//$('.add-select label').css("color","#3490df");
					$('.add-popup').css({"left":left-35,"top":top+45});
					$('.add-popup .popup-arrow').css('left','68px');
					$('.add-popup').show();
					$('.add-select label').css("padding-top","9px");
					$('.add-option').removeClass('add-select');
					if($('.first').hasClass('enabled2')==true){$('.first').removeClass('enabled2');}
					else if($('.first').hasClass('disabled2')==true){$('.first').removeClass('disabled2');}
					if($('.second').hasClass('enabled1')==true){$('.second').removeClass('enabled1');}
					else if($('.second').hasClass('disabled1')==true){$('.second').removeClass('disabled1');}
				}
				else{
					var left = $('#'+chk).offset().left;
					var top  = $('#'+chk).offset().top; 
					//$('.add-select').css("border","1px solid #a1c4e2");
					//$('.add-select label').css("color","#3490df");
					$('.add-popup .popup-arrow').css('left','8px');
					$('.add-popup').css({"left":left-16,"top":top+38});
					$('.add-popup').show();
					$('.add-select label').css("padding-top","9px");
					$('.add-option').removeClass('add-select');
					if($('.first').hasClass('enabled2')==true){$('.first').removeClass('enabled2');}
					else if($('.first').hasClass('disabled2')==true){$('.first').removeClass('disabled2');}
					if($('.second').hasClass('enabled1')==true){$('.second').removeClass('enabled1');}
					else if($('.second').hasClass('disabled1')==true){$('.second').removeClass('disabled1');}
				}
			});
			if(method=="3"){
				$('.add-popup').css({"left":left-86,"top":top+44});
				$('.add-option').css('border','none');
			}
		}
		else
		{
			$('.add-option').css("border","1px solid #afb3b3");
			$('.add-option').css("border-top","1px solid #ced1d2");
			$('.add-option label').css("color","#6a6a6a");
			$('.add-option label').css("padding-top","9px");
			$('.add-popup').hide();
			$('.add-option').addClass('add-select');
		}
	
		switch (last_sale)
		{
		case '1':
			{
			$('#list-layout-1').addClass('enabled2');
			$('#list-type-4').addClass('enabled1');
			$('.popup-btn-add').css('display','block');
			break;
			}
		case '2':
			{
			$('#list-layout-1').addClass('enabled2');
			$('#list-type-5').addClass('enabled1');
			$('.popup-btn-add').css('display','block');
			break;
			}
		case '3':
			{
			$('#list-layout-1').addClass('enabled2');
			$('#list-type-6').addClass('enabled1');
			$('.popup-btn-add').css('display','block');
			break;
			}
		case '4':
			{
			$('#list-layout-2').addClass('enabled2');
			$('#list-type-4').addClass('enabled1');
			$('.popup-btn-add').css('display','block');
			break;
			}
		case '5':
			{
			$('#list-layout-2').addClass('enabled2');
			$('#list-type-5').addClass('enabled1');
			$('.popup-btn-add').css('display','block');
			break;
			}
		case '6':
			{
			$('#list-layout-2').addClass('enabled2');
			$('#list-type-6').addClass('enabled1');
			$('.popup-btn-add').css('display','block');
			break;
			}
		case '7':
		 	{
			$('#list-layout-3').addClass('enabled2');
			$('#list-type-4').addClass('enabled1');
			$('.popup-btn-add').css('display','block');
			break;
			}
		case '8':
			{
			$('#list-layout-3').addClass('enabled2');
			$('#list-type-5').addClass('enabled1');
			$('.popup-btn-add').css('display','block');
			break;
			}
		case '9':
		 	{
			$('#list-layout-3').addClass('enabled2');
			$('#list-type-6').addClass('enabled1');
			$('.popup-btn-add').css('display','block');
			break;
			}
		 	
		}
		
		$("#create_sale_url").attr("href","/sales/create/"+last_sale);
		$("#create_sale_url div").text("Add");
	}
 function open_sales_filter_popup(chk, method,last_sale)
 {
	 var left = $('#'+chk).offset().left;
	 var top  = $('#'+chk).offset().top; 
	 $('.add-popup1').css({"left":left-40,"top":top+45});
	 $('.add-popup1').show();
	 var link = $(location).attr('href');
	 $('.filter').removeClass('enabled2');
	 if (link.indexOf("filter/147") !=-1) 
	 {
		 $('#filter-layout-2').addClass('enabled2');
	 }
	 else if (link.indexOf("filter/258") !=-1) 
	 {
		 $('#filter-layout-3').addClass('enabled2');
	 }
	 else if (link.indexOf("filter/369") !=-1) 
	 {
		 $('#filter-layout-4').addClass('enabled2');
	 }
	 else
	 {
		 $('#filter-layout-1').addClass('enabled2');
	 }
 }
 /**Change button for filter popup name**/
 $(document).ready(function(){
	 var link = $(location).attr('href');
	 if (link.indexOf("filter/147") !=-1) 
	 {
		 $('.filter_span').text('Invoice');
	 }
	 else if (link.indexOf("filter/258") !=-1) 
	 {
		 $('.filter_span').text('Order');
	 }
	 else if (link.indexOf("filter/369") !=-1) 
	 {
		 $('.filter_span').text('Quote');
	 }
	 else
	 {
		 $('.filter_span').text('All Sales');
	 }
 });
 
 function filterItem(chk,data,filter)
 {
	 var Id=($(chk).attr("id"));
	 if	(($("#" + Id).hasClass('enabled2') == false))
	 {
		 $('.filter').removeClass('enabled2');
		 $("#" + Id).addClass('enabled2');
		 //$('.popup-btn-add').show();
	 }
	 else  if	(($("#" + Id).hasClass('enabled2') == true))
	 {
		 $("#" + Id).removeClass('enabled2');
		 //$('.popup-btn-add').hide();
	 }
	
	if(($('#filter-layout-1').hasClass('enabled2')== true))
	{
		document.getElementById('sale-type-pagination').value = '0';
		$("#filter-layout-1").parent().attr("href","/sales");
	}
	else if(($('#filter-layout-2').hasClass('enabled2')== true))
	{
		document.getElementById('sale-type-pagination').value = '147';
		$("#filter-layout-2").parent().attr("href","/sales/filter/"+filter);
	}
	else if(($('#filter-layout-3').hasClass('enabled2')== true))
	{
		document.getElementById('sale-type-pagination').value = '258';
		$("#filter-layout-3").parent().attr("href","/sales/filter/"+filter);
	}
	else if(($('#filter-layout-4').hasClass('enabled2')== true))
	{
		document.getElementById('sale-type-pagination').value = '369';
		$("#filter-layout-4").parent().attr("href","/sales/filter/"+filter);
	}
	
}

 // customer list  next page
$("#customer-next-page").live("click", function(){
	var next_page		=	(parseInt($("#page-number").val())+1);
	var rowsper_page	=	parseInt($("#view-per-page").val());
	var total_customers	=	parseInt($("#total-customers").val());
	var sort_field		=	$("#sort_field").val();
	var search_customer	=	$('.search_customer').val();
	
	if(search_customer==''){search_customer='000';}
	pages_count	=	parseInt(total_customers/rowsper_page);
	if(total_customers%rowsper_page != 0) pages_count	+=	1;
	if(pages_count < next_page) {
		return false;
	}
	$.ajax({
		type: 'POST',
		url : '/customer/ajax_pagination',
		data: 'page='+next_page+'&rows_per_page='+rowsper_page+'&sort_field='+sort_field+'&search_customer='+search_customer,
		dataType: 'html',
		async:false,
		success: function(r){ 
			$("#customer_list").html(r);
			
			pages_count	=	parseInt(total_customers/rowsper_page);
			if(total_customers%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == next_page) {
				showing_to	=	total_customers;
				$("#customer-next-page").removeClass("next-link").addClass("next-link-inact");
			} else {
				showing_to	=	(next_page*rowsper_page);
			}
		}
	});
	$(".pagination-info").text(((next_page*rowsper_page)-rowsper_page+1)+"-"+(showing_to));
	$("#page-number").val(next_page);
	$("#customer-prev-page").removeClass("prev-link-inact").addClass("prev-link");
});

	//customer list previous page
$("#customer-prev-page").live("click", function(){
	var prev_page		=	(parseInt($("#page-number").val())-1);
	if(prev_page < 1) return false;
	var rowsper_page	=	parseInt($("#view-per-page").val());
	var search_customer	=	$('.search_customer').val();
	var sort_field		=	$("#sort_field").val();
	
	if(search_customer==''){search_customer='000';}
	$.ajax({
		type: 'POST',
		url : '/customer/ajax_pagination',
		data: 'page='+prev_page+'&rows_per_page='+rowsper_page+'&sort_field='+sort_field+'&search_customer='+search_customer,
		dataType: 'html',
		success: function(r){ 
			$("#customer_list").html(r);
			
			$("#customer-next-page").removeClass("next-link-inact").addClass("next-link");
		}
	});
	$("#page-number").val(prev_page);
	$(".pagination-info").text(((prev_page*rowsper_page)-rowsper_page+1)+"-"+(prev_page*rowsper_page));
	if(prev_page == 1) {
		$("#customer-prev-page").removeClass("prev-link").addClass("prev-link-inact");
	}
});

// customer list results per page
$(".customer_result_per_page").live("click", function(){
	var rowsper_page	=	($(this).attr("id")).replace("customer_","");
	var page			=	1;
	var total_customers	=	parseInt($("#total-customers").val());
	var sort_field		=	$("#sort_field").val();
	var sort_order		=	$("#sort_order").val();
	var number_selected	=	$(this).find('a').html().split(' ')['0'];
	var sale_selected	=	$(".slips-selected").text();
	if(number_selected	==	sale_selected){
	$('#dropdown-menu-bottom').hide();
	return false;
	}
	$('.ajax_page .ajax_loader_show').show();
	$.ajax({
		type: 'POST',
		url : '/customer/ajax_pagination',
		data: 'page='+page+'&rows_per_page='+rowsper_page+'&sort_field='+sort_field+'&order='+sort_order,
		dataType: 'html',
		async: false,
		success: function(r){ 
			$("#customer_list").html(r);
			$('.ajax_page .ajax_loader_show').hide();
			
			pages_count	=	parseInt(total_customers/rowsper_page);
			if(total_customers%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == page) {
				$("#customer-next-page").removeClass("next-link").addClass("next-link-inact");
				showing_to	=	total_customers;
			} else {
				$("#customer-next-page").removeClass("next-link-inact").addClass("next-link");
				showing_to	=	rowsper_page;
			}
		}
	});
	if($('#customer_'+rowsper_page).hasClass('all_views') == true){
		$(".slips-selected").text('All');
	}else{
		$(".slips-selected").text(rowsper_page);
	}
	//$(".slips-selected").text(rowsper_page);
	$(".pagination-info").text("1-"+showing_to);
	$("#view-per-page").val(rowsper_page);
	$("#page-number").val("1");
	$("#customer-prev-page").removeClass("prev-link").addClass("prev-link-inact");
});


//job list  next page
$("#jobs-next-page").live("click", function(){
	var next_page		=	(parseInt($("#page-number").val())+1);
	var rowsper_page	=	parseInt($("#view-per-page").val());
	var total_jobs	=	parseInt($("#total-jobs").val());
	var sort_field		=	$("#sort_field").val();
	var search_jobs	=	$(".search_jobs").val();
	if(search_jobs == ''){
		search_jobs ='000';
	}
	pages_count	=	parseInt(total_jobs/rowsper_page);
	if(total_jobs%rowsper_page != 0) pages_count	+=	1;
	if(pages_count < next_page) {
		return false;
	}
	$.ajax({
		type: 'POST',
		url : '/jobs/ajax_pagination',
		data: 'page='+next_page+'&rows_per_page='+rowsper_page+'&search_jobs='+search_jobs+'&sort_field='+sort_field,
		dataType: 'html',
		async: false,
		success: function(r){ 
			$("#jobs_list").html(r);
			
			pages_count	=	parseInt(total_jobs/rowsper_page);
			if(total_jobs%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == next_page) {
				showing_to	=	total_jobs;
				$("#jobs-next-page").removeClass("next-link").addClass("next-link-inact");
			} else {
				showing_to	=	(next_page*rowsper_page);
			}
		}
	});
	$(".pagination-info").text(((next_page*rowsper_page)-rowsper_page+1)+"-"+showing_to);
	$("#page-number").val(next_page);
	$("#jobs-prev-page").removeClass("prev-link-inact").addClass("prev-link");
	
});

// job list previous page
$("#jobs-prev-page").live("click", function(){
	var prev_page		=	(parseInt($("#page-number").val())-1);
	var sort_field		=	$("#sort_field").val();
	var search_jobs	=	$(".search_jobs").val();
	if(search_jobs == ''){
		search_jobs ='000';
	}
	if(prev_page < 1) return false;
	var rowsper_page	=	parseInt($("#view-per-page").val());
	$.ajax({
		type: 'POST',
		url : '/jobs/ajax_pagination',
		data: 'page='+prev_page+'&rows_per_page='+rowsper_page+'&sort_field='+sort_field+'&search_jobs='+search_jobs,
		dataType: 'html',
		success: function(r){ 
			$("#jobs_list").html(r);
			
			$("#jobs-next-page").removeClass("next-link-inact").addClass("next-link");
		}
	});
	$("#page-number").val(prev_page);
	$(".pagination-info").text(((prev_page*rowsper_page)-rowsper_page+1)+"-"+(prev_page*rowsper_page));
	if(prev_page == 1) {
		$("#jobs-prev-page").removeClass("prev-link").addClass("prev-link-inact");
	}
});

// job list results per page
$(".result_per_page").live("click", function(){
	var rowsper_page	=	($(this).attr("id")).replace("jobs_","");
	var page			=	1;
	var total_jobs		=	parseInt($("#total-jobs").val());
	var sort_field		=	$("#sort_field").val();
	var sort_order		=	$("#sort_order").val();
	var number_selected	=	$(this).find('a').html().split(' ')['0'];
	var sale_selected	=	$(".slips-selected").text();
	if(number_selected	==	sale_selected){
	$('#dropdown-menu-bottom').hide();
	return false;
	}
	$('.ajax_page .ajax_loader_show').show();
	$.ajax({
		type: 'POST',
		url : '/jobs/ajax_pagination',
		data: 'page='+page+'&rows_per_page='+rowsper_page+'&sort_field='+sort_field+'&order='+sort_order,
		dataType: 'html',
		async: false,
		success: function(r){ 
			$("#jobs_list").html(r);
			$('.ajax_page .ajax_loader_show').hide();
			
			pages_count	=	parseInt(total_jobs/rowsper_page);
			if(total_jobs%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == page) {
				$("#jobs-next-page").removeClass("next-link").addClass("next-link-inact");
				showing_to	=	total_jobs;
			} else {
				$("#jobs-next-page").removeClass("next-link-inact").addClass("next-link");
				showing_to	=	rowsper_page;
			}
		}
	});
	if($('#jobs_'+rowsper_page).hasClass('all_views') == true){
		$(".slips-selected").text('All');
	}else{
		$(".slips-selected").text(rowsper_page);
	}
	//$(".slips-selected").text(rowsper_page);
	$(".pagination-info").text("1-"+showing_to);
	$("#view-per-page").val(rowsper_page);
	$("#page-number").val("1");
	$("#jobs-prev-page").removeClass("prev-link").addClass("prev-link-inact");
});

// Delete sales conirmation
function delete_sales_cofirm(chk, id, sale_id) {
	
	if(id==1){
		var left = $('#'+chk).offset().left;
		var top  = $('#'+chk).offset().top;
		$('#delete_sales_confirm').css({"left":left-120,"top":top+46});
	} else {
		var left = $('.delete-btn').offset().left;
		var top  = $('.delete-btn').offset().top;
		$('#delete_sales_confirm').css({"left":left-106,"top":top+46});
	}
	$("#delete_sales_confirm").show();
	$("#delete_functionality").val(id);
	$("#delete_box_sale_id").val(sale_id);
}

// Delete after confirmation
function delete_after_confirm_sales() {
	id =	$("#delete_functionality").val();
	switch(id) {
		case '1' : $('#list_view').submit(); break;
		case '2':  sale_id	=	$("#delete_box_sale_id").val();
		  		   location.href= "/sales/delete/"+sale_id;
				   break;
	}
}

//Cancel after confirmation
function cancel_confirmation_box() {
	$(".sync-slip-confirm").hide();
	$(".confirmation-box").hide();
}

//Sync sales conirmation
function sync_confirm(chk, id, sale_id) {
	var x	=	chk;
	if(id==1){
		var left = $('#'+chk).offset().left;
		var top  = $('#'+chk).offset().top;
		$('#sync_sales_confirm').css({"left":left-150,"top":top+40});
	} else {
		var left = $('.synced-synl-all').offset().left;
		var top  = $('.synced-synl-all').offset().top;
		$('#sync_sales_confirm').css({"left":left-110,"top":top+45});
	}
	$("#sync_sales_confirm").show();
	if(x.charAt(0)=='j'){
		$("#sync_functionality").val('3');
	} else if(x.charAt(0)=='c'){
		$("#sync_functionality").val('4');
	} else {
		$("#sync_functionality").val(id);
	}
	$("#sale_id").val(sale_id);
	$('.clicked_id').val(chk);
}

//Sync after confirmation
function sync_after_confirm() {
	id =	$("#sync_functionality").val();
	var clicked_id = $('.clicked_id').val();
	if(clicked_id == 'sync-all'){
		$('.to-be-synced').attr('src','/media/images/tt-new/ajax-loader.gif');
	} else {
		$('#'+clicked_id+' a .to-be-synced').attr('src','/media/images/tt-new/ajax-loader.gif');
	} 
	$('#sync_sales_confirm').hide();
	switch(id) {
		case '1' :	sale_id	=	$("#sale_id").val();
					$.ajax({
						type: 'POST',
						url : '/sales/sync/'+sale_id,
							success: function(r){ 
							$('#'+clicked_id+' a .to-be-synced').attr('src','/media/images/tt-new/refresh1.png');
							$('.'+clicked_id).fadeOut(1000);
							$('.'+clicked_id).remove();
							var synced_value_field = parseInt($('.synced-value-field a').text());
							synced_value_field--;
							$('.synced-value-field a').text(synced_value_field);
							if($('.name-salesItem1').length < 1){
								location.reload(true);
							}
						}
					});
				   	break;
		case '2':  $.ajax({
						type: 'POST',
						url : '/admin/sync_all/',
						success: function(r){
							location.reload(true);
						}
					});
					break;
		case '3':  	sale_id	=	$("#sale_id").val();
					$.ajax({
						type: 'POST',
						url : '/jobs/sync/'+sale_id,
						success: function(r){ 
							$('#'+clicked_id+' a .to-be-synced').attr('src','/media/images/tt-new/refresh1.png');
							$('.'+clicked_id).fadeOut(1000);
							$('.'+clicked_id).remove();
							var synced_value_field = parseInt($('.synced-value-field a').text());
							synced_value_field--;
							$('.synced-value-field a').text(synced_value_field);
							if($('.name-salesItem1').length < 1){
								location.reload(true);
							}
						}
					});
					break;
		case '4':  	sale_id	=	$("#sale_id").val();
					$.ajax({
						type: 'POST',
						url : '/customer/sync/'+sale_id,
						success: function(r){ 
							$('#'+clicked_id+' a .to-be-synced').attr('src','/media/images/tt-new/refresh1.png');
							$('.'+clicked_id).fadeOut(1000);
							$('.'+clicked_id).remove();
							var synced_value_field = parseInt($('.synced-value-field a').text());
							synced_value_field--;
							$('.synced-value-field a').text(synced_value_field);
							if($('.name-salesItem1').length < 1){
								location.reload(true);
							}
						}
					});
					break;
	}
}

//Delete Customer/job conirmation
function delete_customer_job_cofirm(chk, page_id, field_id, type) {
	var left = $('#'+chk).offset().left;
	var top  = $('#'+chk).offset().top; 
	if(page_id == 1){
		$('#delete_customer_jobs_confirm').css({"left":left-120,"top":top+45});
	} else {
		$('#delete_customer_jobs_confirm').css({"left":left-210,"top":top+42});
	}
	$("#delete_customer_jobs_confirm").show();
	$("#delete_functionality").val(page_id);
	$("#field_id").val(field_id);
	$("#type").val(type);
	
}

// Delete after confirmation
function delete_after_confirm() {
	var page_id =	$("#delete_functionality").val();
	switch(page_id) {
		case '1':  $('#list_view').submit(); break;
		case '2':  var type	=	$("#type").val();
				   if(type == "1") { // customer
					   var customer_id	= $("#field_id").val();
					   location.href= "/customer/delete/"+customer_id;
				   } else if(type == "2") { // jobs
					   var job_id	= $("#field_id").val();
					   location.href= "/jobs/delete/"+job_id;
				   }
		  		   break;
	}
}


/*******Popup for Names*******/
$(document).ready(function(){	
	$(".tag-tax-value-arrow").click(function(){
		$('#tax_pop_up_container').hide();
		$('#tax_drop_down').show();
	});
	$("#save_service_sale").click(function(){
		submit_service_invoice_form('', 0);
	});
	$("#details-button-service").click(function(){
		if($(this).attr("value")=='History'){
			//redirect user to payment details page, if history button is clicked.
			var url = $.Global.SITEURL+"/sales/payment_receipt/"+$('#sale_id').val();
			$(location).attr('href',url);
		} else { // by-default: Detail Button
			submit_service_invoice_form('', 1);
		}
	});
	$("#save_item_sale").click(function(){
		submit_item_invoice_form('', 0);
	});
	$("#details-button-item").click(function(){
		if($(this).attr("value")=='History'){
			//redirect user to payment details page, if history button is clicked.
			var url = $.Global.SITEURL+"/sales/payment_receipt/"+$('#sale_id').val();
			$(location).attr('href',url);
		} else { // by-default: Detail Button 
			submit_item_invoice_form('', 1);
		}
	});
	$("#details-button-tb").click(function(){
		if($(this).attr("value")=='History'){
			//redirect user to payment details page, if history button is clicked.
			var url = $.Global.SITEURL+"/sales/payment_receipt/"+$('#sale_id').val();
			$(location).attr('href',url);
		} else { // by-default: Detail Button
			submit_timebilling_invoice_form('', 1);
		}
	});
	
	/*******Pop-up for Account*******/
	var selected_account = '';
	$(".amount").live("focus",function(){ //normal view
		$('.acc-popup').hide();
	});
});

$(document).ready(function(){
	$("#openPaymentPopup1").click(function(){ //normal view
		$('.payment_field').append($('.payment-popup'));
		$('.payment-popup').toggle();
		if($(this).hasClass('selected3')){
			$(this).removeClass('selected3');
		} else {
			$(this).addClass('selected3');
		}
	});
});

 /*******STart :::::::: Script for tax enable*******/
$(".tax-chk").live("click",function(){ 
	if($(this).hasClass('tax-disable'))
	{
		$(this).find('.tax-enable-disable').attr("src","/media/images/tt-new/enable.png");
		$(this).parent().parent().find('.tax-chk').removeClass('tax-disable');	
		$(this).parent().parent().find('.tax_check_value').val('1');
		var cust_tax = $('#customer_taxpercentage').val();
		if( cust_tax!==undefined && cust_tax !==null && cust_tax !=''){
			$(this).parent().parent().find('.tax_applied_percentage').val(cust_tax);
		} else {
			$(this).parent().parent().find('.tax_applied_percentage').val(0);
		}		
	}
	else
	{
		$(this).find('.tax-enable-disable').attr("src","/media/images/tt-new/tax-disable.png");
		$(this).parent().parent().find('.tax-chk').addClass('tax-disable');
		$(this).parent().parent().find('.tax_check_value').val('0');
		$(this).parent().parent().find('.tax_applied_percentage').val(0);
	}
	
	calculate_sub_total(0);
});
/*******Ends :::::::: Script for tax enable*******/

/*******Script for tax enable timebilling*******/
$(".tax-chk1").live("click",function(){
	if($(this).hasClass('tax-disable1'))
	{
		$(this).find('.tax-enable-disable1').attr("src","/media/images/tt-new/enable.png");
		$(this).parent().find('.tax-chk1').removeClass('tax-disable1');	
		$(this).parent().find('.tax_check_value').val('1');
		//fetch_customer_tax(0);
	}
	else
	{
		$(this).find('.tax-enable-disable1').attr("src","/media/images/tt-new/tax-disable.png");
		$(this).parent().find('.tax-chk1').addClass('tax-disable1');
		$(this).parent().find('.tax_check_value').val('0');
	}
	calculate_sub_total(0);
});

/*******Script for freight enable*******/
$(".freight-button").live("click",function(){ //normal view // here: I have to write the basic logic	
	if($(this).hasClass('freight-disable')) ///grey
	{
		$('#freight_check').val(1);
		$(this).children().attr("src","/media/images/tt-new/enable.png");
		$(this).removeClass('freight-disable');
	}
	else // green button
	{	
		$('#freight_check').val(0);
		$(this).children().attr("src","/media/images/tt-new/disable.png");
		$(this).addClass('freight-disable');		
	}
	resize_tag('freight', 0);
	calculate_sub_total();
});

/*******Popup for Items*******/


var selected_item = '';

$(".item_description").live("focus",function(){
	$('.item-popup').hide();
});


/*******Popup for job*******/
var selected_job = '';

$(".done-tax-popup").live("click",function(){ //normal view
	var new_tax_val	=	$(".rate-value-field").val();
	var new_tax_id	=	$(".tax-id").val();
	new_tax_val		=	new_tax_val.replace("%","");
	$("#tax_percentage").val(new_tax_val);
	$("#tax").val(new_tax_id);
	$("#tax_code").val($('.code-rate-value-field').val());
	$('.info-service-tax').css("background","#f9f9f9");// total background
	$('.info-service-tax .amt_line1').css("background","#f9f9f9"); //label
	$('.info-service-tax .amt_line2').css("background","#f9f9f9"); //2nd Div
	$('.info-service-tax .amt_line2 .amt_ipt1').css("background","#f9f9f9"); //input in 2nd div
	calculate_sub_total(0);
});

$("#subtotal").live("focus",function(){ //normal view
	$('.payment-popup').hide();
	$('.shipping-popup').hide();
	$('.color-change-sm').css("background","#f9f9f9");
	$('.color-change-sm').parent().css("background","#f9f9f9");
	$('.shipping_input').css("cssText","background:#f9f9f9 !important");
	$('.color-change-pm').css("background","#f9f9f9");
	$('.color-change-pm').parent().css("background","#f9f9f9");
	$('.payment_input').css("cssText","background:#f9f9f9 !important");
	
});

$("#pay_with_card").live("focus",function(){ //normal view
	$('.payment-popup').hide();
	$('#openPaymentPopup1').removeClass('selected3');
	$('.payment_input1').css("cssText","background:#ffffff !important");
});

$(".address-input-field-tax").live("focus",function(){ //normal view
	$('.payment-popup').hide();
});

/*******Popup for Comment*******/

//Function to get rate from activity
function get_rate(t) {
	//tr_id		=	$(t).closest("tr").attr("id");
	customer_id	=	$("#customer").val();
	activity_id	=	$(t).text();
	units		=	parseFloat(selected_activty.parent().parent().find('.units').val()); //parseFloat($("#"+tr_id+" .units").val());
	
	ajax_url	=	'/data/id';//?table=activity&name='+name+'&c_id='+customer_id+'&activity='+activity_id;
	if(customer_id != "") {
		$.ajax({
			type: 'GET',
			url : ajax_url,
			data: 'table=activity&name='+activity_id+'&c_id='+customer_id+'&activity='+activity_id,
			dataType: 'json',
			success: function(r){ 
				selected_activty.parent().parent().find('.rate').val(r['rate']);
				if(units != "" && !isNaN(units) && r['rate'] != "" && !isNaN(r['rate'])) {
					selected_activty.parent().parent().find('.amount').val(parseFloat(r['rate'])*units);
				}
				calculate_sub_total();
			}
		});
	} else {

	}
}

/**Date conversion into specific format**/

function convertDate()
{
	var date_input=document.getElementById('datepicker').value;
	var temp_arr = new Array();
	temp_arr = date_input.split(" ");
	var remove_data = temp_arr[1].substring(2,3);
	temp_arr[1] = temp_arr[1].replace(remove_data,""); 
	var mm = temp_arr[0];
	switch(mm)
	{
		case 'January':
			mm='01';
			break;
		case 'February':
			mm='02';
			break;
		case 'March':
			mm='03';
			break;
		case 'April':
			mm='04';
			break;
		case 'May':
			mm='05';
			break;
		case 'June':
			mm='06';
			break;
		case 'July':
			mm='07';
			break;
		case 'August':
			mm='08';
			break;
		case 'September':
			mm='09';
			break;
		case 'October':
			mm='10';
			break;
		case 'November':
			mm='11';
			break;
		case 'December':
			mm='12';
			break;
	}
	date_input = temp_arr[2]+'-'+mm+'-'+temp_arr[1];
	document.getElementById('timesheet_date').value = date_input;
}

/*******Calender date pick*******/
	$(function() {
		$('.date-label').datePicker({startDate:'01/01/1996'});
	});

/******Date conversion into specific format for hidden fields******/
	function db_convert()
	{
		var date = $('#specified_date').val();
			var date_input=document.getElementById('specified_date').value;
			var temp_arr = new Array();
			temp_arr = date_input.split(" ");
			var remove_data = temp_arr[1].substring(2,3);
			temp_arr[1] = temp_arr[1].replace(remove_data,""); 
			var mm = temp_arr[0];
			switch(mm)
			{
				case 'January':
					mm='01';
					break;
				case 'February':
					mm='02';
					break;
				case 'March':
					mm='03';
					break;
				case 'April':
					mm='04';
					break;
				case 'May':
					mm='05';
					break;
				case 'June':
					mm='06';
					break;
				case 'July':
					mm='07';
					break;
				case 'August':
					mm='08';
					break;
				case 'September':
					mm='09';
					break;
				case 'October':
					mm='10';
					break;
				case 'November':
					mm='11';
					break;
				case 'December':
					mm='12';
					break;
			}
			date_input = temp_arr[2]+'-'+mm+'-'+temp_arr[1];
			document.getElementById('selected_date').value = date_input;
		//}
	}
	function timebilling_date()
	{
		var date = $('.tb-date').val();
		if(date.charAt(2)=='/')
		{
		var datearray = date.split("/");
		var newdate = datearray[2] + '-' + datearray[0] + '-' + datearray[1];
		$('.date_timebilling').val(newdate);
		}
	} 
	
function load_settings_page(t, url) {
	$(".setting-panel").removeClass("settings-leftpanel-selected").addClass("settings-leftpanel");	
	$("#settings_content").load(url);
	var page_id		=	$(t).attr("id");
	$("#"+page_id+" .setting-panel").addClass("settings-leftpanel-selected");
	if(page_id.indexOf('editusers')!= -1){
		height	=	$(document).height()+60;
		if($(window).width() > 660) {
			$("#sidebar-main").css('height',height);
		}else{
			$("#sidebar-main").css('height','685px');
		}
	}else{
		$("#sidebar-main").css('height','685px');
	}
}


function change_image_timesheet_edit(chk)
{
	var Id_btn=($(chk).attr("id"));
	$('#'+Id_btn+' img').attr('src','/media/images/tt-new/edit_hover.png');
	$('#'+Id_btn+' img').css('margin-right','0px');
}

function change_image_timesheet_edit_out(chk1)
{
	var Id_btn1=($(chk1).attr("id"));
	$('#'+Id_btn1+' img').attr('src','/media/images/tt-new/edit-image-sales.png');
	$('#'+Id_btn1+' img').css('margin-right','0px');
}

// customer list next page
$("#activity-buttons #next").live("click", function(){
	var next_page		=	(parseInt($("#page-number").val())+1);
	var rowsper_page	=	parseInt($("#view-per-page").val());
	var total_slips	=	parseInt($("#total-slips").val());
	pages_count	=	parseInt(total_slips/rowsper_page);
	if(total_slips%rowsper_page != 0) pages_count	+=	1;
	if(pages_count < next_page) {
		return false;
	}
	$.ajax({
		type: 'POST',
		url : '/activitysheet/ajax_pagination',
		data: 'page='+next_page+'&rows_per_page='+rowsper_page,
		dataType: 'html',
		async: false,
		success: function(r){ 
			$("#slips_list").html(r);
			pages_count	=	parseInt(total_slips/rowsper_page);
			if(total_slips%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == next_page) {
				//$("#customer-next-page").removeClass("next-link").addClass("next-link-inact");
				$("#next").hide();
				$("#next-inact").show();
				showing_to	=	total_slips;
			} else {
				showing_to	=	(next_page*rowsper_page);
			}
		}
	});
	$(".pagination-info").text(((next_page*rowsper_page)-rowsper_page+1)+"-"+showing_to);
	$("#page-number").val(next_page);
	//$("#slip-prev-page").removeClass("prev-link-inact").addClass("prev-link");
	$("#prev").show();
	$("#prev-inact").hide();
});

// customer list previous page
$("#activity-buttons #prev").live("click", function(){
	var prev_page		=	(parseInt($("#page-number").val())-1);
	if(prev_page < 1) return false;
	var rowsper_page	=	parseInt($("#view-per-page").val());
	$.ajax({
		type: 'POST',
		url : '/activitysheet/ajax_pagination',
		data: 'page='+prev_page+'&rows_per_page='+rowsper_page,
		dataType: 'html',
		success: function(r){ 
			$("#slips_list").html(r);
			$("#next").show();
			$("#next-inact").hide();
		}
	});
	$("#page-number").val(prev_page);
	$(".pagination-info").text(((prev_page*rowsper_page)-rowsper_page+1)+"-"+(prev_page*rowsper_page));
	if(prev_page == 1) {
		$("#prev").hide();
		$("#prev-inact").show();
	}
});

function reload_activity_list() {
	var cur_page		=	(parseInt($("#page-number").val()));
	var rowsper_page	=	parseInt($("#view-per-page").val());
	$.ajax({
		type: 'POST',
		url : '/activitysheet/ajax_pagination',
		data: 'page='+cur_page+'&rows_per_page='+rowsper_page,
		dataType: 'html',
		success: function(r){ 
			$("#slips_list").html(r);
		}
	});
}
// customer list results per page
$(".slips_result_per_page").live("click", function(){
	var rowsper_page	=	($(this).attr("id")).replace("slips_","");
	var page			=	1;
	var total_slips		=	parseInt($("#total-slips").val());
	var number_selected	=	$(this).find('a').html().split(' ')['0'];
	var sale_selected	=	$(".slips-selected").text();
	if(number_selected	==	sale_selected){
	$('#dropdown-menu-bottom').hide();
	return false;
	}
	$('.ajax_page .ajax_loader_show').show();
	$.ajax({
		type: 'POST',
		url : '/activitysheet/ajax_pagination',
		data: 'page='+page+'&rows_per_page='+rowsper_page,
		dataType: 'html',
		async: false,
		success: function(r){ 
			$("#slips_list").html(r);
			$('.ajax_page .ajax_loader_show').hide();
			pages_count	=	parseInt(total_slips/rowsper_page);
			if(total_slips%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == page) {
				$("#next").hide();
				$("#next-inact").show();
				showing_to	=	total_slips;
			} else {
				$("#next").show();
				$("#next-inact").hide();
				showing_to	=	rowsper_page;
			}
		}
	});
	if($('#slips_'+rowsper_page).hasClass('all_views') == true){
		$(".slips-selected").text('All');
	}else{
		$(".slips-selected").text(rowsper_page);
	}
	//$(".slips-selected").text(rowsper_page);
	$(".pagination-info").text("1-"+showing_to);
	$("#view-per-page").val(rowsper_page);
	$("#page-number").val("1");
	$("#prev").hide();
	$("#prev-inact").show();
});
//sales list next page
$("#sales-next-page").live("click", function(){
	var next_page				=	(parseInt($("#page-number").val())+1);
	var rowsper_page			=	parseInt($("#view-per-page").val());
	var total_sales				=	parseInt($("#total-sales").val());
	var sort_field				=	$("#sort_field").val();
	var sort_order				=	$("#sort_order").val();
	var sale_type_pagination	=	$("#sale-type-pagination").val();
	var search_string			=	$(".search_sale").val();
	if(search_string == ''){
		search_string ='000';
	}
	
	pages_count	=	parseInt(total_sales/rowsper_page);
	if(total_sales%rowsper_page != 0) pages_count	+=	1;
	if(pages_count < next_page) {
		return false;
	}
	$.ajax({
		type: 'POST',
		url : '/sales/ajax_pagination',
		data: 'page='+next_page+'&rows_per_page='+rowsper_page+'&sort_field='+sort_field+'&order='+sort_order+'&sale_type='+sale_type_pagination+'&search_sale='+search_string,
		dataType: 'html',
		async: false,
		success: function(r){ 
			$("#sales_list").html(r);
		
			pages_count	=	parseInt(total_sales/rowsper_page);
			if(total_sales%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == next_page) {
				showing_to	=	total_sales;
				$("#sales-next-page").removeClass("next-link").addClass("next-link-inact");
			} else {
				showing_to	=	(next_page*rowsper_page);
			}
		}
	});
	$(".pagination-info").text(((next_page*rowsper_page)-rowsper_page+1)+"-"+showing_to);
	$("#page-number").val(next_page);
	$("#sales-prev-page").removeClass("prev-link-inact").addClass("prev-link");
});

// customer list previous page
$("#sales-prev-page").live("click", function(){
	var prev_page		=	(parseInt($("#page-number").val())-1);
	if(prev_page < 1) return false;
	var rowsper_page	=	parseInt($("#view-per-page").val());
	var sort_field		=	$("#sort_field").val();
	var sort_order		=	$("#sort_order").val();
	var sale_type		=	$("#sale-type-pagination").val();
	var search_string	=	$(".search_sale").val();
	if(search_string == ''){
		search_string ='000';
	}
	$.ajax({
		type: 'POST',
		url : '/sales/ajax_pagination',
		data: 'page='+prev_page+'&rows_per_page='+rowsper_page+'&sort_field='+sort_field+'&order='+sort_order+'&sale_type='+sale_type+'&search_sale='+search_string,
		dataType: 'html',
		success: function(r){ 
			$("#sales_list").html(r);
			
			$("#sales-next-page").removeClass("next-link-inact").addClass("next-link");
		}
	});
	$("#page-number").val(prev_page);
	$(".pagination-info").text(((prev_page*rowsper_page)-rowsper_page+1)+"-"+(prev_page*rowsper_page));
	if(prev_page == 1) {
		$("#sales-prev-page").removeClass("prev-link").addClass("prev-link-inact");
	}
});


// sales list results per page
$(".sales_result_per_page").live("click", function(){
	var rowsper_page	=	($(this).attr("id")).replace("sales_","");
	var page			=	1;
	var total_sales		=	parseInt($("#total-sales").val());
	var sort_field		=	$("#sort_field").val();
	var sort_order		=	$("#sort_order").val();

	var number_selected	=	$(this).find('a').html().split(' ')['0'];
	var sale_selected	=	$(".sales-selected").text();
	if(number_selected	==	sale_selected){
		$('#dropdown-menu-bottom').hide();
		return false;
	}
	$('.ajax_page .ajax_loader_show').css('display','block');
	$.ajax({
		type: 'POST',
		url : '/sales/ajax_pagination',
		data: 'page='+page+'&rows_per_page='+rowsper_page+'&sort_field='+sort_field+'&order='+sort_order,
		dataType: 'html',
		async: false,
		success: function(r){ 
			$("#sales_list").html(r);
			$('.ajax_page .ajax_loader_show').css('display','none');
			pages_count	=	parseInt(total_sales/rowsper_page);
			if(total_sales%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == page) {
				showing_to	=	total_sales;
				$("#sales-next-page").removeClass("next-link").addClass("next-link-inact");
			} else {
				showing_to	=	rowsper_page;
				$("#sales-next-page").removeClass("next-link-inact").addClass("next-link");
			}
		}
	});
	if($('#sales_'+rowsper_page).hasClass('all_views') == true){
		$(".sales-selected").text('All');
	}else{
		$(".sales-selected").text(rowsper_page);
	}
	$(".pagination-info").text("1-"+showing_to);
	$("#view-per-page").val(rowsper_page);
	$("#page-number").val("1");
	$("#sales-prev-page").removeClass("prev-link").addClass("prev-link-inact");
});

// Sort sales list by ascending or descending
function sort_sales(field, order) {
	var rowsper_page	=	$(".sales-selected").text();
	var page			=	1;
	var total_sales		=	parseInt($("#total-sales").val());
	$("#sort_field").val(field);
	$("#sort_order").val(order);
	sale_type = $("#sale-type-pagination").val();
	search_sale	=	$('.search_sale').val();
	if(search_sale == ''){
		search_sale = '000';
	}
	if(rowsper_page == 'All'){
		rowsper_page=total_sales;
	}
	
	if(field == '1'){
		$('.cust-serviceviewItem-2-field .ajax_loader').show();
	}
	else if(field == '2'){
		$('.cust-serviceviewItem-3-field .ajax_loader').show();
	}
	else if(field == '3'){
		$('.cust-serviceviewItem-4-field .ajax_loader').show();
	}
	else if(field == '4'){
		$('.cust-serviceviewItem-6-field .ajax_loader').show();
	}
	else if(field == '5'){
		$('.cust-serviceviewItem-7-field .ajax_loader').show();
	}
	$.ajax({
		type: 'POST',
		url : '/sales/ajax_pagination',
		data: 'page='+page+'&rows_per_page='+rowsper_page+'&sort_field='+field+'&order='+order+'&sale_type='+sale_type+'&search_sale='+search_sale,
		dataType: 'html',
		success: function(r){
			$("#sales_list").html(r);
			pages_count	=	parseInt(total_sales/rowsper_page);
			if(total_sales%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == page) {
				var showing_to	=	total_sales;
				$("#sales-next-page").removeClass("next-link").addClass("next-link-inact");
			} else {
				var showing_to	=	rowsper_page;
				$("#sales-next-page").removeClass("next-link-inact").addClass("next-link");
			}
			$(".pagination-info").text("1-"+showing_to);
			$('.ajax_loader').hide();
		}
	});
	
	$(".sales-selected").text(rowsper_page);
	$("#view-per-page").val(rowsper_page);
	$("#page-number").val("1");
	$("#sales-prev-page").removeClass("prev-link").addClass("prev-link-inact");
}

//Sort customer list by ascending or descending
function sort_customer(field,order){
	var rowsper_page	=	$(".slips-selected").text();
	var page			=	1;
	var total_sales		=	parseInt($("#total-customers").val());
	$("#sort_field").val(field);
	$("#sort_order").val(order);
	sale_type = $("#sale-type-pagination").val();
	search_customer	=	$('.search_customer').val();
	if(search_customer == ''){
		search_customer = '000';
	}
	if(rowsper_page == 'All'){
		rowsper_page=total_sales;
	}
	if(field == '1'){
		$('.synced1-serviceviewItem-2-field .ajax_loader').show();
	}
	else if(field == '2'){
		$('.synced1-serviceviewItem-3-field .ajax_loader').show();
	}
	else if(field == '3'){
		$('.synced1-serviceviewItem-4-field .ajax_loader').show();
	}
	else if(field == '4'){
		$('.synced1-serviceviewItem-6-field .ajax_loader').show();
	}
	$.ajax({
		type: 'POST',
		url : '/customer/ajax_pagination',
		data: 'page='+page+'&rows_per_page='+rowsper_page+'&sort_field='+field+'&order='+order+'&search_customer='+search_customer,
		dataType: 'html',
		success: function(r){
			$("#customer_list").html(r);
			
			pages_count	=	parseInt(total_sales/rowsper_page);
			if(total_sales%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == page) {
				showing_to	=	total_sales;
				$("#customer-next-page").removeClass("next-link").addClass("next-link-inact");
			} else {
				showing_to	=	rowsper_page;
				$("#customer-next-page").removeClass("next-link-inact").addClass("next-link");
			}
		$(".pagination-info").text("1-"+showing_to);
		$('.ajax_loader').hide();
		}
	});
	$(".slips-selected").text(rowsper_page);
	$("#view-per-page").val(rowsper_page);
	$("#page-number").val("1");
	$("#customer-prev-page").removeClass("prev-link").addClass("prev-link-inact");
}

//Sort jobs list by ascending or descending
function sort_jobs(field,order){
	var rowsper_page	=	$(".slips-selected").text();
	var page			=	1;
	var total_sales		=	parseInt($("#total-jobs").val());
	$("#sort_field").val(field);
	$("#sort_order").val(order);
	sale_type = $("#sale-type-pagination").val();
	search_jobs	=	$('.search_jobs').val();
	if(search_jobs == ''){
		search_jobs = '000';
	}
	if(rowsper_page == 'All'){
		rowsper_page=total_sales;
	}
	if(field == '1'){
		$('.jobs-serviceviewItem-2-field .ajax_loader').show();
	}
	else if(field == '2'){
		$('.jobs-serviceviewItem-6-field .ajax_loader').show();
	}
	else if(field == '3'){
		$('.jobs-serviceviewItem-7-field .ajax_loader').show();
	}
	$.ajax({
		type: 'POST',
		url : '/jobs/ajax_pagination',
		data: 'page='+page+'&rows_per_page='+rowsper_page+'&sort_field='+field+'&order='+order+'&search_jobs='+search_jobs,
		dataType: 'html',
		success: function(r){
			$("#jobs_list").html(r);
			pages_count	=	parseInt(total_sales/rowsper_page);
			if(total_sales%rowsper_page != 0) pages_count	+=	1;
			if(pages_count == page) {
				showing_to	=	total_sales;
				$("#jobs-next-page").removeClass("next-link").addClass("next-link-inact");
			} else {
				showing_to	=	rowsper_page;
				$("#jobs-next-page").removeClass("next-link-inact").addClass("next-link");
			}
			$(".pagination-info").text("1-"+showing_to);
			$('.ajax_loader').hide();
		}
	});
	$(".slips-selected").text(rowsper_page);
	$("#view-per-page").val(rowsper_page);
	$("#page-number").val("1");
	$("#jobs-prev-page").removeClass("prev-link").addClass("prev-link-inact");
}

/********Start Date and End Date**********/

function start_end_convert() {
	var start = $('.start_date #datepicker').val();
	var end = $('.end_date input').val();
	if(start != '') {
		var temp_arr = new Array();
		temp_arr = start.split(" ");
		var remove_data = temp_arr[1].substring(2,3);
		temp_arr[1] = temp_arr[1].replace(remove_data,""); 
		var mm = temp_arr[0];
		switch(mm) {
			case 'January':
				mm='01';
				break;
			case 'February':
				mm='02';
				break;
			case 'March':
				mm='03';
				break;
			case 'April':
				mm='04';
				break;
			case 'May':
				mm='05';
				break;
			case 'June':
				mm='06';
				break;
			case 'July':
				mm='07';
				break;
			case 'August':
				mm='08';
				break;
			case 'September':
				mm='09';
				break;
			case 'October':
				mm='10';
				break;
			case 'November':
				mm='11';
				break;
			case 'December':
				mm='12';
				break;
		}
		start = temp_arr[2]+'-'+mm+'-'+temp_arr[1];
		$('#datestart').val(start);
	} if(end != '') {
		temp_arr = new Array();
		temp_arr = end.split(" ");
		remove_data = temp_arr[1].substring(2,3);
		temp_arr[1] = temp_arr[1].replace(remove_data,""); 
		mm = temp_arr[0];
		switch(mm) {
			case 'January':
				mm='01';
				break;
			case 'February':
				mm='02';
				break;
			case 'March':
				mm='03';
				break;
			case 'April':
				mm='04';
				break;
			case 'May':
				mm='05';
				break;
			case 'June':
				mm='06';
				break;
			case 'July':
				mm='07';
				break;
			case 'August':
				mm='08';
				break;
			case 'September':
				mm='09';
				break;
			case 'October':
				mm='10';
				break;
			case 'November':
				mm='11';
				break;
			case 'December':
				mm='12';
				break;
		}
		end = temp_arr[2]+'-'+mm+'-'+temp_arr[1];
		$('#dateend').val(end);
	}
//	$('form#create_jobs').submit();
}


/***Retina Image for Header starts****/
$(document).ready(function()
{
if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
{
	var images = $('img.highDefinition');   
for(var i=0; i < images.length; i++)
{
	images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/aec-header-logo.png', '/media/images/tt-new/aec-logo.png'));
	$('.highDefinition').css('width','33px');
	$('.highDefinition').css('height','23px');
}
}
if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
{
	var images = $('img.dot');   
for(var i=0; i < images.length; i++)
{
images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/dot.png', '/media/images/tt-new/active-bulb.png'));
$('.dot').css('width','8px');
$('.dot').css('height','9px');
}
}
if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
{
	var images = $('img.dot1');   
for(var i=0; i < images.length; i++)
{
images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/dot1.png', '/media/images/tt-new/inactive-bulb.png'));
$('.dot1').css('width','8px');
$('.dot1').css('height','9px');
}
}
if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
{
	var images = $('img.retina-arrow');   
for(var i=0; i < images.length; i++)
{
images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/timesheet-arrow-left.png', '/media/images/tt-new/retina-timesheet-arrow.png'));
$('.retina-arrow').css('width','16px');
$('.retina-arrow').css('height','16px');
}
}
if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
{
	var images = $('img.retina-arrow-list');   
for(var i=0; i < images.length; i++)
{
images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/allsales-arrow-down.png', '/media/images/tt-new/arrow-down-retina.png'));
$('.retina-arrow-list').css('width','10px');
$('.retina-arrow-list').css('height','6px');
}
}
if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
{
	var images = $('img.create-job-enable-disable');   
for(var i=0; i < images.length; i++)
{
images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/disable.png', '/media/images/tt-new/inactive_retina.png'));
$('.create-job-enable-disable').css('width','18px');
$('.create-job-enable-disable').css('height','18px');
}
}
if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
{
	var images = $('img.create-job-enable-disable1');   
for(var i=0; i < images.length; i++)
{
images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/disable.png', '/media/images/tt-new/inactive_retina.png'));
$('.create-job-enable-disable1').css('width','18px');
$('.create-job-enable-disable1').css('height','18px');
}
}
if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
{
	var images = $('img.job-disable');   
for(var i=0; i < images.length; i++)
{
images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/enable.png', '/media/images/tt-new/active_retina.png'));
$('.job-disable').css('width','18px');
$('.job-disable').css('height','18px');
}
}
if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
{
	var images = $('img.job-disable1');   
for(var i=0; i < images.length; i++)
{
images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/enable.png', '/media/images/tt-new/active_retina.png'));
$('.job-disable1').css('width','18px');
$('.job-disable1').css('height','18px');
}
}
});
/***Retina Image for Header ends****/

/*****Toggle div for mobile starts******/
function toggleCustomerSales(chk,num)
{
	var Id=($(chk).attr("id"));
	if (($("#" + Id).hasClass('toggle-active'))) {
		$("#" + Id).removeClass('toggle-active');
		$("#" + Id).addClass('toggle-inactive');
		$('#arrow-image-field-'+num).removeClass('bor-bottom');
		$('#arrow-image-field-'+num).css("background","url('/media/images/tt-new/timesheet-arrow-left.png') 9px 52% no-repeat");
		$('#rest_hidden_contents_'+num).toggle('slow');
	} else if  ($("#" + Id).hasClass('toggle-inactive')) {
		$("#" + Id).addClass('toggle-active');
		$("#" + Id).removeClass('toggle-inactive');
		$('#arrow-image-field-'+num).addClass('bor-bottom');
		$('#arrow-image-field-'+num).css("background","url('/media/images/tt-new/timesheet-arrow-down.png') 9px 52% no-repeat");
		$('#rest_hidden_contents_'+num).toggle('slow');
	}
}
	
/****Hiding Employee names popup starts****/
    	$(document).bind('click', function(e) {
    		var $clicked = $(e.target);
    		if (($clicked.hasClass("employee-popup"))||($clicked.hasClass("emp_field")) ||($clicked.hasClass("employee_field")) ||($clicked.parent().parent().hasClass("employee-popup"))||($clicked.parent().hasClass("selected_emp"))) {
    			$('.employee-popup').show();
    			$('.employee_arrow').removeClass('selected_emp');
    			$('#emp_field_activity').addClass('yellow');
    			$('.employee_field').addClass('yellow');
    			$('#emp_field_activity #users-activity').addClass('grey');
    		}
		else {
			$('.employee_arrow').addClass('selected_emp');
		$('.employee-popup').hide();
		$('#emp_field_activity').removeClass('yellow');
		$('.employee_field').removeClass('yellow');
		$('#emp_field_activity #users-activity').removeClass('grey');
		}
		});
    	var i=0;
    	var height_scroll1 = '';
    	var height = '40';
    	var height_scroll = '';
    	$('.customer-tag1-textfield').live('focus',function(e){
    		$('.arrow_customer_retina').click();
    		$('.popup-list').scrollTop('0');
    		index_value = $(this).parent().find('.popup .popup-content .popup-list li').length;
    		j=0;
    		height1=40;
    	});
    	$('.employee_field').live('focus',function(e){
    		$('.emp-arrow-down-retina').click();
    		height_scroll	=	$('.employee-popup .popup-list').prop('scrollHeight');
    		height_scroll = parseInt(height_scroll)-240;
    		$('.popup-items2').scrollTop('0');
    	});
    	$('.activity_tt_field').live('focus',function(e){
    		$('.activity_arrow_down').click();
    		$('.popup-list').scrollTop('0');
    		index_value = $(this).parent().find('.popup .popup-content .popup-list li').length;
    		j=0;
    		height1=40;
    	});
    	
    	$('#job').live('focus',function(e){
    		$('.down-arrow-job img').click();
    		$('.popup-list').scrollTop('0');
    		index_value = $(this).parent().find('.popup .popup-content .popup-list li').length;
    		j=0;
    		height1=40;
    	});
    	
    	$('#customer_name,#activity_name,#job,.employee_field').live('blur',function(){
    	$('.customer-tag1-textfield').removeClass('yellow');
    	$('.customer-tag1-textfield').parent().parent().removeClass('yellow');
    	$('.activity_tt_field').removeClass('yellow');
    	$('.activity_tt_field').parent().find('.users-activity').removeClass('grey');
    	$('#activity_section').removeClass('yellow');
    	$('.customer_job').removeClass('yellow');
    	$('.customer_job').parent().find('.users-activity').removeClass('grey');
    	$('.customer_job').parent().parent().find('.yellow').removeClass('yellow');
    	});
    	
    	$('.slip,#units,#total,#notes').live('focus',function(){
    		$('.popup').hide();
    		$('.employee-popup').hide();
    	});
    	
    	$(document).ready(function(){
    		var index_value = '';
    		$(document).bind('keypress click', function(e) {
    			var $clicked = $(e.target);
    			/**For Employee popup in TT module**/
    			if($clicked.hasClass('employee_field') || $clicked.hasClass('emp-arrow-down-retina')){
    				index_value = $('.names-popup-list').length;
    				if(e.keyCode=='40' && i < index_value && i>=0){
    				$('.popup-items2 div').removeClass('blue_background');
    				$('.popup-items2 div').eq(i).addClass('blue_background');
    				if(i > '3' && i<index_value){
    					$('.popup-items2').scrollTop(height);
    					height = parseInt(height)+40;
    				}
    				// window.onscroll = function () { window.scrollTo(0, 0); };
    				i++;
    			}
    			else if(e.keyCode=='38' && i<=index_value && i>0){
    				--i;
    				$('.popup-items2 div').removeClass('blue_background');
    				$('.popup-items2 div').eq(i-1).addClass('blue_background');
    				if(i < parseInt(index_value)-3 && i>0){
    				$('.popup-items2').scrollTop(height_scroll);
					height_scroll = parseInt(height_scroll)-40;
    				}
    				//window.onscroll = function () { window.scrollTo(0, 0); };
    			}
    			if(e.keyCode == '13'){
					$('.names-popup-list').eq(i-1).click();
					$('.employee-popup').hide();
				}
    			}
    			
    			/**For all other popups in TT module**/
    			else if($clicked.hasClass('activity_tt_field') || $clicked.hasClass('activity_arrow') || $clicked.hasClass('down-arrow-customer') || $clicked.hasClass('customer-tag1-textfield')|| $clicked.hasClass('customer_job') || $clicked.hasClass('customer_job_arrow')){
    				index_value = $('.popup-list li').length;
    				
    				
    				if(e.keyCode=='40'){
    					// for dynamic pop up
    					if(j<index_value){
    						j++;
    						$('.popup-list .data-act').removeClass('blue_background');
    						$clicked.parent().find('.popup .popup-content .popup-list .data-act').eq(j-1).addClass('blue_background');
							height1 = (j-1)*40;
							$('.popup-list').scrollTop(height1);
        					
        				}else{
        					// do nothing
        					height1=(index_value-3)*40;
        				}
    					console.log('1 j= '+j);
    					console.log('1 index= '+index_value);
    					console.log('1 Height= '+height1);
    					console.log('------------------------------');
						
    				}
    				
    				else if(e.keyCode=='38'){
    					
    					if(j>1){
    						
    						j--;
							$('.popup-list .data-act').removeClass('blue_background');
    						$clicked.parent().find('.popup .popup-content .popup-list .data-act').eq(j-1).addClass('blue_background');
    						height1 = (j-1)*40;
    						$('.popup-list').scrollTop(height1);
	    					
	        			}else{
    						// do nothing
    						height1=0;
    					}
	    				/**-------------------------------- */
						console.log('112 j= '+j);
						console.log('112 index= '+index_value);
						console.log('112 Height= '+height1);
						console.log('---------------------');
						/**--------------------------------*/
	
    				}
    				if(e.keyCode == '13'){
    					$('.popup-list .data-act').eq(j-1).click();
    					$('.popup').hide();
    				}
    			}
    		});
    	});
/****Hiding Employee names popup ends****/
    	
/**Displaying to be synced popup***/    	
    	function selectSynced(chk,num)
    	{
    		var Id=($(chk).attr("id"));
			if (($("#" + Id).hasClass('enabled') == false))
			{
				$('.enabled').removeClass('enabled');
				$("#" + Id).addClass('enabled');
			}
			else if (($("#" + Id).hasClass('enabled') == true))
			{
				$("#" + Id).removeClass('enabled');
			}
		}
    	
    	function openViewPopup()
    	{
	    	var left = $('.dropdown-toggle').offset().left;
	    	if($('.btn-group').hasClass('open')==false)
	    	{
	    		$('.dropdown-menu').css('left',left);
	    		$('.dropdown-menu').css('top','21px');
	    		$('.dropdown-menu').show();
	    	}
	    	else
	    	{
	    		//$('.dropdown-menu').css({"left":left+2,"top":21px});
	    		//$('.dropdown-menu').hide();
	    	}
    	}
    	
    	$(document).ready(function(){
    		$('.refresh-img').mouseover(function(event){
	    		var contentId = jQuery(this).attr("id");
	    		 if(contentId == undefined)
				{
	    		$('.refresh-img').css('cursor','default');
	    		$('.ver a').css('cursor','default');
				}
				else
				{
				$("#" + contentId).css('cursor','pointer');
				}
			});
    		
    	});
    	
    /***Getting the last child in signup page**/
    	$(document).ready(function(){
    	$(".select-plans").last().css("border-radius","0 0 5px 5px");
    	if($(window).width() <= 700) 
		{
    		$('.search-field').attr("placeholder"," ");
		}
    	else
    	{
    		$('.search-field').attr("placeholder","search");
    	}
		$(window).resize(function()
    	{
		if($(window).width() <= 700) 
    	{
    		$('.search-field').attr("placeholder"," ");
    	}
    	else
    	{
    		$('.search-field').attr("placeholder","search");
    	}
    	});
		
		});
    
   /*******Clear search results********/
   function clear_search_results(url,navigation_flag)
   {
	   if(navigation_flag=='1')
	   {
		   $('.clear_search').attr('href',url+'/sales');
	   }
	   else if(navigation_flag=='2')
	   {
		   $('.clear_search').attr('href',url+'/customer');
	   }
	   else if(navigation_flag=='3')
	   {
		   $('.clear_search').attr('href',url+'/jobs');
	   }
	   else if(navigation_flag=='4')
	   {
		   $('.clear_search').attr('href',url+'/activitysheet');
	   }
	   else if(navigation_flag=='5')
	   {
		   $('.clear_search').attr('href',url+'/timesheet');
	   }
	   else
	   {
		   $('.clear_search').attr('href',url);
	   }
   }
   
   $(document).ready(function(){
	   /*******Function to make fields number specific*******/
	   $(".number").keypress(function (e){
		   var charCode = (e.which) ? e.which : e.keyCode;
		   if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode!=46 && charCode!=37&& charCode!=39) {
		   return false;
		   }
		 });
	   /*****Date conversion for all the sales view pages*****/
	   $(".paid_today").live("focus",function(){
			if($(this).val()==0 || $(this).val()==0.00)
			{
				$(this).val('');
			}
			resize_tag('paid_today', 0);
		});
	   
	   if(screen.availHeight>=1040){
		   $('.margin-top-neg-1').css('margin-top','0');
		   $('.service-add-more-buttons').css('padding-top','1%');
		   $('.info-column-1').css('cssText','margin-top:1%');
		   $('.info-column-2').css('cssText','margin-top:1%');
		   $('.name-salesItem').css('cssText','margin-top:1%');
		   $('.address-info-1').css('cssText','margin-top:-2% !important');
		   $('.address-info-2').css('cssText','margin-top:-2% !important');
		  // $('.info').css('cssText','padding-top:12px !important');
		  // $('.name-salesItem').css('cssText','padding-bottom:5px');
		  // $('.name-salesItem1').css('cssText','height:40px');
		   $('.info-block').css('cssText','height:38px');
		   $('.color-change-pm, .color-change-sp, .color-change-cm, .color-change-rs, .color-change-sm').css('cssText','height:38px');
		   $('.info-service').css('cssText','padding-top:12px !important');
		   //$('.info-service-tax').css('cssText','height:40px');
		   //$('.mar,.mar-tax').css('padding-top','12px');
		   $('.top-mar-huge').css('cssText','margin-top:2% !important');
		   $('.payment-input-field-tax-exp-year').css('cssText','margin-top:0px');
		   //$('.customer-view-list').css('cssText','margin-top:4px');
		   $('.customer-column-1').css('cssText','margin-top:3%');
		   $('.edit-delete-buttons').css('height','42px');
		   if($(window).width()<= 750){
			   $('.address-info-1').css('cssText','margin-top:0% !important');
			   $('.address-info-2').css('cssText','margin-top:0% !important');
		   }
		   $(window).resize(function()
			{
				if(screen.availHeight>=1040 && $(window).width()<=750){
					 $('.address-info-1').css('cssText','margin-top:0% !important');
					 $('.address-info-2').css('cssText','margin-top:0% !important');
				} else {
					$('.address-info-1').css('cssText','margin-top:-2% !important');
					$('.address-info-2').css('cssText','margin-top:-2% !important');
				}
			});
	   }
	   else if(screen.availHeight>=925 && screen.availHeight<1040){
			  $('.top-mar-huge').css('margin-top','0');
			  $('.info-column-1').css('margin-bottom','2%');
			  $('.info-column-2').css('margin-bottom','2%');
			  $('.service-add-more-buttons').css('padding-top','6px');
			  $('.edit-delete-buttons').css('padding-top','8px');
		}
	   else if(screen.availHeight>=770 && screen.availHeight<=800){
		 // $('#outer-div').css('cssText','padding-top:16 !important');
		  $('.margin-2').removeClass('margin-2');
		  $('.navbar').css('cssText','margin-top:30px !important');
		  $('.name-salesItem').css('cssText','margin-top:-4px');
		  //$('.info-column-2').css('cssText','margin-top:10px');
		  $('.customer-column-1').css('margin-bottom','0');
		  $('.cash-cc-btns').css('margin-top','1%');
	   }
	});
   
   function getLeftPos(el) {
	    for (var leftPos = 0;
	        el != null;
	        leftPos += el.offsetLeft, el = el.offsetParent);
	    return leftPos;
	}
	function getTopPos(el) {
	    for (var topPos = 0;
	        el != null;
	        topPos += el.offsetTop, el = el.offsetParent);
	    return topPos;
	}
	
	// for getting the original value of the freight entered.
    function set_org_freight_amt(obj){
    	var orgi_freight_val = $(obj).val();
    	if($("#is_tax_inclusive").val()!=undefined){
    		if($("#is_tax_inclusive").val()=='1'){ // tax inclusive is ON so, subtract the original value with freight tax amount.
    			// this equation is to get the original value from freight TAX -> original value = (100 * total amount)/(percentage+100)
    			orgi_freight_val = (100 * $(obj).val())/(parseInt($('.tax_applied_percentage_freight').val(), 10)+100);
    		} else{
    			// do nothing
    		}
    	}
    	$(obj).attr("freight_orginal_amt", orgi_freight_val);
    	$('#freight_orginal_amt').val(orgi_freight_val); // setting in the hidden field so that we ca store it in database.
    }
    
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		
		/**** hiding on next tab select *****/
		if ($("#pay_with_card").is(":focus")) {
			$('.payment-popup').hide();			
			$('.payment_input1').css("cssText","background:#ffffff !important");
		}
		
		/**** hiding on TAX AUS/CA popup *****/				
		
		// 2nd
		if($clicked.hasClass("openTaxPopup1")){
			$arrow_clicked=$clicked;
			$('.tax-values-popup').show();
			$('#tax_drop_down').show();			
			$('.tax-values-popup').css("position", "absolute");
			$('.tax-values-popup').css("left", getLeftPos($clicked.get(0))-239);
			$('.tax-values-popup').css("top", getTopPos($clicked.get(0))+31);
			
		}else{
			if($clicked.hasClass("names-popup-list-label") && location.href.indexOf('sales') != -1 && location.href.indexOf('payment') == -1) {// if the TAX is clicked properly
				if(location.href.indexOf('payment') != -1){
					$('.payment-popup').hide();
				}
				var selected_taxcode 		= 	$clicked.find('.taxcode').val();
				var selected_taxpercentage 	= 	$clicked.find('.taxpercentage').val();
				var selected_tax_record_id	=	$clicked.find('.tax_record_id').val();
				
				if($.Company.COUNTRY === $.USA){ // for US
					
					if($arrow_clicked.parent().find('#tax_percentage').val()!=undefined&&$arrow_clicked.parent().find('#tax_code').val()!=undefined){
						// this is for the main TAX tags
						
						$arrow_clicked.parent().find('#tax_percentage').val(selected_taxpercentage);
						$arrow_clicked.parent().find('#tax_code').val(selected_taxcode);
						
						// loop through all the item as when the TAX is selected from main TAX US field.
						$('.tax_applied_percentage').each(function(){
							$(this).val(selected_taxpercentage);
						});
						
						// Loop throught for tax record id for each item
						$('.tax_applied_record_id').each(function(){
							$(this).val(selected_tax_record_id);
						});						
						
						// changing feight TAX
						$('.tax_applied_percentage_freight').val(selected_taxpercentage);
						$('.freight_tax_record_id').val(selected_tax_record_id);
						
						//changing customer tax
						
						$('#customer_taxpercentage').val(selected_taxpercentage);
						$('#customer_tax_record_id').val(selected_tax_record_id);
						$('#customer_taxcode').val(selected_taxcode);
					}
				} 
				if($arrow_clicked.parent().find('.tax_applied').html()!==null){ // Non _US - for item taxes 
					$arrow_clicked.parent().find('.tax_applied').html(selected_taxcode);
					$arrow_clicked.parent().find('.tax_applied_percentage').val(selected_taxpercentage);
					$arrow_clicked.parent().find('.tax_applied_record_id').val(selected_tax_record_id);
										
				} else if($arrow_clicked.parent().find('.tax_applied_freight').html()!==null){	// Non _US - for freight taxes
					// we need to calculate the set the freight original TAX amount.
					set_org_freight_amt($arrow_clicked.parent().find('#freight'));
					$arrow_clicked.parent().find('.tax_applied_freight').html(selected_taxcode);
					$arrow_clicked.parent().find('.tax_applied_percentage_freight').val(selected_taxpercentage);
					$arrow_clicked.parent().find('.freight_tax_record_id').val(selected_tax_record_id);
				}								
				// calling TAX calculation
				calculate_sub_total();	
			}
			
			// For customer page
			if(location.href.indexOf('customer') != -1){
				if($clicked.hasClass("names-popup-list-label")){
					var selected_tax_record_id	=	$clicked.parent().find('.tax_record_id').val();
					var selected_tax_code		=	$clicked.parent().find('.taxcode').val();
					$arrow_clicked.parent().find('.tax_code').html(selected_tax_code);
					$arrow_clicked.parent().find('.tax_record_id').val(selected_tax_record_id);
					$arrow_clicked.parent().find('.freight_tax_code').html(selected_tax_code);
					$arrow_clicked.parent().find('.freight_tax_record_id').val(selected_tax_record_id);
				}
			}
			$('.tax-values-popup').hide(); // If the TAX is clicked outside then, It will hide the pop-up.
		}
		/**** hiding on TAX AUS/CA popup ends*****/
	});
	
	/** After selecting the employee in the activitysheet filter this function is called to fill the hidden fields**/	
		function select_emp_field(rec_id,name){
			$('.employee_field').val(name);
			$('.employee_record_id').val(rec_id);
			$('.employee_name').val(name);
		}
		
	/** Function to clear the already setted session variable **/
		function clear_user(flag){
			if(flag=='1'){
				redirect	=	'/timesheet';
			}else{
				redirect	=	'/activitysheet';
			}
			$.ajax({
				type:	'POST',
				url	:	'/activitysheet/unset_employee',
				data:	'clear=1',
				dataType:'json',
				success:function(r){
					if(r[0]['error']=='1'){
						$('.error-desc').val(r[0]['description']);
					}else{
						window.location=redirect;
					}
				}
			});
		}

	/** Employee dropdown selection ajax call function to set the employee in session**/
		function selected_employee(selected_employee,emp_id,flag){
			if(flag=='1'){
				redirect	=	'/timesheet';
			}else{
				redirect	=	'/activitysheet';
			}
			$('.employee-popup').hide();
			$.ajax({
				type	:	'POST',
				url		:	'/activitysheet/selected_employee',
				data	:	'selected_employee='+selected_employee+'&employee_id='+emp_id,
				dataType: 	'json',
				success	:	function(r){
								if(r[0]['error'] ==  '1'){
									$('.error-desc').val(r[0]['description']);
								}else{
									$('.emp_field').val(selected_employee);
									location.href  =	'';
								}
							}	
			});		
		}		

	/** 
	 *	Whether the employee is having show jobs selected or not by admin. 
	 * 	Checking through ajax call on selecting the employee.
	 */
		function get_show_jobs(sync,employee_id,name){
			 if(employee_id == null || employee_id == '') {
				 employee_id = $('.employee_record_id').val(); 
			 }
			 if(name == null || name == 'null'){
				 name	=	$('.employee_name').val();
			 }
			 $('.employee-popup').css('cssText','display:none !important');
			 $('.employee_field').val(name);
			 $('#employee_add').val(name);
			 $('.employee_name').val(name);
			 $('.employee_record_id').val(employee_id);
			 if(sync=="async"){
				 sync=true;
			 }else{
				 sync=false;
			 }
			 $('.employee-popup').hide();
			$.ajax({
				type: 'POST',
				url:  '/activitysheet/get_show_jobs',
				async: false,
				data: 'employee_id='+employee_id,
				dataType: 'json',
				success:function(r){
					if(r[0]['error'] ==  '1'){
						$('.linked_job').val(r[0]['show_jobs']);
					}else{
						$('.linked_job').val(r[0]['show_jobs']);
					}
				}
			});
		}
	   

   
/*****On focus of elements****/
	
	/*****Script for scrolling of popups with keyboard keys starts****/
	$(document).ready(function(){
		$(function() {
		  	  var tabindex = 1;
		  	  $('.tabindex_field').each(function() {
		  	     if (this.type != "hidden" && this.id !='specified_date') {
		  	       var $input = $(this);
		  	       $input.attr("tabindex", tabindex);
		  	       tabindex++;
		  	     }
		  	  });
		  	});
	});
	/*****Script for scrolling of popups with keyboard keys ends****/
	
/******Common for all three sales create pages*******/
	$('#address1').live('focus',function()
	{
	$(this).parent().parent().css('background','#f7f7f7');
	$(this).css('cssText','background:#FFFDE1 !important');
	$(this).parent().css('background','#FFFDE1');
	});
	$('#address1').live('blur',function()
	{
	$(this).parent().parent().css('background','#fafafa');
	$(this).css('cssText','background:#fafafa !important');
	$(this).parent().css('background','#fafafa');
	});
	
	$('.info #address2').live('focus',function()
	{
	$(this).parent().parent().css('background','#f7f7f7');
	$(this).css('cssText','background:#FFFDE1 !important');
	$(this).parent().css('background','#FFFDE1');
	});
	$('.info #address2').live('blur',function()
	{
	$(this).parent().parent().css('background','#fafafa');
	$(this).css('cssText','background:#fafafa !important');
	$(this).parent().css('background','#fafafa');
	});
	
	$('.info #address3').live('focus',function()
	{	
	$(this).parent().parent().css('background','#f7f7f7');
	$(this).css('cssText','background:#FFFDE1 !important');
	$(this).parent().css('background','#FFFDE1');
	});
	$('.info #address3').live('blur',function()
	{	
	$(this).parent().parent().css('background','#fafafa');
	$(this).css('cssText','background:#fafafa !important');
	$(this).parent().css('background','#fafafa');
	});
	
	$('#sale_number').live('focus',function()
	{	
	$(this).parent().parent().css('background','#f7f7f7');
	$(this).css('cssText','background:#FFFDE1 !important');
	$(this).parent().css('background','#FFFDE1');
	});
	$('#sale_number').live('blur',function()
	{	
	$(this).parent().parent().css('background','#fafafa');
	$(this).css('cssText','background:#fafafa !important');
	$(this).parent().css('background','#fafafa');
	});
	
	$('#customer_po').live('focus',function()
	{	
	$(this).parent().parent().css('background','#f7f7f7');
	$(this).css('cssText','background:#FFFDE1 !important');
	$(this).parent().css('background','#FFFDE1');
	});
	$('#customer_po').live('blur',function()
	{	
	$(this).parent().parent().css('background','#fafafa');
	$(this).css('cssText','background:#fafafa !important');
	$(this).parent().css('background','#fafafa');
	});
	
	$('#terms').live('focus',function()
	{	
	$(this).parent().parent().css('background','#f7f7f7');
	$(this).css('cssText','background:#FFFDE1 !important');
	$(this).parent().css('background','#FFFDE1');
	});
	$('#terms').live('blur',function()
	{	
	$(this).parent().parent().css('background','#fafafa');
	$(this).css('cssText','background:#fafafa !important');
	$(this).parent().css('background','#fafafa');
	});
	
	
	
	$('.item_description').live('focus',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','1px solid #67b2f0');
		}
	});
	$('.item_description').live('blur',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','none');
		}
	});
	
	$('.salesItem-1-field .service-invoice-field-item').live('focus',function(){
		if($('.item-container').is(':visible')){
		$(this).parent().css('border','1px solid #67b2f0');
		//$(this).click();
		}
	});
	
	$('.ac_input').live('focus',function(){
		$(this).click();
	});
	

	$('.salesItem-1-field .service-invoice-field-item').live('blur',function(){
		if($('.item-container').is(':visible')){
		$(this).parent().css('border','none');
		}
	});
	
	$('.quantity').live('focus',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','1px solid #67b2f0');
		}
	});
	$('.quantity').live('blur',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','none');
		}
	});
	
	$('.item_price').live('focus',function()
	{
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','1px solid #67b2f0');
		}
	});
	$('.item_price').live('blur',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','none');
		}
	});
	
	$('.total').live('focus',function()
	{	if($('.item-container').is(':visible')){
			$(this).parent().css('border','1px solid #67b2f0');
		}
	});
	$('.total').live('blur',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','none');
		}
	});
	
	$('.service-invoice-field-job').live('focus',function(){
		if($('.item-container').is(':visible')){
		$(this).parent().css('border','1px solid #67b2f0');
		$(this).click();
		}
	});
	$('.service-invoice-field-job').live('blur',function(){
		if($('.item-container').is(':visible')){
		$(this).parent().css('border','none');
		}
	});
	
	$('.account-field').live('focus',function(){
		if($('.item-container').is(':visible')){
		$(this).parent().css('border','1px solid #67b2f0');
		$(this).click();
		}
	});
	$('.account-field').live('blur',function(){
		if($('.item-container').is(':visible')){
		$(this).parent().css('border','none');
		}
	});
	
	$('.timebillingviewItem-1-field .timebilling-invoice-field-desc').live('focus',function(){
		$(this).parent().css('border','1px solid #67b2f0');
		$(this).click();
	});
	$('.timebillingviewItem-1-field .timebilling-invoice-field-desc').live('blur',function(){
		$(this).parent().css('border','none');
	});
	
	$('.cust-input-field').live('focus',function(){
		$(this).parent().css('background','#fffde1');
		$(this).addClass('yellow-background');
		$(this).parent().parent().css('background','#f7f7f7');
	});
	
	$('.cust-input-field').live('blur',function(){
		$(this).parent().css('background','#fafafa');
		$(this).removeClass('yellow-background');
		$(this).parent().parent().css('background','#fafafa');
	});
	
	$('.customer-name-label-span').live('focus',function(){
		$('.customer-name-add-order').addClass('yellow');
		$('#customer_text').addClass('yellow-background');
	});
		
	
	$('.customer-name-label-span').live('blur',function(){
		$('.customer-name-add-order').removeClass('yellow');
		$('#customer_text').removeClass('yellow-background');
		$('.error-popup-customer').hide();
	});
	
	
	
	$('.payment_input1').live('focus',function()
	{	
		$('#openPaymentPopup1').click();
		// var $input = $(this);
		// scroll_popup_elements($input);
	});
	
	$('.freight').live('focus',function()
	{	
		$(this).parent().parent().children('.amt_line1').addClass('grey');// label making grey
		$(this).parent().parent().addClass('yellow');// complete div as yellow
		$(this).addClass('yellow'); // input file is yellow
		$(this).parent().addClass('yellow'); // div of input 2nd div
		 
		resize_tag('freight', 0);
		$('.shipping-popup').hide();
	});
	
	$('.freight').live('blur',function()
	{	
		$(this).parent().parent().children('.amt_line1').removeClass('grey');  //label 
		$(this).parent().parent().removeClass('yellow'); // 2nd div
		$(this).removeClass('yellow'); 
		$(this).parent().removeClass('yellow');
		resize_tag('freight', 1);
	});
	
	$('.tax_input').live('focus',function()
	{	
		$(this).parent().parent().children('.amt_line1').addClass('grey');
		$(this).parent().parent().addClass('yellow');
		$(this).addClass('yellow');
		$(this).parent().addClass('yellow');		
	});
	
	$('.tax_input').live('focusout',function()
	{	
		$(this).parent().parent().children('.amt_line1').removeClass('grey'); // remove yellow/grey for getting fafafa color
		$(this).parent().parent().removeClass('yellow');
		$(this).removeClass('yellow');
		$(this).parent().removeClass('yellow');		
	});
	
	$('.paid_today').live('focus',function()
	{	
		$(this).parent().parent().children('.amt_line1').addClass('grey');
		$(this).addClass('yellow');
		$(this).parent().addClass('yellow');
		resize_tag('paid_today', 0);
	});
	
	$('.paid_today').live('blur',function()
	{	
		$(this).parent().parent().children('.amt_line1').removeClass('grey'); // remove yellow/grey for getting fafafa color
		$(this).removeClass('yellow');
		$(this).parent().removeClass('yellow');
		resize_tag('paid_today', 1);
		
	});
	
	$('.service-invoice-field-tax').live('focus',function()
	{	
	$('.job-popup').hide();
	});

	
	/******For Service create pages*******/
	
	$('.service-invoice-field-desc').live('focus',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','1px solid #67b2f0');
		}
	});
	$('.service-invoice-field-desc').live('blur',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','none');
		}
	});
	
	$('.amount').live('focus',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','1px solid #67b2f0');
		}
	});
	$('.amount').live('blur',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border','none');
		}
	});
	
	$('.tb-date').live('focus',function()
	{	
	$(this).parent().css('border-top','1px solid #67b2f0');
	$(this).parent().css('border-bottom','1px solid #67b2f0');
	$('.name-salesItem1').css('border-left','1px solid #67b2f0');
	$(this).parent().parent().parent().find('.timebillingviewItem1').css('border-right','1px solid #67b2f0');
	});
	$('.tb-date').live('blur',function()
	{	
	$(this).parent().css('border-top','none');
	$(this).parent().css('border-bottom','none');
	$('.name-salesItem1').css('border-left','1px solid #CED1D2');
	$('.timebillingviewItem1').css('border-right','1px solid #CED1D2');
	});
	
	$('.description').live('focus',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border-top','1px solid #67b2f0');
			$(this).parent().css('border-bottom','1px solid #67b2f0');
			$(this).parent().parent().parent().parent().parent().find('.timebillingviewItem3').css('border-right','1px solid #67b2f0');
			$(this).parent().parent().parent().parent().find('.timebillingviewItem2').css('border-right','1px solid #67b2f0');
		}
	});
	$('.description').live('blur',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border-top','none');
			$(this).parent().css('border-bottom','none');
			$('.timebillingviewItem3').css('border-right','1px solid #CED1D2');
			$('.timebillingviewItem2').css('border-right','1px solid #CED1D2');
		}
	});
	
	$('.units').live('focus',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border-top','1px solid #67b2f0');
			$(this).parent().css('border-bottom','1px solid #67b2f0');
			$(this).parent().parent().parent().parent().parent().parent().find('.timebillingviewItem4').css('border-right','1px solid #67b2f0');
			$(this).parent().parent().parent().parent().parent().find('.timebillingviewItem3').css('border-right','1px solid #67b2f0');
		}
	});
	$('.units').live('blur',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border-top','none');
			$(this).parent().css('border-bottom','none');
			$('.timebillingviewItem4').css('border-right','1px solid #CED1D2');
			$('.timebillingviewItem3').css('border-right','1px solid #CED1D2');
		}
	});
	
	$('.rate').live('focus',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border-top','1px solid #67b2f0');
			$(this).parent().css('border-bottom','1px solid #67b2f0');
			$(this).parent().parent().parent().parent().parent().parent().parent().find('.timebillingviewItem5').css('border-right','1px solid #67b2f0');
			$(this).parent().parent().parent().parent().parent().parent().find('.timebillingviewItem4').css('border-right','1px solid #67b2f0');
		}
	});
	$('.rate').live('blur',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border-top','none');

			$(this).parent().css('border-bottom','none');
			$('.timebillingviewItem5').css('border-right','1px solid #CED1D2');
			$('.timebillingviewItem4').css('border-right','1px solid #CED1D2');
		}
	});
	
	$('.amount').live('focus',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border-top','1px solid #67b2f0');
			$(this).parent().css('border-bottom','1px solid #67b2f0');
			$(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.timebillingviewItem6').css('border-right','1px solid #67b2f0');
			$(this).parent().parent().parent().parent().parent().parent().parent().find('.timebillingviewItem5').css('border-right','1px solid #67b2f0');
		}
	});
	$('.amount').live('blur',function()
	{	
		if($('.item-container').is(':visible')){
			$(this).parent().css('border-top','none');
			$(this).parent().css('border-bottom','none');
			$('.timebillingviewItem6').css('border-right','1px solid #CED1D2');
			$('.timebillingviewItem5').css('border-right','1px solid #CED1D2');
		}
	});
	
	$('.emp_field').live('focus',function(){
	selected_item =$(this);
	var left = $('.filter_employees').offset().left;
	var top  = $('.emp_field').offset().top; 
	$('.employee-popup').css({"left":left+8,"top":top+33});
	$('.employee-popup').toggle();
	});
	
	function delete_create_edit(content){
		$('.'+content).remove();
		var total = $("#total_items").val();
		total = parseInt(total)-1;
		$("#total_items").val(total);
		calculate_sub_total();
		hide_deleted_popups();
	}
	
	function hide_deleted_popups(){
		$('.error-popup-customer').hide();
		$('.error-popup-payment').hide();
		$('.error-popup-paid-amount').hide();
		sale_type	=	$("#sale_type").val();
		if(sale_type == '1' || sale_type == '2' || sale_type == '3'){
		/**Hide popup in items page**/
			$('.error-popup-item-number').hide();
			$('.error-popup-quantity').hide();
			$('.error-popup-price').hide();
		}
		if(sale_type == '4' || sale_type == '5' || sale_type == '6'){
		/**Hide popup in service page**/
			$('.error-popup-account').hide();
			$('.error-popup-amount').hide();
		}
		if(sale_type == '7' || sale_type == '8' || sale_type == '9'){
		/**Hide popup in timebilling page**/
			$('.error-popup-hours').hide();
			$('.error-popup-activity').hide();
			$('.error-popup-rate').hide();
		}
	}
	
	//rolls
	String.prototype.width = function(font) {
	  var f = font || '12px HelveticaNeueRegular',
	  		o = $('<div>' + this + '</div>')
		    .css({'position': 'absolute',  'white-space': 'nowrap', 'visibility': 'hidden', 'font': f})
		    .appendTo($('body')),
		      w = o.width();
	  o.remove();	
	  return w;
	};
	
	//  Initialize the amount fields.
	$(document).ready(function(){
		if(location.href.indexOf('create')!=-1 || location.href.indexOf('edit')!=-1){
			resize_input_box(null, 1);
			
			if(location.href.indexOf('edit')!=-1){
				var onetime_flag=1;
				$('.item_price').each(function(){
					//console.log(this);
					if(onetime_flag>0)
					{
						onetime_flag=0;
						//calculate_item_total1(this);							
					}
					resize_tag(this, 1);
				});
				
				$('.total').each(function(){
				//	console.log(this);
					resize_tag(this, 1);
				});

				$('.amount-service').each(function(){ // service				
					resize_tag(this, 1);
				});
								
				$('.amount').each(function(){ // timebilling 				
					resize_tag(this, 1);
				});
				
				$('.rate').each(function(){	//timebilling
					resize_tag(this, 1);
				});
			}	
		}
	});
	
	function resize_input_box(htmlobj, onready){
		if(htmlobj==undefined)
			htmlobj = null;
		if(onready == undefined)
			onready = 0;
		
		if(htmlobj!=null&&onready==0){
			var obj=$(htmlobj);
			var gparent=obj.parent().parent();			
		}
		sale_type	=	$("#sale_type").val();
		switch(sale_type){
			case '1':case '2':case '3': // item
					resize_tag('freight', onready);			
					if(htmlobj!=null&&onready==0){
						resize_tag(gparent.find('.item_price'),0);
						resize_tag(gparent.find('.total'),0);
					} else {
						resize_tag('price', onready);
						resize_tag('tlt_amt', onready);					
					}		
				break;
			
			case '4':case '5':case '6': //service
				resize_tag('freight', onready);
				if(htmlobj!=null&&onready==0){
					resize_tag(gparent.find('.amount-service'),0); // from class
					
				} else {				
					resize_tag('amount-service', onready); // from id
				}			
				break;
				
			case '7':case '8':case '9'://timebilling
				if(htmlobj!=null&&onready==0){
					resize_tag(gparent.find('.rate'),0);
					resize_tag(gparent.find('.amount'),0);
				} else {
					resize_tag('rate', onready);
					resize_tag('amount', onready);
				}			
				break;

			default:
				break;			
		}
		resize_tag('subtotal', onready);
		resize_tag('total_payment', onready);
		resize_tag('paid_today', onready);
		resize_tag('balance_due', onready);
		resize_tag('early_payment_discount', onready);
		resize_tag('late_payment_charge', onready);
	}
	
	function resize_tag(idname, chk){
		var MAX_LENGTH=20;
		var MIN_LENGTH=0;
		if(typeof(idname)== 'string'){
			obj=$('#'+idname);
		} else{
			obj = $(idname);
		}
		if(typeof (obj)!='undefined' && typeof(obj.val()) != 'undefined'){
			if(chk == 1 && (obj.val().length<=MIN_LENGTH)){
				obj.val('0.00');
			}

			if(obj.val().length<MAX_LENGTH){
				if(typeof(obj.css('font-size'))!=undefined && typeof(obj.css('font-family'))!=undefined){
					font=obj.css('font-size')+" "+obj.css('font-family');
				}
				obj.css('cssText','width:'+((obj.val()).width(font)+1)+'px !important');
			}
		}		
	}	
	
	function toggle_object(idname, val){		
		obj=obj=$('#'+idname);
		if(val == 1){
			obj.hide();			
		} else{
			obj.show();
		}		
	}

	$('.amt_line2').live('click', function(){
		$(this).children('.amt_ipt1').focus();
	});

	/**
	 * @Description	: Updation of Total in sales edit
	 * @Params		: saleId and array of total
	**/
	
	function updateTotal(saleId,subtotal,total_payment,balance){
		$.ajax({
			url		: '/sales/update_items_row/'+saleId,
			type	: 'POST',
			data	: 'subtotal='+subtotal+'&total_payment='+total_payment+'&balance='+balance,
			success: function(r){ 
				if(r[0]['error'] != "1") {
					window.location='/sales/edit/'+saleId;
				} 
				else{
					console.log("Error");
				}
			}
		});
	}
	
	/**
	 * @Description	: Updation of deleted records in sales
	 * @Params		: type, sale_id and saleId
	 * type = 1 // for items
	 * type = 2 // for service
	 * type = 3 // for timebilling
	**/
	function update_deleted_records(type,sale_id,saleId){
		$(".delete_row_"+sale_id).css("background","url('/media/images/new-signup/loader.gif') 50% 50% no-repeat");
		if(type=='1'){			// For Items
			$.ajax({
				url : '/sales/delete_items_row_edit/'+sale_id+'/'+saleId,
				async : false,
				success: function(r){
					$('.delete_row').hide();
					if(r[0]['error'] != "1") {
						object	=	JSON.parse(r);
							updateTotal(saleId,object.subtotal,object.total_payment,object.balance);
						if($('.name-salesItem1').length>1){
							$('.delete_row_'+sale_id).hide();
							hide_deleted_popups();
						}
					} 
					else{
						console.log("Error");
					}
				}
			});
		}
		else if(type=='2'){				//For Service
			$.ajax({
				url : '/sales/delete_service_row_edit/'+sale_id+'/'+saleId,
				async : false,
				success: function(r){
					$('.delete_row').hide();
					if(r[0]['error'] != "1") {
						object	=	JSON.parse(r);
						updateTotal(saleId,object.subtotal,object.total_payment,object.balance);
						if($('.name-salesItem1').length>1){
							$('.delete_row_'+sale_id).hide();
							hide_deleted_popups();
						}
					} 
					else{
						console.log("Error");
					}
				}
			});
		}
		else {				//For Timebilling
			$.ajax({
				url : '/sales/delete_tb_row_edit/'+sale_id+'/'+saleId,
				async : false,
				success: function(r){ 
				$('.delete_row').hide();
					if(r[0]['error'] != "1") {
						object	=	JSON.parse(r);
						updateTotal(saleId,object.subtotal,object.total_payment,object.balance);
						if($('.name-salesItem1').length>1){
							$('.delete_row_'+sale_id).hide();
							hide_deleted_popups();
						}
					}
					else{
						console.log("Error");
					}
				}
			});
		}
	}
	
	$(document).ready(function() 
	{
		if($(window).width() >= 701 && $(window).width() < 970) 
		{
		$('.only_balance').text('Balance:');
		}
		$(window).resize(function()
		{
		if($(window).width() >= 701 && $(window).width() < 970) 
			{
			$('.only_balance').text('Balance:');
			}
		});	 
	/**
	 * @Description	: onfocus removing 0 in text fields
	**/
	 $(".item_price,.quantity,.amount-service,.units,.rate").live("focus",function(){
	 	if($(this).val()==0)
	 	{
	 		$(this).val('');
	 	}
	 });
	 
	 $(".item_price,.quantity,.amount-service,.units,.rate").live("blur",function(){
	     if($(this).val()=='')
	     	{
	     		$(this).val('0.00');
	     	}
	     });      
	});
	
	function hide_popup(chk){
		if($(chk).hasClass('customer-name-label-span')){
			$('.error-popup-customer').hide();
		}		
		sale_type	=	$("#sale_type").val();
		if(sale_type == '1' || sale_type == '2' || sale_type == '3'){
		/**Hide popup in items page**/
			if($(chk).hasClass('service-invoice-field-item')){
				$('.error-popup-item-number').hide();
			} else if ($(chk).hasClass('quantity')){
				$('.error-popup-quantity').hide();
				if((parseInt($(chk).parent().next().find('.item_price').val()))>0){
					$('.error-popup-price').hide();
				}
			} else if ($(chk).hasClass('item_price')){
				$('.error-popup-price').hide();
			}
			$('.error-popup-paid-amount').hide();
			$('.error-popup-payment').hide();
		}
		else if(sale_type == '4' || sale_type == '5' || sale_type == '6'){
		/**Hide popup in service page**/
			if($(chk).hasClass('account-field')){
				$('.error-popup-account').hide();
			} else if ($(chk).hasClass('amount-service')){
				$('.error-popup-amount').hide();
			}
			$('.error-popup-paid-amount').hide();
			$('.error-popup-payment').hide();
		}
		else if(sale_type == '7' || sale_type == '8' || sale_type == '9'){
		/**Hide popup in timebilling page**/
			if($(chk).hasClass('timebilling_activity')){
				$('.error-popup-activity').hide();
			} else if ($(chk).hasClass('units')){
				$('.error-popup-hours').hide();
			}else if ($(chk).hasClass('rate')){
				$('.error-popup-rate').hide();
			}
			$('.error-popup-paid-amount').hide();
			$('.error-popup-payment').hide();
		}
	}
	
	
	/**
	 * @Description	: Sorting of to_be_synced page
	 * @Params		: chk (as this), sort_field, order
	**/	
	function sort_synced(chk,sort_field,order){
		var end = ($(chk).parent().attr("id"));
		if(end == undefined){
			end = $('.pagination-info-synced').text();
			end = end.substr(end.indexOf("-") + 1).trim();
			end = parseInt(end);
		}else{
			end = end.substr(end.indexOf("_") + 1).trim();
			end = parseInt(end);
		}
		var total_slips = $('.total_slips').val();
		if(total_slips < end){
			$('.pagination-info-synced').text('1-'+total_slips);
		}
		else{
			$('.pagination-info-synced').text('1-'+end);
		}
		if(sort_field == '0'){
			var selected = $('.sales-selected').text();
			if(end	==	selected){
				$('#dropdown-menu-bottom').hide();
				return false;
			}
			$('.ajax_loader_show').show();
			$.ajax({
				type: 'POST',
				url : '/sales/to_be_synced_pagination/'+sort_field+'/'+order,
				data: 'sort_field='+sort_field+'&order='+order+'&end='+end,
				dataType: 'HTML',
				success: function(r){ 
					$('#to_be_synced').html(r);
					$('.sort-arrow').hide();
					$('.ajax_loader_show').hide();
				}
			});
			if($('#sales_'+end).hasClass('all_views') == true){
				$(".sales-selected").text('All');
			}else{
				$(".sales-selected").text(end);
			}
		}
		else{
			var check = ($(chk).attr("class"));
			$('.'+check+' img').show();
			$('.'+check+' a').show();
			$.ajax({
				type: 'POST',
				url : '/sales/to_be_synced_pagination/'+sort_field+'/'+order,
				data: 'sort_field='+sort_field+'&order='+order+'&end='+end,
				dataType: 'HTML',
				success: function(r){ 
					$('#to_be_synced').html(r);
					$('.sort-arrow').hide();
					$('.'+check+' a').css('display','block');
				}
			});
		}
	}
		
		/**
		 * @Description	: loading an iframe in payment page whenever an error occurs
		 * @Params		: url and response description 
		**/
		function loadResultPage(url,response_description) {
			$('#payment_frame').attr('src', url);
			$('.error_description').html("<img style='margin-right:6px;' src='/media/images/tt-new/reset_error.png'>"+response_description);
		}
		
		/**
		 * @Description	: Handling Text Overflow in popups in focus mode
		**/
		$('.ac_results ul li').live('mouseover',function(){
			if(($(this)[0].scrollWidth) > ($(this).width()+6)){		/** +6 we are adding for padding left **/
				$(this).css('text-overflow','none');
				$(this).css('white-space','normal');
				$(this).css('height','50px');
				$(this).css('line-height','26px');
			}
		});

		/**
		 * @Description	: Handling Text Overflow in popups in blur mode
		**/
		$('.ac_results ul li').live('mouseout',function(){
			$(this).css('text-overflow','ellipsis');
			$(this).css('white-space','nowrap');
			$(this).css('height','38px');
			$(this).css('line-height','39px');
		});
		
		/**
		 * @Description	: Opening/Closing of Individual/Company dropdown
		**/
		$(document).bind('click', function(e) {
			var $clicked = $(e.target);
			if ($clicked.hasClass('individual_dropdown')) { 
				$('.individual_company').css('display','block');
				$('.cash-cc-btns').css('border-radius','6px 6px 0 0');
			}else{
				$('.individual_company').css('display','none');
				$('.cash-cc-btns').css('border-radius','6px 6px 6px 6px');
			}
		});
		
		/**
		 * @Description	: Selecting if Individual/Company from dropdown
		**/
		$('.individual_company').live('click',function(){
			$('.individual_text').text($(this).text());
			if($(this).hasClass('individual')){
				document.getElementById("is_individual").value = '1';
				$('#lname .line1-company-name').text('Last Name');
				$('#fname').show();
				$('.names #lname').css('cssText','border-radius: 0 0 6px 6px !important');
			}else{
				document.getElementById("is_individual").value = '0';
				$('#lname .line1-company-name').text('Company Name');
				$('#fname').hide();
				$('#fname').find('#first_name').val('');
				$('.names #lname').css('cssText','border-radius: 6px 6px 6px 6px !important');
			}

		});
		
		/**
		 * @Description	: Scrolltop as zero for all popups 
		 * 				if opened on blank input field
		**/
		$('.ac_input').live('click',function(){
			if($(this).val()==''){
				$('.ac_results ul').scrollTop(0);
			}
		});
		
		/**
		 * @Description	: Frontend Validation for sales popups
		**/
		$(document).ready(function(){
			if($('.item-container-phone').is(":visible")){
				append	= '.item-container-phone';
			} else {
				append	= '.item-container';
			}
			$(append+' .popup_validate , .customer-name-label-span').live('click keydown',function(){
				$(append+' .popup_validate , .customer-name-label-span').each(function() {
					var field_value = $(this).val();
					if($(this).is(":focus")){
						$('.popup-error').hide();
						return false;
					}
					if(field_value == '' || field_value == '0' || field_value == '0.00'){
						checkCustomer(this);
						return false;
					}
				});
			});
		});
		
		
		/**
		 * @Description	: Generic function for frontend validation
		**/
		function checkCustomer(chk){
			var sale_type = $('#sale_type').val();
			if($('.customer-name-label-span').val()==''){
				left_img = $('#openCustNamesPopup').offset().left;
				top_img  = $('#openCustNamesPopup').offset().top;
				$('.error-popup-customer').css({"left":left_img+42,"top":top_img+8});
				$('.error-popup-customer').show();
				$('.customer-name-add-order').addClass('yellow');
				$('#customer_text').addClass('yellow-background');
				return true;
			} 
			if(sale_type == '1' || sale_type == '2' || sale_type == '3'){		// For Items Page
				if ($(chk).hasClass('service-invoice-field-item') && $(chk).val()==''){
					left_img = $(chk).offset().left;
					top_img = $(chk).offset().top;
					$('.error-popup-item-number').css({"left":left_img+60,"top":top_img-5});
					$('.error-popup-item-number').show();
					return true;
				} 
				else if ($(chk).hasClass('quantity') && ($(chk).val()=='0' || $(chk).val()=='' || $(chk).val()=='0.00')){
					left_img = $(chk).offset().left;
					top_img = $(chk).offset().top;
					$('.error-popup-quantity').css({"left":left_img+50,"top":top_img-5});
					$('.error-popup-quantity').show();
					return true;
				} else if ($(chk).hasClass('item_price') && ($(chk).val()=='0' || $(chk).val()=='' || $(chk).val()=='0.00')){
					left_img	=	$(chk).offset().left;
					top_img		=	$(chk).offset().top;
					$('.error-popup-price').css({"left":left_img+40,"top":top_img-5});
					$('.error-popup-price').show();
					return true;
				}
			} else if(sale_type == '4' || sale_type == '5' || sale_type == '6'){	// For Service Page
				if ($(chk).hasClass('account-field') && $(chk).val()==''){
					left_img = $(chk).offset().left;
					top_img  = $(chk).offset().top;
					$('.error-popup-account').css({"left":left_img+60,"top":top_img-5});
					$('.error-popup-account').show();
					return true;
				} else if ($(chk).hasClass('amount-service') && ($(chk).val()=='0' || $(chk).val()=='' || $(chk).val()=='0.00')){
					left_img = $(chk).offset().left;
					top_img  = $(chk).offset().top;
					$('.error-popup-amount').css({"left":left_img+35,"top":top_img-5});
					$('.error-popup-amount').show();
					return true;
				}
			} else {
				if ($(chk).hasClass('timebilling_activity') && $(chk).val()==''){
					left_img = $(chk).offset().left;
					top_img  = $(chk).offset().top;
					$('.error-popup-activity').css({"left":left_img+30,"top":top_img-5});
					$('.error-popup-activity').show();
					return true;
				} else if ($(chk).hasClass('units') && ($(chk).val()=='0' || $(chk).val()=='' || $(chk).val()=='0.00')){
					if($('.item-container').is(":visible")){
						left_img = $(chk).parent().prev().offset().left;
						top_img  = $(chk).parent().prev().offset().top;
					} else {
						left_img = $(chk).offset().left-100;
						top_img  = $(chk).offset().top;
					}
					$('.error-popup-hours').css({"left":left_img,"top":top_img-3});
					$('.error-popup-hours').show();
					return true;
				} else if ($(chk).hasClass('rate') && ($(chk).val()=='0' || $(chk).val()=='' || $(chk).val()=='0.00')){
					left_img = $(chk).offset().left;
					top_img  = $(chk).offset().top;
					$('.error-popup-rate').css({"left":left_img+40,"top":top_img-3});
					$('.error-popup-rate').show();
					return true;
				}
			}
			return false;
		}
		
		/**Enhancements in Phone Mode**/
		$('.service-invoice-field-item,.service-invoice-field-job').live('focus',function(){
			if($('.item-container-phone').is(":visible")){
				$(this).parent().find('label').addClass('grey');
				$(this).addClass('yellow-background');
				$(this).parent().addClass('yellow-background');
			} 
		});
		
		$('.service-invoice-field-item,.service-invoice-field-job').live('blur',function(){
			if($('.item-container-phone').is(":visible")){
				$(this).parent().find('label').removeClass('grey');
				$(this).removeClass('yellow-background');
				$(this).parent().removeClass('yellow-background');
			} 
		});


		/**
		 * @Description	: View Sales/Customer/Jobs # overflow scroll issue
		**/
		$(document).ready(function(){
			if(($('#slips-view .dropdown-menu li').length) > 4){
				$('#slips-view .dropdown-menu').css('overflow','scroll');
			} else {
				$('#slips-view .dropdown-menu').css('overflow','hidden');
			}
		});
		
		/**
		 * @Description	: Quick add for last created sale
		**/
		function quick_add(URL,last_updated){
			if(last_updated == '0'){				// Handling the condition when no sales exists i.e. first time
				last_updated	=	'1';
			}
			var quick_add_url	= URL+'/sales/create/'+last_updated;
			window.location		= quick_add_url;
		}

		$(document).ready(function() {
			var field_width = $('.info-block').width()-142;
			$('.color-change-pm,.color-change-sp,.color-change-cm,.color-change-rs,.color-change-sm,.change-color-custom1,.change-color-custom2,.change-color-custom3,.change-color-custom4').css('width',field_width+'px');
			var customer_country_field	=	$('.outer_pack').width()-118;
			$('.customer_city,.customer_state').css('width',customer_country_field+'px');
			var address_fields_width	=	$('.address-info-1 .info').width()-148;
			$('.info .employee_address_field').css('width',address_fields_width+'px');
			if($(window).width() >= 550 && $(window).width() <= 750){
				$('.info .employee_address_field').css('width','70%');
			}
			$(window).resize(function() {
				var field_width = $('.info-block').width()-142;
				$('.color-change-pm,.color-change-sp,.color-change-cm,.color-change-rs,.color-change-sm,.change-color-custom1,.change-color-custom2,.change-color-custom3,.change-color-custom4').css('width',field_width+'px');
				var customer_country_field	=	$('.outer_pack').width()-118;
				$('.customer_city,.customer_state').css('width',customer_country_field+'px');
				var address_fields_width	=	$('.address-info-1 .info').width()-148;
				$('.info .employee_address_field').css('width',address_fields_width+'px');
				if($(window).width() >= 751 && $(window).width() <= 800){
					$('.color-change-pm,.color-change-sp,.color-change-cm,.color-change-rs,.color-change-sm,.change-color-custom1,.change-color-custom2,.change-color-custom3,.change-color-custom4').css('width','162px');
				}
				if($(window).width() >= 550 && $(window).width() <= 750){
					$('.info .employee_address_field').css('width','70%');
				}
			});
			/**Setting onmouseover and onmouseout in tax_applied starts**/
			$('.tax_applied,.tool_tip').live('mouseover',function() {
				var value		=	$(this).text();
				var left 		=	$(this).offset().left;
				var top 		=	$(this).offset().top;
				var percentage	=	$(this).parent().find('.tax_applied_percentage').val();
				if(percentage != '' && percentage != undefined){
					var hint	=	"<div class='hint'>"+value+' ( '+percentage+'% ) '+"</div>";
				} else {
					var hint	=	"<div class='hint'>"+value+"</div>";
				}
				$('body').append(hint);
				if($(window).width()<1200){
					$('.hint').css({"left":left+30,"top":top-4});
				} else {
					$('.hint').css({"left":left+50,"top":top-4});
				}
			});
			$('body,.tax_applied,.tool_tip').live('mouseout',function() {
				if($(this).hasClass('salesItem-name-field')){
					return false;	
				} else {
					$('body').find('.hint').remove();
				}
			});
			
			/**Setting onmouseover and onmouseout in tax_applied ends**/
		
			$('.sales_person_input,.referal_input,.payment_input,.shipping_input,.first-field,.second-field,.third-field').keypress(function (e){
				if (e.keyCode == 46 || e.keyCode == 8 || e.keyCode == 37 || e.keyCode == 39) {
					return true;
				} else {
					return false;
				}
			});
		});



/**For Terms Popup starts**/
$(document).bind('click', function(e) {
    var $clicked = $(e.target);
    if($clicked.hasClass('terms_arrow')){ 
    	$clicked.parent().parent().append($('.terms_popup'));
		$('.terms_popup').slideDown(500);
    } 
    else if($clicked.hasClass('terms_popup') || $clicked.hasClass('popup-items2') || $clicked.hasClass('layout-names')|| $clicked.hasClass('layout-label-names') || $clicked.hasClass('terms_fields') || $clicked.hasClass('terms_textfield') || $clicked.hasClass('terms_textfield1') || $clicked.hasClass('terms-popup-list')){
    	$('.terms_popup').show();
    } else if(($clicked.hasClass('retina-arrow') && $clicked.hasClass('service-arrow-left') && $clicked.hasClass('pad-right-1'))){
    	$('.terms_popup').show();
    	$('.terms_popup .popup-items2').hide();
    	$('.terms_popup .terms_second_content').fadeIn(1000);
    } else if($clicked.hasClass('back_terms')){
    	$('.terms_popup').show();
    	$('.terms_popup .terms_second_content').hide();
    	$('.terms_popup .popup-items2').fadeIn(1000);
    } else {
    	$('.terms_popup').hide();
    }
    if($('.terms_popup .terms_second_content').is(":visible")){
    	$('.terms_popup .term_label').text("Payment is Due");
    	$('.terms_popup .back_terms').show();
    	$('.terms_popup .save_terms').hide();
    }else{
    	$('.terms_popup .term_label').text("Terms");
    	$('.terms_popup .back_terms').hide();
    	$('.terms_popup .save_terms').show();
    }
});

function terms_list_select(obj){
	var text	=	trim($(obj).text());
	$('#payment_is_due').val(text);
	$('.second_select_terms').css("background","none");
	$('.payment_is_due_hidden_value').val($(obj).find('input').val());
	$(obj).css("background","url('/media/images/tt-new/enable.png') 98% 50% no-repeat");
}
$('.save_terms').live('click',function(){
	var field			= 	$('.payment_is_due_hidden_value').val();
	var bal_due_days	=	$('#balance_due_days').val();
	var terms_value = '';
	switch(field){
		case '0': terms_value = 'C.O.D.';break;
		case '1': terms_value = 'Prepaid';break;
		case '2': terms_value = 'NET '+bal_due_days;break;
		case '3': terms_value = 'NET '+bal_due_days;break;
		case '4': terms_value = 'NET '+bal_due_days;break;
		case '5': terms_value = 'NET '+bal_due_days;break;
		default:terms_value = '';break;
	}
	$('#terms').val(terms_value);
});
/**For Terms Popup ends**/

/**Send mail in sales view for transactions**/
function mailRecipt(sale,slip_detail,payment,mode){
	$('.email_loader').show();
	JSONstr		= JSON.stringify(sale);
	JSONslip	= JSON.stringify(slip_detail);
	JSONpayment	= JSON.stringify(payment);
	$.ajax({
		type: 'POST',
		url : '/sales/mailRecipt/'+mode,
		dataType: 'json',
		async:false,
		data: {'sale':JSONstr,'slip_detail':JSONslip,'payment_info':JSONpayment},
		success: function(r){
			$('.email_loader').hide();
		}
	});
	if(mode == 'pdf'){
		sale['company_or_lastname'] = sale['company_or_lastname'].replace(/[^a-zA-Z0-9]/g,'');
		var file = sale['company_or_lastname']+'_Invoice_'+sale['sale_number'];
		window.location = '/sales/viewPdf/'+file;
	}
}

function set_billing_rate(){
	if($('#which_rate').val() == 'E'){
		var emp_rec_id =$('.employee_record_id').val(); 
		$.ajax({
			type: 'POST',
			url:  '/data/get_billing_rate_employee/'+emp_rec_id,
			success:function(r){
				$('#rate').val(r);
				if(unitValidator(document.getElementById('units').value))
					document.getElementById('total').value = document.getElementById('units').value * document.getElementById('rate').value;
			}
		});
	}
}

function show_versioning_message(chk){
	var versioning_message = "<div class='versioning_message'>This feature is not available for those using older versions of AccountEdge.</div>";
	var left 		=	$(chk).offset().left;
	var top 		=	$(chk).offset().top;
	$('body').append(versioning_message);
	$('.versioning_message').css({"left":left-50,"top":top+45});
}
$('body').live('mouseout',function() {
	if($(this).hasClass('header-label2')){
		return false;	
	} else {
		$('body').find('.versioning_message').remove();
	}
});