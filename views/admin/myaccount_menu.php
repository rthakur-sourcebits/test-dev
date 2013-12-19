<div id="my-account">
	<div>
	<a href="javascript:void(0);" style="" id="account-button">
		 My Account<img src="<?php echo SITEURL?>/media/images/tt-new/caret.png" style="">
	</a>
			
	</div>
 </div>



<div class="popup-menu popup-menu1" id="my-account-menu-normal">
	<img src="<?php echo SITEURL?>/media/images/tt-new/account-arrow.png" class="my-account-popup-arrow">
	<ul class="myaccount-list">
		<li class="bor-bot-list account-list"><a href="<?php echo SITEURL?>/admin">Admin</a></li>
		<li class="bor-bot-list account-list"><a href="<?php echo SITEURL?>/admin/settings">Settings</a></li>
		<li class="bor-bot-list account-list"><a href="javascript:void(0);" id="change_admin_pass" onclick="change_admin_password_form();">Change Password</a></li>
		<!-- <li class="bor-bot-list account-list"><a href="javascript:void(0);" id="payment_details" onclick="payment_details_form();">Merchant Details</a></li> -->
		<li class="bor-bot-list account-list"><a href="http://support.accountedge.com/kb/time-tracker" target="_blank">FAQ</a></li>
		<li class="bor-bot-list account-list"><a href="http://support.accountedge.com/discussions/time-tracker" target="_blank">Forum</a></li>
		<li class="bor-bot-list account-list"><a href="http://accountedge.com/help" target="_blank">Email Support</a></li>
		<li class="account-list"><a href="<?php echo SITEURL?>/login/logout">Log out</a></li>
	</ul>
</div>