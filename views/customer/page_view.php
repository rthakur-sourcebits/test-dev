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
		