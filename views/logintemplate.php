<!-- 
  @File : logintemplate.php View
  @Description: This file acts as a template for Login page.
  Copyright (c) 2012 Acclivity Group LLC 
 -->
<!-- Minify can send far-future (one year) Expires headers. 
To enable this you must add a number to the querystring (e.g. /min/?g=js&1234 or /min/f=file.js&1234) and 
alter it whenever a source file is changed. 
If you have a build process you can use a build/source control revision number. -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" />
		<title>AccountEdge Cloud</title>
		<link rel="shortcut icon" href="/media/images/tt-new/Logo.ico" />
		<link type="text/css" rel="stylesheet" href="/min/g=logincss&365" />  
		<?php
		/**
		echo HTML::script('media/scripts/jquery-1.7.1.min.js');
		echo html::style('media/css/bootstrap.css');
		//echo html::script('media/scripts/main.js');
		echo html::script('media/scripts/login_script.js');
		echo html::style('media/css/time_tracker_user.css');
		*/
		?>
	</head>
	<body>
	
		<div id="outer-div">
			<?php echo $content;?>
		</div>
		<div class="popup-menu popup-password" id="forgot_password" onclick="show_admin_menu();">
			<img src="<?php echo SITEURL?>/media/images/tt-new/popup-arrow.png" class="my-account-popup-arrow">
			
			<div class="my-acc-type bor-rad">
				<label class="select-sales-label heavy">Forgot Password</label>
			</div>
			<div class="layout">
				<label class="layout-label dull">Enter Email</label>
			</div>
			<div class="popup-list-input first" id="list-layout-1" align="center">
				<input type="text" name="email" id="email-forgot-pass" value="" class="popup-email-input" />
			</div>
			<div class="status-message">&nbsp;
				<span class="loader" style="display:none;"><img src="/media/images/tt-new/ajax-loader-2.gif" /></span>
				<span class="status" style="display:none;"></span>
			</div>
			<div id="controls123" class="controls">
 				<button class="btn btn-small" id="cancel-btn" type="button" onclick="cancel_form_admin();"><span id="cancel_span">Cancel</span></button>
				<button class="btn btn-small" id="save-btn" type="button" onclick="submit_forgot_password();"><span id="save_span">Submit</span></button> 
			</div>
		</div>
	</body>
	<script type="text/javascript" src="/min/g=loginjs&365"></script>  
</html>

