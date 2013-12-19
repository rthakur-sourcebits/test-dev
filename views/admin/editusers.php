<style>
.ui-switch-middle
{
	border:7px solid #f2f2f2 !important;
}
</style>
<div class="span8" id="user-right-content" class="user-right-content">
<?php $arr_count	=	count($company_users);
	  $i			=	1;
	  $tr_class		=	"";
?>
<?php if(!empty($company_users)) {?>
	<?php foreach($company_users as $user) {?>
	<script type="text/javascript">
	$(document).ready(function() {
		if($('#user-contents-edituser').is(":visible")){
			$('select#rate_<?php echo $user['id'];?>').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
				if(type == "off") {
					change_rate_status(1, '<?php echo $user['id'];?>');
				} else {
					change_rate_status(0, '<?php echo $user['id'];?>');
				}
			});
			$('select#payroll_<?php echo $user['id'];?>').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
				if(type == "off") {
					change_payroll_status(1, '<?php echo $user['id'];?>');
				} else {
					change_payroll_status(0, '<?php echo $user['id'];?>');
				}
			});
		} else {
			$('select#mobile_rate_<?php echo $user['id'];?>').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
				if(type == "off") {
					change_rate_status(1, '<?php echo $user['id'];?>');
				} else {
					change_rate_status(0, '<?php echo $user['id'];?>');
				}
			});
			$('select#mobile_payroll_<?php echo $user['id'];?>').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
				if(type == "off") {
		        	//$("#show-jobs").val("0");
		        	change_payroll_status(1, '<?php echo $user['id'];?>');
		        } else {
		        	//$("#show-jobs").val("1");
		        	change_payroll_status(0, '<?php echo $user['id'];?>');
		        }
		    });
		}
		$(window).resize(function() {
			if($('#user-contents-edituser').is(":visible")){
				$('select#rate_<?php echo $user['id'];?>').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
					if(type == "off") {
						change_rate_status(1, '<?php echo $user['id'];?>');
					} else {
						change_rate_status(0, '<?php echo $user['id'];?>');
					}
				});
				$('select#payroll_<?php echo $user['id'];?>').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
					if(type == "off") {
						change_payroll_status(1, '<?php echo $user['id'];?>');
					} else {
						change_payroll_status(0, '<?php echo $user['id'];?>');
					}
				});
			} else {
				$('select#mobile_rate_<?php echo $user['id'];?>').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
					if(type == "off") {
						change_rate_status(1, '<?php echo $user['id'];?>');
					} else {
						change_rate_status(0, '<?php echo $user['id'];?>');
					}
				});
				$('select#mobile_payroll_<?php echo $user['id'];?>').switchify({ on: '1', off: '0' }).data('switch').bind('switch:slide', function(e, type) {
					if(type == "off") {
			        	change_payroll_status(1, '<?php echo $user['id'];?>');
			        } else {
			        	change_payroll_status(0, '<?php echo $user['id'];?>');
			        }
			    });
			}
		});
	});
</script>
<div id="user-contents-edituser" class="user-contents-edituser" >
	<div class="row name-contents">
		<div class="span3 user-name" id="user-name" >
			<label id="label-user-name" ><?php echo $user['first_name']." ".$user['last_name'];?></label>
		</div> 
		<div class="span1 border-margin" id="border-margin-users" ></div> 
		<div class="span3 edit-user-text" id="user-email" >
			<label class="label-user-email"><?php echo $user['email']?></label>
		</div>
		<div class="users-search-contents-margin" ></div>
		<div class="span1 border-margin-2" id="border-margin-2" ></div>
		<div class="span2 edit-user-text" id="employee-designation" >
			<label id="label-employee-designation" ><?php echo $user['type']?></label>
		</div> 
		<div class="span1 border-margin-3" id="border-margin-3" ></div>
		<div class="span3 user-active-inactive" id="user-active-inactive">
		<?php if($user['status'] == 1) {?>
				<button class="active-btn" data-toggle="buttons-checkbox" id="active-btn-<?php echo $user['id'];?>" name="n1" type="button" onclick="change_user_status(this, '<?php echo $user['id'];?>', '<?php echo SITEURL;?>', '<?php echo $i;?>')"></button>
		<?php } else {?>
				<button class="inactive-btn" data-toggle="buttons-checkbox" id="inactive-btn-<?php echo $user['id'];?>" name="n1" type="button" onclick="change_user_status(this, '<?php echo $user['id'];?>', '<?php echo SITEURL;?>', '<?php echo $i;?>')"></button>
		<?php }?>
		</div>
	</div>
	<div class="users-search-contents-margin-middle"></div>
	<div class="row customer-display height-80">
		<div class="span5 edit-user-text1 align-left" id="user-display-rate" >
			<label id="label-user-display-rate"><span class="users-span">Display Rate/Amount</span>
				<select style="display:none;" class="select_rate" id="rate_<?php echo $user['id'];?>">
              	  <option value="1" <?php if($user['display_rate'] == "1") echo " selected='selected'"; ?>>On</option>
              	  <option value="0" <?php if($user['display_rate'] == "0") echo " selected='selected'"; ?>>Off</option>
                </select>
			</label>
		</div> 
		<div class="span1 bor-mar-4" id="bor-mar-4" ></div>
		<div id="users-search-contents-margin" class="users-search-contents-margin" ></div>
		<div class="span6 edit-user-text1" id="user-show-jobs" >
			<label id="label-user-show-jobs"><span class="users-span">Payroll Category</span>
				<select style="display:none;" class="select_payroll" id="payroll_<?php echo $user['id'];?>">
              	  <option value="1" <?php if($user['payroll_category'] == "1") echo " selected='selected'"; ?>>On</option>
              	  <option value="0" <?php if($user['payroll_category'] == "0") echo " selected='selected'"; ?>>Off</option>
                </select>
			</label>
		</div> 
	</div> 
</div>
		
<div class="clear"></div>
<!-- Mobile Mode -->
<div class="edit_users_mobile" style="display:none;">
	<div class="inner_name_content bor-bot">
		<div class="text_content left width-70">
			<label class="left_label" ><?php echo $user['first_name']." ".$user['last_name'];?></label>
		</div>
		<div class="text_content right width-30">
			<label class="right_label" ><?php echo $user['type']?></label>
		</div>
	</div>
	<div class="inner_name_content bor-bot">
		<div class="text_content left width-70">
			<label class="left_label" ><?php echo $user['email'];?></label>
		</div>
		<div class="text_content right width-30">
			<?php if($user['status'] == 1) {?>
				<button class="active-btn toggle_edit_buttons" data-toggle="buttons-checkbox" id="mobile_active-btn-<?php echo $user['id'];?>" name="n1" type="button" onclick="change_user_status(this, '<?php echo $user['id'];?>', '<?php echo SITEURL;?>', '<?php echo $i;?>')"></button>
			<?php } else {?>
				<button class="inactive-btn toggle_edit_buttons" data-toggle="buttons-checkbox" id="mobile_inactive-btn-<?php echo $user['id'];?>" name="n1" type="button" onclick="change_user_status(this, '<?php echo $user['id'];?>', '<?php echo SITEURL;?>', '<?php echo $i;?>')"></button>
			<?php }?>
		</div>
	</div>
	<div class="inner_name_content bor-bot">
		<label class="left_label width-150 left" >Display Rate/Amount</label>
			<select style="display:none;" class="select_rate" id="mobile_rate_<?php echo $user['id'];?>">
				<option value="1" <?php if($user['display_rate'] == "1") echo " selected='selected'"; ?>>On</option>
				<option value="0" <?php if($user['display_rate'] == "0") echo " selected='selected'"; ?>>Off</option>
			</select>
	</div>
	<div class="inner_name_content">
		<label class="left_label width-150 left" >Payroll Category</label>
			<select style="display:none;" class="select_payroll" id="mobile_payroll_<?php echo $user['id'];?>">
				<option value="1" <?php if($user['payroll_category'] == "1") echo " selected='selected'"; ?>>On</option>
				<option value="0" <?php if($user['payroll_category'] == "0") echo " selected='selected'"; ?>>Off</option>
			</select>
	</div>
</div>
<?php		$i++; 
		}
	}
?>
</div>