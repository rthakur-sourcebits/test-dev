<?php
defined('SYSPATH') or die('No direct script access.');

/*
 * @File : sales.php
 * @Class : Model_Sales
 * @Description: Holds sales related database operations.
 * @ Modified: 
 * 			08.12.2013 	-	get sales details.
 * 			10.12.2013 	-	added search_sales_and_purchase_data function for search any 	
 * 
 */
class Model_Sales extends Model
{
	public function get_sale_by_id($sale_id, $company_id){
		$results = DB::select('*')->from('sales')->where('id', '=', $sale_id)->where('company_id', '=', $company_id)->execute()->as_array();
		if(!isset($results) || empty($results) ){
			return FALSE;
		} else {
			return $results;
		}
		
	}
	// Function to get sales information
	public function get_customer_sales($limit=0, $field="created_date", $order="1") {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT s.*, c.firstname, c.company_or_lastname as customer_name, st.type
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".$_SESSION['company_id']."' 
						 AND admin_user = '1'
						 ORDER BY $field $order";
		} else {
			$sql	=	"SELECT s.*, c.firstname, c.company_or_lastname as customer_name, st.type
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id
						 AND s.type = st.id
						 AND s.company_id = '".$_SESSION['company_id']."'
						 AND s.employee_id = '".$_SESSION['employee_id']."'
						 ORDER BY $field $order";
		}
		$start		=	isset($_POST['page'])?$_POST['page']:0;
		$per_page	=	!empty($_POST['view_per_page'])?$_POST['view_per_page']:10;
		if(!empty($_POST['view_per_page']) && $limit == 1) {
			$start	=	$start*$per_page;
			$sql   .=	" LIMIT $start, ".$per_page;
		} else if($limit == 1) {
			$sql   .=	" LIMIT $start, 10";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}

	// 	Function to save sales
	public function save($table, $columns, $values) {
		$query = DB::insert($table, $columns)->values($values)->execute();
		return $query;
	}
	
	// Function to get all the sales person comments
	public function get_comments() {
		$sql	=	"SELECT id, information_name
					 FROM sales_and_purchase
					 WHERE company_id = '".addslashes($_SESSION['company_id'])."'
					 AND information_type = '0' ORDER BY information_name ASC";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get all the referrals
	public function get_referrals() {
		$sql	=	"SELECT id, information_name 	
					 FROM sales_and_purchase
					 WHERE company_id = '".addslashes($_SESSION['company_id'])."'
					 AND information_type = '2' ORDER BY information_name ASC";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get all the payment methods
	public function get_payment_methods() {
		$sql	=	"SELECT id, information_name, payment_type
					 FROM sales_and_purchase
					 WHERE company_id = '".addslashes($_SESSION['company_id'])."'
					 AND information_type = '3' ORDER BY information_name ASC";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// added this function to search all key-values pair for sale-purchase data. 
	public function search_sales_and_purchase_data($key_value){
		if(isset($key_value) && !empty($key_value)){
			$search_query 	= '';
			$flag 			= FALSE;
			foreach ($key_value as $key => $value) {
				if($flag){
					$search_query .= ' AND ';
				}
				$search_query .= $key .' = "'.$value.'"';
				$flag = TRUE;
			}	
			$sql = 'SELECT payment_type 
					FROM sales_and_purchase
					WHERE '.$search_query;

			$sales_purchase_data = DB::query(Database::SELECT, $sql)->execute()->as_array();
		}
		
		if(isset($sales_purchase_data)&&!empty($sales_purchase_data)){
			return $sales_purchase_data;
		} else {
			return FALSE;
		}
	}

	// Function to get all the shipping methods
	public function get_shipping_methods() {
		$sql	=	"SELECT id, information_name
					 FROM sales_and_purchase
					 WHERE company_id = '".addslashes($_SESSION['company_id'])."'
					 AND information_type = '1' ORDER BY information_name ASC";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get latest sale number
	public function get_sale_number() {
		$sql	=	"SELECT count(id) as slip_number
					 FROM sales
					 WHERE company_id = '".$_SESSION['company_id']."'
					 ";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)){
			return 1;
		} else{
			return $result[0]['slip_number']+1;	
		}
	}
	
	// Function to get customer tax
	public function customer_related_tax($customer_id) {
		$sql	=	"SELECT t.id,t.tax_code, t.percentage, t.description
					 FROM customers c, taxes t
					 WHERE c.tax_code = t.tax_code
					 AND c.id = '".$customer_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get sale details
	// 
	public function get_sale_details($sale_id) {
		$sql	=	"SELECT s.*, t.tax_code, t.percentage, t.description,c.use_customer_tax_code, c.firstname, c.email as customer_email,c.company_or_lastname, c.tax_record_id as tax_record_id, t1.tax_code as freight_tax_code, t1.percentage as freight_tax_percentage, c.last_digits_on_card, c.expiration_date, c.name_on_card 
					FROM sales s 
					LEFT JOIN customers c ON (s.customer_id = c.id)
					LEFT JOIN taxes t ON (c.tax_record_id = t.tax_record_id AND s.company_id = t.company_id) 
					LEFT JOIN taxes t1 ON (s.freight_tax_record_id = t1.tax_record_id AND s.company_id = t1.company_id) 
				  	WHERE s.id = '".addslashes($sale_id)."'
				 	AND s.employee_id = '".addslashes($_SESSION['employee_id'])."'";
		
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	public function update_by_id_edit($saleId,$subtotal,$total,$balance){
		$sql				=	"UPDATE sales SET total_payment = '".$total."' , subtotal = '".$subtotal."', balance = '".$balance."' WHERE id = '".$saleId."'";
		$total_rows			=	DB::query(Database::UPDATE, $sql)->execute();
		return $total_rows;
	}
	
	// calculate the sale information for sales-updates.
	private function get_sales_common_by_id($saleId, $country=USA, $company_id){
		// return variable.
		$sales_common 			= 	null;
		
		// Check and get freight TAXes and sales information. 
		$freight_tax_verify		=	DB::SELECT('freight_tax')->FROM('sales')->WHERE('id', '=', $saleId)->execute()->as_array();
		$freight_tax_verify		=	$freight_tax_verify[0];
		
		if($freight_tax_verify['freight_tax'] ==1){
			$sql					=	"SELECT s.freight, t.percentage, s.paid_today, s.payment, s.is_tax_inclusive 
										FROM sales as s 
										JOIN taxes as t ON 	s.freight_tax_record_id = t.tax_record_id
															AND t.company_id = '".$company_id."'
										WHERE s.id = '".$saleId."'";
			$sales_info		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			$sales_info 	= 	$sales_info[0];
			$sales_common['freight_tax_percent']=	isset($sales_info['percentage'])?$sales_info['percentage']:0;
			$sales_common['is_tax_inclusive']	=	isset($sales_info['is_tax_inclusive'])?$sales_info['is_tax_inclusive']:0;
			$sales_common['freight_amount']		=	isset($sales_info['freight']) ? $sales_info['freight'] : 0.00;
			$sales_common['freight_tax_amount'] =	$sales_common['freight_amount']*$sales_common['freight_tax_percent']/100;
			
		}
		else{
			$sql					=	"SELECT freight,paid_today,payment 
										FROM sales 
										WHERE id='".$saleId."'";
			$sales_info				=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			$sales_info 			= 	$sales_info[0];
			
			$sales_common['freight_tax_percent']=	isset($sales_info['percentage'])?$sales_info['percentage']:0;
			$sales_common['freight_amount']		=	isset($sales_info['freight']) ? $sales_info['freight'] : 0.00;
			$sales_common['freight_tax_amount'] = 	0;
		}
		
		// setting sales info
		$sales_common['paid_today']		=	$sales_info['paid_today'];
		return $sales_common;
	}
	
    // Function to delete item row individually
    // modified : based on new TAXes functionality( which is item based now)
    // 			  	adding country parameter with default as US i.e. 0    	
	//				adding company if as parameter			
	public function delete_item_by_id_new($delete_item_sale_id,$saleId, $country=USA, $company_id=0) {
		
		// deleting selected item row.
		DB::delete('sale_item')->where('id','=',$delete_item_sale_id)->execute();
		
		$updated_sale = null;
		$post_sales_data	= 	$this->get_sales_common_by_id($saleId, $country, $company_id);
		$sql					=	"SELECT si.*, t.percentage
									FROM sale_item as si 
									JOIN taxes as t ON si.tax_record_id	= t.tax_record_id
														AND t.company_id= '".$company_id."'
									WHERE si.sale_id='".$saleId."'";
		
		$sales_item_info		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		foreach ($sales_item_info as $items) {
			$post_sales_data['total'][]				= 	$items['total'];
			$post_sales_data['tax_percentage'][]	=	$items['percentage'];
			$post_sales_data['apply_tax'][]			=	$items['apply_tax'];
		}			 
		
		$sales_data	=	$this->common_sales_post_calculation($saleId, $post_sales_data); 
		return $sales_data;
	}	
	
	public function delete_service_by_id_new($delete_service_sale_id,$saleId, $country=USA, $company_id=0) {
		// deleting selected item row.
		DB::delete('sale_service')->where('id','=',$delete_service_sale_id)->execute();
 
		$updated_sale = null;
		
		$post_sales_data	= 	$this->get_sales_common_by_id($saleId, $country, $company_id);
		$sql					=	"SELECT si.*, t.percentage
									FROM sale_service as si 
									JOIN taxes as t ON si.tax_record_id	= t.tax_record_id
														AND t.company_id= '".$company_id."'
									WHERE si.sale_id='".$saleId."'";
		$sales_item_info		=	DB::query(Database::SELECT, $sql)->execute()->as_array();	
											
		foreach ($sales_item_info as $items) {
			$post_sales_data['total'][]				= 	$items['amount'];
			$post_sales_data['tax_percentage'][]	=	$items['percentage'];
			$post_sales_data['apply_tax'][]			=	$items['tax'];
		}			 
		
		$sales_data	=	$this->common_sales_post_calculation($saleId, $post_sales_data); 
		return $sales_data;
	}
	
	public function delete_tb_by_id_new($delete_service_sale_id,$saleId, $country=USA, $company_id=0) {
		// deleting selected item row.
		DB::delete('sale_time_billing')->where('id','=',$delete_service_sale_id)->execute();
 
		$updated_sale = null;
		
		$post_sales_data	= 	$this->get_sales_common_by_id($saleId, $country, $company_id);
		$sql					=	"SELECT si.*, t.percentage
									FROM sale_time_billing as si 
									JOIN taxes as t ON si.tax_record_id	= t.tax_record_id
														AND t.company_id= '".$company_id."'
									WHERE si.sale_id='".$saleId."'";
		
		$sales_item_info		=	DB::query(Database::SELECT, $sql)->execute()->as_array();	
		foreach ($sales_item_info as $items) {
			$post_sales_data['total'][]				= 	$items['amount'];
			$post_sales_data['tax_percentage'][]	=	$items['percentage'];
			$post_sales_data['apply_tax'][]			=	$items['tax_check'];
		}			 
		
		$sales_data	=	$this->common_sales_post_calculation($saleId, $post_sales_data); 
		//var_dump($sales_data);die;
		return $sales_data;
	}
	
	
	// POST calculation of sales item.
	private function common_sales_post_calculation($saleId, $post_sales_data){
		$grand_total= 0;
		$subtotal 	= 0;
		$tax_amount = 0;
		
		// for-each sales item.
		for($i=0;$i<count($post_sales_data['total']);$i++){
			$subtotal	=	$subtotal + $post_sales_data['total'][$i];
			if($post_sales_data['apply_tax'][$i] == 0){
				$grand_total		=	$grand_total + $post_sales_data['total'][$i];
			}
			else{
				$item_tax 			= 	($post_sales_data['tax_percentage'][$i]/100)*($post_sales_data['total'][$i]);
				$tax_amount 		+=	$item_tax;  
				$grand_total		=	$grand_total + $post_sales_data['total'][$i] + $item_tax;
			}
			
		}
		//echo "<pre>";print_r($tax_amount);die;
		
		// adding freight tax amount
		$tax_amount		+=	($post_sales_data['freight_tax_amount']);
		
		// adding calculated freight amount to the grand total.
		 // for non-US
		$grand_total	=	$grand_total + ($post_sales_data['freight_amount'] + $post_sales_data['freight_tax_amount']);
		//
		/*if($post_sales_data['is_tax_inclusive'] == '1'){ // if the TAX inclusive is check then freight amount will be freight TAX added
			 
		}*/
		$balance		=	$grand_total - ($post_sales_data['paid_today']);
		
		
		// update payment info
		$sql					=	"UPDATE sales SET total_payment = '".$grand_total."',tax_total_amount = '".$tax_amount."' , subtotal = '".$subtotal."', balance = '".$balance."' WHERE id = '".$saleId."'";
		$update_total			=	DB::query(Database::UPDATE, $sql)->execute();
		
		$details = array(
			'total_payment'	=> $grand_total,
			"subtotal"		=> $subtotal,
			'tax_amount'	=> $tax_amount,
			'balance'		=> $balance
		);
		return $details;
	}

	// Function to delete item row individually
	// added temporarily
	public function delete_item_by_id($table,$field,$sale_id,$saleId) {
		$total_rows				=	DB::delete($table)->where('id','=',$sale_id)->execute();
		$tax_verify				=	DB::SELECT('tax')->FROM('sales')->WHERE('id', '=', $saleId)->execute()->as_array();	
		if($tax_verify[0]['tax'] !=0){
		$sql					=	"select sales.freight,taxes.percentage,sales.paid_today,sales.payment from sales JOIN taxes on sales.tax=taxes.id where sales.id='".$saleId."'";
		$tax_percentage			=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		$tax_percent			=	$tax_percentage[0]['percentage'];
		}
		else{
		$sql					=	"select freight,paid_today,payment from sales where id='".$saleId."'";
		$tax_percentage			=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		}
		$paid_today				=	$tax_percentage[0]['paid_today'];
		$payment				=	$tax_percentage[0]['payment'];
		$freight				=	isset($tax_percentage[0]['freight']) ? $tax_percentage[0]['freight'] : 0.00;
		$tax_percent			=	isset($tax_percent) ? $tax_percent : 0;
		$total=0;
		$subtotal=0;
		$remaining_rows			=	DB::SELECT('*')->FROM($table)->WHERE('sale_id', '=', $saleId)->execute()->as_array();	
		if($table == 'sale_time_billing'){
		$tax_field	=	'tax_check';
		}
		else{
		$tax_field	=	'tax';
		}
		if(isset($tax_percent)){
			for($i=0;$i<count($remaining_rows);$i++){
				$subtotal	=	$subtotal+$remaining_rows[$i][$field];
				if($remaining_rows[$i][$tax_field] == 0){
					$total		=	$total+$remaining_rows[$i][$field];
				}
				else{
					$total		=	$total+$remaining_rows[$i][$field]+(($tax_percent/100)*($remaining_rows[$i][$field]));
				}
			}
			$total				=	$total+(($tax_percent/100)*$freight);
		}
		//$subtotal			=	$subtotal+$freight;
		$total				=	$total+$freight;
		$balance			=	$total-$paid_today;
		
		$sql					=	"UPDATE sales SET total_payment = '".$total."' , subtotal = '".$subtotal."', balance = '".$balance."' WHERE id = '".$saleId."'";
		$update_total			=	DB::query(Database::UPDATE, $sql)->execute();
		$details = array(
			'total'		=> $total,
			"subtotal"	=> $subtotal,
			'tax_amount'=> ($total-$subtotal-$freight),
			'balance'	=> $balance
		);
		return $details;
	}
	
     
	// Function to get sale details
	public function get_sale_full_details($sale_id) {
		$sql	=	"SELECT s.*, t.tax_record_id,t.tax_code,t.percentage, c.id customer_id, c.firstname,c.company_or_lastname,
					 c.account, c.record_id, c.name_on_card, c.last_digits_on_card, c.expiration_date,
					 c.payment_method, c.zip
					 FROM sales s LEFT JOIN taxes t ON (s.freight_tax_record_id = t.tax_record_id AND t.company_id = '".addslashes($_SESSION['company_id'])."') 
					 LEFT JOIN customers c ON (s.customer_id = c.id)
					 WHERE s.id = '".addslashes($sale_id)."'
					 AND s.employee_id = '".addslashes($_SESSION['employee_id'])."'
					";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get sale items
	// Description: 
	//				we return all sales-item related data + items id's and job info. 
	public function get_sale_item($sale_id, $company_id) {
		$sql	=	"SELECT si.*, i.id as item_id, i.item_number, i.item_name, j.job_name, t.tax_code, t.percentage
					 FROM sale_item si
					 LEFT JOIN items i ON (si.item_number = i.id AND i.company_id='".addslashes($company_id)."')
					 LEFT JOIN jobs j ON (si.job_id  = j.id AND j.company_id='".addslashes($company_id)."')
					 LEFT JOIN taxes t ON (si.tax_record_id = t.tax_record_id AND t.company_id='".addslashes($company_id)."')					 
					 WHERE si.sale_id = '".addslashes($sale_id)."'";
 
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to update sales
	public function update($table, $arr_data, $where_id) {
		$query = DB::update($table)->set($arr_data)->where('id', '=', $where_id)->execute();
		return true;
	}
	
	// Function delete customer/jobs
	public function delete($table, $column, $id) {
		$total_rows = DB::delete($table)->where($column,'=',$id)->execute();
		return true;
	}
	
	// Function to check user before deleting sale
	public function check_current_user($id) {
		$sql	=	"SELECT id
					 FROM sales
					 WHERE id = '".$id."'
					 AND employee_id = '".addslashes($_SESSION['employee_id'])."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return false;
		else return true;
	}
	
	// Function to get all the service list
	public function get_sale_service($sale_id) {
		$sql	=	"SELECT ss.*, a.id as account_id, a.account_number, a.account_name, j.job_name,t.tax_code, t.percentage
					 FROM sale_service ss
					 LEFT JOIN accounts a ON (ss.account = a.id)
					 LEFT JOIN jobs j ON (ss.job_id  = j.id)
					 LEFT JOIN taxes t ON (ss.tax_record_id = t.tax_record_id AND t.company_id='".addslashes($_SESSION['company_id'])."')		
					 WHERE ss.sale_id = '".addslashes($sale_id)."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get time billing sale details
	public function get_sale_time_billing($sale_id) {
		$sql	=	"SELECT st.*, a.activity_id, j.job_name,t.tax_code, t.percentage
					 FROM sale_time_billing st
					 LEFT JOIN activities a ON (st.activity = a.id)
					 LEFT JOIN jobs j ON (st.job_id  = j.id)
					 LEFT JOIN taxes t ON (st.tax_record_id = t.tax_record_id AND t.company_id='".addslashes($_SESSION['company_id'])."')	
					 WHERE st.sale_id = '".addslashes($sale_id)."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get to be synced list
	public function get_to_be_synced_list() {
		$sql	=	"SELECT st.type, s.id, s.created_date,s.updated_date, s.sale_number, s.total_payment, c.company_or_lastname, c.firstname
					 FROM sales s
					 LEFT JOIN sale_type st ON ( s.type = st.id )
					 LEFT JOIN customers c ON ( s.customer_id = c.id )
					 WHERE s.employee_id = '".addslashes($_SESSION['employee_id'])."'
					 AND s.sync_status = '0'
					 ORDER BY s.updated_date desc
					";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	
	// Function to fetch payment method in sales payment
	public function get_payment_method_details($customer_id,$sale_id) {
		$sql	=	"SELECT payment_method, payment_type 
					 FROM sales
					 WHERE customer_id = '".addslashes($customer_id)."' AND id='".addslashes($sale_id)."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get sale info by key
	public function get_sale_by_key($sale_key) {
		$sql	=	"SELECT *
					 FROM sales
					 WHERE sale_key = '".addslashes($sale_key)."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get sales search result
	public function get_sales_search_result($search_keyword, $limit=0, $field="created_date", $order = "1") {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT s.*, c.firstname, c.company_or_lastname as customer_name, st.type
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND admin_user = '1'
						 AND (
						 	 s.sale_number	LIKE '%".addslashes($search_keyword)."%' OR 
						 	 c.company_or_lastname LIKE '%".addslashes($search_keyword)."%' OR 
						 	 st.type  LIKE '%".addslashes($search_keyword)."%'
						 )
						 ORDER BY '".$field."' '".$order."'";
		} else {
			$sql	=	"SELECT s.*, c.firstname, c.company_or_lastname as customer_name, st.type
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND s.employee_id = '".addslashes($_SESSION['employee_id'])."'
						 AND (
						 	 s.sale_number	LIKE '%".addslashes($search_keyword)."%' OR 
						 	 c.company_or_lastname LIKE '%".addslashes($search_keyword)."%' OR 
						 	 st.type  LIKE '%".addslashes($search_keyword)."%'
						 )
						 ORDER BY '".$field."' '".$order."'";
		}
		//die($sql);
		$start		=	isset($_POST['page'])?$_POST['page']:0;
		$per_page	=	!empty($_POST['view_per_page'])?$_POST['view_per_page']:10;
		if(!empty($_POST['view_per_page']) && $limit == 1) {
			$start	=	$start*$per_page;
			$sql   .=	" LIMIT $start, ".$per_page;
		} else if($limit == 1) {
			$sql   .=	" LIMIT $start, 10";
		} else if($limit == 2){
		    $start   =    ($start*$per_page)-$per_page;
		    $sql   .=	" LIMIT $start, 10";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Get all unsynced sales
	public function get_all_sale_to_sync() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT s.*, t.tax_code, t.percentage, t.tax_record_id, c.id as customer_id, c.company_or_lastname,
					 c.account, c.record_id, c.name_on_card, c.last_digits_on_card, c.expiration_date,
					 c.payment_method, c.zip
					 FROM sales s LEFT JOIN taxes t ON (s.freight_tax_record_id = t.tax_record_id AND t.company_id = '".addslashes($_SESSION['company_id'])."')
					 LEFT JOIN customers c ON (s.customer_id = c.id)
					 WHERE s.employee_id = '".addslashes($_SESSION['employee_id'])."'
					 AND s.admin_user	=	'1'
					 AND s.sync_status	=	'0'
					";
		} else {
			$sql	=	"SELECT s.*, t.tax_code, t.percentage, t.tax_record_id, c.id as customer_id, c.company_or_lastname,
						 c.account, c.record_id, c.name_on_card, c.last_digits_on_card, c.expiration_date,
						 c.payment_method, c.zip
						 FROM sales s LEFT JOIN taxes t ON (s.freight_tax_record_id = t.tax_record_id AND t.company_id = '".addslashes($_SESSION['company_id'])."')
						 LEFT JOIN customers c ON (s.customer_id = c.id)
						 WHERE s.employee_id = '".addslashes($_SESSION['employee_id'])."'
						 AND s.sync_status	= '0'
						";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get previous sale
	public function get_prev_sale($sale_id) {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT MAX(s.id) as prev_sale_id
						 FROM sales s
						 WHERE s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND admin_user = '1'
						 AND s.id < '".addslashes($sale_id)."'";
		} else {
			$sql	=	"SELECT MAX(s.id) as prev_sale_id
						 FROM sales s
						 WHERE s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND s.employee_id = '".addslashes($_SESSION['employee_id'])."'
						 AND s.id < '".addslashes($sale_id)."'";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return 0;
		else return $result[0]['prev_sale_id'];
	}

	// Function to get next sale
	public function get_next_sale($sale_id) {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT MIN(s.id) as next_sale_id
						 FROM sales s
						 WHERE s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND admin_user = '1'
						 AND s.id > '".addslashes($sale_id)."'";
		} else {
			$sql	=	"SELECT MIN(s.id) as next_sale_id
						 FROM sales s
						 WHERE s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND s.employee_id = '".addslashes($_SESSION['employee_id'])."'
						 AND s.id > '".addslashes($sale_id)."'";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return 0;
		else return $result[0]['next_sale_id'];
	}
	
	public function get_customer_sales_by_order($limit=0, $field, $order) {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT s.*, c.firstname , c.company_or_lastname as customer_name, st.type
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND admin_user = '1'
						 ORDER BY $field $order";
		} else {
			$sql	=	"SELECT s.*, c.firstname, c.company_or_lastname as customer_name, st.type
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND s.employee_id = '".addslashes($_SESSION['employee_id'])."'
						 ORDER BY $field $order";
		}
		$start		=	isset($_POST['page'])?$_POST['page']:0;
		$per_page	=	!empty($_POST['view_per_page'])?$_POST['view_per_page']:10;
		if(!empty($_POST['view_per_page']) && $limit == 1) {
			$start	=	$start*$per_page;
			$sql   .=	" LIMIT $start, ".$per_page;
		} else if($limit == 1) {
			$sql   .=	" LIMIT $start, 10";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to et filtered results
	public function get_customer_sales_filter($limit, $filter) {

		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT s.*, c.firstname , c.firstname, c.company_or_lastname as customer_name, st.type
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND admin_user = '1'
						 AND s.type IN ($filter)
						 ORDER BY s.created_date";
		} else {
			$sql	=	"SELECT s.*, c.firstname , c.firstname, c.company_or_lastname as customer_name, st.type
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND s.employee_id = '".addslashes($_SESSION['employee_id'])."'
						 AND s.type IN($filter)
						 ORDER BY s.created_date";
		}
	   // die($sql);
		$start		=	0;
		$per_page	=	10;
		if($limit == 1) {
			   $sql   .=	" LIMIT $start, ".$per_page;
		}
		//die($sql);
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
	   // echo "<pre>";print_r($result);die;
		return $result;
	}
	
	// Function to get transaction details
	/*
	public function get_sales_cash_details($sale_id) {
			$sql	=	"SELECT co.total_amount, co.change, co.name, co.note, co.created_date,
								c.company_or_lastname
						 FROM cash_order co LEFT JOIN customers c ON(co.customer_id = c.id)
						 WHERE co.sale_id = '".addslashes($sale_id)."'
						";
			$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			return $result;
		}*/
	
	
	// Function to get sales by AJAX call
	public function paginated_sales($start, $end, $field="created_date", $order="DESC",$filter) {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT s.*,c.firstname, c.company_or_lastname as customer_name, st.type
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND admin_user = '1'
						 AND s.type IN ($filter)
						 ORDER BY $field $order
						 LIMIT $start, $end";
		} else {
			$sql	=	"SELECT s.*,c.firstname, c.company_or_lastname as customer_name, st.type
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND s.employee_id = '".addslashes($_SESSION['employee_id'])."'
						 AND s.type IN ($filter)
						 ORDER BY $field $order
						 LIMIT $start, $end";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Get total jobs
	public function total_sales() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT count(*) as total
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND admin_user = '1'
						";
		} else {
			$sql	=	"SELECT count(*) as total
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND s.employee_id = '".addslashes($_SESSION['employee_id'])."'
			 			";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result[0]['total'];
	}
	
	//get last updated sale detail
	public function get_last_updated_sale() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT s.type as sale_id 
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND admin_user = '1'
						 ORDER BY s.id desc
						 limit 0,1
						";
		} else {
			$sql	=	"SELECT s.type as sale_id 
						 FROM sales s, customers c, sale_type st
						 WHERE s.customer_id = c.id 
						 AND s.type = st.id
						 AND s.company_id = '".addslashes($_SESSION['company_id'])."' 
						 AND s.employee_id = '".addslashes($_SESSION['employee_id'])."'
						 ORDER BY s.id desc
						 limit 0,1
			 			";
		}
		//die($sql);
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	public function get_tax_values($id,$table){
		if($table == 'items'){
		$sql 	= "Select i.tax_when_sold_non_us, t.tax_code, t.percentage 
					FROM items i
					LEFT JOIN taxes t ON (i.tax_when_sold_non_us = t.tax_record_id AND i.company_id = t.company_id) 
					WHERE i.id = '".addslashes($id)."'
				 	";
		} else if($table == 'accounts'){
		$sql 	= "Select a.TaxCodeRecordID, t.tax_code, t.percentage 
					FROM accounts a
					LEFT JOIN taxes t ON (a.TaxCodeRecordID = t.tax_record_id AND a.company_id = t.company_id) 
					WHERE a.id = '".addslashes($id)."'
				 	";
		} else if($table == 'activities'){
			$sql 	= "Select a.TaxCodeRecordID, t.tax_code, t.percentage 
					FROM activities a
					LEFT JOIN taxes t ON (a.TaxCodeRecordID = t.tax_record_id AND a.company_id = t.company_id) 
					WHERE a.id = '".addslashes($id)."'
				 	";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result[0])) {
			return $result[0];
		}
	}
	
	/*	Function to get latest payment number.
	 	Temporary Function - Will have to remove it, 
		once Payment ID# is implemented as similar to AEM.
	*/
	public function get_set_payment_id($company_id , $sale_id) {
		$sql	=	"SELECT max(payment_id)
					 FROM sales
					 WHERE company_id = '".$company_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result[0]['max(payment_id)']) || !isset($result[0])){
			$value['id']	=	str_pad(1,8,"0",STR_PAD_LEFT);
		} else {
			$value['id']	=	str_pad((int)(($result[0]['max(payment_id)'])+1),8,"0",STR_PAD_LEFT);
		}
		$sql	=	"UPDATE sales
					 SET payment_id = '".$value['id']."'
					 where company_id = '".$company_id."' AND id = '".$sale_id."'";
		$this->_db->query(Database::UPDATE, $sql, False);
		
		$sql	=	"SELECT payment_method
					 FROM sales
					 where company_id = '".$company_id."' AND id = '".$sale_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result[0]['payment_method']) || !isset($result[0])){
			$value['method'] = '';
		} else {
			$value['method'] = $result[0]['payment_method'];
		}
		return $value;
	}
}

