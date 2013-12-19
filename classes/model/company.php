<?php  defined('SYSPATH') or die('No direct script access.');
/**
 * 	@File		:	company.php Model
 * 	@Class		:	Model_Company
 * 	@Date		:	18 Nov 2013
 * 	@Description: Model company related query
 * 	
 * 	@Modified 	: 
 * 					18.11.2013 -	added function  get_customer_defaults.
 * 					14.12.2013 -	added function get_company_by_key
* 					14.12.2013 -	shifted function update_ccp_details  from admin to company model
 * 
*/
class Model_Company extends Model {
 
    public function __construct($id = NULL)
	{
		parent::__construct($id);
	}
	
	public function get_company_details($id){
		$mysql = 'SELECT c.*, t1.tax_code as tax_code, t2.tax_code as freight_tax_code
				FROM company as c 
				LEFT JOIN taxes as t1 ON (c.customer_tax_code_record_id 		= t1.tax_record_id AND c.id = t1.company_id)  
				LEFT JOIN taxes as t2 ON (c.customer_freight_tax_code_record_id = t2.tax_record_id AND c.id = t2.company_id)
				WHERE c.id = "'.$id.'"';
				
		$mysql	=	DB::query(Database::SELECT, $mysql);
		$data	=	$mysql->execute()->as_array();
		if(empty($data)) return false;
		else return $data; 
	}
	
	public function admin_company_details($company_id){
		$sql	=	"SELECT name as company_name, address as company_address1, address2 as company_address2,
					 city as company_city, state as company_state, country as company_country, zipcode as company_zipcode 
					 FROM company where id = '".$company_id."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return $data; 
	}
	
	/**
	 * Generic Function to get company information based on key
	 *
	 * @param $company_id : company id of user and value
	 * @return true if updated successfully
	 */
	public function get_company_by_key($keys, $company_id){
		if(isset($keys)){
			$select_query = ' ';
			$flag = 2; 
			
			foreach($keys as $key){ 
				if($flag<2){
					$select_query = $select_query.', ';
				} else {
					--$flag;
				}
				$select_query = $select_query.$key.'';
			}
							 
			$sql	=	"SELECT ".$select_query." 
						 FROM company where id = '".$company_id."'";
			$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			if(isset($data[0]) && !empty($data[0])){
				return $data[0];
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Function to Update CCP ON/OFF as 1/0
	 *
	 * @param $company_id : company id of user and value
	 * @return true if updated successfully
	 */
	public function update($keyval, $company_id){
		$result = DB::update('company')->set($keyval)->where('id', '=', $_SESSION['company_id'])->execute();
		return $result;
	}
	
}?>