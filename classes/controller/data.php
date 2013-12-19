<?php defined('SYSPATH') or die('No direct script access.');
/*
 * @Class		: Controller_Activity
 * @Author		: Durga Malleswar
 * @Created		: 
 * @Modified	: 
 * @Description	: This class file holds the operations of activity slips.
 * Copyright (c) 2011 Acclivity Group LLC
 */
class Controller_Data extends Controller
{
	/*
	 * @Function 	: action_time
	 * @Decription	: return the time value.
	 */
	public function action_time()
	{
		$data_time = array('time' =>  date('h:i:s'));
		echo json_encode($data_time);
	}
	
	/*
	 * @Function 	: action_id
	 * @Decription	: return if the string is matched in the table. This function will be called from new actvity form slipp by selection of customer/activity
	 */
	public function action_id()
	{ 
		$activity_model =	new Model_activity();
		$emp_model		=	new Model_employee();
		$table_name 	=	$_GET['table'];
		$name 			=	$_GET['name'];
		$customerId		=	isset($_GET['c_id'])?$_GET['c_id']:null;
		$employee_id	=	isset($_GET['employee_id']) ? $_GET['employee_id']:null;
		
		switch($table_name)
		{
			case 'activity':
				$field_name = 'ActivityID';
				$table_name	= 'Activities';
			break;
		
			case 'customer':
				//$field_name = 'CompanyOrLastName';
				$field_name = 'RecordID';
				$table_name	= 'Customers';
				$name		=	$customerId;
			break;
			
			case 'job':
				$field_name = 'JobNumber';
				$table_name	= 'Jobs';
			break;	
							  				
			case 'payroll':
				$field_name = 'payroll_category_name';
				$table_name = 'payroll_category';
			break;		
							  			  	
			default:
				$field_name ='';
			break;						
		}
	    $data = $activity_model->get_id($table_name,$field_name,$name);  // gets the data from the table that is given
	    if ($data)
		{
			foreach($data as $row)
			{
				$data	=	$row; // fetch result 
			}
			
			switch($table_name)
			{ 
				case 'Activities': // if selected field is activity then find correspnding rate value
					$rate	=	'';
					$which_rate = isset($data['WhichRateToUse'])?$data['WhichRateToUse']:null;
					// rate value will be based on WhichRateToUse in activities.json
					
					if ($which_rate == 'A' || $which_rate == null || empty($which_rate))
					{ // A means rate value id ActivityRate in activity.json
						
						if (isset($data['ActivityRate']))
						{
							$rate = $data['ActivityRate'];	
						}
						elseif(isset($data['ActivityRate '])) 
						{
							$rate = $data['ActivityRate '];	
						}
					}
					elseif ($which_rate == 'E')
					{ // E means BillingRate value from employee.json
						
						/*if($_SESSION['User_type'] == "Admin") {
							$rate	=	0;
						} else {
							$employee = $activity_model->get_id($_SESSION['User_type'], "RecordID", $_SESSION['employee_id']);
	                      	
							if ($employee)
							{
	                      		$employee = array_merge(array(), $employee);                      					
	                            $rate = $employee[0]['BillingRate'];
							}	
						}*/
							$rate = $emp_model->get_billing_rate($_SESSION['company_id'],$employee_id);
						
					}
					elseif ($which_rate == 'C')
					{ // C means BillingRate value form customers.json
						if ($customerId == null)
						{
							return;
						}
						
						$customer = $activity_model->get_id('Customers', "RecordID", $customerId);
						
						if ($customer)
						{
						    $customer = array_merge(array(), $customer);    
						  	$rate = $customer[0]['BillingRate'];	
						}						
					}
					$data_id = array(	'id'					=> $data['ActivityID'],
										'IsActivityNonHourly'	=> $data['IsActivityNonHourly'],
				  						'which_rate'			=> $which_rate,
										'rate'					=> $rate	);
				break;
				
				case 'Customers': // if customer then check rate value with activity value
						
						if(isset($_GET['activity']) && $_GET['activity'] != "")
						{
							//echo $_GET['activity'];die;
							$act_data = $activity_model->get_id('Activities','ActivityID',$_GET['activity']); // get actviity details if it already selected			
						//	echo "<pre>";count($act_data);die;
							foreach($act_data as $act_row)
							{
								$act_id_data	=	$act_row;
							}
							
							$which_rate = isset($act_id_data['WhichRateToUse'])?$act_id_data['WhichRateToUse']:null;
							
							if ($which_rate == 'C')
							{
								if ($customerId == null)
								{
									return;
								}
								
								$customer = $activity_model->get_id('Customers', "RecordID", $customerId);
								
								if ($customer)
								{
								    $customer = array_merge(array(), $customer);    
								  	$rate = $customer[0]['BillingRate'];	
								}	
								$data_id = array(	'id'					=> $data['RecordID'],
													'rate'					=> $rate	);				
							}
							else {
								$data_id = array(	'id' => $data['RecordID']);
							}
						}
						else {
							$data_id = array(	'id' => $data['RecordID']);
						}
				break;
				
				case 'Jobs': // return job id by job name
					$data_id = array(	'id' => $data['JobNumber']);
				break;
									  				
				case 'payroll_category': // return payroll id with payroll name
					$data_id = array(	'id' => $data['payroll_category_id']);
				break;	
									  			  	
				default:
					$field_name ='';
				break;
						
			}
		}
		else
		{
			$data_id = array('id' => 'other' );
		}
		
		echo json_encode($data_id); // send the result to AJAX result set
	}
/* End of Class */
	
	/**
	 * @Access		:	Public
	 * @Function	:	validate
	 * @Descripton	:	to validate the customers
	 */
	public function validate()
	{
		$element	=	$_POST['element'];
		switch($element)
		{
			case 'customer' :
				$customer	=	$_POST['customer'];
				$json		= 	new Model_json;	
				$json_model->file_content 	=	$_SESSION['Customers'];//$employees;
				$list						=	$json_model->JSON_Query('*', array('CompanyOrLastName' => $customer));
				if(empty($list))
				{
					$result[0]['error']		=	1;
				} else {
					$result[0]['success']	=	1;
				}
				break;
				
			case 'activity' :
				$activity	=	$_POST['activity'];
				$json		= 	new Model_json;	
				$json_model->file_content 	=	$_SESSION['Activities'];//$employees;
				$list						=	$json_model->JSON_Query('*', array('ActivityName' => $activity));
				if(empty($list))
				{
					$result[0]['error']		=	1;
				} else {
					$result[0]['success']	=	1;
				}
				break;
				
			case 'job' :
				$job		=	$_POST['job'];
				$json		= 	new Model_json;	
				$json_model->file_content 	=	$_SESSION['Jobs'];//$employees;
				$list						=	$json_model->JSON_Query('*', array('JobName' => $job));
				if(empty($list))
				{
					$result[0]['error']		=	1;
				} else {
					$result[0]['success']	=	1;
				}
				break;
		}	
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	get_billing_rate_employee
	 * @Descripton	:	getting billing rate
	 */
	public function action_get_billing_rate_employee($record_id){
		$emp_model		=	new Model_employee();
		$billing_rate	=	$emp_model->get_billing_rate($_SESSION['company_id'],$record_id);
		$billing_rate	=	number_format((float)$billing_rate, 2, '.', '');
		echo $billing_rate;die;
	}
}

