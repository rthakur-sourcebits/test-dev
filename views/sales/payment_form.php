 <!-- Payment Receipt Form -->
 <!-- Description: 
 					Showing receipt for CCP-on/off + all payment methods 
 					Showing "void authorization" button
 					Showing delete button --> 

<link href="/media/css/sales.css" rel="stylesheet">
<?php echo $header;?>

<script type="text/javascript">
	
	function redirect_sales_edit(){
		var url = '<?php echo SITEURL; ?>'+'/sales/view/'+<?php echo $sale_details[0]['id']; ?>;
		$(location).attr('href',url);
	}
	
	function redirect_sales_payment_delete(void_flag){
		if(void_flag==1){
			var url = '<?php echo SITEURL; ?>'+'/sales/sale_payment_void/'+'<?php echo $sale_details[0]['id']; ?>';
		}else {
			var url = '<?php echo SITEURL; ?>'+'/sales/sale_payment_delete/'+'<?php echo $sale_details[0]['id']; ?>'+'/'+'<?php echo $card_type; ?>';
		}
			$(location).attr('href',url);
	}
	
	
	// Description: calling an controller to delete following:
					// delete an entry from transaction or transaction-other 
					// subtract value from sales table. 
					// and redirect to sales edit page with confirmation pop-up.					 
	function delete_payment(){
		
	}
</script>

<div class="row-fluid" id="row-fluid" style="background:#ffffff;">
	<div class="name-customer margin-left-1 margin-right-1" style="height:64px !important;">
		<div class="customer-name-add-payment">
			<label class="customer-name-lbl-payment"><?php echo $sale_details[0]['company_or_lastname']?><span class="amount-payment"><?php if(!isset($payment_receipt)) echo '$ '.$paid_today;?></span></label>
		</div>
	</div>
	<?php if(isset($payment_receipt) && $payment_receipt==1){ ?>
		<div class="clear"></div>
		
		<div class="span6 pymt-input-box mar-top-40">
			<div class="names payment-amt no-border">
				
				<?php if($ccp == '1' && ($card_type==CREDIT_CARD) && isset($trace_id) && !empty($trace_id)){ ?>
				<button name="pymt-delete" class="cancel-btn no-margin-right height-32 left width-155" onclick="redirect_sales_payment_delete(1);">Void Authorization</button>
				<?php } else { ?>
				<button name="pymt-delete" class="cancel-btn no-margin-right height-32 left" onclick="redirect_sales_payment_delete(0);">Delete</button>
				<?php } ?>
				<span class="payment_saved">The Payment has been saved</span>
				<button name="pymt-done" class="right done_payment" onclick="redirect_sales_edit();">Done</button>

			</div>
		</div>
		<div class="clear"></div>
		 
		 <div class="span6 pymt-input-box mar-top-40">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Amount Paid</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo '$'.$paid_today; ?></label>
			</div>
		</div>
		
		<!-- we will take card information and based on that we will show details tags 
			cash/other/debit form is same.
			check the check no. is added.
			for CC form is pretty much different. -->
		
		<?php if($card_type==CASH || $card_type==DEBIT_CARD || $card_type==OTHER){ ?> 
		 <div class="span6 pymt-input-box mar-top-20">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Payment Method</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo $payment_method; ?></label>
			</div>
		</div>
		
		 <div class="span6 pymt-input-box mar-top-20 mar-bot4">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Notes</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo $notes; ?></label>
			</div>
		</div>
		<?php }?>
		 
		<div class="clear"></div>
		
		
		<div class="clear"></div>
		
		<?php if($card_type==CHECK) { ?>
		<div class="span6 pymt-input-box mar-top-20">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Payment Method</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo $payment_method; ?></label>
			</div>
		</div>
		
		 <div class="span6 pymt-input-box mar-top-20 mar-bot4">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Check No.</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo $check_num; ?></label>
			</div>
		</div>
		
		 <div class="span6 pymt-input-box mar-top-20 mar-bot4">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Notes</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo $notes; ?></label>
			</div>
		</div>
		<?php } ?>
		
		<div class="clear"></div>
		<?php if($card_type==CREDIT_CARD) { ?>
		 <div class="span6 pymt-input-box mar-top-20">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Payment Method</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo $payment_method; ?></label>
			</div>
		</div>
		 <div class="span6 pymt-input-box mar-top-20">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Name on Card</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo $name_on_card; ?></label>
			</div>
		</div>
		 <div class="span6 pymt-input-box mar-top-20">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Card Number</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo '**** **** **** '.$last_4_digits; ?></label>
			</div>
		</div>
		 <div class="span6 pymt-input-box mar-top-20">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Expiry Date</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo $exp_date; ?></label>
			</div>
		</div>
		 <div class="span6 pymt-input-box mar-top-20 mar-bot-4">
			<div class="border-shadow height-42">
				<label class="ccp-label1 left text-align-right" id="ccp-label">Transcation ID</label>
				<label class="ccp-label2 left" id="ccp-label"><?php echo $trace_id; ?></label>
			</div>
		</div>
	<?php } 
	} else {?>
		<iframe class="sale-payment-form" src="<?php echo $payment_url?>" />
<?php } ?>
</div>

<style>
.row-fluid{
margin-top:2px !important;
}
</style>
