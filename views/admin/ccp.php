
<script src="/media/scripts/jquery.switch.js" type="text/javascript"></script>
<!-- Le syntax highlighting -->
<link href="/media/css/prettify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/media/scripts/prettify.js"></script>
<script>$(function () { prettyPrint() })</script>
<!-- Le jquery switch-->
<link href="/media/css/jquery.switch.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/media/scripts/jquery.switch.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$('.invalid_msg').hide();
	$('select#select_rate').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
        if(type == "off") {
			$('#original_merchant_details').hide();
			$('#fake_merchant_details').fadeIn(500);
			save_ccp(0);
			$('#nav-ccp img').attr('src','/media/images/tt-new/disable.png');
        } else {
        	$('#fake_merchant_details').hide();
        	$('#original_merchant_details').fadeIn(500);
        	save_ccp(1);
        	$('#nav-ccp img').attr('src','/media/images/tt-new/enable.png');
        }
    });
});

function save_ccp(val){
	$.ajax({
		url		:	'/admin/update_ccp/'+val,
		success	:	function(data) {
			
		}
	});	
}

function saveCCP(site_url){
	if($('#gateway_id').val().indexOf('*') != -1){
		$('.error_msg').html(' Please enter valid Gateway Id.');
		$('.invalid_msg').show();
		return false;
	} else if($('#gateway_pwd').val().indexOf('*') != -1){
		$('.error_msg').html(' Please enter valid Gateway Password.');
		$('.invalid_msg').show();
		return false;
	} else if($('#api_login').val().indexOf('*') != -1){
		$('.error_msg').html(' Please enter valid API Login.');
		$('.invalid_msg').show();
		return false;
	} else if($('#transaction_key').val().indexOf('*') != -1){
		$('.error_msg').html(' Please enter valid Transaction Key.');
		$('.invalid_msg').show();
		return false;
	} 
	if($('.invalid_msg').is(":visible")){
		$('.invalid_msg').hide();
	}
	if($('.success_msg').is(":visible")){
		$('.success_msg').hide();
	}
	$('.loader1').show();
	$.post(	site_url+"/admin/enterpaymentdetails", 
			{	ach_gateway_id : 			$('#gateway_id').val(),
				ach_gateway_password : 		$('#gateway_pwd').val(),
				confirm_gateway_password : 	$('#gateway_pwd').val(),
				apli_login_id : 			$('#api_login').val(),
				transaction_key : 			$('#transaction_key').val(),
				submit : 1 },
			function(data){
				if(data[0]['error'] == 1) {
					$('.loader1').hide();
					$('.invalid_msg .error_msg').text(data[0]['desc']);
					$('.invalid_msg').show();
				} else if(data[0]['success'] == 1) {
					$('.loader1').hide();
					$('.success_msg .error_msg').text(' Gateway Details Updated.');
					$('.success_msg').show();
				}
			},
		   "json");
	return false;
}
</script>

<div class="span8 mar-top-20" id="right-content">
	<!-- write code here -->
	<div class="span1" ></div>
	<div class="span8 no_employee_activated mar-top-40" id="">
		An AccountEdge Merchant Account is fully integrated with your AccountEdge Pro, Cloud and Mobile software.
		<br/>
		For more information on rates and plan options, please visit <a class="click_here" href="http://accountedge.com/creditcards/">accountedge.com/creditcards/</a>
	</div>
	<div class="clear"></div>
	<div class="ccp-contents mar-top-40">
		<div class="span1" ></div>
		<div class="span8 ccp-container1" id="">
			<div class="clear"></div>
			<div class="span6 left no-mar-left">
				<span class="ccp-label right text-align-right" id="ccp-label"><?php echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'ccp');?></span>
			</div>
			<div class="span6 left text-align-left">
				<input type="hidden" name="ccp_enable" id="ccp_enable" value="<?php echo $ccp_enable;?>" />										
				<select style="display:none;" id="select_rate">
	            <option value="1" <?php if($ccp_enable == 1) echo " selected='selected'"; ?>>On</option>
	            <option value="0" <?php if($ccp_enable == 0) echo " selected='selected'"; ?>>Off</option>
	            </select>
			</div>
		</div>
	</div>
	
	<div class="clear"></div>
	<div class="span1" ></div>
	<div class="span8 no-mar-left loader1 mar-top-40" style="display:none;">
		<span class=""><img src="/media/images/tt-new/ajax-loader-2.gif"></span>
	</div>
	<div class="span8 no-mar-left mar-top-40 invalid_msg" style="display:none;">
		<img style="margin-right:6px;" src="/media/images/tt-new/reset_error.png"><span class="error_msg"></span>			 	
	</div>
	<div class="span8 no-mar-left mar-top-40 success_msg" style="display:none;">
		<img style="margin-right:6px;" src="/media/images/tt-new/enable.png"><span class="error_msg"></span>			 	
	</div>
	<div class="clear"></div>
	<div class="span1" ></div>
	<div class="span8 no-mar-left mar-top-40" id="original_merchant_details" style="<?php if($ccp_enable == 0) echo 'display:none;'?>">
		<div class="ccp-container1 height-183">
			<div class="span12 bor-bot no-mar-left merchant_outer bor-rad-top" id="">
				<label class="left span6 merchant_det_label">Merchant ID</label>
				<input id="gateway_id" name="gateway_id" type="password" class="span6 heavy merchant_det_field" value="<?php if($status==1){echo '******';} else {echo ''; } ?>" onclick='$(this).val("");' />
			</div>
			<div class="span12 bor-bot no-mar-left merchant_outer" id="">
				<label class="left span6 merchant_det_label">Processing Password</label>
				<input id="gateway_pwd" name="gateway_pwd" type="password" class="span6 heavy merchant_det_field" value="<?php if($status==1){echo '*********';} else {echo ''; } ?>" onclick='$(this).val("");' />
			</div>
			<div class="span12 bor-bot no-mar-left merchant_outer" id="">
				<label class="left span6 merchant_det_label">API Login Id</label>
				<input id="api_login" name="api_login" type="password" class="span6 heavy merchant_det_field" value="<?php if($status==1){echo '**********';} else {echo ''; } ?>" onclick='$(this).val("");' />
			</div>
			<div class="span12 no-mar-left merchant_outer bor-rad-bottom" id="">
				<label class="left span6 merchant_det_label">Secure Transaction Key</label>
				<input id="transaction_key" name="transaction_key" type="password" class="span6 heavy merchant_det_field" value="<?php if($status==1){echo '**********';} else {echo ''; } ?>" onclick='$(this).val("");' />
			</div>
			<div class="span12 no-mar-left" id="">
				<label class="span6"></label>
				<button class="btn btn-small right" id="save-btn" type="button" onclick="saveCCP('<?php echo SITEURL;?>');"><span id="save_span">Save</span></button>
			</div>
		</div>
	</div>
	<div class="clear"></div>
		<div class="span1" ></div>
	<div class="span8 no-mar-left opacity-light mar-top-40" id="fake_merchant_details" style="<?php if($ccp_enable == 1) echo 'display:none;'?>" >
		<div class="ccp-container1 height-184">
			<div class="span12 bor-bot no-mar-left white_background merchant_outer bor-rad-top" id="">
				<label class="left span6 merchant_det_label">Merchant ID</label>
				<label class="left span6 white_background text-align-left heavy merchant_det_label"><?php if($status==1){echo '******';} else {echo ''; } ?></label>
			</div>
			<div class="span12 bor-bot no-mar-left white_background merchant_outer" id="">
				<label class="left span6 merchant_det_label">Processing Password</label>
				<label class="left span6 text-align-left white_background heavy merchant_det_label"><?php if($status==1){echo '*********'; } else {echo ''; } ?></label>
			</div>
			<div class="span12 bor-bot no-mar-left white_background merchant_outer" id="">
				<label class="left span6 merchant_det_label">API Login Id</label>
				<label class="left span6 text-align-left white_background heavy merchant_det_label"><?php if($status==1){echo '**********'; } else {echo ''; } ?></label>
			</div>
			<div class="span12 no-mar-left white_background merchant_outer bor-rad-bottom" id="">
				<label class="left span6 merchant_det_label">Secure Transaction Key</label>
				<label class="left span6 text-align-left white_background heavy merchant_det_label"><?php if($status==1){echo '**********'; } else {echo ''; } ?></label>
			</div>
		</div>
	</div>
</div>
<div class="clear"></div>

	