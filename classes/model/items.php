<?php
defined('SYSPATH') or die('No direct script access.');

/*
 * @File : 			items.php
 * @Class : 		Model_Items
 * @Description: 	Holds Items related database operations.
 * @Created Date: 	04-09-2013 
 * @Created By: 	Rahul Thakur
 * @Modified:		04-09-2013 - created
 * 					20-11-2013 - modified get_items function for consolidated TAX.  
 */
class Model_Items extends Model
{
	// Function to get items of the company
	public function get_items($country) {
		
		if(isset($country) && $country==USA){ // for US
			$sql		=	"SELECT id, item_number, item_name, selling_price, is_description_used, item_description, tax_when_sold_us, tax_when_sold_non_us
						 	FROM items
						 	WHERE company_id = '".$_SESSION['company_id']."' AND status=1 AND is_item_sold = 1 ORDER BY item_number ASC"; // Also we have to get the TAX from customer.
			$return		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		} else {	// for all other countries.
			$sql		=	"SELECT i.id,  item_number, item_name, selling_price, is_description_used, item_description, tax_when_sold_us, tax_when_sold_non_us, tax_code, description, percentage, sub_tax_code
							FROM items AS i
							LEFT JOIN taxes AS t ON i.company_id = t.company_id
							AND i.tax_when_sold_non_us = t.tax_record_id
							WHERE i.company_id = '".$_SESSION['company_id']."'
							AND i.status =  '1' AND is_item_sold = 1 
							ORDER BY item_number ASC";
			$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			// Processing for consolidated TAX percentages.
			$return 	= 	array();
			foreach($result as $item){
				if($item['sub_tax_code']!=''){ // Getting consolidated TAX percentage.
					
					// get the sum of the consolidated TAX percentage.
					$sql = 'SELECT SUM(t.percentage) as t_percentage
							FROM taxes as t
							LEFT JOIN sub_taxes as st ON st.tax_code_internal_id = t.id
							WHERE st.consolidated_taxes_internal_id = (	SELECT id 
																		FROM taxes 
																		WHERE tax_record_id = '.$item["tax_when_sold_non_us"].'
																		AND company_id = '.$_SESSION["company_id"].')';
					
					$percentage		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
					if(isset($percentage)){
						$item['percentage']=$percentage[0]['t_percentage'];
					}
				} 
				$return[] = $item;
			}
		}
		
		return $return;
	}
}