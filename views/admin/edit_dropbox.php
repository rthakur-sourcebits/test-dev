<?php 
//echo html::style('media/css/account_setup.css');
?>
<style type="text/css">
ol{
list-style: decimal outside none !important;
margin-left: 26px !important;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
    applyHeight();
    $(window).resize(function(){
        applyHeight();
    });
    
    function applyHeight(){
        var wrapheight = $(window).height();
		var listheight = $(window).height() - 40;
        $('#wrapper').css('height', wrapheight + 'px');
		$('#pane1, .jScrollPaneContainer').css('height', listheight);

        $('.scroll-pane').jScrollPane({
            scrollbarWidth: 13,
            scrollbarMargin: 0
        });
    }
	
	$('table tr:last-child td').css('border-bottom','0px');
	
	
});


</script>

			  <!-- Center Contents Starts -->
 <div class="span8 edit_dropbox_page" id="user-middle-content" >
				
				<div class="dropbox-sync-note">
				<label class="dropbox-note-label">
					A Dropbox account is required in order to sync data with AccountEdge. 
					<br/>
					Please enter the AccountEdge Device Name created in AccountEdge Device Manager.
				</label>
				</div>
				
				<div class="ordered-list">
				    <ol style= "list-style:decimal; margin-left:19px;">
						<li class="account-setup-info">Choose AccountEdge Cloud from the AccountEdge Pro setup menu.</li>
						<li class="account-setup-info">Link AccountEdge to a Dropbox account.</li>
						<li class="account-setup-info">Create a "Device" in AccountEdge using the Application type of AccountEdge Cloud.</li>
						<li class="account-setup-info">Select Sync on the Details tab.</li>
						<li class="account-setup-info">Add your Device name info below.</li>
					</ol>
					<div class="dropbox_connect" style="display:none;">
        				<label class="heavy mar-top-8">Please wait while it connects to dropbox</label>
        				<img src='/media/images/tt-new/load.gif' />
        			</div>
				</div>
				
				
			<!-- 	<form action='<?php echo SITEURL?>/admin/setup' method="post" id="updatedropbox" class='text_normal' name="updatedropbox">   -->
					<?php 
					if(!empty($success)) echo "<div class='success_message'>Dropbox credential updated. Please sync users</div><br/>";
					if(isset($error))
					{
						echo "<div class='error_message'>".$error."</div><br/>";
					}
					//if(!empty($error)) echo "<div class='error_message'>".$error."</div><br/>";
					?>
					
			 	 <div class="search-contents" >
					<div class="row search-contents-row" id="edit_device">
						<div class="span3 accounts-setup-activity">AccountEdge Device Name
						</div> 
						<input type="text"  class="mar-cust acc-customer-textfield" id="edit_device_name_field" name="device_name" value="<?php echo $dropbox['device_name'];?>">
					</div>
					<div class="act-error" style="display:none;">
							<img src="/media/images/tt-new/reset_error.png">Please Enter Device Name</div>
					</div>
					
					<div class="acc-upgrade">
					<a class="admin-button button_class upgrade_dropbox" href="javascript:void(0);" name='update' id='save' onclick="update_dropbox();">
				<div class="label-update-btn acc-upgrade-btn">
				Update
				</div>
				</a>
				<div id="do_nothing_button" style="display:none;" >Link to Dropbox</div>
				</div>
			</div>
				</div>
			<!-- 	</form>				 -->
		</div>
			<!-- Center Contents Ends -->


<script type="text/javascript">
		$(document).ready(function()
		{
		if($(window).width() < 500) 
		{
		$('.field-margin-left').css("margin-left","0%");
		
		$('.accounts-setup-activity').attr('style', 'width: 47% !important');
		$('.acc-customer-textfield').attr('style', 'width: 47% !important');
		$('.acc-customer-textfield1').attr('style', 'width: 47% !important');
		$('.accounts-setup-activity').text('AccountEdge Device Name');
	
		}
		else if (($(window).width() > 630 ) && ($(window).width() <= 800 ) )
		{
		$('.field-margin-left').css("margin-left","0%");
		
		$('.accounts-setup-activity').attr('style', 'width: 37% !important');
		$('.acc-customer-textfield').attr('style', 'width: 56% !important');
		$('.acc-customer-textfield1').attr('style', 'width: 56% !important');
		$('.accounts-setup-activity').text('AccountEdge Device Name');
		}
		else if (($(window).width() > 800 ) && ($(window).width() < 1100 ) )
		{
			$('.field-margin-left').css("margin-left","0%");
			
			$('.accounts-setup-activity').attr('style', 'width: 37% !important');
			$('.acc-customer-textfield').attr('style', 'width: 56% !important');
			$('.acc-customer-textfield1').attr('style', 'width: 56% !important');
			$('.accounts-setup-activity').text('AccountEdge Device Name');
		}
		else
		{
		$('.field-margin-left').css("margin-left","0%");
		
		$('.accounts-setup-activity').attr('style', 'width: 37% !important');
		$('.acc-customer-textfield').attr('style', 'width: 56% !important');
		$('.acc-customer-textfield1').attr('style', 'width: 56% !important');
		$('.accounts-setup-activity').text('AccountEdge Device Name');
		}
		$(window).resize(function()
		{
			if($(window).width() < 500) 
			{
			$('.field-margin-left').css("margin-left","0%");
			
			$('.accounts-setup-activity').attr('style', 'width: 47% !important');
			$('.acc-customer-textfield').attr('style', 'width: 47% !important');
			$('.acc-customer-textfield1').attr('style', 'width: 47% !important');
			$('.accounts-setup-activity').text('AccountEdge Device Name');
			}
			else if (($(window).width() > 630 ) && ($(window).width() <= 800 ) )
			{
			$('.field-margin-left').css("margin-left","0%");
			
			$('.accounts-setup-activity').attr('style', 'width: 37% !important');
			$('.acc-customer-textfield').attr('style', 'width: 56% !important');
			$('.acc-customer-textfield1').attr('style', 'width: 56% !important');
			$('.accounts-setup-activity').text('AccountEdge Device Name');
			}
			else if (($(window).width() > 800 ) && ($(window).width() < 1100 ) )
			{
				$('.field-margin-left').css("margin-left","0%");
				
				$('.accounts-setup-activity').attr('style', 'width: 37% !important');
				$('.acc-customer-textfield').attr('style', 'width: 56% !important');
				$('.acc-customer-textfield1').attr('style', 'width: 56% !important');
				$('.accounts-setup-activity').text('AccountEdge Device Name');
			}
			else
			{
			$('.field-margin-left').css("margin-left","0%");
			
			$('.accounts-setup-activity').attr('style', 'width: 37% !important');
			$('.acc-customer-textfield').attr('style', 'width: 56% !important');
			$('.acc-customer-textfield1').attr('style', 'width: 56% !important');
			$('.accounts-setup-activity').text('AccountEdge Device Name');
			}
		});
		}); 
		</script>
<style>
@media all and (max-width: 1019px) 
{
.accounts-setup-activity{
padding-top:2px;
}
}
</style>