<!-- 
<table width="100%">
	<tr>
		<td>Job name</td>
		<td><?php echo $jobs[0]['job_name'];?></td>
	</tr>
	<tr>
		<td>Job number</td>
		<td><?php echo $jobs[0]['job_number'];?></td>
	</tr>
	<tr>
		<td>Contact</td>
		<td><?php echo $jobs[0]['contact'];?></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><?php echo $jobs[0]['description'];?></td>
	</tr>
	<tr>
		<td>Percentage complete</td>
		<td><?php echo $jobs[0]['percent_complete'];?></td>
	</tr>
	<tr>
		<td colspan="3">
			<a href="<?php echo SITEURL?>/jobs/edit/<?php echo $jobs[0]['id']?>">Edit</a>&nbsp;&nbsp;
			<a href="<?php echo SITEURL?>/jobs/sync/<?php echo $jobs[0]['id']?>">Sync</a>&nbsp;&nbsp;
			<a href="<?php echo SITEURL?>/jobs/delete/<?php echo $jobs[0]['id']?>">Delete</a>&nbsp;&nbsp;
			<a href="<?php echo SITEURL?>/jobs">Back to list</a>
		</td>
	</tr>
</table>
-->


<link href="/media/css/sales.css" rel="stylesheet">
		<?php echo $header;?>
	
		<div class="row-fluid" id="row-fluid" >
			
			<div class="add-info-column-1 width-header margin-left-1 white-background white-shadow">
				
				<div class="outer_div_header bor-right">
					<label class="header-job-line1 regular-font left">Header Job</label>
					<label class="job_select header right"></label>
				</div>

				<div class="outer_div_header width-50">
					<label class="job_select detail right"></label>
					<label class="header-job-line1 width-70 regular-font left">Detail Job</label>
				</div>
			</div>
			<input type="hidden" value="<?php echo $jobs[0]['is_header_job'];?>" id="is_header" name="is_header" />
			
			<div class="add-info-column-2 margin-right-1" >
				<div class="desc-add-sales-info"><label class="regular-font left ">Description</label>
					<textarea class="description-area" style="resize:none;cursor:default;" disabled ><?php echo trim($jobs[0]['description']);?></textarea>
				</div>
			</div>
				
			<div class="info-column-1 margin-left-1 mar_view">
				<div class="info sub-job">
					<label class="subject_job medium-font">Number</label>
					<label class="add-jobs-input-field dark pad-top-4"><?php echo $jobs[0]['job_number'];?></label>
				</div>
				
				<div class="info sub-job">
					<label class="subject_job medium-font">Sub-Job of</label>
					<label class="add-jobs-input-field dark pad-top-4"><?php echo $jobs[0]['sub_job_of'];?></label>
				</div>
				
				<div class="info sub-job">
					<label class="subject_job medium-font">Name</label>
					<label class="add-jobs-input-field dark pad-top-4"><?php echo $jobs[0]['job_name'];?></label>
				</div>

				<?php if($jobs[0]['is_header_job']==0){?>
				<div class="add-sales-info mar-7 white-background">
					<div class="left width-50 bor-right height-46">
						<label class="job-line1-inactive1 regular-font width-mob1 text-align-right no-pad-mob">Inactive Job</label>
						<?php $active_jobs= $jobs[0]['is_job_inactive'];
						if($active_jobs == 0)
						{
						    $link_active="inactive_retina";
						}
						else
						{
						    $link_active="active_retina";
						}
						?>
						<img src="/media/images/tt-new/<?php echo $link_active;?>.png" class="create-job-enable-disable job-disable1 enable1" width="18px" height="18px">
						
					</div>
					<label class="job-line2 regular-font pad-top-create-mobile">Track Reimbursables</label>
					<?php $track_reimburse= $jobs[0]['track_reimbursables'];
						if($track_reimburse == 0)
						{
						    $track="disable";
						}
						else
						{
						    $track="enable";
						}
						?>
					 <img src="/media/images/tt-new/<?php echo $track;?>.png" class="create-job-enable-disable enable" width="18px" height="18px">
				</div>
			<?php } ?>
				<div class="add-sales-info mar-3 white-background">
					<div class="left width-50 bor-right height-46">
						<label class="job-line1-inactive1 regular-font no-pad-mob width-mob text-align-right">Start Date</label>
						<label class="dark s-date-label-view"><?php if($jobs[0]['start_date']=='0000-00-00'){echo '';}else {echo date("F d, Y", strtotime($jobs[0]['start_date']));}?></label>
					</div>
					
					<label class="job-line2-end regular-font">Finish Date</label>
					<label class="dark e-date-label-view"><?php if($jobs[0]['finish_date']=='0000-00-00'){echo '';}else {echo date("F d, Y", strtotime($jobs[0]['finish_date']));}?></label>
				</div>
			</div>
			<div class="info-column-2 margin-right-1" >
				<div class="info sub-job width-ipad">
					<label class="line1 medium-font">Contact</label>
					<label class="add-jobs-input-field dark pad-top-4"><?php echo $jobs[0]['contact'];?></label>
				</div>
				<?php if($jobs[0]['is_header_job']==0){?>
				<div class="info sub-job width-ipad">
					<label class="line1 medium-font">Percent Complete</label>
					<label class="add-jobs-input-field dark pad-top-4"><?php echo $jobs[0]['percent_complete'];?></label>
				</div>
				<?php }?>
				
				<div class="info sub-job width-ipad">
					<label class="line1 medium-font">Manager</label>
					<label class="add-jobs-input-field dark pad-top-4"><?php echo $jobs[0]['manager'];?></label>
				</div>
				<?php if($jobs[0]['is_header_job']==0){?>
				<div class="info sub-job width-ipad">
					<label class="line1 medium-font">Linked Customer</label>
					<label class="add-jobs-input-field dark pad-top-4"><?php echo $jobs[0]['linked_customer'];?></label>
				</div>
				<?php }?>
				<div class="edit-delete-buttons-view" style="height:70px;">
    				<a href="javascript:void(0);"  id="delete-job" onclick="delete_customer_job_cofirm(this.id, 2, '<?php echo $jobs[0]['id']?>', 2);">
    					<div class="delete-btn delete-label" style="height:29px;">Delete</div>
    				</a>
    				
    				<a href="<?php echo SITEURL?>/jobs/edit/<?php echo $jobs[0]['id']?>">
    					<div class="edit-btn edit-label pad-top">Edit</div>
    				</a>
				</div>
			</div>
		</div>

	<script>
	$(document).ready(function()
	{
		if($('#is_header').val()=='1'){
			$('.header').addClass('select_job');
		}else{
			$('.detail').addClass('select_job');
		}
		var width_start = ($('.add-sales-info').width()/2)-100;
		$('.s-date-label-view').css('width',width_start+'px');
		$('.e-date-label-view').css('width',width_start+'px');
		if($(window).width() <= 750) 
		{
		$('.info-column-1').css("width","98%");//42
		$('.info-column-2').css("width","98%");//42		
		$('.info').css("padding-bottom","2px");//2			
		$('.details-button').css("margin-right","0");//8
		$('.cancel-save-buttons').css("padding-left","0");//38
		$('.cancel-save-buttons').css("padding-right","0");//8
		}
		
		else if (($(window).width() > 750) && ($(window).width() < 1000))
		{
		$('.info-column-1').css("width","98%");//42
		$('.info-column-2').css("width","98%");//42		
		$('.info').css("padding-bottom","2px");//2	
		$('.details-button').css("margin-right","0");//8
		$('.cancel-save-buttons').css("padding-left","0");//38
		$('.cancel-save-buttons').css("padding-right","0");//8
		}
		
		else
		{
		$('.info-column-1').css("width","44%");//42
		$('.info-column-2').css("width","44%");//42		
		$('.info').css("padding-bottom","2px");//2	
		$('.details-button').css("margin-right","8%");//8
		$('.cancel-save-buttons').css("padding-left","38%");//38
		$('.cancel-save-buttons').css("padding-right","8%");//8
		}
		
		$(window).resize(function()
		{
			var width_start = ($('.add-sales-info').width()/2)-100;
			$('.s-date-label-view').css('width',width_start+'px');
			$('.e-date-label-view').css('width',width_start+'px');
    		if($(window).width() <= 750) 
    		{
    		$('.info-column-1').css("width","98%");//42
    		$('.info-column-2').css("width","98%");//42		
    		$('.info').css("padding-bottom","2px");//2			
    		$('.details-button').css("margin-right","0");//8
    		$('.cancel-save-buttons').css("padding-left","0");//38
    		$('.cancel-save-buttons').css("padding-right","0");//8
    		}
    		
    		else if (($(window).width() > 750) && ($(window).width() < 1000))
    		{
    		$('.info-column-1').css("width","98%");//42
    		$('.info-column-2').css("width","98%");//42		
    		$('.info').css("padding-bottom","2px");//2	
    		$('.details-button').css("margin-right","0");//8
    		$('.cancel-save-buttons').css("padding-left","0");//38
    		$('.cancel-save-buttons').css("padding-right","0");//8
    		}
    		
    		else
    		{
    		$('.info-column-1').css("width","44%");//42
    		$('.info-column-2').css("width","44%");//42		
    		$('.info').css("padding-bottom","2px");//2	
    		$('.details-button').css("margin-right","8%");//8
    		$('.cancel-save-buttons').css("padding-left","38%");//38
    		$('.cancel-save-buttons').css("padding-right","8%");//8
    		}
		});
	});

	</script>
	
