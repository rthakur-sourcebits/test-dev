<?php
class Model_Transaction extends Model 
{
	
	public function create($table, $data) { 
		$query = DB::insert($table, array_keys($data))->values($data)->execute();
		return $query[0];
	}
	
	public function insert_tranasction_log($table, $data) {
		/*$query = DB::insert($table, array_keys($data))->values($data)->execute();
		return $query[0];*/
		return $this->create($table, $data);
	}
	
	public function update_transaction_log($table, $arr_data, $where_id) {
		$query = DB::update($table)->set($arr_data)->where('id', '=', $where_id)->execute();
		return true;
	}
	
	public function update($table, $arr_data, $where) {
		
		var_dump($arr_data); 
		var_dump($where); die;
		$query = DB::update($table)->set($arr_data)->where('sale_id', '=', $where['id'])->where('customer_id', '=', $where['cid'])->execute();
		return true;
	}
	
	public function delete_sales_transaction($table, $sale_id){
		$query = DB::delete($table)->where('sale_id', '=', $sale_id)->execute();
		return true;
	}
	
	// Function to get transaction details
	public function get_card_transaction($sale_id) {
		$sql	=	"SELECT t.*, c.account, 
							c.record_id, c.company_or_lastname, c.expiration_date, c.last_digits_on_card, c.name_on_card, c.firstname, c.payment_method, c.zip,
							co.deposit_account
					 FROM transaction t LEFT JOIN customers c ON(c.id = t.customer_id)
					 LEFT JOIN company co ON (c.company_id = co.id) 
					 WHERE t.sale_id = '".$sale_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	
	// Function to get transaction details
	public function get_sales_transaction_details($sale_id) {
		$sql	=	"SELECT t.id, t.transaction_amount, t.transaction_date, t.payment_method, t.gateway_transaction_id,
					 c.company_or_lastname, c.name_on_card, c.last_digits_on_card, c.expiration_date, t.customer_id, t.gateway_authorization_code, co.deposit_account 
					 FROM transaction t LEFT JOIN customers c ON(t.customer_id = c.id)
					 LEFT JOIN company co ON (c.company_id = co.id) 
					 WHERE t.sale_id = '".addslashes($sale_id)."'
					";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get other transaction details
	public function get_other_transaction($sale_id) {
		$sql	=	"SELECT t.*, 
							c.company_or_lastname, c.record_id, c.firstname, t.payment_method, 
							c.account, co.deposit_account
					 FROM transaction_other t 
					 LEFT JOIN customers c ON(c.id = t.customer_id)
					 LEFT JOIN company co ON (c.company_id = co.id) 
					 WHERE t.sale_id = '".$sale_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Get transaction paid by cash
	public function get_cash_transaction($sale_id) {
		$sql	=	"SELECT co.id, co.customer_id, co.total_amount, co.created_date,
							c.record_id, c.company_or_lastname, c.firstname, c.zip
					 FROM cash_order co LEFT JOIN customers c ON(c.id = co.customer_id)
					 WHERE co.sale_id = '".$sale_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	public function check_payment_exists($sale_id){
		$sql	=	"(SELECT id
					 FROM transaction_other 
					 WHERE sale_id = '".$sale_id."') 
					 UNION (SELECT id
					 FROM transaction  
					 WHERE sale_id = '".$sale_id."')";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)){
			return null;
		} else {
			return $result;
		}
	}
	// Get Total Payment Processed for other transaction.
	public function get_transaction_other_details_for_sale($sale_id,$customer_id){
		$sql 	= "Select SUM(transaction_amount) as total_amount from transaction_other where sale_id = '".addslashes($sale_id)."' AND customer_id= '".addslashes($customer_id)."' ";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)){
			return 0;
		}else{
			return $result[0]['total_amount'];
		}
	}
	
	// Get Total Payment Processed
	public function get_transaction_details_for_sale($sale_id,$customer_id){
		$sql 	= "Select SUM(transaction_amount) as total_amount from transaction where sale_id = '".addslashes($sale_id)."' AND customer_id= '".addslashes($customer_id)."' ";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)){
			return 0;
		}else{
			return $result[0]['total_amount'];
		}
	}
}
?>