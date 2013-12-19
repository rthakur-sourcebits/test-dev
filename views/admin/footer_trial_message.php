<?php if(isset($_SESSION['free_user']) && $_SESSION['free_user'] == 1) {?>
		<div class="expiry-message">
			<label class="msg">You have <?php echo $_SESSION['days_left']?> day(s) left. Please contact your company
			Administrator to upgrade your plan.</label>
		</div>
<?php }?>