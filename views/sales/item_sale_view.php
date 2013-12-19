<script type="text/javascript">
var sale_array		= <?php echo json_encode(array_merge($sale[0],$company_info[0]));?>;
var sale_item		= <?php echo json_encode($sale_item);?>;
var payment			= <?php echo json_encode(isset($payment[0])?$payment[0] : "");?>;
</script>
<?php echo $header;	?>
<?php if(isset($country) && $country != USA) { // for non us?>
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
        //calculate_sub_total();
    });
    $('select#select_tax_inclusive').switchify({ on: '1', off: '0' }).data('switch').unbind();
    $('.ui-switch').css("top", "0px");
    $('.amt_line2').css("top-padding", "0px !important");
});
</script>
<?php } ?>
<input type="hidden" value="<?php echo $sale[0]['id'];?>" name="sale_id" id='sale_id' />
<div class="row-fluid margin-2" id="row-fluid" >
	<div class="name-customer margin-left-1 margin-right-1" style="background:#fef1e4;border:none;">
		<label class="customer-name-lbl"><?php echo $sale[0]['firstname'].' '.$sale[0]['company_or_lastname'];?></label>
		<label class="view-date-label"><?php echo date("F d, Y",strtotime($sale[0]['selected_date']));?></label>
		<img src="<?php echo SITEURL?>/media/images/tt-new/stamp.png" class="stamp-image" style="float:right;">
	</div>
	<div class="mobile-icons-cont" style="display:none;">
		<a href="javascript:void(0)" onclick="delete_sales_cofirm(this.id, 2, '<?php echo $sale[0]['id'];?>');" class="left mobile-icons">
			<div class="delete-btn-mobile">
			</div>
		</a>
		<a href="<?php echo SITEURL?>/sales/edit/<?php echo $sale[0]['id'];?>" class="left mobile-icons">
			<div class="edit-btn-mobile">
			</div>
		</a>
		<a href="javascript:void(0)" onclick="open_sales_popup(this.id, 3)" class="left mobile-icons">
			<div class="add-btn-mobile">
			</div>
		</a>
		<div class="up-down-bar-mobile">
			<a href="##" id="up-arr-mobile"></a>
			<a href="###" id="down-arr-mobile"></a>
		</div>
	</div>
	<div class="info-column-1 margin-left-1" style="margin-top:10px">
		<div class="info">
			<label class="line1 width-120">Shipping Address</label>
			<label class="line2 mar-left-8 left"><?php echo $sale[0]['address1']?></label>
		</div>
		<div class="info">
			<label class="line1 width-120"></label>
			<label class="line2 mar-left-8 left"><?php echo $sale[0]['address2']?></label>
		</div>
		<div class="info">
			<label class="line1 width-120"></label>
			<label class="line2 mar-left-8 left"><?php echo $sale[0]['address3']?></label>
		</div>
	</div>
	<div class="info-column-2 margin-right-1" style="margin-top:10px" >
		<div class="info">
			<label class="line1 width-120">Sale #</label>
			<label class="line2 mar-left-8 left"><?php echo $sale[0]['sale_number']?></label>
		</div>
		<div class="info">
			<label class="line1 width-120">Customer PO #</label>
			<label class="line2 mar-left-8 left"><?php echo $sale[0]['customer_po']?></label>
		</div>
		<div class="info">
			<label class="line1 width-120">Terms</label>
			<label class="line2 mar-left-8 left"><?php echo $sale[0]['terms']?></label>
		</div>
	</div>	
	<!-- Sales Item List starts -->
	<div class="item-list margin-left-1 margin-right-1">
		<!--Item Heading starts-->
		<div class="name-salesItem">
			<div class="salesItem7">
				<div class="salesItem6">
					<div class="salesItem5">
						<div class="salesItem4">
							<div class="salesItem3">
								<div class="salesItem2">
									<div class="salesItem-1-field"><label class="salesItem-name-field pad-left-18 font-color">Item #</label></div>
									<div class="salesItem-2-field"><label class="salesItem-name-field pad-left-18 font-color">Item Description</label></div>
									<div class="salesItem-3-field"><label class="salesItem-name-field pad-left-18 font-color">Quantity</label></div>
									<div class="salesItem-4-field"><label class="salesItem-name-field pad-right-18 font-color">Price</label></div>
									<div class="salesItem-5-field"><label class="salesItem-name-field pad-right-18 font-color">Total</label></div>
									<div class="salesItem-6-field"><label class="salesItem-name-field pad-left-18 font-color">Job</label></div>
									<div class="salesItem-7-field"><label class="salesItem-name-field pad-left-18 font-color"><?php  echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'taxName');  ?></label></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Item Heading ends -->		
		<!-- Item Values Starts Loop Section -->
		<div id="item-part">
		<?php $subtotal = 0;?>
		<?php foreach($sale_item as $item) {
			if($country == USA){ // For US
			// Do Nothing
			} else { // For Non-US
				if(isset($sale[0]['is_tax_inclusive'])&&$sale[0]['is_tax_inclusive']=='1'){
					$item['total']			+= (($item['total']*$item['percentage'])/100);
					$item['total']			=	number_format((float)$item['total'], 2, '.', '');
					$subtotal				+=	$item['total'];
				}
			}
		?>
			<div class="name-salesItem1 names_fields_<?php echo $item['id']?>" id="item-section-0">
				<div class="salesItem7">
					<div class="salesItem6">
						<div class="salesItem5">
							<div class="salesItem4">
								<div class="salesItem3">
									<div class="salesItem2">
										<div class="salesItem-1-field"><label class="salesItem-name-field pad-left-18 font-color1"><?php echo $item['item_number']?></label></div>
										<div class="salesItem-2-field"><label class="salesItem-name-field pad-left-18 font-color1"><?php echo $item['item_name']?></label></div>
										<div class="salesItem-3-field"><label class="salesItem-name-field pad-left-18 font-color1"><?php echo $item['quantity']?></label></div>
										<div class="salesItem-4-field"><label class="salesItem-name-field pad-right-18 font-color1">$ <?php echo $item['price']?></label></div>
										<div class="salesItem-5-field"><label class="salesItem-name-field pad-right-18 font-color1">$ <?php echo $item['total']?></label></div>
										<div class="salesItem-6-field"><label class="salesItem-name-field pad-left-18 font-color1"><?php echo $item['job_name']?></label></div>
										<div class="salesItem-7-field">
										<?php if( isset($country) && $country == USA) { // for US?>  
											<label class="salesItem-name-field pad-left-10 font-color1">
												<input type="hidden" name="tax_check[]" 
												<?php 
													if($item['apply_tax'] == "1") {
												        $source="/media/images/tt-new/enable.png";
    												} else {
    													$source="/media/images/tt-new/disable.png";
    												}
												?> />
												<img src="<?php echo $source;?>">
											</label>
										<?php } else { // for Non US ?>
												<span class="tax_applied"><?php echo $item['tax_code']; ?></span>    												
										<?php }?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
		<!-- Item Values Ends Loop Section -->
	</div>
	<!-- Mobile Version Starts -->
	<?php  
		$subtotal =0;
		foreach($sale_item as $item) {
			if($country == USA){ // For US
			// Do Nothing
			} else { // For Non-US
				if(isset($sale[0]['is_tax_inclusive'])&&$sale[0]['is_tax_inclusive']=='1'){
					$item['total']			+= (($item['total']*$item['percentage'])/100);
					$item['total']			=	number_format((float)$item['total'], 2, '.', '');
					$subtotal				+=	$item['total'];
				}
			}
	?>
	<div class="clear"></div>
	<div  id="item-section-0" >
		<div id="sales-phone-version-<?php echo $item['id'];?>" class="item-view toggle-inactive margin-left-1 margin-right-1" style="display:none;" onclick="toggleCustomerSales(this,'<?php echo $item['id'];?>')">
			<div id="arrow-image-field-<?php echo $item['id'];?>" class="phone_fields background-arrow" >
				<label class="heavy sales_label_view"><label class="mar-rig-10 dull phone_view_label">Item Number</label><?php echo $item['item_number']?></label>
			</div>
			<div id="rest_hidden_contents_<?php echo $item['id'];?>" class="rest_hidden">
				<div class="phone_fields bor-bottom" >
					<label class="heavy sales_label_view"><label class="mar-rig-10 dull phone_view_label">Item Name</label><?php echo $item['item_name']?></label>
				</div>
				<div class="phone_fields bor-bottom" >
					<label class="heavy sales_label_view"><label class="mar-rig-10 dull phone_view_label">Quantity</label><?php echo $item['quantity']?></label>
				</div>
				<div class="phone_fields bor-bottom" >
					<label class="heavy right_text sales_label_view"><label class="mar-rig-10 dull phone_view_label">Price</label>$ <?php echo $item['price']?></label>
				</div>
				<div class="phone_fields bor-bottom" >
					<label class="heavy right_text sales_label_view"><label class="mar-rig-10 dull phone_view_label">Total</label>$ <?php echo $item['total'];?></label>
				</div>
				<div class="phone_fields bor-bottom" >
					<label class="heavy sales_label_view"><label class="mar-rig-10 dull phone_view_label">Job</label><?php echo $item['job_name']?></label>
				</div>
				<div class="phone_fields" >
					<label class="heavy sales_label_view"><label class="mar-rig-10 dull phone_view_label"><?php echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'taxName'); ?></label>
					<?php if( isset($country) && $country == USA) { // for US?>  
						<label class="salesItem-name-field pad-left-10">
							<input type="hidden" name="tax_check[]" 
							<?php 
								if($item['apply_tax'] == "1") {
							        $source="/media/images/tt-new/enable.png";
								} else {
									$source="/media/images/tt-new/disable.png";
								}
							?> />
							<img src="<?php echo $source;?>">
						</label>
					<?php } else { // for Non US ?>
							<span class="tax_applied"><?php echo $item['tax_code']; ?></span>    												
					<?php }?>
					</label>
				</div> 
			</div>
		</div>
	</div>
	<?php
	}?>
	<!-- Mobile Version Ends -->
	<!-- Sales Item List starts -->	
	<div class="info-column-1 margin-left-1" style="margin-top:10px" >
		<div class="info mar_view">
			<label class="line1 width-120">Salesperson</label>
			<label class="line2 mar-left-8 left"><?php echo $sale[0]['sales_person'];?></label>
		</div>
		<div class="info mar_view">
			<label class="line1 width-120">Comment</label>
			<label class="line2 mar-left-8 left"><?php echo $sale[0]['comment'];?></label>
		</div>
		<div class="info mar_view">
			<label class="line1 width-120">Refferal Source</label>
			<label class="line2 mar-left-8 left"><?php echo $sale[0]['referal_source'];?></label>
		</div>
		<div class="info mar_view">
			<label class="line1 width-120">Payment Method</label>
			<label class="line2 mar-left-8 left"><?php if (isset($sale[0]['payment_method'])&&!empty($sale[0]['payment_method'])) echo $sale[0]['payment_method']; else echo '';?></label>
		</div>
		<?php if($sale_type != "3") {?>
		<div class="info mar_view">
			<label class="line1 width-120">Shipping Method</label>
			<label class="line2 mar-left-8 left"><?php echo $sale[0]['shipping_method'];?></label>
		</div>
		<?php }?>
		<div class="clear"></div>
				
		<div class="email_pdf_view right" id="">
			<img style="display:none;" src="/media/images/tt-new/loader.gif" class="mar-top-9 email_loader"/>
			<input type="button" class="recipt right mar-left-8" onclick="mailRecipt(sale_array,sale_item,payment,'pdf')" value="Download"/>
			<?php if(isset($sale[0]['customer_email']) && !empty($sale[0]['customer_email'])) {?>
			<input type="button" onclick="mailRecipt(sale_array,sale_item,payment,'email')" class="recipt right" value="Email"/>
			<?php } ?>
		</div>
	</div>
	
	<div class="info-column-2 margin-right-1" style="margin-top:10px">
		<div class="info mar_view no-border">
			<label class="line1-view">Subtotal</label>
			<label class="line2 f-left right_align view_subtotal">$ <?php if(isset($sale[0]['is_tax_inclusive'])&&$sale[0]['is_tax_inclusive']=='1'){ echo $sale[0]['subtotal'] = number_format((float)$subtotal, 2, '.', ''); } else {echo $sale[0]['subtotal'];}?></label>
		</div>	
		<?php
		// call the calculate freight function once again from the original value.
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
			} else {
				// do nothing
			}			
		}
	
		if(isset($country) && $country != USA){ // non US					
			?>
			<div class="freight-tax no-pad">
			<div class="info mar_view">
			<label class="line1-view"><?php  echo Kohana::message($_SESSION['CONSTANT_MSG_FILE'], 'taxType');  ?></label>
				<span class="line2 f-left right_align right_align">$ <?php echo number_format($freight_amount,2); ?></span>
				<span class="tax_applied_code_freight_view"><?php if(isset($sale[0]['freight_tax_code'])) { echo $sale[0]['freight_tax_code']; }?></span>
			</div>
			</div>
		<?php } else { // Default should be US ?>
			<div class="info mar_view">
			<label class="line1-view">Freight</label>
			<label class="line2 f-left dull right_align">$ <?php echo number_format($freight_amount,2); ?></label>
			<a href="javascript:void(0);" class="amt_side1 freight_view_chk <?php echo $freight_chk;?>"><img src="/media/images/tt-new/<?php echo $freight_enab;?>.png" class="freight-enable-disable tabindex_field"></a>
			</div>						 
		<?php } ?>
		<?php if (isset($country)&&$country != USA){ // Non-US?>
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
					<span class="amt_ipt1 color-be tabindex_field tax_input number" name="tax_totl" id="tax_totl">$&nbsp;<?php echo number_format($sale[0]['tax_total_amount'],2); ?></span>
				</div>			
			</div>
		<?php } else { // for US ?>
		
			<div class="info mar_view">
			<label class="line1-view">Tax</label>
			<label class="line2 f-left mar-top-neg6 dull right_align">
				<span class="color-be tabindex_field tax_input number" name="tax_totl" id="tax_totl">$&nbsp;<?php echo number_format($sale[0]['tax_total_amount'],2)."&nbsp;&nbsp;".$sale_item[0]['percentage']."%&nbsp;&nbsp;".$sale_item[0]['tax_code'];?></span>
			</label>
			</div>
		<?php } ?>
		<div class="info-paid-today mar_view">
			<label class="line1-view">Total Amount</label>
			<label class="line2 f-left right_align view_total">$ <?php echo $sale[0]['total_payment'];?></label>
		</div>				
		<?php if($sale_type != "3") {?>
		<div class="info margin-details">
			<label class="line1-view">Paid Today</label>
			<label class="line2-paid-today f-left right_align" style="text-align:left;">$ <?php echo $sale[0]['paid_today'];?></label>
			<?php if(isset($is_payment_exist)&&!empty($is_payment_exist)){ ?>
			<img class="payment_checked" src="/media/images/tt-new/enable.png" />
			<?php } 
			if(isset($ccp) && ($ccp == 0 || ($ccp==1 && isset($ach_status)&&$ach_status)) || isset($is_payment_exist)&&!empty($is_payment_exist)){?>
			<div class="details-button">
				<a href="/sales/<?php if(isset($is_payment_exist)&&!empty($is_payment_exist)){echo 'payment_receipt'; } else { echo 'details'; } ?>/<?php echo $sale[0]['id'];?>">
					<label class="details-label"><?php if(isset($is_payment_exist)&&!empty($is_payment_exist)){echo 'History'; } else { echo 'Details'; } ?></label>
				</a>
			</div>								
			<a href="/sales/details/<?php echo $sale[0]['id'];?>" style="display:none;" class="mobile-icons-details" >
				<div class="details-btn-mobile">
				</div>
			</a>
			<?php }?>
		</div>
		<?php }?>
		<div class="double_border">
			<div class="info pad-top-12">
				<label class="line11 only_balance">Balance Due</label>
				<label class="line2 balance-view f-left right_align" id="balance_due" style="font-size:15px;"><span class="mar-dollar">$</span><?php echo $sale[0]['balance'];?></label>
			</div>
		</div>				
		<?php if($sync_status == 0){ ?>
		<div class="edit-delete-buttons-view mar-bot4" id="delete-sale">
		<a href="javascript:void(0);" id="delete1-views" onclick="delete_sales_cofirm('delete-sale', 2, '<?php echo $sale[0]['id'];?>');">
			<div class="delete-btn delete-label" style="height:29px;">Delete</div>
		</a>
		<a href="<?php echo SITEURL?>/sales/edit/<?php echo $sale[0]['id'];?>">
			<div class="edit-btn1 edit-label">Edit</div>
		</a>
		</div>
		<?php }?>
		<div class="clear"></div>
	</div>

</div>

<div class="ending-blue-line" style=""></div>

<script>
$(document).ready(function()
{
	$('.ending-blue-line').css('background','#fef1e4');
	if($(window).width() <= 400) 
	{
		$('.info-column-1').css("width","98%");//42
		$('.info-column-2').css("width","98%");//42		
		$('.edit-delete-buttons').css("padding-left","0");//38
		$('.edit-delete-buttons').css("padding-right","0");//8
		$('.item-list').hide();
		$('.edit-btn').hide();
		$('.item-view').show();
		$('.mobile-icons,.mobile-icons-cont').show();
		$('.mobile-icons-details').show();
		$('.details-button').hide();		
	}
	else if (($(window).width() > 400) && ($(window).width() <= 750)) 
	{
		$('.info-column-1').css("width","98%");//42
		$('.info-column-2').css("width","98%");//42		
		$('.edit-delete-buttons').css("padding-left","0");//38
		$('.edit-delete-buttons').css("padding-right","0");//8
		$('.item-list').hide();
		$('.edit-btn').hide();
		$('.item-view').show();
		$('.mobile-icons,.mobile-icons-cont').show();
		$('.mobile-icons-details').show();
		$('.details-button').hide();	
	}
	else if (($(window).width() > 750) && ($(window).width() < 1000))
	{
		$('.info-column-1').css("width","42%");//42
		$('.info-column-2').css("width","42%");//42		
		$('.edit-delete-buttons').css("padding-left","0");//38
		$('.edit-delete-buttons').css("padding-right","0");//8
		$('.item-list').show();
		$('.edit-btn , .delete-btn').show();
		$('.mobile-icons,.mobile-icons-cont').hide();
		$('.item-view').hide();
		$('.mobile-icons-details').hide();
		$('.details-button').show();
	}
	else if (($(window).width() > 1000) && ($(window).width() < 1050))
	{
		$('.info-column-1').css("width","42%");//42
		$('.info-column-2').css("width","42%");//42		
		$('.edit-delete-buttons').css("padding-left","38%");//38
		$('.edit-delete-buttons').css("padding-right","8%");//8
		$('.item-list').show();
		$('.edit-btn , .delete-btn').show();
		$('.item-view').hide();
		$('.mobile-icons,.mobile-icons-cont').hide();
		$('.mobile-icons-details').hide();
		$('.details-button').show();
	}
	else if (($(window).width() > 1050) && ($(window).width() < 1300))
	{
		$('.info-column-1').css("width","42%");//42
		$('.info-column-2').css("width","42%");//42		
		$('.edit-delete-buttons').css("padding-left","38%");//38
		$('.edit-delete-buttons').css("padding-right","8%");//8
		$('.item-list').show();
		$('.edit-btn , .delete-btn').show();
		$('.item-view').hide();
		$('.mobile-icons,.mobile-icons-cont').hide();
		$('.mobile-icons-details').hide();
		$('.details-button').show();
	}
	else
	{
		$('.info-column-1').css("width","42%");//42
		$('.info-column-2').css("width","42%");//42		
		$('.edit-delete-buttons').css("padding-left","38%");//38
		$('.edit-delete-buttons').css("padding-right","8%");//8
		$('.item-list').show();
		$('.edit-btn , .delete-btn').show();
		$('.mobile-icons,.mobile-icons-cont').hide();
		$('.item-view').hide();
		$('.mobile-icons-details').hide();
		$('.details-button').show();
	}
	
	$(window).resize(function()
	{
		if($(window).width() <= 400) 
		{
			$('.info-column-1').css("width","98%");//42
			$('.info-column-2').css("width","98%");//42		
			$('.edit-delete-buttons').css("padding-left","0");//38
			$('.edit-delete-buttons').css("padding-right","0");//8
			$('.item-view').show();
			$('.item-list').hide();
			$('.edit-btn').hide();
			$('.mobile-icons,.mobile-icons-cont').show();
			$('.mobile-icons-details').show();
			$('.details-button').hide();
		}
		else if (($(window).width() > 400) && ($(window).width() <= 750)) 
		{
			$('.info-column-1').css("width","98%");//42
			$('.info-column-2').css("width","98%");//42		
			$('.edit-delete-buttons').css("padding-left","0");//38
			$('.edit-delete-buttons').css("padding-right","0");//8
			$('.item-list').hide();
			$('.edit-btn').hide();
			$('.item-view').show();
			$('.mobile-icons,.mobile-icons-cont').show();
			$('.mobile-icons-details').show();
			$('.details-button').hide();	
		}
		else if (($(window).width() > 750) && ($(window).width() < 1000))
		{
			$('.info-column-1').css("width","42%");//42
			$('.info-column-2').css("width","42%");//42		
			$('.edit-delete-buttons').css("padding-left","0");//38
			$('.edit-delete-buttons').css("padding-right","0");//8
			$('.item-list').show();
			$('.edit-btn , .delete-btn').show();
			$('.mobile-icons,.mobile-icons-cont').hide();
			$('.mobile-icons-details').hide();
			$('.item-view').hide();
			$('.details-button').show();
		}
		else if (($(window).width() > 1000) && ($(window).width() < 1050))
		{
			$('.info-column-1').css("width","42%");//42
			$('.info-column-2').css("width","42%");//42		
			$('.edit-delete-buttons').css("padding-left","38%");//38
			$('.edit-delete-buttons').css("padding-right","8%");//8
			$('.item-list').show();
			$('.edit-btn , .delete-btn').show();
			$('.mobile-icons,.mobile-icons-cont').hide();
			$('.mobile-icons-details').hide();
			$('.item-view').hide();
			$('.details-button').show();
		}
		else if (($(window).width() > 1050) && ($(window).width() < 1300))
		{
			$('.info-column-1').css("width","42%");//42
			$('.info-column-2').css("width","42%");//42		
			$('.edit-delete-buttons').css("padding-left","38%");//38
			$('.edit-delete-buttons').css("padding-right","8%");//8
			$('.item-list').show();
			$('.edit-btn , .delete-btn').show();
			$('.mobile-icons,.mobile-icons-cont').hide();
			$('.mobile-icons-details').hide();
			$('.item-view').hide();
			$('.details-button').show();
		}
		else
		{
			$('.info-column-1').css("width","42%");//42
			$('.info-column-2').css("width","42%");//42		
			$('.edit-delete-buttons').css("padding-left","38%");//38
			$('.edit-delete-buttons').css("padding-right","8%");//8
			$('.item-list').show();
			$('.edit-btn , .delete-btn').show();
			$('.mobile-icons,.mobile-icons-cont').hide();
			$('.item-view').hide();
			$('.mobile-icons-details').hide();
			$('.details-button').show();
		}
	});
});
</script>
		
