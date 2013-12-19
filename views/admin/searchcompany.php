<script type="text/javascript" src="<?php echo SITEURL?>/media/scripts/custom-form-elements.js"></script>
<script type="text/javascript" src="<?php echo SITEURL?>/media/scripts/jquery.tablesorter.min.js"></script>
<?php echo HTML::style('media/jquery.tablesorter/tablesorter/themes/blue/style.css');?><script type="text/javascript" charset="utf-8">
	$(document).ready(function(){ 
		
		 $("#mytable_search").tablesorter(); 
		 $("form.jqtransform").jqTransform();

		 applyHeight();
		    
		    function applyHeight(){
		    	var wrapheight = $(".block").height();
		        wrapheight += 300;
				var listheight = $(window).height() - 40;
		        $('#wrapper').css('height', wrapheight + 'px');
		        //adm-footer-trust-wave
		    }
		 
    }); 
    function change_user_status1(a,c) {
        	alert(33)
    }
</script>
<div class="wrapper  over-visible" id="wrapper">
			
			
				<div class="admin-logo">
					<h1 class="logo"><a href="<?php echo SITEURL?>/admin">Time Tracker</a></h1>
					<div class="clear"></div>
				</div>
				<div class="header">
				<div class="employee-list-heading">
				<!--BEGIN NAVIGATION-->
					<form method="post" action="<?php echo SITEURL?>/admin/searchcompany" id="search-user" name="search_user">
						<?php 
							if(isset($_POST['search'])) {
								$category_display	=	$_POST['search_field_name'];
								$category_value		=	$_POST['search_field'];
							} else {
								$category_display	=	"All";
								$category_value		=	"all";
							}
						?>
						<div class="left search-wrap mtop5" style="margin-bottom: 8px;">
							<input type="text" class="search-bar2 right mleft15" value="<?php echo $search_value;?>" name="search" id="search_user_category" />
							<a href="#" class="cancel-search" onclick="clear_search_field();"></a>
							<input type="hidden" name="search_field" value="<?php echo $category_value;?>" id="search_user_category_field" />
							<input type="hidden" name="search_field_name" value="<?php echo $category_display;?>" id="search_user_category_field_name" />
						</div>
					</form>
				</div>
				<div class="back-button left"><a href="<?php echo SITEURL?>/admin">Admin</a></div>
				<a href="<?php echo SITEURL."/admin/logout"; ?>" class="header-links">Logout</a>
					
				
				<div class="clear"></div>
			</div>
			<div class="background">
				<div class="middle" id="middle">
					<form id="export_search_form" name="export_search_form" class="" action="<?php echo SITEURL?>/admin/export" method="post">
						<div class="search-export"><a href="#" onclick="export_search_form.submit();" class="view-button left export-button-search rounded-ten">Export</a></div>
						<input type="hidden" name="export_search" value="<?php echo $search_value;?>" />
					</form>
					<div class="block">
						
						<div class="sortable-data mtop30">
							<form id="" class="" action="" method="post">
								<div class="table-wrapper2">
									<table class="tablesorter" id="mytable_search" border="0" width="100%" cellpadding="0" cellspacing="0" style="position: relative;">
										<thead style="position: absolute; top: -40px; width: 100%;" id="t-header">
											<tr>
												<th width="15%"><div class="th-bg1-2">Created Date<div class="th-bg"></div></div></th> 
												<th width="15%"><div class="th-bg1-2">Company Name<div class="th-bg"></div></div></th> 
											    <th width="15%"><div class="th-bg1-2">Serial Number<div class="th-bg"></div></div></th> 
											    <th width="20%"><div class="th-bg1-2">Email<div class="th-bg"></div></div></th> 
											    <th width="15%"><div class="th-bg1-2">Location<div class="th-bg"></div></div></th>
											    <th width="10%"><div class="th-bg1-2">Service Plan<div class="th-bg"></div></div></th> 
												<th width="10%"><div class="th-bg1-2">&nbsp;<div class="th-bg"></div></div></th> 
												<th width="">&nbsp; </th> 
											</tr> 
										</thead>
										<tbody>
										<?php $arr_count	=	count($company_list);
											  $i	=	1;
											  $tr_class	=	"";
										?>
										<?php if(!empty($company_list)) {?>
											<?php foreach($company_list as $company) {?>
												<?php if($i == $arr_count){$tr_class	=	"noborder";}?>
											<tr class="<?php echo $tr_class;?>">
												<td width="15%"><?php echo date("M-d-Y", strtotime($company['create_date']))?></td>
												<td width="15%" class="bold"><?php echo $company['name']?><!-- <span class="searched-string">Bruce</span> Lee--></td>
												<td width="15%"><?php echo $company['serialnumber']?></td>
												<td width="20%"><?php echo $company['email']?></td>
												<td width="15%"><?php echo $company['city']?><br/><?php echo $company['country']?></td>
												<td width="10%"><?php echo $company['service_plan']?></td>
												<td width="10%">
													<?php if($company['status'] == 1) {?>
															<a href="<?php echo SITEURL?>/admin/edit_company/<?php echo $company['id']?>" class="view-button right blue-button rounded-ten">View</a>
													<?php } else {?>
															<a href="<?php echo SITEURL?>/admin/offlinecompany/<?php echo $company['id']?>" class="view-button right blue-button rounded-ten">View</a>
													<?php }?>
												</td>
												<td>&nbsp;</td>
											</tr>
											<?php
													$i++; 
												}
											} else {
											?>
												<tr class="noborder" ><td colspan="8" align="center">No records found.</td></tr>
											<?php
											}
								  			?>
										</tbody>	
									</table>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>