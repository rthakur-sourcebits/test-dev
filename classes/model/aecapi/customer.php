<?php defined('SYSPATH') or die('No direct script access.');
/*
 * @Class: Model AEC API/customer.php
 * @Created: 21-10-2013
 * @Modified: 21-10-2013 
 * @Description: This model file uses for JSON or AJAX action by AEC_API.
 * Update : <21-10-2013> 	: 	added a functionality for updating the customer value.
 * 			<21-10-2013> 	:	added a functionality for get_customer_to_sync_all_api
 * 			<21-10-2013> 	:	added a functionality for check_record_aec
 * 			<21-10-2013> 	:	added a functionality for check_record
 * 			<21-10-2013> 	:	added a functionality for customer_exists
 * 			<21-10-2013> 	:	added a functionality for delete_data
 * 			<23-10-2013> 	:	added a functionality for process_aed_ack
 */
 
 //////////////////////////////		CONSTANTS
 define ("INSERT", 0);
 define ("UPDATE", 1);
 define ("DELETE", 2);
 define ("HANDSHAKE", 3);
 define ("SKIP", 4);
  
 //////////////////////////////		CLASS
class Model_Aecapi_Customer extends Model
{
	
	// Function to get all customer list to be SYNCED
	public function get_customer_to_sync_all_api( $company_id ) {		
		$sql_select	=	"SELECT * 
						FROM customers 
						WHERE company_id = '".$company_id."' AND (synced_status = '0' OR record_id = '0')"; // for inserted and updated data.
		
		$result_select	=	DB::query(Database::SELECT, $sql_select)->execute()->as_array();
		$return_data=NULL;
		
		foreach ($result_select as $key => $cutomer_data) {
			$return_data[$key] = array();
			
			if(isset($cutomer_data['is_card_inactive']) 	&& $cutomer_data['is_card_inactive'] !='' && $cutomer_data['is_card_inactive'] == 1) {	$return_data[$key]['IsCardInactive'] = TRUE; } 
			if(isset($cutomer_data['company_or_lastname']) 	&& $cutomer_data['company_or_lastname'] !='') { 	$return_data[$key]['CompanyOrLastName'] = $cutomer_data['company_or_lastname'];}
			if(isset($cutomer_data['expiration_date']) 		&& $cutomer_data['expiration_date'] !='') { 		$return_data[$key]['ExpirationDate'] = $cutomer_data['expiration_date'];}
			if(isset($cutomer_data['last_digits_on_card']) 	&& $cutomer_data['last_digits_on_card'] !='') { 	$return_data[$key]['Last4DigitsOnCard'] = $cutomer_data['last_digits_on_card'];} 
			if(isset($cutomer_data['name_on_card']) 		&& $cutomer_data['name_on_card'] !='') { 			$return_data[$key]['NameOnCard'] = $cutomer_data['name_on_card'];}
			if(isset($cutomer_data['firstname']) 			&& $cutomer_data['firstname'] !='') { 				$return_data[$key]['FirstName'] = $cutomer_data['firstname'];}
			if(isset($cutomer_data['payment_method']) 		&& $cutomer_data['payment_method'] !='') { 			$return_data[$key]['PaymentMethod'] = $cutomer_data['payment_method'];}
			if(isset($cutomer_data['billing_rate']) 		&& $cutomer_data['billing_rate'] !='' 	&& $cutomer_data['billing_rate'] != 0 ) { 			$return_data[$key]['BillingRate'] = $cutomer_data['billing_rate'];} 
			if(isset($cutomer_data['contact']) 				&& $cutomer_data['contact'] !='') { 				$return_data[$key]['Addr1ContactName'] = $cutomer_data['contact'];}
			if(isset($cutomer_data['tax_record_id']) 		&& $cutomer_data['tax_record_id'] !='' 	&& $cutomer_data['tax_record_id'] != 0 ) { 			$return_data[$key]['TaxCodeRecordID'] = $cutomer_data['tax_record_id'];}
			if(isset($cutomer_data['type']) 				&& $cutomer_data['type'] !='') { 					$return_data[$key]['TypeOfCard'] 	= $cutomer_data['type'];}
			if(isset($cutomer_data['custom_list1']) 		&& $cutomer_data['custom_list1'] !='') { 			$return_data[$key]['CustomList1'] 	= $cutomer_data['custom_list1'];}
			if(isset($cutomer_data['custom_list2']) 		&& $cutomer_data['custom_list2'] !='') { 			$return_data[$key]['CustomList2'] 	= $cutomer_data['custom_list2'];}
			if(isset($cutomer_data['custom_list3']) 		&& $cutomer_data['custom_list3'] !='') { 			$return_data[$key]['CustomList3'] 	= $cutomer_data['custom_list3'];}
			if(isset($cutomer_data['custom_field1']) 		&& $cutomer_data['custom_field1'] !='') { 			$return_data[$key]['CustomField1'] 	= $cutomer_data['custom_field1'];}
			if(isset($cutomer_data['custom_field2']) 		&& $cutomer_data['custom_field2'] !='') { 			$return_data[$key]['CustomField2'] 	= $cutomer_data['custom_field2'];}
			if(isset($cutomer_data['custom_field3']) 		&& $cutomer_data['custom_field3'] !='') { 			$return_data[$key]['CustomField3'] 	= $cutomer_data['custom_field3'];}
			if(isset($cutomer_data['city']) 				&& $cutomer_data['city'] !='') { 					$return_data[$key]['Addr1City'] 	= $cutomer_data['city'];}
			if(isset($cutomer_data['street1']) 				&& $cutomer_data['street1'] !='') { 				$return_data[$key]['Addr1Line1'] 	= $cutomer_data['street1'];}
			if(isset($cutomer_data['street2']) 				&& $cutomer_data['street2'] !='') { 				$return_data[$key]['Addr1Line2'] 	= $cutomer_data['street2'];}
			if(isset($cutomer_data['phone']) 				&& $cutomer_data['phone'] !='') { 					$return_data[$key]['Addr1Phone1'] 	= $cutomer_data['phone'];}
			if(isset($cutomer_data['state']) 				&& $cutomer_data['state'] !='') { 					$return_data[$key]['Addr1State'] 	= $cutomer_data['state'];}
			if(isset($cutomer_data['email']) 				&& $cutomer_data['email'] !='') { 					$return_data[$key]['Addr1Email'] 	= $cutomer_data['email'];}
			if(isset($cutomer_data['country']) 				&& $cutomer_data['country'] !='') { 				$return_data[$key]['Addr1Country'] 	= $cutomer_data['country'];}
			if(isset($cutomer_data['zip']) 					&& $cutomer_data['zip'] !='') { 					$return_data[$key]['Addr1ZipCode'] 	= $cutomer_data['zip'];}
			if(isset($cutomer_data['account']) 				&& $cutomer_data['account']!='') { 					$return_data[$key]['Account'] 		= $cutomer_data['account'];}
			if(isset($cutomer_data['id']) 					&& $cutomer_data['id']!='') { 						$return_data[$key]['AecId'] 		= $cutomer_data['id'];}
			if(isset($cutomer_data['record_id']) 			&& $cutomer_data['record_id']!='' && $cutomer_data['record_id']!=0) { 				$return_data[$key]['RecordID'] 		= $cutomer_data['record_id'];}
			
			// AEC changes operation.
			if(isset($cutomer_data['record_id']) 			&& $cutomer_data['record_id'] != '0'){
				$return_data[$key]['op'] = 'update';
			} else {
				$return_data[$key]['op'] = 'insert';
			}
		}
		
		$sql_update = " UPDATE customers
				 		SET synced_status	=	'1'
				 		WHERE 	company_id 	= '".$company_id."' AND synced_status = '0'";
		$result_update	=	DB::query(Database::UPDATE, $sql_update)->execute();// updating all UN-SYNC data
		return $return_data;
	}
	
	 /**
	 * Function to checking the record with AEC ID. 
	 * 
	 * @param 	$AEC id.
	 * 			return true // if sync_status = 1 and record_id = ""
	  * 		return false // if record doesn't exists or record_id != ""  
	 */
	 /*
	 private function check_record_aec($record_id){
			  $sql_query 	= "	SELECT  synced_status
							 FROM customers 
							 WHERE id='".$record_id."'";
			 $res 		= $this->_db->query(Database::SELECT, $sql_query, False)->as_array();
							if(isset($res[0]['synced_status'])){
				 return TRUE;
								} else {
				 return FALSE;
								}		
		  }*/
	 
	 
	  	/**
	 * Function to check if the record exitst of the given record id.
	 * Description: check if record exists from given record_id.
	 * created: 02-07-2013
	 * @param 	string $module.
	 * 			int $record_id
		 * 		int $company_id    
	 * 			return synced_status value
	 */
	 private function check_record( $record_id, $company_id){
		$sql_query 	= "	SELECT  synced_status
						FROM customers
						WHERE record_id = '".$record_id."' AND company_id = '".$company_id."'";
		$res 		= $this->_db->query(Database::SELECT, $sql_query, False)->as_array();
		
		if(isset($res)&&!empty($res))
			return $res[0];
		else 
			return FALSE;
	 }
	 
	 /**
	 * Function to check customer already present or not
	 *
	 * @param unknown_type $customer_id
	 * @param unknown_type $company_id
	 * @return unknown
	 */
	private function customer_exists($customer_id, $company_id) {
		$sql	=	"SELECT id
					 FROM customers
					 WHERE record_id = '".addslashes($customer_id)."'
					 AND company_id = '".addslashes($company_id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return true;
	}
	
	/**
	 * @Method			:	delete_data
	 * @Description		:	we will delete the data based on company id and the module given.
	 * @Return			:	Nothing
	 */
	public function delete_data($company_id, $module){
		if( isset($company_id) && isset($module) ) {
			switch ($module) {
				case 'customer':
					$module = 'customers';
					break;
				
				default:
					return "module name is not proper.";
					break;
			}
			// we need to delete AED sync data i.e. if sync = 1 OR if record_id exists thats is RECORD ID?
			$delete_sql = "	DELETE 
							FROM `$module`
							WHERE `company_id` = $company_id
									AND (synced_status	=	'1'
									OR 
									record_id != '0'
									)";
			$result		= DB::query(Database::DELETE, $delete_sql)->execute();
			return $result;
		} else{
			return "company id or module name is not set properly";
		}		
	}
	 
	/**
	 * 
	 * Function to process the AED acknowledgement
	 * Description: This function is used to receive the acknowledgement about the AEC changes.
	 * 				case 1: AEC insert acknowledgement.
	 * 				case 2: AEC update & its reject from AED.
	 * 				case 3: AEC delete operation acknowledgement (no implementing now.) 
	 *
	 * @param 	@company_id, 
	 * 			@acks
	 * 						RecordID, AECId, Choice, t_op, data	
	 * 
	 */
	private function process_aed_ack($acks) {
		if(isset($acks)){
			foreach($acks as $data){
				$sql='';
				switch($data['t_op']){
					case 'insert': 	// insert ack from AED
									// update the AED-ID and make the sync = 1.
									if($data['result']=='TRUE'){// insert accepted
										$arr_data=array(
														'record_id' =>$data['RecordID'],
														'updated_date'		=> date('Y-m-d H:i:s'),
												 		'synced_status'		=>	'1');												
										$result = DB::update('customers')->set($arr_data)->where('id', '=', $data['AecId'])->execute();
										//echo '------------------ AEC ACK INSERT ------------------------------------';
									} else if($data['result']=='FALSE'){ // 
										$result = DB::delete('customers')->where('id', '=', $data['AecId'])->execute();
										//echo '------------------ AEC ACK INSERT DELETE ------------------------------------';
									}
									
									break;
					case 'update':	// update ack from AED
									// update data which is received from AED.
									if($data['result']=='FALSE'){// update is rejected
										// query to update all the values.
										if(isset($data['data']['IsCardInactive']) && ($data['data']['IsCardInactive'] == "1" || $data['data']['IsCardInactive'] == "true" || $data['data']['IsCardInactive'] == true)){
											$arr_data['is_card_inactive']	= "1";
										} else{
											$arr_data['is_card_inactive']	= "0";
										}
										if(isset($data['data']['IsIndividualCard']) && ($data['data']['IsIndividualCard'] == "1" || $data['data']['IsIndividualCard'] == "true" || $data['data']['IsIndividualCard'] == true)){
											$arr_data['is_individual_card']	= "1";
										} else{
											$arr_data['is_individual_card']	= "0";
										}
										if(isset($data['data']['CompanyOrLastName'])){ 	$arr_data['company_or_lastname']	= addslashes($data['data']['CompanyOrLastName']); }
										if(isset($data['data']['ExpirationDate'])){		$arr_data['expiration_date']		= addslashes($data['data']['ExpirationDate']);	}
										if(isset($data['data']['Last4DigitsOnCard'])){	$arr_data['last_digits_on_card']	= addslashes($data['data']['Last4DigitsOnCard']); 	}
										if(isset($data['data']['NameOnCard'])){			$arr_data['name_on_card']	= addslashes($data['data']['NameOnCard']);	}
										if(isset($data['data']['FirstName'])){			$arr_data['firstname']		= addslashes($data['data']['FirstName']);	}
										if(isset($data['data']['PaymentMethod'])){		$arr_data['payment_method']	= addslashes($data['data']['PaymentMethod']);	}
										if(isset($data['data']['BillingRate'])){		$arr_data['billing_rate']	= addslashes($data['data']['BillingRate']);	}
										if(isset($data['data']['Addr1ContactName'])){	$arr_data['contact']	= addslashes($data['data']['Addr1ContactName']); }
										if(isset($data['data']['TaxCodeRecordID'])){	$arr_data['tax_record_id']	= addslashes($data['data']['TaxCodeRecordID']);	}
										if(isset($data['data']['TypeOfCard'])){			$arr_data['type']	= addslashes($data['data']['TypeOfCard']); 	}
										if(isset($data['data']['CustomList1'])){		$arr_data['custom_list1']	= addslashes($data['data']['CustomList1']);	}
										if(isset($data['data']['CustomList2'])){		$arr_data['custom_list2']	= addslashes($data['data']['CustomList2']);	}
										if(isset($data['data']['CustomList3'])){		$arr_data['custom_list3']	= addslashes($data['data']['CustomList3']);	}
										if(isset($data['data']['CustomField1'])){		$arr_data['custom_field1']	= addslashes($data['data']['CustomField1']);	}
										if(isset($data['data']['CustomField2'])){		$arr_data['custom_field2']	= addslashes($data['data']['CustomField2']);	}
										if(isset($data['data']['CustomField3'])){		$arr_data['custom_field3']	= addslashes($data['data']['CustomField3']);	}
										if(isset($data['data']['Addr1City'])){		$arr_data['city']		= addslashes($data['data']['Addr1City']);	}
										if(isset($data['data']['Addr1Line1'])){		$arr_data['street1']	= addslashes($data['data']['Addr1Line1']);	}
										if(isset($data['data']['Addr1Line2'])){		$arr_data['street2']	= addslashes($data['data']['Addr1Line2']);	}
										if(isset($data['data']['Addr1Phone1'])){	$arr_data['phone']	= addslashes($data['data']['Addr1Phone1']);	}
										if(isset($data['data']['Addr1State'])){		$arr_data['state']	= addslashes($data['data']['Addr1State']);	}
										if(isset($data['data']['Addr1Email'])){		$arr_data['email']	= addslashes($data['data']['Addr1Email']);	}
										if(isset($data['data']['Addr1Country'])){	$arr_data['country']	= addslashes($data['data']['Addr1Country']);	}
										if(isset($data['data']['Addr1ZipCode'])){	$arr_data['zip']		= addslashes($data['data']['Addr1ZipCode']);	}
										if(isset($data['data']['Account'])){		$arr_data['account']	= addslashes($data['data']['Account']);	}
										if(isset($data['data']['RecordID'])){ 		$arr_data['record_id']	= addslashes($data['data']['RecordID']);	}
										$arr_data['updated_date']	= date('Y-m-d H:i:s');
										$arr_data['synced_status']	= '1';
										$result = DB::update('customers')->set($arr_data)->where('id', '=', $data['AecId'])->execute();
										//echo '------------------ AEC ACK UPDATE ------------------------------------';
									}
									
									break;
					case 'delete': // this we will implement later.
									break;
					default:
							// do nothing  	
				}
			}
		}		
	}
	
	/**
	 * 
	 * Function to process the AED changes
	 * Description: This function is used to receive the AED changes and will process the data based on the changes.
	 * 				 
	 *
	 * @param @company_id, @contents 
	 */
	private function process_aed_changes($company_id, $data) {
		// if(!isset($data['t_id'])||empty($data['t_id'])||$data['t_id']==""){ // TID should be present with very record.
			// $response['tid']='';
			// $response['result']= "data skipped: T_id not present";
			// continue;
		// }
		
		if ( isset($data['data']['Addr1Email'])){
			$data['data']['Addr1Email']=strtolower($data['data']['Addr1Email']);
		}	
		
		$is_card_inactive		=	(isset($data['data']['IsCardInactive']) && ($data['data']['IsCardInactive'] == "1" || $data['data']['IsCardInactive'] == "true" || $data['data']['IsCardInactive'] == true))?"1":"0";
		$is_individual_card		=	(isset($data['data']['IsIndividualCard']) && ($data['data']['IsIndividualCard'] == "1" || $data['data']['IsIndividualCard'] == "true" || $data['data']['IsIndividualCard'] == true))?"1":"0";
		$company_or_lastname	=	empty($data['data']['CompanyOrLastName'])?"":$data['data']['CompanyOrLastName'];
		$expire_days			=	empty($data['data']['ExpirationDate'])?"":$data['data']['ExpirationDate'];
		$last_digits_on_card	=	empty($data['data']['Last4DigitsOnCard'])?"":$data['data']['Last4DigitsOnCard'];
		$name_on_card			=	empty($data['data']['NameOnCard'])?"":$data['data']['NameOnCard'];
		$firstname				=	empty($data['data']['FirstName'])?"":$data['data']['FirstName'];
		$payment_method			=	empty($data['data']['PaymentMethod'])?"":$data['data']['PaymentMethod'];
		$record_id				=	empty($data['data']['RecordID'])?"":$data['data']['RecordID'];
		$billing_rate			=	empty($data['data']['BillingRate'])?"":$data['data']['BillingRate'];
		
		$contact				=	empty($data['data']['Addr1ContactName'])?"":$data['data']['Addr1ContactName'];
		$tax_record_id			=	empty($data['data']['TaxCodeRecordID'])?"":$data['data']['TaxCodeRecordID'];
		$type_of_card			=	empty($data['data']['TypeOfCard'])?"":$data['data']['TypeOfCard'];
		$custom_list1			=	empty($data['data']['CustomList1'])?"":$data['data']['CustomList1'];
		$custom_list2			=	empty($data['data']['CustomList2'])?"":$data['data']['CustomList2'];
		$custom_list3			=	empty($data['data']['CustomList3'])?"":$data['data']['CustomList3'];
		$custom_field1			=	empty($data['data']['CustomField1'])?"":$data['data']['CustomField1'];
		$custom_field2			=	empty($data['data']['CustomField2'])?"":$data['data']['CustomField2'];
		$custom_field3			=	empty($data['data']['CustomField3'])?"":$data['data']['CustomField3'];
		
		$city					=	empty($data['data']['Addr1City'])?"":$data['data']['Addr1City'];
		$street1				=	empty($data['data']['Addr1Line1'])?"":$data['data']['Addr1Line1'];
		$street2				=	empty($data['data']['Addr1Line2'])?"":$data['data']['Addr1Line2'];
		$phone					=	empty($data['data']['Addr1Phone1'])?"":$data['data']['Addr1Phone1'];
		$state					=	empty($data['data']['Addr1State'])?"":$data['data']['Addr1State'];
		$email					=	empty($data['data']['Addr1Email'])?"":$data['data']['Addr1Email'];
		$country				=	empty($data['data']['Addr1Country'])?"":$data['data']['Addr1Country'];
		$zipcode				=	empty($data['data']['Addr1ZipCode'])?"":$data['data']['Addr1ZipCode'];
		$account				=	empty($data['data']['Account'])?"":$data['data']['Account'];
		
		$record_id 				= 	empty($data['RecordID'])?"":$data['RecordID'];
		
		$operation				=	SKIP;
		$sync_status['synced_status']	=	NULL;
		if(isset($data['t_op'])){
			switch($data['t_op']){
				case "insert":								
						/*
						if(isset($data['RecordID'])&&!empty($data['RecordID'])){ // AEC insert (acknowledgement) // we are checking if the data is there and also AEC ID
													if($this->check_record_aec($data['RecordID'])){ // check if data exist with aed id
														$operation	= HANDSHAKE;
													} else {
														$operation	= SKIP;
													}
												}else */
						if(isset($data['data'])&&!empty($data['data'])){			// AED insert (new record)
							$sync_status=$this->check_record($record_id, $company_id);
							if(isset($sync_status['synced_status'])){
								if((int)$sync_status['synced_status']==1){ // data exist + synced => update the data.
									$operation = UPDATE;
								} else if ((int)$sync_status['synced_status']==0){	 // data exist + not synced => skipped the data.
									$operation = SKIP;
								}
							} else{
								$operation	= INSERT;
							}
						} else {// insert from AED
							$operation	= SKIP;
						}
						
						break;

				case "update":
						if( (isset($data['data'])&&!empty($data['data'])) ){ // Data rejected from AEC
							$sync_status	= $this->check_record( $record_id, $company_id);
							
							// for both update from AED or update from AEC will have same case. 				
							if(isset($sync_status['synced_status']) && (int)$sync_status['synced_status']==1){  // AED data will get updated here   
								$operation	= UPDATE;
							} else { 	
								$operation	= SKIP;
							}
						} else{
							$operation	= SKIP;
						}
						break;
						
				case "delete":
						// later
						if(isset($data['RecordID']) && !empty($data['RecordID'])){ // AEC will delete the data.
							$operation	= DELETE;
							
						}else{
							if(isset($data['result']) && !empty($data['result'])){
								if($data['result'] == TRUE){ // AEC will delete the record
									$operation	= DELETE;
								}else{// AEC will un-mark the delete operation
									// still its to-do.
								}										
							}else{
								// error : choice is not set so, we can decide a default action for this data.
							}
						}
						break;
						
				default:
						break;
			}
		} else{
			// echo "error invalid operation"
		}
		
		if($operation == UPDATE	&&	$this->customer_exists($record_id, $company_id)) {
			$database_type	= DATABASE::UPDATE;
			$sql	=	"UPDATE customers
						 SET company_or_lastname = '".addslashes($company_or_lastname)."',
						 	 account	=	'".addslashes($account)."',
						 	 contact		=	'".addslashes($contact)."',
						 	 tax_record_id	=	'".addslashes($tax_record_id)."',
						 	 type			=	'".addslashes($type_of_card)."',
						 	 custom_list1	=	'".addslashes($custom_list1)."',
						 	 custom_list2	=	'".addslashes($custom_list2)."',
						 	 custom_list3	=	'".addslashes($custom_list3)."',
						 	 custom_field1	=	'".addslashes($custom_field1)."',
						 	 custom_field2	=	'".addslashes($custom_field2)."',
						 	 custom_field3	=	'".addslashes($custom_field3)."',
						 	 
						 	 street1	=	'".addslashes($street1)."',
						 	 street2	=	'".addslashes($street2)."',
						 	 city		=	'".addslashes($city)."',
						 	 state		=	'".addslashes($state)."',
						 	 country	=	'".addslashes($country)."',
						 	 zip		=	'".addslashes($zipcode)."',
						 	 email		=	'".addslashes($email)."',
						 	 phone		=	'".addslashes($phone)."',
						 	 billing_rate			=	'".addslashes($billing_rate)."',
						 	 expiration_date 		= '".addslashes($expire_days)."',
						 	 is_card_inactive 		= '".$is_card_inactive."',
						 	 is_individual_card 	= '".$is_individual_card."',
						 	 last_digits_on_card	= '".addslashes($last_digits_on_card)."',
						 	 name_on_card 			= '".addslashes($name_on_card)."',
						 	 firstname 				= '".addslashes($firstname)."',
						 	 payment_method 		= '".addslashes($payment_method)."',
						 	 updated_date 			= now(),
						 	 synced_status			=	'1',
						 	 status					=	'1'
						 WHERE record_id = '".$record_id."' AND company_id = '".$company_id."'";
						 
		} 
		if($operation == INSERT){
			$database_type	= DATABASE::INSERT;
			$sql	=	"INSERT INTO customers 
						 (company_id, company_or_lastname, account, contact, tax_record_id, type, 
						  custom_list1, custom_list2, custom_list3, custom_field1, custom_field2, custom_field3,
						  street1, street2, city, state, country, zip, email, phone, 
						  billing_rate, expiration_date, is_card_inactive, is_individual_card, last_digits_on_card, name_on_card, firstname, 
						  record_id, payment_method, created_date, updated_date, synced_status)
						 VALUES (
							'".$company_id."',
							'".addslashes($company_or_lastname)."',
							'".addslashes($account)."',
							'".addslashes($contact)."',
						 	'".addslashes($tax_record_id)."',
						 	'".addslashes($type_of_card)."',
						 	'".addslashes($custom_list1)."',
						 	'".addslashes($custom_list2)."',
						 	'".addslashes($custom_list3)."',
						 	'".addslashes($custom_field1)."',
						 	'".addslashes($custom_field2)."',
						 	'".addslashes($custom_field3)."',
							'".addslashes($street1)."',
							'".addslashes($street2)."',
							'".addslashes($city)."',
							'".addslashes($state)."',
							'".addslashes($country)."',
							'".addslashes($zipcode)."',
							'".addslashes($email)."',
							'".addslashes($phone)."',
							'".addslashes($billing_rate)."',
							'".addslashes($expire_days)."',
							'".addslashes($is_card_inactive)."',
							'".addslashes($is_individual_card)."',
							'".addslashes($last_digits_on_card)."',
							'".addslashes($name_on_card)."',
							'".addslashes($firstname)."',
							'".$record_id."',
							'".addslashes($payment_method)."',
							now(),
							now(),
							'1'
						 )";
		}
		if($operation == HANDSHAKE){
			// add the AED ID in database as record id
			$database_type	= DATABASE::UPDATE;
			$sql 			= " UPDATE customers
								 SET record_id = '".addslashes($record_id)."',							 	 
								 	 updated_date = now(),
								 	 synced_status	=	'1' 	
								 WHERE 	id = '".$data['RecordID']."'";
		}
		if($operation == DELETE){
			$database_type	= DATABASE::DELETE;
			$sql 			= " DELETE FROM customers
								 WHERE record_id = '".$record_id."'
								 AND company_id = '".$company_id."'";
		}
		
		//$response['t_id']	= $data['t_id'];
				
		// response from customer.
		if($operation == INSERT	||	$operation == UPDATE	||	$operation == DELETE	||	$operation == HANDSHAKE){
			$result					= $this->_db->query($database_type, $sql, False);
			$response['result']	= TRUE;
		} else {
			// do nothing : skip record.
			$response['result']	= TRUE;
		}
		
		$presentation_res['operation']			= !isset($data['t_op'])?"":$data['t_op'];
		//$presentation_res['t_id']					= !isset($data['t_id'])?"":$data['t_id'];
		$presentation_res['operation_performed']	= !isset($operation)?"":$operation;
		$presentation_res['record_id']			= !isset($record_id)?"":$record_id;
		$presentation_res['company_id']			= !isset($company_id)?"":$company_id;
		$presentation_res['sync_status']			= !isset($sync_status["synced_status"])?"":$sync_status["synced_status"];
		
		$return_data['response'] 			= $response;
		$return_data['presentation_res'] 	= $presentation_res;
		
		return $return_data;
	}
	
	/**
	 * 
	 * Function to insert/update/delete AEC customer json files
	 * Description: This function also check if there is any conflicts if the data is been updated.
	 *
	 * @param 
	 */
	public function update_customers($company_id, $contents) {
		$res			= array(); 
		$database_type 	= DATABASE::INSERT;  //default type.
		
		if(count($contents)>0){
			foreach($contents as $key=> $c) {
				
				if(!strcmp($key,"master")) // skip the master in the receive JSON file.
				{
					continue;
				}
				else if(!strcmp($key,"aed_ack")) // skip the master in the receive JSON file.
				{
					$this->process_aed_ack( $c ); // this function will not return any-response to AED.
				} 
				else // AED changes. 
				{
					$res[$key] = $this->process_aed_changes( $company_id, $c );
					// we will modify this.
					$presentation_res[$key]	= $res[$key]['presentation_res'];
					$res[$key]				= $res[$key]['response'];
				}
			}
		}
		
		if(isset($presentation_res)){
			$res1[0]	= $presentation_res;
		} else {
			$res1[0]	= NULL;
		}
		$res1[1] = $res; 
		return $res1;
	}
	
} 
