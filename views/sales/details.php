<!-- 
 * @File : details.php
 * @Author: 
 * @Created: 01-10-2012
 * @Modified:  
 * @Description: view for details submitted by user at the time of payment
 * Copyright (c) 2012 Acclivity Group LLC 
-->
<link href="/media/css/sales.css" rel="stylesheet">

<?php echo $header;?>
		
<script type="text/javascript">
$(document).ready(function()
{
	if($(window).width() < 600) 
	{
			
		$('.info-column-1').css("width","100%");//42
		$('.info-column-2').css("width","100%");//42		
		$('.info').css("padding-bottom","12px");//2			
		$('.details-button').css("margin-right","0");//8
		$('.edit-delete-buttons').css("padding-left","0");//38
		$('.edit-delete-buttons').css("padding-right","0");//8
		$('.sales-info-toggle-details').show();
	}
	else if (($(window).width() > 600) && ($(window).width() <= 750))
	{
		$('.info-column-1').css("width","42%");//42
		$('.info-column-2').css("width","42%");//42		
		$('.info').css("padding-bottom","12px");//2	
		$('.details-button').css("margin-right","0");//8
		$('.edit-delete-buttons').css("padding-left","0");//38
		$('.edit-delete-buttons').css("padding-right","0");//8
		$('.sales-info-toggle-details').show();
	}
	else
	{
		$('.info-column-1').css("width","40%");//42
		$('.info-column-2').css("width","40%");//42		
		$('.info').css("padding-bottom","2px");//2	
		$('.details-button').css("margin-right","8%");//8
		$('.edit-delete-buttons').css("padding-left","38%");//38
		$('.edit-delete-buttons').css("padding-right","8%");//8
		$('.sales-info-toggle-details').hide();
	}
	$(window).resize(function()
	{
		if($(window).width() < 600) 
		{
			$('.info-column-1').css("width","100%");//42
			$('.info-column-2').css("width","100%");//42		
			$('.info').css("padding-bottom","12px");//2			
			$('.details-button').css("margin-right","0");//8
			$('.edit-delete-buttons').css("padding-left","0");//38
			$('.edit-delete-buttons').css("padding-right","0");//8
			$('.sales-info-toggle-details').show();
		}
		else if (($(window).width() > 600) && ($(window).width() <= 750))
		{
			$('.info-column-1').css("width","42%");//42
			$('.info-column-2').css("width","42%");//42		
			$('.info').css("padding-bottom","12px");//2	
			$('.details-button').css("margin-right","0");//8
			$('.edit-delete-buttons').css("padding-left","0");//38
			$('.edit-delete-buttons').css("padding-right","0");//8
			$('.sales-info-toggle-details').show();
		}
		else
		{
			$('.info-column-1').css("width","40%");//42
			$('.info-column-2').css("width","40%");//42		
			$('.info').css("padding-bottom","2px");//2	
			$('.details-button').css("margin-right","8%");//8
			$('.edit-delete-buttons').css("padding-left","38%");//38
			$('.edit-delete-buttons').css("padding-right","8%");//8
			$('.sales-info-toggle-details').hide();
		}
	});
});
</script>
		
<div class="row-fluid" id="row-fluid" style="margin-top:10px;">
	<div class="top20perc"></div>
	<?php
	if(empty($payment_details)){
   	 ?>
    <div class="empty"><label class="empty-lbl">No Details Present</label></div>
    <?php 
	} else { ?>
    
	<div class="sales-item-list margin-left-6 margin-right-6 top10">
		<!--Item Heading starts-->
		<div class="name-salesItem">
			<div class="synced-serviceviewItem7">
				<div class="synced-serviceviewItem6">
					<div class="synced-serviceviewItem5">
						<div class="synced-serviceviewItem3">
						<?php if($payment_mode == "1") {?>
							<div class="synced-serviceviewItem-2-field"><label class="salesItem-name-field font-color">Date</a></label></div>
							<div class="synced-serviceviewItem-3-field"><label class="salesItem-name-field font-color">Transaction ID</label></div>
							<div class="synced-serviceviewItem-4-field right-align"><label class="salesItem-name-field font-color"></label></div>
							<div class="synced-serviceviewItem-6-field"><label class="salesItem-name-field font-color">Customer</label></div>
							<div class="synced-serviceviewItem-7-field"><label class="salesItem-name-field font-color"></label></div>
						<?php } else if($payment_mode == "2") {?>
							<div class="synced-serviceviewItem-2-field"><label class="salesItem-name-field font-color">Date</a></label></div>
							<div class="synced-serviceviewItem-3-field"><label class="salesItem-name-field font-color">Notes</label></div>
							<div class="synced-serviceviewItem-4-field"><label class="salesItem-name-field font-color"></label></div>
							<div class="synced-serviceviewItem-6-field"><label class="salesItem-name-field font-color">Customer</label></div>
							<div class="synced-serviceviewItem-7-field"><label class="salesItem-name-field font-color"></label></div>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--Item Heading ends -->
		<?php 
		$i	=	1;
		foreach($payment_details as $p) {?>
			<!-- Item Values Starts Loop Section -->
			<div class="name-salesItem1" style="height:42px;">
				<div class="synced-serviceviewItem7">
					<div class="synced-serviceviewItem6">
						<div class="synced-serviceviewItem5">
							<div class="synced-serviceviewItem3">
							<?php if($payment_mode == "1") {?>
								<div class="synced-serviceviewItem-2-field"><label class="salesItem-name-field overflow_height tool_tip font-color-list"><?php echo date("M d, Y",strtotime($p['transaction_date']))?></label></div>
								<div class="synced-serviceviewItem-3-field"><label class="salesItem-name-field overflow_height tool_tip font-color-list"><?php echo $p['gateway_transaction_id']?></label></div>
								<div class="synced-serviceviewItem-4-field"><label class="salesItem-name-field font-color-list right-align">$ <?php echo number_format($p['transaction_amount'], 2, '.', '');?></label></div>
								<div class="synced-serviceviewItem-6-field"><label class="salesItem-name-field overflow_height tool_tip font-color-list"><?php echo $p['company_or_lastname']?></label></div>
								<div class="synced-serviceviewItem-7-field"><label class="salesItem-name-field overflow_height tool_tip font-color-list"><?php if(!empty($p['payment_method'])) echo $p['payment_method'];else echo '';?></label></div>
							<?php } else if($payment_mode == "2") {?>
								<div class="synced-serviceviewItem-2-field"><label class="salesItem-name-field overflow_height tool_tip font-color-list"><?php echo date("M d, Y",strtotime($p['transaction_date']))?></label></div>
								<div class="synced-serviceviewItem-3-field"><label class="salesItem-name-field overflow_height tool_tip font-color-list"><?php echo $p['notes'];?></label></div>
								<div class="synced-serviceviewItem-4-field"><label class="salesItem-name-field font-color-list right-align">$ <?php echo number_format($p['transaction_amount'], 2, '.', '');?></label></div>
								<div class="synced-serviceviewItem-6-field"><label class="salesItem-name-field overflow_height tool_tip font-color-list"><?php echo $p['company_or_lastname']?></label></div>
								<div class="synced-serviceviewItem-7-field"><label class="salesItem-name-field overflow_height tool_tip font-color-list"><?php if(!empty($p['payment_method'])) echo $p['payment_method'];else echo '';?></label></div>
							<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php 
			$i++;
		}?>
	</div>
	
	<!-- Mobile Version Starts -->
	<?php foreach($payment_details as $p) { ?>
		<div class="sales-info-toggle-details" style="display:none;">
			<div class="row sales-cust-toggle-details-row" id="sales-cust-toggle-details-row">
			<?php if($payment_mode == "1") {?>
					<div class="data-input-sales-fields-overtime-phone-date add-fields" >
					<label class="sales-info-label-mobile dull">Date &nbsp;<span class="heavy mar-left-4"><?php echo date("M d, Y",strtotime($p['transaction_date']))?></span></label>
					</div> 
					
					<div class="data-input-sales-fields-overtime-phone-sync add-fields" >
						<label class="sales-info-label-mobile heavy"><?php if(!empty($p['payment_method'])) echo $p['payment_method'];else echo '';?></label>
					</div>
					<div class="data-input-sales-fields-customer1 add-fields" >
					<label class="sales-info-label-mobile overflow_height tool_tip dull">Transaction ID &nbsp;
					<span class="heavy mar-left-4"><?php echo $p['gateway_transaction_id']?></span>
					</label>
					</div>  
					<div class="data-input-sales-fields-overtime-phone-date" >
					<label class="sales-info-label-mobile overflow_height tool_tip dull">Customer &nbsp;<span class="heavy"><?php echo $p['company_or_lastname']?></span></label>
					</div> 
				
					<div class="data-input-sales-fields-overtime-phone-sync" >
						<label class="sales-info-label-mobile heavy">$ <?php echo number_format($p['transaction_amount'], 2, '.', '');?></label>
					</div>
			<?php } ?>
			</div>
		</div>
	<?php }
	}
	?>
	<div class="clear"></div>
	<!-- Mobile Version Ends -->
	<div class="bottommargin"></div>
</div>
<style>
.name-salesItem .synced-serviceviewItem-7-field label:after{
	content:"Payment Method" !important;
}
.name-salesItem .synced-serviceviewItem-4-field label:after{
	content:"Total amount" !important;
}
@media all and (max-width: 750px) 
{
	.sales-item-list{
		display:none !important;
	}
}
@media all and (min-width: 750px) and (max-width: 1160px)  
{
	.name-salesItem .synced-serviceviewItem-7-field label:after{
		content:"Payment" !important;
	}
	.name-salesItem .synced-serviceviewItem-4-field label:after{
		content:"Total" !important;
	}
}
</style>