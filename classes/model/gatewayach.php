<?php defined('SYSPATH') or die('No direct script access.');


class Model_Gatewayach extends Model
{
	public function get_merchant_personal_ach_details($company_id) {
		$sql	=	"SELECT AES_DECRYPT(ach_gateway_id, '".ENCRYPT_KEY."') as ach_gateway_id,
							AES_DECRYPT(ach_gateway_password, '".ENCRYPT_KEY."') as ach_gateway_password,
							AES_DECRYPT(apli_login_id, '".ENCRYPT_KEY."') as apli_login_id,
							AES_DECRYPT(transaction_key, '".ENCRYPT_KEY."') as transaction_key,
							status
					 FROM gateway_ach
					 WHERE company_id = '".$company_id."'";
		$result	=	$this->_db->query(Database::SELECT, $sql, False)->as_array();
		return $result;
	}
	
	//check the payment details of admin has been added or not
	public function get_payment_status() {
		$sql	=	"SELECT * FROM gateway_ach WHERE company_id = '".addslashes($_SESSION['company_id'])."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(count($result)==1)
			return $result;
		else
			return false;
	}
}