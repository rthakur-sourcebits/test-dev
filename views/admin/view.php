<div class="wrapper admin" id="wrapper">
	<?php echo $header_user_list; ?>
	<div class="container">
		<div class="content">
			<div class="block">
				<div class="admin-detail">
					<h1 class="left name">
					<?php
					if(empty($firstname))
						echo $user_name;
					else 
						echo stripslashes($firstname)."&nbsp;".stripslashes($lastname);
					?></h1>
					<?php 
					/*if(!empty($email))
					{*/
					?>
						<div class="radius-15 right role"><?php echo $type; ?></div>
					<?php 
					//}
					?>
					<div class="clear"></div>
				</div>
				<?php 
				if(!empty($email))
				{
				?>
				<div class="table-wrapper">
				<table>
					<tr>
						<td class="td-first">Email Address</td>
						<td><input type="text" class="input-1" value="<?php echo stripslashes($email);?>" readonly="true"></td>
					</tr>
					<tr>
						<td class="td-first">Password</td>
						<td><input type="password" class="input-1" value="<?php echo $password;?>" readonly="true"/></td>
					</tr>
				</table>
				</div>
				<div class="empty2"></div>
				<!-- <div class="table-wrapper">
				<table>
					<tr>
						<td class="td-first">Payroll Category</td>
						<td><input type="text" class="input-1" value="Base Hourly" readonly="true"></td>
					</tr>
					
				</table>
				</div>-->
				<div style="height:9px;"></div>
				
				<form method="post" action="<?php echo SITEURL."/admin/delete"?>" id="form_user_delete">
					<div class="btn-block">
						<?php 
							$url_text	=	empty($email)?"Add":"Edit";
						?>
						<a class="admin-button" href="#" onclick="location.href='<?php echo SITEURL."/admin/edit/".$recordID?>'"><?php echo $url_text;?></a>
						<?php 
						if(!empty($email))
						{
						?>
							<!--<a class="admin-button" href="#" onclick="delete_alert('delete_user');">Delete</a> -->
						<?php 
						}
						?>
						<div class="clear"></div>
					</div>
					<input type="hidden" name="recordID" value="<?php echo $recordID; ?>" />
					<input type="hidden" name="type" value="<?php echo $type;?>" />
				</form>
				<?php 
				}
				else{
				?><div class="table-wrapper1" style="float:left;">
					<a class="admin-button" href="#" onclick="location.href='<?php echo SITEURL."/admin/edit/".$recordID?>'">Add</a>	
				  </div>
				<?php 
				}
				?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<div class="popup" id='delete_user'> 	
		<h3 class="question">Do you want to delete <?php echo stripslashes($firstname);?>&nbsp;<?php echo stripslashes($lastname);?>?</h3>
		<div>Deleting this user will remove his records from the system. This operation cannot be undone.</div>
		<a href="#" class="radius-5 button-1 right" onclick="cancel_gray_out('delete_user');">Cancel</a>
		<a href="#" class="radius-5 button-1 right" onclick="delete_user_submit();">Delete</a>
	</div>
</div>