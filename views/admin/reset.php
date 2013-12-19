<script type="text/javascript">
$(document).ready(function()
{
	if($(window).width() < 480) 
	{
		$('.warning-label').css("font-size","24px");
		$('#customer-data-span').css("width","44%");
		$('#data-input-fields').css("width","53%");
		$('#data-name-input').css("width","80%");
		$('.reset-margin').css("font-size","14px");
		$('.reset-margin').css("width","90%");
		$('.reset-margin').css("margin-left","5%");
		// $('#sidebar-main').css("min-height","0");
		$('.reset-button').css("height","37px");
		$('.reset-label').css("margin-top","7px");
	} else {
		$('.warning-label').css("font-size","50px");
		$('#customer-data-span').css("width","26%");
		$('#data-input-fields').css("width","71%");
		$('#data-name-input').css("width","90%");
		$('.reset-margin').css("font-size","16px");
		$('.reset-margin').css("width","80%");
		$('.reset-margin').css("margin-left","10%");
		$('.reset-button').css("height","46px");
		$('.reset-label').css("margin-top","12px");
	}
	$(window).resize(function()
	{
		if($(window).width() <480) {
			$('.warning-label').css("font-size","24px");
			$('#customer-data-span').css("width","44%");
			$('#data-input-fields').css("width","53%");
			$('#data-name-input').css("width","80%");
			// $('#sidebar-main').css("min-height","0");
			$('.reset-margin').css("font-size","14px");			
			$('.reset-margin').css("width","90%");
			$('.reset-margin').css("margin-left","5%");
			$('.reset-button').css("height","37px");
			$('.reset-label').css("margin-top","7px");
		} else {
			$('.warning-label').css("font-size","50px");
			$('#customer-data-span').css("width","26%");
			$('#data-input-fields').css("width","71%");
			$('#data-name-input').css("width","90%");
			$('.reset-margin').css("font-size","16px");		
			$('.reset-margin').css("width","80%");
			$('.reset-margin').css("margin-left","10%");
			$('.reset-button').css("height","46px");
			$('.reset-label').css("margin-top","12px");
		}
	});
}); 
</script>

<div class="span8 reset-page" id="user-right-content" >
	
	<?php if(isset($success)) {?>
			<div class="account-reset-alert"><?php echo $success;?></div>
	<?php }?>
			<!--	<img src="images2/reset-image.png" class="reset-image">  -->
				<div class="reset-image">				
				<img src="<?php echo SITEURL?>/media/images/tt-new/reset-image.png" class="reset-image hd">
				</div>
				<div class="warning">
				<label class="warning-label">Warning</label>
				</div>
				<div class="reset-margin">
				Reset will irreversibly delete all AccountEdge Cloud data and settings, including your AccountEdge device files on Dropbox
			<!--  	<label class="reset-data-label">Reset will irreversibly delete all AccountEdge  Cloud </label>
				<label class="reset-data-label">data and settings, including your AccountEdge device files on Dropbox </label> -->
				</div>
				
				<form method="post" action="<?php echo SITEURL?>/admin/reset" name="reset_account" id="reset_account">
				<div class="cust-toggle-details" >				
					<div class="row cust-toggle-details-row <?php if(isset($error)) { echo "reset-error-border"; }?>" id="cust-toggle-details-row">
						<div class="span3" id="customer-data-span" >Password
						</div> 
						<div id="data-input-fields" <?php if(isset($error)) { echo "class='reset-error-input'"; }?>> <input type="password" name="password" id="data-name-input">
						</div>
					</div>
					<?php if(isset($error)) {?>
						<div class="row reset-pass-error">
							<div class="error-desc">
							<img src="/media/images/tt-new/reset_error.png" />Incorrect password. Please try again.</div>
							<div id="login-lost-pass">
								<p>
									<a href="javascript:void(0);" id="forgot_pass" onclick="open_forgot_password_window();"  title="shijith">Lost Password</a>
								</p>
							</div>
						</div>
					<?php }?>
				</div>
				
				<div class="reset-button" >
					<a href="javascript:void(0);" onclick="reset_account.submit();"><label class="reset-label">Reset</label>
					</a>
				</div>
				</form>
			</div>
			<?php echo View::factory("admin/footer_trial_message");?>
<script>
$(document).ready(function()
		{
		if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) )
		{
			var images = $('img.hd');   
		for(var i=0; i < images.length; i++)
		{
		images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/reset-image.png', '/media/images/tt-new/cloud_admin2.png'))
		}
		}
		});
</script>