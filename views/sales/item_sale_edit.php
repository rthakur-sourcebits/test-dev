<!-- 
 * @File : item_sale_edit.php
 * @Author: 
 * @Created: 26-09-2012
 * @Modified:  
 * @Description: view for editing item sale
 * Copyright (c) 2012 Acclivity Group LLC 
-->
<!-- Le jquery switch-->
<?php if(isset($country)&&$country != USA) { // for non-US?>
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
<?php } ?>

<?php 
echo $popups_sales;
echo $sales_edit_header;
$total_tax=0;
?>



<div class="row-fluid top-mar-huge" id="row-fluid" onclick="">
	<div class="name-customer margin-left-1 margin-right-1">
	<div class="date-field">
		<form id="chooseDateForm" action="#" name="chooseDateForm">
		<input type="text" class="date-label date-input" id="specified_date" readonly value="<?php echo date("F d, Y",strtotime($sale[0]['selected_date']));?>" />
		</form>
	</div>
	</div>
	<form method="post" action="<?php echo SITEURL;?>/sales/create" id="item_invoice" name="item_invoice">
		<input type="hidden" name="phone_mode" id="phone_mode" value="" />
	    <?php if(isset($error)) {?>
		<div>
			<div align="center" class="error_message"><?php echo $error?></div>
		</div>
	    <?php } ?>
		<div class="customer-name-add-order">
			<input type="text" popup_id="<?php echo CUSTOMER_POPUP;?>" class="customer-name-label-span tabindex_field" readonly value="<?php echo $sale[0]['firstname'].' '.$sale[0]['company_or_lastname'];?>" name="customer_text" id="customer_text"/>
			<input type="hidden" name="customer" class="customerid_val" value="<?php echo $sale[0]['customer_id']?>" id="customer"/> 
			<input type="hidden" name="customer_taxcode" class="customerid_taxcode" id="customer_taxcode" value="<?php if(isset($sale[0]['tax_code'])){ echo $sale[0]['tax_code']; } else { echo '';}?>"/>
			<input type="hidden" name="customer_taxpercentage" class="customerid_taxpercentage" id="customer_taxpercentage" value="<?php if(isset($sale[0]['percentage'])){ echo $sale[0]['percentage']; }  else { echo 0;} ?>" />
			<input type="hidden" name="customer_tax_record_id" class="customerid_tax_record_id" id="customer_tax_record_id" value="<?php if(isset($sale[0]['tax_record_id'])){ echo $sale[0]['tax_record_id']; }  else { echo 0;}?>" />
			<input type="hidden" value="<?php echo $sale[0]['use_customer_tax_code']?>" class="use_customer_tax_code" />	
			<a class="service-down-arrow-customer" href="javascript:void(0)" id="openCustNamesPopup"></a>
		</div>
		<input type="hidden" name="selected_date" id="selected_date" value="<?php echo $sale[0]['selected_date']?>" />
		<div class="clear"></div>
		<div class="info-column-1 address-info-1 margin-left-1" style="margin-top:-54px;">
			<div class="info">
				<label class="line1">Shipping Address</label>
				<div class="employee_address_field" >
				<input type="text" name="address1" id="address1" class="address-input-field tabindex_field font-color1" value="<?php echo $sale[0]['address1']?>"/>
				</div>
			</div>
			<div class="info">
				<label class="line1"></label>
				<div class="employee_address_field" >
				<input type="text" name="address2" id="address2" class="address-input-field tabindex_field font-color1" value="<?php echo $sale[0]['address2']?>"/>
				</div>
			</div>
			<div class="info">
				<label class="line1"></label>
				<div class="employee_address_field" >
				<input type="text" name="address3" id="address3" class="address-input-field tabindex_field font-color1" value="<?php echo $sale[0]['address3']?>"/>
				</div>
			</div>
		</div>
		<div class="info-column-2 address-info-2 margin-right-1" style="margin-top:-54px;">
			<div class="info">
				<label class="line1">Sale #</label>
				<div class="employee_address_field" >
				<input type="text" name="sale_number" id="sale_number" maxlength="8" value="<?php echo $sale[0]['sale_number']?>" class="address-input-field tabindex_field font-color1" >
				</div>
			</div>
			<div class="info">
				<label class="line1">Customer PO #</label>
				<div class="employee_address_field" >
				<input type="text" name="customer_po" value="<?php echo $sale[0]['customer_po']?>" class="address-input-field tabindex_field font-color1" >
				</div>
			</div>
			<div class="info relative">
				<label class="line1">Terms</label>
				<div class="employee_address_field" >
				<input type="text" readonly name="terms" value="<?php echo $sale[0]['terms']?>" class="address-input-field tabindex_field pointer font-color1 ">
				<a id="" class="terms_arrow Individualarrow right" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
		</div>			
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
										<div class="salesItem-2-field"><label class="salesItem-name-field pad-left-10 font-color">Item description</label></div>
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
			<?php 
        	$k	=	0;
        	$count = count($sale_item);
        	$subtotal=0;
        	$total = 0;
        	foreach($sale_item as $item) {
        		if($country == USA){ // For US
					$total	=	$item['total'];
				} else { // For Non-US
					if(isset($sale[0]['is_tax_inclusive'])&&$sale[0]['is_tax_inclusive']=='1'){
						$total	=	$item['total'];
						$total			+= (($total*$item['percentage'])/100);
						$total			=	number_format((float)$total, 2, '.', '');
						$subtotal				+=	$total;
					} else {
						$total	=	$item['total'];
					}
				}
        	?>
        	<div class="name-salesItem1 additional_content_<?php echo $k+1;?>">
				<div class="salesItem7">
					<div class="salesItem6">
						<div class="salesItem5">
							<div class="salesItem4">
								<div class="salesItem3">
									<div class="salesItem2">
										<div class='salesItem-1-field salesItem_<?php echo $k+1;?>'><input type="text" popup_id="<?php echo ITEMS_POPUP;?>" readonly class="items_field_<?php echo $k+1;?> service-invoice-field-item tabindex_field font-color1" value="<?php echo $item['item_number']?>"><input type="hidden" name="item_number[]" class="items-value item_val_<?php echo $k+1;?>" value="<?php echo $item['item_id']?>" /><a id="openItemPopup" href="javascript:void(0)" class="item_popup mar-rig-10 service-down-arrow1 pad-20 right openItemPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
										<div class="salesItem-2-field"><input type="text" class="service-invoice-field-select font-color1 tabindex_field description_<?php echo $k+1;?> item_description" name="item_description[]" value="<?php echo $item['item_name']?>"></div>
										<div class="salesItem-3-field"><input type="text" class="service-invoice-field font-color1 number tabindex_field quantity" name="quantity[]" 
											value="<?php echo $item['quantity']?>" 
											onkeyup="calculate_item_total1(this);resize_input_box(this);"></div>

										<div class="salesItem-4-field">
											<input type="text" class="price<?php echo $k+1;?> service-invoice-field right number item_price font-color1 amt_ipt1 tabindex_field"  
													id="price<?php echo $k+1;?>" name="price[]" value="<?php echo $item['price']; ?>"
													onblur="this.value=Number(this.value).toFixed(2);resize_tag(this, 0);"
													onfocus='if(this.value=="0.00") {this.value=""}'
													onkeyup="calculate_item_total1(this);resize_input_box(this);"/>
											<span class='dollar_sales_items right mar-left-8'>$</span>
										</div>
										<div class="salesItem-5-field">
											<input type="text" id='tlt_amt<?php echo $k+1;?>' class="service-invoice-field font-color1 right amt_ipt1 total tabindex_field"  
													value="<?php echo $total;?>" readonly
													/>
													<input type="hidden" class="original_total_amount" value="<?php echo $item['total'];?>" name="total[]" />   
											<span class='dollar_sales_items right mar-left-8'>$</span>
										</div>											
										<div class="salesItem-6-field popup_jobs_<?php echo $k+1;?>"><input type="text" readonly popup_id="<?php echo JOBS_POPUP;?>" class="jobs_field_<?php echo $k+1;?> service-invoice-field-job tabindex_field font-color1" value="<?php echo $item['job_name']?>"><input type="hidden" value="<?php echo $item['job_id']?>" name="job[]" class="job_val_<?php echo $k+1;?>" /><a id="openJobPopup" href="javascript:void(0)" class="jobs-popup openJobPopup service-down-arrow1 mar-rig-10 pad-20 right">&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
										<?php 
										if(isset($item['apply_tax']) && $item['apply_tax'] == '1') 
    										{
    										  $tax_chk = '';
    										  $tax_enab = 'enable';
    										}
    									else
	                                        {
    										  $tax_chk = 'tax-disable';
    										  $tax_enab = 'tax-disable';
    									    }
    									?>
										<div class="salesItem-7-field">
											<?php if(isset($country)&&$country != USA){ // for Non-US?>
												<?php // I can't optimize this because java script is using it ?>
												<input type='hidden' name='tax_check[]' class='tax_check_value' value='1' />
												<span class="tax_applied"><?php echo $item['tax_code']; ?></span>
												<?php if(($sync_status == 0) && $count>1) {?>
												<span class='delete_row_create' onclick="update_deleted_records('1','<?php echo $item['id']?>','<?php echo $saleId?>')";></span>
												<?php }?>
												<a class="service-down-arrow amt_side2 openTaxPopup1" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;&nbsp;</a>
											<?php } else { // US?>
												<?php // I can't optimize this because java script is using it ?>
												<input type="hidden" name="tax_check[]" class="tax_check_value" value="<?php echo $item['apply_tax'];?>" />
												<label class="service-invoice-field-tax mar-left-8 font-color1">
												<a href="javascript:void(0);" class="tax-chk <?php echo $tax_chk;?>"><img src="/media/images/tt-new/<?php echo $tax_enab;?>.png" class="tax-enable-disable tabindex_field"></a>
												<?php if(($sync_status == 0) && $count>1) {?><a onclick="update_deleted_records('1','<?php echo $item['id']?>','<?php echo $saleId?>');" style="margin-top:-5px !important" class="delete_row mar-rig-10 delete_row_<?php echo $item['id']?>"></a>
											<?php }?>
											</label>	
											<?php } ?>
											<input class='tax_applied_percentage' name='item_tax_percentage[]' id='item_tax_percentage' type='hidden' value="<?php echo $item['percentage']; ?>" />
											<input class='tax_applied_record_id' name='item_tax_record_id[]' id='item_tax_record_id' type='hidden' value="<?php echo $item['tax_record_id']; ?>" />												
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="item_id[]" value="<?php echo $item['id'];?>" />
			<?php
	    	$k++; 
	        }
        	?>
			<!-- Item Values Ends Loop Section -->
		</div>
	
		<!-- Mobile Version Starts -->
		<div class="item-container-phone">
		    <?php 
			$k	=	0;
			$subtotal = 0;
			$total = 0;
		    foreach($sale_item as $item) {
		    	if($country == USA){ // For US
					$total	=	$item['total'];
				} else { // For Non-US
					if(isset($sale[0]['is_tax_inclusive'])&&$sale[0]['is_tax_inclusive']=='1'){
						$total	=	$item['total'];
						$total			+= (($total*$item['percentage'])/100);
						$total			=	number_format((float)$total, 2, '.', '');
						$subtotal				+=	$total;
					} else {
						$total	=	$item['total'];
					}
				}
			?>
			<div id="" class="name-salesItem1 phone_mode additional_content_<?php echo $k+1;?>">
				<div id="" class="phonesalesItem_<?php echo $k+1;?> bor-bottom phone_fields phone_sales_item" >
					<label class="left font-light phone_labels">Item#</label>
					<input type="text" popup_id="<?php echo ITEMS_POPUP;?>" readonly value="<?php echo $item['item_number'];?>" class="items_phonefield_<?php echo $k+1;?> service-invoice-field-item left font-color1">
					<input type="hidden" name="item_number_phone[]" value="<?php echo $item['item_id']?>" class="items-value item_val_phone_<?php echo $k+1;?>"/>
					<a id="openItemPopup" href="javascript:void(0)" class="service-down-arrow1 pad-20 mar-right-4 right">&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
	
				<div class="phone_fields bor-bottom" >
					<label class="left font-light phone_labels">Description</label>
					<input type="text" class="descriptionPhone_<?php echo $k+1;?> item_description left font-color1" name="item_description_phone[]" value="<?php echo $item['item_name']?>" />
				</div> 
	
				<div class="phone_fields bor-bottom" >
					<label class="left font-light phone_labels">Quantity</label>
					<input type="text" class="service-invoice-field left font-color1 quantity" id="" name="quantity_phone[]" value="<?php echo $item['quantity'];?>" onkeyup="calculate_item_total1(this);"/>
				</div> 
				
				<div class="phone_fields bor-bottom" >
					<label class="left font-light phone_labels">Price</label>
					<span class='dollar_sales_mobile left mar-left-8'>$</span>
					<input type="text" class="price<?php echo $k+1;?> service-invoice-field left font-color1 item_price" name="price_phone[]" value="<?php echo $item['price'];?>" onkeyup="calculate_item_total1(this);"/>
				</div>  
				<div class="clear"></div>
	
				<div class="phone_fields bor-bottom" >
					<label class="left font-light phone_labels">Total</label>
					<span class='dollar_sales_items left mar-left-8'>$</span>
					<input type="text" class="service-invoice-field font-color1 left width-50 total left"  value="<?php echo $total;?>" readonly />
					<input type="hidden" class="original_total_amount" value="<?php echo $item['total'];?>" name="total_phone[]" />
				</div>
				
				<div class="clear"></div>
				<div class="phone_jobs_<?php echo $k+1;?> phone_fields bor-bottom" >
					<label class="left font-light phone_labels">Jobs</label>
					<input type="text" popup_id="<?php echo JOBS_POPUP;?>" readonly value="<?php echo $item['job_name'];?>" class="jobs_phonefield_<?php echo $k+1;?> service-invoice-field-job left font-color1">
					<input type="hidden" name="job_phone[]" value="<?php echo $item['job_id']?>" class="job_val_phone_<?php echo $k+1;?>" />
					<a id="openJobPopup" href="javascript:void(0)" class="service-down-arrow1 pad-20 right mar-right-4">&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div> 
	
				<div class="phone_fields taxes" >
					<?php 
					if(isset($item['apply_tax']) && $item['apply_tax'] == '1')
						{
						  $tax_chk = '';
						  $tax_enab = 'enable';
						}
					else
	                    {
						  $tax_chk = 'tax-disable';
						  $tax_enab = 'tax-disable';
					    }
					?>
					<label class="left font-light phone_labels"><?php echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'taxName'); ?></label>
					<?php if(isset($country)&&$country != USA){ // for Non-US ?>
						<?php // I can't optimize this because java script is using it. ?>
						<input type="hidden" value="<?php echo $item['apply_tax'];?>" name="tax_check_phone[]" class="tax_check_value" />
						<span class="tax_applied"><?php echo $item['tax_code']; ?></span>
						<a class='service-down-arrow amt_side2 openTaxPopup1' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;&nbsp;</a>
						<?php if(($sync_status == 0) && $count>1) {?>
	        				<a onclick="update_deleted_records('1','<?php echo $item['id']?>','<?php echo $saleId?>');" class="delete_row mar-rig-10 delete_row_<?php echo $item['id']?>">
							</a>
						<?php }
					} else { // US ?>
						<?php // I can't optimize this because java script is using it. ?>
						<input type="hidden" value="<?php echo $item['apply_tax'];?>" name="tax_check_phone[]" class="tax_check_value" />
						<label class='service-invoice-field-tax font-color1'>
							<?php if(($sync_status == 0) && $count>1) {?>
	    						<a onclick="update_deleted_records('1','<?php echo $item['id']?>','<?php echo $saleId?>');" style="margin-top:-5px !important" class="delete_row mar-rig-10 delete_row_<?php echo $item['id']?>"></a>
							<?php }?>
							<a href="javascript:void(0);" class="tax-chk <?php echo $tax_chk;?>"><img src="/media/images/tt-new/<?php echo $tax_enab;?>.png" class="tax-enable-disable"></a>
						</label>
						
					<?php 
					} ?>
					<input class='tax_applied_percentage' name='item_tax_percentage_mobile[]' id='item_tax_percentage' type='hidden' value="<?php echo $item['percentage']; ?>" />
					<input class='tax_applied_record_id' name='item_tax_record_id_mobile[]' id='item_tax_record_id' type='hidden' value="<?php echo $item['tax_record_id']; ?>" />
				</div>
			</div>
			<div class="clear"></div>
			<input type="hidden" name="item_id_phone[]" value="<?php echo $item['id'];?>" />
				<?php
		    	$k++; 
				 // Calculating total tax amount.
				if(isset($item['apply_tax'])&& $item['apply_tax']=='1'){
					$total_tax+=($item['total']*$item['percentage']/100);
				}
				
			}?>
		</div>
		<!-- Mobile Version Ends -->
	
	<div class="clear"></div>
	<div class="service-add-more-buttons">
		<input type="button" class="add-more-btn tabindex_field" value="Add More" />
	</div>
	<div class="clear">
	</div>
	<div class="clear"></div>
		<?php include Kohana::find_file('views', 'sales/error_popups_display') ?>
	<div class="clear"></div>

	<div class="info-column-1 margin-top-3 margin-left-1 margin-ipad">
		<div class="info-block">
			<label class="line1-block2 color-89">Salesperson</label>
			<div class="color-change-sp">
			<input type="text" popup_id="<?php echo SALESPERSON_POPUP;?>" class="cust-input-field font-color1 tabindex_field sales_person_input" id="" value="<?php echo $sale[0]['sales_person'];?>" name="sales_person"><input type="hidden" class="emp_val" name="sales_person" value="<?php echo $sale[0]['sales_person'];?>--<?php echo $sale[0]['sales_person_id'];?>" />
			<a class="service-down-arrow1 pad-20 right" href="javascript:void(0)" id="openNamesPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a>
			</div>
		</div>
		<div class="info-block">
			<label class="line1-block2 color-89">Comment</label>
			<div class="color-change-cm">
			<input type="text" popup_id="<?php echo COMMENT_POPUP;?>" class="cust-input-field font-color1 tabindex_field comment_input" value="<?php echo $sale[0]['comment'];?>" name="comment" />
			<a class="service-down-arrow1 pad-20 right" href="javascript:void(0)" id="openCommentPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a>
			</div>
		</div>
		<div class="info-block">
			<label class="line1-block2 color-89">Referal Source</label>
			<div class="color-change-rs">
			<input type="text" popup_id="<?php echo REFERAL_POPUP;?>" class="cust-input-field font-color1 tabindex_field referal_input" value="<?php echo $sale[0]['referal_source'];?>" name="referal_source">
			<a class="service-down-arrow1 pad-20 right" href="javascript:void(0)" id="openReferalPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a>
			</div>
		</div>
		<div class="info-block">
			<label class="line1-block2 color-89">Payment Method</label>
			<div class="color-change-pm">
			<?php  if(isset($is_payment_exist)&&!empty($is_payment_exist)) {?>
				<input type="text" readonly class="cust-input-field font-color1 tabindex_field" value="<?php if (isset($sale[0]['payment_method'])&&!empty($sale[0]['payment_method'])) echo $sale[0]['payment_method']; else echo '';?>" name="payment_method" />
			<?php } else {?>
			<input type="text" popup_id="<?php echo PAYMENT_POPUP;?>" class="cust-input-field font-color1 tabindex_field payment_input" value="<?php if (isset($sale[0]['payment_method'])&&!empty($sale[0]['payment_method'])) echo $sale[0]['payment_method']; else echo '';?>" name="payment_method" />
			<?php }?>
			<input type="hidden" class="payment_type" name="payment_type" value="<?php 	if (isset($sale[0]['payment_type'])&&!empty($sale[0]['payment_type'])){
																							echo $sale[0]['payment_type'];
																						} else { 
																							echo '';
																						} ?>" />
																	
			<a class="service-down-arrow1 pad-20 right" href="javascript:void(0)" id="openPaymentPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a>
			</div>
		</div>
		<div id="payment_validation" class="error-desc-payment" style="display:none;"><img style="margin-right:4px;" src='/media/images/tt-new/reset_error.png' />Please select a  payment method</div>
		<input type="hidden" class="details_tab" name="details" value="" />
		<?php if($sale_type != "3") {?>
		<div class="info-block">
			<label class="line1-block2 color-89">Shipping Method</label>
			<div class="color-change-sm">
				<input type="text" popup_id="<?php echo SHIPPING_POPUP;?>" class="cust-input-field font-color1 tabindex_field shipping_input" value="<?php echo $sale[0]['shipping_method'];?>" name="shipping_method" />
				<a class="service-down-arrow1 pad-20 right" href="javascript:void(0)" id="openShippingPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a>
			</div>
		</div>
		<?php } else {?>
				<input type="hidden" name="shipping_method" value="" />
		<?php }?>
	</div>
	
	<!-- Mobile Version Ends -->
	<div class="info-column-2 margin-right-1 margin-top-0" >
		<div class="info-service">
			<label class="amt_line1 color-b8">Subtotal</label>
			<?php if(!isset($sale[0]['subtotal'])|| empty($sale[0]['subtotal'])){ $sale[0]['subtotal']= 0.00 ; } ?>
			<div class="text">
				<div class="amt_line2">
					<input type="text" id="subtotal"  value="<?php if(isset($sale[0]['is_tax_inclusive'])&&$sale[0]['is_tax_inclusive']=='1'){ echo $sale[0]['subtotal'] = number_format((float)$subtotal, 2, '.', ''); } else {echo $sale[0]['subtotal'];}?>"  class="amt_ipt1 right color-4c" max-width="20px" onkeyup='javascript:resize_tag("subtotal", 0);'/>
					<input type="hidden" class="original_subtotal_amount" value="<?php echo $sale[0]['subtotal'];?>" name="subtotal" /> 
					<span id="amt_s1" class="amt_s1 right">$</span>
				</div>
			</div>
		</div>
		<?php  // this we need to check if we need this.
		// call the calculate freight function once again from the original value.
		
		$freight_amount = 0;
		if ($sale[0]['freight_tax']==0 || empty($sale[0]['freight_tax']))
	    {
	    	$freight_chk = 'freight-disable';
			$freight_enab = 'disable';
	    }
	    else
	    {
	        $freight_chk = '';
		    $freight_enab = 'enable';
	    }
		// get original freight + freight tax from sales.
		$freight_amount=$sale[0]['freight'];
		
		if(isset($freight_amount)&&$freight_amount!=null){
			// check if tax is inclusive.
			if(isset($sale[0]['is_tax_inclusive'])&&$sale[0]['is_tax_inclusive']=='1'){
				$freight_amount += ($freight_amount*$sale[0]['freight_tax_percentage']/100);
				$freight_amount	= number_format((float)$freight_amount, 2, '.', '');
			} else {
				// do nothing
			}
			if($sale[0]['freight_tax'] == '1'){
				$total_tax+=($sale[0]['freight']*$sale[0]['freight_tax_percentage']/100);
			}
		}
		$total_tax	= number_format((float)$total_tax, 2, '.', '');
		?>
		
		<div class="freight-tax bor-bottom no-pad">
			<label class="amt_line1 color-b8"><?php echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'taxType'); ?></label>
			<div class="amt_line2">
			<input type="text" name="freight" id="freight" value="<?php echo $freight_amount; ?>" class="freight number color-be right amt_ipt1 tabindex_field"
						amount_changed=0
						freight_orginal_amt=<?php echo $sale[0]['freight']; ?>
						onkeyup='set_org_freight_amt($(this));javascript:resize_tag("freight", 0);' 
						onfocus='javascript:toggle_object("amt_s2", 1); if(this.value=="0.00") {this.value=""}' 
						onblur='javascript:toggle_object("amt_s2", 0);$(this).attr("amount_changed", 1); calculate_sub_total();'
				/>
			<input type="hidden" id="freight_orginal_amt" name="freight_orginal_amt" value=<?php echo $sale[0]['freight']; ?>>
			<input type="hidden" name="freight_check" id="freight_check" value="<?php echo $sale[0]['freight_tax']; ?>" />
				<span id="amt_s2" class="amt_s1 right color-be">$</span>
			</div>
			<?php if(isset($country) && $country == USA) { // freight for US ?>
				<a href="javascript:void(0);" class="amt_side1 freight-button <?php echo $freight_chk;?>"><img src="/media/images/tt-new/<?php echo $freight_enab;?>.png" class="freight-enable-disable tabindex_field"></a>
			<?php } else { // freight for non-US ?>
				<span class="tax_applied_freight"><?php echo $sale[0]['freight_tax_code']; ?></span>
				<a class="service-down-arrow amt_side2 openTaxPopup1" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;&nbsp;</a>
			<?php }?>
			<input class="tax_applied_percentage_freight" type="hidden" value='<?php echo $sale[0]['freight_tax_percentage']; ?>' />
			<input class="freight_tax_record_id" id='freight_tax_record_id' name='freight_tax_record_id' type="hidden" value='<?php echo $sale[0]['freight_tax_record_id']; ?>' />					
		</div>
		
		
		<?php if(isset($country)&& $country != USA) { // for non-US ?>
			<div class="info-service-tax1 box1">
				<label class="amt_line1 color-b8"><?php if(isset($country)&&$country == UK) echo 'VAT Inclusive'; else echo 'Tax Inclusive'; ?></label>
				<div class="amt_line2 no-pad">
					<select style="display:none;" id="select_tax_inclusive">
                    	<option value="1" <?php if(isset($sale[0]['is_tax_inclusive'])&&$sale[0]['is_tax_inclusive']=='1') { echo "selected='selected'"; } ?>>on</option>
                    	<option value="0" <?php if(isset($sale[0]['is_tax_inclusive'])&&$sale[0]['is_tax_inclusive']=='0') { echo "selected='selected'";} ?> >off</option>
                    </select>	
					<input type="hidden" value="<?php if(isset($sale[0]['is_tax_inclusive'])&&$sale[0]['is_tax_inclusive']=='1') { echo '1'; } else {echo '0';} ?>" id="is_tax_inclusive" name="is_tax_inclusive" />
				</div>											
			</div>
			<div class="info-service-tax box1">
				<label class="amt_line1 color-b8"><?php echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'taxName'); ?></label>
				<div class="amt_line2">
					<span class="amt_ipt1 color-be tabindex_field tax_input number" name="tax_totl" id="tax_totl">$&nbsp;<?php echo $total_tax; ?></span>
				</div>			
			</div>
			<input type="hidden" id='tax_total_amount' name='tax_total_amount'></input>					
		<?php } else {  // for US?>
			<div class="info-service-tax box1">
				<label class="amt_line1 color-b8">Tax</label>
				<div class="amt_line2 pad-2">
					<span class="amt_ipt1 color-be tabindex_field tax_input number" name="tax_totl" id="tax_totl">$&nbsp;<?php echo $total_tax."&nbsp;&nbsp;".$sale_item[0]['percentage']."%&nbsp;&nbsp;".$sale_item[0]['tax_code'];?></span> 
					<input type="hidden" name="tax_percentage" id="tax_percentage" value="<?php echo $sale_item[0]['percentage']; ?>"/>
					<input type="hidden" name="tax_code" id="tax_code" value="<?php echo $sale_item[0]['tax_code']; ?>"/>
					<input type="hidden" id="is_tax_inclusive" name="is_tax_inclusive" value="<?php if(isset($sale[0]['is_tax_inclusive'])) { echo $sale[0]['is_tax_inclusive']; }?>"/>
				</div>
				<a class="service-down-arrow amt_side2 openTaxPopup1" id="openTaxPopup" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;&nbsp;</a>					
			</div>
			<input type="hidden" id='tax_total_amount' name='tax_total_amount'></input>
		<?php } ?>
		<div class="info-paid-today mar">
			<label class="amt_line1 color-b8">Total Amount</label>
			<div class="amt_line2">
			<input type="text" name="total_payment" id="total_payment" value="<?php if(isset($sale[0]['total_payment']) && !empty($sale[0]['total_payment'])){echo $sale[0]['total_payment'];}else {echo "0.00";}?>" class="amt_ipt1 right color-4c" />
			<span id="amt_s4" class="amt_s1 right">$</span>					 
			</div>
		</div>
		
		<?php if($sale_type != "3") {?>
		<div class="info no-pad height-32 change_background">
			<label class="amt_line1 color-b8">Paid Today</label>
			<div class="amt_line2">
				<input type="text" name="paid_today" value="<?php echo $sale[0]['paid_today'];?>" id="paid_today" class="tabindex_field amt_ipt1 paid_today right number" 
					onkeyup='javascript:resize_tag("paid_today", 0);'
					onfocus='javascript:toggle_object("amt_s5", 1); hide_popup();if(this.value=="0.00") {this.value=""}' 
					onblur='javascript:toggle_object("amt_s5", 0); if(this.value!="0.00") {this.value=Number(this.value).toFixed(2);} resize_tag("paid_today", 0);'
					<?php if(isset($is_payment_exist)&&!empty($is_payment_exist)){ echo 'readonly'; }?> 
				/>    					
				<span id="amt_s5" class="amt_s1 right" style="line-height:22px;">$</span>
			</div>
			<?php if(isset($is_payment_exist)&&!empty($is_payment_exist)){ ?>
			<img class="payment_checked" src="/media/images/tt-new/enable.png" />
			<?php } 
			if(isset($ccp) && ($ccp == 0 || ($ccp==1 && isset($ach_status)&&$ach_status)) || isset($is_payment_exist)&&!empty($is_payment_exist)){?>
				<input type="button" id="details-button-item" class="details-button check-payment tabindex_field" value="<?php if(isset($is_payment_exist)&&!empty($is_payment_exist)){echo 'History'; } else { echo 'Details'; } ?>" />
				<input type="hidden" id="ccp" value="<?php echo $ccp; ?>" />
			<?php }?>
		</div>
		<?php } else {?>
		<input type="hidden" name="paid_today" value="" id="paid_today"  class="address-input-field font-color1" />
		<?php }?>
		<div class="double_border">
			<div class="info">
				<label class="amt_line1 color-7f only_balance">Balance Due:</label>

				<div class="amt_line2" >
					<input type="text" name="balance_due" value="<?php if(isset($sale[0]['balance']) && !empty($sale[0]['balance'])){echo $sale[0]['balance'];}else {echo "0.00";}?>" id="balance_due"class="amt_ipt1 right" 
					/>
					<span id="amt_s6" class="amt_s1 right amt_s2">$</span>
				</div>
			</div>
		</div>
		<?php if($sync_status == 0){?>
		<div class="edit-delete-buttons mar-top-20">
		<a href="<?php echo SITEURL?>/sales/"><div class="service-save-button edit-label-btn">Cancel</div></a>
		<input type="button" class="service-cancel-button" id="save" name="save" value="Save" onclick="submit_item_invoice_form(event, 0);" />
		</div>
		<?php }?>
	</div>
	<input type="hidden" value="<?php echo count($sale_item);?>" id="total_items" name="total_items" />
	<input type="hidden" value="<?php echo $sale[0]['id'];?>" name="sale_id" id='sale_id'/>
	<input type="hidden" value="1" name="edit_sale" />
	<input type="hidden" value="<?php echo $sale_type;?>" id="sale_type" name="sale_type" />
</form>
</div>		
	
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
						}?>
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
				<?php } ?>
			</div>
		</div>
	</div>
	<!-- Ends Taxes Pop Up Here -->
	<div class="ending-blue-line"></div>
<script type="text/javascript">
$(document).ready(function()
{
	if($(window).width() <= 400) 
	{
		$('.info-column-1').css("width","98%");//42
		$('.info-column-2').css("width","98%");//42		
		$('.sales-phone-version').show();
		$('.item-list').hide();
	}
	else if (($(window).width() > 400) && ($(window).width() <= 750))
	{
		$('.info-column-1').css("width","98%");//42
		$('.info-column-2').css("width","98%");//42		
		$('.sales-phone-version').show();
		$('.item-list').hide();
	}
	else if (($(window).width() > 750) && ($(window).width() < 1000))
	{
		$('.info-column-1').css("width","42%");//42
		$('.info-column-2').css("width","42%");//42		
		$('.sales-phone-version').hide();
		$('.item-list').show();
	}
	else  if (($(window).width() > 1000) && ($(window).width() < 1050))
	{
		$('.info-column-1').css("width","40%");//42
		$('.info-column-2').css("width","40%");//42		
		$('.sales-phone-version').hide();
		$('.item-list').show();
	}
	else if (($(window).width() > 1050) && ($(window).width() < 1300))
	{
		$('.info-column-1').css("width","40%");//42
		$('.info-column-2').css("width","40%");//42		
		$('.sales-phone-version').hide();
		$('.item-list').show();
	}
	else
	{
		$('.info-column-1').css("width","40%");//42
		$('.info-column-2').css("width","40%");//42		
		$('.sales-phone-version').hide();
		$('.item-list').show();
	}
	$(window).resize(function()
	{
		if($(window).width() <= 400) 
		{
			$('.info-column-1').css("width","98%");//42
			$('.info-column-2').css("width","98%");//42		
			$('.sales-phone-version').show();
			$('.item-list').hide();
		}
		else if (($(window).width() > 400) && ($(window).width() <= 750))
		{
			$('.info-column-1').css("width","98%");//42
			$('.info-column-2').css("width","98%");//42		
			$('.sales-phone-version').show();
			$('.item-list').hide();
		}
		else if (($(window).width() > 750) && ($(window).width() < 1000))
		{
			$('.info-column-1').css("width","42%");//42
			$('.info-column-2').css("width","42%");//42		
			$('.sales-phone-version').hide();
			$('.item-list').show();
		}
		else  if (($(window).width() > 1000) && ($(window).width() < 1050))
		{
			$('.info-column-1').css("width","40%");//42
			$('.info-column-2').css("width","40%");//42
			$('.sales-phone-version').hide();
			$('.item-list').show();
		}
		else if (($(window).width() > 1050) && ($(window).width() < 1300))
		{
			$('.info-column-1').css("width","40%");//42
			$('.info-column-2').css("width","40%");//42
			$('.sales-phone-version').hide();
			$('.item-list').show();
		}
		else
		{
			$('.info-column-1').css("width","40%");//42
			$('.info-column-2').css("width","40%");//42		
			$('.sales-phone-version').hide();
			$('.item-list').show();
		}
	});
});
</script>
	
<script type="text/javascript">
$(document).ready(function(){
	$('.toggle-customer-details').hide();
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('.error-popup').hide();
});
</script>

<?php
if(isset($error_msg)){?>
<script type="text/javascript">
	$(document).ready(function(){
		var error_msg = '<?php echo $error_msg; ?>';
		alert(error_msg);
	}
</script>
<?php		
} ?>