<html>
<head>
	<title>Error Page</title>
	<link rel="stylesheet" type="text/css" href="../../../media/css/style.css">
</head>
<body>
	<div class="wrapper" id="wrapper">
	
		<div class="header">
			<h1 class="logo"><a href="<?php echo SITEURL; ?>">Time Tracker</a></h1>
		</div>
		
		<div class="login-container">
			<div class="inner-wrapper-login error-page">
				<form method='post' id='myform' action='<?php echo SITEURL;?>'>
						<span class="error-code">error code</span><br /><br />
						<h2>Page Not Found</h2><br /><br />
						<h4>The Requested page was not found. It may have moved,<br /> been deleted ,  or archieved</h4><br /><br />
											
					<p class="error-buttons">
						<input type="submit" value="Home Page" class="cancel-button submit_forgot" style="border: none !important;">
					</p>
				</form>
			</div>
		</div>
		<div class="ae-webapp">an AccountEdge web app</div>
	</div>
</body>
</html>