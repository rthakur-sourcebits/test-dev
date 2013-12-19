<?php
defined('SYSPATH') or die('No direct script access.');


class Model_Customertoken extends Model
{
	public function update($customer_id, $data,$company_id) {
		//$query = DB::update("customer_token")->set($data)->where('customer_id', '=', $customer_id)->execute();
		$query	=	"UPDATE customer_token
					 SET payment_token = AES_ENCRYPT('".$data['payment_token']."', '".ENCRYPT_KEY."'),
					 	 last_updated_date = now()
					 WHERE customer_id = '".$customer_id."' AND company_id = '".$company_id."'
					 ";
		$this->_db->query(Database::INSERT, $query, False);
		return true;
	}
	
// 	Function to save tokens
	public function save($table, $columns, $values, $customer_id) {
		if(!$this->token_exists($customer_id)) {
			$query = DB::insert($table, $columns)->values($values)->execute();
			return $query;
		}
	}
	
	private function token_exists($customer_id) {
		$sql		=	"SELECT id FROM customer_token WHERE customer_id = '".$customer_id."' AND company_id = '".$_SESSION['company_id']."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return false;
		else return true;
	}
	
	public function get_customer_tokens($customer_id,$company_id) {
		$sql		=	"SELECT id, AES_DECRYPT(payment_token, '".ENCRYPT_KEY."') as payment_token FROM customer_token WHERE customer_id = '".$customer_id."' AND company_id = '".$company_id."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to fetch customer token
	public function get_customer_payment_info($customer_id) {
		$sql	=	"SELECT ct.customer_token, ct.payment_token, c.expiration_date, c.name_on_card, 
					 c.payment_method, c.last_digits_on_card, c.payment_type 
					 FROM customer_token ct, customers c
					 WHERE c.id = ct.customer_id
					 AND ct.customer_id = '".addslashes($customer_id)."'
					 AND payment_token != ''
					 AND ct.company_id = '".addslashes($_SESSION['company_id'])."'
					";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
}