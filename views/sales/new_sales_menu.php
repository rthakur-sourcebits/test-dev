<!-- 
 * @File : new_sales_filter_menu.php
 * @Author: 
 * @Created: 11-12-2012
 * @Modified:  
 * @Description: filter for sales create view.
 * Copyright (c) 2012 Acclivity Group LLC 
-->


<div class="add-popup" onclick="show_menu();" style="display:none;"><img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="popup-arrow">
	<div class="sales-type">
		<label class="select-sales-label heavy">Select Sales Type</label>
	</div>
	<div class="layout">
		<label class="layout-label dull">Layout</label>
	</div>
	<div class="popup-items">
		<div class="popup-list first" id="list-layout-1" onclick="selectItem(this,1)">
			<label class="popup-list-label heavy">Item</label>
		</div>
		<div class="popup-list first" id="list-layout-2" onclick="selectItem(this,1)">
			<label class="popup-list-label heavy">Service</label>
		</div>
		<div class="popup-list first no-border" id="list-layout-3" onclick="selectItem(this,1)">
			<label class="popup-list-label heavy">Time Billing</label>
		</div>
	</div>
	
	<div class="layout">
		<label class="layout-label dull">Type</label>
	</div>
	<div class="popup-items">
		<div class="popup-list second" id="list-type-6" onclick="selectItem(this,2)">
			<label class="popup-list-label heavy">Quote</label>
		</div>
		<div class="popup-list second" id="list-type-5" onclick="selectItem(this,2)">
			<label class="popup-list-label heavy">Order</label>
		</div>
		<div class="popup-list second no-border" id="list-type-4" onclick="selectItem(this,2)">
			<label class="popup-list-label heavy">Invoice</label>
		</div>
	</div>
	
	<!-- <a href="javascript:void(0);" id="create_sale_url" class="popup-btn-add-label"> -->
	<a href="javascript:void(0);" id="create_sale_url" class="popup-btn-add-label">
    	<div class="popup-btn-add popup-btn-add-label" style="display:none;">
    	Add
    	</div>
	</a>
	<!-- </a> -->
</div>