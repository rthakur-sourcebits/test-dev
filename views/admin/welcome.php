<div class="wrapper admin" id="wrapper" style="overflow: visible;">
<script type="text/javascript">
$(document).ready(function(){
	$("#account-section-container").css("background-color", "#FFFFFF");
	if($(window).width() > 1200) {
		$(".welcome_image img").css("margin-left", "-39px");
	} else {
		$(".welcome_image img").css("margin-left", "");
	}
	if($(window).width() > 800 && $(window).width() < 1200) {
		$("#Image-Maps_8201107200747049").css("width", "60%");
	}
	$(window).resize(function(){
		if($(window).width() > 1200) {
			$(".welcome_image img").css("margin-left", "-39px");
		} else {
			$(".welcome_image img").css("margin-left", "");
		}
		if($(window).width() > 800 && $(window).width() < 1200) {
			$("#Image-Maps_8201107200747049").css("width", "60%");
		}
		else {
			$("#Image-Maps_8201107200747049").css("width", "");
		}
	});
});
</script>
	<?php
	if(!empty($superadmin))
	{
		?>
			<?php echo $company; ?>
			<div class="container">
				<div class="content">
					<div class="block"><?php echo $company_form; ?></div>
				</div>
			</div>
			<?php
			if(isset($company_info))
			{
				if(isset($offline)) {
					$delete_url		=	'deleteoffline';
				} else {
					$delete_url		=	'delete_company';
				}
			?>
			<div class="popup_admin" id='delete_company'> 	
				<div class="alert-pop-up">
				<p class="question">Do you want to delete <?php echo stripslashes($company_info['name']);?>?</p>
				<p class="message">Deleting this company will remove all the records from the system. This operation cannot be undone.</p>
				<a href="#" class="radius-5 button-1 right" onclick="cancel_gray_out('delete_company');">Cancel</a>
				<a href="<?php echo SITEURL?>/admin/<?php echo $delete_url; ?>/<?php echo $company_info['id'] ?>" class="radius-5 button-1 right">Delete</a>
			<?php
			}
			?>
			</div>
			</div>
		<?php
	}
	else
	{
	?>
		<?php echo $header_user_list; ?>
		<div class="welcome_image">
			<img id="Image-Maps_8201107200747049" src="<?php echo SITEURL?>/media/images/tt_admin_welcome.png" usemap="#Image-Maps_8201107200747049" />	
			<map id="_Image-Maps_8201107200747049" name="Image-Maps_8201107200747049">
				<area shape="rect" coords="695,325,727,341" href="<?php echo SITEURL?>/admin/loginasuser" alt="" title="Login"  />
			</map>
	 	</div>		
	 </div>
	<?php
	}
	?>
	
</div>