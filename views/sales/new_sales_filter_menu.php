<!-- 
 * @File : new_sales_filter_menu.php
 * @Author: 
 * @Created: 10-12-2012
 * @Modified:  
 * @Description: filter menu for sales page
 * Copyright (c) 2012 Acclivity Group LLC 
-->

<div class="add-popup1" style="display:none;"><img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="popup-arrow">
	<div class="sales-filter-type">
		<label class="select-sales-label heavy">Select Sales Type</label>
	</div>
	<div class="layout">
		<label class="layout-label dull">Layout
		</label>
	</div>
	<div class="popup-items-filter">
		<a href="javascript:void(0);">
		<div class="popup-list-filter filter" id="filter-layout-1" onclick="filterItem(this,1,'0')">
			<label class="popup-list-filter-label heavy">All Sales</label>
		</div>
		</a>
		<a href="javascript:void(0);">
		<div class="popup-list-filter filter" id="filter-layout-2" onclick="filterItem(this,1,'147')">
			<label class="popup-list-filter-label heavy">Invoice</label>
		</div>
		</a>
		<a href="javascript:void(0);">
		<div class="popup-list-filter filter" id="filter-layout-3" onclick="filterItem(this,1,'258')">
			<label class="popup-list-filter-label heavy">Order</label>
		</div>
		</a>
		<a href="javascript:void(0);">
		<div class="popup-list-filter filter radius no-border" id="filter-layout-4" onclick="filterItem(this,1,'369')">
			<label class="popup-list-filter-label heavy">Quote</label>
		</div>
		</a>
	</div>
</div>