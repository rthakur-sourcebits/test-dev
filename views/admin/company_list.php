<!-- 
 * @File : admin.php
 * @Author: 
 * @Created: 15-11-2012
 * @Modified:  
 * @Description: Admin view for AccountEdge Cloud
   Copyright (c) 2012 Acclivity Group LLC 
-->
<script type="text/javascript">

$(document).ready(function(){
    applyHeight();    
    $(window).resize(function(){
        applyHeight();
    });
	$('table tr:last-child td').css('border-bottom','0px');
});

function applyHeight(){	
    var wrapheight = $('.container').height();
	$('.background, #middle').css('height', wrapheight + 'px');
	var listheight = $('#middle').height();
	$('#pane1, .jScrollPaneContainer').css('height', listheight);
	//$('#wrapper').css('height', wrapheight);
    $('.scroll-pane').jScrollPane({
        scrollbarWidth: 13,
        scrollbarMargin: 0
    });
}


function clear_popup_window()
{
	$('.cal-popup').hide();
}
</script>
<div class="admin-logo">
		<h1 class="logo"><a href="<?php echo SITEURL?>/admin">Time Tracker</a></h1>
	</div>
<div class="header">
	<div class="employee-list-heading">
		<?php
		if(!empty($_SESSION['selected_link_type']))
		{
			$class	=	$_SESSION['selected_link_type'] == 1?"btn-2 left":"btn-1 left";
		}
		else
		{
			$class	=	"btn-1 left";
		}
		?>
		<a href="<?php echo SITEURL; ?>/admin/company" class="<?php echo $class;?> left">
			<span class="bgleft"><span class="bgright"><span class="bgmid">Edit Company</span></span></span>
		</a>
		<?php
		if(!empty($_SESSION['selected_link_type']))
		{
			$class	=	$_SESSION['selected_link_type'] == 2?"btn-2 left":"btn-1 left";
		}
		else
		{
			$class	=	"btn-1 left";
		}
		?><!-- 
		<a href="<?php echo SITEURL; ?>/admin/home" class="<?php echo $class;?> left">
			<span class="bgleft"><span class="bgright"><span class="bgmid">Add New Company</span></span></span>
		</a> -->
		<a href="<?php echo SITEURL?>/admin/searchcompany" class="search-icon left">Search</a>
		<a href="#" onclick="prompt_eport_date_selection();" class="view-button left export-button-search rounded-ten" style="margin-left:10px;">Export</a>			
		<div class="clear"></div>
		
	</div>
	<?php if(empty($_SESSION['readonly_super_admin'])) {?>
			<a href="#" onclick="api_key_form('<?php echo SITEURL?>');" class="api-key">API Key</a>
	<?php } ?>
	<a href="<?php echo SITEURL."/admin/logout"; ?>" class="header-links">Logout</a>
	<div class="clear"></div>
</div>
<div class="background">
		<div class="middle" id="middle">
		</div>
</div>
<div class="employee-list">
	<div id="pane1" class="scroll-pane" tabindex="0">
		<ul id="activity">
		<?php
		//	echo "offline ".$offline;
			foreach($company_list as $company)
			{
				$first_selected = "";
				
				if($company['status'] == 1) {
					if(!empty($selectedCompany) AND $selectedCompany == $company['id'] && empty($offline)) {
						$first_selected	=	"selected";
					}
					echo '<li class="'.$first_selected.'"><a href="'.SITEURL.'/admin/edit_company/'.$company['id'].'">'.html_entity_decode($company['name']).'</a></li>';
				} else {
					if(!empty($selectedCompany) AND $selectedCompany == $company['id'] && isset($offline)) {
						$first_selected	=	"selected";
					}
					echo '<li class="'.$first_selected.'"><a href="'.SITEURL.'/admin/offlinecompany/'.$company['id'].'">'.html_entity_decode($company['name']).'</a></li>';
				}
				
			}
		?>
		</ul>
	</div>
</div>

<div class="popup_admin" id='api_key_form' style="width:450px !important;padding-right:32px !important;">
	<form  class='text_normal'>
		<h3 class="error_message" id="api_error" style="display:none;"></h3>
		<div class="alert-message" align="right" style="margin-top:5px;width:100%"><strong>Enter API Key</strong>&nbsp;&nbsp;&nbsp;<input type="text" class="api-key-input" name="api_key" value="" id="api_key" size="50"/></div>
		<div align="center" style="margin-top:5px;">
			<a href="#" class="radius-5 alert-save api-button" onclick="save_api_key('<?php echo SITEURL?>')">Save</a>
			<a href="#" class="radius-5 button-1 api-button" onclick="cancel_api_key_form();">Cancel</a>
		</div>
	</form>
</div>

<div class="popup_admin" id='api_key_form_success'>
	<div class="success_message" align="center" style="font-weight:bold;">API key successfully Updated</div>
	<div align="center" style="margin-top:15px;"><a href="#" class="radius-5 ok-button-api" onclick="go_back_to_welcome();">OK</a></div>
</div>


<div class="popup_admin" id="modify_expire_form" style="width:350px !important;padding-right:32px !important;">
	<form  class='text_normal'>
		<h3 class="error_message" id="exprie_error" style="display:none;"></h3>
		<p class="question">Select number of days you want to add more from the list and then click on Save.</p>
		<div class="message" align="center" style="margin-top:5px;width:100%">Days&nbsp;&nbsp;&nbsp;
			<select name="expire_date" id="expire_date">
			<?php 
				for($k=1;$k<=50;$k++) {
					?>
					<option value="<?php echo $k?>"><?php echo $k;?></option>
					<?php 
				}
			?>
				<option value="100">100</option>
				<option value="200">200</option>
				<option value="500">500</option>
				<option value="1000">1000</option>
				<option value="3000">3000</option>
				<option value="5000">5000</option>
			</select>
		</div>
		<div align="center" style="margin-top:5px;">
			<a href="#" class="radius-5 alert-save api-button" onclick="modify_expire_date('<?php echo SITEURL?>')">Save</a>
			<a href="#" class="radius-5 button-1 api-button" onclick="cancel_admin_popup('modify_expire_form');">Cancel</a>
		</div>
	</form>
</div>

<div class="popup_admin" id='modify_expire_success'>
	<div class="success_message" align="center" style="font-weight:bold;">Expire date successfully modified for this company.</div>
	<div align="center" style="margin-top:15px;"><a href="" class="radius-5 ok-button-api">OK</a></div>
</div>

<div class="popup_admin" id='resume_suspend_plan'>
	<div class="alert-pop-up">
		<p class="question">Do you want to <span id="resume_suspend_text"></span> this company?</p>
		<a href="#" class="radius-5 button-1 right" onclick="cancel_gray_out('resume_suspend_plan');">Cancel</a>
		<a href="" class="radius-5 button-1 right" id="resume_suspend_button">Suspend</a>
	</div>
</div>

<div class="popup_admin" id='export_date_prompt'>
	<div class="alert-pop-up">
		<div class="error_message" align="center" style="font-weight:bold;display:none">Please select date.</div>
		<form method="post" action="<?php echo SITEURL?>/admin/export" id="export_user_by_date">
			<p class="question">Select user's created date&nbsp;
			<input type="text" id="export_users_date" size="20" value="" name="export_users_date" style="border: 1px solid #869299;height:20px;" /><br/><span style="font-weight:normal;font-style:italic;padding-left:167px;">(format: mm/dd/yyyy)</span></p>
			<a href="#" class="radius-5 button-1 right" onclick="cancel_gray_out('export_date_prompt');">Cancel</a>
			<a href="#" class="radius-5 alert-save right" id="export_user_button" onclick="export_user_details();">Export</a>
		</form>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.jScrollPaneContainer').css({'z-index':3});
});

</script>