<?php
defined('SYSPATH') or die('No direct script access.');

/*
 * @File : activity.php
 * @Class : Model_Activity
 * @Author: 
 * @Created: 27-08-2010
 * @Modified:  
 * @Description: Holdes the database operations.
 */
class Model_Register extends Model {
	
		public function save_company_info($post) 
		{
			//echo "<pre>";print_r($post);echo "</pre>";die;
			$delete_reactive	=	0;
			$activation_id	=	md5(time().rand());
			if(isset($_POST['free_company_id']) && $_POST['free_company_id'] != "") {
				$company_id	=	$_POST['free_company_id'];
			} elseif(!empty($_SESSION['company_id'])) {
				$company_id	=	$_SESSION['company_id'];
			} elseif(!empty($post['ref_id'])) {
				$company_id			=	$this->get_company_id_by_token($post['ref_id']);
				$delete_reactive	=	1;
			} else {
				$company_id	=	"";
			}
			$post['UserEmail']=strtolower($post['UserEmail']);
			$sql			=	"INSERT INTO temp_company_signup (
									plan, company_name, serialnumber, billing_name, address, address2, city, state, country, zipcode, 
									phone, email, password, name, last_name, created_date, activation_id, service_token, company_id, reactive, status
									)
								VALUES (
									'".addslashes($post['plan'])."',
									'".addslashes(htmlentities(trim($post['cname'])))."',
									'".addslashes(htmlentities(trim($post['serialnumber'])))."',
									'".addslashes(htmlentities(trim($post['cname'])))."',
									'".addslashes(htmlentities(trim($post['address'])))."',
									'".addslashes(htmlentities(trim($post['address2'])))."',
									'".addslashes(htmlentities(trim($post['city'])))."',
									'".addslashes(htmlentities(trim($post['state'])))."',
									'".addslashes(htmlentities(trim($post['country'])))."',
									'".addslashes(htmlentities(trim($post['zipcode'])))."',
									'".addslashes(htmlentities(trim($post['phone'])))."',
									'".addslashes(htmlentities(trim($post['UserEmail'])))."',
									AES_ENCRYPT('".addslashes($post['password'])."','".ENCRYPT_KEY."'),
									'".addslashes(htmlentities(trim($post['name'])))."',
									'".addslashes(htmlentities(trim($post['lastname'])))."',
									now(),
									'".$activation_id."',
									'".addslashes(htmlentities(trim($post['token_id'])))."',
									'".$company_id."',
									'".$delete_reactive."',
									0
								)";
			$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			return true;
		}
	
		
		public function active_user($arr_ref_id)
		{
			if(isset($arr_ref_id['response_new_referance_id'])) {
				$ref_id		=	$arr_ref_id['response_new_referance_id'];
				$new_ref_id	=	$arr_ref_id['response_new_referance_id'];
			} else {
				$ref_id		=	$arr_ref_id['response_referance_id'];
				$new_ref_id	=	$arr_ref_id['response_referance_id'];
			}
			$sql			=	"SELECT id, plan, company_name, serialnumber, billing_name, address,
								 address2, city, state, country, zipcode, phone, email, AES_DECRYPT(password, '".ENCRYPT_KEY."') as password,
								 name, last_name, created_date, activation_id, service_token, company_id, reactive, status
							 	 FROM temp_company_signup
						 	 	 WHERE service_token = '".addslashes($ref_id)."'";
			$result			=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			if(empty($result[0]['company_id'])) {
				$data_model		=	new Model_Data;
				$admin_model	=	new Model_Admin;
				$activation_id	=	$result[0]['activation_id'];
				$result[0]['UserEmail']		=	$result[0]['email'];
				$mail_content['to']			=	$result[0]['email'];
				$mail_content['cc']			=	"";
				$mail_content['subject']	=	"AccountEdge Cloud Registration";
				$activation_link			=	SITEURL."/admin/activation/".$activation_id;
				/*$mail_content['content']	=	"Dear ".ucfirst($result[0]['name']).",<br/><br/>
															You have been signed up as an Administrator for AccountEdge Cloud.<br/><br/>
															Please complete your AccountEdge Cloud registration by clicking the following link:<br/>
															<a href='".$activation_link."'>".$activation_link."</a><br/><br/>
															Sincerely,<br/><br/>
															AccountEdge Cloud Team
												";*/
				$mail_content['content']	=	$admin_model->get_signup_email_content($result[0], $activation_link);
				$data_model->send_email($mail_content, 1);	// send email.
				
				$sql		=	"UPDATE temp_company_signup
								 SET status = 1
								 WHERE service_token = '".addslashes($ref_id)."'";
				$query  	=  DB::query(Database::UPDATE, $sql, true)->execute();
				return 1;
			} else {
				$company_info	=	$result[0];
				$plan_name		=	$this->get_plan_name_by_id($company_info['plan']);
				$data_model		=	new Model_Data;
				$admin_model	=	new Model_Admin;
				try {
				/*$activation_id	=	$company_info['activation_id'];
				$company_info['UserEmail']		=	$company_info['email'];
				$mail_content['to']			=	$company_info['email'];
				$mail_content['cc']			=	"pravhr@gmail.com";
				$mail_content['subject']	=	"AccountEdge Cloud Registration";
				$login_link					=	SITEURL."/admin";
				
				$mail_content['content']	=	$admin_model->get_signup_email_content($company_info, $login_link);
				$data_model->send_email($mail_content, 1);	// send email.*/
				//$xmlrpc->email_confirmation($company_info['company_id'], $company_info['plan']);
				
				$sql		=	"UPDATE company
								 SET name ='".addslashes(htmlentities(trim($company_info['company_name'])))."',
									 serialnumber='".addslashes(htmlentities(trim($company_info['serialnumber'])))."',
									 billing_name='".addslashes(htmlentities(trim($company_info['billing_name'])))."',
									 address='".addslashes(htmlentities(trim($company_info['address'])))."',
									 city='".addslashes(htmlentities(trim($company_info['city'])))."',
									 state='".addslashes(htmlentities(trim($company_info['state'])))."',
									 country='".addslashes(htmlentities(trim($company_info['country'])))."',
									 zipcode='".addslashes(htmlentities(trim($company_info['zipcode'])))."',
									 phone='".addslashes(htmlentities(trim($company_info['phone'])))."',
									 status_flag = 1
								 WHERE id = '".$company_info['company_id']."'
								 ";
											
				$query  	=  $this->_db->query(Database::INSERT, $sql, true);
				
				//insert company user details
				/*$sql	=	"UPDATE admin_users
							 SET email = '".addslashes(htmlentities(trim($company_info['email'])))."',
								 password = AES_ENCRYPT('".addslashes($company_info['password'])."','".ENCRYPT_KEY."'),
								 name = '".addslashes(htmlentities(trim($company_info['name'])))."',
								 lastname = '".addslashes(htmlentities(trim($company_info['last_name'])))."'
							 WHERE company_id = '".$company_info['company_id']."'";*/
				$sql	=	"UPDATE admin_users
							 SET 
								 name = '".addslashes(htmlentities(trim($company_info['name'])))."',
								 lastname = '".addslashes(htmlentities(trim($company_info['last_name'])))."'
							 WHERE company_id = '".$company_info['company_id']."'";
				$query  	=  $this->_db->query(Database::INSERT, $sql, true);
				//$company_admin_id	=  $query[0];
				
				$sql	=	"UPDATE company_plan
							 SET signup_plan_id ='".addslashes($company_info['plan'])."',
							 	service_token = '".addslashes($new_ref_id)."'
							 WHERE company_id = '".$company_info['company_id']."'";
				
				$query  =  $this->_db->query(Database::INSERT, $sql, true);
				
				$sql	=	"DELETE FROM temp_company_signup WHERE company_id = '".$result[0]['company_id']."'";
				$query  =  $this->_db->query(Database::DELETE, $sql, true); // delete all the users in this company
				
				$plan_change_msg	=	"You have successfully upgraded your AccountEdge Cloud account to the";
				$this->send_free_upgrade_email($company_info['company_id'], $company_info['plan'], $plan_change_msg);
				} catch(Exception $e) {
					die($e->getMessage());
				}
				return 2;
			}
			
			
		}
		
		public function get_plan_name_by_id($plan_id)
		{
			$sql	=	"SELECT name
					  	 FROM signup_plan
					  	 WHERE plan_id = '".$plan_id."'";
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			return $result[0]['name'];
		}
		
		public function get_planId()
		{
		    $sql	=	"SELECT plan_id
					  	 FROM signup_plan
					  	 WHERE price = '0'";
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			return $result[0]['plan_id'];
			
		}
		
		public function get_company_by_token($token)
		{
			$sql	=	"SELECT c.plan, c.company_name, c.address, c.billing_name, c.city, c.state, c.phone,
								c.email, c.zipcode, c.name as user_name, c.last_name, c.activation_id, cl.country, sp.name, sp.price
						 FROM temp_company_signup as c
						 LEFT JOIN signup_plan as sp
						 ON c.plan = sp.plan_id
						 LEFT JOIN country_list as cl
						 ON c.country = cl.id
						 WHERE c.service_token	=	'".addslashes($token)."'
						 ";
			$query	=	DB::query(Database::SELECT, $sql);
			$result	=	$query->execute()->as_array();
			return $result;
		}
		
		public function get_company_by_active_id($id)
		{
			$sql	=	"SELECT c.plan, c.company_name, c.address, c.billing_name, c.city, c.state, c.phone,
								c.email, c.activation_id, sp.name, sp.price
						 FROM temp_company_signup as c
						 LEFT JOIN signup_plan as sp
						 ON c.plan = sp.plan_id
						 WHERE c.activation_id	=	'".addslashes($id)."'
						 ";
			$query	=	DB::query(Database::SELECT, $sql);
			$result	=	$query->execute()->as_array();
			return $result;
		}
		
		public function get_company_by_upgrade_token($id)
		{
			$sql	=	"SELECT c.name as company_name, c.billing_name, c.address, c.city, c.state, c.phone, c.country as country_id,
							 c.zipcode, c.name as user_name, a.email, s.name as name, s.price
						FROM company as c 
						LEFT JOIN admin_users as a
						ON c.id = a.company_id
						LEFT JOIN company_plan as cp
						ON c.id = cp.company_id
						LEFT JOIN signup_plan as s
						ON cp.signup_plan_id = s.plan_id
						WHERE cp.service_token = '".addslashes($id)."'";
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			$result[0]['country']	=	$this->get_country_name($result[0]['country_id']);
			return $result;
		}
		
		public function get_company_id_by_token($token)
		{
			$sql	=	"SELECT company_id
						 FROM company_plan
						 WHERE service_token = '".addslashes($token)."'";
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			return $result[0]['company_id'];
		}
		
		private function get_country_name($cid)
		{
			$sql	=	"SELECT country
						 FROM country_list
						 WHERE id = '".$cid."'";
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			return $result[0]['country'];
		}
		
		public function send_free_upgrade_email($company_id, $plan, $plan_change_msg)
		{
			$sql	=	"SELECT email, name
						 FROM admin_users
						 WHERE company_id = '".$company_id."'";
			$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			$data_model		=	new Model_Data;
			$obj_register	=	new Model_Register;
			$plan_name		=	$obj_register->get_plan_name_by_id($plan);
			$mail_content['to']			=	$data[0]['email'];
			$mail_content['subject']	=	"Your AccountEdge Cloud Plan has changed";
			$login_link					=	SITEURL."/admin";
			$mail_content['content']	=	"Dear ".ucfirst($data[0]['name']).",<br/><br/>
														".$plan_change_msg."&nbsp;". ucfirst($plan_name)." plan.<br/><br/>
														Please click on this link to log in as account Administrator:<br/>
														<a href='".$login_link."'>".$login_link."</a><br/><br/>
														Sincerely,<br/><br/>
														The AccountEdge Cloud Team
											";
			$data_model->send_email($mail_content);	// send email.
			return true;
		}
}