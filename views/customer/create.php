<link href="/media/css/sales.css" rel="stylesheet">
<?php echo $header;?>
<?php if( isset($country_code) && ($country_code == AUSTRALIA || $country_code == NZ || $country_code == UK)){?>
<link href="/media/css/jquery.switch.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/media/scripts/jquery.switch.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('select#select_tax_inclusive').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
		 if(type == "off") {
			$(".use_customer_tax_code").val("0");
        } else {
        	$(".use_customer_tax_code").val("1");
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
?>
<div class="row-fluid" id="row-fluid" onclick="" >
	<form method="post" action="" id="create_customer">
	<?php if(isset($error)) {?>
	<div>
		<label class="error_message normal-font"><?php echo $error?></label>
	</div>
	<?php } ?>
	<?php 
	$is_individual_text = 'Individual';
	if($is_individual==1){
		$is_individual_text = 'Individual';
	}else{
		$is_individual_text = 'Company';
		$firstname			= '';
	}
	?>
	<div class="cash-cc-btns individual_dropdown">
		<label class="individual_text individual_dropdown"><?php echo $is_individual_text; ?></label>
		<a class="Individualarrow right individual_dropdown" id="" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;&nbsp;</a>
		<div class="clear"></div>
		<div class="individual_company individual" >Individual</div>
		<div class="individual_company company" >Company</div>
		<input type="hidden" value="<?php echo $is_individual;?>" id="is_individual" name="is_individual" />
	</div>
	<div class="info-column-2 customer-column-1 left margin-left-1" style="margin-top:1%;" >
		<div class="names border-box">
			<div class="info-service-customer bor-bottom-cust white" id="lname" <?php if($is_individual==0){?>style='border-radius:6px 6px 6px 6px !important;'<?php }?>>
				<?php if($is_individual == "0") {?>
						<label class="line1-company-name mar-customer font-light">Company Name</label>
				<?php } else {?>
						<label class="line1-company-name mar-customer font-light">Last Name</label>
				<?php }?>
				<div class="payment-create-sales white">
				<input type="text" tabindex="2" class="customer_street white left font-color1" value="<?php echo $lastname;?>" name="lastname" 
				id="last_name"/>
				</div>
			</div>
			<div class="info-service-customer bor-bot-none white" id="fname" style="<?php if(isset($is_individual) && $is_individual==0) {echo 'display:none;';}?>" >
				<label class="line1-company-name mar-customer font-light">First Name</label>
				<div class="payment-create-sales white">
				<input type="text" tabindex="1" class="customer_street white left font-color1" value="<?php echo $firstname;?>" name="firstname" 
				id="first_name"/>
				</div>
			</div>
			<div class="error-desc-name" style="display:none">
			</div>
		</div>
		

		<div class="info-service-customer box-cust white bor-bottom-cust">
			<label class="line1-company-name mar-customer font-light">Street 1</label>
			<div class="payment-create-sales white">
			<input type="text" tabindex="3" class="customer_street white left font-color1" value="<?php echo $street1;?>" name="street1" />
			</div>
		</div>
		<div class="info-service-customer box2 white bor-bottom-cust">
			<label class="line1-company-name mar-customer font-light">Street 2</label>
			<div class="payment-create-sales white">
			<input type="text" tabindex="4" class="customer_street white left font-color1" value="<?php echo $street2;?>" name="street2" />
			</div>
		</div>
		<div class="clear"></div>
		<div class="info-service-customer mobile_height box2 white bor-bottom-cust">
			<div class="outer_pack width-49 left bor-right white">
			<label class="line1-company-name mar-customer exp-mon-lbl font-light">City</label>
			<div class="customer_city white">
				<input type="text" tabindex="5" class="left white font-color1" value="<?php echo $city;?>" name="city" />
			</div>
			</div>
			<div class="outer_pack width-49 left white">
			<label class="line1-exp-year-1 mar-customer-year state font-light">State/Prov.</label>
			<div class="customer_state white no-border">
			<input type="text" tabindex="6" class="left white font-color1" value="<?php echo $state;?>" name="state" />
			</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="info-service-customer mobile_height box2 white bor-bottom-cust country_relative">
			<div class="outer_pack width-49 left zip bor-right white">
			<label class="line1-company-name mar-customer exp-mon-lbl zip-code font-light">Zip/Postal Code</label>
			<div class="customer_city white">
				<input type="text" tabindex="7" class="left white font-color1" value="<?php echo $zipcode;?>" name="zipcode" />
			</div>
			</div>
			<div class="outer_pack width-49 left white">
				<label class="line1-exp-year-1 mar-customer-year font-light">Country</label>
				<div class="customer_state white no-border">
					<input type="text" readonly tabindex="8" name="country" class="country-input white font-color1" value="<?php if(isset($country_name)) echo $country_name; else '';?>" /> 
				</div>
				 <a class="service-down-arrow1 right mar-customer-arrow" id="openCountryPopup" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;&nbsp;</a>
			</div>
		</div>
		<div class="clear"></div>
		<div class="info-service-customer box2 white bor-bottom-cust">
			<label class="line1-company-name mar-customer font-light">Email</label>
			<div class="payment-create-sales white">
			<input type="text" tabindex="9" class="customer_street white left font-color1" id="email-id" value="<?php echo $email;?>" name="email" />
			</div>
		</div>
			<div class="error-desc-mail" style="display:none" id="email-error">
			</div>
	
		<input type="hidden" name="type" value="Customer" />
		<input type="hidden" name="taxcode" value="Customer" />
			
		<div class="clear"></div>
		
		<div class="info-service-customer box2 white bor-bottom-cust">
			<label class="line1-company-name mar-customer font-light">Contact Name</label>
			<div class="payment-create-sales white">
			<input type="text" tabindex="10" class="customer_street white left font-color1" value="<?php echo $contact;?>" name="contact" />
			</div>
		</div>
		
		<div class="info-service-customer box3 white">
			<label class="line1-company-name mar-customer payment-token-lbl font-light">Phone Number</label>
			<div class="payment-create-sales white">
			<input type="text" tabindex="11" class="customer_street left white font-color1" value="<?php echo $phone;?>" name="phone" />
			</div>
		</div>
		<div class="clear"></div>
		<div class="info-block-details mar-top-20">
			<label class="font-dark pad-left-4">Selling Details</label>
		</div>
		
		<div class="info-column-selling-details margin-left-1 margin-ipad">
		<div class="info-block">
			<label class="line1-block2 font-dark">Credit Limit</label>
			<div class="color-change-none">
			<input type="text" tabindex="12" class="cust-input-field1 font-color1" id="" value="<?php echo $credit_limit;?>" name="credit_limit"/>
			</div>
		</div>

		<div class="info-block">
			<label class="line1-block2 font-dark">Income Account</label>
			<div class="color-change-pm width-55">
			<input type="text" readonly tabindex="13" popup_id="<?php echo ACCOUNT_POPUP;?>" name="income_account" class="cust-input-field income_account font-color1 payment_input mar-left-0" value="<?php if(isset($income_account)) echo $income_account; else echo '';?>" /> 
			<a class="service-down-arrow1 pad-20 right" href="javascript:void(0)" id="openIncomeAccountPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a>
			</div>
		</div>
		
		<div class="info-block">
			<label class="line1-block2 font-dark">Salesperson</label>
			<div class="color-change-sp width-55">
			<input type="text" readonly tabindex="14" popup_id="<?php echo SALESPERSON_POPUP;?>" class="cust-input-field font-color1 sales_person_input mar-left-0" id="" value="<?php if(isset($salesperson)) echo $salesperson; else echo '';?>" >
			<input type="hidden" class="emp_val" value="<?php if(isset($salesperson) && isset($salesperson_id)) echo $salesperson.'--'.$salesperson_id;?>" name="sales_person"/>
			<a class="service-down-arrow1 pad-20 right" href="javascript:void(0)" id="openNamesPopup">&nbsp;&nbsp;&nbsp;&nbsp;</a>
			</div>
		</div>
		
		<div class="info-block">
			<label class="line1-block2 font-dark">Billing</label>
			<div class="color-change-none">
				<input type="text" tabindex="15" class="cust-input-field1 font-color1" value="<?php echo $billing_rate;?>" name="billing_rate" />
			</div>
		</div>
		</div>
	</div>

	<div class="info-column-2 customer-column-1 margin-right-1 rig" style="margin-top:1%;" >
		<div class="info-block-details mar-top-2">
			<label class="font-dark pad-left-4"><?php if(isset($country_code)&&$country_code == UK) echo "VAT Details"; else echo "Tax Details"; ?></label>
		</div>
		<div class="names border-box mar-top-12">
		<?php if( isset($country_code) && ($country_code == AUSTRALIA || $country_code == NZ || $country_code == UK)){?>
			<div class="info-service-customer bor-radius bor-bottom white" id="" >
				<label class="line1-use-tax mar-customer font-light"><?php if(isset($country_code)&&$country_code == UK) echo "Use Customer's VAT Code"; else echo "Use Customer's Tax Code"; ?></label>
				<select style='display:none;' id='select_tax_inclusive'>
					<option value="1" <?php if(isset($use_customer_tax_code) && $use_customer_tax_code == 1) { echo "selected='selected'"; } ?>>on</option>
                    <option value="0" <?php if(isset($use_customer_tax_code) && $use_customer_tax_code == 0) { echo "selected='selected'";} ?> >off</option>
                </select>
			</div>
		<?php }?>
			
			<input type='hidden' value="<?php echo $use_customer_tax_code; ?>" class='use_customer_tax_code' name='use_customer_tax_code' /> 
			<div class="info-service-customer <?php if( isset($country_code) && ($country_code == USA))  echo 'bord-radius'; else 'bor-bottom'; ?> white" id="" >
				<label class="line1-use-tax mar-customer font-light"><?php if(isset($country_code)&&$country_code == UK) echo "VAT Code"; else echo "Tax Code"; ?></label>
				<input type="hidden" name="tax_record_id" value="<?php if(isset($tax_record_id) && !empty($tax_record_id)) echo $tax_record_id; ?>" class="tax_record_id" />
				<a class="service-down-arrow-customer amt_side3 openTaxPopup1" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;&nbsp;</a>
				<span class="left customer_taxes_add tax_code"><?php if(isset($customer_tax_code) && !empty($customer_tax_code)) echo $customer_tax_code; ?></span>
			</div>
			<?php if( isset($country_code) && ($country_code == AUSTRALIA || $country_code == CANADA || $country_code == NZ || $country_code == UK)){?>
			<div class="info-service-customer bor-radius1 bor-top white" id="" >
				<label class="line1-use-tax mar-customer font-light"><?php if(isset($country_code)&&$country_code == UK) echo "Carriage VAT Code"; else echo "Freight Tax Code"; ?></label>
				<input type="hidden" name="freight_tax_record_id" value="<?php if(isset($freight_tax_record_id) && !empty($freight_tax_record_id)) echo $freight_tax_record_id; ?>" class="freight_tax_record_id" />
				<a class="service-down-arrow-customer amt_side3 openTaxPopup1" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;&nbsp;</a>
				<span class="left customer_taxes_add freight_tax_code"><?php if(isset($freight_tax_code) && !empty($freight_tax_code)) echo $freight_tax_code; ?></span>
			</div>
			<?php } ?>
		</div>
		<div class="clear"></div>
		<div class="info-block-details mar-top-2">
			<label class="font-dark pad-left-4">Card Details</label>
		</div>
		<div class="info-column-selling-details margin-left-1 margin-ipad">
			<div class="info-block">
				<label class="line1-block2 font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_list1'])) echo $custom_names[0]['custom_list1']; else "Custom List1";?></label>
				<div class="change-color-custom1">
				<input type="text" tabindex="16" class="cust-input-field first-field font-color1 left no-margin-left" id="" value="<?php echo $custom_list1;?>" name="custom_list1" />
				<a id="opencustomPopup1" href="javascript:void(0)" class="service-down-arrow1 pad-20 right">&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
			<div class="info-block">
				<label class="line1-block2 font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_list2'])) echo $custom_names[0]['custom_list2']; else "Custom List2";?></label>
				<div class="change-color-custom2">
				<input type="text" tabindex="17" class="cust-input-field second-field font-color1 left no-margin-left" value="<?php echo $custom_list2;?>" name="custom_list2" />
				<a id="opencustomPopup2" href="javascript:void(0)" class="service-down-arrow1 pad-20 right">&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
			<div class="info-block">
				<label class="line1-block2 font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_list3'])) echo $custom_names[0]['custom_list3']; else "Custom List3";?></label>
				<div class="change-color-custom3">
				<input type="text" tabindex="18" class="cust-input-field third-field font-color1 left no-margin-left" value="<?php echo $custom_list3;?>" name="custom_list3" />
				<a id="opencustomPopup3" href="javascript:void(0)" class="service-down-arrow1 pad-20 right">&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
			<div class="info-block">
				<label class="line1-block2 font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_field1'])) echo $custom_names[0]['custom_field1']; else "Custom Field1";?></label>
				<div class="change-color-custom4 custom">
				<input type="text" tabindex="19" class="cust-input-field font-color1 left no-margin-left" value="<?php echo $custom_field1;?>" name="custom_field1" />
				</div>
			</div>
			<div class="info-block">
				<label class="line1-block2 font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_field2'])) echo $custom_names[0]['custom_field2']; else "Custom Field2";?></label>
				<div class="change-color-custom4 custom">
				<input type="text" tabindex="20" class="cust-input-field1 font-color1 left" value="<?php echo $custom_field2;?>" name="custom_field2" />
				</div>
			</div>
			<div class="info-block">
				<label class="line1-block2 font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_field3'])) echo $custom_names[0]['custom_field3']; else "Custom Field3";?></label>
				<div class="change-color-custom4 custom">
					<input type="text" tabindex="21" class="cust-input-field1 left font-color1 shipping_input" value="<?php echo $custom_field3;?>" name="custom_field3" />
				</div>
			</div>
			<div class="clear"></div>
			<div class="info-block cancel-save no-bor-bottom">
				<div class=""><input tabindex="22" type="submit" class="submit-save-button" value="Save" name="submit" onclick="return validate_names();"></div>
				<a href="<?php echo SITEURL?>/customer">
					<div tabindex="21" class="customer-cancel-button">
						<label class="edit-label-btn">Cancel</label>
					</div>
				</a>
			</div>
		</div>
	</div>
	<?php if(isset($customer_id)) {?>
			<input type="hidden" value="<?php echo $customer_id;?>" name="customer_id" />
   	<?php }?>
	</form>
	
   	
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
				<?php 
				} 
			?>
		</div>
		
		<?php if(isset($consolidated_data)){ // NOt using right now, but it will be reference for creating consolidated TAXes UI?>
		<div class="popup-subitems1-taxes" style="display:none;">
			<!--  consolidated header -->
			<?php foreach($consolidated_data as $t) { ?>
					<div class="tax-popup-list first tax-list-row" >
						<label class="names-popup-list-label heavy">
							<?php echo $t['tax_code'].' '.$t['description'];?><span style="float:right;"><?php echo $t['percentage'].'%';?></span>
							<input type="hidden" name="taxcode[]" class="taxcode" value="<?php echo $t['tax_code'];?>"/>
							<input type="hidden" name="taxdescription[]" class="taxdescription" value="<?php echo $t['description'];?>"/>
							<input type="hidden" name="taxpercentage[]" class="taxpercentage" value="<?php echo $t['percentage'];?>"/>
							<input type="hidden" name="taxid[]" class="taxid" value="<?php echo $t['id'];?>"/>
							<input type="hidden" name="tax_record_id[]" class="tax_record_id" value="<?php echo $t['tax_record_id'];?>"/>
						</label>
					</div>
			<?php 
			} ?>
		</div>
		<?php 
		} ?>
		</div>
	</div>
</div>
	
<script type="text/javascript">
$(document).ready(function()
{
	if($('#is_individual').val() == 0){
		$("#fname").hide();
	}
});
</script>
			
<script>
$(document).ready(function()
{
	$('a.ui-switch-on').html('Yes');
	$('a.ui-switch-off').html('No');
	if($(window).width() < 400) 
	{
		$('.info').css("padding-bottom","12px");//2			
		$('.name-customer').css("height","136px");
		
		$('.address-input-field').css('cssText','width:50%');//56
		$('.add-new-card-btn').css("width","108px");//120
		
		$('.update-customer-label').css('margin-top','-8px');-1
		$('.update-customer-label').css('width','64%');//72
		
		$('.line1-exp-year').css('width','23%');//18
		$('.state').text("State / Province");
		$('.zip-code').text("Zip Code");
		
		$('.payment-with-card-lbl').css('width','40%');//30
		
		$('.rig').css('float','left');
		$('.rig').css('margin-left','1%');
	}
	else if (($(window).width() > 400) && ($(window).width() < 600))
	{
		$('.info').css("padding-bottom","12px");//2		
		$('.name-customer').css("height","96px");	
	
	
		$('.address-input-field').css('cssText','width:50%');//56
		$('.add-new-card-btn').css("width","108px");//120
		
		$('.update-customer-label').css('margin-top','-8px');-1
		$('.update-customer-label').css('width','64%');//72
		
		$('.line1-exp-year').css('width','23%');//18
		$('.state').text("State/Prov.");
		$('.zip-code').text("Zip Code");
		
		$('.payment-with-card-lbl').css('width','40%');//30
	
		$('.rig').css('float','left');
		$('.rig').css('margin-left','1%');
	}
	else if (($(window).width() > 600) && ($(window).width() < 1000))
	{
		$('.info').css("padding-bottom","12px");//2	
		$('.name-customer').css("height","80px");
		
		
		$('.address-input-field').css('cssText','width:56%');//56
		$('.add-new-card-btn').css("width","120px");//120
		
		$('.update-customer-label').css('margin-top','-8px');//-1
		$('.update-customer-label').css('width','72%');//72
		
		$('.line1-exp-year').css('width','18%');//18
		$('.state').text("State/Prov.");
		$('.zip-code').text("Zip Code");
		
		$('.payment-with-card-lbl').css('width','30%');//30
		$('.rig').css('float','right');
		$('.rig').css('margin-left','1%');
	}
	else
	{
		$('.info').css("padding-bottom","6px");//2	
		$('.name-customer').css("height","74px");
	
		$('.address-input-field').css('cssText','width:56%');//56
		$('.add-new-card-btn').css("width","120px");//120
		
		$('.update-customer-label').css('margin-top','-1px');-1
		$('.update-customer-label').css('width','72%');//72
		
		
		
		$('.line1-exp-year').css('width','18%');//18
		$('.state').text("State/Prov.");
		$('.zip-code').text("Zip/Postal Code");
		
		$('.payment-with-card-lbl').css('width','30%');//30
		$('.rig').css('float','right');
		$('.rig').css('margin-left','1%');
	}
	$(window).resize(function()
	{
		if($(window).width() < 400) 
		{
			$('.info').css("padding-bottom","12px");//2			
			$('.name-customer').css("height","136px");
			
			$('.address-input-field').css('cssText','width:50%');//56
			$('.add-new-card-btn').css("width","108px");//120
		
			$('.update-customer-label').css('margin-top','-8px');-1
			$('.update-customer-label').css('width','64%');//72
			
			
			$('.line1-exp-year').css('width','23%');//18
			$('.state').text("State/Prov.");
			$('.zip-code').text("Zip Code");
		
			$('.payment-with-card-lbl').css('width','40%');//30
			
		
			$('.rig').css('float','left');
			$('.rig').css('margin-left','1%');
		}
		else if (($(window).width() > 400) && ($(window).width() < 600))
		{	
			$('.info').css("padding-bottom","12px");//2		
			$('.name-customer').css("height","96px");	
	
	
			$('.address-input-field').css('cssText','width:50%');//56
			$('.add-new-card-btn').css("width","108px");//120
		
			$('.update-customer-label').css('margin-top','-8px');-1
			$('.update-customer-label').css('width','64%');//72
			
				
			$('.line1-exp-year').css('width','23%');//18
			$('.state').text("State/Prov.");
			$('.zip-code').text("Zip Code");
		
			$('.payment-with-card-lbl').css('width','40%');//30
	
			$('.rig').css('float','left');
			$('.rig').css('margin-left','1%');
		}
		else if (($(window).width() > 600) && ($(window).width() < 1000))
		{
			$('.info').css("padding-bottom","12px");//2	
			$('.name-customer').css("height","80px");
		
		
			$('.address-input-field').css('cssText','width:56%');//56
			$('.add-new-card-btn').css("width","120px");//120
		
			$('.update-customer-label').css('margin-top','-8px');//-1
			$('.update-customer-label').css('width','72%');//72
			
			
			$('.line1-exp-year').css('width','18%');//18
			$('.state').text("State/Prov.");
			$('.zip-code').text("Zip Code");
		
			$('.payment-with-card-lbl').css('width','30%');//30
			$('.rig').css('float','right');
			$('.rig').css('margin-left','1%');
		}
		else
		{
			$('.info').css("padding-bottom","6px");//2	
			$('.name-customer').css("height","74px");
			
			$('.address-input-field').css('cssText','width:56%');//56
			$('.add-new-card-btn').css("width","120px");//120
		
			$('.update-customer-label').css('margin-top','-1px');-1
			$('.update-customer-label').css('width','72%');//72
			
			$('.line1-exp-year').css('width','18%');//18
			$('.state').text("State/Prov.");
			$('.zip-code').text("Zip/Postal Code");
		
			$('.payment-with-card-lbl').css('width','30%');//30
			$('.add-new-card-button').css('width','47%');//41
			$('.rig').css('float','right');
			$('.rig').css('margin-left','1%');
		}
	});
}); 

function validate_names() {
	var flag = 0;
	$('.error-desc-name').text("");
	$('#email-error').text('');
	if($('#is_individual').val() == 1){
		if($('#first_name').val()==""){
			flag = 1;
    		$('.error-desc-name').show();
    		$('.error-desc-name').val("");
    		$('.error-desc-name').text("Please Enter firstname.");
    		$('.error-desc-name').append("<br>");
    		
		} else {
			$('.error-desc').hide();
  		}
	}
	if($('#last_name').val()==""){
		flag=1;
		$('.error-desc-name').show();
		$('.error-desc-name').val("");
		if($('#is_individual').val() == 0)
	 		$('.error-desc-name').append("Please enter Companyname.");
		else
			$('.error-desc-name').append("Please enter lastname.");
	} else {
		if(flag==0)
			$('.error-desc-name').hide();
	}
	if($("#email-id").val()!="") {
		if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#email-id').val())){
			$('#email-error').hide();
			// no flag reset
		} 
		else {
			flag=1;
			$('#email-error').show();
			$('#email-error').val('');
			$('#email-error').text("Please enter a valid Email");
		}
	}
	if(flag==1){
		return false;
	} else {
		return true;
	}
}
</script>
<style>
.ui-switch{
	float:right !important;
}
.ui-switch-on,.ui-switch-off{
	font-family:HelveticaNeueMedium !important;
}
</style>