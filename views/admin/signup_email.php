<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<title>AccountEdge Cloud for AccountEdge - Admin</title>
<style type="text/css">

</style>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#EEEEEE" >

<STYLE>
 .headerTop { background-color:#EEEEEE; border-top:0px solid #000000; border-bottom:0px solid #FFCC66; text-align:right; }
 .adminText { font-size:10px; color:#FFFFCC; line-height:200%; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; text-decoration:none; }
 .headerBar { background-color:#FFFFFF; border-top:0px solid #FFFFFF; border-bottom:0px solid #333333; }
 .title { font-size:22px; font-weight:bold; color:#336600; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; line-height:110%; }
 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; }
 td { font-size:12px; color:#000000; line-height:150%; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; }
 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
 .footerText { font-size:10px; color:#333333; line-height:100%; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; }
 a { color:#0177d0; }
</STYLE>

	<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" bgcolor="#EEEEEE" >
		<tr>
			<td valign="top" align="center">

				<table width="550" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="2" align="center" style="background-color:#EEEEEE;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:right;">
							<div align="left">
								<div align="right">
									<span style="font-size:10px;color:#000000;line-height:200%;text-decoration:none;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">
										<a href="http://www.acclivitysoftware.com/" style="color:#0983d1;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;"><img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/timetracker.png" alt="Time Tracker" width="110" height="24" border="0" align="left"></a>Trouble viewing? <a href="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/timetracker-admin.html" style="color:#0983d1;"><strong>Click here</strong></a>.
									</span>
								</div>
							</div>
						</td>
					</tr>

					<tr>
						<td colspan="2" align="center" style="background-color:#EEEEEE;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:right;">&nbsp;</td>
					</tr>

					</table>

				<table width="600" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #fff; border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;">
					<tr>
						<td valign="top" bgcolor="#FFFFFF" style="font-size:12px;color:#000000;line-height:150%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">

							<table width="550" border="0" align="center" cellpadding="5" cellspacing="0">
								<tr>
									<td align="center" valign="middle" bgcolor="#FFFFFF" style="font-size:12px;color:#000000;line-height:200%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">
										<table width="550" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" align="center">
												<img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/hello.jpg" alt="Hello and Welcome" width="300" height="100" border="0" align="center">
												</td>
												<tr>
													<td height="50" valign="top" width="550" colspan="2">
														<img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/breaker.jpg" height="20" alt="" style="border: 0;" width="550" align="center" />
													</td>
												</tr>
											</tr>
											<tr>
												<td colspan="3" style="font-size:12px;color:#000000;line-height:200%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">
													<div align="left">Dear <?php echo $user_name?>,<br><br>Welcome to AccountEdge Cloud. This email includes important details about your account, so please hang on to it.<br><br> 1. 
													To get started, you'll need to configure AccountEdge and AccountEdge Cloud to connect to your Dropbox account. Get set up with a Dropbox account by going to
													 <a href="http://www.dropbox.com/" style="color:#0983d1"> Dropbox.com</a>. It's fast, it's easy and it's free.<br><br>2. Next, open AccountEdge and go to Setup > AccountEdge Cloud > Manage AccountEdge Cloud. Enter your Dropbox account info, then tab to link your company file Dropbox. 
													 Then create a Device that will be used by AccountEdge Cloud to access your AccountEdge data in Dropbox. A folder with this name will be created inside your AccountEdge folder on Dropbox.<br><br>3. Almost done. After you\'re set up with Dropbox and AccountEdge, click this <a href="<?php echo $activation_link;?>" style="color:#0983d1"><?php echo $activation_link;?></a> and follow the directions. This is where you connect AccountEdge Cloud to Dropbox. <strong>Complete this step prior to logging into Time Tracker as an Admin for the first time</strong>.<br><br>4. Once the steps above are completed, you can log into Time Tracker as your company Admin using this link (we suggest that you bookmark this link for easier access in the future). <a href="<?php echo SITEURL;?>/admin" style="color:#0983d1"> '.SITEURL.'/admin</a> <br><br><span style="background-color:#ffff00">
													 Your Admin log in is: <?php echo $post['UserEmail'];?><br>Your Admin log in password is: <?php echo $post['password'];?></span><br><br>Please keep this email for your records. This user information is for your company Admin. Each employee or vendor you set up as a user will be sent their own unique log in credentials, including you if you will be both user and Admin.<br><br>That\'s it. If you need help, check out our online <a href="http://www.accountedge.com/timetrackerhelp/" style="color:#0983d1"> support center</a> for <a href="http://support.accountedge.com/kb/time-tracker" style="color:#0983d1"> FAQs</a>, the <a href="http://support.accountedge.com/discussions/time-tracker" style="color:#0983d1"> user forum</a> and <a href="http://www.accountedge.com/timetrackerhelp/" style="color:#0983d1"> tutorial videos</a>. If you have any additional questions, feel free to <a href="mailto:ttsupport@acclivitysoftware.com" style="color:#0983d1"> contact us</a>.<br><br>Kind regards,<br>AccountEdge Cloud Team </div>
												</td>
											</tr>


        
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="600" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #eeeeee;">
					<tr>
						<td align="left" valign="top">
							<div align="center">
								<span style="font-size:9px;color:#333333;line-height:150%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">&copy;2011 Acclivity LLC. Check out our <a href="http://acclivity.tumblr.com/" style="color:#0983d1;"><strong>company blog</strong></a> and <a href="http://twitter.com/accountedge" style="color:#0983d1;"><strong>follow us</strong></a> on Twitter. <br>
									<tr>
										<td colspan="2" align="center" style="background-color:#EEEEEE;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:center;">
											<a href="http://twitter.com/accountedge" style="color:#0983d1;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;"><img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/twitter-follow.png" alt="Follow us" width="29" height="29" border="0"></a><a href="http://acclivity.tumblr.com/rss" style="color:#0983d1;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;"><img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/rss-follow.png" alt="Read our blog" width="29" height="29" border="0"></a>
										</td>
									</tr>
								</span>
							</div>
						</td>
					</tr>
				</table>
			</tr>
		</table>
</body>
</html>