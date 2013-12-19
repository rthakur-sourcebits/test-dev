<?php //defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Ach payment response file
 * @package    Core
 * @Copyright (c) 2011 Acclivity Group LLC
 * @created date 11/08/2010
 */

class Controller_Achpaymentresponse extends Controller_Template 
{
	
	private $ach;
	public function action_index()
 	{
 		require_once Kohana::find_file('classes', 'library/Sync');
		$sync_lib		=	new Sync;	
 		$sales					=	new Model_Sales;
 		$customer_token_model	=	new Model_Customertoken;
 		$transaction_model		=	new Model_Transaction;
 		$customer_jobs			=	new Model_Customerandjobs;
 		if(isset($_POST) && count($_POST)>0 && $_POST['pmid'] != '') {
			$payment_token  = $_POST['pmid'];
			$referance_id   = $_POST['refid']; // customer_id - merchant_id - page_name
			$referance_id	= explode('-',$referance_id); 
			$sale_id 		= $referance_id[0];
		 	$customer_id	= $referance_id[1]; 
		 	$save_card_flag	= $referance_id[2];
		 	$payment_method	= '';
		 	/*if($save_card_flag == "1") {
				$data 	= array('payment_token'	=>	$payment_token,
								'updated_date'	=>	date('Y-m-d H:i:s'));
				$result = $customer_token_model->update($customer_id,$data,$_SESSION['company_id']);
				$card_exp_month = substr($_POST['exp'],4,2);
		  		$card_exp_year  = substr($_POST['exp'],2,2);
				switch ($_POST['card_type']){
					case 'VISA': $payment_method = 'Visa';
					break;
					case 'MAST': $payment_method = 'MasterCard';
					break;
					case 'DISC': $payment_method = 'Discover';
					break;
					case 'AMER': $payment_method = 'American Express';
					break;
				}
				$customer_data	=	array(
										"last_digits_on_card"	=>	$_POST['last4'],
										"name_on_card"	=>	$_POST['name'],
										"expiration_date"	=>	$card_exp_month.'/'.$card_exp_year,
										"payment_method"	=>	!empty($payment_method)?$payment_method:''
									);
				$update_status	=	$customer_jobs->update('customers', $customer_data, $customer_id);
			}*/
		 	$gateway_ach_model	= new Model_Gatewayach;
			require Kohana::find_file('classes', 'library/Ach');
			$merchant_details 	= $gateway_ach_model->get_merchant_personal_ach_details($_SESSION['company_id']);
			$merchant_details	= json_decode(json_encode($merchant_details));
			$sale_info			= $sales->get_sale_details($sale_id);
			$amount				= $sale_info[0]['paid_today'];
		
			//take random numbers to make $consumer_order_id unique for each transaction
   			$consumer_order_id = rand();
			if(!empty($merchant_details)) {
				if($merchant_details[0]->ach_gateway_id != "" || $merchant_details[0]->ach_gateway_password  != "" || $merchant_details[0]->apli_login_id != "" || $merchant_details[0]->transaction_key != "") {
					$this->ach 		= new Ach($merchant_details[0], true);
					$output_transaction = "pg_merchant_id=".$merchant_details[0]->ach_gateway_id."&pg_password=".$merchant_details[0]->ach_gateway_password."&pg_transaction_type=10&pg_total_amount=".$amount."&pg_client_id=0&pg_payment_method_id=".$payment_token."&ecom_consumerorderid=".$consumer_order_id."&endofdata&";
					$data_log = array();
  				 	$data_log['request_code'] 	= $output_transaction;
  				 	$data_log['customer_id'] 	= $customer_id;
  				 	$data_log['sale_id'] 		= $sale_id;
  				 	$data_log['domain_name'] 	= $_SERVER['SERVER_NAME'];
  				 		
  				 	$transaction_log_id = $transaction_model->insert_tranasction_log('transaction_log', $data_log);
					$this->ach->setParameter('output_data',$output_transaction);
			 		//make a process
			 	  	$this->ach->process();
			 	 	
			 	  	$gateway_response_type		  =   $this->ach->get_response_type();
				 	$gateway_response_description =   $this->ach->get_response_description();
				 	$gateway_response_code 		  =   $this->ach->get_response_code();
					$gateway_transaction_num 	  =   $this->ach->get_transaction_number();
					$gateway_authorization_code   =   $this->ach->get_authorization_code();
					
					if($gateway_response_type == 'A' && $gateway_response_code == 'A01') {
						if($save_card_flag == "1") {
							$data 	= array('payment_token'	=>	$payment_token,
											'updated_date'	=>	date('Y-m-d H:i:s'));
							$result = $customer_token_model->update($customer_id,$data,$_SESSION['company_id']);
							$card_exp_month = substr($_POST['exp'],4,2);
					  		$card_exp_year  = substr($_POST['exp'],2,2);
							switch ($_POST['card_type']){
								case 'VISA': $payment_method	= 'Visa';
											 $payment_type		= 2;
								break;
								case 'MAST': $payment_method	= 'MasterCard';
											 $payment_type		= 2;
								break;
								case 'DISC': $payment_method 	= 'Discover';
											 $payment_type		= 2;
								break;
								case 'AMER': $payment_method	= 'American Express';
											 $payment_type		= 2;
								break;
							}
							$customer_data	=	array(
											"last_digits_on_card"	=>	$_POST['last4'],
											"name_on_card"			=>	$_POST['name'],
											"expiration_date"		=>	$card_exp_month.'/'.$card_exp_year,
											"payment_method"		=>	!empty($payment_method)?$payment_method:'',
											"payment_type"			=>	isset($payment_type) ? $payment_type : '0',
											"synced_status"			=>	'0'
										);
							$update_status	=	$customer_jobs->update('customers', $customer_data, $customer_id);
						}
						$transaction_details  = array('customer_id'					 => $customer_id,
												  'sale_id'							 => $sale_id,
					 	 						  'transaction_amount'		 		 => $amount,
					 	 						  'transaction_date'  			  	 => date('Y-m-d H:i:s'),
												  'gateway_transaction_id'    		 => $gateway_transaction_num,
					 	 						  'gateway_authorization_code'		 => $gateway_authorization_code,	
												  'payment_method'					 => !empty($payment_method)?$payment_method:'', //$_POST['card_type'],
												  'gateway_transaction_status'		 => $gateway_response_type,
												  'card_name'						 => $_POST['name'],
												  'last4digits'						 => $_POST['last4'],
												  'expiry_date'						 => $card_exp_month.'/'.$card_exp_year,
					 	 						  'gateway_transaction_short_message'=> $gateway_response_code, 	
					 	 						  'gateway_transaction_long_message' => $gateway_response_description
					 	 					);
						$new_transaction_id = $transaction_model->create('transaction', $transaction_details);
						$success		=	true;
						$total_amount	=	doubleval($sale_info[0]['total_payment']);
						$balance		=	($total_amount-doubleval($amount));
						$sales_data		=	array(	'payment'		=>	'1', 
													'balance' 		=>	$balance,
													'updated_date'	=>	date('Y-m-d H:i:s'));
						$sale_update	=	$sales->update("sales", $sales_data, $sale_id);
					} else if($gateway_response_type == 'E' || $gateway_response_type == 'D') { //for PAYMENT METHODID INVALID
/*
						$transaction_details  = array('customer_id'						 => $customer_id,
													  'sale_id'							 => $sale_id,
	    										 	  'transaction_amount'		 		 => $amount,
			 	 								      'transaction_date'  			 	 => date('Y-m-d H:i:s'),
													  'gateway_transaction_id'    		 => $gateway_transaction_num,
				 	 								  'gateway_authorization_code'		 => $gateway_authorization_code,
													  'payment_method'					 => !empty($payment_method)?$payment_method:'', //$_POST['card_type'],
													  'gateway_transaction_status'		 => $gateway_response_type,
				 	 								  'gateway_transaction_short_message'=> $gateway_response_code, 	
				 	 								  'gateway_transaction_long_message' => $gateway_response_description
											       );
  						$new_transaction_id = $transaction_model->create('transaction', $transaction_details);*/
  						
  						// reset the sale tables.
						$balance		=	doubleval($sale_info[0]['total_payment']);
						$sales_data		=	array(	'payment'		=>	'0', 
													'paid_today' 	=> 	'0', 
													'balance' 		=> 	$balance,
													'updated_date'	=>	date('Y-m-d H:i:s'));
						$sale_update	=	$sales->update("sales", $sales_data, $sale_id);
						
  						$success	=	false;
					}
						
					$total_amount	=	$sale_info[0]['total_payment'];
					$total_paid		=	$transaction_model->get_transaction_details_for_sale($sale_id,$customer_id);
					$balance		=	($total_amount-$total_paid);
					$sales_data		=	array(	'payment'		=>	'1', 
												'balance' 		=> 	$balance,
												'paid_today' 	=> 	$total_paid,
												'updated_date'	=>	date('Y-m-d H:i:s'));
					$sale_update	=	$sales->update("sales", $sales_data, $sale_id);
					//update transaction log table
  					 $data_log_update = array();
  					 $transaction_log_response = $amount.'-'.$gateway_transaction_num.'-'.$gateway_authorization_code.'-'.$gateway_response_type.'-'.$gateway_response_code.'-'.$gateway_response_description;
					 $data_log_update['response_data']  = $transaction_log_response;
  					 $data_log_update['transaction_id'] = $new_transaction_id;
  					 $data_log_update['created_date']   = date("Y-m-d H:i:s");
					 $data_log_update['updated_date']   = date("Y-m-d H:i:s");
  					 $transaction_model->update_transaction_log('transaction_log', $data_log_update, $transaction_log_id);
					 
  					 if($success) {
  					 	$confirm_page 	=	"/sales/payment_receipt/".$sale_id;
  					 } else {
  					 	$confirm_page	=	"/sales/edit/".$sale_id.'?e=1';
  					 }
					 echo "<script type='text/javascript'>window.top.location.href = '".$confirm_page."';</script>";
  					 
				}
			}
		}
 	}
}