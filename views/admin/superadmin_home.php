<div style="float: none; margin: 0 auto; position: relative; left: 0;margin:0 auto 20px; min-height:100%;"> 
<div class="admin_welcome_message">
	Welcome to AccountEdge Cloud Super Admin panel.
	<br/>
	<table cellpadding="0" cellspacing="0" id="weclome_page_slips_info">
		<tr>
			<td>Select Company</td>
			<td>
				<form method="post" action="" name="company_slips">
					<select name="company" onchange="company_slips.submit();">
						<option value="0">All</option>
					<?php foreach($company_list as $company) {
					?>
							<option value="<?php echo $company['id'];?>" <?php if(isset($_POST['company']) &&  $_POST['company']== $company['id']) echo "selected='selected'";?>><?php echo html_entity_decode($company['name']);?></option>
					<?php }?>
					</select>
				</form>
			</td>
		</tr>
		<tr>
			<td>Total Slips Processed</td>
			<td><?php echo $slip_details[0]['total_slips'];?></td>
		</tr>
		<tr>
			<td>Total Synced Slips</td>
			<td><?php echo $slip_details[0]['synced_slips'];?></td>
		</tr>
		<tr>
			<td>Total Unsynced Slips</td>
			<td><?php echo $slip_details[0]['unsynced_slips'];?></td>
		</tr>
		<tr>
			<td>Total Slips processed in current week</td>
			<td><?php echo $slip_details[0]['week_total'];?></td>
		</tr>
		<tr>
			<td>Total Slips by today</td>
			<td><?php echo $slip_details[0]['day_total'];?></td>
		</tr>
	</table>
</div>
</div>