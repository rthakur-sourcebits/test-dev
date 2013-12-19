<!-- 
 * @File : to_be_synced.php
 * @Author: 
 * @Created: 22-12-2012
 * @Modified:  
 * @Description: view for to be synced page for sales.
 * Copyright (c) 2012 Acclivity Group LLC 
-->

<link href="/media/css/sales.css" rel="stylesheet">

<script type="text/javascript">
$(".slips-number").live("click", function(){
	var slips_per_page	=	($(this).text()).replace(" Slips","");
	var input = $("<input>").attr("type", "hidden").attr("name", "view_per_page").val(slips_per_page);
	$('#form_pager').append($(input));
	$('#form_pager').submit();
});
$(document).ready(function() {
	if ( (window.devicePixelRatio) && (window.devicePixelRatio >= 2) ) {
		var images = $('img.to-be-synced');   
		for(var i=0; i < images.length; i++){
			images.eq(i).attr('src', images.eq(i).attr('src').replace('/media/images/tt-new/refresh1.png', '/media/images/tt-new/sync-btn-retina.png'))
			$('.to-be-synced').css('width','23px')
			$('.to-be-synced').css('height','23px')
		}
	}
});

</script>

		
		<?php echo $header;?>
		<div class="row-fluid margin-2" id="sales-row-fluid" >

			<div class="name-customer-sales margin-left-6 margin-right-6">
				
				<a href="<?php echo SITEURL?>/activity/importcustomer/4">
				<div class="refresh-list">
				Refresh List
				</div>
				</a>
			
				<a id="sync-all" onclick="sync_confirm(this.id, 2,0)" href="javascript:void(0);" class="sync-all-link">
				<div class="synced-synl-all">
				Sync All
				</div>
				</a>
			</div>
			<input type="hidden" class="clicked_id" value="" />
			<?php
			if(empty($sales)){
			?>
			<div class="empty"><label class="empty-lbl">No Sales Present</label></div>
			<?php } ?>
			<input type="hidden" id="page-number" value="1" />
	 		<input type="hidden" id="view-per-page" value="10" />
			<!-- Sales Item List starts -->
			<div class="to_be_synced margin-left-6 margin-right-6" id="to_be_synced">
				<!--Item Heading starts-->
				<?php
				if(!empty($sales)){
				?>
				<div class="name-salesItem normal_mode">
					<div class="sync-serviceviewItem7">
						<div class="sync-serviceviewItem6">
							<div class="sync-serviceviewItem5">
								<div class="sync-serviceviewItem3">
									<?php $order=1;?>
									<?php if($order==0){
											$sort_image	="sort-arrow-up.png";
										} else {
											$sort_image	="sort-arrow-down.png";
										}
									?>
											<div class="sync-serviceviewItem-2-field" onclick="sort_synced(this,'1',<?php echo $order;?>)" ><label class="salesItem-name-field font-color">Date
											<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
											<a class="sort-arrow right" style="display:none;" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
											</label></div>
											<div class="sync-serviceviewItem-3-field" onclick="sort_synced(this,'2',<?php echo $order;?>)" ><label class="salesItem-name-field font-color">Type
											<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
											<a class="sort-arrow right" style="display:none;" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
											</label></div>
											<div class="sync-serviceviewItem-4-field" onclick="sort_synced(this,'3',<?php echo $order;?>)" ><label class="salesItem-name-field font-color">Name
											<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
											<a class="sort-arrow right" style="display:none;" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
											</label></div>
											<div class="sync-serviceviewItem-6-field" onclick="sort_synced(this,'4',<?php echo $order;?>)" ><label class="salesItem-name-field font-color">Total Amount
											<img class="ajax_loader" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
											<a class="sort-arrow right" style="display:none;" href="javascript:void(0);"><img src="<?php echo SITEURL?>/media/images/tt-new/<?php echo $sort_image;?>"></a>
											</label></div>
											<div class="sync-serviceviewItem-7-field"><label class="salesItem-name-field font-color">Sync</label></div>
										</div>
									
								</div>
						</div>
					</div>
				</div>
				<?php }?>
				
				<!--Item Heading ends -->
				<?php
					$i	=	1;
					foreach($sales as $s) {
						$class	=	(($i%2) == 0)?"even":"odd";
					?>
				<!-- Item Values Starts Loop Section -->
				<div class="normal_mode name-salesItem1 <?php echo $class;?> <?php if($s['type']=='Job'){ echo "job".$s['id'];} else if($s['type']=='Customer'){ echo "cust".$s['id'];} else{ echo "sync-one-sale-".$s['id'];}?>" >
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
			</div>		
				<!-- Item Values Ends Loop Section -->
		
			<?php 
			$page_count	=	$count_slips/$per_slip;
			if($count_slips%$per_slip != 0) $total_pages	=	$page_count+1;
			else $total_pages	=	$page_count;
			$total_pages	=	intval($total_pages);
			?>
			<?php if(!empty($sales)) { ?>
			<div class="clear"></div>
			<div class="slips margin-left-5">
				<input type="hidden" class="total_slips" value="<?php echo $count_slips;?>"/>
				<form method="post" action="" id="form_pager">
					<div class="span3 slips-view" id="slips-view">
						<div class="btn-group">
							<a class="dropdown-toggle" onclick="openViewPopup();" data-toggle="dropdown" href="javascript:void(0);">
							<label id="slips_label" class="ajax_page" style="color:#a5a5a5;">View <span style="color:#515151;font-family:HelveticaNeueBold !important;"><span class="sales-selected">10</span> Entries</span><span class="caret"></span>
							<img class="ajax_loader_show" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
							</label>
							</a>
							<ul class="dropdown-menu" id="dropdown-menu-bottom" style="margin-left:0%;margin-top:0%;">
							<?php for($j=1;$j<=$total_pages;$j++) {?>
									<li class="" id="sales_<?php echo $j*10;?>"><a href="javascript:void(0);" onclick="sort_synced(this,'0','1');" class="select_to_be_synced"><?php echo $j*10; ?> Entries</a></li>
							<?php }?>
							<li class="all_views" id="sales_<?php echo $j*10;?>"><a href="javascript:void(0);" onclick="sort_synced(this,'0','1');" class="select_to_be_synced">All Entries</a></li>
							</ul>
						</div>
					</div>
					
				</form>
				</div>
				<div class="clear"></div>
				<div class="" id="slips-show" style="text-align:left;">
					<label class="slips_label" id="slips_label" style="color:#A9A9A9;">Showing <span class="pagination-info-synced">1-<?php echo ($count_slips>10)?10:$count_slips;?></span> of <?php echo $count_slips;?></label>
				</div>
		<?php }?>
			
			<!-- Mobile Version Starts -->
			
			<!-- Mobile Version Ends -->
			<div class="span12"></div>
		</div>
<style>
.separator{
 background: -moz-linear-gradient(center top , #75AED9, #2865AC) repeat scroll 0 0 rgba(0, 0, 0, 0);
}
</style>

