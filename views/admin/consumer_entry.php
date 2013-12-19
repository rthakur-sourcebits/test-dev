<div class="wrapper" id="wrapper">
	<div class="header">
		<h1 class="logo"><a href="<?php echo SITEURL?>/admin">Time Tracker</a></h1>
	</div>
	<div class="login-container">
		<div class="login-tab">Dropbox</div>
		
		<div class="inner-wrapper-login">
		
			<form action="<?php echo SITEURL?>/admin/key_entry" method="post" id="myform" onsubmit="return submit_dropbox();">	
				<p class="dropbox">
					<span class="key">Consumer Key</span><input type="text" class="inp" name="key" id="key" maxlength='50' value="" autocomplete="off" /><br />
					<span class="secret">Consumer Secret</span><input type="text" class="inp" name="secret" id="secret" maxlength='50' value="" autocomplete="off" /><br /><br />
				</p>			
				<p class="dropbox">
					<input type="submit" name="submit" value="Submit" class="login-button" />
				</p>
				<input type="hidden" name="company" value="<?php echo $key_info['company_id'] ?>" />
			</form>
		</div>
	</div>
</div>