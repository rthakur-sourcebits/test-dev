<!-- 
 * @File : saleslist_header.php
 * @view : list sales header
 * @Author: 
 * @Created: 09-12-2012
 * @Modified:  
 * @Description: View file for header of list all sales.
   Copyright (c) 2012 Acclivity Group LLC 
-->

<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />

<?php if(isset($customer_job)) {?>
	<div class="navbar" id= "fix-ie" style="margin-top:34px;" >
		<div class="navbar-inner" id="sales-inner-nav" >
			<ul class="nav" id="navigation-menu-sales" >
				<li class="sales-activity-slip">
					<?php if(isset($job)) {?>
							<a href="/jobs" id="sales-activity-slip-link" class="sales-nav-link-admin1" ><div class="act-slip-mar-sales" >Jobs</div></a>
					<?php } elseif(isset($customer)) {?>
							<a href="/customer" id="sales-activity-slip-link" class="customer-nav-link-admin1" ><div class="act-slip-mar-customers" >Customers</div></a>
					<?php }?>
				</li>
					
				<li class="invoice">
					<div class = "sales-nav-link-settings">
						<label id="act-slip-lbl" class="font-16-mob"><?php echo $title;?></label>
					</div>
				</li>
					<li class="right pad-right-1" >
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
			 	
			</ul>
		</div> 
	</div>
<?php } else if(isset($payment_form)) { ?>
	<div class="navbar" id= "fix-ie" style="margin-top:34px;">
		<div class="navbar-inner" id="sales-inner-nav" >
			<ul class="nav" id="navigation-menu-sales" >
				<li class="sales-activity-slip">
					<?php if(isset($view)) {?>
						<a href="/sales/view/<?php echo $sale_id?>" id="sales-activity-slip-link" class="sales-nav-link-admin1" ><div class="act-slip-mar-sales" >Back</div></a>
					<?php } else {?>
						<a href="/sales/edit/<?php echo $sale_id?>" id="sales-activity-slip-link" class="sales-nav-link-admin1" ><div class="act-slip-mar-sales" >Back</div></a>
					<?php }?>
				</li>
					
				<li class="invoice">
					<div class = "sales-nav-link-settings">
						<label id="act-slip-lbl" class="font-16-mob"><?php echo $title;?></label>
					</div>
				</li>
				 	
				
				<li class="right pad-right-1">
					<div class ="synced-value-field"><a href="/sales/tobesynced"><?php echo $to_be_synced_count;?></a></div>
				</li>
				<li class="synced-list right">
					<div class = "nav-link-synced">
						<a href="<?php echo SITEURL?>/sales/tobesynced" class="font-synced to-be-synced-link"></a>
					</div>
				</li>
				<li class="right separator">
					<img src="/media/images/tt-new/separator.png">
				</li>
			</ul>
		</div> 
	</div>
<?php } else {?>
	<div class="navbar" id= "fix-ie" style="margin-top:34px;" >
		<?php 
		$navigation="/sales";
		if($tab=='2'){
		    $navigation="/customer";
		}
        if($tab=='3'){
		    $navigation="/jobs";
		}
		?>
		<form method="post" action="<?php echo $navigation;?>">
			<div class="navbar-inner" id="sales-inner-navigation" >
				<ul class="nav" id="navigation-menu-sales" >
					<li class="navigation-to-sales mobile-sales">
						<a href="/sales" id="" class="<?php if($tab == "1") echo "sales-nav-link-selected"; else echo "nav-link1"?>"><div>Sales</div></a>
					</li>
					<li class="navigation-to-sales mobile-customers">
						<a href="/customer" id="" class="<?php if($tab == "2") echo "sales-nav-link-selected"; else echo "nav-link1"?>" ><div>Customers</div></a>
					</li>
					<li class="navigation-to-sales mobile-jobs">
						<a href="/jobs" id="" class="<?php if($tab == "3") echo "sales-nav-link-selected"; else echo "nav-link1"?>" ><div>Jobs</div></a>
					</li>
					
					<li class="search-link-mobile" style="display:none;">
						<div class ="search-icon-mobile">
							<a href="#" onclick="mobileSearch(this);setFocus();" ><img src="/media/images/tt-new/search_lens.png"></a>
						</div>
					</li>
					
					<li class="<?php if($tab == "4") echo "synced-value blue";?>" style="float:right;padding-right:10px">
						<div class ="synced-value-field"><a href="/sales/tobesynced"><?php echo $to_be_synced_count;?></a></div>
					</li>
					
					<li class="synced-list  <?php if($tab == "4") echo " blue";?>" style="float:right;">
						<div class = "nav-link-synced">
							<a href="/sales/tobesynced"><label id="act-slip-lbl" class="font-synced" ></label></a>
						</div>
					</li>
					
					<li class="separator <?php if($tab == "4") echo " blue";?>" style="float:right;">
						<img src="/media/images/tt-new/separator.png">
					</li> 
					<?php if($tab != "4") {
					$name="search";
					if($tab=='2'){
					    $name='search_customer';
					}
					else if($tab=='3'){
					    $name='search_jobs';
					}
					?>
						<li class="right mar-right-1 search-bar" >
							<input class="span3 search-field" name="<?php echo $name;?>" onblur="mobileSearchOriginal(this)" id="userappendedInputButton" value="<?php echo isset($search_value)?$search_value:'';?>" placeholder="Search" size="16" type="text" >
							<a href="javascript:void(0);" onclick="clear_search_results('<?php echo SITEURL?>','<?php echo $tab;?>')" class="clear_search">X</a>
						</li>
					
					<?php }?>
				</ul>
			</div>
		</form>
	</div>
<?php }?>
<script type="text/javascript">
	$(document).ready(function()
	{
		var total_width_normal	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(2).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width();
		var total_width_mobile	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(2).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width();
		var total_ul_width		= $('.navbar').width();
		if($(window).width() < 400) 
		{
			$('.invoice').css('width',(total_ul_width-total_width_mobile-35)+'px');
		}
		else if (($(window).width() > 400) && ($(window).width() <= 750))
		{
			$('.invoice').css('width',(total_ul_width-total_width_mobile-49)+'px');
		}
		else if (($(window).width() >= 751) && ($(window).width() < 1000))
		{
			$('.invoice').css('width',(total_ul_width-total_width_normal-49)+'px');
		}
		else
		{
			$('.invoice').css('width',(total_ul_width-total_width_normal-50)+'px');
		}
		$(window).resize(function()
		{
			var total_width_normal	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(2).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width();
			var total_width_mobile	= $('#navigation-menu-sales li').eq(0).width()+$('#navigation-menu-sales li').eq(2).width()+$('#navigation-menu-sales li').eq(3).width()+$('#navigation-menu-sales li').eq(4).width();
			var total_ul_width		= $('.navbar').width();
			if($(window).width() < 400) 
			{
				$('.invoice').css('width',(total_ul_width-total_width_mobile-35)+'px');
			}
			else if (($(window).width() > 400) && ($(window).width() <= 750))
			{
				$('.invoice').css('width',(total_ul_width-total_width_mobile-49)+'px');
			}
			else if (($(window).width() >= 751) && ($(window).width() < 1000))
			{
				$('.invoice').css('width',(total_ul_width-total_width_normal-49)+'px');
			}
			else
			{
				$('.invoice').css('width',(total_ul_width-total_width_normal-50)+'px');
			}
		});
	});
	function mobileSearch(chk)
	{
		if($(window).width() < 400) 
		{
			$('.mobile-sales , .mobile-jobs , .mobile-customers').hide();
			$('.search-bar').show();
			$('.search-bar').css("padding-left","2%");
			$('.search-bar').css("padding-right","6%");		
			$('#userappendedInputButton').css('cssText','width:192px !important');
			$('.search-link-mobile').css('cssText','display:none !important');
		}
		else if (($(window).width() > 400) && ($(window).width() <= 750))
		{	
			$('.mobile-sales , .mobile-jobs , .mobile-customers').hide();
			$('.search-bar').show();
			$('.search-bar').css("padding-left","2%");
			$('.search-bar').css("padding-right","4%");
			$('#userappendedInputButton').css('cssText','width:250px !important');
			$('.search-link-mobile').css('cssText','display:none !important');
		}
		else
		{
    		$('.mobile-sales , .mobile-jobs , .mobile-customers').show();
    		$('.search-link-mobile').hide();
    		$('.search-bar').show();
    		$('.search-bar').css("padding-left","1%");
    		$('.search-bar').css("padding-right","3%");
    		$('#userappendedInputButton').css('cssText','width:180px !important');
    		$('.search-link-mobile').css('cssText','display:block !important');
		}
		$(window).resize(function()
		{
			if($(window).width() < 400) 
			{
				$('.mobile-sales , .mobile-jobs , .mobile-customers').hide();
				$('.search-bar').show();
				$('.search-bar').css("padding-left","2%");
				$('.search-bar').css("padding-right","6%");		
				$('#userappendedInputButton').css('cssText','width:192px !important');
				$('.search-link-mobile').css('cssText','display:none !important');
			}
			else if (($(window).width() > 400) && ($(window).width() <= 750))
			{	
				$('.mobile-sales , .mobile-jobs , .mobile-customers').hide();
				$('.search-bar').css("padding-left","2%");
				$('.search-bar').css("padding-right","4%");
				$('#userappendedInputButton').css('cssText','width:250px !important');
				$('.search-link-mobile').css('cssText','display:none !important');
			}
			else
			{
				$('.mobile-sales , .mobile-jobs , .mobile-customers').show();
				$('.search-link-mobile').hide();
				$('.search-bar').show();
				$('.search-bar').css("padding-left","1%");
				$('.search-bar').css("padding-right","3%");
				$('#userappendedInputButton').css('cssText','width:180px !important');
				$('.search-link-mobile').css('cssText','display:block !important');
			}
		});
	}
		
	function setFocus()
	{
		document.getElementById('userappendedInputButton').focus();
	}
	
	function mobileSearchOriginal(chk1)
	{
		if($(window).width() < 800)
		{		
			$('.mobile-sales , .mobile-jobs , .mobile-customers , .search-link-mobile').show();
			$('.search-bar').hide();
			$('.search-bar').removeClass('mobile_search_field');
			$('.search-field').removeClass('mobile_search_field_input');
		}
	}
</script>