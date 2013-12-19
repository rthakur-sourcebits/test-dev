<div id="jobs_list">
	<?php
   	if(empty($jobs)){
      	 ?>
       <div class="empty"><label class="empty-lbl">No Jobs Present</label></div>
        <?php } ?>

	<!-- Sales Item List starts -->
	<form method="post" action="/jobs/deletejobs" id="list_view">
	<div class="sales-item-list margin-left-6 margin-right-6">
		<!--Item Heading starts-->
		<?php
   		if(!empty($jobs)){
      	 ?>
		<div class="name-salesItem">
			<div class="jobs-serviceviewItem7">
				<div class="jobs-serviceviewItem3">
					<?php $sort_order	=	$order;?>
					<?php 
					if($sort_field == '1') {
						$sort_order	=	(1-intval($sort_order));
					}
					?>
					<div class="jobs-serviceviewItem-2-field" onclick="sort_jobs('1', '<?php echo $sort_order?>')" ><label class="salesItem-name-field font-color">Job Number
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
					<div class="jobs-serviceviewItem-6-field" onclick="sort_jobs('2', '<?php echo $sort_order?>')" ><label class="salesItem-name-field font-color">Name
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
					<div class="jobs-serviceviewItem-7-field" onclick="sort_jobs('3', '<?php echo $sort_order?>')" ><label class="salesItem-name-field font-color">Job Type
						<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
						<?php if($sort_field == '3'){ 
							$sort_image	=	($order == '0')? "sort-arrow-up.png" : "sort-arrow-down.png";
						?>
						<a class="sort-arrow right" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
						<?php }	?>
						</label>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
		<!--Item Heading ends -->
		<?php
			$i	=	1;
			foreach($jobs as $j) {
				$class	=	(($i%2) == 0)?"even":"odd";
			?>
		<!-- Item Values Starts Loop Section -->
		<div class="name-salesItem1 <?php echo $class;?>">
			<div class="jobs-serviceviewItem7">
				<div class="jobs-serviceviewItem3">
							<div class="jobs-serviceviewItem-2-field"><label class="salesItem-name-field font-color-list"><?php echo $j['job_number'];?></label></div>
							<div class="jobs-serviceviewItem-6-field"><label class="salesItem-name-field font-color-list"><?php echo $j['job_name'];?></label></div>
							<div class="jobs-serviceviewItem-7-field"><label class="salesItem-name-field font-color-list"><?php if($j['is_header_job'] == "1") {echo 'Header Job';} else {echo 'Detail Job';}?>					
									<a class="list-view-right-arrow right" href="<?php echo SITEURL?>/jobs/view/<?php echo $j['id'];?>"><img src="<?php echo SITEURL?>/media/images/tt-new/timesheet-arrow-left.png" class="service-arrow-left1 retina-arrow"></a>
									<div class="select_disable list_check" onclick="select_jobs(this, '<?php echo $j['id'];?>');">&nbsp;</div>
									<input type="checkbox" id="jobs_check_<?php echo $j['id'];?>" style="visibility:hidden;"  name="job_id[]" value="<?php echo $j['id'];?>" />
								</label></div>
						
				</div>
			</div>
		</div>
		<?php 
				$i++;
			}?>
	</div>		
		<!-- Item Values Ends Loop Section -->

	
	
	<!-- Mobile Version Starts -->
	<?php foreach($jobs as $j) {?>
				<div class="sales-info-toggle-details" style="display:none;">
					<div class="row sales-cust-toggle-details-row" id="sales-cust-toggle-details-row">
						<div class="data-input-sales-fields-billing-phone-date" >
						<label class="sales-info-label-mobile phone-width left dull">Number &nbsp;<span class="font-color-list"><?php echo $j['job_number'];?></span></label>
							<a href="/jobs/view/<?php echo $j['id']?>"><img src="<?php echo SITEURL?>/media/images/tt-new/timesheet-arrow-left.png" class="service-arrow-left"></a>	
							<div class="select_disable list_check" style="top:10px"  onclick="select_jobs(this, '<?php echo $j['id'];?>');">&nbsp;</div>
							<input type="checkbox" id="jobs_check_<?php echo $j['id'];?>" style="visibility:hidden;"  name="job_id[]" value="<?php echo $j['id'];?>" />
						</div> 
						 
						<div class="data-input-sales-fields-billing-phone-date" >
							<label class="sales-info-label-mobile phone-width dull">Name &nbsp;<span class="font-color-list"><?php echo $j['job_name'];?></span></label>
						</div>
								
						<div class="data-input-sales-fields-overtime-phone-date" >
							<label class="sales-info-label-mobile phone-width dull">Type &nbsp;<span class="font-color-list"><?php if($j['is_header_job'] == "1") {echo 'Header Job';} else {echo 'Detail Job';}?></label>
						</div> 
						
						
					</div>
				</div>
				<?php } ?>
	</form>
	</div>
