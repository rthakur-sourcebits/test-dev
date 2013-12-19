<!DOCTYPE html>
<html>
	<head>
	    <title>Time Tracker</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	    <!-- Bootstrap -->
		<link type="text/css" href="http://192.168.19.19/media/css/bootstrap.min.css" rel="stylesheet" />
		<link type="text/css" href="http://192.168.19.19/media/css/bootstrap-responsive.css" rel="stylesheet" />
		<link type="text/css" href="http://192.168.19.19/media/css/bootstrap.css" rel="stylesheet" />
		<link type="text/css" href="http://192.168.19.19/media/css/time_tracker.css" rel="stylesheet" />
		<script type="text/javascript" src="http://192.168.19.19/media/scripts/bootstrap.min.js"></script>
		<link type="text/css" href="http://192.168.19.19/media/css/jScrollPane.css" rel="stylesheet" />
		<script type="text/javascript" src="http://192.168.19.19/media/scripts/jquery-ui/js/jquery-1.4.2.min.js"></script>
		<link type="text/css" href="http://192.168.19.19/media/css/jqtransform.css" rel="stylesheet" />
		<script type="text/javascript" src="http://192.168.19.19/media/scripts/admin.js"></script>
		<script type="text/javascript" src="http://192.168.19.19/media/scripts/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" src="http://192.168.19.19/media/scripts/jScrollPane.js"></script>
		<script type="text/javascript" src="http://192.168.19.19/media/scripts/main.js"></script>
		<script type="text/javascript" src="http://192.168.19.19/media/scripts/jqtransform.js"></script>
		<link type="text/css" href="http://192.168.19.19/media/scripts/jquery-ui-admin/css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="http://192.168.19.19/media/scripts/jquery-ui-admin/js/jquery-ui-1.8.13.custom.min.js"></script>  	
	</head>

	<body>
					 <div class="wrapper">
			 				 		<div id="header-logo">
						<img src="http://192.168.19.19/media/images/tt-new/logo1.jpg" width="133" height="20" alt="abc2">
					</div> 
					<div id="my-account">
	<div class="btn-group">
		<a class="btn dropdown-toggle" id="account-button" data-toggle="dropdown" href="#"></a>
		<ul class="dropdown-menu"> <!-- dropdown menu links -->

		</ul>
	</div>
</div>


<div class="clear"></div>
<div class="popup-menu">
	<div class="pointer"></div>
	<ul>
		<li class=""><a href="http://192.168.19.19/admin">Admin</a></li>
		<li class=""><a href="http://192.168.19.19/admin/settings">Settings</a></li>

		<li class=""><a href="#" onclick="change_admin_password_form();">Change Password</a></li>
		<li class=""><a href="http://support.accountedge.com/kb/time-tracker" target="_blank">FAQ</a></li>
		<li class=""><a href="http://support.accountedge.com/discussions/time-tracker" target="_blank">Forum</a></li>
		<li class=""><a href="http://accountedge.com/help" target="_blank">Email Support</a></li>
		<li class=""><a href="http://192.168.19.19/admin/loginasuser">Log in as User</a></li>
		<li class=""><a href="http://192.168.19.19/admin/logout">Log out</a></li>

	</ul>
</div>					
			 					<script type="text/javascript">

$(document).ready(function(){
    /*applyHeight();
    $(window).resize(function(){
        applyHeight();
    });
    
    function applyHeight(){
        var wrapheight = $(window).height();
		var listheight = $(window).height() - 40;
        $('#wrapper').css('height', wrapheight + 'px');
		$('#pane1, .jScrollPaneContainer').css('height', listheight);

        $('.scroll-pane').jScrollPane({
            scrollbarWidth: 13,
            scrollbarMargin: 0
        });
    }
	*/
	$('table tr:last-child td').css('border-bottom','0px');
	
	
	
	/*$(window).unload(function() {
		alert(334);
		if(form_update) {
			$("#greyout").show();
			$("#cancel_user_edit");
			return false;
		} else {
			return true;
		}
	});*/
});

function changePic(chk,pic){
	if (chk.checked){
		document.pic.src="images/newimage.png";
	}else{
		document.pic.src="images/oldimage.png";
	}
}
</script>
<script type="text/javascript">
var form_update = false;
var url_redirect = "";
$(document).ready(function(){
    applyHeight();
    $(window).resize(function(){
        applyHeight();
    });
	$('table tr:last-child td').css('border-bottom','0px');

	$('#edit_user_form input').focus(function() {
		form_update = true;
	});
});

function applyHeight(){
    var wrapheight = $('.container').height();
	$('.background, #middle').css('height', wrapheight + 'px');
	var listheight = $('#middle').height();
	$('#pane1, .jScrollPaneContainer').css('height', listheight);

	//$('#wrapper').css('height', wrapheight);
    $('.scroll-pane').jScrollPane({
        scrollbarWidth: 13,
        scrollbarMargin: 0,
        arrowScrollOnHover: true 
    });

   
}

function edit_user_account(uid, url)
{
	url_redirect	=	url+"/"+uid;
	if(form_update) {
		$("#grayout").show();
		$("#cancel_user_edit").show();
		return false;
	} else {
		location.href	=	url_redirect;
	}
}

function do_not_save_redirect()
{
	location.href	=	url_redirect;
}

function set_focus()
{
	form_update = true;
}
</script>

<div class="def">

	<div id="header-logo">
		<!--  <img src="images/abc1.jpg" width="133" height="20" alt="abc2"> -->
		<div id="my-account"></div>
	</div>

	<div id="abcd" class="navbar" style="background-image:url('images2/menu_image.png');background-repeat:repeat-x;">
		<ul class="nav">
						<li><a href="http://192.168.19.19/admin/users/1" class="btn-1 left left"></a></li>
						<li><a href="http://192.168.19.19/admin/users/2" class="btn-2 left left"></a></li>
						<li><a href="http://192.168.19.19/admin/users/3" class="btn-1 left left"></a></li>
		</ul>
  
    	<div><img src="images2/menu_image.png"/></div>

 		<div class="input-append" id="search-nav">

  			<input class="span3" id="appendedInputButton" size="16" type="text"><button id="search-btn" class="btn" type="button"></button>
		</div>
	</div>
</div>
<div class="row-fluid" id="row-fluid">
<div class="span4" id="sidebar">
	<div id="sidebar_nav">
		<div class="span11" id="sidebar3">
						<a href="#" name="user_1" onclick="edit_user_account('12', 'http://192.168.19.19/admin/edit')">
	  				<div id='nav1'>

	  					<div id="label">
	  						<label><b>Balan Singh</b></label>
	  							  							<label>joe@myob.com</label>
	  							  					</div>
	  				</div>
  				</a>
								<a href="#" name="user_2" onclick="edit_user_account('161', 'http://192.168.19.19/admin/edit')">
	  				<div id='nav1-not-act'>

	  					<div id="label">
	  						<label><b>Emp Test</b></label>
	  							  							<label>Not Activated</label>
	  							  					</div>
	  				</div>
  				</a>
								<a href="#" name="user_3" onclick="edit_user_account('11', 'http://192.168.19.19/admin/edit')">
	  				<div id='nav1'>

	  					<div id="label">
	  						<label><b>John Sb</b></label>
	  							  							<label>abcd@ttt.com</label>
	  							  					</div>
	  				</div>
  				</a>
								
								<a href="#" name="user_5" onclick="edit_user_account('1', 'http://192.168.19.19/admin/edit')">
	  				<div id='nav1'>

	  					<div id="label">
	  						<label><b>M</b></label>
	  							  							<label>shijith@sourcebits.com</label>
	  							  					</div>
	  				</div>
  				</a>
								<a href="#" name="user_6" onclick="edit_user_account('16', 'http://192.168.19.19/admin/edit')">
	  				<div id='nav1-not-act'>

	  					<div id="label">
	  						<label><b>Wade Barret</b></label>
	  							  							<label>Not Activated</label>
	  							  					</div>
	  				</div>
  				</a>
								<a href="#" name="user_7" onclick="edit_user_account('9', 'http://192.168.19.19/admin/edit')">
	  				<div id='nav1'>

	  					<div id="label">
	  						<label><b>Yaapa Sb</b></label>
	  							  							<label>test@sample.com</label>
	  							  					</div>
	  				</div>
  				</a>
								
							
  		</div>
	</div>    

</div>
 
	<div class="span1"></div>     
	<div class="span7">
		<div id="text-container4">
			<label style="color: #474747; font-size: 13pt"><big><b> Himanshu Arora<b></big></label>
		</div>	
		<div id="text-container5">
			<label style="color: #C3C3C3; font-size: 12pt"><big>Employee</big></label>
		</div>

		<div class="span10" id="text-container">
			<div class="span12" id="text-container1">
 				<div class="input-prepend" id="input-prepend">
  					<span class="add-on" id="input-medium"><font size="3" color=#B3B3B3>Email Address</font></span><input class="span6" id="prependedInput2"  type="text" >
 					<div id="fill-gap">
 					</div>
  
				</div>
				<div class="input-prepend" id="input-prepend1">

  					<span class="add-on" id="input-medium"><font size="3" color=#B3B3B3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Display Rate/Amount</font></span><!--<input class="span8" id="prependedInput"  type="text" > -->
 					<button class="btn btn-mini" data-toggle="buttons-checkbox" id="on-off" type="button"></button>
  					<div id="fill-gap"></div>
				</div>
				<div class="input-prepend" id="input-prepend1">
  					<span class="add-on" id="input-medium"><font size="3" color=#B3B3B3>Show jobs for selected customer</font></span><!-- <input class="span5" id="prependedInput"  type="text" > -->
  					<button class="btn btn-mini" data-toggle="buttons-checkbox"id="on-off" type="button"></button>
				</div>

			</div>
			<br/>
			<div class="span12" id="text-container2">
				<div class="input-prepend" id="input-prepend">
  					<span class="add-on" id="input-medium2"><font size="3" color=#B3B3B3>Total Slips Processed&nbsp;&nbsp;</font></span><input class="span7" id="prependedInput"  type="text" >
 					<div id="fill-gap"></div>
  
				</div>
				<div class="input-prepend" id="input-prepend1">

  					<span class="add-on" id="input-medium1"><font size="3" color=#B3B3B3>Total Synced Slips</font></span><input class="span5" id="prependedInput2"  type="text" >
  					<div id="fill-gap"></div>
  
				</div>
				<div class="input-prepend" id="input-prepend1">
  					<span class="add-on" id="input-medium1"><font size="3" color=#B3B3B3>Last Synced Date</font></span><input class="span4" id="prependedInput2"  type="text" > 
				</div>
			</div>
 			<div id="controls123" class="controls">

 				<button class="btn btn-small" id="cancel-btn" type="button"></button>
  				<button class="btn btn-small" id="save-btn" type="button"></button>
			</div>
		</div>
	</div>
</div>
           
			<div class="popup_admin" id='cancel_user_edit'>
				<h3 class="question">Do you want to cancel editing this card?</h3>

				<div class="alert-message">All changes made by you will be lost. This operation cannot be undone. <br/>If you want to save the changes you made, click "Save" 
					 button instead.
				</div>
				<a href="#" class="radius-5 button-1 left" onclick="cancel_edit_user_alert('cancel_user_edit');">Cancel</a>
				<a href="#" class="radius-5 alert-save right" onclick="save_user_form()">Save</a>
				<a href="#" class="radius-5 button-1 right" onclick="do_not_save_redirect();">Don't Save</a>
			</div>
			<div class="popup_admin" id='delete_user'> 	
				<h3 class="question"></h3>

				<div class="alert-message">Deleting this user will remove his records from the system. This operation cannot be undone.</div>
				<a href="#" class="radius-5 button-1 right" onclick="cancel_gray_out('delete_user');">Cancel</a>
				<a href="#" class="radius-5 button-1 right" onclick="delete_user_submit('http://192.168.19.19/admin/delete');">Delete</a>
			</div>
			
			
			
		</div>			 </div>
		 		 

		<div id="grayout" class='grayBG'></div>
		<div class="popup_admin" id='change_admin_password'>

			<div class="error_message" style="margin-bottom:3px;"></div> 
			<table cellpadding="0" cellspacing="0" class="change_admin_pass_table">
				<tr>
					<td>Current Password</td>
					<td><input type="password" name="current_password" value="" id="current_password" /></td>
				</tr>
				<tr colspan="2"><td>&nbsp;</td></tr>
				<tr>
					<td>New Password</td>

					<td><input type="password" name="new_password" value="" id="new_password" /></td>
				</tr>
				<tr colspan="2"><td>&nbsp;</td></tr>
				<tr>
					<td>Confirm Password</td>
					<td><input type="password" name="confirm_password" value="" id="confirm_password" /></td>
				</tr>
				<tr colspan="2"><td>&nbsp;</td></tr>

				<tr>
					<td colspan="2" align="center">
						<a href="#" class="radius-5 button-1 left" onclick="cancel_admin_popup('change_admin_password');">Cancel</a>
						<a href="#" class="radius-5 alert-save right" onclick="change_admin_password('http://192.168.19.19')">Save</a>
					</td>
				</tr>
			</table>
		</div>

		
		<div class="popup_admin" id='change_user_password'>
			<div class="error_message" style="margin-bottom:3px;"></div> 
			<table cellpadding="0" cellspacing="0" class="change_admin_pass_table">
				<tr>
					<td>Current Password</td>
					<td><input type="password" name="user_current_password" value="" id="user_current_password" /></td>
				</tr>
				<tr colspan="2"><td>&nbsp;</td></tr>
				<tr>

					<td>New Password</td>
					<td><input type="password" name="user_new_password" value="" id="user_new_password" /></td>
				</tr>
				<tr colspan="2"><td>&nbsp;</td></tr>
				<tr>
					<td>Confirm Password</td>
					<td><input type="password" name="user_confirm_password" value="" id="user_confirm_password" /></td>
				</tr>

				<tr colspan="2"><td>&nbsp;</td></tr>
				<tr>
					<td colspan="2" align="center">
						<a href="#" class="radius-5 button-1 left" onclick="cancel_admin_popup('change_user_password');">Cancel</a>
						<a href="#" class="radius-5 alert-save right" onclick="change_user_password('http://192.168.19.19')">Save</a>
					</td>
				</tr>
			</table>

		</div>
		
		<div class="popup_admin" id='reset_password_confirm'>
			<div class="error_message" style="margin-bottom:3px;"></div> 
			<table cellpadding="0" cellspacing="0" class="change_admin_pass_table">
				<tr>
					<td>Enter Admin Password</td>
					<td>
						<input type="password" name="admin_password" value="" id="admin_password" />
					</td>

					<td width="10%" style="display:none;" id="pass_reset_process_image"><img src="http://192.168.19.19/media/images/loading.gif" /></td>
				</tr>
				<tr colspan="2"><td>&nbsp;</td></tr>
				<tr>
					<td colspan="2" align="center">
						<a href="#" class="radius-5 button-1 left" onclick="cancel_admin_popup('reset_password_confirm');">Cancel</a>
						<a href="#" class="radius-5 alert-save right" onclick="reset_user_password('http://192.168.19.19')">Reset</a>
					</td>

				</tr>
			</table>
			<input type="hidden" name="user_id" id="user_id" value="" />
		</div>
		
		<div class="popup_admin" id="reset_password_success">
			<div class="success_message" align="center" style="font-weight:bold;">Password successfully updated and sent via email</div>
			<div align="center" style="margin-top:15px;"><a href="#" class="radius-5 ok-button-api" onclick="cancel_admin_popup('reset_password_success');">OK</a></div>
		</div>

		
		<div class="popup_admin" id="change_password_success">
			<div class="success_message" align="center" style="font-weight:bold;">Password successfully updated</div>
			<div align="center" style="margin-top:15px;"><a href="#" class="radius-5 ok-button-api" onclick="cancel_admin_popup('change_password_success');">OK</a></div>
		</div>
		<!-- 
						<div style="right: 5px; float: right; clear: both; position: relative; bottom: 0pt; margin-top: 180px;"><script type="text/javascript" src="https://sealserver.trustwave.com/seal.js?style=normal"></script></div>
				
		
			<div class="clear"></div>
		<div class="ae-webapp  clear-trust-logo">an AccountEdge web app</div>
		-->
	</body>
</html>