
<div class="span8" id="right-content">
	<div class="image-content">
		<img class="shade" src="<?php echo SITEURL?>/media/images/tt-new/blue_shade.png" >
		<img class="shade-cloud" src="<?php echo SITEURL?>/media/images/tt-new/cloud.png" style="margin-top:-40%;">
	<div class="cld-icon"></div>  
	</div>
	<div class="clear"></div>
	<div class="admin-contents">
		<a class="sync-link" href="<?php echo SITEURL?>/admin/syncusers" >
 			<div class="sync-button sync-link">
 				<label  class="label-sync1">Sync</label>
 			</div>
		</a>
		<p class="sync-date">
			<label>Last Sync on: <?php echo date('F d, Y @ h:ia', strtotime($last_sync));?>&nbsp;(in Eastern time) </label>
		</p>
	</div>
</div>
<div class="clear"></div>
<?php echo View::factory("admin/footer_trial_message");?>
	