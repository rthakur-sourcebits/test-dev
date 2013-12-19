<!-- 
 * @File : sales_edit_header.php
 * @view : edit sales header
 * @Author: 
 * @Created: 08-12-2012
 * @Modified:  
 * @Description: View file for header of editing a sale.
   Copyright (c) 2012 Acclivity Group LLC 
-->

 <link href="/media/css/sales.css" rel="stylesheet">
	<div class="navbar" id= "fix-ie" style="margin-top:36px;" >
			<div class="navbar-inner" id="sales-inner-nav" >
				<ul class="nav" id="navigation-menu-sales" >
					<li class="sales-activity-slip">
						<a href="/sales" id="sales-activity-slip-link" class="sales-nav-link-admin1" ><div class="act-slip-mar-sales" >Sales</div></a>
					</li>
					
					<li class="invoice">
						<div class = "sales-nav-link-settings">
							<label id="act-slip-lbl" class="act-slip-label"><?php echo 'Edit '.$title; ?></label>
						</div>
					</li>
						
					<li class="right pad-right-1">
						<div class ="synced-value-field"><a href="/sales/tobesynced"><?php echo $to_be_synced_count;?></a></div>
					</li>
					<li class="right synced-list">
						<div class = "nav-link-synced">
							<a id="act-slip-lbl" href="/sales/tobesynced" class="font-synced" ></a>
						</div>
					</li>
					<li class="right separator">
					<img src="/media/images/tt-new/separator.png">
					</li>
					
				</ul>
			</div> 
		</div>

<script type="text/javascript">
	$(document).ready(function() {
		var text_value = $('.act-slip-label').text();
		var total_width_normal	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(2).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width();
		var total_width_mobile	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(2).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width();
		var total_ul_width		= $('.navbar').width();		
	if($(window).width() < 400)
	{
		$('.act-slip-label').css("font-size","16px");
		$('#phone_mode').val('1');
		$('.invoice').css('width',(total_ul_width-total_width_mobile-23)+'px');
	}
	else if (($(window).width() > 400) && ($(window).width() < 600))
	{
		$('.act-slip-label').css("font-size","18px");
		$('#phone_mode').val('1');
		$('.invoice').css('width',(total_ul_width-total_width_mobile-48)+'px');
    }
	else if (($(window).width() > 600) && ($(window).width() <= 750))
	{
		$('.act-slip-label').css("font-size","18px");
		$('#phone_mode').val('1');
		$('.invoice').css('width',(total_ul_width-total_width_mobile-48)+'px');
    }
	else if (($(window).width() > 750) && ($(window).width() < 1000))
	{
		$('.act-slip-label').css("font-size","18px");
		$('#phone_mode').val('0');
		$('.invoice').css('width',(total_ul_width-total_width_normal-48)+'px');
    }
	else if (($(window).width() > 1000) && ($(window).width() < 1050))
	{
		$('.act-slip-label').css("font-size","18px");
		$('#phone_mode').val('0');
		$('.invoice').css('width',(total_ul_width-total_width_normal-48)+'px');
	}
	else if (($(window).width() > 1050) && ($(window).width() < 1270))
	{
		$('.act-slip-label').css("font-size","18px");
		$('#phone_mode').val('0');
		$('.invoice').css('width',(total_ul_width-total_width_normal-48)+'px');
	}
	else
	{
		$('.act-slip-label').css("font-size","18px");
		$('#phone_mode').val('0');
		$('.invoice').css('width',(total_ul_width-total_width_normal-48)+'px');
	}
	$(window).resize(function() {
		var total_width_normal	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(2).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width();
		var total_width_mobile	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(2).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width();
		var total_ul_width		= $('.navbar').width();		
		if($(window).width() < 400)
		{
			$('.act-slip-label').css("font-size","16px");
			$('#phone_mode').val('1');
			$('.invoice').css('width',(total_ul_width-total_width_mobile-23)+'px');
		}
		else if (($(window).width() > 400) && ($(window).width() < 600))
		{
			$('.act-slip-label').css("font-size","18px");
			$('#phone_mode').val('1');
			$('.invoice').css('width',(total_ul_width-total_width_mobile-48)+'px');
	    }
		else if (($(window).width() > 600) && ($(window).width() <= 750))
		{
			$('.act-slip-label').css("font-size","18px");
			$('#phone_mode').val('1');
			$('.invoice').css('width',(total_ul_width-total_width_mobile-48)+'px');
	    }
		else if (($(window).width() > 750) && ($(window).width() < 1000))
		{
			$('.act-slip-label').css("font-size","18px");
			$('#phone_mode').val('0');
			$('.invoice').css('width',(total_ul_width-total_width_normal-48)+'px');
	    }
		else if (($(window).width() > 1000) && ($(window).width() < 1050))
		{
			$('.act-slip-label').css("font-size","18px");
			$('#phone_mode').val('0');
			$('.invoice').css('width',(total_ul_width-total_width_normal-48)+'px');
		}
		else if (($(window).width() > 1050) && ($(window).width() < 1270))
		{
			$('.act-slip-label').css("font-size","18px");
			$('#phone_mode').val('0');
			$('.invoice').css('width',(total_ul_width-total_width_normal-48)+'px');
		}
		else
		{
			$('.act-slip-label').css("font-size","18px");
			$('#phone_mode').val('0');
			$('.invoice').css('width',(total_ul_width-total_width_normal-48)+'px');
		}
		});
	});
</script>

<style>
.ui-switch-middle{
border: 7px solid #FAFAFA !important;	
}
</style>