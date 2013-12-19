<!-- 
 * @File : page_view.php
 * @view : Page view for sales slips
 * @Author: 
 * @Created: 05-12-2012
 * @Modified:  
 * @Description: View file for pagination of sales slips.
   Copyright (c) 2012 Acclivity Group LLC 
-->

<form method="post" action="/sales/deletesales" id="list_view">
			<!-- Sales Item List starts -->
			<div class="sales-item-list margin-left-6 margin-right-6" style="">
				<!--Item Heading starts-->
				<div class="name-salesItem">
					<div class="cust-serviceviewItem7">
						<div class="cust-serviceviewItem6">
							<div class="cust-serviceviewItem5">
								<div class="cust-serviceviewItem3">
								<input type="hidden" name="search_sale" class="search_sale" value="" />
									<?php $sort_order	=	$order;?>
									<?php 
									if($sort_field == '1') {
										$sort_order	=	(1-intval($sort_order));
									}
									?>
									<div class="cust-serviceviewItem-2-field" onclick="sort_sales('1', '<?php echo $sort_order?>')"><label class="salesItem-name-field font-color">Date
										<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
										<?php if($sort_field == '1') {
												$sort_image	=	($order == '0')? "sort-arrow-up.png" : "sort-arrow-down.png";
											?>
											<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
										<?php }	?>
									</label></div>
									<?php 
									if($sort_field == '2') {
										$sort_order	=	(1-intval($sort_order));
									}
									?>
									<div class="cust-serviceviewItem-3-field" onclick="sort_sales('2', '<?php echo $sort_order?>')"><label class="salesItem-name-field font-color">Type
										<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
										<?php if($sort_field == '2'){ 
												$sort_image	=	($order == '0')? "sort-arrow-up.png" : "sort-arrow-down.png";
											?>
											<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
										<?php }	?>
									</label></div>
									<?php 
									if($sort_field == '3') {
										$sort_order	=	(1-intval($sort_order));
									}
									?>
									<div class="cust-serviceviewItem-4-field" onclick="sort_sales('3', '<?php echo $sort_order?>')"><label class="salesItem-name-field font-color">Sales #
										<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
										<?php if($sort_field == '3'){ 
												$sort_image	=	($order == '0')? "sort-arrow-up.png" : "sort-arrow-down.png";
											?>
											<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
										<?php }	?>
									</label></div>
									<?php 
									if($sort_field == '4') {
										$sort_order	=	(1-intval($sort_order));
									}
									?>
									<div class="cust-serviceviewItem-6-field" onclick="sort_sales('4', '<?php echo $sort_order?>')"><label class="salesItem-name-field font-color">Customer
										<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
										<?php if($sort_field == '4') {
												$sort_image	=	($order == '0')? "sort-arrow-up.png" : "sort-arrow-down.png";
											?>
											<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
										<?php }	?>
									</label></div>
									<?php 
									if($sort_field == '5') {
										$sort_order	=	(1-intval($sort_order));
									}
									?>
									<div class="cust-serviceviewItem-7-field" onclick="sort_sales('5', '<?php echo $sort_order?>')"><label class="salesItem-name-field font-color">Total Amount
										<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
										<?php if($sort_field == '5') {
												$sort_image	=	($order == '0')? "sort-arrow-up.png" : "sort-arrow-down.png";
											?>
											<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
										<?php }	?>
									</label></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--Item Heading ends -->
				
				<!-- Item Values Starts Loop Section -->
				<?php
			if(empty($sales)){
			?>
					<div class="no-slips"><div class="row" id="view-row1">No sales available</div></div><div class="margin-60"></div>
			<?php 
				}
				$i	=	1;
				foreach($sales as $s) { 
					$class	=	(($i%2) == 0)?"even":"odd";
			?>
				<div class="name-salesItem1 <?php echo $class;?>">
					<div class="cust-serviceviewItem7">
						<div class="cust-serviceviewItem6">
							<div class="cust-serviceviewItem5">
								<div class="cust-serviceviewItem3">
									<div class="cust-serviceviewItem-2-field"><label class="salesItem-name-field font-color-list"><?php echo date("F d, Y",strtotime($s['created_date']));?></label></div>
									<div class="cust-serviceviewItem-3-field"><label class="salesItem-name-field font-color-list"><?php echo $s['type']?></label></div>
									<div class="cust-serviceviewItem-4-field"><label class="salesItem-name-field font-color-list"><?php echo $s['sale_number']?></label></div>
									<div class="cust-serviceviewItem-6-field"><label class="salesItem-name-field font-color-list" style="overflow:hidden"><?php echo $s['firstname'].' '.$s['customer_name'];?></label></div>
									<div class="cust-serviceviewItem-7-field">
										<label class="salesItem-name-field font-color-list"><?php echo $s['total_payment']?> 
											<a class="list-view-right-arrow right" href="<?php echo SITEURL?>/sales/view/<?php echo $s['id'];?>"><img src="<?php echo SITEURL?>/media/images/tt-new/timesheet-arrow-left.png" class="service-arrow-left"></a>
											<div class="select_disable list_check" onclick="select_sale(this, '<?php echo $s['id'];?>');">&nbsp;</div>
											<input type="checkbox" id="sale_check_<?php echo $s['id'];?>" style="visibility:hidden;"  name="sale_id[]" value="<?php echo $s['id'];?>" />
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php 
					$i++;
				} ?>
			</div>	
			</form>	
			
				<!-- Item Values Ends Loop Section -->
		
			
			
			<!-- Mobile Version Starts -->
			<form method="post" action="/sales/deletesales" id="list_view" style="">
			<?php foreach($sales as $s) { ?>
						<div class="sales-info-toggle-details"  style="display:none">
							<div class="row sales-cust-toggle-details-row" id="sales-cust-toggle-details-row">
								<div class="data-input-sales-fields-overtime-phone1 add-fields" >
								<label class="sales-info-label-mobile font-color-list"><?php echo date("F d, Y",strtotime($s['created_date']));?></label>
								</div> 
								
								<div class="data-input-sales-fields-overtime-phone add-fields" >
									<label class="sales-info-label-mobile dull service"><?php echo $s['type']?></label>
									<a class="list-view-right-arrow top10" href="<?php echo SITEURL?>/sales/view/<?php echo $s['id'];?>"><img src="<?php echo SITEURL?>/media/images/tt-new/timesheet-arrow-left.png" class="service-arrow-left"></a>
									<div style="padding-top:10px;" class="select_disable list_check" onclick="select_sale(this, '<?php echo $s['id'];?>');">&nbsp;</div>
									<input type="checkbox" id="sale_check_<?php echo $s['id'];?>" style="visibility:hidden;"  name="sale_id[]" value="<?php echo $s['id'];?>" />
								</div>
								<div class="data-input-sales-fields-customer1 add-fields" >
								<label class="sales-info-label-mobile font-color-list"><span class="dull">Customer </span><?php echo $s['customer_name']?></label>
								</div>  
								
								<div class="data-input-sales-fields-overtime-phone1" >
								<label class="sales-info-label-mobile font-color-list"><?php echo $s['sale_number']?></label>
								</div>
								
								<div class="data-input-sales-fields-overtime-phone" >
								<label class="sales-info-label-mobile font-color-list">Total &nbsp;<span class="heavy">$ <?php echo $s['total_payment']?></span></label>
								</div>
							</div>
						</div>
			<?php } ?>
			</form>