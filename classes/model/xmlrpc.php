<?php

class Model_Xmlrpc extends Model {
	
	private $server_url		=	"www.devrerunapp.com";
	private $server_port	=	80;
	private $service_api	=	"";
	private $free_plan		=	92;
	public function __construct()
	{
		
		include_once DOCUMENT_ROOT.'/application/classes/vendors/xmlrpc/lib/xmlrpc.inc';
		include_once DOCUMENT_ROOT.'/application/classes/vendors/xmlrpc/lib/xmlrpcs.inc';
        include_once DOCUMENT_ROOT.'/application/classes/vendors/xmlrpc/lib/xmlrpc_wrappers.inc';
        $this->service_api	=	$this->getAPI();
	}
	
	public function pay_method($plan_id)
	{
		$sql	=	"SELECT name FROM signup_plan WHERE plan_id = '".addslashes($plan_id)."'";
		$query	=	DB::query(Database::SELECT, $sql);
		$data	=	$query->execute()->as_array();
		return $data[0]['name'];
	}
	
	
	private function getAPI()
	{
		$sql	=	"SELECT Service_API  
					 FROM superadmin_user
					";
		$query	=	DB::query(Database::SELECT, $sql);
		$data	=	$query->execute()->as_array();
		$apiKey = $data[0]['Service_API'];
		return $apiKey;
	}
	
	public function get_signup_plans()
	{
		/*$sql	=	"SELECT s.id, s.plan_id, s.name, s.price, sl.user_limit
						 FROM signup_plan as s
						 LEFT JOIN signup_plan_user_limit as sl
						 ON s.plan_id = sl.plan_id
						 WHERE 1=1
						 ORDER BY price ASC
						";
			$query	=	DB::query(Database::SELECT, $sql);
			$data	=	$query->execute()->as_array();
	
			return $data;*/
		$message = new xmlrpcmsg('sample.plan',
									array(new xmlrpcval( $this->service_api, 'string') //api_key
										 
										  )
									);
		
	
		$c = new xmlrpc_client("/server", $this->server_url, $this->server_port);
		$c->return_type = 'phpvals'; // let client give us back php values instead of xmlrpcvals
		//$c->setDebug(1);	
		$result = & $c->send($message);
		
		if($result->faultCode())
		{
			echo "<p>error '".htmlspecialchars($result->faultString())."'</p>\n";
			return false;
		}
		else
		{
			$struct = 	$result->value(); // result value
			
			$this->check_api_key($struct[0]['response_api_key'], $struct[0]);
			
			$count	=  	count($struct[0]['plan_details']);
			/**
			 * Clear all current values from table and insert new values
			 */
			$sql	=	"TRUNCATE table signup_plan";
			$query 	= 	DB::query(Database::INSERT, $sql, true)->execute();
			for($i=0;$i<$count;$i++) 
			{
				
				$sql	=	"INSERT INTO signup_plan (plan_id, name, price, description)
							 VALUES(
							 	'".addslashes($struct[0]['plan_details'][$i]['plan_id'])."',
							 	'".addslashes($struct[0]['plan_details'][$i]['plan_name'])."',
							 	'".addslashes($struct[0]['plan_details'][$i]['plan_price'])."',
							 	'".addslashes($struct[0]['plan_details'][$i]['plan_description'])."'
							 )";
				$query 	=  DB::query(Database::INSERT, $sql, true)->execute();
				// read all the new plans and return to user
				$sql	=	"SELECT plan_id FROM
							 signup_plan_user_limit
							 WHERE plan_id = '".$struct[0]['plan_details'][$i]['plan_id']."'";
				$query	=	DB::query(Database::SELECT, $sql);
				$data_usr	=	$query->execute()->as_array();
			
				if(empty($data_usr)) {
					$sql	=	"INSERT INTO signup_plan_user_limit
								 (plan_id, user_limit) VALUES (
								 	'".addslashes($struct[0]['plan_details'][$i]['plan_id'])."',
								 	'".addslashes($struct[0]['plan_details'][$i]['plan_users'])."'
								 )";
				} else {
					$sql	=	"UPDATE signup_plan_user_limit
							 SET user_limit = '".addslashes($struct[0]['plan_details'][$i]['plan_users'])."'
							 WHERE plan_id = '".$struct[0]['plan_details'][$i]['plan_id']."'";
				}
				$query 	=  DB::query(Database::INSERT, $sql, true)->execute();
			}
			$sql	=	"SELECT s.id, s.plan_id, s.name, s.price, sl.user_limit, s.description
						 FROM signup_plan as s
						 LEFT JOIN signup_plan_user_limit as sl
						 ON s.plan_id = sl.plan_id
						 WHERE 1=1
						 ORDER BY price ASC
						";
			$query	=	DB::query(Database::SELECT, $sql);
			$data	=	$query->execute()->as_array();
			
			return $data; // return all plans to user in array format
		}
	}
	
	public function get_token_value($post)
	{
		$token			=	array();
		$token['id']	=	md5(time());
		return $token;
		// function to call server function to create token
	}
	function test_payment() {
		$message 	= 	new xmlrpcmsg('sample.register',
									array(new xmlrpcval($this->service_api, 'string'), //api_key
									 	  new xmlrpcval(1, 'int'),//plan_id
										  new xmlrpcval('firstname', 'string'),//f_name
										  new xmlrpcval('lastname', 'string'),//l_name
										  new xmlrpcval('company name', 'string'),
										  new xmlrpcval('mail@email.com', 'string'),
										  new xmlrpcval('address1', 'string'),
										  new xmlrpcval('address2', 'string'),
										  new xmlrpcval('city', 'string'),
										  new xmlrpcval('state', 'string'),
										  new xmlrpcval('country', 'string'),
										  new xmlrpcval('444555', 'string'),
										  new xmlrpcval('989898989', 'string'),
										  new xmlrpcval('ddddddddddde', 'string'),
   										  new xmlrpcval('123456789098', 'string'))
									);
		
		$c = new xmlrpc_client("/server", $this->server_url, $this->server_port);
		$c->return_type = 'phpvals'; // let client give us back php values instead of xmlrpcvals
		$c->setDebug(1);	
		$result =& $c->send($message);
		//die("coming2");
		echo "<pre>";print_r($result);die;
	}
	public function payment_form()
	{
		$old_ref_id	=	empty($_POST['ref_id'])?'':$_POST['ref_id'];
		$message 	= 	new xmlrpcmsg('sample.register',
									array(new xmlrpcval($this->service_api, 'string'), //api_key
									 	  new xmlrpcval($_POST['plan'], 'int'),//plan_id
										  new xmlrpcval($_POST['name'], 'string'),//f_name
										  new xmlrpcval($_POST['lastname'], 'string'),//l_name
										  new xmlrpcval($_POST['cname'], 'string'),
										  new xmlrpcval($_POST['UserEmail'], 'string'),
										  new xmlrpcval($_POST['address'], 'string'),
										  new xmlrpcval($_POST['address2'], 'string'),
										  new xmlrpcval($_POST['city'], 'string'),
										  new xmlrpcval($_POST['state'], 'string'),
										  new xmlrpcval($_POST['country_name'], 'string'),
										  new xmlrpcval($_POST['zipcode'], 'string'),
										  new xmlrpcval($_POST['phone'], 'string'),
										  new xmlrpcval($old_ref_id, 'string'),
   										  new xmlrpcval($_POST['serialnumber'], 'string'))
									);
		
		$c = new xmlrpc_client("/server", $this->server_url, $this->server_port);
		$c->return_type = 'phpvals'; // let client give us back php values instead of xmlrpcvals
		//$c->setDebug(1);	
		$result =& $c->send($message);
		//die("coming2");
		//echo "<pre>";print_r($result);die;
		if($result->faultCode())
		{
			echo "<p>error '".htmlspecialchars($result->faultString())."'</p>\n";
		}
		else
		{
			#print '<pre>';
			#print_r($result); die; 
			$struct = $result->value();
			//var_dump($struct);die;
			//$this->check_api_key($struct[0]['response_api_key']);
			$this->check_api_key($struct[0]['response_api_key'], $struct[0]);
			return $struct[0];
			//echo "<pre>";print_r($struct[0]);die;
		
		}
	}
	
	// update customer api
	public function update_customer_api($post, $reference_id, $country) {
		
		$message 	= 	new xmlrpcmsg('sample.update_customer',
									array(new xmlrpcval($this->service_api, 'string'), //api_key
										new xmlrpcval('', 'int'),//plan
									 	  new xmlrpcval($post['name'], 'string'),//f_name
										  new xmlrpcval($post['lastname'], 'string'),//l_name
										  new xmlrpcval($post['company_name'], 'string'), //company name
										  new xmlrpcval($post['UserEmail'], 'string'), // email
										  new xmlrpcval($post['address'], 'string'), // address1
										  new xmlrpcval('', 'string'), // address2
										  new xmlrpcval($post['city'], 'string'), //city
										  new xmlrpcval($post['state'], 'string'), //state
										  new xmlrpcval($country, 'string'), //country
										  new xmlrpcval($post['zipcode'], 'string'), //zip
										  new xmlrpcval($post['phone'], 'string'), //phone
										  new xmlrpcval($reference_id, 'string'),  // reference id
										  new xmlrpcval($post['serialnumber'], 'string')) // AED serial number
									);
		
		$c = new xmlrpc_client("/server", $this->server_url, $this->server_port);
		$c->return_type = 'phpvals'; // let client give us back php values instead of xmlrpcvals
		
		$result =&$c->send($message);
		if($result->faultCode()) // check for error
		{
			throw new Exception(htmlspecialchars($result->faultString()));
		}
		else
		{
			$struct = $result->value();
			$this->check_api_key($struct[0]['response_api_key'], $struct[0]);
			return $struct; // response from rerun
		}
	}

	public function doPayment($post)
	{
		// call API for payment transaction
		/*$res_status				=	array();
		$res_status['status']	=	"success";
		$res_status['token_id']	=	md5(time());*/
		$res_status = 1;
		return $res_status;
	}
	
	public function delete_company_paystream($company_id)
	{
		try {/*
			$sql	=	"SELECT service_token
						 FROM company_plan
						 WHERE company_id = '".addslashes($company_id)."'";
			$query	=	DB::query(Database::SELECT, $sql);
			$data	=	$query->execute()->as_array();
			$ref_id	=	$data[0]['service_token'];*/
			
			$sql	 =	"SELECT s.name
						 FROM company_plan as c
						 LEFT JOIN signup_plan as s
						 ON c.signup_plan_id = s.plan_id
						 WHERE c.company_id = '".$company_id."'";
			$query	=	DB::query(Database::SELECT, $sql);
			$data	=	$query->execute()->as_array();
			if(empty($data) || $data[0]['name'] == "Free") return true;
			/*$sql	=	"SELECT signup_plan_id
						 FROM company_plan
						 WHERE company_id = '".$company_id."'";
			$query	=	DB::query(Database::SELECT, $sql);
			$data	=	$query->execute()->as_array();
			if($data[0]['signup_plan_id'] == 4) return true;
			*/
			$ref_id	=	$this->get_company_token($company_id);
			//call API for deleting company
			//die("token ".$ref_id);
			$message = new xmlrpcmsg('sample.customer_delete',
								array(new xmlrpcval($this->service_api, 'string'), //api_key
								 	  new xmlrpcval($ref_id, 'string')
								));
				
		
			$c = new xmlrpc_client("/server", $this->server_url, $this->server_port);
			$c->return_type = 'phpvals'; // let client give us back php values instead of xmlrpcvals
		//	$c->setDebug(1);	
			$result =& $c->send($message);
			
			//print '<pre>';
		//	print_r($result); die; 
			if($result->faultCode())
			{
				echo "<p>error '".htmlspecialchars($result->faultString())."'</p>\n";
				return false;
			}
			else
			{
				#print '<pre>';
				#print_r($result); die; 
				$struct = $result->value();
				//var_dump($struct);die;
				//$this->check_api_key($struct[0]['response_api_key']);
				$this->check_api_key($struct[0]['response_api_key'], $struct[0]);
			//	return $struct[0];
				if($struct[0]['response_status'] == 1) {
					//$sql	=	"DELETE FROM company_plan WHERE company_id = '".addslashes($company_id)."'";
					//$query  =  DB::query(Database::DELETE, $sql, true)->execute();
					return true;
				} else {
					//throw new Exception($struct[0]['response_description']);
					return true;
				}
			}
		} catch(Exception $e) {
			//throw new Exception($e->getMessage());
			return true;
		}
		return true;
	}
	
	public function check_api_key($response_api, $arr_response)
	{
		if(isset($arr_response['response_status']) && $arr_response['response_status'] == 0)
		{ 
			throw new Exception($arr_response['response_description']);
		}
		elseif($response_api !== $this->service_api) {
			throw new Exception($arr_response['response_description']);
		}
		else {
			return true;
		}
	}
	
	public function get_card_details($service_token = "", $upgrade = "")
	{
		if(!empty($upgrade) && $upgrade == 1) {
			$sql	=	"SELECT s.price
						 FROM company_plan as c
						 LEFT JOIN signup_plan as s
						 ON c.signup_plan_id = s.plan_id
						 WHERE c.service_token = '".$service_token."'";
		}
		elseif(!empty($service_token)) {
			$sql	 =	"SELECT s.price
						 FROM temp_company_signup as t
						 LEFT JOIN signup_plan as s
						 ON t.plan = s.plan_id
						 WHERE t.service_token = '".$service_token."'";
		} else {
			$sql	 =	"SELECT s.price
						 FROM company_plan as c
						 LEFT JOIN signup_plan as s
						 ON c.signup_plan_id = s.plan_id
						 WHERE c.company_id = '".$_SESSION['company_id']."'";
		}
		
		$query	=	DB::query(Database::SELECT, $sql);
		$data	=	$query->execute()->as_array();

		if(empty($data) || $data[0]['price'] == 0) throw new Exception("No credit card information is required as you have selected the free trial.");
		if(!empty($service_token)) {
			$ref_id	 =	$service_token;
		} else {
			$ref_id	 =	$this->get_company_token($_SESSION['company_id']);
		}
		$message =  new xmlrpcmsg('sample.get_payment_info',
								array(new xmlrpcval($this->service_api, 'string'), //api_key
								 	  new xmlrpcval($ref_id, 'string')
								));
		
		
		$c = new xmlrpc_client("/server", $this->server_url, $this->server_port);
		$c->return_type = 'phpvals'; // let client give us back php values instead of xmlrpcvals
		//$c->setDebug(1);	
		$result =& $c->send($message);
		if($result->faultCode())
		{
			echo "<p>error '".htmlspecialchars($result->faultString())."'</p>\n";
			return false;
		}
		else
		{
			$struct = $result->value();
			//echo "<pre>";print_r($struct);die;
			$this->check_api_key($struct[0]['response_api_key'], $struct[0]);
		//	return $struct[0];
			if(empty($struct[0]['customer_details'])) return false;
			//echo "<pre>";print_r($struct[0]['customer_details'][0]);echo "</pre>";die;
			return $struct[0]['customer_details'][0];
		}
	}
	
	private function get_company_token($company_id)
	{
		$sql	=	"SELECT service_token
					 FROM company_plan
					 WHERE company_id = '".addslashes($company_id)."'";
		$query	=	DB::query(Database::SELECT, $sql);
		$data	=	$query->execute()->as_array();
		$ref_id	=	$data[0]['service_token'];
		return $ref_id;
	}
	
	public function update_payment_info($input)
	{
		if(empty($input['card_name'])) throw new Kohana_Exception("Please enter valid card name.");
		$ref_id	 =	$this->get_company_token($_SESSION['company_id']);
		//echo "<pre>";print_r($input);die;
		$message =  new xmlrpcmsg('sample.update_payment_info',
								array(new xmlrpcval($this->service_api, 'string'), //api_key
								 	  new xmlrpcval($ref_id, 'string'),
								 	  new xmlrpcval($input['card_name'], 'string'),
								 	  new xmlrpcval($input['month'], 'string'),
								 	  new xmlrpcval($input['year'], 'string')
								));
		
		
		$c = new xmlrpc_client("/server", $this->server_url, $this->server_port);
		$c->return_type = 'phpvals'; // let client give us back php values instead of xmlrpcvals
		//$c->setDebug(1);	
		$result =& $c->send($message);
		if($result->faultCode())
		{
			echo "<p>error '".htmlspecialchars($result->faultString())."'</p>\n";
			return false;
		}
		else
		{
			$struct = $result->value();
			//var_dump($struct);die;
			$this->check_api_key($struct[0]['response_api_key'], $struct[0]);
			if($struct[0]['response_status'] == 1) return $struct[0]['response_description'];
			else throw new Exception($struct[0]['response_description']);
		}
	}
	public function change_plan_api($input, $plan_change)
	{
		$admin_model				=	new Model_Admin;
		$this->free_plan			=	$admin_model->get_free_plan_id();

		if($this->current_plan()	==	$this->free_plan) {
			return 2;
		}
		$ref_id	 =	$this->get_company_token($_SESSION['company_id']);
		//echo $input['plan'];die;
		//echo "<pre>";print_r($input);die;
		$message =  new xmlrpcmsg('sample.upgrade_plan',
								array(new xmlrpcval($this->service_api, 'string'), //api_key
								 	  new xmlrpcval($ref_id, 'string'),
								 	  new xmlrpcval($input['plan'], 'int'),
  									  new xmlrpcval($plan_change, 'int')
								));
		
		
		$c = new xmlrpc_client("/server", $this->server_url, $this->server_port);
		//var_dump($c);die;
		$c->return_type = 'phpvals'; // let client give us back php values instead of xmlrpcvals
		//$c->setDebug(1);	
		$result =& $c->send($message);
//echo "<pre>";print_r($result);die;
		if($result->faultCode())
		{
			echo "<p>error '".htmlspecialchars($result->faultString())."'</p>\n";
			return false;
		}
		else
		{
			$struct = $result->value();
			
			$this->check_api_key($struct[0]['response_api_key'], $struct[0]);
			
			$plan_change_msg	=	$_POST['upgrade_current_plan'] == 1?"You have successfully upgraded your Time Tracker account to the":"You have successfully downgrade your Time Tracker account to the";
			$this->email_confirmation($_SESSION['company_id'], $input['plan'], $plan_change_msg);
			if($struct[0]['response_status'] == 1) { 
				$sql	=	"UPDATE company_plan
							 SET signup_plan_id = '".addslashes($input['plan'])."'
							 WHERE company_id = '".$_SESSION['company_id']."'";
				$query 	=  DB::query(Database::INSERT, $sql, true)->execute();
				return $struct[0]['response_description'];
			}
			else throw new Exception($struct[0]['response_description']);
		}
	}
	
	public function suspend_resume_plan($plan_info)
	{
		
		$message =  new xmlrpcmsg('sample.suspend_resume_plan',
								array(new xmlrpcval($this->service_api, 'string'), //api_key
								 	  new xmlrpcval($plan_info['ref_id'], 'string'),
								 	  new xmlrpcval($plan_info['plan'], 'int'),
								 	  new xmlrpcval($plan_info['op_val'], 'int')
								));
		
		
		$c = new xmlrpc_client("/server", $this->server_url, $this->server_port);
		//var_dump($c);die;
		$c->return_type = 'phpvals'; // let client give us back php values instead of xmlrpcvals
		//$c->setDebug(1);	
		$result =& $c->send($message);
		//echo "<pre>";print_r($result);die;

		if($result->faultCode())
		{
		//	echo "<p>error '".htmlspecialchars($result->faultString())."'</p>\n";
		//	return false;
			throw new Exception(htmlspecialchars($result->faultString()));
		}
		else
		{
			$struct = $result->value();
			$this->check_api_key($struct[0]['response_api_key'], $struct[0]);
			if($struct[0]['response_status'] == 1) { 
				return true;
			}
			else throw new Exception($struct[0]['response_description']);
		}
	}

	private function current_plan()
	{
		$sql	=	"SELECT signup_plan_id
					 FROM company_plan
					 WHERE company_id = '".$_SESSION['company_id']."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $data[0]['signup_plan_id'];
	}
	
	public function email_confirmation($company_id, $plan, $plan_change_msg)
	{
		$sql	=	"SELECT email, name
					 FROM admin_users
					 WHERE company_id = '".$company_id."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		$data_model		=	new Model_Data;
		$obj_register	=	new Model_Register;
		$plan_name		=	$obj_register->get_plan_name_by_id($plan);
		$mail_content['to']			=	$data[0]['email'];
		$mail_content['subject']	=	"Your Time Tracker Plan has changed";
		$login_link					=	SITEURL."/admin";
		$mail_content['content']	=	"Dear ".ucfirst($data[0]['name']).",<br/><br/>
													".$plan_change_msg."&nbsp;". ucfirst($plan_name)." plan.<br/><br/>
													Please click on this link to log in as account Administrator:<br/>
													<a href='".$login_link."'>".$login_link."</a><br/><br/>
													Sincerely,<br/><br/>
													The Time Tracker Team
										";
		$data_model->send_email($mail_content);	// send email.
		return true;
	}
}