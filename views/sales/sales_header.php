<!-- 
 * @File : sales_header.php
 * @view : sales header
 * @Author: 
 * @Created: 10-12-2012
 * @Modified:  
 * @Description: View file for sales header
   Copyright (c) 2012 Acclivity Group LLC 
-->

<link href="/media/css/sales.css" rel="stylesheet">
 	   <meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />
 <div class="navbar" id= "fix-ie" style="margin-top:36px;" >
	<div class="navbar-inner" id="sales-inner-nav" >
		<ul class="nav" id="navigation-menu-sales" >
			<li class="sales-activity-slip">
				<a href="/sales" id="sales-activity-slip-link" class="sales-nav-link-admin1" ><div class="act-slip-mar-sales" >Sales</div></a>
			</li>
			<li class="sales-refresh-slip add-option add-select" id="sales-view-add" onclick="open_sales_popup(this.id, 3, '<?php echo $last_updated_sale;?>')">
				<a href="javascript:void(0);" class="sales-nav-link-admin2" id="sales-view-add-anchor"><div><img id="sales-view-add-image" src="/media/images/tt-new/add-image.png"></div></a>
			</li>	
			
			<li class="invoice">
				<div class = "sales-nav-link-settings">
					<label id="act-slip-lbl" class="label-sale-type" ><?php echo $sale_type;?></label>
				</div>
			</li>
			
			<li class="right pad-right-1">
				<div class ="synced-value-field"><a href="/sales/tobesynced"><?php echo $to_be_synced_count;?></a></div>
			</li>
			<li class="right synced-list">
				<div class = "nav-link-synced">
					<a href="<?php echo SITEURL?>/sales/tobesynced" class="font-synced to-be-synced-link"></a>
				</div>
			</li>
			
			<li class="right separator">
			<img src="/media/images/tt-new/separator.png">
			</li>
			<li class="right sales-up-down-navigation pad-right-4">
				<div class="up-down-bar">
					<?php if($prev_sale == 0) {?>
							<a href="javascript:void(0);" id="up-arr" class="disab"></a>
					<?php } else {?>
						<a href="/sales/view/<?php echo $prev_sale;?>" id="up-arr"></a>
					<?php }?>
					<?php if($next_sale == 0) {?>
							<a href="javascript:void(0);" id="down-arr" class="disab"></a>
					<?php } else {?>
						<a href="/sales/view/<?php echo $next_sale;?>" id="down-arr"></a>
					<?php }?>
					
					
				</div>
			</li>	
			
		</ul>
	</div> 
</div>
<?php echo $new_sales_menu;?>

<script type="text/javascript">
$(document).ready(function(){
	var total_width_normal	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(1).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width()+$('#navigation-menu-sales li').eq(5).width()+$('#navigation-menu-sales li').eq(6).width();
	var total_width_mobile	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width()+$('#navigation-menu-sales li').eq(5).width();
	var total_ul_width		= $('.navbar').width();
	
	if($(window).width() < 400) 
	{
	$('.label-sale-type').css('cssText','font-size:16px !important');
	$('.invoice').css('width',(total_ul_width-total_width_mobile-52)+'px');
	}
	else if (($(window).width() > 400) && ($(window).width() <= 752))
	{
		$('.label-sale-type').css('cssText','font-size:16px !important');
		$('.invoice').css('width',(total_ul_width-total_width_mobile-52)+'px');
	}
	else if (($(window).width() > 752) && ($(window).width() < 1000))
	{
		$('.label-sale-type').css('cssText','font-size:18px !important');
		$('.invoice').css('width',(total_ul_width-total_width_normal-52)+'px');
	}
	else
	{
		$('.label-sale-type').css('cssText','font-size:18px !important');
		$('.invoice').css('width',(total_ul_width-total_width_normal-52)+'px');
	}
	$(window).resize(function()
		{
		var total_width_normal	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(1).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width()+$('#navigation-menu-sales li').eq(5).width()+$('#navigation-menu-sales li').eq(6).width();
		var total_width_mobile	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width()+$('#navigation-menu-sales li').eq(5).width();
		var total_ul_width		= $('.navbar').width();
		if($(window).width() < 400) 
		{
			$('.label-sale-type').css('cssText','font-size:16px !important');
			$('.invoice').css('width',(total_ul_width-total_width_mobile-52)+'px');
		}
		else if (($(window).width() > 400) && ($(window).width() <= 752))
		{
			$('.label-sale-type').css('cssText','font-size:16px !important');
			$('.invoice').css('width',(total_ul_width-total_width_mobile-52)+'px');
		}
		else if (($(window).width() > 752) && ($(window).width() < 1000))
		{
			$('.label-sale-type').css('cssText','font-size:18px !important');
			$('.invoice').css('width',(total_ul_width-total_width_normal-52)+'px');
		}
		else
		{
			$('.label-sale-type').css('cssText','font-size:18px !important');
			$('.invoice').css('width',(total_ul_width-total_width_normal-52)+'px');
		}
		});
	});
	
</script>

<style>
.ui-switch-middle{
border: 7px solid #FAFAFA !important;	
}
</style>