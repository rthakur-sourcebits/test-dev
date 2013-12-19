<link href="/media/css/sales.css" rel="stylesheet">
	
<?php echo $header;?>

		<div class="row-fluid margin-2" id="sales-row-fluid">

			<div class="name-customer-sales margin-left-6 margin-right-6">
				<a href="/customer/create">
				<div id="Add_sales" class="hd-background sales-part-add-edit create-sales add-option add-select" >
						
				</div>
				</a>
				<div class="sales-part-add-edit hd-background-edit mar-left-2" id="list_edit" onmouseover="change_image_timesheet_edit(this)" onmouseout="change_image_timesheet_edit_out(this)" onclick="list_edit_mode();">
				
				
				</div>
				<div class="sales-part-add-edit hd-background-delete" style="display:none;" id="list_delete" onclick="delete_customer_job_cofirm(this.id, 1, 0, 1);">
        		
        		</div>
        		<div class="sales-part-add-edit hd-background-cancel mar-left-2" style="display:none;" id="list_cancel" onclick="location.href='/customer'">
        		
        		</div>
				
					<div class="prev-next">
						<!-- <a class="next-link-inact" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a> -->
						<?php if($total_customer > 10) {?>
							<a class="next-link" id="customer-next-page" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>
						<?php } else {?>
		 					<a class="next-link-inact" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>
		 				<?php }?>
		 			    <div class="prev-arrow">
							<a class="prev-link-inact" id="customer-prev-page" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>
		 				</div>
		 				<input type="hidden" id="page-number" value="1" />
		 				<input type="hidden" id="view-per-page" value="10" />
		 				<input type="hidden" id="total-customers" value="<?php echo $total_customer;?>" />
		 				<input type="hidden" id="customer_count" value="<?php echo count($customers);?>" />
		 				<input type="hidden" name="sort_field" value="<?php echo $sort_field?>" id="sort_field" />
		 				<input type="hidden" name="order" value="<?php echo $order?>" id="sort_order" />
		 				<input type="hidden" id="sale-type-pagination" value="<?php echo $filter;?>" />
		 				<input type="hidden" name="search_customer" class="search_customer" value="<?php if(isset($search_customer)){echo $search_customer;}else{echo '';}?>" />
					</div>
				
			</div>
			<div id="customer_list">
			<?php
	    	if(empty($customers)){
	       	 ?>
	        <div class="empty"><label class="empty-lbl">No Customers Present</label></div>
	         <?php } ?>
	        
			<!-- Sales Item List starts -->
			<form method="post" action="/customer/deletecustomers" id="list_view">
			<div class="sales-item-list margin-left-6 margin-right-6">
				<!--Item Heading starts-->
	         <?php
	    	if(!empty($customers)){?>
				<div class="name-salesItem">
					<div class="synced1-serviceviewItem7">
						<div class="synced1-serviceviewItem6">
							<div class="synced1-serviceviewItem5">
								<?php $sort_order	=	$order;?>
								<?php 
								if($sort_field == '1') {
									$sort_order	=	(1-intval($sort_order));
								}
								?>
								<div class="synced1-serviceviewItem-2-field" onclick="sort_customer('1', '<?php echo $sort_order?>')" ><label class="salesItem-name-field font-color">Customer Name
									<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
									<?php if($sort_field == '1') {
										$sort_image	=	($order == '0')? "sort-arrow-up.png" : "sort-arrow-down.png";
									?>
									<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
									<?php }	?>
									</label>
								</div>
								<?php 
								if($sort_field == '2') {
									$sort_order	=	(1-intval($sort_order));
								}
								?>
								<div class="synced1-serviceviewItem-3-field" onclick="sort_customer('2', '<?php echo $sort_order?>')" ><label class="salesItem-name-field font-color">Type
									<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
									<?php if($sort_field == '2'){ 
										$sort_image	=	($order == '0')? "sort-arrow-up.png" : "sort-arrow-down.png";
									?>
									<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
									<?php }	?>
									</label>
								</div>
								<?php 
								if($sort_field == '3') {
									$sort_order	=	(1-intval($sort_order));
								}
								?>
								<div class="synced1-serviceviewItem-4-field" onclick="sort_customer('3', '<?php echo $sort_order?>')"><label class="salesItem-name-field font-color">Phone Number
									<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
									<?php if($sort_field == '3'){ 
										$sort_image	=	($order == '0')? "sort-arrow-up.png" : "sort-arrow-down.png";
									?>
									<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
									<?php }	?>
									</label>
								</div>
								<?php 
								if($sort_field == '4') {
									$sort_order	=	(1-intval($sort_order));
								}
								?>
								<div class="synced1-serviceviewItem-6-field" onclick="sort_customer('4', '<?php echo $sort_order?>')"><label class="salesItem-name-field font-color">Contact Name
									<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
									<?php if($sort_field == '4') {
										$sort_image	=	($order == '0')? "sort-arrow-up.png" : "sort-arrow-down.png";
									?>
									<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
									<?php }	?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<!--Item Heading ends -->
				
				<?php
					$i	=	1;
					foreach($customers as $c) {
						$class	=	(($i%2) == 0)?"even":"odd";
					?>
				<!-- Item Values Starts Loop Section -->
				<div class="name-salesItem1 <?php echo $class;?>">
					<div class="synced1-serviceviewItem7">
						<div class="synced1-serviceviewItem6">
							<div class="synced1-serviceviewItem5">
								
									<div class="synced1-serviceviewItem-2-field"><label class="salesItem-name-field font-color-list"><?php if($c['is_individual_card'] == "0") { echo $c['company_or_lastname']; } else {echo $c['firstname'].' '.$c['company_or_lastname'];}?></label></div>
									<div class="synced1-serviceviewItem-3-field"><label class="salesItem-name-field font-color-list"><?php if($c['is_individual_card'] == "0") {echo 'Company';} else {echo 'Individual';}?></label></div>
									<div class="synced1-serviceviewItem-4-field"><label class="salesItem-name-field font-color-list"><?php echo $c['phone']?></label></div>
									<div class="synced1-serviceviewItem-6-field"><label class="salesItem-name-field left width-96 font-color-list"><?php echo $c['contact']?>
								 					
											<a class="list-view-right-arrow right" href="<?php echo SITEURL?>/customer/view/<?php echo $c['id'];?>"><img src="<?php echo SITEURL?>/media/images/tt-new/timesheet-arrow-left.png" class="retina-arrow service-arrow-left"></a>
											<div class="select_disable list_check" onclick="select_customer(this, '<?php echo $c['id'];?>');">&nbsp;</div>
											<input type="checkbox" id="customer_check_<?php echo $c['id'];?>" style="visibility:hidden;"  name="customer_id[]" value="<?php echo $c['id'];?>" />
										</label>
										</div>
								 
							</div>
						</div>
					</div>
				</div>
				<?php 
						$i++;
					}?>
			</div>		
				<!-- Item Values Ends Loop Section -->
		
			
			
			<!-- Mobile Version Starts -->
			<?php foreach($customers as $c) {?>
						<div class="sales-info-toggle-details" style="display:none;">
							<div class="row sales-cust-toggle-details-row" id="sales-cust-toggle-details-row">
								
								<div class="data-input-sales-fields-billing-phone-date" >
								<label class="sales-info-label-mobile phone-width dull">Name &nbsp;<span class="font-color-list"><?php if($c['is_individual_card'] == "0") { echo $c['company_or_lastname']; } else {echo $c['firstname'].' '.$c['company_or_lastname'];}?></span></label>
								</div>
								
								<div class="data-input-sales-fields-billing-phone-date" >
								<label class="sales-info-label-mobile phone-width dull">Type &nbsp;<span class="font-color-list"><?php if($c['is_individual_card'] == "0") {echo 'Company';} else {echo 'Individual';}?></span>
								<a href="/customer/view/<?php echo $c['id']?>"><img src="<?php echo SITEURL?>/media/images/tt-new/timesheet-arrow-left.png" class=""></a>	
								</label>
								</div>
								
								<div class="data-input-sales-fields-billing-phone-date" >
								<label class="sales-info-label-mobile phone-width dull">Phone &nbsp;<span class="font-color-list"><?php echo $c['phone']?></span></label>
								</div>
								
								<div class="data-input-sales-fields-overtime-phone-date" >
								<label class="sales-info-label-mobile phone-width dull">Country &nbsp;<span class="font-color-list"><?php echo $c['country']?></span></label>
								</div>  
								
								
							</div>
						</div>
						<?php } ?>
			</form>
			
			</div>
			<?php if(!empty($customers)) { ?>
			<div class="slips margin-left-5 margin-right-5">
				<form method="post" action="" id="form_pager">
					<div class="span3 slips-view" id="slips-view">
						<div class="btn-group">
							<a class="dropdown-toggle" onclick="openViewPopup();" data-toggle="dropdown" href="#">
							<label id="slips_label" class="ajax_page" style="color:#a5a5a5;">View <span style="color:#515151;font-family:HelveticaNeueBold !important;"><span class="slips-selected">10</span> Customers</span><span class="caret"></span>
							<img class="ajax_loader_show" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
							</label>
							</a>
							<ul class="dropdown-menu" id="dropdown-menu-bottom" style="margin-left:0%;margin-top:0%;">
							<?php for($j=1;$j<=$total_pages;$j++) {?>
									<li class="customer_result_per_page" id="customer_<?php echo $j*10;?>"><a href="javascript:void(0);" class="slips-number"><?php echo $j*10; ?> Customers</a></li>
							<?php }?>
							<li class="customer_result_per_page all_views" id="customer_<?php echo $j*10;?>"><a href="javascript:void(0);" class="slips-number">All Customers</a></li>
							</ul>
						</div>
					</div>
				</form>
				<div class="clear"></div>
				<div class="" id="slips-show" style="text-align:left;">
					<label class="slips_label" id="slips_label" style="color:#A9A9A9;">Showing <span class="pagination-info">1-<?php echo ($total_customer>10)?10:$total_customer;?></span> of <?php echo $total_customer;?></label>
				</div>
			</div>
		<?php }?>
			<!-- Mobile Version Ends -->
			<div class="span12"></div>
				
		</div>