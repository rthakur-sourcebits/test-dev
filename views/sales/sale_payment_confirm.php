<!-- Service Invoice -->
<link href="/media/css/sales.css" rel="stylesheet">
<?php echo $header;?>

<?php // set card-type
	if(!empty($payment_method_details)){
		switch($payment_method_details[0]['payment_type']){
			case '0': // payment form for CASH
					$card_type=CASH;
					break;
			case '1': // payment form for CHECK
					$card_type=CHECK;
					break;
			case '2': // payment form for CREDIT CARD
					$card_type=CREDIT_CARD;
					break;
			case '3': // payment form for DEBIT CARD
					$card_type=DEBIT_CARD;
					break;
			case '4': // Payment form for OTHER
					$card_type=OTHER;
					break;
			default: 
					$card_type=CREDIT_CARD;
		}
	} else {
		$card_type = '';
	} 
?>

<script type="text/javascript">
$(document)	
</script>

<div class="payment-popup" style="display:none;">
	<div class="popup-items1 number_list_payment popup-scroll">
		<?php foreach($paymentmethod as $pm) {?>
		<div class="names-popup-list first payment-list-row option_payment" >
			<label class="names-popup-list-label heavy"><?php echo $pm['information_name'];?></label>
			<input type="hidden" disabled="disabled"  name="payment_number[]" value="<?php echo $pm['information_name'];?>" class="payment_number"/>
			<input type="hidden" disabled="disabled"  name="payment_type[]" value="<?php echo $pm['payment_type'];?>" class="payment_type"/>
		</div>
		<?php } ?>
	</div>
</div>

<div class="row-fluid" id="row-fluid" >
	<div class="name-customer margin-left-1 margin-right-1">
		<div class="customer-name-add-payment">
			<label class="customer-name-lbl-payment"><?php if (isset($customer[0]['company_or_lastname'])) echo $customer[0]['company_or_lastname']; else echo 'Invalid Customer';?><span class="amount-payment">$<?php echo $paid_today;?></span></label>
		</div>
		<div class="autn-btn">
			<?php
			if($card_type != CREDIT_CARD){ ?>
		    	<input type="button" class="authorize-button" name="authorize" value="Save" onclick="submit_payment_form(0);" />
		    <?php 
			} else {
				if($ccp == 1 && $gateway_info['status']==1) 
				{
	    			if(!empty($payment_info)&&$payment_method_details[0]['payment_method']==$payment_info[0]['payment_method']){ ?>
	    				<input type="button" class="authorize-button" name="authorize" value="Authorize" onclick="submit_payment_form(1);" />
			<?php	} 
				} else {?>
				 
					<input type="button" class="authorize-button" name="authorize" value="Save" onclick="submit_payment_form(0);" />
			<?php } 
			} ?>
		</div>
	</div>
	<div class="clear"></div>
	<div class="cash-cc-btns" style="display: none">
		<div class="left individual-cust cash-cc-btns3" id="btn-1" ><label class="dark pay-cc-cash-label"><span class="dark pay-cc-cash-label">Payment Details</span></label></div>
		<input type="hidden" id="pay_method_cc_cash" name="pay_method" value="1" />
	</div>
	<div class="clear"></div>
	<div id="card">
		<form method="post" action="/sales/addpayment" id="add_payment">
			<div class="card-info">
				<div class="payment-methods info-sales_payment border-shadow">
					<div class="info pad-top-12 payment_field no-bor-bottom">
						<label class="line1 payment-method-lbl">Payment Method</label>
						<div>
							<input type="text" readonly class="tabindex_field payment_input1 ac_input address-input-field white font-color1 left" value="<?php if (isset($payment_method_details)) echo $payment_method_details[0]['payment_method']; else echo "";?>" name="payment_method" />
							<a class="service-down-arrow1 right" href="javascript:void(0)" id="openPaymentPopup1" >&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				<div class="credit-card-block" card_type=<?php echo CREDIT_CARD; ?> style="display: <?php if ($card_type == CREDIT_CARD) { echo 'block'; } else {echo 'none';} ?>" >
					<div class="info-sales_payment border-shadow">
						<label class="line1 payment-method-lbl pad-top-12">Name on card</label>
						<div class="payment-create-sales-payment white">
							<?php 
						    if(empty($payment_info)) { 
							?>
							<input type="text" id='name_on_card' class="tabindex_field payment-input-field-tax white tt-field left font-color1" name="name_on_card" value="" <?php if($ccp==1) echo 'readonly'; ?> />
							<?php 
					    	} else {
							?>
							<input 	type="text" 
									class="tabindex_field payment-input-field-tax white tt-field left font-color1"
									id='name_on_card' 
									name="name_on_card" value="<?php if(!empty($payment_info)  && 
																			$payment_method_details[0]['payment_method']==$payment_info[0]['payment_method'])
																			{ echo $payment_info[0]['name_on_card']; }?>" 
																	<?php if($ccp==1) echo 'readonly'; ?> />
							<?php 
					    	}
					    	?>
						</div>
					</div>
					<div class="clear"></div>
					<div class="info-sales_payment border-shadow" >
						<label class="line1 payment-method-lbl pad-top-12">Last 4 digits</label>
						<div class="payment-create-sales-payment white">
							<?php 
					    	if(empty($payment_info)) {
							?>
							<input type="text" id='last_digits' maxlength="4" class="tabindex_field payment-input-field-tax number white tt-field left font-color1" name="last_digits" value="" <?php if($ccp==1) echo 'readonly'; ?>/>
							<?php 
					    	} else {
							?>
							<input type="text" id='last_digits' maxlength="4" class="tabindex_field payment-input-field-tax white number tt-field left font-color1" name="last_digits" value="<?php if(!empty($payment_info) && ($payment_method_details[0]['payment_method']==$payment_info[0]['payment_method'])) echo $payment_info[0]['last_digits_on_card']?>" 
								<?php if($ccp==1) echo 'readonly'; ?> />
							<?php 
					    	}
					    	?>
						</div>
					</div>
					<div class="clear"></div>
					<!-- <div class="info-sales_payment border-shadow" >
						<label class="line1 payment-method-lbl pad-top-12 exp-mon-lbl">Expiration Date</label>
						<div class="payment-create-sales-payment white">
							<input 	type="text" class="tabindex_field payment-input-field-tax white tt-field left font-color1" 
									name="expiry_date"
									id="expiry_date" 
									value="<?php if(!empty($payment_info[0]['expiration_date'])  && 
														($payment_method_details[0]['payment_method']==$payment_info[0]['payment_method'])){
															 echo $payment_info[0]['expiration_date'];
											 }?>" 
											<?php if($ccp==1) echo 'readonly'; ?> />
						</div>
					</div> -->
					
					<?php if(!empty($payment_info[0]['expiration_date'])  && ($payment_method_details[0]['payment_method']==$payment_info[0]['payment_method'])){
						$exp_month	=	substr($payment_info[0]['expiration_date'],0,2);
						$exp_year	=	substr($payment_info[0]['expiration_date'],3,2);
					}?>
					
					<div class="info-sales_payment border-shadow height-41">
						<div class="height-41 bor-radius-2 left width-50 bor-right white">
							<label class="line1 payment-method-lbl pad-top-12 exp-mon-lbl">Expiry Month</label>
							<input type="text" name="" value="<?php if(isset($exp_month) && !empty($exp_month)) echo $exp_month;?>" readonly id="month" class="left white font-color1 ac_input" tabindex="5" autocomplete="off">
							<?php if($ccp!=1)  {?> 
							<a class="expiry_popup_arrow_month right pointer">&nbsp;&nbsp;&nbsp;</a>
							<?php }?>
							<div class="clear"></div>
							<?php if($ccp!=1)  {?> 
							<div class="popup-contents-month-year mar-top-5 month" style="display:none">
	            				<div class="month-year-popup1">
	            				<?php for($i=1;$i<=12;$i++) {	?>
	                    			<div class="names-popup-list year-row month-select" onclick=''>
	                    				<label class="names-popup-list-label heavy"><?php if($i<10) echo '0'.$i; else echo $i;?></label>
	                    			</div>
	                    		<?php }?>
	                    		</div>
							</div>
							<?php }?>
						</div>
						<div class="left white width-48">
							<label class="line1 payment-method-lbl1 pad-top-12 exp-mon-lbl">Year</label>
							<input type="text" name="" value="<?php if(isset($exp_year) && !empty($exp_year)) echo $exp_year;?>" readonly id="year" class="left white font-color1 ac_input" tabindex="6" autocomplete="off">
							<?php if($ccp!=1)  {?> 
							<a class="expiry_popup_arrow_year right pointer">&nbsp;&nbsp;&nbsp;</a>
							<?php }?>
							<?php 
								$cur_year	=	date("Y");
								$start_year	=	2010;
							?>
						</div>
						<div class="clear"></div>
						<?php if($ccp!=1)  {?> 
							<div class="popup-contents-month-year year" style="display:none">
	            				<div class="month-year-popup1">
	            				<?php for($i=$start_year;$i<=$cur_year+25;$i++) {	?>
	                    			<div class="names-popup-list year-row year-select" onclick=''>
	                    				<label class="names-popup-list-label heavy"><?php echo $i;?></label>
	                    			</div>
	                    		<?php }?>
	                    		</div>
							</div>
						<?php }?>
						<input 	type="hidden" class="" name="expiry_date" id="expiry_date" value="<?php if(!empty($payment_info[0]['expiration_date'])  && ($payment_method_details[0]['payment_method']==$payment_info[0]['payment_method'])){ echo $payment_info[0]['expiration_date']; }?>" />
					</div>
					<div class="clear"></div>
					<?php if ($ccp==1 && $gateway_info['status']==1){ ?>
						<div class="add-new-card-button" >
							<div class="">
								<input type="button" name="add_new" value="Add new card" onclick="submit_payment_form(0)" class="add-new-card-btn tabindex_field" />
							</div>
						</div>	
					<?php } ?>
			
					<div class="clear"></div>
					<div class="info-column-2 pad-top-12 float-none" >
						<div class="info-sales_payment box3 white" style="display: none">
							<label class="line1 pad-top-12 payment-token-lbl">AVS Zipcode</label>
							<div class="payment-create-sales-payment white">
							<input type="text" class="tabindex_field payment-input-field-tax left white tt-field font-color1" readonly />
							</div>
						</div>
						<?php if ($ccp==1 && $gateway_info['status']==1){ ?>
						<div class="update-customer-record box-border white top-mar-4">
							<label class="update-customer-label pad-top-12">Update customer record with new credit card details</label>
							<div class="right">
							<a class="job-line-enable-disable inactive-save-enable" href="javascript: void(0)"><img src="/media/images/tt-new/enable.png" style="padding-top:13px !important" class="create-job-enable-disable1 job-disable1 enable1"></a>
							<input class="tabindex_field" type="hidden" id="save_card" name="save_card" value="1"/>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="clear"></div>
				<div class="debit-card-block" card_type=<?php echo DEBIT_CARD; ?> style="display: <?php if ($card_type == DEBIT_CARD) { echo 'block'; } else {echo 'none';} ?>"> <!-- showing notes + save button-->
					<div class="info-sales_payment border-shadow">
						<label class="line1 payment-method-lbl pad-top-12">Notes</label>
						<div class="payment-create-sales-payment white">
							<input type="text" class="tabindex_field payment-input-field-tax white tt-field left font-color1" name="debit-notes" value="" />
						</div>
					</div>	
				</div>
				
				<div class="cash-block" card_type=<?php echo CASH; ?> style="display: <?php if ($card_type == CASH) { echo 'block'; } else {echo 'none';} ?>">  <!-- showing notes + save button -->
					<div class="info-sales_payment border-shadow">
						<label class="line1 payment-method-lbl pad-top-12">Notes</label>
						<div class="payment-create-sales-payment white">
							<input type="text" class="tabindex_field payment-input-field-tax white tt-field left font-color1" name="cash-notes" value="" />
						</div>
					</div>
				</div>
				
				<div class="check-block" card_type=<?php echo CHECK; ?> style="display: <?php if ($card_type == CHECK) { echo 'block'; } else {echo 'none';} ?>">  <!-- showing check no. + notes + save button -->
					<div class="info-sales_payment border-shadow">
						<label class="line1 payment-method-lbl pad-top-12">Check No.</label>
						<div class="payment-create-sales-payment white">
							<input type="text" class="tabindex_field payment-input-field-tax white tt-field left font-color1" name="check_number" value="" />
						</div>
					</div>
					<div class="clear"></div>
					<div class="info-sales_payment border-shadow">
						<label class="line1 payment-method-lbl pad-top-12">Notes</label>
						<div class="payment-create-sales-payment white">
							<input type="text" class="tabindex_field payment-input-field-tax white tt-field left font-color1" name="check-notes" value="" />
						</div>
					</div>
				</div>
				
				<div class="other-block" card_type=<?php echo OTHER; ?> style="display: <?php if ($card_type == OTHER) { echo 'block'; } else {echo 'none';} ?>">  <!-- showing notes + save button -->
					<div class="info-sales_payment border-shadow">
						<label class="line1 payment-method-lbl pad-top-12">Notes</label>
						<div class="payment-create-sales-payment white">
							<input type="text" class="tabindex_field payment-input-field-tax white tt-field left font-color1" name="other-notes" value="" />
						</div>
					</div>
				</div>

				<input type="hidden" name="customer_id" value="<?php if (isset($customer[0]['id'])) echo $customer[0]['id']; else echo '';?>" />
				<input type="hidden" name="sale_id" value="<?php echo $sale_id;?>" />
				<input type="hidden" name="sale_key" value="<?php echo $sale_key;?>" />
				<input type="hidden" name="paid_today" id="paid_today" value="<?php echo $paid_today?>" readonly="true" />
				<input type="hidden" name="card_type" id="card_type" value="<?php echo $card_type; ?>" />

			</div>
		</form>
	</div>
</div>

<style>
	.row-fluid{min-height:700px;}
</style>
		
<script type="text/javascript">
$(document).ready(function()
{
	if($(window).width() <= 700) 
	{
		$('.info-column-1').css("width","100%");
		$('.info-column-2').css("width","100%");//42		
		$('.info').css("padding-bottom","8px");//2			
	}
	else if (($(window).width() > 700) && ($(window).width() < 1286))
	{
		$('.info-column-1').css("width","80%");//42
		$('.info-column-2').css("width","100%");//42		
		$('.info').css("padding-bottom","8px");//2	
	}
	else
	{
		$('.info-column-1').css("width","40%");//42
		$('.info-column-2').css("width","40%");//42		
		$('.info').css("padding-bottom","3px");//2	
	}
	$(window).resize(function()
	{
		if($(window).width() <= 700) 
		{
			$('.info-column-1').css("width","100%");
			$('.info-column-2').css("width","100%");//42		
			$('.info').css("padding-bottom","8px");//2			
		}
		else if (($(window).width() > 700) && ($(window).width() < 1286))
		{
			$('.info-column-1').css("width","80%");//42
			$('.info-column-2').css("width","100%");//42		
			$('.info').css("padding-bottom","8px");//2	
		}
		else
		{
			$('.info-column-1').css("width","40%");//42
			$('.info-column-2').css("width","40%");//42		
			$('.info').css("padding-bottom","3px");//2	
		}
	});
	$('.month-select').live('click',function(){
		var month = $(this).find('label').text();
		$('#month').val(month);
		var selected_year = $('#year').val();
		if(selected_year != ''){
			$('#expiry_date').val(month+'/'+selected_year);
		}
	});
	$('.year-select').live('click',function(){
		var year = $(this).find('label').text();
		year = year.substring(2,4);
		$('#year').val(year);
		var selected_month = $('#month').val();
		if(selected_month != ''){
			$('#expiry_date').val(selected_month+'/'+year);
		}
	});
});


$(".inactive-save-enable").click(function(){ //normal view
	if($(".create-job-enable-disable1").hasClass('job-disable1')== true && $(".create-job-enable-disable1").hasClass('enable1')== true )
	{
		$('.enable1').attr("src","/media/images/tt-new/disable.png");
		$('.enable1').removeClass('job-disable1');
		document.getElementById("save_card").value = '0';
	}
	else if($(".create-job-enable-disable1").hasClass('job-disable1')== false )
	{
		$('.enable1').attr("src","/media/images/tt-new/enable.png");
		$('.enable1').addClass('job-disable1');
		document.getElementById("save_card").value = '1';
	}
});

$(document).bind('click', function(e) {
	var $clicked = $(e.target);
	if (($clicked.hasClass("service-down-arrow1"))||($clicked.hasClass("selected3"))||($clicked.hasClass("payment_input1"))) {
		$('.payment-popup').show();
		if(($clicked.hasClass("payment-list-row"))){
			$('.payment-popup').hide();
		}
	} else {
		if ($(".payment_input").is(":focus")) {
		}
		else {
		$('.payment-popup').hide();
		}
	}
	if (($clicked.hasClass("expiry_popup_arrow_month"))) {
		$('.popup-contents-month-year.month').fadeIn(500);
	}	
	else if (($clicked.hasClass("expiry_popup_arrow_year"))) {
		$('.popup-contents-month-year.year').fadeIn(500);
	} else {
		$('.popup-contents-month-year').fadeOut(500);
	}
});

$(".payment-list-row").click(function(){ //normal view
	// add input.
	var selected_text =$(this).text().trim();
	var payment_type	=	$(this).find('.payment_type').val().trim();

	$('.payment_input1').val(selected_text);
	
	var ccp 	= <?php echo $ccp; ?>;
	var status 	= <?php echo $gateway_info['status']; ?>;
	var payment = <?php if(!empty($payment_info)) { echo 1; } else { echo 0;} ?> ;
	
	var cust_pymnt_method  = '<?php if(!empty($payment_method_details[0]['payment_method'])) { echo $payment_method_details[0]['payment_method']; } else { echo '';} ?>';
	var sale_pymnt_method  = $('.payment_input1').val();
	
	var card_name 	= '<?php if (isset($payment_info[0]['name_on_card'])) { echo $payment_info[0]['name_on_card']; } ?>';
	var last4digits = '<?php if (isset($payment_info[0]['last_digits_on_card'])) { echo $payment_info[0]['last_digits_on_card']; } ?>';
	var expiry_date = '<?php if (isset($payment_info[0]['expiration_date'])) { echo $payment_info[0]['expiration_date']; } ?>';
	
	$('#card_type').val(payment_type);
	if(payment_type != $.CREDIT_CARD){
		$('.autn-btn').html('<input type="button" class="authorize-button" name="authorize" value="Save" onclick="submit_payment_form(0);" />');
	} else {
		// check if CCP is on/off
		if(ccp ==1 && status==1){ 
			if(payment==1){
				// show authourize
				if(cust_pymnt_method == sale_pymnt_method){
					$('.autn-btn').html('<input type="button" class="authorize-button" name="authorize" value="Authorize" onclick="submit_payment_form(1);" />');
					$('#name_on_card').val(card_name);
					$('#expiry_date').val(expiry_date);
					$('#last_digits').val(last4digits);
					if(expiry_date != ''){
						$('#month').val(expiry_date.substring(0,2));
						$('#year').val(expiry_date.substring(3,5));
					}
				} else {
					$('#name_on_card').val('');
					$('#expiry_date').val('');
					$('#last_digits').val('');
					$('#month').val('');
					$('#year').val('');
					// remove button
					$('.autn-btn').html(''); 
				}
			} else {
				// remove button
				$('.autn-btn').html(''); 
			}
		} else {
			// show save
			$('.autn-btn').html('<input type="button" class="authorize-button" name="authorize" value="Save" onclick="submit_payment_form(0);" />'); 
		}
		
	}
	$("div[card_type]").each(function(){
		//console.log(($(this).attr('card_type') == payment_type));
		if ($(this).attr('card_type') == payment_type){
			$(this).show();
		} else {
			$(this).hide();
		}
	});

});
</script>
<style>
.row-fluid{
margin-top:2px !important;
}
</style>

