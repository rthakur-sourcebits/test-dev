<?php defined('SYSPATH') or die('No direct script access.');
/**
 * ACHD library for one time and recurring payment
 * This is used to process one time and recurring payment
 * @package    Core
 * @author     harish
 * @copyright
 * @license
 * @created date 28/05/2010
 */

class Ach {
	
	private $output_url;
	private $test = TRUE; //TRUE - test , FALSE- live
	private $response_type;
	private $response_code;
	private $response_description;
	private $process_result;
	private $clean_data;
	private $transaction_number;
	private $pg_merchant_id;	 
	private	$pg_total_amount;	
	private	$pg_client_id;
	private $pg_authorization_code;
	
	private $MerchantID;  
	private $ApiPassword;  			
    private $ApiLoginID; 		
    private $SecureTransactionKey; 
    private $payment_token_url;
    
    

   function __construct($merchant_login,$is_test_mode=FALSE)
    {
    	if(is_object($merchant_login)) {
	     	//if (empty($merchant_login->ach_gateway_id) || empty($merchant_login->apli_login_id) || empty($merchant_login->transaction_key) )
	     	if (empty($merchant_login->ach_gateway_id) || empty($merchant_login->ach_gateway_password))
	        {
	            throw new AchdirectCMIException("You have not configured your Payments Gateway login credentials.");
	        }
	       
	       	$this->MerchantID 			=  $merchant_login->ach_gateway_id;
        	$this->ApiPassword			=  $merchant_login->ach_gateway_password;
        	$this->ApiLoginID			=  $merchant_login->apli_login_id;
        	$this->SecureTransactionKey =  $merchant_login->transaction_key; 
	        
     	} else if(is_array($merchant_login)){   
     		
     		if (empty($merchant_login['ach_gateway_id']) || empty($merchant_login['ach_gateway_password']))
	        {
	            throw new AchdirectCMIException("You have not configured your Payments Gateway login credentials.");
	        }
	        
	       	$this->MerchantID 			=  $merchant_login['ach_gateway_id'];
        	$this->ApiPassword			=  $merchant_login['ach_gateway_password'];
        	$this->ApiLoginID			=  $merchant_login['apli_login_id'];
        	$this->SecureTransactionKey =  $merchant_login['transaction_key'];
     	}
        
     	
     	$this->test = $is_test_mode;
     	
     	     	
        if($this->test == TRUE) { 
        	$this->location 			= "https://sandbox.paymentsgateway.net/WS/Client.svc/Basic";
        	$this->url 					= "https://sandbox.paymentsgateway.net/WS/Client.wsdl";
        	#$this->payment_token_url    = "https://sandbox.paymentsgateway.net/WS/CMIPM.aspx";
        	$this->payment_token_url    = "https://sandbox.paymentsgateway.net/SWP/co/capture.aspx";
        	
        } else { 
        	$this->location 			= "https://ws.paymentsgateway.net/Service/v1/Client.svc/Basic";
        	$this->url 					= "https://ws.paymentsgateway.net/Service/v1/Client.wsdl";
        	#$this->payment_token_url    = "https://ws.paymentsgateway.net/Service/v1/CMIPM.aspx";
			$this->payment_token_url = "https://swp.paymentsgateway.net/co/capture.aspx";
        }	
        
        
     }

	
     /*
      * Function to create CLIENT TOKEN
      * param 	- client_details array('client_id','first_name','last_name')
      * returns response from payment gateway
      */
     public function create_client_token($client_details)
     {
     		$addedtime 			= $this->get_utc_time();
			$Authentication 	= $this->get_authentication($addedtime);	
        
						
			$client 			= new SoapClient($this->url, array("location" => $this->location));
    		$client->MerchantID = $this->MerchantID;
    		$client->ClientID   = $client_details['client_id'];
    		$client->FirstName  = $client_details['first_name'];
    		$client->LastName   = $client_details['last_name'];
    		
    		if(isset($client_details['company']) && $client_details['company'] != '') {
    			$client->CompanyName = $client_details['company'];
    		} else if(isset($client_details['primary_email']) && $client_details['primary_email'] != "") {
    			$client->CompanyName = $client_details['primary_email'];
    		}
    		if(isset($client_details['billing_zipcode']) && $client_details['billing_zipcode'] != '') $client->PostalCode = $client_details['billing_zipcode'];
    		if(isset($client_details['address1']) && $client_details['address1'] != '') $client->Address1 = $client_details['address1'];
    		if(isset($client_details['address2']) && $client_details['address2'] != '') $client->Address2 = $client_details['address2'];
    		if(isset($client_details['billing_city']) &&  $client_details['billing_city'] != '') $client->City = $client_details['billing_city'];
    		if(isset($client_details['billing_state']) && $client_details['billing_state'] != '') $client->State = $client_details['billing_state'];
    		if(isset($client_details['billing_phone']) && $client_details['billing_phone'] != '') $client->PhoneNumber = $client_details['billing_phone'];
    		//if(isset($client_details['primary_email']) && $client_details['primary_email'] != '') $client->EmailAddress = $client_details['primary_email'];
     		try
		    {
		        $params   = array("ticket" => $Authentication,"client"=>$client);
		        $response = $client->createClient($params);
		        return $response->createClientResult;
		    }
		    catch (SoapFault $fault)
		    {
		     // trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
		     return  $fault->faultstring;
		    }
     }
     
     /*
      * Function to update the client billing address
      * parama - 
      * returns - 
      */
     public function update_client_billing_details($client_token,$billing_details)
     {
     	$addedtime 			= $this->get_utc_time();
		$Authentication 	= $this->get_authentication($addedtime);	
     	
		$client = new SoapClient($this->url, array("location" => $this->location));
		
		$client->MerchantID = $this->MerchantID;
		$client->ClientID   = $client_token;
		$client->FirstName	= $billing_details['first_name'];
		
		if(isset($billing_details['billing_zipcode']) && $billing_details['billing_zipcode'] != '') $client->PostalCode = $billing_details['billing_zipcode'];
    	if(isset($billing_details['address1']) && $billing_details['address1'] != '') $client->Address1 = $billing_details['address1'];
    	if(isset($billing_details['address2']) && $billing_details['address2'] != '') $client->Address2 = $billing_details['address2'];
    	if(isset($billing_details['billing_city']) &&  $billing_details['billing_city'] != '') $client->City = $billing_details['billing_city'];
    	if(isset($billing_details['billing_state']) && $billing_details['billing_state'] != '') $client->State = $billing_details['billing_state'];
		
		 $params   = array("ticket" => $Authentication,"client"=>$client);
		 try {
			 $response = $client->updateClient($params);
			 	
			 if(is_soap_fault($response)) {
	    		return	$response->faultstring;  
	     	 } else {
	    		return  $response->updateClientResult; 
	    	 }
		 } catch(Exception $e) {
		   return $e->faultstring;
		 
		 }
     }
    
     /*
      * Function to create the payment token of a respective customer
      * param   - client_id,client_token and merchant_id(session id)
      * returns - out put will be posted to retun url
      * 
      */
     public function create_payment_token($client_token, $sale_id, $customer_id, $save_card_flag)
     {
    		
     		$utc_time   = $this->get_utc_time();
 		    $added_data = $this->ApiLoginID."|".$utc_time;
 		    $thash 		= $this->hmac($this->SecureTransactionKey,$added_data); 
		    
 		 // $url = $this->payment_token_url."?TSHash=".$thash."&APILoginID=".$this->ApiLoginID."&UTCTime=".$utc_time."&style=1&fontcolor=869299&fontweight=700&fontsize=12px&btncolor=&echo=yes&default=yes&clientid=".$client_token."&btntext1=Submit&refid=".$client_id.'-'.$merchant_id."&postback=http://".$_SERVER['HTTP_HOST']."/ach_payment_response&payment_type=cc&pg_template_id=3";
 		  $url = $this->payment_token_url."?TSHash=".$thash."&APILoginID=".$this->ApiLoginID."&UTCTime=".$utc_time."&style=1&fontcolor=869299&font=Arial,sans-serif&fontweight=700&fontsize=13px&btncolor=&echo=yes&default=yes&clientid=".$client_token."&btntext1=Submit&refid=".$sale_id.'-'.$customer_id.'-'.$save_card_flag."&postback=http://".$_SERVER['HTTP_HOST']."/achpaymentresponse&payment_type=cc&pg_template_id=4";
 		 # if($url == "Request is expired.")
 		  		#$this->create_payment_token($client_token,$client_id,$merchant_id,$page='c');
 		  return $url; 
     }
     
     
	 /*
      * Function to create the payment token of a respective customer using eCheck
      * param   - client_id,client_token and merchant_id(session id)
      * returns - out put will be posted to retun url
      * 
      */
     public function create_payment_token_echeck($client_token,$client_id,$merchant_id,$page='c')
     {
    		
     		$utc_time   = $this->get_utc_time();
 		    $added_data = $this->ApiLoginID."|".$utc_time;
 		    $thash 		= $this->hmac($this->SecureTransactionKey,$added_data); 
		    
 		  $url = $this->payment_token_url."?TSHash=".$thash."&APILoginID=".$this->ApiLoginID."&UTCTime=".$utc_time."&style=1&fontcolor=869299&font=Arial,sans-serif&fontweight=700&fontsize=13px&btncolor=&echo=yes&default=yes&clientid=".$client_token."&btntext1=Submit&refid=".$client_id.'-'.$merchant_id.'-'.$page."&postback=http://".$_SERVER['HTTP_HOST']."/ach_payment_response&payment_type=echeck&pg_template_id=4";
 		  return   $url; 
     }
     
     
     
     // Create merchant payment token
     public function create_merchant_payment_token($client_token, $merchant_id,$customer_id,$plan_id) 
     {
     
  
    	 $utc_time   = $this->get_utc_time();
 		 $added_data = $this->ApiLoginID."|".$utc_time;
 		 $thash 	 = $this->hmac($this->SecureTransactionKey,$added_data); 
 		 if($_POST['upgrade_plan_merchant_id'] == "0") { // Check merchant upgrade his plan from settings page, 0-new registeration, value-upgrades plan
		 	$url 		 = $this->payment_token_url."?TSHash=".$thash."&APILoginID=".$this->ApiLoginID."&UTCTime=".$utc_time."&style=1&fontcolor=869299&fontweight=700&fontsize=12px&btncolor=&echo=yes&default=yes&clientid=".$client_token."&btntext1=Submit&refid=".$merchant_id.'-'.$customer_id.'-'.$plan_id."&postback=http://".$_SERVER['HTTP_HOST']."/ach_merchant_payment_response&payment_type=cc&pg_template_id=3";
 		 } else {
		 	$url 		 = $this->payment_token_url."?TSHash=".$thash."&APILoginID=".$this->ApiLoginID."&UTCTime=".$utc_time."&style=1&fontcolor=869299&fontweight=700&fontsize=12px&btncolor=&echo=yes&default=yes&clientid=".$client_token."&btntext1=Submit&refid=".$merchant_id.'-'.$customer_id.'-'.$plan_id.'-1'."&postback=http://".$_SERVER['HTTP_HOST']."/ach_merchant_payment_response&payment_type=cc&pg_template_id=3";
 		 }
		 return $url; 
     }
     
     
	// Create merchant payment token to update
     public function create_merchant_payment_update_token($client_token,$client_id,$merchant_id,$page)
     {
    	  $utc_time   	= $this->get_utc_time();
 		  $added_data	= $this->ApiLoginID."|".$utc_time;
 		  $thash 		= $this->hmac($this->SecureTransactionKey,$added_data); 
		  $url 			= $this->payment_token_url."?TSHash=".$thash."&APILoginID=".$this->ApiLoginID."&UTCTime=".$utc_time."&style=1&fontcolor=869299&font=Arial,sans-serif&fontweight=700&fontsize=13px&btncolor=&echo=yes&default=yes&clientid=".$client_token."&btntext1=Submit&refid=".$client_id.'-'.$merchant_id.'-'.$page."&postback=http://".$_SERVER['HTTP_HOST']."/ach_response_merchant_payment_update&payment_type=cc&pg_template_id=4";
 		  return $url; 
     }
	 /*
      * Function to create the payment token of a respective customer
      * param   - client_id,client_token and merchant_id(session id)
      * returns - out put will be posted to retun url
      * 
      */
     public function webservice_create_payment_token($client_token,$stream_reference,$call_back_url)
     {
    		
     		$utc_time   = $this->get_utc_time();
 		    $added_data = $this->ApiLoginID."|".$utc_time;
 		    $thash 		= $this->hmac($this->SecureTransactionKey,$added_data); 
		    
 		    $url 		= $this->payment_token_url."?TSHash=".$thash."&APILoginID=".$this->ApiLoginID."&UTCTime=".$utc_time."&style=1&fontcolor=869299&fontweight=700&fontsize=12px&btncolor=&echo=yes&default=yes&clientid=".$client_token."&btntext1=Submit&refid=".$stream_reference."&postback=".$call_back_url."&payment_type=cc&pg_template_id=3";
 		    return   $url; 
     }
     
     /*
      * Function to create payment method
      * param - client_id,merchant_id,client_token
      * returns - payment token
      * 
      */
     public function create_payment_method($client_details)
     {
     		$addedtime 					= $this->get_utc_time();
			$Authentication 			= $this->get_authentication($addedtime);	
        
			$client 					= new SoapClient($this->url, array("location" => $this->location));
    		$client->MerchantID 		= $this->MerchantID;
    		$client->ClientID   		= $client_details['client_token'];
    		$client->AcctHolderName  	= $client_details['account_holder_name'];
    		$client->CcCardNumber    	= $client_details['card_number'];
    		$client->CcExpirationDate   = $client_details['card_expire_date'];
    		$client->CcCardType    		= $client_details['card_type'];
    		$client->Note     			= $client_details['merchant_id'];
    		#$client->IsDefault			= '1';
    		$client->PaymentMethodID    = '';
     		
   		   try
		    {
		        $params   = array("ticket" => $Authentication,"payment"=>$client);
		        
		        $response = $client->createPaymentMethod($params);
		       
		        return $response->createPaymentMethodResult;
		    }
		    catch (SoapFault $fault)
		    {
		   	  return  $fault->faultstring;
		    }
     
     }
     
     
     /*
      * Function to get payment method(credit card) details
      * param -  client_token,payment_token
      * returns - details of credit card
      */
     
     public function get_payment_details($client_token,$payment_token)
     {
     	//$this->MerchantID
     	$addedtime 		= $this->get_utc_time();
     	$Authentication = $this->get_authentication($addedtime);

     	$payment		= new SoapClient($this->url, array("location" => $this->location));
     	
     	$params 		 = array("ticket" => $Authentication, "MerchantID" =>$this->MerchantID,"ClientID"=>$client_token, "PaymentMethodID" => $payment_token );
     	$payment_details = $payment->getPaymentMethod($params);
     	return $payment_details;
    }
    
    /*
     * Function to update payment method(credit card) details
     * param 	- merchant_id,client_token,payment_token,
     * returns 	- Payment method ID 
     */
    public function update_payment_method($card_details,$client_token,$payment_token)
    {
    	$response 		= '';
    	$addedtime 		= $this->get_utc_time();
     	$Authentication = $this->get_authentication($addedtime);
     	
     	$payment = new SoapClient($this->url, array('location' => $this->location,'trace'=>1,'exceptions' => 0));
	 	
	 	$payment->AcctHolderName 		= $card_details['name_on_card'];
   		$payment->CcExpirationDate 		= $card_details['card_exp_date']; 
    	//$payment->IsDefault 			= true;
   		//$payment->CcProcurementCard 	= true;
   	 	//$payment->Note 				= "Insert Note";
    	$payment->ClientID 				= $client_token;
    	$payment->MerchantID 			= $this->MerchantID;
    	$payment->PaymentMethodID 		= (int) $payment_token;
    	
    	$params = array("ticket" => $Authentication, "payment" => $payment );
    	
    	$response = $payment->updatePaymentMethod($params);
    
    	if(is_soap_fault($response)) {
    		return	$response->faultstring;  
    	} else {
    		return  $response->updatePaymentMethodResult; 
    	}
      
    }
    
    /*
     * Function to update payment method(eCheck) details
     * param 	- merchant_id,client_token,payment_token,
     * returns 	- Payment method ID 
     */
    public function update_payment_method_echeck($card_details,$client_token,$payment_token)
    {
    	$response 		= '';
    	$addedtime 		= $this->get_utc_time();
     	$Authentication = $this->get_authentication($addedtime);
     	
     	$payment = new SoapClient($this->url, array('location' => $this->location,'trace'=>1,'exceptions' => 0));
	 	
     	#$card_details['name_on_card'] = 'test';
     	#$payment->AcctHolderName 		= $card_details['name_on_card'];
   		$payment->EcAccountType  		= $card_details['EcAccountType']; 
    	
    	$payment->ClientID 				= $client_token;
    	$payment->MerchantID 			= $this->MerchantID;
    	$payment->PaymentMethodID 		= (int) $payment_token;
    	
    	$params = array("ticket" => $Authentication, "payment" => $payment );
    	
    	$response = $payment->updatePaymentMethod($params);
    
    	if(is_soap_fault($response)) {
    		return	$response->faultstring;  
    	} else {
    		return  $response->updatePaymentMethodResult; 
    	}
      
    }
    
    public function getClient($client_token)
    {
    	$response 		= '';
    	$addedtime 		= $this->get_utc_time();
     	$Authentication = $this->get_authentication($addedtime);
     	 
     	
      	
     	$payment = new SoapClient($this->url, array('location' => $this->location,'trace'=>1,'exceptions' => 0));
    	#$payment->ClientID 				= $client_token;
    	#$payment->MerchantID 			= $this->MerchantID;
   		$params = array("ticket" => $Authentication, "MerchantID" => $this->MerchantID, "ClientID" =>  $client_token);
    	
    	$response = $payment->getClient($params);
    
    	if(is_soap_fault($response)) {
    		return	$response->faultstring;  
    	} else {
    		return  $response; 
    	}
     	
    }
    
    
    /*
     * Function to delete payment method from ACHD merchant account
     * 
     */
    public function delete_payment_method($payment_token)
    {
    	$response 		= '';
    	$addedtime 		= $this->get_utc_time();
     	$Authentication = $this->get_authentication($addedtime);
    
    	$payment = new SoapClient($this->url, array('location' => $this->location,'trace'=>1,'exceptions' => 0));
    	
    	$payment->MerchantID  		= $this->MerchantID;
   		$payment->PaymentMethodID  	= (int) $payment_token; 
   		
    	$params 	= array("ticket" => $Authentication);
    	$response 	= $payment->deletePaymentMethod($params,$payment->MerchantID, $payment->PaymentMethodID);
    
    	if(is_soap_fault($response)) {
    		return	$response->faultstring;  
    	} else {
    		/** CHECK THE BELOW RESP FOR delete_payment_method **/
    		return  $response->updatePaymentMethodResult; 
    	}
    	
    }
       
     /*
      * Function to process payment using respective client token and payment token
      */
     public function process_payment($client_token,$payment_token,$amount)
     {
      	 $output_transaction = "pg_merchant_id=".$this->MerchantID."&pg_password=".$this->ApiPassword."&pg_transaction_type=10&pg_total_amount=".$amount."&pg_client_id=".$client_token."&pg_payment_method_id=".$payment_token."&endofdata&";
 	 	
 	 	# $this->setParameter('test',TRUE);
 	 	 $this->setParameter('output_data',$output_transaction);
 	 	
 	 	
 	  	 $this->process();
 	 	
 	 	if( $this->get_response_type() == 'A') {
 	 		$response = array();
 	 		$response['resopnse_code'] 				= $this->get_response_code();
 	 		$response['resopnse_descripiton'] 		= $this->get_response_description();
      		$response['resopnse_trsnaction_number']	= $this->get_transaction_number();
 	 	} else {
 	 		$response['resopnse_descripiton'] 		= $this->get_response_description();
 	 	}
 	 	return $response;
     }
     
     
     /*
      * Function to process payment using respective client token and payment token using eCheck
      */
     public function process_payment_echeck($client_token,$payment_token,$amount)
     {
      	 $output_transaction = "pg_merchant_id=".$this->MerchantID."&pg_password=".$this->ApiPassword."&pg_transaction_type=20&pg_total_amount=".$amount."&pg_client_id=".$client_token."&pg_payment_method_id=".$payment_token."&endofdata&";
 	 	
 	 	# $this->setParameter('test',TRUE);
 	 	 $this->setParameter('output_data',$output_transaction);
 	 	
 	 	
 	  	 $this->process();
 	 	
 	 	if( $this->get_response_type() == 'A') {
 	 		$response = array();
 	 		$response['resopnse_code'] 				= $this->get_response_code();
 	 		$response['resopnse_descripiton'] 		= $this->get_response_description();
      		$response['resopnse_trsnaction_number']	= $this->get_transaction_number();
 	 	} else {
 	 		$response['resopnse_descripiton'] 		= $this->get_response_description();
 	 	}
 	 	return $response;
     }
     
     
     
     //Function to get authentication object
 	public function get_authentication($addedtime)
     {
     		$Authentication 					  = new Authentication();
         	$Authentication->APILoginID 		  = $this->ApiLoginID;  
        	$Authentication->SecureTransactionKey = $this->SecureTransactionKey;
        	$Authentication->TSHash  			  = $this->hmac($this->SecureTransactionKey,$this->ApiLoginID."|".$addedtime);
        	$Authentication->UTCTime			  = $addedtime; 
     
     		return $Authentication;
     }
     
     
     //Function to get UTC tick time
     private function get_utc_time()
     {
     		$time = time();
			$multiplied = $time * 10000000; //adjust to microseconds
			$addedtime = $multiplied + 621355968000000000; //adjust date from epoch to .net. not exact but close.
			$time = time() + 62135596800;
		 	$addedtime = $time.'0000000';
		 	
		 	return $addedtime;
     } 
     
     
    //Function to do HMAC encryption 
	public function hmac ($key, $data)
	{
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing
		
		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
		$key = pack("H*",md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;
		
		return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	}
	
	//To get hierarchy tree of merchant
	public function auth_merchant_details()
	{
		$response 		= '';
    	$addedtime 		= $this->get_utc_time();
     	$Authentication = $this->get_authentication($addedtime);
     	//die("start");
     	if($this->test == TRUE) {
     		$this->url = 'https://sandbox.paymentsgateway.net/WS/Merchant.wsdl';
     		$this->location = 'https://sandbox.paymentsgateway.net/WS/Merchant.svc/Basic';
      	} else {
     		$this->url = 'https://ws.paymentsgateway.net/Service/v1/Merchant.wsdl';
     		$this->location = 'https://ws.paymentsgateway.net/Service/v1/Merchant.svc/Basic';
     	}	
     //	die("hello");
     	$payment = new SoapClient($this->url, array('location' => $this->location,'trace'=>1,'exceptions' => 0));
	 	
		$params  = array("ticket" => $Authentication, "MerchantID" => $this->MerchantID );
    	
    	$response = $payment->getHierarchyTree($params);
    	
    	if(is_soap_fault($response)) {
    		return	$response->faultstring;  
    	} else {
    		return  $response; 
    	}
	
	}
	
	/*
	 * Function to check MerchantID and Processing password
	 * param - MerchantID,Password
	 */
	Public function check_merchant_password()
	{
		$response 		= '';
    	$addedtime 		= $this->get_utc_time();
     	$Authentication = $this->get_authentication($addedtime);
     	$trans_num 		= '00000000-0000-0000-0000-000000000000';
     	$auth_code 		= '123456';
     	
     	$this->setParameter('output_data','pg_merchant_id='.$this->MerchantID.'&pg_password='.$this->ApiPassword.'&pg_transaction_type=14&pg_original_trace_number='.$trans_num.'&pg_original_authorization_code='.$auth_code.'&endofdata&');
     	
     	 $this->process();
		 $response = array();
		 if( $this->get_response_type() == 'A') {
 	 		$response['response_type']				= $this->get_response_type();
 	 		$response['response_code'] 				= $this->get_response_code();
 	 		$response['response_descripiton'] 		= $this->get_response_description();
      		$response['response_transaction_number']= $this->get_transaction_number();
      		$response['response_authorization_code']= $this->get_authorization_code();
 	 	} else {
 	 		$response['response_type']				= $this->get_response_type();
 	 		$response['response_code'] 				= $this->get_response_code();
 	 		$response['response_descripiton'] 		= $this->get_response_description();
      		$response['response_transaction_number']= $this->get_transaction_number();
 	 	}
 	 	//changed the return value from $response['response_code'] to $response
 	 	return $response;
	
	}
	
	/*
	 * Function To get the transaction status
	 * param - transction_id
	 * return - transaction status
	 * 
	 */
	public function get_transaction_details($transactionID)
	{
		
		$response 		= '';
    	$addedtime 		= $this->get_utc_time();
     	$Authentication = $this->get_authentication($addedtime);
     	
		if($this->test == TRUE) {
			$this->url 		= "https://sandbox.paymentsgateway.net/WS/Transaction.wsdl";
			$this->location = "https://sandbox.paymentsgateway.net/WS/Transaction.svc/Basic";	
		} else {
			$this->url 		= "https://ws.paymentsgateway.net/Service/v1/Transaction.wsdl";
			$this->location = "https://ws.paymentsgateway.net/Service/v1/Transaction.svc/Basic";
		}
			
    	try
        {
            $client = new SoapClient($this->url, array('location' => $this->location));
            $params = array('ticket' => $Authentication, 'MerchantID' =>$this->MerchantID, 'TransactionID' => $transactionID );
            $response = $client->getTransaction($params);
            return $response->getTransactionResult->Response->Status;
        }
        catch (Exception $e)
        {            
            echo $e->getMessage();
        }
	}
	
	/*
	 * Function To process void transaction
	 * param - transction_number,authorization_code
	 * return - success/failure
	 */
	public function void_transaction($trans_num,$auth_code,$account_type='cc')// default type CC  
	{
		if($account_type == 'cc') {
		 	$this->setParameter('output_data','pg_merchant_id='.$this->MerchantID.'&pg_password='.$this->ApiPassword.'&pg_transaction_type=14&pg_original_trace_number='.$trans_num.'&pg_original_authorization_code='.$auth_code.'&endofdata&');
		} else 	if($account_type == 'echeck') {
			$this->setParameter('output_data','pg_merchant_id='.$this->MerchantID.'&pg_password='.$this->ApiPassword.'&pg_transaction_type=24&pg_original_trace_number='.$trans_num.'&pg_original_authorization_code='.$auth_code.'&endofdata&');	
		}  else {
			$this->setParameter('output_data','pg_merchant_id='.$this->MerchantID.'&pg_password='.$this->ApiPassword.'&pg_transaction_type=14&pg_original_trace_number='.$trans_num.'&pg_original_authorization_code='.$auth_code.'&endofdata&');
		}
		
		 $this->process();
		 $response = array();
		 if( $this->get_response_type() == 'A') {
 	 		$response['response_type']				= $this->get_response_type();
 	 		$response['response_code'] 				= $this->get_response_code();
 	 		$response['response_descripiton'] 		= $this->get_response_description();
      		$response['response_transaction_number']= $this->get_transaction_number();
      		$response['response_authorization_code']= $this->get_authorization_code();
 	 	} else {
 	 		$response['response_type']				= $this->get_response_type();
 	 		$response['response_code'] 				= $this->get_response_code();
 	 		$response['response_descripiton'] 		= $this->get_response_description();
      		$response['response_transaction_number']= $this->get_transaction_number();
 	 	}
 	 	return $response;
	}
	
	/*
	 * Function To process credit transaction
	 * param - client_token,payment_token,first_name,last_name,amount
	 * return - success/failure
	 */
	public function credit_transaction($client_token,$payment_token,$first_name,$last_name,$amount,$account_type)
	{
		if($account_type == 'cc'){
			$this->setParameter('output_data','pg_merchant_id='.$this->MerchantID.'&pg_password='.$this->ApiPassword.'&pg_transaction_type=13&pg_total_amount='.$amount.'&ecom_billto_postal_name_first='.$first_name.'&ecom_billto_postal_name_last='.$last_name.'&pg_client_id='.$client_token.'&pg_payment_method_id='.$payment_token.'&endofdata&');
		} else 	if($account_type == 'echeck'){
			$this->setParameter('output_data','pg_merchant_id='.$this->MerchantID.'&pg_password='.$this->ApiPassword.'&pg_transaction_type=23&pg_total_amount='.$amount.'&ecom_billto_postal_name_first='.$first_name.'&ecom_billto_postal_name_last='.$last_name.'&pg_client_id='.$client_token.'&pg_payment_method_id='.$payment_token.'&endofdata&');
		} else {
			$this->setParameter('output_data','pg_merchant_id='.$this->MerchantID.'&pg_password='.$this->ApiPassword.'&pg_transaction_type=13&pg_total_amount='.$amount.'&ecom_billto_postal_name_first='.$first_name.'&ecom_billto_postal_name_last='.$last_name.'&pg_client_id='.$client_token.'&pg_payment_method_id='.$payment_token.'&endofdata&');
		}
 	    $this->process();
 	    
 	    $response = array();
		 if( $this->get_response_type() == 'A') {
 	 		$response['response_type']				= $this->get_response_type();
 	 		$response['response_code'] 				= $this->get_response_code();
 	 		$response['response_descripiton'] 		= $this->get_response_description();
      		$response['response_transaction_number']= $this->get_transaction_number();
      		$response['response_authorization_code']= $this->get_authorization_code();
 	 	} else {
 	 		$response['response_type']				= $this->get_response_type();
 	 		$response['response_code'] 				= $this->get_response_code();
 	 		$response['response_descripiton'] 		= $this->get_response_description();
      		$response['response_transaction_number']= $this->get_transaction_number();
 	 	}
 	 	return $response;
		
	}

	
	//To process method and return result
	public function process() 
	{
		if($this->test == TRUE) {
			$this->output_url = 'https://www.paymentsgateway.net/cgi-bin/posttest.pl';
		} else {
			$this->output_url = 'https://www.paymentsgateway.net/cgi-bin/postauth.pl';
		}
		
		// setup curl
		$ch = curl_init ($this->output_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	   	curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml")); 
	   	curl_setopt($ch, CURLOPT_HEADER, 1); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params['output_data']);
		$this->process_result = curl_exec ($ch);
		curl_close ($ch);
		
		// clean response data of whitespace, convert newline to ampersand for parse_str function and trim off endofdata
		$this->clean_data = str_replace("\n","&",trim(str_replace("endofdata", "", trim($this->process_result))));
						
		// parse the string into variable name = variable data
		parse_str($this->clean_data);
			
		$this->response_type 			= $pg_response_type; 
		$this->response_code			= $pg_response_code; 
		$this->response_description		= $pg_response_description;
		$this->transaction_number		= $pg_trace_number;
		if(isset($pg_authorization_code)) {
			$this->pg_authorization_code = $pg_authorization_code; 
		}
		if(isset($pg_merchant_id)) {
			$this->mearchant_id			= $pg_merchant_id;
		}		 
		if(isset($pg_total_amount)) {
			$this->transaction_amount	= $pg_total_amount;
		}		
		if(isset($pg_client_id)) {
			$this->client_token			= $pg_client_id;
		} 
	}
	
 	 public function setParameter($field = "", $value = null)
 	 { 
     	$field = (is_string($field)) ? trim($field) : $field; 
     	$value = (is_string($value)) ? trim($value) : $value; 
     	$this->params[$field] = $value; 
     }
     
     public function get_response_type()
     {
     	return $this->response_type;
     }
     
	 public function get_response_code()
     {
     	return $this->response_code;
     }
     
	public function get_authorization_code()
     {
     	return $this->pg_authorization_code;
     }
     
     
	 public function get_response_description()
     {
     	return $this->response_description;
     }
     
     public function get_transaction_number()
     {
     	return $this->transaction_number;
     }
	
     public function get_transaction_amount() 
     {
		return	$this->transaction_amount;	
     }
     
     public function get_client_token()
     {
     	return $this->client_token;
     }
}
class Authentication
{    
    public $APILoginID;
    public $SecureTransactionKey;
}
class AchdirectCMIException extends Exception {}