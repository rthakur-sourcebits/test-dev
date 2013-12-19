<?php echo html::style('media/css/sales.css');?>

<script type="text/javascript">
	
$(".slips-number").live("click", function(){
	var slips_per_page	=	($(this).text()).replace(" Slips","");
	var input = $("<input>").attr("type", "hidden").attr("name", "view_per_page").val(slips_per_page);
	$('#form_pager').append($(input));
	$('#form_pager').submit();
});

$('.prev-link').live("click", function(){
	var page  = parseInt($("#page").val())-1;
	$("#page").val(page);
	var input = $("<input>").attr("type", "hidden").attr("name", "page_move").val("-1"); //back
	$('#form-pagination').append($(input));
	$('#form-pagination').submit();
});
$('.next-link').live("click", function(){
	var page  = parseInt($("#page").val())+1;
	$("#page").val(page);
	var input = $("<input>").attr("type", "hidden").attr("name", "page_move").val("1"); //next
	$('#form-pagination').append($(input));
	$('#form-pagination').submit();
});
</script> 
<?php echo $header;?>

<?php 
if($success == "1") {
	?>
	<script type="text/javascript">
	$(document).ready(function() { 
		var left = $('#all_sales_filter').offset().left;
		var top  = $('#all_sales_filter').offset().top;
		$("#success_sale_create").show();
		$('#success_sale_create').css({"left":"30%"});
	});
	$(document).bind('click', function(e) {
		$("#success_sale_create").hide();
	});
	</script>
	
	<?php 
}
?>
<div class="row-fluid margin-2" id="sales-row-fluid" >

			<div class="name-customer-sales margin-left-6 margin-right-6">
				<!-- <div id="quick_add" class="sales-part-add-edit hd-background" onclick="quick_add('<?php echo SITEURL;?>','<?php echo $last_updated_sale;?>')" >
        		</div>
				 -->
				 <div id="Add_sales" class="sales-part-add-edit hd-background add-option add-select"  onclick="open_sales_popup(this.id, 1,'<?php echo $last_updated_sale;?>')">
				</div>
				<div class="sales-part-add-edit hd-background-edit mar-left-2" id="list_edit" onmouseover="change_image_timesheet_edit(this)" onmouseout="change_image_timesheet_edit_out(this)" onclick="list_edit_mode();">
				<!-- <img src="<?php echo SITEURL?>/media/images/tt-new/edit-image-sales.png" class="btn-images-sales"> -->
				
				</div>
				
				<div class="sales-part-add-edit hd-background-cancel" style="display:none;" id="list_cancel" onclick="location.href='/sales'">
				</div>
				
				<div class="sales-part-add-edit hd-background-delete mar-left-2" style="display:none;" id="list_delete" onclick="delete_sales_cofirm(this.id, 1, 0);">
        		</div>
        		
				<div class="all-sales-part all_sales_filter all-sales-label" id="all_sales_filter" onclick="open_sales_filter_popup(this.id, 2 ,0)">
				<span class="filter_span">All Sales</span>
				<img class="retina-arrow-list filter-arrow" src ='<?php echo SITEURL?>/media/images/tt-new/allsales-arrow-down.png'>
				</div>
				
				
				<div class="prev-next">
					<!-- <a class="next-link-inact" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a> -->
					<?php if($count_slips > 10) {?>
						<a class="next-link" id="sales-next-page" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<?php } else {?>
	 					<a class="next-link-inact" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>
	 				<?php }?>
	 			    <div class="prev-arrow">
						<!-- <a class="prev-link-inact" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>-->
						<a class="prev-link-inact" id="sales-prev-page" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;</a>
	 				</div>
	 				<input type="hidden" id="page-number" value="1" />
	 				<input type="hidden" id="view-per-page" value="10" />
	 				<input type="hidden" id="total-sales" value="<?php echo $count_slips;?>" />
	 				<input type="hidden" id="sale-type-pagination" value="<?php echo $filter;?>" />
	 				
	 				<input type="hidden" name="sort_field" value="<?php echo $sort_field?>" id="sort_field" />
	  				<input type="hidden" name="order" value="<?php echo $order?>" id="sort_order" />
	  				<input type="hidden" name="search_sale" class='search_sale' value="<?php if(isset($search_sale)){echo $search_sale;} else {echo '';}?>" />
				</div>
				
			</div>
			
			<?php
			if(empty($sales)){
			?>
			<div class="empty"><label class="empty-lbl">No Sales Present</label></div>
			<?php } ?>
			
			<?php 
			//$new_sales_menu = '';
			echo $new_sales_menu;?>
			<?php echo $new_sales_filter_menu;?>
			<div id="sales_list">
			<form method="post" action="/sales/deletesales" id="list_view">
			<!-- Sales Item List starts -->
			<div class="sales-item-list margin-left-6 margin-right-6" >
				<!--Item Heading starts-->
				<?php
				if(!empty($sales)){
				?>
				<div class="name-salesItem">
					<div class="cust-serviceviewItem7">
						<div class="cust-serviceviewItem6">
							<div class="cust-serviceviewItem5">
								<div class="cust-serviceviewItem3">
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
									<div class="cust-serviceviewItem-7-field" onclick="sort_sales('5', '<?php echo $sort_order?>')"><label class="salesItem-name-field font-color">Total
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
				<?php }?>
				<!--Item Heading ends -->
				
				<!-- Item Values Starts Loop Section -->
				
			<?php 
			
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
										<label class="salesItem-name-field font-color-list">$ <?php echo $s['total_payment']?> 
											<a class="list-view-right-arrow right" href="<?php echo SITEURL?>/sales/view/<?php echo $s['id'];?>"><img src="<?php echo SITEURL?>/media/images/tt-new/timesheet-arrow-left.png" class="retina-arrow service-arrow-left"></a>
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
			<form method="post" action="/sales/deletesales" id="list_view">
			<?php foreach($sales as $s) { ?>
						<div class="sales-info-toggle-details" style="display:none;">
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
			</div>
			<!-- Mobile Version Ends -->
			<!-- Sales Item List starts -->
			<?php 
			$page_count	=	$count_slips/$per_slip;
			if($count_slips%$per_slip != 0) $total_pages	=	$page_count+1;
			else $total_pages	=	$page_count;
			$total_pages	=	intval($total_pages);
			?>
		<!--Show records ends -->
		<?php if(!empty($sales)) { ?>
			<div class="clear"></div>
			<div class="slips margin-left-5 margin-right-5">
				<form method="post" action="" id="form_pager">
					<div class="span3 slips-view" id="slips-view">
						<div class="btn-group">
							<a class="dropdown-toggle" onclick="openViewPopup();" data-toggle="dropdown" href="#">
							<label id="slips_label" class="ajax_page" style="color:#a5a5a5;">View <span style="color:#515151;font-family:HelveticaNeueBold !important;"><span class="sales-selected">10</span> Sales</span><span class="caret"></span>
							<img class="ajax_loader_show" style="display:none;" src="/media/images/tt-new/ajax-loader-2.gif" />
							</label>
							</a>
							<ul class="dropdown-menu" id="dropdown-menu-bottom" style="margin-left:0%;margin-top:0%;">
							<?php for($j=1;$j<=$total_pages;$j++) {?>
									<li class="sales_result_per_page" id="sales_<?php echo $j*10;?>"><a href="#" class="sales-number"><?php echo $j*10; ?> Sales</a></li>
							<?php }?>
							<li class="sales_result_per_page all_views" id="sales_<?php echo $j*10;?>"><a href="#" class="sales-number">All Sales</a></li>
							</ul>
						</div>
					</div>
					<input type="hidden" name="search" value="<?php echo $search?>" />
				</form>
				</div>
				<div class="clear"></div>
				<div class="" id="slips-show" style="text-align:left;">
					<label class="slips_label" id="slips_label" style="color:#A9A9A9;">Showing <span class="pagination-info">1-<?php echo ($count_slips>10)?10:$count_slips;?></span> of <?php echo $count_slips;?></label>
				</div>
		<?php }?>
		<!--Show records ends -->			
		</div>
		
	
 <div class="confirmation-box-success" id="success_sale_create" style="display:none">
	<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="popup-arrow-input">
	<div class="confirmation_buttons">
		Sale successfully created
	</div>
</div>

<script>
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		/******Hide filter Popup******/
		if (($clicked.hasClass("add-popup1")) || ($clicked.hasClass("sales-filter-type")) || ($clicked.hasClass("layout")) || ($clicked.hasClass("popup-items-filter")) || ($clicked.hasClass("all_sales_filter"))|| ($clicked.hasClass("filter-arrow")) || ($clicked.hasClass("filter"))|| ($clicked.hasClass("popup-list-filter-label")))
		{
			$('.add-popup1').show();
		}
		else {
			$('.add-popup1').hide();
		}
	});
</script>