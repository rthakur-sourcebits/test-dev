<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package    	Core
 * @author     	Rahul Thakur
 * @copyright 	Acclivity Software
 * @license
 * @created date 14/12/2013
 * @Description	
 * 			16.12.2013	-	Added function get_group_info
 * 			16.12.2013	-	Added function set_group_to_user
 * 			16.12.2013	-	Added function access_rule1
 */

class Versioning {
	
	/**
	 * @Access		:	Public
	 * @Function	:	get_group_info
	 * @Description	:	Function to get group information
	 */
	public function get_group_info($company_id) {
		$group_num	=	DB::select('group')->from('company')->where('id', '=', $company_id)->execute()->as_array();
		
		if(isset($group_num[0]) && !empty($group_num[0])){
			return $group_num[0];
		} else {
			return FALSE;
		}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	set_group_to_user
	 * @Description	:	Function to set rule1: 	read build parameter in company
									if build-id => 18.x.x then, show SALES
									else then, hide SALES
	 */
	private function access_rule1($company_id){
		$select_qry	= "	SELECT `id`
						FROM `company`
						WHERE `id` = '".$company_id."'
						AND SUBSTRING_INDEX( build, '.', 1 ) = '18'";
		$rule1	=	DB::query(Database::SELECT, $select_qry)->execute()->as_array();
		
		return $rule1;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	set_group_to_user
	 * @Description	:	Function to set user group number based on rules.
	 */
	public function set_group_to_user($company_id){
		$rule1 = $this->access_rule1($company_id);
		
		if(isset($rule1) && !empty($rule1)){ // Rule1 : Passed.
			$company['group'] = SALES;
		}else{
			$company['group'] = TIME_TRACKER;
		}
		// Update group in company table...
		$result = DB::update('company')->set($company)->where('id', '=', $company_id)->execute();
		
		return $company['group']; 
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	get_access_to_module
	 * @Description	:	Function to check and tell weather to give access to company.
	 */
	public function access_to_module($module_id, $company_id){ 
		$group_num	=	DB::select('group')->from('company')->where('id', '=', $company_id)->execute()->as_array();
		
		if(isset($group_num[0]) && !empty($group_num[0])){
			return $group_num[0]['group'] >= $module_id;
		} else {
			return FALSE;
		}
	}
}

?>