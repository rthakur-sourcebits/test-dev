<link href="/media/css/sales.css" rel="stylesheet">

<?php echo $header;?>
<div class="row-fluid margin-2" id="sales-row-fluid" >

	<div class="name-customer-sales margin-left-6 margin-right-6">
		<a href="/jobs/create">
		<div id="Add_sales" class="hd-background sales-part-add-edit add-option create-sales add-select" >
		</div>
		</a>
		<div class="sales-part-add-edit hd-background-edit mar-left-2" id="list_edit" onclick="list_edit_mode();">
		</div>
		
		<div class="sales-part-add-edit hd-background-delete" style="display:none;" id="list_delete" onclick="delete_customer_job_cofirm(this.id, 1, 0, 2);">
		</div>
		<div class="sales-part-add-edit hd-background-cancel mar-left-2" style="display:none;" id="list_cancel" onclick="location.href='/jobs'">
		</div>
		
		
		<div class="prev-next">
			<!-- <a class="next-link-inact" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a> -->
			<?php if($total_jobs > 10) {?>
				<a class="next-link" id="jobs-next-page" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>
			<?php } else {?>
					<a class="next-link-inact" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>
				<?php }?>
			    <div class="prev-arrow">
				<!-- <a class="prev-link-inact" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>-->
				<a class="prev-link-inact" id="jobs-prev-page" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
				<input type="hidden" id="page-number" value="1" />
				<input type="hidden" id="view-per-page" value="10" />
				<input type="hidden" id="total-jobs" value="<?php echo $total_jobs;?>" />
				<input type="hidden" id="job_count" value="<?php echo count($jobs);?>" />
				<input type="hidden" name="sort_field" value="<?php echo $sort_field?>" id="sort_field" />
		 				<input type="hidden" name="order" value="<?php echo $order?>" id="sort_order" />
		 		<input type="hidden" id="sale-type-pagination" value="<?php echo $filter;?>" />
		 		<input type="hidden" name="search_jobs" class="search_jobs" value="<?php if(isset($search_jobs)){echo $search_jobs;}else{echo '';}?>" />
		</div>
	</div>
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
	<?php if(!empty($jobs)) { ?>
			<div class="slips margin-left-5 margin-right-5">
				<form method="post" action="" id="form_pager">
					<div class="span3 slips-view" id="slips-view">
						<div class="btn-group">
							<a class="dropdown-toggle" onclick="openViewPopup();" data-toggle="dropdown" href="#">
							<label id="slips_label" class="ajax_page" style="color:#a5a5a5;">View <span style="color:#515151;font-family:HelveticaNeueBold !important;"><span class="slips-selected">10</span> Jobs</span><span class="caret"></span>
							<img class="ajax_loader_show" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
							</label>
							</a>
							<ul class="dropdown-menu" id="dropdown-menu-bottom" style="margin-left:0%;margin-top:0%;">
							<?php for($j=1;$j<=$total_pages;$j++) {?>
									<li class="result_per_page" id="jobs_<?php echo $j*10;?>"><a href="javascript:void(0);" class="slips-number"><?php echo $j*10; ?> Jobs</a></li>
							<?php }?>
							<li class="result_per_page all_views" id="jobs_<?php echo $j*10;?>"><a href="javascript:void(0);" class="slips-number">All Jobs</a></li>
							</ul>
						</div>
					</div>
				</form>
				<div class="clear"></div>
				<div class="" id="slips-show" style="text-align:left;">
					<label class="slips_label" id="slips_label" style="color:#A9A9A9;">Showing <span class="pagination-info">1-<?php echo ($total_jobs>10)?10:$total_jobs;?></span> of <?php echo $total_jobs;?></label>
				</div>
			</div>
		<?php }?>
	<!-- Mobile Version Ends -->
	<div class="span12"></div>
		
</div>
<!--  Hello 
<div>
	<input type="button" name="edit" value="Edit" onclick="list_edit_mode();" id="list_edit" />
	<input type="button" name="delete" value="Delete" style="display:none" onclick="$('#list_view').submit();" id="list_delete" />
</div>
<form method="post" action="/jobs/deletejobs" id="list_view">
	<table width="100%">
		<tr>
			<td>Job name</td>
			<td>Job number</td>
			<td>Description</td>
			<td>Action</td>
		</tr>
-->  