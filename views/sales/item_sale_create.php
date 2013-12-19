<!--
 * @File : item_sale_create.php
 * @Author: 
 * @Created: 03-10-2012
 * @Modified:  
 * @Description: view for creating item sales.
 * Copyright (c) 2012 Acclivity Group LLC 
-->

<!-- Le jquery switch-->
<?php if(isset($country)&&$country!=USA) { // non-us ?>
    <link href="/media/css/jquery.switch.css" type="text/css" rel="stylesheet" />
 	<script type="text/javascript" src="/media/scripts/jquery.switch.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$('select#select_tax_inclusive').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
			 if(type == "off") {
				$("#is_tax_inclusive").val("0");
	        } else {
	        	$("#is_tax_inclusive").val("1");
	        }
	        calculate_sub_total();
	    });
	    $('.ui-switch').css("top", "0px");
	    $('.amt_line2').css("top-padding", "0px !important");
	});
	</script>
<?php } 
	echo $sales_create_header;
    echo $popups_sales; 
?>        
<div class="row-fluid top-mar-huge" id="row-fluid" onclick="">
	<div class="name-customer margin-left-1 margin-right-1">
		<div class="date-field">
			<form id="chooseDateForm" action="#" name="chooseDateForm">
			<input type="text" class="date-label date-input" id="specified_date" readonly value="<?php echo date("F d, Y");?>"/> 
			</form>
		</div>
	</div>			
	<form method="post" action="" id="item_invoice" name="item_invoice">
		<input type="hidden" name="phone_mode" id="phone_mode" value="" />
		<input type="hidden" value="1" id="total_items" name="total_items" />
		<input type="hidden" value="<?php echo $sale_type;?>" id="sale_type" name="sale_type" />
		<?php if(isset($error)) {?>
			<div>
				<div align="center" class="error_message"><?php echo $error?></div>
			</div>
		<?php } ?>
		<div class="customer-name-add-order">
	
		<input type="text" class="customer-name-label-span border_none tabindex_field popup_validate" readonly popup_id="<?php echo CUSTOMER_POPUP;?>" placeholder="Select Customer" value="" name="customer_text" id="customer_text" onfocus="hide_popup(this);"/>
		<input type="hidden" name="customer" class="customerid_val" id="customer"/> 
		<input type="hidden" name="customer_taxcode" class="customerid_taxcode" id="customer_taxcode"/>
		<input type="hidden" name="customer_taxpercentage" class="customerid_taxpercentage" id="customer_taxpercentage"/>
		<input type="hidden" name="customer_tax_record_id" class="customerid_tax_record_id" id="customer_tax_record_id"/>		
		<input type="hidden" value="" class="use_customer_tax_code" />		
		<a class="service-down-arrow-customer" href="javascript:void(0)" id="openCustNamesPopup"></a>
		</div>
		<input type="hidden" name="selected_date" id="selected_date" value=""/>
		<div class="clear"></div>
		<div class="info-column-1 address-info-1 margin-left-1" style="margin-top:-54px;">
			<div class="info">
				<label class="line1">Shipping Address</label>
				<div class="employee_address_field" >
				<input type="text" name="address1" id="address1" class="address-input-field focus_field font-color1 tabindex_field" value="<?php echo $address1?>"/>
				</div>
			</div>
			<div class="info">
				<label class="line1"></label>
				<div class="employee_address_field" >
				<input type="text" name="address2" id="address2" class="address-input-field font-color1 tabindex_field" value="<?php echo $address2?>"/>
				</div>
			</div>
			<div class="info">
				<label class="line1"></label>
				<div class="employee_address_field" >
				<input type="text" name="address3" id="address3" class="address-input-field font-color1 tabindex_field" value="<?php echo $address3?>"/>
				</div>
			</div>
		</div>
		<div class="info-column-2 address-info-2 margin-right-1" style="margin-top:-54px;">
			<div class="info">
				<label class="line1">Sale #</label>
				<div class="employee_address_field" >
				<input type="text" name="sale_number" id="sale_number" maxlength="8" value="<?php echo $sale_number?>" class="address-input-field font-color1 tabindex_field" >
				</div>
			</div>
			<div class="info">
				<label class="line1">Customer PO #</label>
				<div class="employee_address_field" >
				<input type="text" name="customer_po" id="customer_po" value="<?php echo $customer_po?>" class="address-input-field font-color1 tabindex_field" />
				</div>
			</div>
			<div class="info relative">
				<label class="line1">Terms</label>
				<div class="employee_address_field" >
				<input type="text" name="terms" readonly id="terms"  value="<?php echo $terms?>" class="address-input-field font-color1 pointer tabindex_field" />
				<a id="" class="terms_arrow Individualarrow right" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<!-- Sales Item List starts -->
		<div class="item-list margin-left-1 margin-right-1 item-container">
			<!--Item Heading starts-->
			<div class="name-salesItem">
				<div class="salesItem7">
					<div class="salesItem6">
						<div class="salesItem5">
							<div class="salesItem4">
								<div class="salesItem3">
									<div class="salesItem2">
										<div class="salesItem-1-field"><label class="salesItem-name-field pad-left-10 font-color">Item #</label></div>
										<div class="salesItem-2-field"><label class="salesItem-name-field pad-left-10 font-color">Item Description</label></div>
										<div class="salesItem-3-field"><label class="salesItem-name-field pad-left-10 font-color">Quantity</label></div>
										<div class="salesItem-4-field"><label class="salesItem-name-field pad-right-10 font-color right-aligned1">Price</label></div>
										<div class="salesItem-5-field"><label class="salesItem-name-field pad-right-10 font-color right-aligned1">Total</label></div>
										<div class="salesItem-6-field"><label class="salesItem-name-field pad-left-10 font-color">Job</label></div>
										<div class="salesItem-7-field"><label class="salesItem-name-field pad-left-10 font-color"><?php echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'taxName'); ?></label></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--Item Heading ends -->
			<!-- Item Values Starts Loop Section -->
			<div class="name-salesItem1 additional_content_1">
				<div class="salesItem7">
					<div class="salesItem6">
						<div class="salesItem5">
							<div class="salesItem4">
								<div class="salesItem3">
									<div class="salesItem2">
										<div class="salesItem-1-field salesItem_1">
											<input type="text" popup_id="<?php echo ITEMS_POPUP;?>" readonly class="items_field_1 left service-invoice-field-item font-color1 tabindex_field popup_validate" onfocus="hide_popup(this);" />
											<input type="hidden"  name="item_number[]" class="items-value item_val_1"/>
											<a id="openItemPopup" href="javascript:void(0)" class="item_popup service-down-arrow1 mar-rig-10 pad-20 right openItemPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a>
										</div>
										<div class="salesItem-2-field">
											<input type="text" onfocus="hide_popup(this);" class="service-invoice-field-select left item_description popup_validate description_1 font-color1 tabindex_field" name="item_description[]" value="<?php echo $item_description;?>" />
										</div>
										<div class="salesItem-3-field">
											<input type="text" onfocus="hide_popup(this);" class="service-invoice-field left font-color1 number quantity popup_validate tabindex_field" id="qty" name="quantity[]" 
											value="0" 
											onkeyup="calculate_item_total1(this);resize_input_box();">
										</div>
										<div class="salesItem-4-field">
											<input type="text" onfocus="hide_popup(this);" class="price1 service-invoice-field right number font-color1 amt_ipt1 item_price tabindex_field popup_validate"  
													id="price" name="price[]" value="0.00"
													onblur="this.value=Number(this.value).toFixed(2);resize_tag('price', 0);"
													onkeyup="calculate_item_total1(this);resize_input_box();"/>
											<span class='dollar_sales_items right mar-left-8'>$</span>
										</div>
										<div class="salesItem-5-field">
											<input type="text" id='tlt_amt' class="service-invoice-field font-color1 right amt_ipt1 total tabindex_field" 
													value="0.00" readonly
											/>
										 	<input type="hidden" class="original_total_amount" value="" name="total[]" />   
											<span class='dollar_sales_items right mar-left-8'>$</span>
										</div>
										<div class="salesItem-6-field popup_jobs_1"><input type="text" value="" readonly popup_id="<?php echo JOBS_POPUP;?>" class="jobs_field_1 left service-invoice-field-job font-color1 popup_textfield tabindex_field"><input type="hidden" name="job[]" class="job_val_1" /><a id="openJobPopup" href="javascript:void(0)" class="jobs-popup service-down-arrow1 pad-20 right mar-rig-10">&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
										<div class="salesItem-7-field">
											<?php if(isset($country)&&$country != USA){ // for Non-US?>
											<?php // I can't optimize this because java script is using it ?>
											<input type='hidden' name='tax_check[]' class='tax_check_value' value='1' />
											<span class="tax_applied"></span>
											<a class="service-down-arrow amt_side2 openTaxPopup1" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;&nbsp;</a>
											<?php } else { // US?>
											<?php // I can't optimize this because java script is using it ?>
											<input type='hidden' name='tax_check[]' class='tax_check_value' value='1' />
											<label class="service-invoice-field-tax mar-left-8 font-color1">
											<a href="javascript:void(0);" class="tax-chk tax-enable"><img src="/media/images/tt-new/enable.png" class="tax-enable-disable tabindex_field"></a>
											</label>	
											<?php } ?>
											<input class='tax_applied_percentage' name='item_tax_percentage[]' id='item_tax_percentage' type='hidden' />
											<input class='tax_applied_record_id' name='item_tax_record_id[]' id='item_tax_record_id' type='hidden' />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Item Values Ends Loop Section -->
		</div>
		<div class='clear'></div>
			<?php include Kohana::find_file('views', 'sales/error_popups_display') ?>
		<div class='clear'></div>
		<!-- Mobile Version Starts -->
		<div class='item-container-phone'>
			<div id='' class='name-salesItem1 phone_mode additional_content_1'>
				<div id='' class='phonesalesItem_1 bor-bottom phone_fields phone_sales_item' >
					<label class='left font-light phone_labels'>Item#</label>
					<input type='text' onfocus='hide_popup(this);' readonly popup_id='<?php echo ITEMS_POPUP;?>' class='items_phonefield_1 service-invoice-field-item popup_validate left font-color1'>
					<input type='hidden' name='item_number_phone[]' class='items-value item_val_phone_1'/>
					<a id='openItemPopup' href='javascript:void(0)' class='service-down-arrow1 mar-right-4 pad-20 right'>&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
				<div class='phone_fields bor-bottom' >
					<label class='left font-light phone_labels'>Description</label>
					<input type='text' onfocus='hide_popup(this);' class='descriptionPhone_1 item_description left font-color1 popup_validate' name='item_description_phone[]' value='' />
				</div>
				<div class='phone_fields bor-bottom' >
					<label class='left font-light phone_labels'>Quantity</label>
					<input type='text' onfocus='hide_popup(this);' class='service-invoice-field left font-color1 quantity popup_validate' name='quantity_phone[]' value='0' onkeyup='calculate_item_total1(this);'/>
				</div>
				<div class='phone_fields bor-bottom' >
					<label class='left font-light phone_labels'>Price</label>
					<span class='dollar_sales_mobile left mar-left-8'>$</span>
					<input type='text' onfocus='hide_popup(this);' class='price1 service-invoice-field left font-color1 item_price popup_validate' name='price_phone[]' value='0.00'  onkeyup='calculate_item_total1(this);'/>
				</div>  
				<div class='clear'></div>
				<div class='phone_fields bor-bottom' >
					<label class='left font-light phone_labels'>Total</label>
					<span class='dollar_sales_items left mar-left-8'>$</span>
					<input type='text' class='service-invoice-field font-color1 left width-50 total left' name='' value='0.00' readonly />
					<input type="hidden" class="original_total_amount" value="" name="total_phone[]" />  
				</div>
				<div class='clear'></div>
				<div class='phone_jobs_1 phone_fields bor-bottom' >
					<label class='left font-light phone_labels'>Jobs</label>
					<input type='text' readonly popup_id='<?php echo JOBS_POPUP;?>' class='jobs_phonefield_1 service-invoice-field-job left font-color1'>
					<input type='hidden' name='job_phone[]' class='job_val_phone_1' />
					<a id='openJobPopup' href='javascript:void(0)' class='service-down-arrow1 pad-20 mar-right-4 right'>&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
				<div class='clear'></div> 
				<div class='phone_fields taxes' >
					<label class='left font-light phone_labels'><?php echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'taxName'); ?></label>
					<!----  browser HTML  we need to convert to this and also we need to check sales pop-up, main.js and controller.	-->
					<?php if(isset($country)&&$country != USA){ // for Non-US ?>
						<?php // I can't optimize this because java script is using it. ?>
						<input type='hidden' name='tax_check_phone[]' class='tax_check_value' value='1' />
						<span class='tax_applied'></span>
						<a class='service-down-arrow amt_side2 openTaxPopup1' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<?php } else { // US ?>
						<?php // I can't optimize this because java script is using it. ?>
						<input type='hidden' name='tax_check_phone[]' class='tax_check_value' value='1' />
						<label class='service-invoice-field-tax font-color1'>
							<a href='javascript:void(0);' class='tax-chk tax-enable'><img src='/media/images/tt-new/enable.png' class='tax-enable-disable'></a>
						</label>
					<?php } ?>
					<input class='tax_applied_percentage' name='item_tax_percentage_mobile[]' id='item_tax_percentage' type='hidden' />
					<input class='tax_applied_record_id'  name='item_tax_record_id_mobile[]'  id='item_tax_record_id'  type='hidden' />					
					<!----  	phone html 		-->
				</div>
			</div>
		</div>
		<div class='clear'></div>
		<!-- 	Mobile div ends 	-->
		<div class='service-add-more-buttons'>			
		<input type='button' class='add-more-btn tabindex_field' value='Add More' />
		</div>
		<div class='clear'></div>
		<div class='info-column-1 margin-left-1 margin-ipad'>
			<div class='info-block'>
				<label class='line1-block2 color-89'>Salesperson</label>
				<div class='color-change-sp'>
				<input type='text' popup_id='<?php echo SALESPERSON_POPUP;?>' class='cust-input-field font-color1 sales_person_input tabindex_field' id='' ><input type='hidden' class='emp_val' name='sales_person'/>
				<a class='service-down-arrow1 pad-20 right' href='javascript:void(0)' id='openNamesPopup'>&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
			<div class='info-block'>
				<label class='line1-block2 color-89'>Comment</label>
				<div class='color-change-cm'>
				<input type='text' popup_id='<?php echo COMMENT_POPUP;?>' class='cust-input-field font-color1 comment_input tabindex_field'>
				<input type='hidden' class='comment_val' name='comment'/>
				<a class='service-down-arrow1 pad-20 right' href='javascript:void(0)' id='openCommentPopup'>&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
			<div class='info-block'>
				<label class='line1-block2 color-89'>Referal Source</label>
				<div class='color-change-rs'>
				<input type='text' popup_id='<?php echo REFERAL_POPUP;?>' class='cust-input-field font-color1 referal_input tabindex_field'><input type='hidden' class='reff_val' name='referal_source'/>
				<a class='service-down-arrow1 pad-20 right' href='javascript:void(0)' id='openReferalPopup'>&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
			<div class='info-block'>
				<label class='line1-block2 color-89'>Payment Method</label>
				<div class='color-change-pm'>
				<input type='text' popup_id='<?php echo PAYMENT_POPUP;?>' class='cust-input-field font-color1 payment_input tabindex_field'>
				<input type='hidden' class='payment_val' name='payment_method'/>
				<input type='hidden' class='payment_type' name='payment_type'/>
				<a class='service-down-arrow1 pad-20 right' href='javascript:void(0)' id='openPaymentPopup'>&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
			<div id='payment_validation' class='error-desc-payment' style='display:none;'><img style="margin-right:4px;" src='/media/images/tt-new/reset_error.png' />Please select a  payment method</div>
			<?php if($sale_type != '3') {?>
			<div class='info-block'>
				<label class='line1-block2 color-89'>Shipping Method</label>
				<div class='color-change-sm'>
					<input type='text' popup_id='<?php echo SHIPPING_POPUP;?>' class='cust-input-field font-color1 shipping_input tabindex_field'><input type='hidden' class='shipping_val' name='shipping_method'/>
					<a class='service-down-arrow1 pad-20 right' href='javascript:void(0)' id='openShippingPopup'>&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
			<?php } else {?>
					<input type='hidden' name='shipping_method' value='' />
			<?php }?>
		</div>			
		<div class='info-column-2 margin-right-1 margin-top-0' >
			<div class='info-service'>
				<label class='amt_line1 color-b8'>Subtotal</label>
				<div class='text'>
				<div class='amt_line2'>
					<input type='text' id='subtotal' value='0.00'  class='amt_ipt1 right color-4c' max-width='20px' readonly />
					<input type="hidden" class="original_subtotal_amount" value="" name="subtotal" />   
				<span id='amt_s1' class='amt_s1 right'>$</span>
				</div>
				</div>
			</div>
			<div class='freight-tax bor-bottom no-pad'>
				<label class='amt_line1 color-b8'><?php echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'taxType'); ?></label>
				<!--- Original Amount ::::  get this rule value from the data base store the rule in database. --->
				<div class='amt_line2'>
					<input type='text' name='freight' id='freight' value='0.00' class='freight number color-be right amt_ipt1 tabindex_field'
							amount_changed=0
							freight_orginal_amt=0
							onkeyup='set_org_freight_amt($(this));javascript:resize_tag("freight", 0);' 
							onfocus='javascript:toggle_object("amt_s2", 1); if(this.value=="0.00") {this.value=""}' 
							onblur='javascript:toggle_object("amt_s2", 0);$(this).attr("amount_changed", 1);calculate_sub_total();'
					/>
					<input type='hidden' id='freight_orginal_amt' name='freight_orginal_amt' value=0>				 
					<span id='amt_s2' class='amt_s1 right color-be'>$</span>					
				</div>
				<?php if(isset($country) && $country == USA) { // freight for US ?>
					<a href='javascript:void(0);' class='amt_side1 freight-button'><img src='/media/images/tt-new/enable.png' class='freight-enable-disable tabindex_field'></a>
				<?php } else { // freight for non-US ?>
					<span class='tax_applied_freight'></span>
					<a class='service-down-arrow amt_side3 openTaxPopup1' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;&nbsp;</a>
				<?php }?>
				<input class='tax_applied_percentage_freight' type='hidden' value='0' />
				<input class='freight_tax_record_id' id='freight_tax_record_id' name='freight_tax_record_id' type='hidden' value='0' />
				<input type='hidden' name='freight_check' id='freight_check' value='1' />
			</div>
			<?php if(isset($country)&& $country != USA) { // for non-US ?>
				<div class='info-service-tax1 box1'>
					<label class='amt_line1 color-b8'><?php if(isset($country)&&$country == UK) echo 'VAT Inclusive'; else echo 'Tax Inclusive'; ?></label>
					<div class='amt_line2 no-pad'>
						<select style='display:none;' id='select_tax_inclusive'>
	                    	<option value='1' >on</option>
	                    	<option value='0' selected='selected'>off</option>
	                    </select>	
						<input type='hidden' value='0' id='is_tax_inclusive' name='is_tax_inclusive' />
					</div>											
				</div>
				<div class='info-service-tax box1'>
					<label class='amt_line1 color-b8'><?php echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'taxName'); ?></label>
					<div class='amt_line2'>
						<span class='amt_ipt1 color-be tabindex_field tax_input number' name='tax_totl' id='tax_totl'>$&nbsp;0.00</span>
					</div>			
				</div>
				<input type='hidden' id='tax_total_amount' name='tax_total_amount'></input>
			<?php } else {  // for US?>
				<div class='info-service-tax box1'>
					<label class='amt_line1 color-b8'>Tax</label>
					<div class='amt_line2 pad-2'>
						<span class='amt_ipt1 color-be tabindex_field tax_input number' name='tax_totl' id='tax_totl'>$&nbsp;0.00&nbsp;&nbsp;0%</span> 
						<input type='hidden' name='tax_percentage' id='tax_percentage' value='0'/>
						<input type='hidden' name='tax_code' id='tax_code' value=''/>
						<input type='hidden' value='0' id='is_tax_inclusive' name='is_tax_inclusive' />
					</div>
					<a class='service-down-arrow amt_side3 openTaxPopup1' id='openTaxPopup' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;&nbsp;</a>					
				</div>
				<input type='hidden' id='tax_total_amount' name='tax_total_amount'></input>
			<?php } ?>
			<div class='info-paid-today mar'>
				<label class='amt_line1 color-b8'>Total Amount</label>
				<div class='amt_line2'>
				<input type='text' name='total_payment' id='total_payment' value="<?php if(isset($total_payment) && !empty($total_payment)){echo $total_payment;}else {echo '0.00';}?>" class="amt_ipt1 right color-4c" />
				<span id='amt_s4' class='amt_s1 right'>$</span>
				</div>					
			</div>
			<input type='hidden' class='details_tab' name='details' value='' />
			<?php if($sale_type != '3') {?>
			<div class='info no-pad height-32 no-border change_background'>
				<label class='amt_line1 color-b8'>Paid Today</label>
				<div class="amt_line2">
					<input type="text" name="paid_today" value="0.00" id="paid_today" class="tabindex_field amt_ipt1 paid_today right number color-4c" 
						onkeyup='javascript:resize_tag("paid_today", 0);'
						onfocus='javascript:toggle_object("amt_s5", 1); hide_popup();if(this.value=="0.00") {this.value=""}' 
						onblur='javascript:toggle_object("amt_s5", 0); if(this.value!="0.00") {this.value=Number(this.value).toFixed(2);} resize_tag("paid_today", 0);' />
					<span id="amt_s5" class="amt_s1 right">$</span>
				</div>

				<?php
				if(isset($ccp) && ($ccp == 0 || ($ccp==1 && isset($ach_status)&&$ach_status))){?>
					<input type="hidden" id="ccp" value="<?php echo $ccp; ?>" />
					<input type="button" id="details-button-item" class="details-button check-payment tabindex_field" value="Details" />
				<?php }?>
			</div>
			<?php } else {?>
					<input type="hidden" name="paid_today" value="" id="paid_today"  class="address-input-field font-color1" />
			<?php }?>
			<div class="double_border">
				<div class="info">
					<label class="amt_line1 color-7f only_balance">Balance Due:</label>
					<div class="amt_line2">
						<input type="text" name="balance_due" value="<?php if(isset($balance_due) && !empty($balance_due)){echo $balance_due;}else {echo "0.00";}?>" id="balance_due"class="amt_ipt1 right"/>
						<span id="amt_s6" class="amt_s1 amt_s2 right">$</span>
					</div>
				</div>
			</div>			
			<div class="edit-delete-buttons" style="margin-top: 8px !important">
				<a href="<?php echo SITEURL?>/sales/"><div class="service-save-button edit-label-btn tabindex_field">Cancel</div></a>
				<input type="button" class="service-cancel-button save-input tabindex_field" id="save_item_sale"  value="Save" />
			</div>
		</div>
	</form>
	<!-- Start Taxes Pop Up Here -->
	<div class="tax-values-popup" style="display:none;">	
		<div id="tax_drop_down" style="display:none;">
			<div class="popup-items1-taxes">
				<?php foreach($taxes as $t) {
					$percentage = 0;
					if(isset($t['consolidated_taxes'])){ // show consolidated TAX button 
						$consolidated_data= $t['consolidated_taxes'];
						foreach($consolidated_data as $c_tax){
							$percentage += floatval($c_tax['percentage']);
						}	
					} else {
						$percentage = $t['percentage'];
					} ?>
				<div class="tax-popup-list first tax-list-row" >
					<label class="names-popup-list-label heavy">
						<?php echo $t['tax_code'].' '.$t['description'];?><span style="float:right;"><?php echo $percentage.'%';?></span>
						<input type="hidden" name="taxcode[]" class="taxcode" value="<?php echo $t['tax_code'];?>"/>
						<input type="hidden" name="taxdescription[]" class="taxdescription" value="<?php echo $t['description'];?>"/>
						<input type="hidden" name="taxpercentage[]" class="taxpercentage" value="<?php echo $percentage;?>"/>
						<input type="hidden" name="taxid[]" class="taxid" value="<?php echo $t['id'];?>"/>
						<input type="hidden" name="tax_record_id[]" class="tax_record_id" value="<?php echo $t['tax_record_id'];?>"/>
					</label>
				</div>
				<?php 
				} ?>
			</div>
		</div>
	</div>
	<!-- Ends Taxes Pop Up Here -->
	
</div>
<div class="ending-blue-line"></div>

	<script type="text/javascript">
	$(document).ready(function()
	{
		$('#customer_text').focus().click();
		$('#act-slip-lbl').css('font-size','18px');
		if($(window).width() <= 400) 
		{
		$('.info-column-1').css("width","98%");//42
		$('.info-column-2').css("width","98%");//42		
		$('.item-list').hide();
		}
		else if (($(window).width() > 401) && ($(window).width() <= 750))
		{
			$('.info-column-1').css("width","98%");//42
			$('.info-column-2').css("width","98%");//42		
			$('.item-list').hide();
		}
		else if (($(window).width() > 750) && ($(window).width() < 1000))
		{
		$('.info-column-1').css("width","42%");//42
		$('.info-column-2').css("width","42%");//42		
		$('.item-list').show();
		}
		else if (($(window).width() > 1000) && ($(window).width() < 1050))
		{
		$('.info-column-1').css("width","40%");//42
		$('.info-column-2').css("width","40%");//42		
		$('.item-list').show();
		}
		else if (($(window).width() > 1050) && ($(window).width() < 1300))
		{
		$('.info-column-1').css("width","40%");//42
		$('.info-column-2').css("width","40%");//42		
		$('.item-list').show();
		}
		else
		{
		$('.info-column-1').css("width","40%");//42
		$('.info-column-2').css("width","40%");//42		
		$('.item-list').show();
		}
		$(window).resize(function()
		{	$('#act-slip-lbl').css('font-size','18px');
			if($(window).width() <= 400) 
			{
			$('.info-column-1').css("width","98%");//42
			$('.info-column-2').css("width","98%");//42		
			$('.item-list').hide();
			}
			else if (($(window).width() > 401) && ($(window).width() <= 750))
			{
				$('.info-column-1').css("width","98%");//42
				$('.info-column-2').css("width","98%");//42		
				$('.item-list').hide();
			}
			else if (($(window).width() > 750) && ($(window).width() < 1000))
			{
				$('.info-column-1').css("width","42%");//42
				$('.info-column-2').css("width","42%");//42		
				$('.item-list').show();
			}
			else if (($(window).width() > 1000) && ($(window).width() < 1050))
			{
			$('.info-column-1').css("width","40%");//42
			$('.info-column-2').css("width","40%");//42		
			$('.item-list').show();
			}
			else if (($(window).width() > 1050) && ($(window).width() < 1300))
			{
			$('.info-column-1').css("width","40%");//42
			$('.info-column-2').css("width","40%");//42		
			$('.item-list').show();
			}
			else
			{
			$('.info-column-1').css("width","40%");//42
			$('.info-column-2').css("width","40%");//42		
			$('.item-list').show();
			}
		});
		});
	</script>
