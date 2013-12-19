
			<!--  Sales Item List starts -->
				<!--Item Heading starts-->
				
				<div class="name-salesItem normal_mode">
					<div class="sync-serviceviewItem7">
						<div class="sync-serviceviewItem6">
							<div class="sync-serviceviewItem5">
								<div class="sync-serviceviewItem3">
									<?php $order=1;?>
										<?php if(isset($_POST['order']) && $_POST['order']==1) {$order=0;}?>
										<?php 
												if($order==0){
													$sort_image	="sort-arrow-up.png";
												} else {
													$sort_image	="sort-arrow-down.png";
												}
											?>
											<div class="sync-serviceviewItem-2-field" onclick="sort_synced(this,'1',<?php echo $order;?>)" ><label class="salesItem-name-field font-color">Date
											<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
											<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
											</label></div>
											<div class="sync-serviceviewItem-3-field" onclick="sort_synced(this,'2',<?php echo $order;?>)" ><label class="salesItem-name-field font-color">Type
											<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
											<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
											</label></div>
											<div class="sync-serviceviewItem-4-field" onclick="sort_synced(this,'3',<?php echo $order;?>)" ><label class="salesItem-name-field font-color">Name
											<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
											<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
											</label></div>
											<div class="sync-serviceviewItem-6-field" onclick="sort_synced(this,'4',<?php echo $order;?>)" ><label class="salesItem-name-field font-color">Total Amount
											<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
											<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
											</label></div>
											<div class="sync-serviceviewItem-7-field"><label class="salesItem-name-field font-color">Sync</label></div>
										</div>
									
								</div>
						</div>
					</div>
				</div>
				<!--Item Heading ends -->
				<?php
					$i	=	1;
					foreach($sales as $s) {
						$class	=	(($i%2) == 0)?"even":"odd";
						
					?>
				<!-- Item Values Starts Loop Section -->
				<div class="normal_mode name-salesItem1 <?php echo $class;?>">
					<div class="sync-serviceviewItem7">
						<div class="sync-serviceviewItem6">
							<div class="sync-serviceviewItem5">
								<div class="sync-serviceviewItem3">
									<div class="sync-serviceviewItem-2-field"><label class="salesItem-name-field font-color-list"><?php echo date("F d, Y",strtotime($s['created_date']))?></label></div>
									<div class="sync-serviceviewItem-3-field"><label class="salesItem-name-field font-color-list"><?php echo $s['type']?></label></div>
									<div class="sync-serviceviewItem-4-field"><label class="salesItem-name-field font-color-list"><?php echo isset($s['firstname']) ? $s['firstname'].' '.$s['company_or_lastname'] : $s['company_or_lastname']?></label></div>
									<div class="sync-serviceviewItem-6-field"><label class="salesItem-name-field font-color-list"><?php if(!empty($s['total_payment'])) echo '$&nbsp;'.$s['total_payment']; else echo '-NA-';?></label></div>
									<div class="sync-serviceviewItem-7-field" id="<?php if($s['type']=='Job'){
																											echo "job".$s['id'];}
																									   else if($s['type']=='Customer'){
																									   		echo "cust".$s['id'];}
																									   	else{
																									   	echo "sync-one-sale-".$s['id'];}?>" 
								onclick="sync_confirm(this.id, 1, '<?php echo $s['id'];?>');"><a href="javascript:void(0);"><img src="/media/images/tt-new/refresh1.png" class="pad-top-14 to-be-synced"></a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="clear"></div>
				<div class="sales-info-toggle-details" style="display:none;">
							<div class="row sales-cust-toggle-details-row" id="sales-cust-toggle-details-row">
								<div class="data-input-sales-fields-customer1 add-fields" >
								<label class="sales-info-label-mobile left font-color-list"><?php echo date("F d, Y",strtotime($s['created_date']))?></label>
								<a href="<?php echo SITEURL?>/sales/sync/<?php echo $s['id'];?>"><img src="/media/images/tt-new/refresh1.png" class="pad-7"></a>
								</div> 
								
								<div class="data-input-sales-fields-customer1 add-fields" >
								<label class="sales-info-label-mobile dull">Customer <span class="font-color-list"><?php echo isset($s['firstname']) ? $s['firstname'].' '.$s['company_or_lastname'] : $s['company_or_lastname']?></span></label>
								</div>  
								
								<div class="data-input-sales-fields-overtime-phone-date" >
								<label class="sales-info-label-mobile dull">Type &nbsp;<span class="font-color-list"><?php echo $s['type']?></span></label>
								</div> 
								
								<div class="data-input-sales-fields-overtime-phone-sync" >
								<label class="sales-info-label-mobile dull">Total &nbsp;<span class="font-color-list"><?php echo '$'.$s['total_payment'];?></span></label>
								</div>
							</div>
						</div>
						
				<?php 
						$i++;
					}?>
				<div class="clear"></div>
				<!-- Item Values Ends Loop Section -->
<style>
.separator{
 background: -moz-linear-gradient(center top , #75AED9, #2865AC) repeat scroll 0 0 rgba(0, 0, 0, 0);
}
</style>