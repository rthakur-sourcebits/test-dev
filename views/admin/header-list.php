	<script type="text/javascript">
    var form_update = false;
    var url_redirect = "";
  
    $(document).ready(function(){
	if($(window).width() <= 400) {
		$("#search-nav").css("float", "left");
		$("#search-nav").css("margin-left", "4%");
		$("#search-nav #appendedInputButton").css("min-height", "25px");
		$(".popup_admin").css("width", "78%");
		$(".popup_admin").css("margin-left", "4%");
		$(".row-fluid .span7 ").css("cssText","width:100%");
		
		}
	
    	 else if($(window).width() >= 401 && $(window).width() <= 660) {
    			$("#search-nav").css("float", "right");
    			$("#search-nav").css("margin-left", "10px");
    			$("#search-nav #appendedInputButton").css("min-height", "25px");
     			$(".popup_admin").css("width", "78%");
    			$(".popup_admin").css("margin-left", "4%");
    			$(".row-fluid .span7 ").css("cssText","width:100%");
    			
    		}
    	else if($(window).width() >= 661 && $(window).width() <= 750) {
    		 $("#search-nav").css("float", "right");
    			$("#search-nav").css("margin-left", "0px");
    			$("#search-nav #appendedInputButton").css("min-height", "0");
    			$(".popup_admin").css("width", "32%");
    			$(".popup_admin").css("margin-left", "32%");
    			$(".row-fluid .span7 ").css("width", "49.27%");
    				
 		}
    	 else {
		$("#search-nav").css("float", "right");
		$("#search-nav").css("margin-left", "0px");
		$("#search-nav #appendedInputButton").css("min-height", "0");
		$(".popup_admin").css("width", "32%");
		$(".popup_admin").css("margin-left", "32%");
		$(".row-fluid .span7 ").css("width", "49.27%");
		}
	if($(window).width() > 370 && $(window).width() < 390) {
		$("#search-nav").css("margin-left", "3%");
	}
	 $(window).resize(function(){
		 
		 if($(window).width() <= 400) {
				$("#search-nav").css("float", "left");
				$("#search-nav").css("margin-left", "4%");
				$("#search-nav #appendedInputButton").css("min-height", "25px");
				$(".popup_admin").css("width", "78%");
				$(".popup_admin").css("margin-left", "4%");
				$(".row-fluid .span7 ").css("cssText","width:100%");
				
				}
			
		    	 else if($(window).width() >= 401 && $(window).width() <= 660) {
		    			$("#search-nav").css("float", "right");
		    			$("#search-nav").css("margin-left", "10px");
		    			$("#search-nav #appendedInputButton").css("min-height", "25px");
		    			$(".popup_admin").css("width", "78%");
		    			$(".popup_admin").css("margin-left", "4%");
		    			$(".row-fluid .span7 ").css("cssText","width:100%");
		    					
		    		}
		    	 else if($(window).width() >= 661 && $(window).width() <= 750) {
		    		 $("#search-nav").css("float", "right");
		    			$("#search-nav").css("margin-left", "0px");
		    			$("#search-nav #appendedInputButton").css("min-height", "0");
		    			$(".popup_admin").css("width", "32%");
		    			$(".popup_admin").css("margin-left", "32%");
		    			$(".row-fluid .span7 ").css("width", "49.27%");
		    		}
		    	 else {
				$("#search-nav").css("float", "right");
				$("#search-nav").css("margin-left", "0px");
				$("#search-nav #appendedInputButton").css("min-height", "0");
				$(".popup_admin").css("width", "32%");
				$(".popup_admin").css("margin-left", "32%");
				$(".row-fluid .span7 ").css("width", "49.27%");
				}
			if($(window).width() > 370 && $(window).width() < 390) {
				$("#search-nav").css("margin-left", "3%");
			}
	 });
		$('#edit_user_form input').focus(function() {
		form_update = true;
	});
});

function applyHeight(){
    var wrapheight = $('.container').height();
	$('.background, #middle').css('height', wrapheight + 'px');
	var listheight = $('#middle').height();
	$('#pane1, .jScrollPaneContainer').css('height', listheight);

    $('.scroll-pane').jScrollPane({
        scrollbarWidth: 13,
        scrollbarMargin: 0,
        arrowScrollOnHover: true 
    });
}
jQuery.ajaxSetup({
	  beforeSend: function() {
			$(".user-loader").show()
	  },
	  complete: function(){
		  $(".user-loader").hide()
		  $("#content").show()
	  },
	  success: function() {}
	});
function edit_user_account(uid, url)
{
	url_redirect	=	url+"/"+uid;
	if(form_update) {
		$("#grayout").show();
		$("#cancel_user_edit").show();
		return false;
	} else {
		location.href	=	url_redirect;
	}
	
	$(".navigation-"+uid).addClass("nav1-select");
}

function do_not_save_redirect()
{
	location.href	=	url_redirect;
}

function set_focus()
{
	form_update = true;
}
</script>

<div class="navi-bar">
	<div id="login-image" class="login-image">
 		<div id="my-account" class="my-account">
   		</div>
   </div>
   
	<div class="navbar" style="margin-top:22px;" id="main-search-bar">
		<form method="post" action="<?php echo SITEURL?>/admin/searchuser" id="search-user" name="search_user">
			<div class="input-append" id="search-nav">
				<input class="span3" id="appendedInputButton" name="search" placeholder="Search..." size="16" type="text" style="">
			</div>
			<input type="hidden" name="search_field" value="all" id="search_user_category_field" />
		</form>
    	<div class="navbar-inner" id="inn">
    		<ul class="nav">
	    		<?php
				if(!empty($_SESSION['selected_user_type']))
				{
					$class	=	$_SESSION['selected_user_type'] == 1?"nav-link-selected":"nav-link";
				}
				else
				{
					$class	=	"nav-link";
				}
				?>
    			<li class="nav-li">
					<a href="<?php echo SITEURL; ?>/admin/users/1" id="" class="<?php echo $class;?>"><div>All</div></a>
				</li>
				<?php
				if(!empty($_SESSION['selected_user_type']))
				{
					$class	=	$_SESSION['selected_user_type'] == 2?"nav-link-selected":"nav-link";
				}
				elseif(empty($_SESSION['selected_user_type']))
				{
					$class	=	"nav-link-selected";
				} else {
					$class	=	"nav-link";
				}
				?>
    			<li class="nav-li">
					<a href="<?php echo SITEURL; ?>/admin/users/2" id="" class="<?php echo $class;?>"><div>Employees</div></a>
				</li>
				<?php
				if(!empty($_SESSION['selected_user_type']))
				{
					$class	=	$_SESSION['selected_user_type'] == 3?"nav-link-selected":"nav-link";
				}
				else
				{
					$class	=	"nav-link";
				}
				?>
    			<li class="nav-li navigation-vendor">
					<a href="<?php echo SITEURL; ?>/admin/users/3" id="" class="<?php echo $class;?> navigation-vendor"><div>Vendors</div></a>
				</li>
    		</ul>
    	</div>
    </div>
</div>



<div class="row-fluid" id="account-section-container">
<?php 
if(empty($setup)) {
?>

	<div class="span11" id="sidebar-main">
	
	<?php
	$first_selected	=	"";
	$indx	=	1;
	if(isset($users) && !empty($users)){
		foreach($users as $user)
		{
			if($first_selected == "" AND !empty($selectedUser) AND $selectedUser == $user['RecordID'])
			{
				$first_selected	=	"nav1-select";
			}
			if(empty($user['firstname']))
			{
				$display_name	=	$user['CompanyOrLastName'];
			}
			else
			{
				$display_name	=	$user['firstname']." ".$user['CompanyOrLastName'];
			}
			if($user['active_status'] == 1) {
				$visual_active_status	=	"nav1";
			} else {
				$visual_active_status	=	"nav1-not-act";
			}
			?>		
			<a href="javascript:void(0);" name="<?php echo 'user_'.$indx?>" onclick="edit_user_account('<?php echo $user['RecordID'];?>', '<?php echo SITEURL;?>/admin/edit')">
				<div class="navigation-<?php echo $user['RecordID'];?> <?php echo $visual_active_status;?> <?php echo " ".$first_selected;?>">
					<div id="nav-label">
						<label id="l1"><?php echo $display_name;?></label>
						<?php if(!empty($user['email']) && isset($user['active_status'])&& $user['active_status']==1) { // Email Exist + is there entry in dharma user table then, Active Employee?>
							<span id="back-dot" class="user-status-image">
								<img src="<?php echo SITEURL;?>/media/images/tt-new/dot.png" class="dot">
							</span>
							<label class="email-label"><?php echo $user['email']?></label>
						<?php } else {?>
							<span id="back-dot" class="user-status-image">
								<img src="<?php echo SITEURL;?>/media/images/tt-new/dot1.png" class="dot1">
							</span>
							<label class="email-label">Not Activated</label>
						<?php }?>
					</div>
				</div>
			</a>
		<?php 
				$first_selected	=	"";
				$indx++;
		}
	}
	?>
	</div>

	<div class="span1"></div>
	<a name="user-content-section"></a>

<?php } ?>
<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />