<?php
defined('SYSPATH') or die('No direct script access.');

/*
 * @File : 			accounts.php
 * @Class : 		Model_Accounts
 * @Description: 	Holds Accounts related database operations.
 * @Created Date: 	04-09-2013 
 * @Created By: 	Rahul Thakur
 * @Modified:		04-09-2013 - created 
 * 					20-11-2013 - modified get_company_account function for consolidated TAX.
 */
class Model_Accounts extends Model
{
	// Function to get all company account
	public function get_company_account($country) {
		if(isset($country) && $country==USA){ // for US
			$sql	=	"SELECT account_name, account_number,account_description, id, TaxCodeRecordID 
						FROM accounts 
						WHERE company_id = '".$_SESSION['company_id']."' 
						ORDER BY account_number ASC";
			$return	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
		} else {
			$sql	=	"SELECT a.*, t.percentage, t.tax_code, t.sub_tax_code
						FROM accounts AS a
						LEFT JOIN taxes as t ON   a.company_id = t.company_id
						AND a.TaxCodeRecordID = t.tax_record_id
						WHERE a.company_id = '".$_SESSION['company_id']."'
						ORDER BY account_number ASC ";
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			// Processing for consolidated TAX percentages.
			$return 	= 	array();
			foreach($result as $account){
				if($account['sub_tax_code']!=''){ // Getting consolidated TAX percentage.
					
					// get the sum of the consolidated TAX percentage.
					$sql = 'SELECT SUM(t.percentage) as t_percentage
							FROM taxes as t
							LEFT JOIN sub_taxes as st ON st.tax_code_internal_id = t.id
							WHERE st.consolidated_taxes_internal_id = (	SELECT id 
																		FROM taxes 
																		WHERE tax_record_id = '.$account["TaxCodeRecordID"].'
																		AND company_id = '.$_SESSION["company_id"].')';
					
					$percentage		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
					if(isset($percentage)){
						$account['percentage']=$percentage[0]['t_percentage'];
					}
				} 
				$return[] = $account;
			}
		}

		return $return;
	}
}
?>