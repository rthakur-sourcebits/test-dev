<script type="text/javascript">
function sales_blocked(){
	alert ('You are not eligible...');
}
</script>
<div class="span11" id="sidebar-main"> 
	<a id="setting_sync" href="javascript:void(0);" onclick="load_settings_page(this, '<?php echo SITEURL?>/admin/sync/1');">
		<div id="" class="setting-panel <?php if($page == 6) echo "settings-leftpanel-selected"; else echo "settings-leftpanel";?>">
			<div id="nav-sync" class="settings-icon">
				<label id="l1">Sync Users</label>
				<label class="label2-nav-bar">Last Sync on <?php echo date('h:i A T,', strtotime($last_sync));?></br><?php echo date('l, F d, Y', strtotime($last_sync));?>.</label>
			</div>
		</div>
	</a>
	<a id="setting_ccp" href="javascript:void(0);" onclick="<?php if(isset($sales_group_enable) &&  $sales_group_enable){ ?> 
																load_settings_page(this, '<?php echo SITEURL?>/admin/ccp/1');
															<?php } else { ?>
																sales_blocked();
															<?php } ?>">
		<div id="" class="setting-panel <?php if($page == 7) echo "settings-leftpanel-selected"; else echo "settings-leftpanel";?>">
			<div id="nav-ccp" class="settings-icon">
				<label id="l1">Credit Card Processing</label>
				<label class="label2-nav-bar" >Enable payment processing</label>
			</div>
		</div>
	</a>
	<!-- Deposited Account -->
	<!-- 
	<a id="setting_deposit_account" href="javascript:void(0);" onclick="load_settings_page(this, '<?php echo SITEURL?>/admin/deposit_account/1');">
		<div id="" class="setting-panel <?php if($page == 8) echo "settings-leftpanel-selected"; else echo "settings-leftpanel";?>">
			<div id="nav-account" class="settings-icon">
				<label id="l1">Deposit Account</label>
				<label class="label2-nav-bar" ><span class="left">Current Selected Account: </span><span class="width-60 mar-left-10 left">1-11160</span></label>
			</div>
		</div>
	</a>
	 -->
	<a id="setting_editusers" href="javascript:void(0);" onclick="load_settings_page(this, '<?php echo SITEURL?>/admin/editusers/1');">
		<div id="" class="setting-panel <?php if($page == 1) echo "settings-leftpanel-selected"; else echo "settings-leftpanel";?> ">
			<div id="nav-edit" class="settings-icon">
				<label id="l1">Edit Users</label>
				<label class="label2-nav-bar" ><?php echo $user_details['active_users']?> Active, <?php echo $user_details['inactive_users']?> Inactive</label>
				<label class="label3-nav-bar"><?php echo $user_details['total_users']?> Available</label>
			</div>
		</div>
	</a>
	<a id="setting_updatecard" href="javascript:void(0);" onclick="load_settings_page(this, '<?php echo SITEURL?>/admin/updatecard');">
		<div id="" class="setting-panel <?php if($page == 2) echo "settings-leftpanel-selected"; else echo "settings-leftpanel";?> ">
			<div id="nav-payment" class="settings-icon">
				<label id="l1">Payment Method</label>
				<label class="label2-nav-bar" >Save your card details and preferences</label>
			</div>
		</div>
	</a>
	<a id="setting_changeplan" href="javascript:void(0);" onclick="load_settings_page(this, '<?php echo SITEURL?>/admin/changeplan/false/1');">
		<div id="" class="setting-panel <?php if($page == 3) echo "settings-leftpanel-selected"; else echo "settings-leftpanel";?> ">
			<div id="nav-plan" class="settings-icon">
				<label id="l1">Change Plan</label>
				<label class="label2-nav-bar" >Current Plan: <?php echo $plan_name['name'];?></label>
				<label class="label3-nav-bar">Change or Upgrade your plan</label>
			</div>
		</div>
	</a>
	<a id="setting_account" href="javascript:void(0);" onclick="load_settings_page(this, '<?php echo SITEURL?>/admin/setup//0');">
		<div id="" class="setting-panel <?php if($page == 4) echo "settings-leftpanel-selected"; else echo "settings-leftpanel";?> ">
			<div id="nav-account" class="settings-icon">
				<label id="l1">Account</label>
				<label class="label2-nav-bar" >Sync your account with Dropbox</label>
			</div>
		</div>
	</a>
	<a id="setting_reset" href="javascript:void(0);" onclick="load_settings_page(this, '<?php echo SITEURL?>/admin/reset');">
		<div id="" class="setting-panel <?php if($page == 5) echo "settings-leftpanel-selected"; else echo "settings-leftpanel";?> ">
			<div id="nav-reset" class="settings-icon">
				<label id="l1">Reset</label>
				<label class="label2-nav-bar">Reset all AccountEdge Cloud data</label>
			</div>
		</div> 
	</a>
</div> 
