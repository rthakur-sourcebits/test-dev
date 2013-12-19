
<script type="text/javascript">
		$(document).ready(function()
		{
			if($('.subscription-label').text() == '')
			{
				$('.payment-subscription-plan').hide();
				$('.payment-process').css("margin-top","5%");
			}
		});
	</script>
	


    <!-- Center Contents Starts -->
		<div id="normal-view" >
			<div class="span8 update-payment-page" id="user-right-content">
			<?php 
	        if(empty($card_details)) {
		        $form_action	=	"changeplan";
	        } else {
	        	$form_action	=	"updatecard";
	        }
        	?>
        	<form class="jqtransform" action='<?php echo SITEURL?>/admin/<?php echo $form_action?>' method="post" id="updatecard" name="updatecard">
		       	<div id="payment-subscription-plan" class="payment-subscription-plan marginBottom-10" >
					 <?php
		            	if(!empty($message)){ echo "<label class='subscription-label message-error'>".$message."</label>";
		            	
		            	}
			            if(!empty($error)){ echo "<label class='subscription-label message-error'>".$error."</label><br/>";
			            
			            }
	    	        ?>
				</div>
				
				<div class="payment-process">
					<div class="user-credit-card-details" >
						<div class="row credit-card-payment-details-row credit-card-payment-details-row" id="">
							<div class="credit-card-span" id="" >Payment Method
							</div> 
							<div id="cc-field" > <input type="text" value="Credit Card" class="credit-card-number-field" readonly>
							</div> 
						</div>
					</div>
				
					<div class="user-credit-card-details" >
						<div class="row credit-card-payment-details-row" id="credit-card-payment-details-row">
							<div class="span3 pad-left-5 credit-card-span" id="" >Credit Card#
							</div> 
							<div id="cc-field" > <input type="text" value="<?php echo $card_details['credit_card_number']?>" class="credit-card-number-field" readonly>
							</div> 
						</div>
					</div>
		
					<div class="type-of-card">
					<?php 
					$card_select_1="";
					$card_select_2="";
					$card_select_3="";
					$card_select_4= "";
					$card_select_label_1="";
					$card_select_label_2="";
					$card_select_label_3="";
					$card_select_label_4="";
					if ($card_details['credit_card_type']== 'VISA')
					{
					    $card_select_1="card_select_1";
					    $card_select_label_1="card_select_label_1";
					}
					else if ($card_details['credit_card_type']== 'MASTERCARD')
					{
					    $card_select_2="card_select_2";
					    $card_select_label_2="card_select_label_2";
					}
					else if ($card_details['credit_card_type']== 'AMERICAN EXPRESS')
					{
					    $card_select_3="card_select_3";
					    $card_select_label_3="card_select_label_3";
					}
					else if ($card_details['credit_card_type']== 'DISCOVER')
					{
					    $card_select_4="card_select_4";
					    $card_select_label_4="card_select_label_4";
					}
					?>
					
						<div class="card-type">
							<label class="card-type-label2">Card Type</label>
						</div>
						<div id="" class="card-type-visa inselect-mode <?php echo $card_select_1;?>" >
							<a class="card-type-link" href="#"><label class="card-type-label <?php echo $card_select_label_1;?>">Visa</label></a>
						</div>
						<div id="" class="card-type-master inselect-mode <?php echo $card_select_2;?>" >
							<a class="card-type-link" href="#"><label class="card-type-label master-label <?php echo $card_select_label_2;?>" >Mastercard</label></a>
						</div>
						<div id="" class="card-type-american inselect-mode <?php echo $card_select_3;?>" >
							<a class="card-type-link" href="#"><label class="card-type-label1 american-label <?php echo $card_select_label_3;?>" >American Express</label></a>
						</div>
						<div id="" class="card-type-discover inselect-mode <?php echo $card_select_4;?>" >
						<a class="card-type-link" href="#"><label class="card-type-label <?php echo $card_select_label_4;?>" >Discover</label></a></div>
					</div>
					
					<div class="user-credit-card-details" >
						<div class="row credit-card-payment-details-row1" id="credit-card-payment-details-row1">
							<div class="credit-card-span" id="" >Exp Year
							</div> 
							<?php 
								$cur_year	=	date("Y");
								$start_year	=	2010;
							?>
							<div id="cc-field-expiry-year" >  <input type="text" id="date" name="year" class="credit-card-expiry-field year" value="<?php echo $card_details['exp_date_year'];?>" /> 
								<div class="popup-contents-year" style="display:none;">
            						<div class="month-year-popup1">
            						<?php for($i=$start_year;$i<=$cur_year+25;$i++) {	?>
                    					<div class="names-popup-list year-row year-select" onclick=''>
                    							<label class="names-popup-list-label heavy"><?php echo $i;?></label>
                    					</div>
                    				<?php }?>
                    				</div>
								</div>
							</div> 
							<a href="javascript:void(0);" class="arrow-year right pad-right">
								<img src="/media/images/tt-new/calender-dates.png" class="calender-arrow"/> 
							</a>
						</div>
						<div class="row credit-card-payment-details-row2" id="credit-card-payment-details-row2">
							<div class="credit-card-span" id="" >Exp Month
							</div> 
							<?php 
							$month_name="";
							switch($card_details['exp_date_month'])
							{
							case "01" :
							$month_name="January";
							break;
							case "02" :
							$month_name="February";
							break;
							case "03" :
							$month_name="March";
							break;
							case "04" :
							$month_name="April";
							break;
							case "05" :
							$month_name="May";
							break;
							case "06" :
							$month_name="June";
							break;
							case "07" :
							$month_name="July";
							break;
							case "08" :
							$month_name="August";
							break;
							case "09" :
							$month_name="September";
							break;
							case "10" :
							$month_name="October";
							break;
							case "11" :
							$month_name="November";
							break;
							case "12" :
							$month_name="December";
							break;
							}
							?>
							<div class="cc-field-expiry-month" >  <input type="text" id="month-select" class="credit-card-expiry-field" value="<?php echo $month_name;?>"/> 
							<input type="hidden" name="month" id="month" value="" />
							
								<div class="popup-contents-month" style="display:none;">
                					<div class="month-year-popup">
                					<div class="months-row names-popup-list month-select" id="month-01" onclick=''>
                							<label class="names-popup-list-label heavy">January</label>
                					</div>
                					<div class="months-row names-popup-list month-select" id="month-02" onclick=''>
                							<label class="names-popup-list-label heavy">February</label>
                					</div>
                					<div class="names-popup-list months-row month-select" id="month-03" onclick=''>
                							<label class="names-popup-list-label">March</label>
                					</div>
                					<div class="names-popup-list months-row month-select" id="month-04" onclick=''>
                							<label class="names-popup-list-label heavy">April</label>
                					</div>
                					<div class="names-popup-list months-row month-select" id="month-05" onclick=''>
                							<label class="names-popup-list-label heavy">May</label>
                					</div>
                					<div class="names-popup-list months-row month-select" id="month-06" onclick=''>
                							<label class="names-popup-list-label heavy">June</label>
                					</div>
                					<div class="names-popup-list months-row month-select" id="month-07" onclick=''>
                							<label class="names-popup-list-label heavy">July</label>
                					</div>
                					<div class="names-popup-list months-row month-select" id="month-08" onclick=''>
                							<label class="names-popup-list-label heavy">August</label>
                					</div>
                					<div class="names-popup-list months-row month-select" id="month-09" onclick=''>
                							<label class="names-popup-list-label heavy">September</label>
                					</div>
                					<div class="names-popup-list months-row month-select" id="month-10" onclick=''>
                							<label class="names-popup-list-label heavy">October</label>
                					</div>
                					<div class="names-popup-list months-row month-select" id="month-11" onclick=''>
                							<label class="names-popup-list-label heavy">November</label>
                					</div>
                					<div class="names-popup-list months-row month-select" id="month-12" onclick=''>
                							<label class="names-popup-list-label heavy">December</label>
                					</div>
                				</div>
							</div>
							</div>
							<a href="javascript:void(0);" class="arrow-months right pad-right">
							<img src="/media/images/tt-new/calender-dates.png" class="calender-arrow"/> 
							</a>
						</div>
					</div>
					
					<div class="user-credit-card-details" >
						<div class="row credit-card-payment-details-row credit-card-payment-details-row" id="">
							<div class="credit-card-span" id="" >Name on card
							</div> 
							<div id="cc-field" > <input type="text" autocomplete="off" class="credit-card-name-field" name="card_name" id="card_name" maxlength='50' value="<?php echo $card_details['name_on_card']?>" />
							</div> 
						</div>
					</div>
				</div>
				</form>
				<?php 
				if(empty($card_details)) {
	            ?>
			<div id="payment-upgrade-plan" class="payment-upgrade-plan">
					<label class="upgrade-label">
				Please upgrade your plan to <strong>Update</strong> card details.</label>
				<a class="upgrade-btn-label-payment" href="<?php echo SITEURL?>/admin/changeplan">
    				<div class="payment-upgrade-button">
    					Upgrade
    				</div>
				</a>
			</div>
	        <?php } else {?>
			<div id="payment-upgrade-plan" class="payment-upgrade-plan">
					<label class="upgrade-label">
				To finish updating your Payment Method, please select <strong>Update</strong> below.</label>
				<div class="payment-upgrade-button">
				<a class="upgrade-btn-label-payment" href="#" onclick="updatecard.submit();">Update</a>
				</div>
			</div>
	<?php }?>
				
			</div>
		</div>
<script>
$(".arrow-months").live("click", function(){ //normal view
	var left = $('.arrow-months').offset().left;
	var top  = $('.arrow-months').offset().top; 
	$('.popup-contents-month').css({"left":left-135,"top":top+43});
	$('.popup-contents-month').toggle();
	$('.popup-contents-year').hide();
	if($(this).hasClass('selected'))
	{
	$(this).removeClass('selected');
	} 
	else 
	{
	$(this).addClass('selected');
	}
	});
$(".months-row").live("click", function(){ //normal view
	var selected_text =$(this).text().trim();
	document.getElementById("month-select").value = selected_text;
	});
$(document).bind('click', function(e) {
	var $clicked = $(e.target);
	if(($clicked.hasClass("popup-contents-month")) || ($clicked.parent().hasClass("month-year-popup")) || ($clicked.parent().parent().hasClass("month-year-popup")) || ($clicked.parent().hasClass("selected")))
	{
		if(($clicked.parent().hasClass("month-select")))
		{
			var id = $clicked.parent().text($(this).attr('id')).trim();
			switch (id)
			{
			case "January":
			{
			document.getElementById("month").value = "01";
			break;
			}
			case "February":
			{
			document.getElementById("month").value = "02";
			break;
			}
			case "March":
			{
			document.getElementById("month").value = "03";
			break;
			}
			case "April":
			{
			document.getElementById("month").value = "04";
			break;
			}
			case "May":
			{
			document.getElementById("month").value = "05";
			break;
			}
			case "June":
			{
			document.getElementById("month").value = "06";
			break;
			}
			case "July":
			{
			document.getElementById("month").value = "07";
			break;
			}
			case "August":
			{
			document.getElementById("month").value = "08";
			break;
			}
			case "September":
			{
			document.getElementById("month").value = "09";
			break;
			}
			case "October":
			{
			document.getElementById("month").value = "10";
			break;
			}
			case "November":
			{
			document.getElementById("month").value = "11";
			break;
			}
			case "December":
			{
			document.getElementById("month").value = "12";
			break;
			}
			}
		
			$('.popup-contents-month').hide();
			$('.arrow-months').removeClass('selected');
		}
		else
		{
			$('.popup-contents-month').show();
		}
	}
	else  
	{
		$('.popup-contents-month').hide();
		$('.arrow-months').removeClass('selected');
	}
	});

$(".arrow-year").live("click", function(){ //normal view
	var left = $('.arrow-year').offset().left;
	var top  = $('.arrow-year').offset().top; 
	$('.popup-contents-year').css({"left":left-135,"top":top+43});
	$('.popup-contents-year').toggle();
	$('.popup-contents-month').hide();
	if($(this).hasClass('selected1'))
	{
	$(this).removeClass('selected1');
	} 
	else 
	{
	$(this).addClass('selected1');
	}
	});

	
	$(".year-row").live("click", function(){ //normal view
	var selected_text1 =$(this).text().trim();
	document.getElementById("date").value = selected_text1;
	});

	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if(($clicked.hasClass("popup-contents-year")) || ($clicked.parent().hasClass("month-year-popup1")) || ($clicked.parent().parent().hasClass("month-year-popup1")) || ($clicked.parent().hasClass("selected1")))
		{
			if(($clicked.parent().hasClass("year-select")))
			{
				$('.popup-contents-year').hide();
				$('.arrow-year').removeClass('selected1');
			}
			else
			{	
				$('.popup-contents-year').show();
			}
		}
		else  
		{
		$('.popup-contents-year').hide();
		$('.arrow-year').removeClass('selected1');
		}
		});
</script>
	            
	