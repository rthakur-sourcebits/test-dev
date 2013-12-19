<?php defined('SYSPATH') or die('No direct script access.');
/*
 * @Class: Model ini.php
 * @Created: 21-10-2013
 * @Modified: 21-10-2013 
 * @Description: This model file uses for json or ajax action by AEC_API.
 * Update : <21-10-2013> 	: 	added a functionlity for checking the initial status of AEC API request.
 * 			<21-10-2013> 	:	added a functionlity for 
 */
 
class Model_Aecapi_Master extends Model{
	
	/**
	 * @Method			:	check_init_force
	 * @Description		:	checking for AEC init/force sync. checking 3 things:
	 *						1. If the sync exists for the company id: If not exists then, create sync variable.
	 * 						2. if exits then if the synx field is 1 then, we will got for force sync response,
	 * 						3. If exists & the sync field is 0 then,  we will response with normal sync process.
	 * @Return		:	TRUE/FALSE
	 */
	public function check_init_force($company_id, $module, $aed_master){
		if(isset($company_id)&&isset($module)) {
			$sql			=	"SELECT init_force_sync, UNIX_TIMESTAMP(last_timestamp) AS last_timestamp
								FROM aec_api_master
								WHERE company_id = '".$company_id."' ";
			$result_select	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			if(empty($result_select)){ // If the result is empty.
				// insert query to mark the data as sync.
				$insert_sql	=	"INSERT INTO `aec_api_master` (
									`company_id` , `module` , `init_force_sync` , `last_timestamp`
								) VALUES (
									'$company_id', '$module', '0', CURRENT_TIMESTAMP
								);";
				$result		=	DB::query(Database::INSERT, $insert_sql)->execute();
				return TRUE; // It calls for initialization.
				
			} else { // record exits.
				$result_select=$result_select[0];
				$update_sql	=	"UPDATE `aec_api_master` SET `init_force_sync` = '0' WHERE `company_id` = $company_id AND `module` = '$module'";
				
				if($result_select['init_force_sync']=='1'){
					// Updating the master time stamp.					
					$result	=	DB::query(Database::UPDATE, $update_sql)->execute();
					return TRUE; // AEC is telling to go for init sync
				
				} else if(isset($aed_master['last_timestamp'])&&intval($aed_master['last_timestamp'])<intval($result_select['last_timestamp'])){
					// Updating the master time stamp.
					$result	=	DB::query(Database::UPDATE, $update_sql)->execute();
					return TRUE; // AEC is telling to go for init sync
					
				} else {
					// Updating the master time stamp.					
					$result	=	DB::query(Database::UPDATE, $update_sql)->execute();
					return FALSE; // normal sync
					
				}
			}
			
		} else {
			return "company id and module name is must";
		}
	}

	/**
	 * @Method			:	update_aec_timestamp
	 * @Description		:	updating the time-stamp for company id and module
	 * @Return			:	TIME_STAMP		
	 */
	public function update_aec_timestamp($company_id, $module){
		if(isset($company_id)&&isset($module)) {
			$update_sql	=	"	UPDATE `aec_api_master` 
								SET `last_timestamp` = CURRENT_TIMESTAMP 
								WHERE `company_id` = $company_id AND `module` = '$module'";
			$result		=	DB::query(Database::UPDATE, $update_sql)->execute();
			
			$select_sql = "	SELECT UNIX_TIMESTAMP(last_timestamp) AS last_timestamp 
							FROM `aec_api_master`
							WHERE `company_id` = $company_id AND module = '$module'";
			$result		=	DB::query(Database::SELECT, $select_sql)->execute()->as_array();
							
			if(isset($result[0]['last_timestamp'])){
				return intval($result[0]['last_timestamp']);
			} else{
				// This means the record is not there: then, we should insert a new record and update to user.
				// insert query to mark the data as sync.
				$insert_sql	=	"INSERT INTO `aec_api_master` (
								`company_id` ,
								`module` ,
								`init_force_sync` ,
								`last_timestamp`
								)
								VALUES (
								'$company_id', '$module', '0', CURRENT_TIMESTAMP
								);";
								
				$result		=	DB::query(Database::INSERT, $insert_sql)->execute();
				$select_sql = "	SELECT UNIX_TIMESTAMP(last_timestamp)
								FROM `aec_api_master`
								WHERE `company_id` = $company_id AND module = '$module'";
				$result		=	DB::query(Database::SELECT, $select_sql)->execute()->as_array();
								
				if(isset($result[0]['last_timestamp'])){
					return intval($result[0]['last_timestamp']);
				}else{
					echo "error in getting updated time-stamp.";
				}
			}			
			
		} else{
			echo "Company Id and Module is not set";
			
		}		
	}
		
	
} 