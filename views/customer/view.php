<?php if( isset($country_code) && ($country_code == AUSTRALIA || $country_code == CANADA || $country_code == NZ || $country_code == UK)){?>
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
    });
    $('.ui-switch').css("top", "0px");
    $('.amt_line2').css("top-padding", "0px !important");
});
</script>
<?php }?>
 <link href="/media/css/sales.css" rel="stylesheet">
		<?php echo $header;?>
		<div class="row-fluid" id="row-fluid" >
			<div class="customer-name-type">
				<?php $type_customer = $customer[0]['is_individual_card'];
					if($type_customer == 0){
						$type="Company";
						$type_name=$customer[0]['company_or_lastname'];
					}else{
						$type="Individual";
						$type_name=$customer[0]['firstname'] .' '. $customer[0]['company_or_lastname'];
					}
				?>
					<div class="width-240 left mar-left-8"><label class="cust-name-label left-align pad-top-9" ><?php echo $type_name;?></label></div>
					<div class="right mar-rig-10"><label class="cust-type-label right-align pad-top-9" ><?php echo $type;?></label></div>
			</div>
			<div class="info-column-2 customer-column-1 left margin-left-1" style="margin-top:4%" >
				<div class="info-service-tax box-cust white bor-bottom-cust">
					<label class="line1 mar-tax font-light">Street 1</label>
					<div class="cust-view-sales white">
						<label class="customer-view-list dark"><?php echo $customer[0]['street1'];?></label>
					</div>
				</div>
				<div class="info-service-tax box2 white bor-bottom-cust">
					<label class="line1 mar-tax font-light">Street 2</label>
					<div class="cust-view-sales white">
					<?php $str2 = $customer[0]['street2'];
					if($str2 == '')
					{
					    $address_street2="- - - -";
					}
					else
					{
					    $address_street2=$str2;
					}
					?>
						<label class="customer-view-list dark"><?php echo $address_street2;?></label>
					</div>
				</div>
				
				<div class="info-service-tax box2 mobile-div white bor-bottom-cust">
					<label class="line1 mar-tax font-light">State</label>
					<div class="cust-view-sales white">
						<label class="customer-view-list dark"><?php echo $customer[0]['state'];?></label>
					</div>
				</div>
				<div class="info-service-tax box2 mobile-div white bor-bottom-cust">
					<label class="line1 mar-tax font-light">Country</label>
					<div class="cust-view-sales white">
						<label class="customer-view-list dark"><?php echo $country;?></label>
					</div>
				</div>
				<div class="info-service-tax box2 mobile-div white bor-bottom-cust">
					<label class="line1 mar-tax font-light">ZipCode</label>
					<div class="cust-view-sales white">
						<label class="customer-view-list dark"><?php echo $customer[0]['zip'];?></label>
					</div>
				</div>
				<div class="info-service-tax box2 normal-div white bor-bottom-cust">
					<label class="line1 mar-tax exp-mon-lbl font-light">City</label>
					<div class="customer-view-sales-exp-month white">
    					<label class="customer-view-list dark"><?php echo $customer[0]['city'];?></label>
    				</div>
					<div class="payment-create-sales-exp-year white">
					<!-- <label class="line1-exp-year-1 mar-tax-year state font-light pad-rig-5">State/Province</label>  -->
						<label class="customer-view-list_state dark"><span class="font-light mar-rig-10">State/Prov.</span><?php echo $customer[0]['state'];?></label>
    				</div>
				</div>
				<div class="info-service-tax box2 normal-div white bor-bottom-cust">
					<label class="line1 mar-tax exp-mon-lbl zip-code font-light">Zip/Postal Code</label>
					<div class="customer-view-sales-exp-month white">
    					<label class="customer-view-list dark"><?php echo $customer[0]['zip'];?></label>
    				 </div>
					<div class="payment-create-sales-exp-year white">
					<!-- <label class="line1-exp-year-1 mar-tax-year font-light pad-rig-5">Country</label>  -->
						<label class="customer-view-list_state dark"><span class="font-light mar-rig-10">Country</span><?php echo $country;?></label>
					</div>
				</div>
				
				<div class="info-service-tax box2 white bor-bottom-cust">
					<label class="line1 mar-tax font-light">Email</label>
					<div class="cust-view-sales white">
						<label class="customer-view-list dark"><?php echo $customer[0]['email'];?></label>
					</div>
				</div>
				
				<div class="info-service-tax box2 white bor-bottom-cust">
					<label class="line1 mar-tax font-light">Contact Name</label>
					<div class="cust-view-sales white">
						<label class="customer-view-list dark"><?php echo $customer[0]['contact'];?></label>
					</div>
				</div>
				
				<div class="info-service-tax box3 white">
					<label class="line1 mar-tax font-light">Phone Number</label>
					<div class="cust-view-sales white">
						<label class="customer-view-list dark"><?php echo $customer[0]['phone'];?></label>
					</div>
				</div>
				
				
				<div class="info-block-details" style="margin-top:30px;">
					<label class="font-dark pad-left-4">Selling Details</label>
				</div>
				
				<div class="info-column-selling-details margin-left-1 margin-ipad">
				<div class="info-block">
					<label class="line1-block font-dark">Credit Limit</label>
					<div class="color-change-cust">
						<label class="customer-view dark"><?php echo $customer[0]['credit_limit'];?></label>
					</div>
				</div>
				<div class="info-block">
					<label class="line1-block font-dark">Income Account</label>
					<div class="color-change-cust">
						<label class="customer-view dark"><?php if(!empty($customer[0]['account']))
																	echo $customer[0]['account'];
																else
																	echo "";?></label>
					</div>
				</div>
				<div class="info-block">
					<label class="line1-block font-dark">Salesperson</label>
					<div class="color-change-cust">
						<label class="customer-view dark"><?php echo $customer[0]['salesperson'];?></label>					
					</div>
				</div>
				<div class="info-block">
					<label class="line1-block font-dark">Billing</label>
					<div class="color-change-cust">
    					<label class="customer-view dark"><?php echo $customer[0]['billing_rate'];?></label>
    				</div>
				</div>
			</div>
		</div>
	
			<div class="info-column-2 customer-column-1 margin-right-1 rig" style="margin-top:4%;">
				<div class="info-block-details mar-top-2">
					<label class="font-dark pad-left-4"><?php if(isset($country_code)&&$country_code == UK) echo "VAT Details"; else echo "Tax Details"; ?></label>
				</div>
				<div class="names border-box mar-top-12">
    				<?php if( isset($country_code) && ($country_code == AUSTRALIA  || $country_code == NZ || $country_code == UK)){?>
    				<div class="info-service-customer bor-radius bor-bottom white" id="" >
    					<label class="line1-use-tax mar-customer font-light"><?php if(isset($country_code)&&$country_code == UK) echo "Use customer's VAT code"; else echo "Use customer's tax code"; ?></label>
    					<select style='display:none;' id='select_tax_inclusive'>
	                    	<option value="1" <?php if(isset($customer[0]['use_customer_tax_code'])&&$customer[0]['use_customer_tax_code']=='1') { echo "selected='selected'"; } ?>>on</option>
	                		<option value="0" <?php if(isset($customer[0]['use_customer_tax_code'])&&$customer[0]['use_customer_tax_code']=='0') { echo "selected='selected'";} ?> >off</option>
	                    </select>	
						<input type='hidden' value='0' class='use_customer_tax_code' name='use_customer_tax_code' />
    				</div>
    				<?php }?>
    		
    				<div class="info-service-customer bord-radius white" id="" >
    					<label class="line1-use-tax mar-customer font-light"><?php if(isset($country_code)&&$country_code == UK) echo "VAT Code"; else echo "Tax Code"; ?></label>
    					<span class="left customer_taxes_add tax_code"><?php echo $customer[0]['tax_code'];?></span>
    				</div>
    				<?php if( isset($country_code) && ($country_code == AUSTRALIA || $country_code == CANADA || $country_code == NZ || $country_code == UK)){?>
    				<div class="info-service-customer bor-radius1 bor-top white" id="" >
    					<label class="line1-use-tax mar-customer font-light"><?php if(isset($country_code)&&$country_code == UK) echo "Carriage VAT Code"; else echo "Freight Tax Code"; ?></label>
    					<span class="left customer_taxes_add freight_tax_code"><?php echo $customer[0]['freight_tax_code'];?></span>
    				</div>
    				<?php }?>
    			</div>
    		
				<div class="clear"></div>
				<div class="info-block-details mar-top-2">
					<label class="font-dark pad-left-4">Card Details</label>
				</div>
				<div class="info-column-selling-details margin-left-1 margin-ipad">
    				<div class="info-block">
    					<label class="line1-block font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_list1'])) echo $custom_names[0]['custom_list1']; else "Custom List1";?></label>
    					<div class="color-change-cust">
    						<label class="customer-view dark"><?php echo $customer[0]['custom_list1'];?></label>
    					</div>
    				</div>
    				<div class="info-block">
    					<label class="line1-block font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_list2'])) echo $custom_names[0]['custom_list2']; else "Custom List2";?></label>
    					<div class="color-change-cust">
    						<label class="customer-view dark"><?php echo $customer[0]['custom_list2'];?></label>
    					</div>
    				</div>
    				<div class="info-block">
    					<label class="line1-block font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_list3'])) echo $custom_names[0]['custom_list3']; else "Custom List3";?></label>
    					<div class="color-change-cust">
    						<label class="customer-view dark"><?php echo $customer[0]['custom_list3'];?></label>
    					</div>
    				</div>
    				<div class="info-block">
    					<label class="line1-block font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_field1'])) echo $custom_names[0]['custom_field1']; else "Custom Field1";?></label>
    					<div class="color-change-cust">
    						<label class="customer-view dark"><?php echo $customer[0]['custom_field1'];?></label>
    					</div>
    				</div>
    				<div class="info-block">
    					<label class="line1-block font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_field2'])) echo $custom_names[0]['custom_field2']; else "Custom Field2";?></label>
    					<div class="color-change-cust">
    						<label class="customer-view dark"><?php echo $customer[0]['custom_field2'];?></label>
    					</div>
    				</div>
    				<div class="info-block">
    					<label class="line1-block font-dark"><?php if(!empty($custom_names) && !empty($custom_names[0]['custom_field3'])) echo $custom_names[0]['custom_field3']; else "Custom Field3";?></label>
    					<div class="color-change-cust">
        					<label class="customer-view dark"><?php echo $customer[0]['custom_field3'];?></label>
        				</div>
    				</div>
    			
    				<div class="edit-delete-buttons-view" id="delete-sale">
				<a href="javascript:void(0);" id="delete-customer" onclick="delete_customer_job_cofirm(this.id, 2, '<?php echo $customer[0]['id']?>', 1);">
					<div class="delete-btn delete-label" style="height:29px;">Delete</div>
				</a>
				<a href="<?php echo SITEURL?>/customer/edit/<?php echo $customer[0]['id']?>">
					<div class="edit-btn1 edit-label">Edit</div>
				</a>
				</div>
				</div>
			</div>
		</div>
	<script type="text/javascript">
		$(document).ready(function() {
			if($(window).width() < 400) {
				$('.info-column-1').css("width","100%");
				$('.payment-methods').css("width","97%");
				$('.info-column-2').css("width","100%");//42		
				$('.info').css("padding-bottom","12px");//2			
				$('.name-customer').css("height","136px");
				$('.line1-company-name').css("width","37%");//30
				$('.line1').css("width","20%");//30
				$('.address-input-field').css('cssText','width:50%');//56
				$('.add-new-card-btn').css("width","108px");//120
				$('.cust-view-sales').css("width","50%");//63
				$('.update-customer-label').css('margin-top','-8px');-1
				$('.update-customer-label').css('width','64%');//72
				$('.customer-view-sales-exp-month').css('width','30%');//17%
				$('.customer-view-sales-exp-month').css('padding-left','1%');//5%
				$('.payment-create-sales-exp-year').css('width','46%');//46%
				$('.payment-input-field-tax-exp').css('width','39%');
				$('.line1-exp-year').css('width','23%');//18
				$('.state').text("State / Province");
				$('.zip-code').text("Zip Code");
				$('.cust-view-sales').css('padding-left','1%');//5%
				$('.payment-with-card-lbl').css('width','40%');//30
				$('.rig').css('float','left');
				$('.rig').css('margin-left','1%');
				$('.mobile-div').show();
				$('.normal-div').hide();
			} else if (($(window).width() > 400) && ($(window).width() < 600)) {
				$('.info-column-1').css("width","97%");//42
				$('.payment-methods').css("width","97%");
				$('.info-column-2').css("width","97%");//42		
				$('.info').css("padding-bottom","12px");//2		
				$('.name-customer').css("height","96px");	
				$('.line1-company-name').css("width","33%");//30
				$('.line1').css("width","33%");//30
				$('.address-input-field').css('cssText','width:50%');//56
				$('.add-new-card-btn').css("width","108px");//120
				$('.cust-view-sales').css("width","50%");//63
				$('.update-customer-label').css('margin-top','-8px');-1
				$('.update-customer-label').css('width','64%');//72
				$('.customer-view-sales-exp-month').css('width','20%');//17%
				$('.customer-view-sales-exp-month').css('padding-left','1%');//5%
				$('.payment-create-sales-exp-year').css('width','43%');//46%
				$('.payment-input-field-tax-exp').css('width','39%');
				$('.line1-exp-year').css('width','23%');//18
				$('.state').text("State/Province");
				$('.zip-code').text("Zip Code");
				$('.cust-view-sales').css('padding-left','1%');//5%
				$('.payment-with-card-lbl').css('width','40%');//30
				$('.rig').css('float','left');
				$('.rig').css('margin-left','1%');		
				$('.mobile-div').show();
				$('.normal-div').hide();
			} else if (($(window).width() > 600) && ($(window).width() < 1000)) {
				$('.info-column-1').css("width","");//42
				$('.payment-methods').css("width","60%");
				$('.info-column-2').css("width","46%");//42		
				$('.info').css("padding-bottom","12px");//2	
				$('.name-customer').css("height","80px");
				$('.line1-company-name').css("width","30%");//30
				$('.line1').css("width","30%");//30
				$('.address-input-field').css('cssText','width:56%');//56
				$('.add-new-card-btn').css("width","120px");//120
				$('.cust-view-sales').css("width","63%");//63
				$('.update-customer-label').css('margin-top','-8px');//-1
				$('.update-customer-label').css('width','72%');//72
				$('.customer-view-sales-exp-month').css('width','17%');//17%
				$('.customer-view-sales-exp-month').css('padding-left','5%');//5%
				$('.payment-create-sales-exp-year').css('width','46%');//46%
				$('.payment-input-field-tax-exp').css('width','52%');//52
				$('.line1-exp-year').css('width','18%');//18
				$('.state').text("State");
				$('.zip-code').text("Zip Code");
				$('.cust-view-sales').css('padding-left','5%');//5%
				$('.payment-with-card-lbl').css('width','30%');//30
				$('.rig').css('float','right');
				$('.rig').css('margin-left','1%');
				$('.mobile-div').hide();
				$('.normal-div').show();
			} else {
				$('.info-column-1').css("width","42%");//42
				$('.payment-methods').css("width","42%");
				$('.info-column-2').css("width","42%");//42		
				$('.info').css("padding-bottom","6px");//2	
				$('.name-customer').css("height","74px");
				$('.line1-company-name').css("width","30%");//30
				$('.line1').css("width","30%");//30
				$('.address-input-field').css('cssText','width:56%');//56
				$('.add-new-card-btn').css("width","120px");//120
				$('.cust-view-sales').css("width","63%");//63
				$('.update-customer-label').css('margin-top','-1px');-1
				$('.update-customer-label').css('width','72%');//72
				$('.customer-view-sales-exp-month').css('width','17%');//17%
				$('.customer-view-sales-exp-month').css('padding-left','2%');//5%
				$('.payment-create-sales-exp-year').css('width','46%');//46%
				$('.payment-input-field-tax-exp').css('width','52%');//52
				$('.line1-exp-year').css('width','18%');//18
				$('.state').text("State/Province");
				$('.zip-code').text("Zip/Postal Code");
				$('.cust-view-sales').css('padding-left','2%');//5%
				$('.payment-with-card-lbl').css('width','30%');//30
				$('.rig').css('float','right');
				$('.rig').css('margin-left','1%');
				$('.mobile-div').hide();
				$('.normal-div').show();
			}
			$(window).resize(function() {
				if($(window).width() < 400) {
					$('.info-column-1').css("width","100%");
					$('.payment-methods').css("width","97%");
					$('.info-column-2').css("width","100%");//42		
					$('.info').css("padding-bottom","12px");//2			
					$('.name-customer').css("height","136px");
					$('.line1-company-name').css("width","37%");//30
					$('.line1').css("width","20%");//30
					$('.address-input-field').css('cssText','width:50%');//56
					$('.add-new-card-btn').css("width","108px");//120
					$('.cust-view-sales').css("width","50%");//63
					$('.update-customer-label').css('margin-top','-8px');-1
					$('.update-customer-label').css('width','64%');//72
					$('.customer-view-sales-exp-month').css('width','30%');//17%
					$('.customer-view-sales-exp-month').css('padding-left','1%');//5%
					$('.payment-create-sales-exp-year').css('width','46%');//46%
					$('.payment-input-field-tax-exp').css('width','39%');
					$('.line1-exp-year').css('width','23%');//18
					$('.state').text("State/Province");
					$('.zip-code').text("Zip Code");
					$('.cust-view-sales').css('padding-left','1%');//5%
					$('.payment-with-card-lbl').css('width','40%');//30
					$('.rig').css('float','left');
					$('.rig').css('margin-left','1%');
					$('.mobile-div').show();
					$('.normal-div').hide();
				} else if (($(window).width() > 400) && ($(window).width() < 600)) {
					$('.info-column-1').css("width","97%");//42
					$('.payment-methods').css("width","97%");
					$('.info-column-2').css("width","97%");//42		
					$('.info').css("padding-bottom","12px");//2		
					$('.name-customer').css("height","96px");	
					$('.line1-company-name').css("width","33%");//30
					$('.line1').css("width","33%");//30
					$('.address-input-field').css('cssText','width:50%');//56
					$('.add-new-card-btn').css("width","108px");//120
					$('.cust-view-sales').css("width","50%");//63
					$('.update-customer-label').css('margin-top','-8px');-1
					$('.update-customer-label').css('width','64%');//72
					$('.customer-view-sales-exp-month').css('width','20%');//17%
					$('.customer-view-sales-exp-month').css('padding-left','1%');//5%
					$('.payment-create-sales-exp-year').css('width','43%');//46%
					$('.payment-input-field-tax-exp').css('width','39%');
					$('.line1-exp-year').css('width','23%');//18
					$('.state').text("State/Province");
					$('.zip-code').text("Zip Code");
					$('.cust-view-sales').css('padding-left','1%');//5%
					$('.payment-with-card-lbl').css('width','40%');//30
					$('.rig').css('float','left');
					$('.rig').css('margin-left','1%');
					$('.mobile-div').show();
					$('.normal-div').hide();
				} else if (($(window).width() > 600) && ($(window).width() < 1000)) {
					$('.info-column-1').css("width","60%");//42
					$('.payment-methods').css("width","60%");
					$('.info-column-2').css("width","46%");//42		
					$('.info').css("padding-bottom","12px");//2	
					$('.name-customer').css("height","80px");
					$('.line1-company-name').css("width","30%");//30
					$('.line1').css("width","30%");//30
					$('.address-input-field').css('cssText','width:56%');//56
					$('.add-new-card-btn').css("width","120px");//120
					$('.cust-view-sales').css("width","63%");//63
					$('.update-customer-label').css('margin-top','-8px');//-1
					$('.update-customer-label').css('width','72%');//72
					$('.customer-view-sales-exp-month').css('width','17%');//17%
					$('.customer-view-sales-exp-month').css('padding-left','5%');//5%
					$('.payment-create-sales-exp-year').css('width','46%');//46%
					$('.payment-input-field-tax-exp').css('width','52%');//52
					$('.line1-exp-year').css('width','18%');//18
					$('.state').text("State");
					$('.zip-code').text("Zip Code");
					$('.cust-view-sales').css('padding-left','5%');//5%
					$('.payment-with-card-lbl').css('width','30%');//30
					$('.rig').css('float','right');
					$('.rig').css('margin-left','1%');
					$('.mobile-div').hide();
					$('.normal-div').show();
				} else {
					$('.info-column-1').css("width","42%");//42
					$('.payment-methods').css("width","42%");
					$('.info-column-2').css("width","42%");//42		
					$('.info').css("padding-bottom","6px");//2	
					$('.name-customer').css("height","74px");
					$('.line1-company-name').css("width","30%");//30
					$('.line1').css("width","30%");//30
					$('.address-input-field').css('cssText','width:56%');//56
					$('.add-new-card-btn').css("width","120px");//120
					$('.cust-view-sales').css("width","63%");//63
					$('.update-customer-label').css('margin-top','-1px');-1
					$('.update-customer-label').css('width','72%');//72
					$('.customer-view-sales-exp-month').css('width','17%');//17%
					$('.customer-view-sales-exp-month').css('padding-left','2%');//5%
					$('.payment-create-sales-exp-year').css('width','46%');//46%
					$('.payment-input-field-tax-exp').css('width','52%');//52
					$('.line1-exp-year').css('width','18%');//18
					$('.state').text("State/Province");
					$('.zip-code').text("Zip/Postal Code");
					$('.cust-view-sales').css('padding-left','2%');//5%
					$('.payment-with-card-lbl').css('width','30%');//30
					$('.add-new-card-button').css('width','47%');//41
					$('.rig').css('float','right');
					$('.rig').css('margin-left','1%');
					$('.mobile-div').hide();
					$('.normal-div').show();
				}
			});
		}); 
	</script>

	
	
	
	
