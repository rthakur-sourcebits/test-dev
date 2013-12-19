<!-- 
 * @File : activate.php
 * @Author: 
 * @Created: 17-11-2012
 * @copyright:Acclivity LLC
 * @Modified:  
 * @Description: Activation view for user.
-->
<div class="wrapper" id="wrapper" style="padding-left:4%;padding-right:4%;">
			<div class="background-activation">
				<img id="account-edge-logo-login" class="logo_activation" height="23px" width="180px" src="<?php echo SITEURL; ?>/media/images/tt-new/logo.png">
			</div>
			<div class="navbar" id= "fix-ie" style="margin-top:22px; " >
	

    <div class="navbar-inner" id="inner-nav" >
	<!-- <div class="input-append" id="search-nav">
  <input class="span3 search-field" id="userappendedInputButton" placeholder="search" size="16" type="text">
 </div> -->
    <ul class="nav" style="width:100%;" >
    <!--  <li>
	<a href="#" id="" class="nav-link-admin"><div>Admin Dashboard </div></a>
	</li> -->
	<!-- <div class="span4"></div> -->
    <li class="title_nav" style="width:55% !important;" >
	<!-- <a href="#" id=""  class="nav-link-settings"> -->
	<div class = "nav-link-settings" >Activate</div>
	<!-- </a> -->
	</li>
   
    </ul>
    </div> 
    </div>
			
		<!-- 	<div id="account-section-container" class="row-fluid">-->
		 	<div id="account-section-container" class="row-fluid">
								  <!-- Center Contents Starts -->
 <div class="span8" id="user-middle-content" style="margin-left:0.5% !important; padding-bottom:10px !important;">
				
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
				
				
			<!-- 	<form action='<?php echo SITEURL?>/admin/activateuser' method="post" id="company_create" class='text_normal' name="admin_form">   -->
						<?php
						if(isset($error))
						{
							echo "<div class='error_message'>".$error."</div><br/>";
						}
						
						if($access_status == 0) {
							?>
							<input type="hidden" name="redirect_source" value="0" />
							<?php 
						}
						elseif($access_status == 1) {
							?>
							<input type="hidden" name="redirect_source" value="1" />
							<?php 
						} elseif($access_status == 2) {
							?>
							<input type="hidden" name="redirect_source" value="2" />
							<?php 
						}
						?>
			 	 <div class="search-contents" >
					<div class="row search-contents-row" id="activation_device"   >
						<div class="span3 accounts-setup-activity">AccountEdge Device Name
						</div> 
						<input type="text"  class="mar-cust acc-customer-textfield" id="device_name_field" name="device_name" value="">
					</div>
					<div class="act-error" style="display:none;">
							<img src="/media/images/tt-new/reset_error.png">Please Enter Device Name</div>
					</div>
					<div class="clear"></div>
					<div class="acc-upgrade">
						<a  class="admin-button button_class" href="javascript:void(0);" name='update' id='save' onclick="return submit_admin_form();" >
        					<div id="link_to_dropbox" class="acc-upgrade-btn link-to-db" >
        					Link to Dropbox				
        					<input type="hidden" name="activate_id" id='activate_id'value="<?php echo $activate_id;?>" />
        					</div>
        				</a>
						<div id="do_nothing_button" style="display:none;" >Link to Dropbox</div>
        			</div>
        			
				</div>
			
				
			<!-- </form>		 -->		
		</div>
			<!-- Center Contents Ends -->
						<div class="clear"></div>
				<!-- 	</div> -->
				
					<div class="clear"></div>
			</div>



<script type="text/javascript">
		$(document).ready(function()
		{
		if($(window).width() < 750) 
		{
		$('.field-margin-left').css("margin-left","0%");
		$('#user-middle-content').css("padding-right","4%");
		$('.accounts-setup-activity').attr('style', 'width: 46% !important');
		$('.acc-customer-textfield').attr('style', 'width: 47% !important');
		$('.acc-customer-textfield1').attr('style', 'width: 47% !important');
		$('#user-middle-content').attr('style','margin-left: 0% !important');
		$('.accounts-setup-activity').text('AccountEdge Device Name');
		}

		else if($(window).width() >= 750 && $(window).width()<1000) 
		{
		$('.field-margin-left').css("margin-left","0%");
		$('#user-middle-content').css("padding-right","4%");
		$('.accounts-setup-activity').attr('style', 'width: 46% !important');
		$('.acc-customer-textfield').attr('style', 'width: 47% !important');
		$('.acc-customer-textfield1').attr('style', 'width: 47% !important');
		$('#user-middle-content').attr('style','margin-left: 19% !important');
		$('.accounts-setup-activity').text('AccountEdge Device Name');
		}
		
		else
		{
		$('.field-margin-left').css("margin-left","0%");
		$('#user-middle-content').css("padding-right","9%");
		$('.accounts-setup-activity').attr('style', 'width: 37% !important');
		$('.acc-customer-textfield').attr('style', 'width: 56% !important');
		$('.acc-customer-textfield1').attr('style', 'width: 56% !important');
		$('#user-middle-content').attr('style','margin-left: 19% !important');
		$('.accounts-setup-activity').text('AccountEdge Device Name');
		}
		$(window).resize(function()
		{
		if($(window).width() < 750) 
		{
		$('.field-margin-left').css("margin-left","0%");
		$('#user-middle-content').css("padding-right","4%");
		$('.accounts-setup-activity').attr('style', 'width: 46% !important');
		$('.acc-customer-textfield').attr('style', 'width: 47% !important');
		$('.acc-customer-textfield1').attr('style', 'width: 47% !important');
		$('#user-middle-content').attr('style','margin-left: 0% !important');
		$('.accounts-setup-activity').text('AccountEdge Device Name');
		}

		else if($(window).width() >= 750 && $(window).width()<1000) 
		{
		$('.field-margin-left').css("margin-left","0%");
		$('#user-middle-content').css("padding-right","4%");
		$('.accounts-setup-activity').attr('style', 'width: 46% !important');
		$('.acc-customer-textfield').attr('style', 'width: 47% !important');
		$('.acc-customer-textfield1').attr('style', 'width: 47% !important');
		$('#user-middle-content').attr('style','margin-left: 19% !important');
		$('.accounts-setup-activity').text('AccountEdge Device Name');
		}
		else
		{
		$('.field-margin-left').css("margin-left","0%");
		$('#user-middle-content').css("padding-right","9%");
		$('.accounts-setup-activity').attr('style', 'width: 37% !important');
		$('.acc-customer-textfield').attr('style', 'width: 56% !important');
		$('.acc-customer-textfield1').attr('style', 'width: 56% !important');
		$('#user-middle-content').attr('style','margin-left: 19% !important');
		$('.accounts-setup-activity').text('AccountEdge Device Name');
		}
		});
		}); 
		</script>
<style>
ol{
list-style: decimal outside none !important;
margin-left: 26px !important;
}
@media all and (max-width: 452px) 
{
.accounts-setup-activity{
padding-top:2px;
}
}
</style>