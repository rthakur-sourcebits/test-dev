<!-- 
  * @File : companysettings.php
  * @view : view for Company Settings
  * @Author: 
  * @Created: 08-11-2012
  * @Modified:  
  * @Description: View file for Company Settings i.e. Admin Dashboard and settings.
	Copyright (c) 2012 Acclivity Group LLC 
-->
<script type="text/javascript">
$(document).ready(function(){
	 if($(window).width() > 490) {
		 $("#search-nav").css("float", "right");
		 $("#search-nav #appendedInputButton").css("min-height", "0");
		 $("#appendedInputButton").css("width", "219px");
		 $("#setting_nav").css("width", "50%");
		 $("#setting_nav .title_nav").css("float", "right");
		 $("#admin_dashboard").text("Admin Dashboard");
		 
	 } else {
		 width = $(window).width();
		 width = (width-40)+'px';
		 $('#appendedInputButton').css('cssText','width:'+width+' !important');
		 $("#search-nav").css("cssText", "float:left !important");
		 $("#search-nav #appendedInputButton").css("min-height", "25px");
		 $("#setting_nav").css("width", "70%");
		 $("#setting_nav .title_nav").css("float", "none");
		 $("#admin_dashboard").text("Admin");
		 $("#inn a.nav-link:last").css("width", "60px");
		 $("#sync-cloud-image").css({"min-width":" 334px","min-height":" 371px"});
	 }
	 if($(window).width() > 490 && $(window).width() <= 950) {
		$("#admin_dashboard").text("Admin");
	}
	$(window).resize(function(){
		 if($(window).width() > 490) {
			 $("#search-nav").css("float", "right");
			 $("#search-nav #appendedInputButton").css("min-height", "0");
			 $("#appendedInputButton").css("width", "219px");
			 $("#setting_nav").css("width", "50%");
			 $("#setting_nav .title_nav").css("float", "right");
			 $("#admin_dashboard").text("Admin Dashboard");
			 
		 } else {
			 width = $(window).width();
			width = (width-40)+'px';
			$('#appendedInputButton').css('cssText','width:'+width+' !important');
			$("#search-nav").css("cssText", "float:left !important");
			 $("#search-nav #appendedInputButton").css("min-height", "25px");
			 $("#setting_nav").css("width", "70%");
			 $("#setting_nav .title_nav").css("float", "none");
			 $("#admin_dashboard").text("Admin");
			 $("#inn a.nav-link:last").css("width", "60px");
			 $("#sync-cloud-image").css({"min-width":" 334px","min-height":" 371px"});
		 }
		 if($(window).width() > 490 && $(window).width() <= 950) {
			$("#admin_dashboard").text("Admin");
		}
	});
});
</script>
<div class="navi-bar">
	<div id="login-image" class="login-image">
		<div id="my-account" class="my-account"> </div>
	</div>
</div>

<div class="navbar" id= "fix-ie" style="margin-top:22px; " >
	<div class="navbar-inner" id="main-search-bar" >
		<form method="post" action="<?php echo SITEURL?>/admin/searchuser" id="search-user" name="search_user">
			<div class="input-append" id="search-nav">
		  		<input class="span3" name="search" placeholder="Search..." id="appendedInputButton" size="16" type="text"> 
		 	</div> 
		 	<input type="hidden" name="search_field" value="all" id="search_user_category_field" />
	 	</form>
    	<ul class="nav" id="setting_nav">
    		<li style="margin-left:3%;">
				<a href="<?php echo SITEURL?>/admin" id="" class="nav-link-admin"><div id="admin_dashboard">Admin Dashboard</div></a>
			</li> 
			<li class="title_nav">
				<div class="nav-link-settings">Settings</div>
			</li>
    	</ul>
    </div> 
</div>


<div class="row-fluid" id="account-section-container">
	<!-- Sidebar Starts-->
	<div class="span4" id="sidebar">
		<div id="sidebar_nav">
 			<?php echo $setting;?>
		</div>    
  	</div>
  	<!-- Sidebar Ends-->
  	
  	<div id="settings_content"><?php echo $content;?></div>
</div>
<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />