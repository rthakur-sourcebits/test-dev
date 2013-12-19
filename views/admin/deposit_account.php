<div class="span8 mar-top-20" id="right-content">
	<!-- write code here -->
	<div class="span1" ></div>
	<div class="clear"></div>
	<div class="ccp-contents mar-top-40">
		<div class="span1" ></div>
		<div class="span8 ccp-container1" id="">
			<div class="clear"></div>
			<div class="span6 left account_number_label no-mar-left">
				Deposit Account
			</div>
			<div class="span6 left account_number text-align-left">
				<span class=''>1-1160</span>
				<a class="deposit_account_openpopup right"></a>
			</div>
			<div class="clear"></div>
			<div class="deposit_account_popup" id="deposit_account_popup" style="display:none;" >
				<div class="inner_deposit" id="deposit_account_1" onclick="select_deposit_account(this.id);">
					<span class="left pad-left-10">MasterCard</span>
					<span class="right pad-right-40">1-11160</span>
				</div>
				<div class="inner_deposit" id="deposit_account_2" onclick="select_deposit_account(this.id);">
					<span class="left pad-left-10">Visa</span>
					<span class="right pad-right-40">1-11190</span>
				</div>
				<div class="inner_deposit" id="deposit_account_3" onclick="select_deposit_account(this.id);">
					<span class="left pad-left-10">Undeposited Funds</span>
					<span class="right pad-right-40">1-21150</span>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).bind('click', function(e) {
	var $clicked = $(e.target);
	if ($clicked.hasClass('deposit_account_openpopup') || $clicked.hasClass('deposit_account_popup')) { 
		$('#deposit_account_popup').fadeIn(500);
	} else {
		$('#deposit_account_popup').fadeOut(200);
	}
});
function select_deposit_account(id){
	var account_info	=	$('#'+id).children().eq(1).text();
	$('.account_number span').text(account_info);
	$('#setting_deposit_account').find('.width-60.mar-left-10').text(account_info);
}
$(document).ready(function(){
	$('.deposit_account_popup').children().eq(0).css('border-radius','5px 5px 0 0');
	$('.deposit_account_popup').children().eq(count).css('border-radius','0px 0px 5px 5px');
});
</script>

