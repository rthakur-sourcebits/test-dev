<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package    Core
 * @author     
 * @copyright
 * @license
 * @created date 4/12/2012
 */

class Sync {
	
	/**
	 * @Access		:	Public
	 * @Function	:	get_unsynced_sale_count
	 * @Description	:	Function to get total number unsynced sales
	 */
	public function get_unsynced_sale_count() {
		$sales					=	new Model_Sales;
		$customers				=	new Model_Customerandjobs;
		$to_besynced_list		=	$sales->get_to_be_synced_list();
		$to_besynced_customers	=	$customers->get_customer_to_sync();
		$to_besynced_jobs		=	$customers->get_jobs_to_sync();
		return count($to_besynced_list)+count($to_besynced_customers)+count($to_besynced_jobs);
	}
}