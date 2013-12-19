<?php
if(isset($referalsource)){
	$referalsource1=array();
	for($i=0;$i<sizeOf($referalsource);$i++) {
		$referalsource1[$i]['id1']					=	$referalsource[$i]['id'];
		$referalsource1[$i]['information_name1']	=	$referalsource[$i]['information_name'];
	}
}

if(isset($paymentmethod)){
	$paymentmethod1=array();
	for($i=0;$i<sizeOf($paymentmethod);$i++) {
		$paymentmethod1[$i]['id2']					=	$paymentmethod[$i]['id'];
		$paymentmethod1[$i]['information_name2']	=	$paymentmethod[$i]['information_name'];
		$paymentmethod1[$i]['payment_type2']	=	$paymentmethod[$i]['payment_type'];
	}	
}

if(isset($shippingmethod)){
	$shippingmethod1=array();
	for($i=0;$i<sizeOf($shippingmethod);$i++) {
		$shippingmethod1[$i]['id3']					=	$shippingmethod[$i]['id'];
		$shippingmethod1[$i]['information_name3']	=	$shippingmethod[$i]['information_name'];
	}
}

if(isset($sales_comment)){
	$sales_comment1=array();
	for($i=0;$i<sizeOf($sales_comment);$i++) {
		$sales_comment1[$i]['id4']					=	$sales_comment[$i]['id'];
		$sales_comment1[$i]['information_name4']	=	$sales_comment[$i]['information_name'];
	}
}

if(isset($employees) && !empty($employees)){
	$salesperson1=array();
	foreach($employees as $Emp => $val){
		$salesperson1[$Emp]['RecordID5']					=	$val['RecordID'];
		$salesperson1[$Emp]['CompanyOrLastName']			=	$val['CompanyOrLastName'];
		$salesperson1[$Emp]['firstname']					=	$val['firstname'];
	}
}

if(isset($accounts)){
	$accounts1	=	array();
	for($i=0;$i<sizeOf($accounts);$i++) {
		$accounts1[$i]['account_id']				=	$accounts[$i]['id'];
		$accounts1[$i]['account_name']				=	$accounts[$i]['account_name'];
		$accounts1[$i]['account_number']			=	$accounts[$i]['account_number'];
		$accounts1[$i]['account_description']		=	$accounts[$i]['account_description'];
		$accounts1[$i]['TaxCodeRecordID']			=	$accounts[$i]['TaxCodeRecordID'];
		$accounts1[$i]['percentage']				=	isset($accounts[$i]['percentage'])?$accounts[$i]['percentage'] : "";
		$accounts1[$i]['tax_code']					=	isset($accounts[$i]['tax_code'])?$accounts[$i]['tax_code'] : "";
	}
}

if(isset($jobs)){
	$jobs1	=	array();
	for($i=0;$i<sizeOf($jobs);$i++) {
		$jobs1[$i]['job_id']						=	$jobs[$i]['id'];
		$jobs1[$i]['job_name']						=	$jobs[$i]['job_name'];
		$jobs1[$i]['job_number']					=	$jobs[$i]['job_number'];
	}
}

if(isset($items)){
	$items1	=	array();
	for($i=0;$i<sizeOf($items);$i++) {
		$items1[$i]['item_id']						=	$items[$i]['id'];
		$items1[$i]['item_number']					=	$items[$i]['item_number'];
		$items1[$i]['item_name']					=	$items[$i]['item_name'];
		$items1[$i]['selling_price']				=	$items[$i]['selling_price'];
		$items1[$i]['is_description_used']			=	$items[$i]['is_description_used'];
		$items1[$i]['item_description']				=	$items[$i]['item_description'];
		$items1[$i]['tax_when_sold_us']				=	$items[$i]['tax_when_sold_us'];		// got the TAX code info in Item POP-UP
		$items1[$i]['tax_when_sold_non_us']			=	$items[$i]['tax_when_sold_non_us'];
		
		if(isset($items[$i]['tax_code'])){ // TAX details from item
			$items1[$i]['tax_code']						=	$items[$i]['tax_code'];
			$items1[$i]['percentage']					=	$items[$i]['percentage'];
		} else { // tax details from customer.
			// null we will get this field dynamically from browser/javascript
			// so, not taking any data here
		}
		
		if(isset($items[$i]['sub_tax_code'])&&$items[$i]['sub_tax_code']!=""){
			$items1[$i]['sub_tax_code']					=	$items[$i]['sub_tax_code'];	
		}		
	}
}

if(isset($activities)){
	$activities1	=	array();
	for($i=0;$i<sizeOf($activities);$i++) {
		$activities1[$i]['act_id']					=	$activities[$i]['id'];
		$activities1[$i]['activity_id']				=	$activities[$i]['activity_id'];
		$activities1[$i]['use_description_on_sales']=	$activities[$i]['use_description_on_sales'];
		$activities1[$i]['description']				=	$activities[$i]['description'];
		$activities1[$i]['activity_name']			=	$activities[$i]['activity_name'];
		$activities1[$i]['activity_rate']			=	$activities[$i]['activity_rate'];
		$activities1[$i]['TaxCodeRecordID']			=	$activities[$i]['TaxCodeRecordID'];
		$activities1[$i]['percentage']				=	isset($activities[$i]['percentage'])?$activities[$i]['percentage'] : "";
		$activities1[$i]['tax_code']				=	isset($activities[$i]['tax_code'])?$activities[$i]['tax_code'] : "";
		$activities1[$i]['tax_when_sold_us']		=	$activities[$i]['tax_when_sold_us'];
	}
}
if(isset($Custom_list_2)){
	$Custom_list2_2=array();
	for($i=0;$i<sizeOf($Custom_list_2);$i++) {
		$Custom_list2_2[$i]['custom_list_name_second']	=	$Custom_list_2[$i]['custom_list_name'];
	}
}
if(isset($Custom_list_3)){
	$Custom_list3_3=array();
	for($i=0;$i<sizeOf($Custom_list_3);$i++) {
		$Custom_list3_3[$i]['custom_list_name_third']	=	$Custom_list_3[$i]['custom_list_name'];
	}
}

?>

	<script type="text/javascript">
	<?php if(isset($employees) && !empty($employees)){ ?>
	var emp				=	<?php echo json_encode($salesperson1);?>;
	<?php } ?>
	<?php if(isset($referalsource)){ ?>
	var referal			=	<?php echo json_encode($referalsource1);?>;
	<?php } ?>
	<?php if(isset($sales_comment)){ ?>
	var comment			=	<?php echo json_encode($sales_comment1);?>;
	<?php } ?>
	<?php if(isset($paymentmethod)){ ?>
	var payment			=	<?php echo json_encode($paymentmethod1);?>;
	<?php } ?>
	<?php if(isset($shippingmethod)){ ?>
	var ship			=	<?php echo json_encode($shippingmethod1);?>;
	<?php } ?>
	<?php if(isset($accounts)){ ?>
	var accountJSON		=	<?php echo json_encode($accounts1);?>;
	<?php } ?>
	<?php if(isset($jobs)){ ?>
	var jobJSON			=	<?php echo json_encode($jobs1);?>;
	<?php } ?>
	<?php if(isset($items)){?>
		var itemJSON	=	<?php echo json_encode($items1);?>;
	<?php }?>
	<?php if(isset($activities)){ ?>
		var activityJSON	=	<?php echo json_encode($activities1);?>;
	<?php } ?>
	<?php if(isset($customers)){ ?>
	var customers		=	<?php echo json_encode($customers);?>;
	<?php } ?>
	<?php if(isset($countries)){?>
	var country	=	<?php  echo json_encode($countries);?>;
	<?php } ?>
	<?php if(isset($Custom_list_1)){?>
	var custom1	=	<?php echo json_encode($Custom_list_1);?>;
	<?php } ?>
	<?php if(isset($Custom_list_2)){?>
	var custom2	=	<?php echo json_encode($Custom_list2_2);?>;
	<?php } ?>
	<?php if(isset($Custom_list_3)){?>
	var custom3	=	<?php  echo json_encode($Custom_list3_3);?>;
	<?php } ?>
	<?php if(isset($sub_job)){?>
	var sub_job	=	<?php  echo json_encode($sub_job);?>;
	<?php } ?>
	
	$().ready(function() {
		$(".add-more-btn").click(function(){
			var total_items	=	$("#total_items").val();
			var i = parseInt(total_items)+1;
			if($('#item_invoice .add-more-btn').is(':visible')){
				if($('.item-container').is(':visible')){
						var add_content	=	"<div class='name-salesItem1 additional_content_"+i+"'>"+
									"<div class='salesItem7'>"+
										"<div class='salesItem6'>"+
											"<div class='salesItem5'>"+
												"<div class='salesItem4'>"+
													"<div class='salesItem3'>"+
														"<div class='salesItem2'>"+
															"<div class='salesItem-1-field salesItem_"+i+"'><input autocomplete='off' popup_id='<?php echo ITEMS_POPUP;?>' onfocus='hide_popup(this);' class='items_field_"+i+" left service-invoice-field-item font-color1 tabindex_field ac_input popup_validate' type='text'><input name='item_number[]' class='items-value item_val_"+i+"' type='hidden'><a id='openItemPopup' href='javascript:void(0)' class='item_popup service-down-arrow1 mar-rig-10 pad-20 right openItemPopup'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
															"</div>"+
															"<div class='salesItem-2-field'><input class='service-invoice-field-select left item_description description_"+i+" font-color1 popup_validate tabindex_field' onfocus='hide_popup(this);' name='item_description[]' type='text'></div>"+
															"<div class='salesItem-3-field'><input class='service-invoice-field left font-color1 number quantity tabindex_field popup_validate' id='qty' onfocus='hide_popup(this);' name='quantity[]' value='0' type='text' autocomplete='off' onkeyup='calculate_item_total1(this);resize_input_box(this);' ></div>"+
															"<div class='salesItem-4-field'><input type='text' class='price"+i+" service-invoice-field right number font-color1 amt_ipt1 item_price tabindex_field popup_validate'  id='price"+i+"' onfocus='hide_popup(this);' name='price[]' autocomplete='off' value='0.00' onblur='this.value=Number(this.value).toFixed(2);resize_tag(this, 0);'  onkeyup='calculate_item_total1(this);resize_input_box(this);'/> <span class='dollar_sales_items right mar-left-8'>$</span> </div>"+
															"<div class='salesItem-5-field'><input type='hidden' class='original_total_amount' value='' name='total[]' />   <input type='text' id='tlt_amt"+i+"' autocomplete='off' class='service-invoice-field font-color1 right amt_ipt1 total tabindex_field' value='0.00' readonly/> <span class='dollar_sales_items right mar-left-8'>$</span> </div>"+
															" <div class='salesItem-6-field popup_jobs_"+i+"'><input autocomplete='off' popup_id='<?php echo JOBS_POPUP;?>' class='jobs_field_"+i+" left service-invoice-field-job font-color1 tabindex_field ac_input' type='text'><input name='job[]' class='job_val_"+i+"' type='hidden'><a id='openJobPopup' href='javascript:void(0)' class='jobs-popup service-down-arrow1 pad-20 right mar-rig-10'>&nbsp;&nbsp;&nbsp;&nbsp;</a></div>"+
															" <div class='salesItem-7-field'>"+
																<?php if(isset($country)&&$country == USA){ // for US?>
																"<label class='service-invoice-field-tax mar-left-8 font-color1'>"+
																"<a href='javascript:void(0);' class='tax-chk tax-enable'><img src='/media/images/tt-new/enable.png' class='tax-enable-disable tabindex_field'></a>"+
																"<input type='hidden' name='tax_check[]' class='tax_check_value' value='1' />"+
																"<span class='delete_row_create mar-top-neg2' onclick=delete_create_edit('additional_content_"+i+"');></span>"+
																"</label>"+
																<?php } else { ?>
																"<span class='tax_applied'></span>"+
																"<div class='delete_row_create' onclick=delete_create_edit('additional_content_"+i+"');></div>"+
																"<a class='service-down-arrow amt_side2 openTaxPopup1' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
																"<input type='hidden' name='tax_check[]' class='tax_check_value' value='1' />"+
																<?php } ?>
																"<input class='tax_applied_percentage name='item_tax_percentage[]' id='item_tax_percentage' type='hidden' />"+
																"<input class='tax_applied_record_id' name='item_tax_record_id[]' id='item_tax_record_id' type='hidden' />"+
															"</div>"+
														"</div>"+
													"</div>"+
												"</div>"+
											"</div>"+
										"</div>"+
									"</div>";
					}else{
						var add_content_phone	=	"<div id='' class='name-salesItem1 additional_content_"+i+" phone_mode'>"+
														"<div id='' class='phonesalesItem_"+i+" bor-bottom phone_fields phone_sales_item' >"+
															"<label class='left font-light phone_labels'>Item#</label>"+
															"<input type='text' onfocus='hide_popup(this);' popup_id='<?php echo ITEMS_POPUP;?>' class='items_phonefield_"+i+" service-invoice-field-item left popup_validate font-color1'>"+
															"<input type='hidden' name='item_number_phone[]' class='items-value item_val_phone_"+i+"'/>"+
															"<a id='openItemPopup' href='javascript:void(0)' class='service-down-arrow1 pad-20 right mar-right-4'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
														"</div>"+
					
														"<div class='phone_fields bor-bottom' >"+
															"<label class='left font-light phone_labels'>Description</label>"+
															"<input type='text' onfocus='hide_popup(this);' class='descriptionPhone_"+i+" item_description left font-color1 popup_validate' name='item_description_phone[]' value='' />"+
														"</div> "+
		
														"<div class='phone_fields bor-bottom' >"+
															"<label class='left font-light phone_labels'>Quantity</label>"+
															"<input type='text' onfocus='hide_popup(this);' class='service-invoice-field left font-color1 quantity popup_validate' id='' name='quantity_phone[]' value='0' onkeyup='calculate_item_total1(this);'/>"+
														"</div>"+
					
														"<div class='phone_fields bor-bottom' >"+
															"<label class='left font-light phone_labels'>Price</label>"+
															"<span class='dollar_sales_mobile left mar-left-8'>$</span>"+
															"<input type='text' onfocus='hide_popup(this);' class='price"+i+" service-invoice-field left font-color1 item_price popup_validate' name='price_phone[]' value='0.00' onkeyup='calculate_item_total1(this);'/>"+
														"</div>"+
														"<div class='clear'></div>"+
					
														"<div class='phone_fields bor-bottom' >"+
															"<label class='left font-light phone_labels'>Total</label>"+
															"<span class='dollar_sales_items left mar-left-8'>$</span>"+
															"<input type='hidden' class='original_total_amount' value='' name='total_phone[]' /><input type='text' class='service-invoice-field font-color1 left width-50 total left' value='0.00' readonly />"+
														"</div>"+
					
														"<div class='clear'></div>"+
														"<div class='phone_jobs_"+i+" phone_fields bor-bottom' >"+
															"<label class='left font-light phone_labels'>Jobs</label>"+
																"<input type='text' popup_id='<?php echo JOBS_POPUP;?>' class='jobs_phonefield_"+i+" service-invoice-field-job left font-color1'>"+
																"<input type='hidden' name='job_phone[]' class='job_val_phone_"+i+"' />"+
																"<a id='openJobPopup' href='javascript:void(0)' class='service-down-arrow1 pad-20 right mar-right-4'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
														"</div>"+
														"<div class='phone_fields taxes' >"+
															"<label class='left font-light phone_labels'>Tax</label>"+
															<?php if(isset($country)&&$country !=USA ){ //for non US?>
															"<input type='hidden' name='tax_check_phone[]' class='tax_check_value' value='1' />"+
															"<span class='tax_applied'></span>"+
															"<span class='delete_row_create' onclick=delete_create_edit('additional_content_"+i+"');></span>"+
															"<a class='service-down-arrow amt_side2 openTaxPopup1' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
															<?php } else { // for US ?>
															"<input type='hidden' name='tax_check_phone[]' class='tax_check_value' value='1' />"+
															"<label class='service-invoice-field-tax font-color1'>"+
															"<span class='delete_row_create mar-top-neg2' onclick=delete_create_edit('additional_content_"+i+"');></span>"+
															"<a href='javascript:void(0);' class='tax-chk tax-enable'><img src='/media/images/tt-new/enable.png' class='tax-enable-disable'></a>"+
															"</label>"+
															<?php } ?>
															"<input class='tax_applied_percentage' name='item_tax_percentage_mobile[]' id='item_tax_percentage' type='hidden' />"+
															"<input class='tax_applied_record_id'  name='item_tax_record_id_mobile[]'  id='item_tax_record_id'  type='hidden' />"+
														"</div>"+
													"</div>";
					}
					var total_items	=	parseInt($("#total_items").val());
				}
				else if($('#service_invoice .add-more-btn').is(':visible')){
					if($('.item-container').is(':visible')){
					var add_content	=	"<div class='name-salesItem1 additional_content_"+i+"'>"+
											"<div class='serviceviewItem7'>"+
												"<div class='serviceviewItem6'>"+
													"<div class='serviceviewItem5'>"+
														"<div class='serviceviewItem3'>"+
															"<div class='serviceviewItem-2-field serviceAccounts_"+i+"'>"+
															"<input type='text' popup_id='<?php echo ACCOUNT_POPUP;?>' onfocus='hide_popup(this);' class='accounts_field_"+i+" marheight margin-left-2 tabindex_field service-invoice-field-select account-field font-color1 popup_validate' onfocus='hide_popup(this)' /><input type='hidden' name='account[]' class='account_val_"+i+" account-value' />"+
															"<a id='openAccountPopup' href='javascript:void(0)' class='accounts_popup_service service-down-arrow1 mar-rig-10 padding-16 right openAccountPopup'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
															"</div>"+
															"<div class='serviceviewItem-3-field'>"+
															"<input type='text' class='service-invoice-field-desc marheight tabindex_field popup_validate font-color1 service_description_"+i+"' name='description[]' value='' onfocus='hide_popup()' />"+
															"</div>"+
															"<div class='serviceviewItem-4-field'>"+
															"<input type='text' onfocus='hide_popup(this);' autocomplete='off' class='service-invoice-field right amt_ipt1 number font-color1 tabindex_field amount-service right popup_validate' id='amount-service"+i+"' value='0.00' name='' onblur='calculate_service_total(this.value,this); this.value=Number(this.value).toFixed(2); resize_tag(this, 0);' onkeyup='resize_input_box(this);' /><span class='dollar_sales right mar-left-8'>$</span>"+					
															"<input type='hidden' class='original_total_amount' value='' name='amount[]' />"+   						
															"</div>"+
															"<div class='serviceviewItem-6-field popup_jobs_"+i+"'><input type='text' popup_id='<?php echo JOBS_POPUP;?>' autocomplete='off' class='jobs_field_"+i+" marheight1 service-invoice-field-job tabindex_field font-color1'><input type='hidden' name='job[]' class='job_val_"+i+"'/><a id='openJobPopup' href='javascript:void(0)' class='jobs-popup service-down-arrow1 pad-20 mar-rig-10 right'>&nbsp;&nbsp;&nbsp;&nbsp;</a></div>"+
															"<div class='serviceviewItem-7-field'>"+
															<?php if(isset($country)&&$country == USA){ //For US ?>
															"<label class='service-invoice-field-tax mar-left-8 font-color1'>"+
															"<a href='javascript:void(0);' class='tax-chk tax-enable'><img src='/media/images/tt-new/enable.png' class='tax-enable-disable tabindex_field'></a>"+
															"<input type='hidden' name='tax_check[]' class='tax_check_value' value='1' />"+
															"<span class='delete_row_create mar-top-neg2' onclick=delete_create_edit('additional_content_"+i+"');></span>"+
															"</label>"+
															<?php } else { ?>
															"<span class='tax_applied'></span>"+
															"<span class='delete_row_create' onclick=delete_create_edit('additional_content_"+i+"');></span>"+
															"<a class='service-down-arrow amt_side2 openTaxPopup1' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
															"<input type='hidden' name='tax_check[]' class='tax_check_value' value='1' />"+
															<?php } ?>
															"<input class='tax_applied_percentage' name='item_tax_percentage[]' id='item_tax_percentage' type='hidden' />"+
															"<input class='tax_applied_record_id' name='service_tax_record_id[]' id='item_tax_record_id' type='hidden' />"+
															"</div>"+
														"</div>"+
													"</div>"+
												"</div>"+
											"</div>"+
										"</div>";
				}else{
					var add_content_phone	= "<div class='clear'></div><div id='' class='name-salesItem1 additional_content_"+i+" phone_mode'>"+
													"<div id='' class='phone_service_account bor-bottom phone_fields phoneserviceAccounts_"+i+"' >"+
                                    					"<label class='left font-light phone_labels'>Account</label>"+
                                    					"<input type='text' onfocus='hide_popup(this);' popup_id='<?php echo ACCOUNT_POPUP;?>' class='phoneaccounts_field_"+i+" service-invoice-field left account-field popup_validate font-color1'>"+
                                    					"<input type='hidden' name='account_phone[]' class='account-value account_val_phone_"+i+"'/>"+
                                    					"<a id='openAccountPopup' href='javascript:void(0)' class='service-down-arrow1 pad-20 right mar-right-4'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
                                    				"</div>"+
				
                                    				"<div class='phone_fields bor-bottom' >"+
                                    					"<label class='left font-light phone_labels'>Description</label>"+
                                    					"<input type='text' class='service-invoice-field service_description_phone_"+i+" font-color1 width-50 quantity'  name='description_phone[]' value='' "+
                                    				"</div> "+
                                    				"<div class='clear'></div>"+	
                                    				"<div class='amount_phone phone_fields bor-bottom' >"+
                                    					"<label class='left font-light phone_labels'>Amount</label>"+
                                    					"<span class='dollar_sales_mobile left mar-left-8'>$</span><input type='hidden' class='original_total_amount' value='' name='amount_phone[]' />  "+
                                    					"<input type='text' onfocus='hide_popup(this);' class='service-invoice-field font-color1 amount-service popup_validate' name='' value='0.00' onkeyup='calculate_service_total(this.value,this);' />"+
                                    				"</div>"+
                                    				
                                    				"<div class='clear'></div>"+
                                    				"<div class='phone_jobs_"+i+" phone_fields bor-bottom' >"+
                                    					"<label class='left font-light phone_labels'>Jobs</label>"+
                                    							"<input type='text' popup_id='<?php echo JOBS_POPUP;?>' class='jobs_phonefield_"+i+" service-invoice-field-job left font-color1'>"+
                                    							"<input type='hidden' name='job_phone[]' class='job_val_phone_"+i+"' />"+
                                    							"<a id='openJobPopup' href='javascript:void(0)' class='service-down-arrow1 pad-20 right mar-right-4'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
                                    				"</div>"+
                                    				"<div class='phone_fields taxes' >"+
														"<label class='left font-light phone_labels'>Tax</label>"+
														<?php if(isset($country)&&$country != USA){ //for non US?>
														"<input type='hidden' name='tax_check_phone[]' class='tax_check_value' value='1' />"+
														"<span class='tax_applied'></span>"+
														"<span class='delete_row_create' onclick=delete_create_edit('additional_content_"+i+"');></span>"+
														"<a class='service-down-arrow amt_side2 openTaxPopup1' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
														<?php } else { // for US ?>
														"<input type='hidden' name='tax_check_phone[]' class='tax_check_value' value='1' />"+
														"<label class='service-invoice-field-tax font-color1'>"+
														"<span class='delete_row_create mar-top-neg2' onclick=delete_create_edit('additional_content_"+i+"');></span>"+
														"<a href='javascript:void(0);' class='tax-chk tax-enable'><img src='/media/images/tt-new/enable.png' class='tax-enable-disable'></a>"+
														"</label>"+
														<?php } ?>
														"<input class='tax_applied_percentage' name='item_tax_percentage[]' id='item_tax_percentage' type='hidden' />"+
														"<input class='tax_applied_record_id'  name='service_tax_record_id_mobile[]'  id='item_tax_record_id'  type='hidden' />"+
													"</div>"+
												"</div>";
				}
					var total_items	=	parseInt($("#total_items").val());
				}
				else if($('#timebilling_invoice .add-more-btn').is(':visible')){
					if($('.item-container').is(':visible')){
					var add_content	=	"<div class='name-salesItem1 additional_content_"+i+"'>"+
											"<div class='timebillingviewItem7'>"+
												"<div class='timebillingviewItem6'>"+
													"<div class='timebillingviewItem5'>"+
														"<div class='timebillingviewItem4'>"+
															"<div class='timebillingviewItem3'>"+
																"<div class='timebillingviewItem2'>"+
																	"<div class='timebillingviewItem1'>"+
																		"<div class='timebillingviewItem-0-field'>"+
																			"<input type='text' placeholder='MM/DD/YYYY' class='timebilling-invoice-field-desc font-color1 tb-date tabindex_field'  name='date_item[]' value='' />"+
																		"</div>"+
																		"<div class='timebillingviewItem-1-field timebillingActivity_"+i+"'>"+
																			"<input type='text' onfocus='hide_popup(this);' popup_id='<?php echo ACTIVITY_POPUP;?>' autocomplete='off' class='activity_field_"+i+" timebilling-invoice-field-desc timebilling_activity font-color1 tb-invoice-field-select tabindex_field popup_validate' />"+
																			"<input type='hidden' name='activity[]' class='activity_value account_val_"+i+"'/><a id='openActivityPopup' href='javascript:void(0)' class='activities-popup service-down-arrow1 pad-20 right mar-rig-10 openActivityPopup'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
																		"</div>"+
																		"<div class='timebillingviewItem-2-field'>"+
																			"<input type='text' class='timebilling-invoice-field-desc font-color1 description popup_validate tb_description_"+i+" tabindex_field' name='description[]'  value='' />"+
																		"</div>"+
																		"<div class='timebillingviewItem-3-field'>"+
																			"<input  type='text' onfocus='hide_popup(this);' autocomplete='off' class='timebilling-invoice-field-desc font-color1 number units tabindex_field popup_validate'  id='hrs-units' name='units[]' value='0' onkeyup='calculate_tb_total1(this);resize_input_box(this);' />"+
																		"</div>"+
																		"<div class='timebillingviewItem-4-field'>"+
																			"<input  type='text' onfocus='hide_popup(this);' autocomplete='off' class='activity_rate_"+i+" timebilling-invoice-field-desc amt_ipt1 number right font-color1 rate popup_validate tabindex_field' id='rate"+i+"' name='rates[]'  value='0.00' onblur='this.value=Number(this.value).toFixed(2);resize_tag(this, 0);' onkeyup='calculate_tb_total1(this);resize_input_box(this);'/><span class='dollar_sales right mar-left-8'>$</span>"+
																		"</div>"+
																		"<div class='timebillingviewItem-5-field'>"+
																			"<input type='hidden' class='original_total_amount' value='' name='amount[]' /><input type='text' autocomplete='off' class='timebilling-invoice-field-desc font-color1 amt_ipt1 right amount tabindex_field' id='amount"+i+"' name=''  value='0.00' readonly /><span class='dollar_sales right mar-left-8'>$</span>"+
																		"</div>"+
																		"<div class='timebillingviewItem-6-field popup_jobs_"+i+"'>"+
																			"<input type='text' autocomplete='off' popup_id='<?php echo JOBS_POPUP;?>' class='jobs_field_"+i+" timebilling-invoice-field-desc tabindex_field service-invoice-field-job font-color1' /><input type='hidden' name='job[]' class='job_val_"+i+"'/><a id='openJobPopup' href='javascript:void(0)' class='jobs-popup service-down-arrow1 mar-rig-10 pad-20 right'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
																		"</div>"+
																		"<div class='timebillingviewItem-7-field'>"+
																			<?php if(isset($country)&&$country == USA){ // For US ?>
																			"<label class='service-invoice-field-tax mar-left-8 font-color1'>"+
																			"<a href='javascript:void(0);' class='tax-chk tax-enable'><img src='/media/images/tt-new/enable.png' class='tax-enable-disable tabindex_field'></a>"+
																			"<input type='hidden' name='tax_check[]' class='tax_check_value' value='1' />"+
																			"<span class='delete_row_create mar-top-neg2' onclick=delete_create_edit('additional_content_"+i+"');></span>"+
																			"</label>"+
																			<?php } else { ?>
																			"<span class='tax_applied'></span>"+
																			"<span class='delete_row_create' onclick=delete_create_edit('additional_content_"+i+"');></span>"+
																			"<a class='service-down-arrow amt_side2 openTaxPopup1' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
																			"<input type='hidden' name='tax_check[]' class='tax_check_value' value='1' />"+
																			<?php } ?>
																			"<input class='tax_applied_percentage' name='item_tax_percentage[]' id='item_tax_percentage' type='hidden' />"+
																			"<input class='tax_applied_record_id' name='tb_tax_record_id[]' id='item_tax_record_id' type='hidden' />"+
																		"</div>"+
																	"</div>"+
																"</div>"+
															"</div>"+
														"</div>"+
													"</div>"+
												"</div>"+
											"</div>"+
										"</div>";
					}else{
						var add_content_phone = "<div class='clear'><div id='' class='name-salesItem1 additional_content_"+i+" phone_mode'>"+
													"<div class='phone_fields bor-bottom' >"+
														"<label class='left font-light phone_labels'>Date</label>"+
														"<input type='text' class='mobile_timebilling_units font-color1 date_item' value='' name='date_item_phone[]' />"+
													"</div>"+
													"<div id='' class='timebillingviewItem_activity_phone bor-bottom phone_fields phonetimebillingActivity_"+i+"' >"+
														"<label class='left font-light phone_labels'>Activity</label>"+
														"<input type='text' onfocus='hide_popup(this);' popup_id='<?php echo ACTIVITY_POPUP;?>' class='activity_phonefield_"+i+" left service-invoice-field-item font-color1 timebilling_activity popup_validate tb-invoice-field-select'>"+
														"<input type='hidden' name='activity_phone[]' class='activity_value account_val_phone_"+i+"'/>"+
														"<a id='openActivityPopup' href='javascript:void(0)' class='service-down-arrow1 pad-20 right mar-right-4'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
													"</div>"+
													"<div class='phone_fields bor-bottom' >"+
														"<label class='left font-light phone_labels'>Description</label>"+
														"<input type='text' class='tb_description_phone_"+i+" timebilling-invoice-field-desc font-color1 description popup_validate' name='description_phone[]'  value='' />"+
													"</div>"+
													"<div class='phone_fields bor-bottom' >"+
														"<label class='left font-light phone_labels'>Hrs</label>"+
														"<input type='text' onfocus='hide_popup(this);' class='mobile_timebilling_units font-color1 number units popup_validate' name='units_phone[]' value='0' onkeyup='calculate_tb_total1(this);'/>"+
													"</div> "+
													"<div class='phone_fields bor-bottom' >"+
														"<label class='left font-light phone_labels'>Rate</label>"+
														"<span class='dollar_sales_mobile left mar-left-8'>$</span>"+
														"<input type='text' onfocus='hide_popup(this);' class='activity_rate_phone_"+i+" mobile_timebilling_units number font-color1 rate popup_validate' name='rates_phone[]'  value='0.00' onkeyup='calculate_tb_total1(this);' />"+
													"</div>"+
													"<div class='clear'></div>"+
			
													"<div class='phone_fields bor-bottom' >"+
														"<label class='left font-light phone_labels'>Amount</label>"+
														"<span class='dollar_sales_items left mar-left-8'>$</span>"+
														"<input type='hidden' class='original_total_amount' value='' name='amount_phone[]' />  <input type='text' class='mobile_timebilling_units font-color1 amount' name=''  value='0.00' readonly />"+
													"</div>"+
													"<div class='clear'></div>"+
													"<div class='phone_jobs_"+i+" phone_fields bor-bottom' >"+
														"<label class='left font-light phone_labels'>Jobs</label>"+
														"<input type='text' popup_id='<?php echo JOBS_POPUP;?>' class='jobs_phonefield_"+i+" service-invoice-field-job left font-color1'>"+
														"<input type='hidden' name='job_phone[]' class='job_val_phone_"+i+"' />"+
														"<a id='openJobPopup' href='javascript:void(0)' class='service-down-arrow1 pad-20 mar-right-4 right'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
													"</div> "+
													"<div class='phone_fields taxes' >"+
														"<label class='left font-light phone_labels'>Tax</label>"+
														<?php if(isset($country)&&$country !=USA ){ //for non US?>
															"<input type='hidden' name='tax_check_phone[]' class='tax_check_value' value='1' />"+
															"<span class='tax_applied'></span>"+
															"<div class='delete_row_create' onclick=delete_create_edit('additional_content_"+i+"');></div>"+
															"<a class='service-down-arrow amt_side2 openTaxPopup1' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;&nbsp;</a>"+
														<?php } else { // for US ?>
															"<input type='hidden' name='tax_check_phone[]' class='tax_check_value' value='1' />"+
															"<label class='service-invoice-field-tax font-color1'>"+
															"<span class='delete_row_create mar-top-neg2' onclick=delete_create_edit('additional_content_"+i+"');></span>"+
															"<a href='javascript:void(0);' class='tax-chk tax-enable'><img src='/media/images/tt-new/enable.png' class='tax-enable-disable'></a>"+
															"</label>"+
														<?php } ?>
														"<input class='tax_applied_percentage' name='item_tax_percentage[]' id='item_tax_percentage' type='hidden' />"+
														"<input class='tax_applied_record_id'  name='tb_tax_record_id_mobile[]'  id='item_tax_record_id'  type='hidden' />"+
													"</div>"+
												"</div>";
					}
					var total_items	=	parseInt($("#total_items").val());
					}
			if($('.item-container').is(':visible')){
				$(".item-container").append(add_content);
			}else{
				$(".item-container-phone").append(add_content_phone);
			}
			<?php if(isset($items)) { ?>
				add_new_item();
			<?php } ?>
			add_new_job();
			<?php if(isset($accounts)) { ?>
			add_new_account();
			<?php } ?>
			<?php if(isset($activities)){ ?>
			add_new_activity();
			<?php } ?>
			total_items++;
			$("#total_items").val(total_items);
			resize_tag('price'+i, 1);
			resize_tag('tlt_amt'+i, 1);
			resize_tag('amount-service'+i, 1);
			resize_tag('rate'+i, 1);
			resize_tag('amount'+i, 1);

			$(function() {
	      	  var tabindex = 1;
	      	  $('.tabindex_field').each(function() {
	      	     if (this.type != "hidden" && this.id !='specified_date') {
	      	       var $input = $(this);
	      	       $input.attr("tabindex", tabindex);
	      	       tabindex++;
	      	     }
	      	  });
	      	});

			$(".number").keypress(function (e){
			   var charCode = (e.which) ? e.which : e.keyCode;
			   if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode!=46) {
			     return false;
			   }
			 });

			i++;
			});

	<?php if(isset($customers)){ ?>
		$('#customer_text').autocomplete(customers, {
			minChars: 0,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.firstname+' '+row.company_or_lastname;
			},
			formatMatch: function(row, i, max) {
				return row.firstname+' '+row.company_or_lastname;
			},
			formatResult: function(row) {
				return row.firstname+' '+row.company_or_lastname;
			}
		});
	<?php } ?>
	
	<?php if(isset($employees) && !empty($employees)){ ?>
		$(".sales_person_input").autocomplete(emp, {
			minChars: 0,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.firstname+' '+row.CompanyOrLastName;
			},
			formatMatch: function(row, i, max) {
				return row.firstname+' '+row.CompanyOrLastName;
			},
			formatResult: function(row) {
				return row.firstname+' '+row.CompanyOrLastName;
			}
		});
	<?php } ?>

	<?php if(isset($referalsource)){ ?>	
		$(".referal_input").autocomplete(referal, {
			minChars: 0,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.information_name1 ;
			},
			formatMatch: function(row, i, max) {
				return row.information_name1;
			},
			formatResult: function(row) {
				return row.information_name1;
			}
		});
	<?php } ?>
		
	<?php if(isset($sales_comment)){ ?>
		$(".comment_input").autocomplete(comment, {
			minChars: 0,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.information_name4;
			},
			formatMatch: function(row, i, max) {
				return row.information_name4;
			},
			formatResult: function(row) {
				return row.information_name4;
			}
		});
	<?php } ?>

	<?php if(isset($paymentmethod)){ ?>
		$(".payment_input").autocomplete(payment, {
			minChars: 0,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.information_name2 ;
			},
			formatMatch: function(row, i, max) {
				return row.information_name2;
			},
			formatResult: function(row) {
				return row.information_name2;
			}
		});
	<?php } ?>
		
		<?php if(isset($shippingmethod)){ ?>
		$(".shipping_input").autocomplete(ship, {
			minChars: 0,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.information_name3 ;
			},
			formatMatch: function(row, i, max) {
				return row.information_name3;
			},
			formatResult: function(row) {
				return row.information_name3;
			}
		});
		<?php } ?>
		
		<?php if(isset($accounts)) { ?>
		function add_new_account(){
			var account_class='';
			if(location.href.indexOf('sales')!=-1){
				account_class = 'account-field';
			}
			else{
				account_class = 'payment_input';
			}
			
			$('.'+account_class).autocomplete(accountJSON, {
				minChars: 0,
				autoFill: false,
				formatItem: function(row, i, max) {
					var input_account1	=	row.account_number.substr('0','1');
					var input_account2	=	row.account_number.substr('1',row.account_number.length);
					return input_account1+'-'+input_account2+' : '+row.account_name;
				},
				formatMatch: function(row, i, max) {
					var input_account1	=	row.account_number.substr('0','1');
					var input_account2	=	row.account_number.substr('1',row.account_number.length);
					return input_account1+'-'+input_account2+' : '+row.account_name;
				},
				formatResult: function(row) {
					var input_account1	=	row.account_number.substr('0','1');
					var input_account2	=	row.account_number.substr('1',row.account_number.length);
					return input_account1+'-'+input_account2;
				}
			});
		}
		add_new_account();
		<?php } ?>

		<?php if(isset($jobs)){ ?>
		function add_new_job(){
			$(".service-invoice-field-job").autocomplete(jobJSON, {
				minChars: 0,
				autoFill: false,
				formatItem: function(row, i, max) {
					return (row.job_name);
				},
				formatMatch: function(row, i, max) {
					return row.job_name;
				},
				formatResult: function(row) {
					return (row.job_name);
				}
			});
		}
		add_new_job();
		<?php }?>
		<?php if(isset($items)){ ?>
			function add_new_item(){
				$(".service-invoice-field-item").autocomplete(itemJSON, {
					minChars: 0,
					autoFill: false,
					formatItem: function(row, i, max) {
						return (row.item_number);
					},
					formatMatch: function(row, i, max) {
						return row.item_number;
					},
					formatResult: function(row) {
						return (row.item_number);
					}
				});
			}
			add_new_item();
		<?php } ?>
		<?php if(isset($activities)){ ?>
		function add_new_activity(){
			$(".tb-invoice-field-select").autocomplete(activityJSON, {
				minChars: 0,
				autoFill: false,
				formatItem: function(row, i, max) {
					return (row.activity_id);
				},
				formatMatch: function(row, i, max) {
					return row.activity_id;
				},
				formatResult: function(row) {
					return (row.activity_id);
				}
			});
		}
		add_new_activity();
		<?php } ?>
		<?php if(isset($customers)){ ?>
		$(".linked-cust-field").autocomplete(customers, {
			minChars: 0,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.firstname+' '+row.company_or_lastname;
			},
			formatMatch: function(row, i, max) {
				return row.firstname+' '+row.company_or_lastname;
			},
			formatResult: function(row) {
				return row.firstname+' '+row.company_or_lastname;
			}
		});
		<?php } ?>

	<?php if(isset($countries)){ ?>
		$(".country-input").autocomplete(country, {
			minChars: 0,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.country;
			},
			formatMatch: function(row, i, max) {
				return row.country;
			},
			formatResult: function(row) {
				return row.country;
			}
		});
	<?php }?>

	<?php if(isset($Custom_list_1)){ ?>
	$(".first-field").autocomplete(custom1, {
		minChars: 0,
		autoFill: false,
		formatItem: function(row, i, max) {
			return row.custom_list_name;
		},
		formatMatch: function(row, i, max) {
			return row.custom_list_name;
		},
		formatResult: function(row) {
			return row.custom_list_name;
		}
	});
	<?php }?>

	<?php if(isset($Custom_list_2)){ ?>
	$(".second-field").autocomplete(custom2, {
		minChars: 0,
		autoFill: false,
		formatItem: function(row, i, max) {
			return row.custom_list_name_second;
		},
		formatMatch: function(row, i, max) {
			return row.custom_list_name_second;
		},
		formatResult: function(row) {
			return row.custom_list_name_second;
		}
	});
	<?php }?>

	<?php if(isset($Custom_list_3)){ ?>
	$(".third-field").autocomplete(custom3, {
		minChars: 0,
		autoFill: false,
		formatItem: function(row, i, max) {
			return row.custom_list_name_third;
		},
		formatMatch: function(row, i, max) {
			return row.custom_list_name_third;
		},
		formatResult: function(row) {
			return row.custom_list_name_third;
		}
	});
	<?php }?>

	<?php if(isset($sub_job)){ ?>
	$(".sub_job").autocomplete(sub_job, {
		minChars: 0,
		autoFill: false,
		formatItem: function(row, i, max) {
			return row.sub_job_number+' : '+row.sub_job_name;
		},
		formatMatch: function(row, i, max) {
			return row.sub_job_number+' : '+row.sub_job_name;
		},
		formatResult: function(row) {
			return row.sub_job_number+' : '+row.sub_job_name;
		}
	});
	<?php }?>
});

	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		var click = $clicked.attr('id');
		switch (click)
		{
			/**For Sales Arrow**/
			case "openNamesPopup":
			case "openCommentPopup":
			case "openPaymentPopup":
			case "openShippingPopup":
			case "openReferalPopup":
			case "openItemPopup":
			case "openJobPopup":
			case "openAccountPopup":
			case "openActivityPopup":
			case "openCustNamesPopup":
			/**For Customers Arrow**/
			case "openNamesPopup":
			case "openIncomeAccountPopup":
			case "opencustomPopup1":
			case "opencustomPopup2":
			case "opencustomPopup3":
			case "openCountryPopup":
			/**For Jobs Arrow**/
			case "openLinkedPopup":
			case "opensubjobPopup":
			{
				$clicked.parent().find('.ac_input').focus().click();
				$clicked.parent().find('.ac_input').click().focus();
				break; 
			}
		}
	});
</script>

<script type='text/javascript'>

/**
 * Name			:	fillOtherText
 * Description	:	Autocomplete Various Fields in 
 * 					sales/customers/jobs on select/click of popup
 * Params		:	selected text of the popup
 * **/

function fillOtherText(selected){
	clicked_value = clicked_class.attr('popup_id');
	if(clicked_value == undefined || clicked_value== null){
		clicked_value	=	$(clicked_arrow).parent().find('input').attr('popup_id');
	}
	switch(clicked_value){		/**START OF SWITCH STATEMENT**/
	case 'customer':		
		var customer_salesperson_record = selected['RecordID5'];
		$('#sales_person_id').val(customer_salesperson_record);
		var customer_id				= selected['id'];
		var customer_address1		= selected['street1'];
		var customer_address2		= selected['street2'];
		var customer_address_city	= selected['city'];
		var customer_address_state	= selected['state'];
		var customer_address_zip	= selected['zip'];
		var customer_address_country= selected['country_name'];
		var customer_payment_method	= selected['payment_method'];
		var customer_payment_type	= selected['payment_type'];

		if(customer_address1 == '' && customer_address2 != ''){
			customer_address1 = customer_address2;
			customer_address2 = '';
		}
		if(customer_address1 == '' && (customer_address_city != '' || customer_address_state != '' || customer_address_country !='' || customer_address_zip != '')){
			customer_address1 = customer_address_city+' '+customer_address_state+' '+customer_address_country+' - '+customer_address_zip;
			customer_address_city = '';customer_address_state='';customer_address_country='';customer_address_zip='';
		}
		if(customer_address2 == '' && (customer_address_city != '' || customer_address_state != '' || customer_address_country !='' || customer_address_zip != '')){
			customer_address2 = customer_address_city+' '+customer_address_state+' '+customer_address_country+' - '+customer_address_zip;
			customer_address_city = '';customer_address_state='';customer_address_country='';customer_address_zip='';
		}

		var customer_tax_code				= selected['tax_code'];
		var customer_tax_percentage			= selected['percentage'];
		var customer_tax_record_id 			= selected['tax_record_id'];
		var customer_freight_tax_percentage	= selected['freight_percentage'];
		var customer_freight_tax_code		= selected['freight_tax_code'];
		var customer_freight_record_id		= selected['freight_tax_record_id'];
		var use_customer_tax_code			= selected['use_customer_tax_code'];
		if(use_customer_tax_code == ''){
			use_customer_tax_code = '0';
		}
		
		if(customer_id != null){
			$('#customer').val(customer_id);
		}
		if(customer_tax_code != null){						// adding tax info with customer info.
			$('#customer_taxcode').val(customer_tax_code);
		} else {
			$('#customer_taxcode').val('');
		}
		if(customer_tax_percentage != null){
			$('#customer_taxpercentage').val(customer_tax_percentage);
		} else {
			$('#customer_taxpercentage').val(0);
		}
		if(customer_tax_record_id != null){
			$('#customer_tax_record_id').val(customer_tax_record_id);
		}														// customer tax info ends
		if(customer_address1 !=null){
			$('#address1').val(customer_address1);
		}
		if(customer_address2 !=null || customer_address_city !=null){
			$('#address2').val(customer_address2);
		}
		if(customer_address_state != null || customer_address_zip!= null || customer_address_country!= null){
			$('#address3').val(customer_address_city+' '+customer_address_state+' '+customer_address_country+' '+customer_address_zip);
		}
		if(customer_payment_method != null){
			$('.payment_input').val(customer_payment_method);
			$('.payment_val').val(customer_payment_method);
			$('.payment_type').val(customer_payment_type);
		}
		<?php if(isset($country)&&($country == AUSTRALIA || $country == NZ || $country == UK)){ // AUS ?>
			$('.use_customer_tax_code').val(use_customer_tax_code);
		<?php } else { ?>
			$('.use_customer_tax_code').val(0);
		<?php 	}?>
		//Default Tax for Sales form and Freight 
		<?php if(isset($country)&&$country == USA){ // US ?>
			// enter global TAX
			var tax_str	=	'$&nbsp;0.00&nbsp;&nbsp; 0%'; // TAX format
			var tax_val = 	0;
			if(customer_tax_code	!== null && customer_tax_percentage	 !== null){
				// hidden fields -> I think we will not require these hidden fields.
				$('.tax_input').html('$&nbsp;0.00&nbsp;&nbsp;'+customer_tax_percentage+'%&nbsp'+customer_tax_code);
				$('#tax_percentage').val(customer_tax_percentage);
				$('#tax_code').val(customer_tax_code);
				// there is no need to have a parent tax information .. may be we remove the above 2 fields.
				$('.tax_applied_percentage').each(function(){ // item taxes
					console.log('customer_tax_percentage: '+customer_tax_percentage);
					$(this).val(customer_tax_percentage);
					// set the tax record id of item from customer.
					$(this).parent().find('.tax_applied_record_id').val(customer_tax_record_id);
				});
				// freight taxes info
				$('.tax_applied_percentage_freight').val(customer_tax_percentage);
				$('.freight_tax_record_id').val(customer_tax_record_id);
			} else {
				// hidden fields -> I think we will not require these hidden fields.
				$('.tax_input').html('$&nbsp;0.00&nbsp;&nbsp; 0%');
				$('#tax_percentage').val(0);
				$('#tax_code').val('');
				
				$('.tax_applied_percentage').each(function(){ // item taxes
					console.log('customer_tax_percentage: '+customer_tax_percentage);
					$(this).val(0);
					// set the tax record id of item from customer.
					$(this).parent().find('.tax_applied_record_id').val(0);
				});
				// freight taxes info
				$('.tax_applied_percentage_freight').val(0);
				$('.freight_tax_record_id').val(0);
				
			}
		<?php } else { // Non-US ?> 
			$('.tax_applied_percentage_freight').val(customer_freight_tax_percentage);
			$('.freight_tax_record_id').val(customer_freight_record_id);
			if(customer_freight_tax_code	!== null && customer_freight_tax_percentage	 !== null){
				$('.tax_applied_freight').html(customer_freight_tax_code);
			}else {
				$('.tax_applied_freight').html('');
			}
			<?php if(isset($country)&& ($country == AUSTRALIA || $country == UK || $country == NZ)){ // AUS ?>
			if(use_customer_tax_code == 1){		// if use_customer_tax_code is true
				$('.name-salesItem1').each(function(){ 
					$(this).find('.tax_applied_percentage').val(customer_tax_percentage);
					$(this).find('.tax_applied_record_id').val(customer_tax_record_id);
					$(this).find('.tax_applied').text(customer_tax_code);
				});
			} else {
				
				$('.tax_applied:visible').each(function(){		// use_customer_tax_code = false and on changing the customer name
					var code='';
					var r_id='';
					var percent='';
					if($('#sale_type').val() == 1 || $('#sale_type').val() == 2 || $('#sale_type').val() == 3) { // for items
						var id	=	$(this).parents().find('.items-value').val();
						if(id != 'undefined' && id != null && id != ''){
							$.ajax({
								type: 'POST',
								url:  '/sales/get_tax_values_on_customer_change/'+id+'/items',
								async: false,
								dataType: 'json',
								success:function(r){
									if(r!= null) {
										code = (r.tax_code);
										r_id = (r.tax_when_sold_non_us);
										percent = (r.percentage);
									}
								}
							});
						}
					} else if($('#sale_type').val() == 4 || $('#sale_type').val() == 5 || $('#sale_type').val() == 6) { // for service
						var id	=	$(this).parents().find('.account-value').val();
						if(id != 'undefined' && id != null && id != ''){
							$.ajax({
								type: 'POST',
								url:  '/sales/get_tax_values_on_customer_change/'+id+'/accounts',
								async: false,
								dataType: 'json',
								success:function(r){
								console.log(r);
									if(r!= null) {
										code = (r.tax_code);
										r_id = (r.TaxCodeRecordID);
										percent = (r.percentage);
									}
								}
							});
						}
					}
					else { // for time-billing
						var id	=	$(this).parents().find('.activity_value').val();
						if(id != 'undefined' && id != null && id != ''){
							$.ajax({
								type: 'POST',
								url:  '/sales/get_tax_values_on_customer_change/'+id+'/activities',
								async: false,
								dataType: 'json',
								success:function(r){
								console.log(r);
									if(r!= null) {
										code = (r.tax_code);
										r_id = (r.TaxCodeRecordID);
										percent = (r.percentage);
									}
								}
							});
						}
					}
					$(this).text(code);
					$(this).parent().find('#item_tax_record_id').val(r_id);
					$(this).parent().find('#item_tax_percentage').val(percent);
				});
			}
		<?php }?>
		<?php } ?>		
		calculate_sub_total();

		// For Terms
		var payment_is_due			=	selected['payment_is_due'];
		var balance_due_days		=	selected['balance_due_days'];
		var discount_days			=	selected['discount_days'];
		var early_payment_discount	=	selected['early_payment_discount'];
		var late_payment_charge		=	selected['late_payment_charge'];
		var payment_is_due_text		=	"";
		var terms					=	"";
		switch (payment_is_due){
			case '0' :	payment_is_due_text	=	"C.O.D.";
						terms				=	"C.O.D.";
			break;
			case '1' :	payment_is_due_text	=	"Prepaid";
						terms				=	"Prepaid";
			break;
			case '2' :	payment_is_due_text	=	"In a Given Number of Days";
						terms				=	"NET"+' '+balance_due_days;
			break;
			case '3' :	payment_is_due_text	=	"On a Day of the Month";
						terms				=	"NET"+' '+balance_due_days;
			break;
			case '4' :	payment_is_due_text	=	"Number of Days after End of Month";
						terms				=	"NET"+' '+balance_due_days;
			break;
			case '5' :	payment_is_due_text	=	"Day of Month after End of Month";
						terms				=	"NET"+' '+balance_due_days;
			break;
			default:	payment_is_due_text	=	"None";
						terms				=	"None";
			break;
		}
		$('#terms').val(terms);
		$('.payment_is_due_hidden_value').val(payment_is_due);
		$('#payment_is_due').val(payment_is_due_text);
		$('#balance_due_days').val(balance_due_days);
		$('#discount_days').val(discount_days);
		$('#early_payment_discount').val(early_payment_discount);
		$('#late_payment_charge').val(late_payment_charge);
		break;

		case 'items':	
			/***For Items Starts***/
			var id				= 	selected['item_id'];
			var description		=	selected['item_description'];
			var name			=	selected['item_name'];
			var is_description	=	selected['is_description_used'];
			var selling_price	=	selected['selling_price'];
			var taxcode			=	selected['tax_code'];
			var taxpercentage	=	selected['percentage'];
			var sub_tax_code	= 	selected['sub_tax_code'];
			var tax_when_sold_us= 	selected['tax_when_sold_us'];
			var tax_when_sold_non_us= 	selected['tax_when_sold_non_us'];
			
			var item_str='', desp_str='';
			if($('.item-container').is(':visible')){
				item_str='.item_val_';
				desp_str='.description_';
			}
			else if($('.item-container-phone').is(':visible')){
				item_str='.item_val_phone_';
				desp_str='.descriptionPhone_';
			}
	
			// setter statements
			if(id != null){											$(item_str+click).val(id);		}
			if(is_description == '0' && name != null){				$(desp_str+click).val(name);		}
			else if (is_description == '1' && description != null){	$(desp_str+click).val(description);		}
			//if(sub_tax_code!=null){									$('.tax_applied').html(sub_tax_code);		}
			
			if($.Company.COUNTRY===$.USA){// US
				console.log('taking tax from customer');
				if($('#customer_taxcode').val()!=null){
					taxcode = $('#customer_taxcode').val();
				} else { // default value
					taxcode = '';
				}
				if($('#customer_taxpercentage').val()!=null){
					taxpercentage = $('#customer_taxpercentage').val();
				} else { // default value
					taxpercentage = 0;
				}
				if($('#customer_tax_record_id').val()!=null){
					tax_when_sold_non_us = $('#customer_tax_record_id').val();
				} else { // default value
					tax_when_sold_non_us = 0;
				}
				
				// update the TAX-Checked Value.
				if(tax_when_sold_us == 1){
					$('.additional_content_'+click).find('.tax-enable-disable').attr("src","/media/images/tt-new/enable.png");
					$('.additional_content_'+click).find('.tax-chk').removeClass('tax-disable');
					$('.additional_content_'+click).find('.tax_check_value').val('1');
				} else {
					$('.additional_content_'+click).find('.tax-enable-disable').attr("src","/media/images/tt-new/disable.png");
					$('.additional_content_'+click).find('.tax-chk').addClass('tax-disable');
					$('.additional_content_'+click).find('.tax_check_value').val('0');
				}
				
			}else{
				if(($.Company.COUNTRY===$.AUSTRALIA || $.Company.COUNTRY===$.UK || $.Company.COUNTRY===$.NZ) && $('.use_customer_tax_code').val() == 1){// AUS - considering the case for use customer taxes
					taxcode	=	$('#customer_taxcode').val();
					taxpercentage	=	$('#customer_taxpercentage').val();
					tax_when_sold_non_us	=	$('#customer_tax_record_id').val();
				}
				console.log('taking tax from item');
				console.log('item taxcode: '+taxcode+', item taxpercentage: '+ taxpercentage+'tax_when_sold_non_us: '+tax_when_sold_non_us);
				if(taxcode!=null){ // do-nothing
				}else{
					taxcode='';
				}
				if(taxpercentage!=null){// do-nothing
				}
				else{
					taxpercentage=0;
				}
				if(tax_when_sold_non_us!=null){// do-nothing
				} else {
					tax_when_sold_non_us = 0;
				}
			}
			
			$('.additional_content_'+click).find('.tax_applied').html(taxcode);
			$('.additional_content_'+click).find('.tax_applied_percentage').val(taxpercentage);
			$('.additional_content_'+click).find('.tax_applied_record_id').val(tax_when_sold_non_us);
			
			if(selling_price != null){
				selling_price	=	parseFloat(selling_price).toFixed(2);
				$('.price'+click).val(selling_price);
			}
	
			calculate_item_total1(clicked_class);
			if(click>1){
				resize_tag($('#price'+click));
			} else {
				resize_input_box();
			}
			break;
	
		case 'jobs':
			var sales_jobs = selected['job_id'];
			if(sales_jobs != undefined){
				if($('.item-container-phone').is(':visible')){
					$('.job_val_phone_'+click).val(sales_jobs);
				}
				$('.job_val_'+click).val(sales_jobs);
			}
			break;
	
		case 'account':
			var sales_accounts 		= selected['account_id'];
			var account_description	= selected['account_description'];
			var account_name		= selected['account_name'];
			var taxcode			=	selected['tax_code'];
			var taxpercentage	=	selected['percentage'];
			var tax_record_id	=	selected['TaxCodeRecordID'];
			
			if(location.href.indexOf('sales')!=-1){		// Customer is also similar but, we are not
				if($.Company.COUNTRY===$.USA){// US
					console.log('taking tax from customer');
					if($('#customer_taxcode').val()!=null){
						taxcode = $('#customer_taxcode').val();
					} else { // default value
						taxcode = '';
					}
					if($('#customer_taxpercentage').val()!=null){
						taxpercentage = $('#customer_taxpercentage').val();
					} else { // default value
						taxpercentage = 0;
					}
					if($('#customer_tax_record_id').val()!=null){
						tax_record_id = $('#customer_tax_record_id').val();
					} else { // default value
						tax_record_id = 0;
					}
	
				}else{
					if(($.Company.COUNTRY===$.AUSTRALIA || $.Company.COUNTRY===$.UK || $.Company.COUNTRY===$.NZ) && $('.use_customer_tax_code').val() == 1){// AUS - considering the case for use customer taxes
						taxcode	=	$('#customer_taxcode').val();
						taxpercentage	=	$('#customer_taxpercentage').val();
						tax_record_id	=	$('#customer_tax_record_id').val();
					}
					console.log('taking tax from accounts');
					console.log('accounts taxcode: '+taxcode+', accounts taxpercentage'+ taxpercentage);
					
					if(taxcode!=null){ // do-nothing
					}else{
						taxcode='';
					}
					if(taxpercentage!=null){// do-nothing
					}
					else{
						taxpercentage=0;
					}
					if(tax_record_id!=null){// do-nothing
					} else {
						tax_record_id = 0;
					}
				}
				$('.additional_content_'+click).find('.tax_applied_record_id').val(tax_record_id);
				$('.additional_content_'+click).find('.tax_applied').html(taxcode);
				$('.additional_content_'+click).find('.tax_applied_percentage').val(taxpercentage);
				if(sales_accounts != undefined){
					$('.account_val_'+click).val(sales_accounts);
					if(account_description != ""){
					$('.service_description_'+click).val(account_description);
					} else {
						$('.service_description_'+click).val(account_name);
					}
					if($('.item-container-phone').is(':visible')){
						$('.account_val_phone_'+click).val(sales_accounts);
						if(account_description != ""){
							$('.service_description_phone_'+click).val(account_description);
						} else {
							$('.service_description_phone_'+click).val(account_name);
						}
					}
				}
			}
			calculate_service_total(clicked_class);
			break;
			
		case 'activity':
			var sales_activity = selected['act_id'];
			var taxcode			=	selected['tax_code'];
			var taxpercentage	=	selected['percentage'];
			var tax_record_id	=	selected['TaxCodeRecordID'];
			var tax_when_sold_us= 	selected['tax_when_sold_us'];
			
			if($.Company.COUNTRY===$.USA){// US
				console.log('taking tax from customer');
				if($('#customer_taxcode').val()!=null){
					taxcode = $('#customer_taxcode').val();
				} else { // default value
					taxcode = '';
				}
				if($('#customer_taxpercentage').val()!=null){
					taxpercentage = $('#customer_taxpercentage').val();
				} else { // default value
					taxpercentage = 0;
				}
				if($('#customer_tax_record_id').val()!=null){
					tax_record_id = $('#customer_tax_record_id').val();
				} else { // default value
					tax_record_id = 0;
				}
				
				// update the TAX-Checked Value.
				if(tax_when_sold_us == 1){
					$('.additional_content_'+click).find('.tax-enable-disable').attr("src","/media/images/tt-new/enable.png");
					$('.additional_content_'+click).find('.tax-chk').removeClass('tax-disable');
					$('.additional_content_'+click).find('.tax_check_value').val('1');
				} else {
					$('.additional_content_'+click).find('.tax-enable-disable').attr("src","/media/images/tt-new/disable.png");
					$('.additional_content_'+click).find('.tax-chk').addClass('tax-disable');
					$('.additional_content_'+click).find('.tax_check_value').val('0');
				}
	
			}else{
				if(($.Company.COUNTRY===$.AUSTRALIA || $.Company.COUNTRY===$.UK || $.Company.COUNTRY===$.NZ) && $('.use_customer_tax_code').val() == 1){// AUS - considering the case for use customer taxes
					taxcode	=	$('#customer_taxcode').val();
					taxpercentage	=	$('#customer_taxpercentage').val();
					tax_record_id	=	$('#customer_tax_record_id').val();
				}
				console.log('taking tax from accounts');
				console.log('accounts taxcode: '+taxcode+', accounts taxpercentage'+ taxpercentage);
				
				if(taxcode!=null){ // do-nothing
				}else{
					taxcode='';
				}
				if(taxpercentage!=null){// do-nothing
				}
				else{
					taxpercentage=0;
				}
				if(tax_record_id!=null){// do-nothing
				} else {
					tax_record_id = 0;
				}
			}
			
			$('.additional_content_'+click).find('.tax_applied_record_id').val(tax_record_id);
			$('.additional_content_'+click).find('.tax_applied').html(taxcode);
			$('.additional_content_'+click).find('.tax_applied_percentage').val(taxpercentage);
			
			if(sales_activity != undefined){
				$('.timebillingviewItem-1-field .account_val_'+click).val(sales_activity);
				if($('.item-container-phone').is(':visible')){
					$('.account_val_phone_'+click).val(sales_activity);
				}
			}
			var sales_activity_name				=	selected['activity_name'];
			var sales_activity_description		=	selected['description'];
			var sales_activity_is_description	=	selected['use_description_on_sales'];
			var sales_activity_rate				=	selected['activity_rate'];
			
			if((sales_activity_is_description != '1' || sales_activity_is_description == null) && sales_activity_name != null){
				if($('.item-container-phone').is(':visible')){
					$('.tb_description_phone_'+click).val(sales_activity_name);
					$('.activity_rate_phone_'+click).val(sales_activity_rate);
				}else{
					$('.tb_description_'+click).val(sales_activity_name);
					$('.activity_rate_'+click).val(sales_activity_rate);
				}
			}
			else if(sales_activity_is_description == '1' && sales_activity_is_description != null && sales_activity_description != null){
				if($('.item-container-phone').is(':visible')){
					$('.tb_description_phone_'+click).val(sales_activity_description);
					$('.activity_rate_phone_'+click).val(sales_activity_rate);
				}else{
					$('.tb_description_'+click).val(sales_activity_description);
					$('.activity_rate_'+click).val(sales_activity_rate);
				}
			}
			calculate_tb_total1(clicked_class);
			if(click>1){
					resize_tag($('#rate'+click));
			} else {
				resize_input_box();
			}
			break;
			
		case 'salesperson':
			if(location.href.indexOf('sales')!=-1 || location.href.indexOf('customer')!=-1){
				var sales_salesperson	= selected['firstname']+' '+selected['CompanyOrLastName']+'--'+selected['RecordID5'];
				if(sales_salesperson != 'undefined--undefined' ) {
					$('.emp_val').val(sales_salesperson);
				}
			}
			else {
				var jobs_linked	=	selected['record_id'];
				if(jobs_linked != null){
					$('#linked_customer').val(jobs_linked);
				}
			}
			break;
			
		case 'comment':
			var sales_comment = selected['information_name4'];
			if(sales_comment != null) {
				$('.comment_val').val(sales_comment);
			}
			break;
			
		case 'referal':
			var sales_referal = selected['information_name1'];
			if(sales_referal != null) {
				$('.reff_val').val(sales_referal);
			}
			break;
			
		case 'payment':
			var sales_payment 	= selected['information_name2'];
			var payment_type 	= selected['payment_type2'];
			if(sales_payment != null) {
				$('.payment_val').val(sales_payment);
				$('.payment_type').val(payment_type);
			}
			break;
			
		case 'shipping':
			var sales_shipping = selected['information_name3'];
			if(sales_shipping != null) {
				$('.shipping_val').val(sales_shipping);
			}
			break;

		case 'sub_job':
			var sub_job	=	selected['sub_job_number'];
			if(sub_job != null) {
				$('.sub_job').val(sub_job);
			}
			break;
	}
	/**END OF SWITCH STATEMENT**/

	clicked_arrow.parent().find('input.popup_validate').focus();
	clicked_arrow.parent().find('.ac_results').hide();
}

</script>

<div class="terms_popup" style="display:none;" >
    <div class="layout-names">
    	<label class="layout-label-names term_label width-120 dull">Terms</label>
   		 <input type="button" value="Save" class="save-btn save_terms" />
   		 <input type="button" value="Back" class="cancel-btn back_terms" style="display:none;" />
    </div>
   <div class="popup-items2 white-background overflow-none" style="height:200px;">
	 	<div class="terms-popup-list first names-list-row" >
	    	<label class="terms_fields heavy left">Payment Is Due</label>
	    	<img class="retina-arrow service-arrow-left right pad-right-1 pointer pad-left-2" src="/media/images/tt-new/timesheet-arrow-left.png">
	    	<input type="text" readonly id="payment_is_due" name="payment_is_due" value="<?php if(isset($payment_is_due)) echo $payment_is_due; else '';?>" class="terms_textfield width-120 left pointer" />
	    	<input type="hidden" class="payment_is_due_hidden_value" value="" />
	    </div>
	    <div class="terms-popup-list first names-list-row" >
	    	<label class="terms_fields heavy left">Balance Due Days</label>
	    	<input type="text" id="balance_due_days" name="balance_due_days" value="<?php if(isset($balance_due_days)) echo $balance_due_days; else '0';?>" class="terms_textfield width-120 left" />
	    </div>
	    <div class="terms-popup-list first names-list-row" >
	    	<label class="terms_fields heavy left">Discount Days</label>
	    	 <input type="text" id="discount_days" name="discount_days" value="<?php if(isset($discount_days)) echo $discount_days; else '0';?>" class="terms_textfield left width-120" /> 
	    </div>
	    <div class="terms-popup-list first names-list-row" >
	    	<label class="terms_fields heavy left">Discount for Early Payment</label>
	    	<input type="text" id="early_payment_discount" name="early_payment_discount" value="<?php if(isset($early_payment_discount)) echo $early_payment_discount; else '0.00';?>" class="terms_textfield1 left" onkeyup="resize_input_box();"/>
	    	<span class="dark left mar-right-4 mar-top-9">%</span>
	    </div>
	    <div class="terms-popup-list first names-list-row no-bor-bottom" >
	    	<label class="terms_fields heavy left">Charge for Late Payment</label>
	    	<input type="text" id="late_payment_charge" name="late_payment_charge" value="<?php if(isset($late_payment_charge)) echo $late_payment_charge; else '0.00';?>" class="terms_textfield1 left" onkeyup="resize_input_box();" /> 
	    	<span class="dark left mar-right-4 mar-top-9">%</span>
	    </div>
	</div>
	<div class="terms_second_content white-background overflow-none" style="height:200px;display:none;">
		<div class="terms-popup-list second_select_terms white-background" onclick="terms_list_select(this);" >
			C.O.D.
		<input type="hidden" value="0">
		</div>
		<div class="terms-popup-list second_select_terms white-background" onclick="terms_list_select(this);" >
			Prepaid
			<input type="hidden" value="1">
		</div>
		<div class="terms-popup-list second_select_terms white-background" onclick="terms_list_select(this);" >
			In a Given Number of Days
			<input type="hidden" value="2">
		</div>
		<div class="terms-popup-list second_select_terms white-backgroundt" onclick="terms_list_select(this);" >
			On a Day of the Month
			<input type="hidden" value="3">
		</div>
		<div class="terms-popup-list second_select_terms white-background" onclick="terms_list_select(this);" >
			Number of Days after End of Month
			<input type="hidden" value="4">
		</div>
		<div class="terms-popup-list second_select_terms no-bor-bottom white-background" onclick="terms_list_select(this);" >
			Day of Month after End of Month
			<input type="hidden" value="5">
		</div>
	</div>
</div>
