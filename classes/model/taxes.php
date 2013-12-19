<?php
defined('SYSPATH') or die('No direct script access.');

/*
 * @File : 			taxes.php
 * @Class : 		Model_Taxes
 * @Description: 	Holds Taxes related database operations.
 * @Created Date: 	04-09-2013 
 * @Created By: 	Rahul Thakur
 * @Modified:		04-09-2013 - create
 * 					11-11-2013 - Added function update_taxes
 * 					11-11-2013 - Added function update_consolidated_taxes
 * 					11-11-2013 - Added function tax_exists
 * 					11-11-2013 - Modified tax_exits query.
 * 					19-11-2013 - Added function sub_tax_exists
 * 					19-11-2013 - Updated function update_consolidated_taxes
 * 					19-11-2013 - Added function add_sub_taxes
 * 					19-11-2013 - Updated function get_taxes 
 */
class Model_Taxes extends Model
{
	// Function to get taxes details of the company
	public function get_taxes() {
		$sql	=	"SELECT id, tax_code, percentage ,description, tax_record_id, sub_tax_code
					 FROM taxes
					 WHERE company_id = '".$_SESSION['company_id']."'
					 AND status = '1' ORDER BY tax_code ASC";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		
		$return = array();
		foreach($result as $tax){
		
			if(!empty($tax['sub_tax_code'])){ 	// getting the consolidated TAX + its TAX info
				// get consolidated Taxes info. from consolidated TAX id.
				$sql	=	"SELECT tax_code_internal_id
							 FROM sub_taxes
							 WHERE  consolidated_taxes_internal_id = '".$tax['id']."'";
				$tax_ids =	DB::query(Database::SELECT, $sql)->execute()->as_array();
				
				if(isset($tax_ids)){
					foreach ($tax_ids as $tax_id) {
						// get Taxes info for the consolidated TAX id from taxes.
						$sql	=	"SELECT id, tax_code, percentage ,description, tax_record_id, sub_tax_code
					 				 FROM taxes
									 WHERE  id = '".$tax_id['tax_code_internal_id']."'";
						$tax_info =	DB::query(Database::SELECT, $sql)->execute()->as_array();
						if(isset($tax_info)){
							$tax_info =	$tax_info[0];
						}
						$tax['consolidated_taxes'][]=$tax_info;
					}
				}
				
				$return[]=$tax;
			} else{
				$return[]=$tax;
			}
		}
		
		return $return;
	}
	
	/**
	 * Function to check tax already present or not
	 *
	 * @return unknown
	 */
	private function tax_exists($tax_record_id, $company_id) {
		$sql	=	"SELECT id
					 FROM taxes
					 WHERE  tax_record_id = '".addslashes($tax_record_id)."'
					 AND company_id = '".addslashes($company_id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return true;
	}
	
	/**
	 * Function to check tax already present or not in sub taxes
	 *
	 * @return unknown
	 */
	private function sub_tax_exists($consolidated_taxes_id, $tax_code) {
		$sql	=	"SELECT id
					 FROM sub_taxes
					 WHERE  consolidated_taxes_internal_id = '".addslashes($consolidated_taxes_id)."'
					 AND  	tax_code = '".addslashes($tax_code)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)){ 
			return false;
		} else { 
			return true;
		}
	}
	
	/**
	 * Function to update all the items
	 *
	 * @param unknown_type $content
	 */
	public function update_taxes($contents) {
		if(count($contents)>0){
			foreach($contents as $c) {
				$tax_code		=	empty($c['TaxCode'])?"":$c['TaxCode'];
				$record_id		=	empty($c['TaxCodeRecordID'])?"":$c['TaxCodeRecordID'];
				$percentage		=	empty($c['Rate'])?"":$c['Rate'];
				$description	=	empty($c['Description'])?"":$c['Description'];
				$tax_collected_acc	=	empty($c['TaxCollectedAccount'])?"":$c['TaxCollectedAccount'];
				$tax_paid_acc		=	empty($c['TaxPaidAccount'])?"":$c['TaxPaidAccount'];
				
				if($this->tax_exists($record_id, $_SESSION['company_id'])) {
					$sql	=	"UPDATE taxes
								 SET tax_record_id 	= '".addslashes($record_id)."',
									 description 	= '".addslashes($description)."',
									 percentage		= '".addslashes($percentage)."',
									 tax_collected_acc	= '".addslashes($tax_collected_acc)."',
									 tax_paid_acc		= '".addslashes($tax_paid_acc)."',
									 last_updated_date = now()
								 WHERE company_id 	= '".$_SESSION['company_id']."'
								 AND tax_code = '".$tax_code."'
								 ";
					$this->_db->query(Database::UPDATE, $sql, False);
				} else {
					$sql	=	"INSERT INTO taxes
								 (company_id, tax_code, tax_record_id, description, percentage, tax_collected_acc, tax_paid_acc, created_date, last_updated_date, status)
								 VALUES (
								 	'".addslashes($_SESSION['company_id'])."',
								 	'".addslashes($tax_code)."',
								 	'".addslashes($record_id)."',
								 	'".addslashes($description)."',
								 	'".addslashes($percentage)."',
								 	'".addslashes($tax_collected_acc)."',
								 	'".addslashes($tax_paid_acc)."',
								 	now(),
								 	now(),
								 	'1'
								 )
								 ";
					$this->_db->query(Database::INSERT, $sql, False);
				}
				
			}
		}
	}
	
	/**
	 * Function to update all the items
	 *
	 * @param unknown_type $content
	 */
	public function update_consolidated_taxes($contents) {
		if(count($contents)>0){
			foreach($contents as $c) {
				$tax_code		=	empty($c['ConsolidatedTaxCode'])?"":$c['ConsolidatedTaxCode'];
				$record_id		=	empty($c['TaxCodeRecordID'])?"":$c['TaxCodeRecordID'];
				$sub_tax_code	=	empty($c['SubTaxCode'])?"":implode(",",$c['SubTaxCode']);
				$description	=	empty($c['ConsolidatedDescription'])?"":$c['ConsolidatedDescription'];
				$rate			=	empty($c['Rate'])?"":$c['Rate'];
				$tax_collected_acc	=	empty($c['TaxCollectedAccount'])?"":$c['TaxCollectedAccount'];
				$tax_paid_acc		=	empty($c['TaxPaidAccount'])?"":$c['TaxPaidAccount'];
				
				if($this->tax_exists($record_id , $_SESSION['company_id'])) {
					$sql	=	"UPDATE taxes
								 SET tax_record_id 	= '".addslashes($record_id)."',
									 description 	= '".addslashes($description)."',
									 sub_tax_code	= '".$sub_tax_code."',
									 percentage		= '".addslashes($rate)."',
									 tax_collected_acc	= '".addslashes($tax_collected_acc)."',
									 tax_paid_acc		= '".addslashes($tax_paid_acc)."',								 
									 last_updated_date = now()
								 WHERE company_id 	= '".$_SESSION['company_id']."'
								 AND tax_code = '".$tax_code."'
								 ";
					$consolidated_taxes_id = $this->_db->query(Database::UPDATE, $sql, False);
					if(isset($consolidated_taxes_id)){ // just entered/updated consolidated taxes.
						$sql =  "	SELECT id
									FROM taxes
									WHERE company_id 	= '".$_SESSION['company_id']."'
								 	AND tax_code = '".$tax_code."'
								 ";
						$consolidated_taxes_id = DB::query(Database::SELECT, $sql)->execute()->as_array();
						if(isset($consolidated_taxes_id)){ // just entered/updated consolidated taxes.
							$consolidated_taxes_id = $consolidated_taxes_id[0];
							$consolidated_taxes_id = $consolidated_taxes_id['id'];
						} else {
							$consolidated_taxes_id = FALSE;
						}
					}else{
						$consolidated_taxes_id = FALSE;
					}
				} else {
					$sql	=	"INSERT INTO taxes
								 (company_id, tax_code, tax_record_id, description, sub_tax_code, percentage, tax_collected_acc, tax_paid_acc, created_date, last_updated_date, status)
								 VALUES (
								 	'".addslashes($_SESSION['company_id'])."',
								 	'".addslashes($tax_code)."',
								 	'".addslashes($record_id)."',
								 	'".addslashes($description)."',
								 	'".$sub_tax_code."',
								 	'".addslashes($rate)."',
								 	'".addslashes($tax_collected_acc)."',
								 	'".addslashes($tax_paid_acc)."',
								 	now(),
								 	now(),
								 	'1'
								 )
								 ";
					$consolidated_taxes_id = $this->_db->query(Database::INSERT, $sql, False);
					if(isset($consolidated_taxes_id)){ // just entered/updated consolidated taxes. 
						$consolidated_taxes_id = $consolidated_taxes_id[0];
					} else{
						$consolidated_taxes_id = FALSE;
					}
				}
				
				// read the sub taxes and making an entry in sub-taxes table.
				if($consolidated_taxes_id && isset($sub_tax_code)){
					// parse the tax code.
					$sub_tax_code = explode(',', $sub_tax_code);
					foreach ($sub_tax_code as $t_code) {
						// adding the entry for sub-tax code. 
						$this->add_sub_taxes($consolidated_taxes_id, $t_code, $_SESSION['company_id']);
					}
					
				} else {
					// do nothing.
				}
			}
		}
	}
	
	/*
	 * Function : add_sub_taxes
	 * 
	 * @Description	: To add/update sub taxes table.
	 * 
	 */  	
	private function add_sub_taxes($consolidated_taxes_id, $tax_code, $company_id){
		
		// find the TAX code.
		$sql = "SELECT id
				FROM taxes
				WHERE tax_code = '".$tax_code."'
				AND company_id = '".$company_id."'
				";
		$tax_code_internal_id = DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(isset($tax_code_internal_id)){
			$tax_code_internal_id = $tax_code_internal_id[0];
			$tax_code_internal_id =	$tax_code_internal_id['id']; 
		}else {
			$tax_code_internal_id = null;
		}
		
		if($this->sub_tax_exists($consolidated_taxes_id, $tax_code)){
			// update the existing entry.
			$sql	=	"UPDATE sub_taxes
						 SET tax_code_internal_id 	= '".addslashes($tax_code_internal_id)."'
						 WHERE consolidated_taxes_internal_id 	= '".$consolidated_taxes_id."'
						 AND tax_code = '".$tax_code."'";
			$this->_db->query(Database::UPDATE, $sql, False);
		} else {
			// make the entry in the sub taxes.
			$sql	=	"INSERT INTO sub_taxes
								 (consolidated_taxes_internal_id,  	tax_code,  	tax_code_internal_id)
								 VALUES (
								 	'".addslashes($consolidated_taxes_id)."',
								 	'".addslashes($tax_code)."',
								 	'".addslashes($tax_code_internal_id)."'
								 )
								 ";
			$this->_db->query(Database::INSERT, $sql, False);
		}
	}
	
	/*
	public function test(){
			$sql = "INSERT INTO taxes
									 (company_id, tax_code, tax_record_id, description, sub_tax_code, percentage, tax_collected_acc, tax_paid_acc, created_date, last_updated_date, status)
									 VALUES (
									 '1',
										 '1',
										 '1',
										 '1',
										 '1',
										 '1',
										 '1',
										 '1',
										 now(),
										 now(),
										 '1')";
			$id = $this->_db->query(Database::INSERT, $sql, False);
			var_dump($id);
		}
		
		public function test1(){
			$sql="UPDATE taxes	 SET tax_record_id 	= '5',
										 description 	= 'test test',
										 sub_tax_code	= 'CTY,STE,MET',
										 percentage		= '0',
										 tax_collected_acc	= '',
										 tax_paid_acc		= '',								 
										 last_updated_date = now()
									 WHERE company_id 	= '201'
									 AND tax_code = 'CST'";
			$id = $this->_db->query(Database::UPDATE, $sql, False);
			var_dump($id);
		}
		
		public function test2(){
			$sql =  "	SELECT id
						FROM taxes
						WHERE company_id 	= '201'
						 AND tax_code = 'CST'";
												   $data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			var_dump($data);	
		}*/
	
}