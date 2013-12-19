<script type="text/javascript">
$(document).ready(function(){
	if($(window).width() < 490) {
		$("#search-nav #appendedInputButton").css("min-height", "25px");
		$("#inn a.nav-link:last").css("width", "60px");
		$("#inn ul.nav").css("margin-top", "4px");
		$("#search-nav").css("float", "left");
		$("#search-nav").css("margin-left", "10px");
		$("#main-search-bar #appendedInputButton").css("width", "236px");
		$("#main-search-bar #appendedInputButton").css("margin-top", "9px");
		$("#main-search-bar #appendedInputButton").css("min-height", "23px");
		$(".nav-link").css("width", "86px");
		$("ul.nav").css("width", "270px")
	}
	$(window).resize(function(){
		 if($(window).width() > 490) {
				$("#search-nav #appendedInputButton").css("min-height", "0");
				$("#inn ul.nav").css("margin-top", "0px");
				$("#search-nav").css("float", "right");
				$("#search-nav").css("margin-left", "0px");
				$(".nav-link").css("width", "100px");
				$("ul.nav").css("width", "306px")
		 } else {
			  $("#search-nav #appendedInputButton").css("min-height", "25px");
			  $("#inn a.nav-link:last").css("width", "60px");
			  $("#inn ul.nav").css("margin-top", "4px");
			  $("#search-nav").css("float", "left");
			  $("#search-nav").css("margin-left", "10px");
			  $("#main-search-bar #appendedInputButton").css("width", "236px");
			  $("#main-search-bar #appendedInputButton").css("margin-top", "9px");
			  $("#main-search-bar #appendedInputButton").css("min-height", "23px");
			  $(".nav-link").css("width", "86px");
			  $("ul.nav").css("width", "270px")
		 }
	 });
});
</script>
<div class="navi-bar">
	<div id="login-image" class="login-image">
		<!--  <img src="images/abc1.jpg" width="133" height="20" alt="abc2"> -->
		<div id="my-account" class="my-account">
		</div>
	</div>
</div>
   
   
<div class="navbar" id="main-search-bar" style="margin-top:22px;" >

	<form method="post" action="<?php echo SITEURL?>/admin/searchuser" id="search-user" name="search_user">
		<div class="input-append" id="search-nav">
			<input class="span3" id="appendedInputButton" name="search" placeholder="Search..." size="16" type="text" value="<?php echo $search_value;?>">
		</div>
		<input type="hidden" name="search_field" value="all" id="search_user_category_field" />
	</form>

	<div class="navbar-inner" id="inn">
		<ul class="nav">
			<li class="nav-li">
				<a href="<?php echo SITEURL; ?>/admin/users/1" id="" class="nav-link"><div>All</div></a>
			</li>
			<li class="nav-li">
				<a href="<?php echo SITEURL; ?>/admin/users/2" id="" class="nav-link"><div>Employees</div></a>
			</li>
			<li class="nav-li">
				<a href="<?php echo SITEURL; ?>/admin/users/3" id="" class="nav-link"><div>Vendors</div></a>
			</li>
		</ul>
	</div>
</div>
    
<div class="row-fluid" id="account-section-container">
	<div class="span3" ></div>
	<div class="span6" id="search-container">
		</br></br>
		<div class="top-contents" style="padding-top:10px;">
			<?php $arr_count	=	count($company_users);
			?>
			<div id="search-results" style="float:right;">
				<label id="search-results"><?php echo $arr_count;?> Search Results</label>
			</div>
			<label id="label-name"><?php echo $search_value;?>&nbsp;</label>
		</div>
		<div id="back" class="search-title-line">
		</div>

		<div id="overall-search-contents">  
		
		
		<?php if(!empty($company_users)) {
			  		foreach($company_users as $user) {
		?>
						<div id="search-contents" >
							<div class="row" style="margin-left:10px;">
								<div class="span3" id="first-name" >
									<?php echo $user['first_name']." ".$user['last_name']?>
								</div> 
								<div class="span9" id="desig"><?php echo $user['type']?> 
									<a href="<?php echo SITEURL?>/admin/edit/<?php echo $user['record_id']?>#" id="arrow">
										<img src="<?php echo SITEURL?>/media/images/tt-new/arrow.png"> 
									</a> 
								</div> 
							</div>
							<div id="search-contents-margin" >
							</div>
							<div class="row" style="margin-left:10px;">
								<div class="span3" id="mail-add">Email Address</div> 
								<div class="span9" id="email-add-head"><?php echo $user['email']?></div>
							</div>
						</div>
	  <?php 
			  	}
			}
	  ?>

		</div>
		<div class="span3"></div>
		
	</div> 
 </div>