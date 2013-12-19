<?php if(isset($_SESSION['free_user']) && $_SESSION['free_user'] == 1) {?>
		<div class="notice-slips" style="margin-top:7%;text-align:center;color:#A9A9A9;">  
			<label id="ex1">You have <?php echo $_SESSION['days_left']?> day(s) left. Please contact your company
			Administrator to upgrade your plan.</label>
		</div>
<?php }?>
