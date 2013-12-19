<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * @File : sales.php Controller
 * @Class : Controller_Sales
 * @Description: This class file holds the operations of new sale, edit, view and sync
 * Copyright (c) 2012 Acclivity Group LLC
 * 
 * @Modified:
 * 			08.12.2013 	-	Function Added Sales Payment Delete.
 * 			13.12.2013 	-	Function added payment_receipt 
 */

class Controller_Sales extends Controller_Template {
	
	var $dropbox_sales_folder;
	var $dropbox_payment_folder;
	private $ach;
	public $template = 'template';
	
	/** 
	 *  Constructor Function
	 */
	public function __construct(Request $request) {
		parent::__construct($request);
		
		$validations = new Model_Validations;
		$validations->check_user_session(); 
		
		$this->dropbox_sales_folder		=	DROPBOXFOLDERPATH."ThirdPartyChanges/Sales/";
		$this->dropbox_payment_folder	=	DROPBOXFOLDERPATH."ThirdPartyChanges/Payments/";
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_index
	 * @Description	:	Sales controller index page
	 * @Param		:	flag
	 */
	public function action_index($flag=0) { 
	try {
		$this->template->tab	=	2;
		require Kohana::find_file('classes', 'library/Sync');
		require Kohana::find_file('classes', 'library/date_format');
		$sync_lib 		= 	new Sync;
		$format 		= 	new date_format;
		$sales			=	new Model_Sales;
		$sort_field		=	isset($_POST['sort_field'])?$_POST['sort_field']:'1';
		$order			=	isset($_POST['order'])?$_POST['order']:'1';
		switch($sort_field) {
			case 1: $field_val	=	"s.created_date";
					break;
			case 2: $field_val	=	"st.type";
					break;
			case 3: $field_val	=	"s.sale_number";
					break;
			case 4: $field_val	=	"customer_name";
					break;
			case 5: $field_val	=	"s.total_payment";
					break;
			default: $field_val	=	"s.created_date";
					 break;
		}
		$order_method		=	($order == 1) ? "DESC":"ASC";
		if(!empty($_POST['search'])) {
		    $search_field = $_POST['search'];
		    $sales_list			=	$sales->get_sales_search_result($_POST['search'], 1, $field_val, $order_method);
			$sales_full_list	=	$sales->get_sales_search_result($_POST['search'], 0, $field_val, $order_method);
			$sale_count			=	count($sales_full_list);
		} else if(!empty($_POST['filter'])) {
		    $search_field = '';
			$sales_list			=	$sales->get_customer_sales_filter(1);
			$sales_full_list	=	$sales->get_customer_sales_filter(0);
			$sale_count			=	count($sales_full_list);
		} else {
		    $search_field = '';
			$sales_list			=	$sales->get_customer_sales(1, $field_val, $order_method);
			$sales_full_list	=	$sales->get_customer_sales(0, $field_val, $order_method);
			$sale_count			=	count($sales_full_list);
		}
		if($flag == 1) {
			$success	=	1;
			$error		=	0;
		} elseif($flag == 2) {
			$success	=	0;
			$error		=	1;
		} else {
			$success	=	0;
			$error		=	0;
		}
		
		$header 	= 	View::factory('sales/sales_list_header')
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('search_value',$search_field)
										->set('tab', '1');
		$this->template->content = 	View::factory('sales/list')
										->set('sales', $sales_list)
										->set('format', $format)
										->set('last_updated_sale',$this->last_created_sale())
										->set('header', $header)
										->set('count_slips', $sale_count)
										->set('per_slip', isset($_POST['view_per_page'])?$_POST['view_per_page']:10)
										->set('page', isset($_POST['page'])?$_POST['page']:0)
										->set('sort_field', $sort_field)
										->set('order', $order)
										->set('search', isset($_POST['search'])?$_POST['search']:"")
										->set('new_sales_menu', View::factory('sales/new_sales_menu'))
										->set('new_sales_filter_menu', View::factory('sales/new_sales_filter_menu'))
										->set('error', $error)
										->set('filter', '0')
										->set('success', $success)
										->set('search_sale',isset($search_field)?$search_field:'');
										
	}catch(Exception $e){
		die($e->getMessage());
	}
											
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_create
	 * @Description	:	Function to create new sales
     */ 
	public function action_create($type=0) {
		$this->template->tab	=	2;
		$sales			=	new Model_Sales;
		try{
			if(isset($_POST['customer'])) { // edit sales form functional call's
				switch($_POST['sale_type']) {
					case 1: $sale_type="Item Invoice";
							$this->process_item_sale($sale_type);
							break;
					case 2: $sale_type="Item Order";
							$this->process_item_sale($sale_type);
							break;
					case 3: $sale_type="Item Quote";
							$this->process_item_sale($sale_type);
							break;
					case 4: $sale_type="Service Invoice";
							$this->process_service_sale($sale_type);
							break;
					case 5: $sale_type="Service Order";
							$this->process_service_sale($sale_type);
							break;
					case 6: $sale_type="Service Quote";
							$this->process_service_sale($sale_type);
							break;
					case 7: $sale_type="Time Billing Invoice";
							$this->process_time_billing_sale($sale_type);
							break;
					case 8: $sale_type="Time Billing Order";
							$this->process_time_billing_sale($sale_type);
							break;
					case 9: $sale_type="Time Billing Quote";
							$this->process_time_billing_sale($sale_type);
							break;
					default: $sale_type="Item Invoice";
							 $this->process_item_sale($sale_type);
							 break;
				}
			} else {  // new sales forms call's (empty form)
				switch($type) {
					case 1: $sale_type="Item Invoice";
							$this->item_sale_form($type, $sale_type);
							break;
					case 2: $sale_type="Item Order";
							$this->item_sale_form($type, $sale_type);
							break;
					case 3: $sale_type="Item Quote";
							$this->item_sale_form($type, $sale_type);
							break;
					case 4: $sale_type="Service Invoice";
							$this->service_sale_form($type, $sale_type);
							break;
					case 5: $sale_type="Service Order";
							$this->service_sale_form($type, $sale_type);
							break;
					case 6: $sale_type="Service Quote";
							$this->service_sale_form($type, $sale_type);
							break;
					case 7: $sale_type="Time Billing Invoice";
							$this->time_billing_sale_form($type, $sale_type);
							break;
					case 8: $sale_type="Time Billing Order";
							$this->time_billing_sale_form($type, $sale_type);
							break;
					case 9: $sale_type="Time Billing Quote";
							$this->time_billing_sale_form($type, $sale_type);
							break;
					default: $sale_type="Item Invoice";
							 $this->item_sale_form($type, $sale_type);
							 break;
				}
			}
		}
		catch(Exception $e) {
		    die($e->getMessage());
		}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	item_sale_form
	 * @Description	:	Function to display empty item invoice form
	 * @Params		:	int, int
	 */
	private function item_sale_form($type, $sale_type){       // CREATE/NEW SALE form.
		require Kohana::find_file('classes', 'library/Sync');
		$data_model 	=	new Model_Data;
		$customer_jobs	=	new Model_Customerandjobs;
		$sales			=	new Model_Sales;
		$taxes_m 		= 	new Model_Taxes;
		$items_m		= 	new Model_Items;
		$jobs_m			= 	new Model_Jobs;
		$accounts_m 	= 	new Model_Accounts;
		$activity_m 	=	new Model_Activity;
		$employee_m 	= 	new Model_Employee;
		$sync_lib 		= 	new Sync;
		$gateway_ach_m	= 	new Model_Gatewayach;
		$company_m		=	new Model_Company;
		
		try {
			$keys 			= 	array('ccp');
			$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
			if(!empty($company_data)&&$company_data['ccp']=='1'){
				$ccp = 1;
			} else {
				$ccp = 0;
			}
			$last_sale				=	$sales->get_sale_number();
			$slip_id				=	str_pad((int) $last_sale,8,"0",STR_PAD_LEFT);
			$sales_create_header 	= 	View::factory('sales/sales_create_header')
													->set('title', $sale_type)
													->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count());
			$popups_sales        	=	View::factory('sales/sales_popups')
			                                    ->set('items', $items_m->get_items($_SESSION['country']))
			                                    ->set('sales_comment', $sales->get_comments())
			                                    ->set('paymentmethod', $sales->get_payment_methods())
			                                    ->set('shippingmethod', $sales->get_shipping_methods())
			                                    ->set('referalsource', $sales->get_referrals())
			                                    ->set('jobs', $jobs_m->get_all_jobs())
			                                    ->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
												->set('employees', $employee_m->get_all_employee($_SESSION['company_id']))
			                                    ->set('customers', $customer_jobs->get_all_customer(1, $_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']));
			$this->template->content = 	View::factory('sales/item_sale_create')
													->set('customer','')
													->set('sale_number',$slip_id)
													->set('selected_date','')
													->set('address1','')
													->set('address2','')
													->set('address3','')
													->set('customer_po','')
													->set('terms','')
													->set('payment_is_due','')
													->set('balance_due_days','')
													->set('discount_days','')
													->set('early_payment_discount','')
													->set('late_payment_charge','')
													->set('item_number','')
													->set('item_description','')
													->set('quantity','0')
													->set('price','0')
													->set('total','0')
													->set('job','')
													->set('sales_person','')
													->set('comment','')
													->set('referal_source','')
													->set('payment_method', '')
													->set('shipping_method', '')
													->set('subtotal','')
													->set('freight','0')
													->set('tax_total_amount','')
													->set('taxes', $taxes_m->get_taxes())
													->set('total_payment','')
													->set('paid_today','')
													->set('balance_due','')
													->set('ach_status',$gateway_ach_m->get_payment_status())
													->set('ccp', $ccp)
													->set('sale_type',$type)
													->set('popups_sales',$popups_sales)
													->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
													->set('sales_create_header',$sales_create_header);
		} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	service_sale_form
	 * @Description	:	Function to display service invoice form
	 * @Params		:	int, int
	 */
	private function service_sale_form($type, $sale_type) {
		require Kohana::find_file('classes', 'library/Sync');
		$data_model 	=	new Model_Data;
		$customer_jobs	=	new Model_Customerandjobs;
		$sales			=	new Model_Sales;
		$taxes_m 		= 	new Model_Taxes;
		$items_m 		=	new Model_Items;
		$jobs_m 		= 	new Model_Jobs;
		$accounts_m		= 	new Model_Accounts;
		$activity_m		=	new Model_Activity;
		$sync_lib 		= 	new Sync;
		$employee_m		=	new Model_Employee;
		$gateway_ach_m	= 	new Model_Gatewayach;
		$company_m		=	new Model_Company;

		try {
			$last_sale		=	$sales->get_sale_number();
			$slip_id		=	str_pad((int) $last_sale,8,"0",STR_PAD_LEFT);
			$keys			=	array('ccp');
			$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
			if(!empty($company_data)&&$company_data['ccp']=='1'){
				$ccp = 1;
			} else {
				$ccp	= 0;
			}

			$sales_create_header = View::factory('sales/sales_create_header')
												->set('title', $sale_type)
												->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count());
	    	$popups_sales        = View::factory('sales/sales_popups')
		                                    ->set('sales_comment', $sales->get_comments())
		                                    ->set('paymentmethod', $sales->get_payment_methods())
		                                    ->set('shippingmethod', $sales->get_shipping_methods())
		                                    ->set('referalsource', $sales->get_referrals())
		                                    ->set('jobs', $jobs_m->get_all_jobs())
		                                    ->set('accounts', $accounts_m->get_company_account($_SESSION['country']))
											->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
											->set('employees', $employee_m->get_all_employee($_SESSION['company_id']))
		                                    ->set('customers', $customer_jobs->get_all_customer(1, $_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']));
			$this->template->content = 	View::factory('sales/service_sale_create')
												->set('customers', $customer_jobs->get_all_customer(1, $_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']))
												->set('customer','')
												->set('sale_number',$slip_id)
												->set('selected_date','')
												->set('address1','')
												->set('address2','')
												->set('address3','')
												->set('customer_po','')
												->set('terms','')
												->set('payment_is_due','')
												->set('balance_due_days','')
												->set('discount_days','')
												->set('early_payment_discount','')
												->set('late_payment_charge','')
												->set('description', '')
												->set('account','')
												->set('amount','0')
												->set('job','')
												->set('tax','')
												->set('sales_person','')
												->set('comment','')
												->set('referal_source','')
												->set('payment_method', '')
												->set('shipping_method', '')
												->set('subtotal','')
												->set('freight','0')
												->set('tax_total_amount','')
												->set('ach_status',$gateway_ach_m->get_payment_status())
												->set('ccp', $ccp)
												->set('taxes', $taxes_m->get_taxes())
												->set('total_payment','')
												->set('paid_today','')
												->set('balance_due','')
												->set('sale_type',$type)
												->set('popups_sales',$popups_sales)
												->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
												->set('sales_create_header',$sales_create_header);
		} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	time_billing_sale_form
	 * @Description	:	Function to display time billing form
	 * @Params		:	int, int	
	 */
	private function time_billing_sale_form($type, $sale_type) {
		require Kohana::find_file('classes', 'library/Sync');
		$data_model 	=	new Model_Data;
		$customer_jobs	=	new Model_Customerandjobs;
		$sales			=	new Model_Sales;
		$taxes_m 		= 	new Model_Taxes;
		$items_m		= 	new Model_Items;
		$jobs_m			=	new Model_Jobs;
		$accounts_m		= 	new Model_Accounts;
		$activity_m		= 	new Model_Activity;
		$sync_lib 		= 	new Sync;
		$employee_m		=	new Model_Employee;
		$gateway_ach_m	= 	new Model_Gatewayach;
		$company_m		=	new Model_Company;
		
		try {
			$last_sale		=	$sales->get_sale_number();
			$keys 			= 	array('ccp');
			$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
			if(!empty($company_data)&&$company_data['ccp']=='1'){
				$ccp = 1;
			} else {
				$ccp = 0;
			}
			$slip_id		=	str_pad((int) $last_sale,8,"0",STR_PAD_LEFT);
			$sales_create_header = View::factory('sales/sales_create_header')
												->set('title', $sale_type)
												->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count());
			$popups_sales        = View::factory('sales/sales_popups')
		                                    ->set('sales_comment', $sales->get_comments())
		                                    ->set('paymentmethod', $sales->get_payment_methods())
		                                    ->set('referalsource', $sales->get_referrals())
		                                    ->set('jobs', $jobs_m->get_all_jobs())
		                                    ->set('activities', $activity_m->get_activities($_SESSION['country']))
											->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
											->set('employees', $employee_m->get_all_employee($_SESSION['company_id']))
		                                    ->set('customers', $customer_jobs->get_all_customer(1,$_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']));
			$this->template->content = 	View::factory('sales/time_billing_sale_create')
												->set('customer','')
												->set('sale_number',$slip_id)
												->set('selected_date','')
												->set('date','')
												->set('terms','')
												->set('payment_is_due','')
												->set('balance_due_days','')
												->set('discount_days','')
												->set('early_payment_discount','')
												->set('late_payment_charge','')
												->set('date_items', '')
												->set('units', '0')
												->set('activity', '')
												->set('description', '')
												->set('rate', '')
												->set('amount','0')
												->set('job','')
												->set('sales_person','')
												->set('comment','')
												->set('referal_source','')
												->set('payment_method', '')
												->set('payment_type', '')
												->set('ach_status',$gateway_ach_m->get_payment_status())
												->set('ccp', $ccp)
												->set('subtotal','')
												->set('tax_total_amount','')
												->set('taxes', $taxes_m->get_taxes())
												->set('total_payment','')
												->set('paid_today','')
												->set('balance_due','')
												->set('sale_type',$type)
												->set('popups_sales',$popups_sales)
												->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
												->set('sales_create_header',$sales_create_header);
		} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	process_item_sale
	 * @Description	:	Function to process item invoice sale
	 * @Param		:	int 
	 */	
	private function process_item_sale($sale_type) {
		$sales			=	new Model_Sales;
		$customer_jobs	=	new Model_Customerandjobs;
		$taxes_m 		= 	new Model_Taxes;
		$items_m 		= 	new Model_Items;
		$jobs_m			= 	new Model_Jobs;
		$gateway_ach_m	=	new Model_Gatewayach;
		$transaction_m 	=	new Model_Transaction;
		$company_m		=	new Model_Company;
		
		try {
			$sale_id 		=	NULL;
			$keys 			= 	array('ccp');
			$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
			if(!empty($company_data)&&$company_data['ccp']=='1'){
				$ccp = 1;
			} else {
				$ccp = 0;
			}
			$gateway_info 	=	$gateway_ach_m->get_merchant_personal_ach_details($_SESSION['company_id']);
			if(isset($gateway_info[0]['status'])){
				if($gateway_info[0]['status'] == '1'){
					$gateway_status=1;
				}else {
					$gateway_status=0;
				}
			} else {
				$gateway_status=0;
			}
			
			if(intval($_POST['paid_today']) > 0) {
				if( $_POST['payment_type'] != '2'){
				$payment='2'; // payment non credit card
				}
				else {
					$payment='1'; // payment credit card
				}
			}
			
			/**Address Line1, Address Line2, Address Line3 Issue starts**/
			if(empty($_POST['address1']) && !empty($_POST['address2'])){
				$_POST['address1']	=	$_POST['address2'];
				$_POST['address2']	=	'';
			}
			if(empty($_POST['address1']) && !empty($_POST['address3'])){
				$_POST['address1']	=	$_POST['address3'];
				$_POST['address3']	=	'';
			}
			if(empty($_POST['address2']) && !empty($_POST['address3'])){
				$_POST['address2']	=	$_POST['address3'];
				$_POST['address3']	=	'';
			}
			/**Address Line1, Address Line2, Address Line3 Issue ends - Resolved Here**/
			if($_POST['customer'] == "") throw new Exception("Please select customer");
			if($_POST['sale_number'] == "") throw new Exception("Please enter sale number");
			if($_POST['item_number'] == "") throw new Exception("Please select items");
			if($_POST['quantity'] == "") throw new Exception("Please enter quantity");
			if($_POST['price'] == "") throw new Exception("Please enter price");
			if(!empty($_POST['sales_person'])) {
				$sales_person_arr	=	explode("--", $_POST['sales_person']);
				$sales_person_id	=	$sales_person_arr[1];
				$sales_person_name	=	$sales_person_arr[0];
			} else {
				$sales_person_id	=	0;
				$sales_person_name	=	"";
			}
			if(isset($_POST['edit_sale'])) { 								// EDIT :  if user edits item invoice sale
				$sale_id			=	$_POST['sale_id'];
				$sales_data			=	array(	'customer_id'			=> $_POST['customer'],
												  'address1'			=> $_POST['address1'], 
												  'address2'			=> $_POST['address2'],
												  'address3'			=> $_POST['address3'], 
												  'sale_number'			=> $_POST['sale_number'], 
												  'selected_date'		=> $_POST['selected_date'], 
												  'sales_person_id'		=> $sales_person_id,
												  'sales_person'		=> $sales_person_name,
												  'comment'				=> $_POST['comment'], 
												  'referal_source'		=> $_POST['referal_source'], 
												  'payment_method'		=> $_POST['payment_method'],
												  'payment_type'		=> $_POST['payment_type'],  
												  'shipping_method'		=> $_POST['shipping_method'], 
												  'freight'				=> $_POST['freight_orginal_amt'], 
												  'freight_tax'			=> isset($_POST['freight_check'])?$_POST['freight_check']:'0',
												  'freight_tax_record_id'	=> isset($_POST['freight_tax_record_id'])?$_POST['freight_tax_record_id']:'0',
												  'subtotal'			=> $_POST['subtotal'],  
												  'tax_total_amount'	=> $_POST['tax_total_amount'], 
												  'total_payment'		=> $_POST['total_payment'],
												  'paid_today'			=> $_POST['paid_today'],
												  'balance'				=> $_POST['balance_due'],
												  'customer_po'			=> $_POST['customer_po'],
												  'terms'				=> $_POST['terms'],
												  'payment_is_due'		=> isset($_POST['payment_is_due']) ? $_POST['payment_is_due'] : "" ,
												  'balance_due_days'	=> isset($_POST['balance_due_days']) ? $_POST['balance_due_days'] : "0" ,
												  'discount_days'		=> isset($_POST['discount_days']) ? $_POST['discount_days'] : "0" ,
												  'early_payment_discount'=> isset($_POST['early_payment_discount']) ? $_POST['early_payment_discount'] : "0" ,
												  'late_payment_charge'	=> isset($_POST['late_payment_charge']) ? $_POST['late_payment_charge'] : "0" ,
												  'payment'				=> isset($payment) ? $payment :'0',
												  'is_tax_inclusive'	=> $_POST['is_tax_inclusive'],
												  'updated_date'		=> date('Y-m-d H:i:s')
											);
				$sale_info	=	$sales->update("sales", $sales_data, $_POST['sale_id']);  // updating sales here
				
				for($i=0;$i<$_POST['total_items'];$i++) { // update each sale item
					if($_POST['phone_mode'] == "1") {
						if(!isset($_POST['item_id_phone'][$i])) {
							$sale_item_columns	=	array(	'sale_id', 
															'item_number', 
															'item_name', 
															'quantity', 
															'price',
															'total', 
															'job_id', 
															'apply_tax', 
															'tax_record_id',
															'created_date', 
															'status'
													);
							$sale_item_values	=	array(	$sale_id, 
															$_POST['item_number_phone'][$i], 
															$_POST['item_description_phone'][$i], 
															$_POST['quantity_phone'][$i], 
															$_POST['price_phone'][$i],
															$_POST['total_phone'][$i], 
															$_POST['job_phone'][$i],
															isset($_POST['tax_check_phone'][$i])?$_POST['tax_check_phone'][$i]:'0',// need to write of apply_tax
															$_POST['item_tax_record_id_mobile'][$i], // need to write of tax record id
															date("Y-m-d H:i:s"), 
															'1'
													);
							$sale_item	=	$sales->save("sale_item", $sale_item_columns, $sale_item_values);
						} else {
							$sale_item_data			=	array(	'item_number'	=> $_POST['item_number_phone'][$i],
													  			'item_name'	=> $_POST['item_description_phone'][$i], 
													  			'quantity'	=> $_POST['quantity_phone'][$i],
													  			'price'		=> $_POST['price_phone'][$i], 
													  			'total'		=> $_POST['total_phone'][$i], 
													  			'job_id'		=> $_POST['job_phone'][$i],
													  			'apply_tax'		=> isset($_POST['tax_check_phone'][$i])?$_POST['tax_check_phone'][$i]:'0',
													  			'tax_record_id'	=> $_POST['item_tax_record_id_mobile'][$i] // need to for tax record id
												);
							$item_id			=	$_POST['item_id_phone'][$i];
							$sale_item_info		=	$sales->update("sale_item", $sale_item_data, $item_id);
						}
					} else {							
							if(!isset($_POST['item_id'][$i])) {							
							$sale_item_columns	=	array(	'sale_id', 
															'item_number', 
															'item_name', 
															'quantity', 
															'price',
															'total', 
															'job_id', 
															'apply_tax',
															'tax_record_id', 
															'created_date', 
															'status'
													);
							$sale_item_values	=	array(	$sale_id, 
															$_POST['item_number'][$i], 
															$_POST['item_description'][$i], 
															$_POST['quantity'][$i], 
															$_POST['price'][$i],
															$_POST['total'][$i], 
															$_POST['job'][$i], 
															isset($_POST['tax_check'][$i])?$_POST['tax_check'][$i]:'0',
															$_POST['item_tax_record_id'][$i], 
															date("Y-m-d H:i:s"),
															'1'
													);
							$sale_item	=	$sales->save("sale_item", $sale_item_columns, $sale_item_values);
						}
						else {
							$sale_item_data			=	array('item_number'	=> $_POST['item_number'][$i],
													  'item_name'	=> $_POST['item_description'][$i], 
													  'quantity'	=> $_POST['quantity'][$i],
													  'price'		=> $_POST['price'][$i], 
													  'total'		=> $_POST['total'][$i], 
													  'job_id'		=> $_POST['job'][$i],
													  'tax_record_id'=>$_POST['item_tax_record_id'][$i], 
													  'apply_tax'			=> isset($_POST['tax_check'][$i])?$_POST['tax_check'][$i]:'0'
												);
							$item_id			=	$_POST['item_id'][$i];
							$sale_item_info		=	$sales->update("sale_item", $sale_item_data, $item_id);
						}
					}
				}
				$sale_id	=	$_POST['sale_id'];
			} else {																 // SAVE: CREATE/NEW: if user create new item invoice sale
				$sale_columns	=	array( 'company_id', 
											'employee_id', 
											'admin_user', 
											'type', 
											'customer_id',
										   	'address1', 
										   	'address2', 
										   	'address3', 
										   	'sale_number', 
										   	'selected_date',
										   	'sales_person_id',
										   	'sales_person',
										   	'comment', 
										   	'referal_source', 
										   	'payment_method',
										   	'payment_type', 
										   	'shipping_method',
										   	'freight', 
										   	'freight_tax', 
										   	'freight_tax_record_id',
										   	'tax_total_amount', 
										   	'subtotal', 
										   	'total_payment', 
										   	'paid_today', 
										   	'balance', 
										   	'customer_po', 
										   	'terms', 
											'payment_is_due',
											'balance_due_days',
											'discount_days',
											'early_payment_discount',
											'late_payment_charge',
											'payment',
										   	'is_tax_inclusive',
										   	'created_date', 
										   	'updated_date',
										   	'sync_status', 
										   	'status'
									);
				$sale_values	=	array( $_SESSION['company_id'], 
											$_SESSION['employee_id'], 
											$_SESSION['admin_user'],
										   	$_POST['sale_type'], 
										   	$_POST['customer'], 
										   	$_POST['address1'], 
										   	$_POST['address2'], 
										   	$_POST['address3'],
										   	$_POST['sale_number'], 
										   	$_POST['selected_date'], 
										   	$sales_person_id, 
										   	$sales_person_name, 
										   	$_POST['comment'], 
										   	$_POST['referal_source'],
										   	$_POST['payment_method'],
										   	$_POST['payment_type'], 
										   	$_POST['shipping_method'], 
										   	$_POST['freight_orginal_amt'], 
										   	isset($_POST['freight_check'])?$_POST['freight_check']:'0',
										   	isset($_POST['freight_tax_record_id'])?$_POST['freight_tax_record_id']:'0',
										   	$_POST['tax_total_amount'],
										   	$_POST['subtotal'], 
										   	$_POST['total_payment'], 
										   	$_POST['paid_today'], 
										   	$_POST['balance_due'],
										   	$_POST['customer_po'], 
										   	$_POST['terms'], 
										   	isset($_POST['payment_is_due']) ? $_POST['payment_is_due'] : "",
										   	isset($_POST['balance_due_days']) ? $_POST['balance_due_days'] : "0",
										   	isset($_POST['discount_days']) ? $_POST['discount_days'] : "0",
										   	isset($_POST['early_payment_discount']) ? $_POST['early_payment_discount'] : "0",
										   	isset($_POST['late_payment_charge']) ? $_POST['late_payment_charge'] : "0",
										   	isset($payment) ? $payment :'0',
										   	$_POST['is_tax_inclusive'],
										   	date("Y-m-d H:i:s"), 
										   	date("Y-m-d H:i:s"), 
										   	'0', 
										   	'1' 
									);
				$sale_info	=	$sales->save("sales", $sale_columns, $sale_values);
				$sale_id	=	$sale_info[0];
				for($i=0;$i<$_POST['total_items'];$i++) { // insert each sale items
					$sale_item_columns	=	array(	'sale_id', 
													'item_number', 
													'item_name', 
													'quantity', 
													'price',
													'total', 
													'job_id', 
													'apply_tax',
													'tax_record_id', 
													'created_date', 
													'status'
											);
					if($_POST['phone_mode'] == "1") {
						$sale_item_values	=	array(	$sale_id, 
														$_POST['item_number_phone'][$i], 
														$_POST['item_description_phone'][$i], 
														$_POST['quantity_phone'][$i], 
														$_POST['price_phone'][$i],
														$_POST['total_phone'][$i], 
														$_POST['job_phone'][$i], 
														isset($_POST['tax_check_phone'][$i])?$_POST['tax_check_phone'][$i]:'0',
														$_POST['item_tax_record_id_mobile'][$i], // we need to get this id for phone mode.
														date("Y-m-d H:i:s"), 
														'1'
											);
					} else {
						$sale_item_values	=	array(	$sale_id, 
														$_POST['item_number'][$i], 
														$_POST['item_description'][$i], 
														$_POST['quantity'][$i], 
														$_POST['price'][$i],
														$_POST['total'][$i], 
														$_POST['job'][$i], 
														isset($_POST['tax_check'][$i])?$_POST['tax_check'][$i]:'0',
														$_POST['item_tax_record_id'][$i], 
														date("Y-m-d H:i:s"), 
														'1'
											);
					}
					$sale_item	=	$sales->save("sale_item", $sale_item_columns, $sale_item_values);
				}
			}
			// Entering payment info for sale edit + create page.
			$is_payment_checked	=	$transaction_m->check_payment_exists($sale_id);
			if(isset($is_payment_checked)&&!empty($is_payment_checked)){// payment exist then, skip transaction entry.
				// do nothing 
				// skip
			} else {
				// rules: for entry in transaction table.
				// ccp is OFF + all payment method.
				// ccp is ON + all payment method except credit card.
				
				// CCP is ON + credit card + paid-today then, it will get save but the green tick will not come.
				// basically skip this process.
				if(intval($_POST['paid_today']) > 0 ){ 	// If you like anything to pay.
					if($ccp==1 && (	$_POST['payment_type'] == CREDIT_CARD )){ // payment type 2 is for credit card
						$transaction_details  = array('customer_id'						 => intval($_POST['customer']),
													  'sale_id'							 => $sale_id,
 						 	 						  'transaction_amount'		 		 => intval($_POST['paid_today']),
 						 	 						  'transaction_date'  			 	 => date('Y-m-d H:i:s'),
 													  'gateway_transaction_id'    		 => '',
 						 	 						  'gateway_authorization_code'		 => '',	
													  'payment_method'					 => $_POST['payment_method'],
 													  'gateway_transaction_status'		 => '',
 						 	 						  'gateway_transaction_short_message'=> '', 	
 						 	 						  'gateway_transaction_long_message' => ''
 						  						);
  						$new_transaction_id = $transaction_m->create('transaction', $transaction_details);
						
					} else {  // CCP is 0 + all payment-method & CCP is 1 + except credit-card payment method.
						if($_POST['payment_type'] == CREDIT_CARD){ // Save in transaction table
							$transaction_details  = array('customer_id'						 => intval($_POST['customer']),
														  'sale_id'							 => $sale_id,
	 						 	 						  'transaction_amount'		 		 => intval($_POST['paid_today']),
	 						 	 						  'transaction_date'  			 	 => date('Y-m-d H:i:s'),
	 													  'gateway_transaction_id'    		 => '',
	 						 	 						  'gateway_authorization_code'		 => '',	
														  'payment_method'					 => $_POST['payment_method'],
	 													  'gateway_transaction_status'		 => '',
	 						 	 						  'gateway_transaction_short_message'=> '', 	
	 						 	 						  'gateway_transaction_long_message' => ''
	 						  					);
	  						$new_transaction_id = $transaction_m->create('transaction', $transaction_details);
						} 
						else { // saving in transaction other table.
							$transaction_details  = array('customer_id'			 => intval($_POST['customer']),
														  'sale_id'				 => intval($sale_id),
					 	 								  'transaction_amount'	 => intval($_POST['paid_today']),
					 	 								  'transaction_date'  	 => date('Y-m-d H:i:s'),
														  'payment_method'		 => $_POST['payment_method'],
														  'check_num'			 => '',
														  'notes'				 => ''
						 	 						);
							$new_transaction_id = $transaction_m->create('transaction_other', $transaction_details);
						 }
					}	  
				} else {
					// skip record
				}
			}
			
			if(isset($_POST['details']) && ($_POST['details']==1)) {				
				$sale_key	=	md5(time().rand());
				$sales_data	=	array(	"sale_key" 		=> 	$sale_key,
										'updated_date'	=>	date('Y-m-d H:i:s'));
				$sale_info	=	$sales->update("sales", $sales_data, $sale_id);
				Request::instance()->redirect(SITEURL.'/sales/payment/'.$sale_key);
			} else {
			 	Request::instance()->redirect(SITEURL.'/sales/view/'.$sale_id);
			}
		} catch(Exception $e) { die($e->getMessage(). " line ". $e->getLine());
			$this->template->content = 	View::factory('sales/item_sale_create')
												->set('customers', $customer_jobs->get_all_customer(1, $_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']))
												->set('customer',$_POST['customer'])
												->set('sale_number',$_POST['sale_number'])
												->set('selected_date',$_POST['selected_date'])
												->set('address1',$_POST['address1'])
												->set('address2',$_POST['address2'])
												->set('address3',$_POST['address3'])
												->set('customer_po',$_POST['customer_po'])
												->set('terms',$_POST['terms'])
												->set('payment_is_due', $_POST['payment_is_due'])
												->set('balance_due_days', $_POST['balance_due_days'])
												->set('discount_days', $_POST['discount_days'])
												->set('early_payment_discount', $_POST['early_payment_discount'])
												->set('late_payment_charge', $_POST['late_payment_charge'])
												->set('items', $items_m->get_items($_SESSION['country']))
												->set('item_number',$_POST['item_number'])
												->set('item_description',$_POST['item_description'])
												->set('quantity',$_POST['quantity'])
												->set('price',$_POST['price'])
												->set('total',$_POST['total'])
												->set('job',$_POST['job'])
												->set('jobs', $jobs_m->get_all_jobs())
												->set('tax_total_amount',$_POST['tax_total_amount'])
												->set('sales_person',$_POST['sales_person'])
												->set('sales_comment', $sales->get_comments())
												->set('comment',$_POST['comment'])
												->set('referal_source',$_POST['referal_source'])
												->set('referalsource', $sales->get_referrals())
												->set('payment_method', $_POST['payment_method'])
												->set('payment', isset($payment) ? $payment :'0')
												->set('paymentmethod', $sales->get_payment_methods())
												->set('payment_type', $_POST['payment_type'])
												->set('shipping_method', $_POST['shipping_method'])
												->set('shippingmethod', $sales->get_shipping_methods())
												->set('subtotal',$_POST['subtotal'])
												->set('freight',$_POST['freight'])
												->set('tax',$_POST['tax'])
												->set('taxes', $taxes_m->get_taxes())
												->set('total_payment',$_POST['total_payment'])
												->set('paid_today',$_POST['paid_today'])
												->set('balance_due',$_POST['balance_due'])
												->set('error',$e->getMessage());
		}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	process_service_sale
	 * @Description	:	Function to process item invoice sale
	 * @Param		:	int
	 */
	private function process_service_sale($sale_type) {
		$sales			=	new Model_Sales;
		$customer_jobs	=	new Model_Customerandjobs;
		$taxes_m		= 	new Model_Taxes;
		$jobs_m			= 	new Model_Jobs;
		$accounts_m		= 	new Model_Accounts;
		$transaction_m 	=	new Model_Transaction;
		$gateway_ach_m	=	new Model_Gatewayach;
		$company_m		=	new Model_Company;
		
		try {
			$sale_id =	NULL;
			$keys 			= 	array('ccp');
			$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
			if(!empty($company_data)&&$company_data['ccp']=='1'){
				$ccp = 1;
			} else {
				$ccp = 0;
			}
			$gateway_info =	$gateway_ach_m->get_merchant_personal_ach_details($_SESSION['company_id']);
			if(isset($gateway_info[0]['status'])){
				if($gateway_info[0]['status'] == '1'){
					$gateway_status=1;
				}else {
					$gateway_status=0;
				}
			} else {
				$gateway_status=0;
			}
			if(intval($_POST['paid_today']) > 0) {
				if( $_POST['payment_type'] != '2'){
				$payment='2'; // payment non credit card
				}
				else {
					$payment='1'; // payment credit card
				}
			}
			/**Address Line1, Address Line2, Address Line3 Issue starts**/
			if(empty($_POST['address1']) && !empty($_POST['address2'])){
				$_POST['address1']	=	$_POST['address2'];
				$_POST['address2']	=	'';
			}
			if(empty($_POST['address1']) && !empty($_POST['address3'])){
				$_POST['address1']	=	$_POST['address3'];
				$_POST['address3']	=	'';
			}
			if(empty($_POST['address2']) && !empty($_POST['address3'])){
				$_POST['address2']	=	$_POST['address3'];
				$_POST['address3']	=	'';
			}
		/**Address Line1, Address Line2, Address Line3 Issue ends - Resolved Here**/
			if($_POST['customer'] == "") throw new Exception("Please select customer");
			if($_POST['sale_number'] == "") throw new Exception("Please enter sale number");
			if(!empty($_POST['sales_person'])) {
				$sales_person_arr	=	explode("--", $_POST['sales_person']);
				$sales_person_id	=	$sales_person_arr[1];
				$sales_person_name	=	$sales_person_arr[0];
			} else {
				$sales_person_id	=	0;
				$sales_person_name	=	"";
			}
			//echo "<pre>";print_r($_POST);die;
			if(isset($_POST['edit_sale'])) { // if user edits item invoice sale
				$sale_id			=	$_POST['sale_id'];
				$sales_data			=	array(	  'customer_id'			=> $_POST['customer'],
												  'address1'			=> $_POST['address1'], 
												  'address2'			=> $_POST['address2'],
												  'address3'			=> $_POST['address3'], 
												  'sale_number'			=> $_POST['sale_number'], 
												  'selected_date'		=> $_POST['selected_date'], 
												  'sales_person_id'		=> $sales_person_id,
												  'sales_person'		=> $sales_person_name,
												  'comment'				=> $_POST['comment'], 
												  'referal_source'		=> $_POST['referal_source'], 
												  'payment_method'		=> $_POST['payment_method'], 
												  'payment_type'		=> $_POST['payment_type'],
												  'shipping_method'		=> $_POST['shipping_method'], 
												  'freight'				=> $_POST['freight_orginal_amt'], 
												  'freight_tax'			=> isset($_POST['freight_check'])?$_POST['freight_check']:'0', 
												  'freight_tax_record_id'	=> isset($_POST['freight_tax_record_id'])?$_POST['freight_tax_record_id']:'0',
												  'tax_total_amount'	=> $_POST['tax_total_amount'], 
												  'subtotal'			=> $_POST['subtotal'],  
												  'total_payment'		=> $_POST['total_payment'],
												  'payment'				=> isset($payment) ? $payment :'0',
												  'paid_today'			=> $_POST['paid_today'],
												  'is_tax_inclusive' 	=> $_POST['is_tax_inclusive'],
												  'balance'				=> $_POST['balance_due'],
												  'customer_po'			=> $_POST['customer_po'],
												  'terms'				=> $_POST['terms'],
												  'payment_is_due'		=> isset($_POST['payment_is_due']) ? $_POST['payment_is_due'] : "" ,
												  'balance_due_days'	=> isset($_POST['balance_due_days']) ? $_POST['balance_due_days'] : "0" ,
												  'discount_days'		=> isset($_POST['discount_days']) ? $_POST['discount_days'] : "0" ,
												  'early_payment_discount'=> isset($_POST['early_payment_discount']) ? $_POST['early_payment_discount'] : "0" ,
												  'late_payment_charge'	=> isset($_POST['late_payment_charge']) ? $_POST['late_payment_charge'] : "0" ,
												  'updated_date'		=> date('Y-m-d H:i:s')
											);
				$sale_info	=	$sales->update("sales", $sales_data, $_POST['sale_id']);
				for($i=0;$i<$_POST['total_items'];$i++) { // update each sale item
					if($_POST['phone_mode'] == "1") {
						if(!isset($_POST['service_id_phone'][$i])) {
							$sale_service_columns	=	array('sale_id', 'description', 'account', 'amount', 'job_id', 
												  'tax','tax_record_id','status'
												);
												//echo "<pre>";print_r($_POST);die;
							$sale_service_values	=	array($sale_id, $_POST['description_phone'][$i], $_POST['account_phone'][$i], $_POST['amount_phone'][$i], 
												  $_POST['job_phone'][$i], isset($_POST['tax_check_phone'][$i])?$_POST['tax_check_phone'][$i]:'0',$_POST['service_tax_record_id_mobile'][$i], '1'
												);
							$sale_item	=	$sales->save("sale_service", $sale_service_columns, $sale_service_values);
						} else {
							$sale_item_data			=	array('description'	=> $_POST['description_phone'][$i],
													  'account'			=> $_POST['account_phone'][$i], 
													  'amount'			=> $_POST['amount_phone'][$i],
													  'job_id'			=> $_POST['job_phone'][$i],
													  'tax'				=> isset($_POST['tax_check_phone'][$i])?$_POST['tax_check_phone'][$i]:'0',
													  'tax_record_id'	=> $_POST['service_tax_record_id_mobile'][$i]
												);
							$service_id			=	$_POST['service_id_phone'][$i];
							$sale_item_info	=	$sales->update("sale_service", $sale_item_data, $service_id);
						}
					} else {
						if(!isset($_POST['service_id'][$i])) {
							$sale_service_columns	=	array('sale_id', 'description', 'account', 'amount', 'job_id', 
												  'tax', 'tax_record_id','status'
												);
							$sale_service_values	=	array($sale_id, $_POST['description'][$i], $_POST['account'][$i], $_POST['amount'][$i], 
												  $_POST['job'][$i], isset($_POST['tax_check'][$i])?$_POST['tax_check'][$i]:'0',$_POST['service_tax_record_id'][$i], '1'
												);
							$sale_item	=	$sales->save("sale_service", $sale_service_columns, $sale_service_values);
						} else {
							$sale_item_data			=	array('description'	=> $_POST['description'][$i],
													  'account'			=> $_POST['account'][$i], 
													  'amount'			=> $_POST['amount'][$i],
													  'job_id'			=> $_POST['job'][$i],
													  'tax'				=> isset($_POST['tax_check'][$i])?$_POST['tax_check'][$i]:'0',
													  'tax_record_id'	=> $_POST['service_tax_record_id'][$i]
												);
							$service_id			=	$_POST['service_id'][$i];
							$sale_item_info	=	$sales->update("sale_service", $sale_item_data, $service_id);
						}
					}
					
					
				}
			} else { // if user create new item invoice sale
				$sale_columns	=	array( 'company_id', 'employee_id', 'admin_user', 'type', 'customer_id',
										   'address1', 'address2', 'address3', 'sale_number', 'selected_date', 'sales_person_id', 'sales_person',
										   'comment', 'referal_source', 'payment_method', 'payment', 
										   'payment_type', 'shipping_method',
										   'freight', 'freight_tax','freight_tax_record_id', 'tax_total_amount', 'subtotal', 'total_payment', 'paid_today', 
										   'balance', 'customer_po', 'terms','payment_is_due','balance_due_days','discount_days','early_payment_discount','late_payment_charge','is_tax_inclusive', 'created_date', 'updated_date',
										   'sync_status', 'status'
									);
											
				$sale_values	=	array( $_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user'],
										   $_POST['sale_type'], $_POST['customer'], $_POST['address1'], $_POST['address2'], $_POST['address3'],
										   $_POST['sale_number'], $_POST['selected_date'], $sales_person_id, $sales_person_name, $_POST['comment'], $_POST['referal_source'],
										   $_POST['payment_method'], isset($payment) ? $payment :'0', $_POST['payment_type'], $_POST['shipping_method'], $_POST['freight_orginal_amt'], isset($_POST['freight_check'])?$_POST['freight_check']:'0',isset($_POST['freight_tax_record_id'])?$_POST['freight_tax_record_id']:'0', $_POST['tax_total_amount'],
										   $_POST['subtotal'], $_POST['total_payment'], $_POST['paid_today'], $_POST['balance_due'],
										   $_POST['customer_po'], $_POST['terms'], isset($_POST['payment_is_due']) ? $_POST['payment_is_due'] : "", isset($_POST['balance_due_days']) ? $_POST['balance_due_days'] : "0", isset($_POST['discount_days']) ? $_POST['discount_days'] : "0", isset($_POST['early_payment_discount']) ? $_POST['early_payment_discount'] : "0", isset($_POST['late_payment_charge']) ? $_POST['late_payment_charge'] : "0",$_POST['is_tax_inclusive'], date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), '0', '1' 
									);
									
									//die("g");
				$sale_info	=	$sales->save("sales", $sale_columns, $sale_values);
				$sale_id	=	$sale_info[0];
				//echo "<pre>";print_r($_POST);die;
				for($i=0;$i<$_POST['total_items'];$i++) { // insert each sale items
					$sale_service_columns	=	array('sale_id', 'description', 'account', 'amount', 'job_id', 
												  'tax','tax_record_id', 'status'
												);
					if($_POST['phone_mode'] == "1") {
						$sale_service_values	=	array($sale_id, $_POST['description_phone'][$i], $_POST['account_phone'][$i], $_POST['amount_phone'][$i], 
												  $_POST['job_phone'][$i], isset($_POST['tax_check_phone'][$i])?$_POST['tax_check_phone'][$i]:'0',$_POST['service_tax_record_id_mobile'][$i], '1'
												);
					} else {
						$sale_service_values	=	array($sale_id, $_POST['description'][$i], $_POST['account'][$i], $_POST['amount'][$i], 
												  $_POST['job'][$i], isset($_POST['tax_check'][$i])?$_POST['tax_check'][$i]:'0',$_POST['service_tax_record_id'][$i], '1'
												);
					}
					
					$sale_item	=	$sales->save("sale_service", $sale_service_columns, $sale_service_values);
				}
			}

		// Entering payment info for sale edit + create page.
			$is_payment_checked	=	$transaction_m->check_payment_exists($sale_id);
			if(isset($is_payment_checked)&&!empty($is_payment_checked)){// payment exist then, skip transaction entry.
				// do nothing 
				// skip
			} else {
				// rules: for entry in transaction table.
				// ccp is OFF + all payment method.
				// ccp is ON + all payment method except credit card.
				
				// CCP is ON + credit card + paid-today then, it will get save but the green tick will not come.
				// basically skip this process.
				if(intval($_POST['paid_today']) > 0 ){ 	// If you like anything to pay.
					if($ccp==1 && (	$_POST['payment_type'] == CREDIT_CARD )){ // payment type 2 is for credit card
						$transaction_details  = array('customer_id'						 => intval($_POST['customer']),
													  'sale_id'							 => $sale_id,
 						 	 						  'transaction_amount'		 		 => intval($_POST['paid_today']),
 						 	 						  'transaction_date'  			 	 => date('Y-m-d H:i:s'),
 													  'gateway_transaction_id'    		 => '',
 						 	 						  'gateway_authorization_code'		 => '',	
													  'payment_method'					 => $_POST['payment_method'],
 													  'gateway_transaction_status'		 => '',
 						 	 						  'gateway_transaction_short_message'=> '', 	
 						 	 						  'gateway_transaction_long_message' => ''
 						  						);
  						$new_transaction_id = $transaction_m->create('transaction', $transaction_details);
						
					} else {  // CCP is 0 + all payment-method & CCP is 1 + except credit-card payment method.
						if($_POST['payment_type'] == CREDIT_CARD){ // Save in transaction table
							$transaction_details  = array('customer_id'						 => intval($_POST['customer']),
														  'sale_id'							 => $sale_id,
	 						 	 						  'transaction_amount'		 		 => intval($_POST['paid_today']),
	 						 	 						  'transaction_date'  			 	 => date('Y-m-d H:i:s'),
	 													  'gateway_transaction_id'    		 => '',
	 						 	 						  'gateway_authorization_code'		 => '',	
														  'payment_method'					 => $_POST['payment_method'],
	 													  'gateway_transaction_status'		 => '',
	 						 	 						  'gateway_transaction_short_message'=> '', 	
	 						 	 						  'gateway_transaction_long_message' => ''
	 						  					);
	  						$new_transaction_id = $transaction_m->create('transaction', $transaction_details);
						} 
						else { // saving in transaction other table.
							$transaction_details  = array('customer_id'			 => intval($_POST['customer']),
														  'sale_id'				 => intval($sale_id),
					 	 								  'transaction_amount'	 => intval($_POST['paid_today']),
					 	 								  'transaction_date'  	 => date('Y-m-d H:i:s'),
														  'payment_method'		 => $_POST['payment_method'],
														  'check_num'			 => '',
														  'notes'				 => ''
						 	 						);
							$new_transaction_id = $transaction_m->create('transaction_other', $transaction_details);
						 }
					}	  
				} else {
					// skip record
				}
			}

			if(isset($_POST['details']) && ($_POST['details']==1)) { 
				$sale_key	=	md5(time().rand());
				$sales_data	=	array(	"sale_key" 		=> 	$sale_key,
										'updated_date'	=>	date('Y-m-d H:i:s'));
				$sale_info	=	$sales->update("sales", $sales_data, $sale_id);
				Request::instance()->redirect(SITEURL.'/sales/payment/'.$sale_key);
			} else {
				Request::instance()->redirect(SITEURL.'/sales/view/'.$sale_id);
			}
		} catch(Exception $e) { die($e->getMessage()." ".$e->getFile(). " ".$e->getLine());
			$this->template->content = 	View::factory('sales/service_sale_create')
												->set('customers', $customer_jobs->get_all_customer(1,$_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']))
												->set('customer',$_POST['customer'])
												->set('sale_number',$_POST['sale_number'])
												->set('selected_date',$_POST['selected_date'])
												->set('address1',$_POST['address1'])
												->set('address2',$_POST['address2'])
												->set('address3',$_POST['address3'])
												->set('customer_po',$_POST['customer_po'])
												->set('terms',$_POST['terms'])
												->set('payment_is_due', $_POST['payment_is_due'])
												->set('balance_due_days', $_POST['balance_due_days'])
												->set('discount_days', $_POST['discount_days'])
												->set('early_payment_discount', $_POST['early_payment_discount'])
												->set('late_payment_charge', $_POST['late_payment_charge'])
												->set('accounts', $accounts_m->get_company_account($_SESSION['country']))
												->set('job',$_POST['job'])
												->set('jobs', $jobs_m->get_all_jobs())
												->set('tax_total_amount',$_POST['tax'])
												->set('sales_person',$_POST['sales_person'])
												->set('sales_comment', $sales->get_comments())
												->set('comment',$_POST['comment'])
												->set('referal_source',$_POST['referal_source'])
												->set('referalsource', $sales->get_referrals())
												->set('payment_method', $_POST['payment_method'])
												->set('payment'	, isset($payment) ? $payment :'0')
												->set('payment_type', 	$_POST['payment_type'])
												->set('is_tax_inclusive',$_POST['is_tax_inclusive'])
												->set('paymentmethod', $sales->get_payment_methods())
												->set('shipping_method', $_POST['shipping_method'])
												->set('shippingmethod', $sales->get_shipping_methods())
												->set('subtotal',$_POST['subtotal'])
												->set('taxes', $taxes_m->get_taxes())
												->set('total_payment',$_POST['total_payment'])
												->set('paid_today',$_POST['paid_today'])
												->set('balance_due',$_POST['balance_due'])
												->set('error',$e->getMessage());
		}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	process_time_billing_sale
	 * @Description	:	Function process time billing invoice
	 * @Param		:	int
	 */
	private function process_time_billing_sale($sale_type) {
		$sales			=	new Model_Sales;
		$customer_jobs	=	new Model_Customerandjobs;
		$taxes_m 		= 	new Model_Taxes;
		$jobs_m			= 	new Model_Jobs;
		$activity_m		=	new Model_Activity;
		$transaction_m 	=	new Model_Transaction;
		$gateway_ach_m	=	new Model_Gatewayach;
		$company_m		=	new Model_Company;

		require Kohana::find_file('classes', 'library/date_format');
		$date_format	=	new Date_format;
		try {
			$sale_id =	NULL;
			$keys 			= 	array('ccp');
			$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
			if(!empty($company_data)&&$company_data['ccp']=='1'){
				$ccp = 1;
			} else {
				$ccp = 0;
			}
			$gateway_info =	$gateway_ach_m->get_merchant_personal_ach_details($_SESSION['company_id']);
			if(isset($gateway_info[0]['status'])){
				if($gateway_info[0]['status'] == '1'){
					$gateway_status=1;
				}else {
					$gateway_status=0;
				}
			} else {
				$gateway_status=0;
			}
			
			if(intval($_POST['paid_today']) > 0) {
				if($_POST['payment_type'] != '2'){
					$payment='2'; // payment non credit card
				}
				else {
					$payment='1'; // payment credit card
				}
			}
			if($_POST['customer'] == "") throw new Exception("Please select customer");
			if($_POST['sale_number'] == "") throw new Exception("Please enter sale number");
			if(!empty($_POST['sales_person'])) {
				$sales_person_arr	=	explode("--", $_POST['sales_person']);
				$sales_person_id	=	$sales_person_arr[1];
				$sales_person_name	=	$sales_person_arr[0];
			} else {
				$sales_person_id	=	0;
				$sales_person_name	=	"";
			}
			//echo "<pre>";print_r($_POST);die;
			if(isset($_POST['edit_sale'])) { // if user edits item invoice sale
				$sale_id			=	$_POST['sale_id'];
				$sales_data			=	array(	  'customer_id'			=> $_POST['customer'],
												  'sale_number'			=> $_POST['sale_number'], 
												  'selected_date'		=> $_POST['selected_date'], 
												  'sales_person_id'		=> $sales_person_id,
												  'sales_person'		=> $sales_person_name,
												  'comment'				=> $_POST['comment'], 
												  'referal_source'		=> $_POST['referal_source'], 
												  'payment_method'		=> $_POST['payment_method'], 
												  'freight_tax'			=> '0', 
												  'subtotal'			=> $_POST['subtotal'],  
												  'total_payment'		=> $_POST['total_payment'],
												  'payment'				=> isset($payment) ? $payment :'0',
												  'payment_type'		=> $_POST['payment_type'],
												  'paid_today'			=> $_POST['paid_today'],
												  'balance'				=> $_POST['balance_due'],
												  'tax_total_amount'	=> $_POST['tax_total_amount'], 
												  'terms'				=> $_POST['terms'],
												  'payment_is_due'		=> isset($_POST['payment_is_due']) ? $_POST['payment_is_due'] : "" ,
												  'balance_due_days'	=> isset($_POST['balance_due_days']) ? $_POST['balance_due_days'] : "0" ,
												  'discount_days'		=> isset($_POST['discount_days']) ? $_POST['discount_days'] : "0" ,
												  'early_payment_discount'=> isset($_POST['early_payment_discount']) ? $_POST['early_payment_discount'] : "0" ,
												  'late_payment_charge'	=> isset($_POST['late_payment_charge']) ? $_POST['late_payment_charge'] : "0" ,
												  'is_tax_inclusive'	=> $_POST['is_tax_inclusive'],
												  'updated_date'		=> date('Y-m-d H:i:s')
										    );
										    
				$sale_info	=	$sales->update("sales", $sales_data, $_POST['sale_id']);
					for($i=0;$i<$_POST['total_items'];$i++) { // update each sale item
						if(isset($_POST['date_item'][$i]) && !empty($_POST['date_item'][$i])) {
							$_POST['date_item'][$i]	=	$date_format->get_formatted_date($_POST['date_item'][$i]);
						}
					if($_POST['phone_mode'] == "1") {
						if(isset($_POST['date_item_phone'][$i]) && !empty($_POST['date_item_phone'][$i])) {
							$_POST['date_item_phone'][$i]	=	$date_format->get_formatted_date($_POST['date_item_phone'][$i]);
						}
						if(!isset($_POST['time_billing_id_phone'][$i])) {
							$sale_service_columns	=	array('sale_id', 'description', 'activity', 'date','units','rates', 'amount', 'job_id', 
														  'tax_record_id', 'tax_check', 'created_date', 'status'
														);
							$sale_service_values	=	array($sale_id, $_POST['description_phone'][$i], $_POST['activity_phone'][$i], $_POST['date_item_phone'][$i], 
														  $_POST['units_phone'][$i], $_POST['rates_phone'][$i], $_POST['amount_phone'][$i], 
														  $_POST['job_phone'][$i],$_POST['tb_tax_record_id_mobile'][$i] ,isset($_POST['tax_check_phone'][$i])?$_POST['tax_check_phone'][$i]:'0', date('Y-m-d H:i:s'), '1'
														);
							$sale_item	=	$sales->save("sale_time_billing", $sale_service_columns, $sale_service_values);
						} else {
							$sale_item_data			=	array(	'description'	=> $_POST['description_phone'][$i],
														  		'activity'		=> $_POST['activity_phone'][$i], 
														  		'date'			=> $_POST['date_item_phone'][$i],
														  		'job_id'		=> $_POST['job_phone'][$i],
														  		'tax_check'		=> isset($_POST['tax_check_phone'][$i])?$_POST['tax_check_phone'][$i]:'0',
																'tax_record_id'	=> $_POST['tb_tax_record_id_mobile'][$i],
														  		'units'			=> $_POST['units_phone'][$i],
														  		'rates'			=> $_POST['rates_phone'][$i],
														  		'amount'		=> $_POST['amount_phone'][$i],
															);
							$timebilling_id		=	$_POST['time_billing_id_phone'][$i];
							$sale_item_info		=	$sales->update("sale_time_billing", $sale_item_data, $timebilling_id);
						}
					} else {
						if(!isset($_POST['time_billing_id'][$i])) {
							$sale_service_columns	=	array(	'sale_id', 'description', 'activity', 'date','units','rates', 'amount', 'job_id', 
																'tax_check','tax_record_id', 'created_date', 'status'
															);
							
							$sale_service_values	=	array(	$sale_id, $_POST['description'][$i], $_POST['activity'][$i], $_POST['date_item'][$i], 
												  				$_POST['units'][$i], $_POST['rates'][$i], $_POST['amount'][$i], 
												  				$_POST['job'][$i], isset($_POST['tax_check'][$i])?$_POST['tax_check'][$i]:'0',$_POST['tb_tax_record_id'][$i] , date('Y-m-d H:i:s'), '1'
															);
							$sale_item	=	$sales->save("sale_time_billing", $sale_service_columns, $sale_service_values);
						} else {
							$sale_item_data		=	array(	'description'	=>	$_POST['description'][$i],
															'activity'		=>	$_POST['activity'][$i], 
															'date'			=>	$_POST['date_item'][$i],
															'job_id'		=>	$_POST['job'][$i],
															'tax_check'		=>	isset($_POST['tax_check'][$i])?$_POST['tax_check'][$i]:'0',
															'units'			=>	$_POST['units'][$i],
															'rates'			=>	$_POST['rates'][$i],
															'tax_record_id'	=> $_POST['tb_tax_record_id'][$i],
															'amount'		=>	$_POST['amount'][$i]
												);
							$timebilling_id		=	$_POST['time_billing_id'][$i];
							$sale_item_info		=	$sales->update("sale_time_billing", $sale_item_data, $timebilling_id);
						}
					}
					
				}
			} else { // if user create new item invoice sale
			
				$sale_columns	=	array( 'company_id', 'employee_id', 'admin_user', 'type', 'customer_id',
										   'sale_number', 'selected_date', 'sales_person_id', 'sales_person',
										   'comment', 'referal_source', 'payment_method', 'payment', 'payment_type',
										   'freight_tax', 'subtotal', 'total_payment','tax_total_amount', 'is_tax_inclusive', 'paid_today', 
										   'balance', 'terms', 'payment_is_due','balance_due_days','discount_days','early_payment_discount','late_payment_charge','created_date', 'updated_date',
										   'sync_status', 'status'
									);
				$sale_values	=	array( $_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user'],
										   $_POST['sale_type'], $_POST['customer'],
										   $_POST['sale_number'], $_POST['selected_date'], $sales_person_id, $sales_person_name, $_POST['comment'], $_POST['referal_source'],
										   $_POST['payment_method'], isset($payment) ? $payment :'0', $_POST['payment_type'], '0', 
										   $_POST['subtotal'], $_POST['total_payment'],$_POST['tax_total_amount'], $_POST['is_tax_inclusive'], $_POST['paid_today'], $_POST['balance_due'],
										   $_POST['terms'], isset($_POST['payment_is_due']) ? $_POST['payment_is_due'] : "", isset($_POST['balance_due_days']) ? $_POST['balance_due_days'] : "0", isset($_POST['discount_days']) ? $_POST['discount_days'] : "0", isset($_POST['early_payment_discount']) ? $_POST['early_payment_discount'] : "0", isset($_POST['late_payment_charge']) ? $_POST['late_payment_charge'] : "0",date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), '0', '1' 
									);
				
				$sale_info	=	$sales->save("sales", $sale_columns, $sale_values);
				$sale_id	=	$sale_info[0];
				for($i=0;$i<$_POST['total_items'];$i++) { // insert each sale items
					
					if(isset($_POST['date_item'][$i]) && !empty($_POST['date_item'][$i])) {
						$_POST['date_item'][$i]	=	$date_format->get_formatted_date($_POST['date_item'][$i]);
					} 
					
					$sale_service_columns	=	array('sale_id', 'description', 'activity', 'date','units','rates', 'amount', 'job_id', 
												  'tax_check','tax_record_id', 'created_date', 'status'
												);
					if($_POST['phone_mode'] == "1") {
						if(isset($_POST['date_item_phone'][$i]) && !empty($_POST['date_item_phone'][$i])) {
							$_POST['date_item_phone'][$i]	=	$date_format->get_formatted_date($_POST['date_item_phone'][$i]);
						}
						$sale_service_values	=	array($sale_id, $_POST['description_phone'][$i], $_POST['activity_phone'][$i], $_POST['date_item_phone'][$i], 
												  $_POST['units_phone'][$i], $_POST['rates_phone'][$i], $_POST['amount_phone'][$i], 
												  $_POST['job_phone'][$i], isset($_POST['tax_check_phone'][$i])?$_POST['tax_check_phone'][$i]:'0',$_POST['tb_tax_record_id_mobile'][$i], date('Y-m-d H:i:s'), '1'
												);
					} else {
						$sale_service_values	=	array($sale_id, $_POST['description'][$i], $_POST['activity'][$i], $_POST['date_item'][$i], 
												  $_POST['units'][$i], $_POST['rates'][$i], $_POST['amount'][$i], 
												  $_POST['job'][$i], isset($_POST['tax_check'][$i])?$_POST['tax_check'][$i]:'0',$_POST['tb_tax_record_id'][$i], date('Y-m-d H:i:s'), '1'
												);
					}
					
					$sale_item	=	$sales->save("sale_time_billing", $sale_service_columns, $sale_service_values);
				}
			}
			
		// Entering payment info for sale edit + create page.
			$is_payment_checked	=	$transaction_m->check_payment_exists($sale_id);
			if(isset($is_payment_checked)&&!empty($is_payment_checked)){// payment exist then, skip transaction entry.
				// do nothing 
				// skip
			} else {
				// rules: for entry in transaction table.
				// ccp is OFF + all payment method.
				// ccp is ON + all payment method except credit card.
				
				// CCP is ON + credit card + paid-today then, it will get save but the green tick will not come.
				// basically skip this process.
				if(intval($_POST['paid_today']) > 0 ){ 	// If you like anything to pay.
					if($ccp==1 && (	$_POST['payment_type'] == CREDIT_CARD )){ // payment type 2 is for credit card
						$transaction_details  = array('customer_id'						 => intval($_POST['customer']),
													  'sale_id'							 => $sale_id,
 						 	 						  'transaction_amount'		 		 => intval($_POST['paid_today']),
 						 	 						  'transaction_date'  			 	 => date('Y-m-d H:i:s'),
 													  'gateway_transaction_id'    		 => '',
 						 	 						  'gateway_authorization_code'		 => '',	
													  'payment_method'					 => $_POST['payment_method'],
 													  'gateway_transaction_status'		 => '',
 						 	 						  'gateway_transaction_short_message'=> '', 	
 						 	 						  'gateway_transaction_long_message' => ''
 						  						);
  						$new_transaction_id = $transaction_m->create('transaction', $transaction_details);
						
					} else {  // CCP is 0 + all payment-method & CCP is 1 + except credit-card payment method.
						if($_POST['payment_type'] == CREDIT_CARD){ // Save in transaction table
							$transaction_details  = array('customer_id'						 => intval($_POST['customer']),
														  'sale_id'							 => $sale_id,
	 						 	 						  'transaction_amount'		 		 => intval($_POST['paid_today']),
	 						 	 						  'transaction_date'  			 	 => date('Y-m-d H:i:s'),
	 													  'gateway_transaction_id'    		 => '',
	 						 	 						  'gateway_authorization_code'		 => '',	
														  'payment_method'					 => $_POST['payment_method'],
	 													  'gateway_transaction_status'		 => '',
	 						 	 						  'gateway_transaction_short_message'=> '', 	
	 						 	 						  'gateway_transaction_long_message' => ''
	 						  					);
	  						$new_transaction_id = $transaction_m->create('transaction', $transaction_details);
						} 
						else { // saving in transaction other table.
							$transaction_details  = array('customer_id'			 => intval($_POST['customer']),
														  'sale_id'				 => intval($sale_id),
					 	 								  'transaction_amount'	 => intval($_POST['paid_today']),
					 	 								  'transaction_date'  	 => date('Y-m-d H:i:s'),
														  'payment_method'		 => $_POST['payment_method'],
														  'check_num'			 => '',
														  'notes'				 => ''
						 	 						);
							$new_transaction_id = $transaction_m->create('transaction_other', $transaction_details);
						 }
					}	  
				} else {
					// skip record
				}
			}
			
			if(isset($_POST['details']) && ($_POST['details']==1)) { 
				$sale_key	=	md5(time().rand());
				$sales_data	=	array(	"sale_key" 		=> 	$sale_key,
										'updated_date'	=>	date('Y-m-d H:i:s'));
				$sale_info	=	$sales->update("sales", $sales_data, $sale_id);
				Request::instance()->redirect(SITEURL.'/sales/payment/'.$sale_key);
				
			} else {
			  	Request::instance()->redirect(SITEURL.'/sales/view/'.$sale_id);
			}
		} catch(Exception $e) { die($e->getMessage());
			$this->template->content = 	View::factory('sales/time_billing_sale_create')
												->set('customers', $customer_jobs->get_all_customer(1,$_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']))
												->set('customer',$_POST['customer'])
												->set('sale_number',$_POST['sale_number'])
												->set('selected_date',$_POST['selected_date'])
												->set('terms',$_POST['terms'])
												->set('payment_is_due', $_POST['payment_is_due'])
												->set('balance_due_days', $_POST['balance_due_days'])
												->set('discount_days', $_POST['discount_days'])
												->set('early_payment_discount', $_POST['early_payment_discount'])
												->set('late_payment_charge', $_POST['late_payment_charge'])
												->set('job',$_POST['job'])
												->set('jobs', $jobs_m->get_all_jobs())
												->set('tax_total_amount',$_POST['tax'])
												->set('sales_person',$_POST['sales_person'])
												->set('sales_comment', $sales->get_comments())
												->set('comment',$_POST['comment'])
												->set('referal_source',$_POST['referal_source'])
												->set('referalsource', $sales->get_referrals())
												->set('payment_method', $_POST['payment_method'])
												->set('payment'	, isset($payment) ? $payment :'0')
												->set('paymentmethod', $sales->get_payment_methods())
												->set('payment_type'. $_POST['payment_type'])
												->set('activity', '')
												->set('activities', $activity_m->get_activities($_SESSION['country']))
												->set('subtotal',$_POST['subtotal'])
												->set('taxes', $taxes_m->get_taxes())
												->set('total_payment',$_POST['total_payment'])
												->set('paid_today',$_POST['paid_today'])
												->set('balance_due',$_POST['balance_due'])
												->set('error',$e->getMessage());
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_get_customer_related_tax
	 * @Description	:	Function process time billing invoice
	 */
	public function action_get_customer_related_tax() {
		$result		=	array();
		$sales		=	new Model_Sales;
		$customer	=	$_POST['customer_id'];
		$tax_info	=	$sales->customer_related_tax($customer);
		if(empty($tax_info)) {
			$result[0]['error']	=	1;
		} else {
			$result[0]['error']		 =	0;
			$result[0]['percentage'] =	$tax_info[0]['percentage'];
			$result[0]['code']		 =	$tax_info[0]['tax_code'];
			$result[0]['description']=	$tax_info[0]['description'];
			$result[0]['id']=	$tax_info[0]['id'];
		}
		die(json_encode($result));
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_view
	 * @Description	:	Function to view sale
	 * @Param		:	int
	 */
	public function action_view($sale_id) {
		$this->template->tab	=	2;
		$sales		=	new Model_Sales;
		$sale_info	=	$sales->get_sale_details($sale_id);
		if(empty($sale_info)) Request::instance()->redirect(SITEURL.'/sales');
		switch($sale_info[0]['type']) {
			case '1':
					 $sale_type="Item Invoice";
					 $this->view_item_sale($sale_info, $sale_type);
					 break;
			case '2':
					 $sale_type="Item Order";
					 $this->view_item_sale($sale_info, $sale_type);
					 break;
			case '3':
					 $sale_type="Item Quote";
					 $this->view_item_sale($sale_info, $sale_type);
					 break;
			case '4':
					 $sale_type="Service Invoice";
					 $this->view_service_sale($sale_info, $sale_type);
					 break;
			case '5':
					 $sale_type="Service Order";
					 $this->view_service_sale($sale_info, $sale_type);
					 break;
			case '6':
					 $sale_type="Service Quote";
					 $this->view_service_sale($sale_info, $sale_type);
					 break;
			case '7':
					 $sale_type="Time Billing Invoice";
					 $this->view_time_billing_sales($sale_info, $sale_type);
					 break;
			case '8':
					 $sale_type="Time Billing Order";
					 $this->view_time_billing_sales($sale_info, $sale_type);
					 break;
			case '9':
					 $sale_type="Time Billing Quote";
					 $this->view_time_billing_sales($sale_info, $sale_type);
					 break;
		}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	view_item_sale
	 * @Description	:	Function to view item invoice sale
	 * @Param		:	int, int
	 */
	private function view_item_sale($sale, $sale_type) {
		
		require Kohana::find_file('classes', 'library/Sync');
		require Kohana::find_file('classes', 'library/date_format');
		$sales			=	new Model_Sales;
		$customertoken_m= 	new Model_Customertoken;
		$transaction_m	=	new Model_Transaction;
		$company_m 		=	new Model_Company;
		$sync_lib 		= 	new Sync;
		$gateway_ach_m	= 	new Model_Gatewayach;
			
		$keys 			= 	array('ccp');
		$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
		if(!empty($company_data)&&$company_data['ccp']=='1'){
			$ccp = 1;
		} else {
			$ccp = 0;
		}
		$format 	= 	new date_format;
		$sale_item	=	$sales->get_sale_item($sale[0]['id'], $sale[0]['company_id']);
		$prev_sale	=	$sales->get_prev_sale($sale[0]['id']);
		$next_sale	=	$sales->get_next_sale($sale[0]['id']);
		$is_payment_exist	= $transaction_m->check_payment_exists($sale[0]['id']);
		
		$company_info	=	$company_m->admin_company_details($sale[0]['company_id']);
		$payment		=	$transaction_m->get_sales_transaction_details($sale[0]['id']);
		$header 		= 	View::factory('sales/sales_header')
										->set('next_sale', $next_sale)
										->set('prev_sale', $prev_sale)
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('last_updated_sale',$this->last_created_sale())
										->set('sale_type', $sale_type)
										->set('new_sales_menu', View::factory('sales/new_sales_menu'));
		$this->template->content = 	View::factory('sales/item_sale_view')
											->set('sale', $sale)
											->set('format', $format)
											->set('payment_info', $customertoken_m->get_customer_payment_info($sale[0]['customer_id']))
											->set('sale_item', $sale_item)
											->set('ach_status',$gateway_ach_m->get_payment_status())
											->set('ccp', $ccp)
											->set('payment',$payment)
											->set('sync_status',$sale[0]['sync_status'])
											->set('saleId',$sale[0]['id'])
											->set('sale_type', $sale[0]['type'])
											->set('company_info',$company_info)
											->set('header', $header)
											->set('country', $_SESSION['country'])
											->set('is_payment_exist', $is_payment_exist);
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	view_service_sale
	 * @Description	:	Function to view service invoice sale
	 * @Param		:	int, int
	 */
	private function view_service_sale($sale, $sale_type) {
		require Kohana::find_file('classes', 'library/Sync');
		require Kohana::find_file('classes', 'library/date_format');
		$sales			=	new Model_Sales;
		$sync_lib 		= 	new Sync;
		$customertoken_m=	new Model_Customertoken;
		$transaction_m	=	new Model_Transaction;
		$gateway_ach_m	= 	new Model_Gatewayach;
		$company_m		=	new Model_Company;
		
		$keys 			= 	array('ccp');
		$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
		if(!empty($company_data)&&$company_data['ccp']=='1'){
			$ccp = 1;
		} else {
			$ccp = 0;
		}
		$format 		= 	new date_format;
		$sale_service	=	$sales->get_sale_service($sale[0]['id']);
		$prev_sale		=	$sales->get_prev_sale($sale[0]['id']);	
		$next_sale		=	$sales->get_next_sale($sale[0]['id']);
		$company_m 		=	new Model_Company;
		$company_info	=	$company_m->admin_company_details($sale[0]['company_id']);
		$payment		=	$transaction_m->get_sales_transaction_details($sale[0]['id']);
		$is_payment_exist	=	$transaction_m->check_payment_exists($sale[0]['id']);
		
		$header 	= 	View::factory('sales/sales_header')
										->set('next_sale', $next_sale)
										->set('prev_sale', $prev_sale)
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('last_updated_sale',$this->last_created_sale())
										->set('sale_type', $sale_type)
										->set('new_sales_menu', View::factory('sales/new_sales_menu'));
		$this->template->content = 	View::factory('sales/service_sale_view')
											->set('sale', $sale)
											->set('payment_info', $customertoken_m->get_customer_payment_info($sale[0]['customer_id']))
											->set('format', $format)
											->set('sale_service', $sale_service)
											->set('sync_status',$sale[0]['sync_status'])
											->set('saleId',$sale[0]['id'])
											->set('ach_status',$gateway_ach_m->get_payment_status())
											->set('ccp', $ccp)
											->set('sale_type', $sale[0]['type'])
											->set('header', $header)
											->set('company_info',$company_info)
											->set('payment',$payment)
											->set('country', $_SESSION['country'])
											->set('is_payment_exist', $is_payment_exist)
											->set('new_sales_menu', View::factory('sales/new_sales_menu'));
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	view_time_billing_sales
	 * @Description	:	Function to view time billing invoice
	 * @Param		:	int, int
	 */
	private function view_time_billing_sales($sale, $sale_type) {
		require Kohana::find_file('classes', 'library/Sync');
		require Kohana::find_file('classes', 'library/date_format');
		$sales			=	new Model_Sales;
		$sync_lib 		= 	new Sync;
		$customertoken_m= 	new Model_Customertoken;
		$transaction_m	=	new Model_Transaction;
		$format 		= 	new date_format;
		$gateway_ach_m	= 	new Model_Gatewayach;
		$company_m 		=	new Model_Company;
		
		$keys 			= 	array('ccp');
		$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
		if(!empty($company_data)&&$company_data['ccp']=='1'){
			$ccp = 1;
		} else {
			$ccp = 0;
		}
		
		$sale_billing	=	$sales->get_sale_time_billing($sale[0]['id']);
		$prev_sale		=	$sales->get_prev_sale($sale[0]['id']);	
		$next_sale		=	$sales->get_next_sale($sale[0]['id']);
		$company_info	=	$company_m->admin_company_details($sale[0]['company_id']);
		$payment		=	$transaction_m->get_sales_transaction_details($sale[0]['id']);
		$is_payment_exist	=	$transaction_m->check_payment_exists($sale[0]['id']);
		$header 	= 	View::factory('sales/sales_header')
										->set('next_sale', $next_sale)
										->set('prev_sale', $prev_sale)
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('last_updated_sale',$this->last_created_sale())
										->set('sale_type', $sale_type)
										->set('new_sales_menu', View::factory('sales/new_sales_menu'));
		$this->template->content = 	View::factory('sales/time_billing_sale_view')
											->set('sale', $sale)
											->set('payment_info', $customertoken_m->get_customer_payment_info($sale[0]['customer_id']))
											->set('format', $format)
											->set('sale_billing', $sale_billing)
											->set('ach_status',$gateway_ach_m->get_payment_status())
											->set('ccp', $ccp)
											->set('sync_status',$sale[0]['sync_status'])
											->set('company_info',$company_info)
											->set('saleId',$sale[0]['id'])
											->set('payment',$payment)
											->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
											->set('sale_type', $sale[0]['type'])
											->set('is_payment_exist', $is_payment_exist)
											->set('header', $header);
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_edit
	 * @Description	:	Function to edit sale
	 * @Param		:	int
	 */
	public function action_edit($sale_id) {
		$this->template->tab	=	2;
		$sales			=	new Model_Sales;
		$sale_info		=	$sales->get_sale_details($sale_id);
		if(isset($_GET['e']) && $_GET['e']=='1'){
			$sales_error_msg	= 'Payment Unsuccessful: Invalid Payment Response';
		} else {
			$sales_error_msg	= FALSE;
		}
		if(empty($sale_info)) Request::instance()->redirect(SITEURL.'/sales');
		switch($sale_info[0]['type']) {
			case '1':$sale_type="Item Invoice";
					 $this->edit_item_sale($sale_info,$sale_type, $sales_error_msg);
					 break;
			case '2':$sale_type="Item Order";
					 $this->edit_item_sale($sale_info,$sale_type, $sales_error_msg);
					 break;
			case '3':$sale_type="Item Quote";
			         $this->edit_item_sale($sale_info,$sale_type, $sales_error_msg);
					 break;
			case '4':$sale_type="Service Invoice";
					 $this->edit_service_sale($sale_info,$sale_type, $sales_error_msg);
					 break;
			case '5':$sale_type="Service Order";
					 $this->edit_service_sale($sale_info,$sale_type, $sales_error_msg);
					 break;
			case '6':$sale_type="Service Quote";
			         $this->edit_service_sale($sale_info,$sale_type, $sales_error_msg);
					 break;
			case '7':$sale_type="Timebilling Invoice";
					 $this->edit_timebilling_sale($sale_info,$sale_type, $sales_error_msg);
					 break;
			case '8':$sale_type="Timebilling Order";
					 $this->edit_timebilling_sale($sale_info,$sale_type, $sales_error_msg);
					 break;
			case '9':$sale_type="Timebilling Quote";
			         $this->edit_timebilling_sale($sale_info,$sale_type, $sales_error_msg);
					 break;
		}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	edit_item_sale
	 * @Description	:	Function to edit sale
	 * @Param		:	int,int
	 */
	private function edit_item_sale($sale, $sale_type, $sales_error_msg) { 
		require Kohana::find_file('classes', 'library/Sync');
		require Kohana::find_file('classes', 'library/date_format');
		$sales			=	new Model_Sales;
		$customer_jobs	=	new Model_Customerandjobs;
		$taxes_m		= 	new Model_Taxes;
		$items_m 		=	new Model_Items;
		$jobs_m			=	new Model_Jobs;
		$accounts_m		=	new Model_Accounts;
		$activity_m		= 	new Model_Activity;
		$customertoken_m=	new Model_Customertoken;
		$sync_lib 		= 	new Sync;
		$employee_m		=	new Model_Employee;
		$transaction_m 	=	new Model_Transaction;
		$gateway_ach_m	= 	new Model_Gatewayach;
		$company_m 		=	new Model_Company;
		
		$keys 			= 	array('ccp');
		$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
		if(!empty($company_data)&&$company_data['ccp']=='1'){
			$ccp = 1;
		} else {
			$ccp = 0;
		}		
		$format 		= 	new date_format; 
		$sale_item		=	$sales->get_sale_item($sale[0]['id'], $sale[0]['company_id']);
		$already_paid	=	$transaction_m->get_transaction_details_for_sale($sale[0]['id'],$sale[0]['customer_id']);
		
		$is_payment_exist	=	$transaction_m->check_payment_exists($sale[0]['id']);
		
		try{	
			$sales_edit_header = View::factory('sales/sales_edit_header')
			                     ->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
			                     ->set('title',$sale_type)
		                         ->set('sale_type', $sale[0]['type']);
	
	         $popups_sales     = View::factory('sales/sales_popups')
	                            ->set('items', $items_m->get_items($_SESSION['country']))
	                            ->set('sales_comment', $sales->get_comments())
	                            ->set('paymentmethod', $sales->get_payment_methods())
	                            ->set('shippingmethod', $sales->get_shipping_methods())
	                            ->set('referalsource', $sales->get_referrals())
	                            ->set('jobs', $jobs_m->get_all_jobs())
								->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
								->set('employees', $employee_m->get_all_employee($_SESSION['company_id']))
	                            ->set('customers', $customer_jobs->get_all_customer(1,$_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']))
	                            ->set('payment_is_due',$sale[0]['payment_is_due'])
								->set('balance_due_days',$sale[0]['balance_due_days'])
								->set('discount_days',$sale[0]['discount_days'])
								->set('early_payment_discount',$sale[0]['early_payment_discount'])
								->set('late_payment_charge',$sale[0]['late_payment_charge']);
	
			$this->template->content = 	View::factory('sales/item_sale_edit')
												->set('sale', $sale)
												->set('sale_item', $sale_item)
												->set('format', $format)
												->set('payment_info', $customertoken_m->get_customer_payment_info($sale[0]['customer_id']))
												->set('taxes', $taxes_m->get_taxes())
												->set('ach_status',$gateway_ach_m->get_payment_status())
												->set('ccp', $ccp)
												->set('sale_type', $sale[0]['type'])
												->set('sync_status',$sale[0]['sync_status'])
												->set('already_paid',$already_paid)
												->set('saleId',$sale[0]['id'])
												->set('sales_edit_header',$sales_edit_header)
												->set('popups_sales',$popups_sales)
												->set('country',isset($_SESSION['country'])?$_SESSION['country']:0)
												->set('is_payment_exist', $is_payment_exist)
												->set('new_sales_menu', View::factory('sales/new_sales_menu')
												->set('error_msg', $sales_error_msg));
											
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
	/**
	 * @Access		:	Private
	 * @Function	:	edit_service_sale
	 * @Description	:	Function to view item invoice sale
	 * @Param		:	int,int
	 */
	private function edit_service_sale($sale,$sale_type, $sales_error_msg) {
		require Kohana::find_file('classes', 'library/Sync');		
		require Kohana::find_file('classes', 'library/date_format');
		$sales			=	new Model_Sales;
		$customer_jobs	=	new Model_Customerandjobs;
		$taxes_m 		= 	new Model_Taxes; 
		$items_m		=	new Model_Items;
		$jobs_m			= 	new Model_Jobs;
		$accounts_m		=	new Model_Accounts;
		$activity_m		= 	new Model_Activity;
		$customertoken_m= 	new Model_Customertoken;
		$sync_lib 		= 	new Sync;
		$employee_m		=	new Model_Employee;
		$transaction_m	=	new Model_Transaction;
		$gateway_ach_m	= 	new Model_Gatewayach;
		$company_m 		=	new Model_Company;
		
		$keys 			= 	array('ccp');
		$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
		if(!empty($company_data)&&$company_data['ccp']=='1'){
			$ccp = 1;
		} else {
			$ccp = 0;
		}
		$sale_item		=	$sales->get_sale_service($sale[0]['id']);
		$already_paid	=	$transaction_m->get_transaction_details_for_sale($sale[0]['id'],$sale[0]['customer_id']);
		$format 		= 	new date_format;
		$is_payment_exist 	=	$transaction_m->check_payment_exists($sale[0]['id']); 
		
		try{	
		$sales_edit_header = View::factory('sales/sales_edit_header')
		                     ->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
		                     ->set('title',$sale_type)
	                         ->set('sale_type', $sale[0]['type']);
	    $popups_sales      = View::factory('sales/sales_popups')
		                     ->set('sales_comment', $sales->get_comments())
                             ->set('paymentmethod', $sales->get_payment_methods()) 
                             ->set('shippingmethod', $sales->get_shipping_methods())
                             ->set('referalsource', $sales->get_referrals())
                             ->set('jobs', $jobs_m->get_all_jobs())
                             ->set('accounts', $accounts_m->get_company_account($_SESSION['country']))
							 ->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
							 ->set('employees', $employee_m->get_all_employee($_SESSION['company_id']))
                             ->set('customers', $customer_jobs->get_all_customer(1,$_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']))
                             ->set('payment_is_due',$sale[0]['payment_is_due'])
							 ->set('balance_due_days',$sale[0]['balance_due_days'])
							 ->set('discount_days',$sale[0]['discount_days'])
							 ->set('early_payment_discount',$sale[0]['early_payment_discount'])
							 ->set('late_payment_charge',$sale[0]['late_payment_charge']);

		$this->template->content = 	View::factory('sales/service_sale_edit')
											->set('sale', $sale)
											->set('sale_service', $sale_item)
											->set('format', $format)
											->set('taxes', $taxes_m->get_taxes())
											->set('sale_type', $sale[0]['type'])
											->set('ach_status',$gateway_ach_m->get_payment_status())
											->set('ccp', $ccp)
											->set('payment_info', $customertoken_m->get_customer_payment_info($sale[0]['customer_id']))
											->set('sync_status',$sale[0]['sync_status'])
											->set('saleId',$sale[0]['id'])
											->set('already_paid',$already_paid)
											->set('country',isset($_SESSION['country'])?$_SESSION['country']:0)
											->set('sales_edit_header',$sales_edit_header)
											->set('popups_sales',$popups_sales)
											->set('is_payment_exist', $is_payment_exist)
											->set('new_sales_menu', View::factory('sales/new_sales_menu')
											->set('error_msg', $sales_error_msg));
		} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	edit_timebilling_sale
	 * @Description	:	Function to view time billing invoice sale
	 * @Param		:	int,int
	 */
	private function edit_timebilling_sale($sale,$sale_type, $sales_error_msg) {
		require Kohana::find_file('classes', 'library/Sync');
		require Kohana::find_file('classes', 'library/date_format');
		$sales			=	new Model_Sales;		
		$customer_jobs	=	new Model_Customerandjobs;
		$taxes_m		= 	new Model_Taxes; 
		$items_m		=	new Model_Items;
		$jobs_m			= 	new Model_Jobs;
		$accounts_m		= 	new Model_Accounts;
		$activity_m		= 	new Model_Activity;
		$customertoken_m=	new Model_Customertoken;
		$employee_m		=	new Model_Employee;
		$transaction_m	= 	new Model_Transaction;
		$gateway_ach_m	= 	new Model_Gatewayach;
		$company_m 		=	new Model_Company;
		
		$keys 			= 	array('ccp');
		$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
		if(!empty($company_data)&&$company_data['ccp']=='1'){
			$ccp = 1;
		} else {
			$ccp = 0;
		}
		$sale_billing	=	$sales->get_sale_time_billing($sale[0]['id']);
		$already_paid	=	$transaction_m->get_transaction_details_for_sale($sale[0]['id'],$sale[0]['customer_id']);
		$sync_lib 		= 	new Sync;
		$format 		= 	new date_format;
		$is_payment_exist	=	$transaction_m->check_payment_exists($sale[0]['id']);
		
		try{	
		$sales_edit_header = View::factory('sales/sales_edit_header')
		                     ->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
		                     ->set('title',$sale_type)
	                         ->set('sale_type', $sale[0]['type']);
        $popups_sales      = View::factory('sales/sales_popups')
		                     ->set('sales_comment', $sales->get_comments())
                             ->set('paymentmethod', $sales->get_payment_methods())
                             ->set('referalsource', $sales->get_referrals())
                             ->set('jobs', $jobs_m->get_all_jobs())
                             ->set('activities', $activity_m->get_activities($_SESSION['country']))
							 ->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
							 ->set('employees', $employee_m->get_all_employee($_SESSION['company_id']))
                             ->set('customers', $customer_jobs->get_all_customer(1,$_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']))
                             ->set('payment_is_due',$sale[0]['payment_is_due'])
							 ->set('balance_due_days',$sale[0]['balance_due_days'])
							 ->set('discount_days',$sale[0]['discount_days'])
							 ->set('early_payment_discount',$sale[0]['early_payment_discount'])
							 ->set('late_payment_charge',$sale[0]['late_payment_charge']);

		$this->template->content = 	View::factory('sales/time_billing_sale_edit')
											->set('sale', $sale)
											->set('sale_billing', $sale_billing)
											->set('format', $format)
											->set('payment_info', $customertoken_m->get_customer_payment_info($sale[0]['customer_id']))
											->set('taxes', $taxes_m->get_taxes())
											->set('sync_status',$sale[0]['sync_status'])
											->set('saleId',$sale[0]['id'])
											->set('ach_status',$gateway_ach_m->get_payment_status())
											->set('ccp', $ccp)
											->set('already_paid',$already_paid)
											->set('sale_type', $sale[0]['type'])
											->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
											->set('sales_edit_header',$sales_edit_header)
											->set('popups_sales',$popups_sales)
											->set('is_payment_exist', $is_payment_exist)
											->set('new_sales_menu', View::factory('sales/new_sales_menu')
											->set('error_msg', $sales_error_msg));
	} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_delete
	 * @Description	:	Function to delete sales
	 * @Param		:	int,int
	 */
	public function action_delete($sale_id, $delete_multiple=0) {
		$sales		=	new Model_Sales;
		$true_user	=	$sales->check_current_user($sale_id);
		$sale_info	=	$sales->get_sale_details($sale_id);
		if(empty($sale_info)) Request::instance()->redirect(SITEURL.'/sales');
		if($true_user) {
			$satus			=	$sales->delete("sales","id",$sale_id);
			switch($sale_info[0]['type']) {
				case '1':
				case '2':
				case '3':
							$satus	=	$sales->delete("sale_item","sale_id",$sale_id);
							break;
				case '4':
				case '5':
				case '6':
							$satus	=	$sales->delete("sale_service","sale_id",$sale_id);
							break;
				case '7':
				case '8':
				case '9':
							$satus	=	$sales->delete("sale_time_billing","sale_id",$sale_id);
							break;
			}
		}
		if($delete_multiple) {
			return true;
		} else {
			Request::instance()->redirect(SITEURL.'/sales');
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_tobesynced
	 * @Description	:	Function to get list of sales to be synced
	 */
	public function action_tobesynced() {

		$this->template->tab	=	2;
		$final_array			=	array();
		require Kohana::find_file('classes', 'library/Sync');
		$sync_lib 	= 	new Sync;
		$sales		=	new Model_Sales;
		$customers	=	new Model_Customerandjobs;
		$to_besynced_list			=	$sales->get_to_be_synced_list();
		$to_besynced_customers		=	$customers->get_customer_to_sync();
		$to_besynced_jobs			=	$customers->get_jobs_to_sync();
		$final_array				=	array_merge($to_besynced_list,$to_besynced_customers,$to_besynced_jobs);
		usort($final_array,array($this,"compare"));
		//echo "<pre>";print_r($final_array);die;
		$initial_array = array_slice($final_array, 0, 9);
		$header 	= 	View::factory('sales/sales_list_header')
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('tab', '4');
										
		$this->template->content = 	View::factory('sales/to_be_synced')
											->set('header', $header)
											->set('count_slips',count($final_array))
											->set('per_slip', isset($_POST['view_per_page'])?$_POST['view_per_page']:10)
											->set('page', isset($_POST['page'])?$_POST['page']:0)
											->set('sales', $initial_array);
	}
	
	/**
	 * @Access		:	private
	 * @Function	:	compare
	 * @Description	:	Function to sort the list by date desc
	 */
	private function compare($a, $b) {
    	if((strtotime($a["updated_date"]) - strtotime($b["updated_date"])) < 0 ){
   			return 1;
    	} else if((strtotime($a["updated_date"]) - strtotime($b["updated_date"])) > 0 ){
   			return -1;
		} else {
   			return 0;
   		}
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_sync
	 * @Description	:	Function to sync sale
	 * @Param		:	int
	 */ 
	public function action_sync($sale_id) {
		$obj_sales	=	new Model_Sales;
		$sale_info	=	$obj_sales->get_sale_full_details($sale_id);
		if(empty($sale_info)) Request::instance()->redirect(SITEURL.'/sales');
		switch($sale_info[0]['type']) {
			case '1':
			case '2':
			case '3':
					$sales			=	$this->item_sale_json($sale_info[0]);
					$sync_status	=	$this->sync_sale($sales, $sale_info[0]['id'], "Sales");
					break;
			case '4':
			case '5':
			case '6':
					$sales			=	$this->service_sale_json($sale_info[0]);
					$sync_status	=	$this->sync_sale($sales, $sale_info[0]['id'], "Sales");
					break;
			case '7':
			case '8':
			case '9':
					$sales			=	$this->time_billing_sale_json($sale_info[0]);
					$sync_status	=	$this->sync_sale($sales, $sale_info[0]['id'], "Sales");
					break;
		}
		
		if($sale_info[0]['payment'] != '' && $sale_info[0]['payment'] != '0') {
			$payment		=	$this->make_payment_json($sale_info[0]['id'], $sale_info[0]['payment']);
			$sync_status	=	$this->sync_sale($payment, $sale_info[0]['id'], "Payment");
		}
		
		$sales_data		=	array(	'sync_status'	=> 	'1',
									'updated_date'	=>	date('Y-m-d H:i:s'));
		$sale_update	=	$obj_sales->update("sales", $sales_data, $sale_info[0]['id']);
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_syncall
	 * @Description	:	Function to do sync all
	 */
	public function action_syncall() {
		try {
			$user_model		=	new Model_User;
			$handshake	=	$user_model->check_handshake_updates();
			$obj_sales	=	new Model_Sales;
			$sales		=	array();
			$payment	=	array();
			$sale_info	=	$obj_sales->get_all_sale_to_sync();
			if(empty($sale_info)) 
				Request::instance()->redirect(SITEURL.'/sales/tobesynced');
			foreach($sale_info as $sale) {
				$sales_data	=	array();
				switch($sale['type']) {
					case '1':
					case '2':
					case '3':
							$sync_status	=	$this->sync_sale($this->item_sale_json($sale), $sale['id'], "Sales");
							break;
					case '4':
					case '5':
					case '6':
							$sync_status	=	$this->sync_sale($this->service_sale_json($sale), $sale['id'], "Sales");
							break;
					case '7':
					case '8':
					case '9':
							$sync_status	=	$this->sync_sale($this->time_billing_sale_json($sale), $sale['id'], "Sales");
							break;
				}
				if($sale['payment'] != '' && $sale['payment'] != '0') {
					$sync_status	=	$this->sync_sale($this->make_payment_json($sale['id'], $sale['payment']), $sale['id'], "Payment");
				}
				$sales_data		=	array(	'sync_status'	=> 	'1',
											'updated_date'	=>	date('Y-m-d H:i:s'));
				$sale_update	=	$obj_sales->update("sales", $sales_data, $sale['id']);
			}
			//$sync_status	=	$this->sync_sale($sales, $sale['id'], "Sales");
		
			Request::instance()->redirect(SITEURL.'/sales/tobesynced');
		} 
		catch(Exception $e) {
			die($e->getMessage());
		}
	}
	
	/**
	 * @Access		:	private
	 * @Function	:	round_price
	 * @Description	:	Function to round the price value
	 * @Param		:	double
	 */
	private function round_price($price) {
		$rounded_price = sprintf("%0.2f", $price);
		return $rounded_price; 
	}
	
	/**
	 * @Access		:	private
	 * @Function	:	item_sale_json
	 * @Description	:	Function to generate item sale in json format before sync
	 * @Params		:	array
	 */
	private function item_sale_json($sale_info) {
		$sales		=	new Model_Sales;
		$sale_item	=	$sales->get_sale_item($sale_info['id'],$_SESSION['company_id']);
		$item		=	array();
		foreach($sale_item as $si) {
			$total	=	$si['total'];
			if($si['apply_tax'] == "1") { // need to change this because we have change the purpose of TAXes.
				$tax	= 	$this->round_price(($total)*($si['percentage']/100));
			} else {
				$tax	=	"0.00";
			}
			
			$item[]	=	array(	"SalesTaxAmount"	=> "$tax",
				                "Description"		=> $si['item_name'],
								"AccountNumber"		=> $sale_info['account'],
				                "ItemNumber"		=> $si['item_number'],
				                "Price"				=> $si['price'],
				                "Quantity"			=> $si['quantity'],
								"TaxCodeRecordID"	=> $si['tax_record_id'], // need to check as field is changed
                				"TaxCode"			=> $si['tax_code'],
								"TaxPercentage"		=> $si['percentage']
						);
		}
			
		$invoice_date	=	explode("-", $sale_info['selected_date']);
		if($sale_info['type'] == "1") $sale_status	=	"I";
		else if($sale_info['type'] == "2") $sale_status	=	"O";
		else $sale_status	=	"Q";
		
		if($sale_info['freight_tax'] == "1") {
			$freight_amount	=	$this->round_price(($sale_info['freight'])*($sale_info['percentage']/100));
		} else {
			$freight_amount	=	0;
		}
		$freight_amount		=	$this->round_price($freight_amount+$sale_info['freight']);
		
		if(strstr($sale_info['sales_person'], ' ')){
			$sales_person		=	explode(" ",$sale_info['sales_person']);
			$sales_person_fname	=	$sales_person[0];
  			$sales_person_lname	=	$sales_person[1];
		} else {
			$sales_person_fname	=	'';
			$sales_person_lname	=	$sale_info['sales_person'];
		}
		
		$arr_sale[0]	    =	array(
								"CustomerCompanyOrLastName"		=>	$sale_info['company_or_lastname'],
								"Comment"						=>	$sale_info['comment'],
								"ShipToAddressLineOne"			=>	$sale_info['address1'],
								"ShipToAddressLineTwo"			=>	$sale_info['address2'],
								"ShipToAddressLineThree"		=>	$sale_info['address3'],
								"ReferralSource"				=>	$sale_info['referal_source'],
								"ShipVia"						=>	$sale_info['shipping_method'],
								"SaleStatus"					=>  $sale_status,
								"SalespersonFirstName"			=>	$sales_person_fname,
								"SalespersonLastName"			=>	$sales_person_lname,
								"SubTotal"						=>  $sale_info['subtotal'],
								//"AmountPaid"					=>  $sale_info['paid_today'],
								"TotalAmount"					=>	$sale_info['total_payment'],
								"Lines"							=>	$item,
								"CustomerRecordID"				=>  $sale_info['record_id'],
								"FreightTaxRecordId"			=>	$sale_info['tax_record_id'],
								"FreightTaxCode"				=>	$sale_info['tax_code'],
								"FreightAmount"					=> 	$freight_amount,
								//"PaymentMethod"					=>  $sale_info['payment_method'],
								//"payment_type"					=>	$sale_info['payment_type'],
								"BalanceDue"					=>	$sale_info['balance'],
								"BalanceDueDays"				=>  "0",
								"PaymentIsDue"					=>  "0",
								"DiscountDays"					=>  "0",
								"LatePaymentChargePercent"		=>  "0.00",
								"EarlyPaymentDiscountPercent"	=>  "0.00",
								"InvoiceDate"					=>	array(	"Day" => $invoice_date[2],
																			"Month" => $invoice_date[1],
																			"Year" => $invoice_date[0]
																	),
								"TypeOfSale"					=>  "Item",
								"InvoiceNumber"					=>  "",
								"JournalMemo"					=>  $sale_info['sale_number'],
								"ThirdPartyUniqueID"			=>	$sale_info['id']				// Need to add record id in sales table and do it as per customers and jobs
								//"NameOnCard"					=>  $sale_info['name_on_card'],
								//"CreditOrDebitCardNumber"		=>  $sale_info['last_digits_on_card'],
								//"AVSPostalCode"					=>  $sale_info['zip'],
								//"AuthorizationNumber"			=>  "",
								//"ACHTraceID"					=>  "",
								//"ACHAuthorizationStatus"		=>  "",
								//"ACHPaymentToken"				=>  "",
								//"ThirdPartyUniqueID"			=>	$sale_info['id'],
								//"CreditOrDebitCardExpiration"	=>  $sale_info['expiration_date'],
							);	
			/*if($sale_info['record_id'] == "0") {
				$arr_sale[0]['ThirdPartyUniqueID']	=	$sale_info['customer_id'];
			} else {
				$arr_sale[0]['SaleRecordID']	=	$sale_info['record_id'];
			}*/
		return $arr_sale;
	}
	
	
	/**
	 * @Access		:	private
	 * @Function	:	service_sale_json
	 * @Description	:	Function to generate service sale json format
	 * @Params		:	array
	 */
	private function service_sale_json($sale_info) {
		$sales			=	new Model_Sales;
		$sale_service	=	$sales->get_sale_service($sale_info['id']);
		//echo "<pre>";print_r($sale_service);die;
		$service		=	array();
		
		foreach($sale_service as $ss) {
			$total	=	$ss['amount'];
			if($ss['tax'] == "1") { // need to change because we have change the functionality of the varia
				$tax	= 	$this->round_price(($total)*($sale_info['percentage']/100));
			} else {
				$tax	=	"0.00";
			}
			$service[]	=	array(	"SalesTaxAmount" => "$tax",
					                "Description" => $ss['description'],
									"AccountNumber" => $ss['account_number'],
					                "Amount" => $ss['amount'],
									"TaxCodeRecordID" => $sale_info['tax_record_id'],// need to check as field is changed
	                				"TaxCode"	=>  $sale_info['tax_code']
							);
	    }
		//try {
		$invoice_date	=	explode("-", $sale_info['selected_date']);
		if($sale_info['type'] == "4") $sale_status	=	"I";
		else if($sale_info['type'] == "5") $sale_status	=	"O";
		else $sale_status	=	"Q";
		if($sale_info['freight_tax'] == "1") {
			$freight_amount	=	$this->round_price(($sale_info['freight'])*($sale_info['percentage']/100));
		} else {
			$freight_amount	=	0;
		}
		$freight_amount		=	$this->round_price($freight_amount+$sale_info['freight']);
		if(strstr($sale_info['sales_person'], ' ')){
			$sales_person		=	explode(" ",$sale_info['sales_person']);
			$sales_person_fname	=	$sales_person[0];
  			$sales_person_lname	=	$sales_person[1];
		} else {
			$sales_person_fname	=	'';
			$sales_person_lname	=	$sale_info['sales_person'];
		}
		$arr_sale[0]	=	array(
								"CustomerCompanyOrLastName"		=>	$sale_info['company_or_lastname'],
								"Comment"						=>	$sale_info['comment'],
								"ShipToAddressLineOne"			=>	$sale_info['address1'],
								"ShipToAddressLineTwo"			=>	$sale_info['address2'],
								"ShipToAddressLineThree"		=>	$sale_info['address3'],
								"ReferralSource"				=>	$sale_info['referal_source'],
								"ShipVia"						=>	$sale_info['shipping_method'],
								"SaleStatus"					=>  $sale_status,
								//"AmountPaid"					=>  $sale_info['paid_today'],
								"TotalAmount"					=>	$sale_info['total_payment'],
								"Lines"							=>	$service,
								"SalespersonFirstName"			=>	$sales_person_fname,
								"SalespersonLastName"			=>	$sales_person_lname,
								"CustomerRecordID"				=>  $sale_info['record_id'],
								"FreightAmount"					=> 	"$freight_amount",
								//"PaymentMethod"					=>  $sale_info['payment_method'],
								//"payment_type"					=>	$sale_info['payment_type'],
								"BalanceDueDays"				=>  "0",
								"PaymentIsDue"					=>  "0",
								"DiscountDays"					=>  "0",
								"LatePaymentChargePercent"		=>  "0.00",
								"EarlyPaymentDiscountPercent"	=>  "0.00",
								"InvoiceDate"					=>	array(	"Day" => $invoice_date[2],
																			"Month" => $invoice_date[1],
																			"Year" => $invoice_date[0]
																	),
								"TypeOfSale"					=>  "Service",
								"InvoiceNumber"					=>  "",
								"JournalMemo"					=>  $sale_info['sale_number'],
								"ThirdPartyUniqueID"			=>	$sale_info['id']			// Need to add record id in sales table and do it as per customers and jobs
								//"NameOnCard"					=>  $sale_info['name_on_card'],
								//"CreditOrDebitCardNumber"		=>  $sale_info['last_digits_on_card'],
								//"AVSPostalCode"					=>  $sale_info['zip'],
								//"AuthorizationNumber"			=>  "",
								//"ACHTraceID"					=>  "",
								//"ACHAuthorizationStatus"		=>  "",
								//"ACHPaymentToken"				=>  "",
								//"ThirdPartyUniqueID"			=>	$sale_info['id'],
								//"CreditOrDebitCardExpiration"	=>  $sale_info['expiration_date'],
							);	
			/*if($sale_info['record_id'] == "0") {
				$arr_sale[0]['ThirdPartyUniqueID']	=	$sale_info['customer_id'];
			} else {
				$arr_sale[0]['SaleRecordID']	=	$sale_info['record_id'];
			}//} catch(Exception $e) {die($e->getMessage());}*/
		return $arr_sale;
	}
	
	/**
	 * @Access		:	private
	 * @Function	:	time_billing_sale_json
	 * @Description	:	Function to generate time billing sale in json format before sync
	 * @Params		:	array
	 */
	private function time_billing_sale_json($sale_info) {
		$sales			=	new Model_Sales;
		$sale_billing	=	$sales->get_sale_time_billing($sale_info['id']);
		//try {
		$item			=	array();
		$invoice_date	=	explode("-", $sale_info['selected_date']);
		if(strstr($sale_info['sales_person'], ' ')){
			$sales_person		=	explode(" ",$sale_info['sales_person']);
			$sales_person_fname	=	$sales_person[0];
  			$sales_person_lname	=	$sales_person[1];
		} else {
			$sales_person_fname	=	'';
			$sales_person_lname	=	$sale_info['sales_person'];
		}
		foreach($sale_billing as $sb) {
			$total	=	$sb['amount'];
			//$tax	= 	$this->round_price(($total)*($sale_info['percentage']/100));
			if($sb['tax_check'] == "1") {
				$tax	= 	$this->round_price(($total)*($sale_info['percentage']/100));
			} else {
				$tax	=	"0.00";
			}
			$item[]	=	array(	"SalesTaxAmount" => "$tax",
					            "Notes" 		 => $sb['description'],
								"ActivityID"	 => $sb['activity_id'],
					            "Amount"		 => $sb['amount'],
								"TaxCodeRecordID" => $sale_info['tax_record_id'], // need to check as field is changed
	                			"TaxCode"		 =>  $sale_info['tax_code'],
								"Hours"			 => $sb['units'],
						        "Rate"			 => $sb['rates'],
						        "DetailDate"	=>	array(	"Day" => $invoice_date[2],
															"Month" => $invoice_date[1],
															"Year" => $invoice_date[0]
													)
						);
		}
		if($sale_info['type'] == "7") $sale_status	=	"I";
		else if($sale_info['type'] == "8") $sale_status	=	"O";
		else $sale_status	=	"Q";
		$arr_sale[0]	=	array(
								"CustomerCompanyOrLastName"		=>	$sale_info['company_or_lastname'],
								"Comment"						=>	$sale_info['comment'],
								"ShipToAddressLineOne"			=>	$sale_info['address1'],
								"ShipToAddressLineTwo"			=>	$sale_info['address2'],
								"ShipToAddressLineThree"		=>	$sale_info['address3'],
								"ReferralSource"				=>	$sale_info['referal_source'],
								"ShipVia"						=>	$sale_info['shipping_method'],
								"SaleStatus"					=>  $sale_status,
								//"AmountPaid"					=>  $sale_info['paid_today'],
								"TotalAmount"					=>	$sale_info['total_payment'],
								"Lines"							=>	$item,
								"CustomerRecordID"				=>  $sale_info['record_id'],
								"SalespersonFirstName"			=>	$sales_person_fname,
								"SalespersonLastName"			=>	$sales_person_lname,
								//"PaymentMethod"					=>  $sale_info['payment_method'],
								//"payment_type"					=>	$sale_info['payment_type'],
								"BalanceDueDays"				=>  "0",
								"PaymentIsDue"					=>  "0",
								"DiscountDays"					=>  "0",
								"LatePaymentChargePercent"		=>  "0.00",
								"EarlyPaymentDiscountPercent"	=>  "0.00",
								"InvoiceDate"					=>	array(	"Day" => $invoice_date[2],
																			"Month" => $invoice_date[1],
																			"Year" => $invoice_date[0]
																	),
								"TypeOfSale"					=>  "TimeBilling",
								"InvoiceNumber"					=>  "",
								"JournalMemo"					=>  $sale_info['sale_number'],
								"ThirdPartyUniqueID"			=>	$sale_info['id']				// Need to add record id in sales table and do it as per customers and jobs
								//"NameOnCard"					=>  $sale_info['name_on_card'],
								//"CreditOrDebitCardNumber"		=>  $sale_info['last_digits_on_card'],
								//"AVSPostalCode"					=>  $sale_info['zip'],
								//"AuthorizationNumber"			=>  "",
								//"ACHTraceID"					=>  "",
								//"ACHAuthorizationStatus"		=>  "",
								//"ACHPaymentToken"				=>  "",
								//"ThirdPartyUniqueID"			=>	$sale_info['id'],
								//"CreditOrDebitCardExpiration"	=>  $sale_info['expiration_date'],
							);	
			/*if($sale_info['record_id'] == "0") {
				$arr_sale[0]['ThirdPartyUniqueID']	=	$sale_info['customer_id'];
			} else {
				$arr_sale[0]['SaleRecordID']	=	$sale_info['record_id'];
			}*/
	//	} catch(Exception $e) {die($e->getMessage());}
		return $arr_sale;
	}
	
	/**
	 * @Access		:	private
	 * @Function	:	make_payment_json
	 * @Description	:	Function to make payment json
	 * @Params		:	int, int
	 */
	private function make_payment_json($sale_id, $payment_mode) {
		$sales			=	new Model_Sales;
		$transaction_m	=	new Model_Transaction;
		$arr_payment	=	array();
		/**Payment ID#**/
		$payment		=	$sales->get_set_payment_id($_SESSION['company_id'] , $sale_id);
		/**Payment ID#**/
		
		if($payment_mode == "1") { //paid by credit card
			$transaction	=	$transaction_m->get_card_transaction($sale_id);
			if(isset($transaction[0])){
				$receipt_date	=	explode("-", date("Y-m-d", strtotime($transaction[0]['transaction_date'])));
				$arr_payment	=	array();
				$arr_payment[0]	=	array(
										"CustomerRecordID"				=>	$transaction[0]['record_id'],
										"PaymentIDNumber"				=>	$payment['id'],
										"PaymentMemo"					=>	"Payment: ".$transaction[0]['company_or_lastname'],
										"NameOnCard"					=>	$transaction[0]['name_on_card'],
										"CreditOrDebitCardExpiration"	=>	$transaction[0]['expiration_date'],
										"CustomerCompanyOrLastName"		=>  $transaction[0]['company_or_lastname'],
										"PaymentMethod"					=>  $transaction[0]['payment_method'],
										"CreditOrDebitCardNumber"		=>	$transaction[0]['last_digits_on_card'],
										"ReceiptDate"					=>	array(
																				"Day" 	=> $receipt_date[2],
																				"Month" => $receipt_date[1],
																				"Year"	=> $receipt_date[0]
																			),
										"ThirdPartyUniqueID"			=>  $transaction[0]['id'],
										"DepositAccount"				=>  $transaction[0]['deposit_account'],
										"AVSPostalCode"					=>  $transaction[0]['zip'],
										"Lines"							=>  array(
																				"ThirdPartyInvoiceID"	=>	$sale_id,
																				"AmountApplied"			=>	"$".$transaction[0]['transaction_amount']
																			)
				);
				if(!empty($transaction[0]['gateway_transaction_status'])){
					$arr_payment[0]["ACHAuthorizationStatus"]	=	$transaction[0]['gateway_transaction_status'];
				}
				if(!empty($transaction[0]['AuthorizationNumber'])){
					$arr_payment[0]["AuthorizationNumber"]		=	$transaction[0]['gateway_authorization_code'];
				}
				if(!empty($transaction[0]['ACHTraceID'])){
					$arr_payment[0]["ACHTraceID"]				=  	$transaction[0]['gateway_transaction_id'];
				}
				
								
			}
			else{
				// Do Nothing
			} 
		} else if($payment_mode == "2"){  // Payment by other mode.
			$transaction	=	$transaction_m->get_other_transaction($sale_id);
			
			if(isset($transaction[0])){
				$receipt_date	=	explode("-", date("Y-m-d", strtotime($transaction[0]['transaction_date'])));
				$arr_payment	=	array();
				$arr_payment[0]	=	array(
										"CustomerRecordID"			=>	$transaction[0]['record_id'],
										"PaymentMemo"				=>	"Payment: ".$transaction[0]['company_or_lastname'],
										"PaymentIDNumber"			=>	$payment['id'],
										"PaymentMethod"				=>  $transaction[0]['payment_method'],
										"CustomerCompanyOrLastName"	=>  $transaction[0]['company_or_lastname'],
										"ReceiptDate"				=>	array(
																			"Day" 	=> $receipt_date[2],
																			"Month" => $receipt_date[1],
																			"Year"	=> $receipt_date[0]
																		),
										"ThirdPartyUniqueID"		=>  $transaction[0]['id'],
										"DepositAccount"			=>  $transaction[0]['deposit_account'],
										"Lines"						=>  array(
																			"ThirdPartyInvoiceID"	=>	$sale_id,
																			"AmountApplied"			=>	"$".$transaction[0]['transaction_amount']
																		)
									);
				if($transaction[0]['payment_method']=='Check'){
					$arr_payment[0]["CheckNumber"]	=  $transaction[0]['check_num'];
				}
			}
		}
		return $arr_payment;
	}
	
	/**
	 * @Access		:	private
	 * @Function	:	sync_sale
	 * @Description	:	Function to sync the sales to dropbox
	 * @Params		:	int, int, string
	 */
	private function sync_sale($sale, $sale_id, $file_name = "Sales") {
		$dropbox	=	new Model_dropbox(); 
		$sales		=	new Model_Sales;
		if($file_name	==	"Payment"){
			$json_file_name			=	$file_name.$sale_id.".json";
			$local_folder_path		=	CACHE_DROPBOXFOLDERPATH.$json_file_name; // create json file in local
			$dropbox_folder_path	=	$this->dropbox_payment_folder.$json_file_name; // create dropbox file
		} else {
			$json_file_name			=	$file_name.$sale_id.".json";
			$local_folder_path		=	CACHE_DROPBOXFOLDERPATH.$json_file_name; // create json file in local
			$dropbox_folder_path	=	$this->dropbox_sales_folder.$json_file_name; // create dropbox file
		}
		$json_content			=	json_encode($sale);
		// create local file and write json content
		$fp	=	fopen($local_folder_path,'a+');
		fwrite($fp, $json_content);
		fclose($fp);
		try {
			$upload_status	=	$dropbox->upload_file($local_folder_path, $dropbox_folder_path); // Upload file to dropbox
		} catch(Exception $e) {
			$folder_status	=	$dropbox->create_folder($dropbox_folder_path); // Create third party folder in dropbox
			$upload_status	=	$dropbox->upload_file($local_folder_path, $dropbox_folder_path); // Upload file to dropbox
		}
		unlink($local_folder_path);
		return true;
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_payment
	 * @Description	:	Function to load sale payment view
	 * @Params		:	int
	 */
	public function action_payment($sale_key) {
		require Kohana::find_file('classes', 'library/Sync');
		$sales			=	new Model_Sales;
		$customer_jobs	=	new Model_Customerandjobs;
		$sync_lib		=	new Sync;
		$customertoken_m= 	new Model_Customertoken;
		$gateway_m		=	new Model_Gatewayach;
		$company_m 		=	new Model_Company;
		
		$this->template->tab	=	2;
		$sale_info		=	$sales->get_sale_by_key($sale_key);
		
		$gateway_info 	=	$gateway_m->get_merchant_personal_ach_details($_SESSION['company_id']);
		$gateway_info 	=	isset($gateway_info[0]) ? $gateway_info[0] : '';
		
		$keys 			= 	array('ccp');
		$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
		if(!empty($company_data)&&$company_data['ccp']=='1'){
			$ccp = 1;
		} else {
			$ccp = 0;
		}
		
		if(isset($gateway_info['status']) && $gateway_info['status']	==  '1'){
			$gateway_info['status']	=  1;
		} else {
			$gateway_info['status']	=  0;
		}
		
		if(empty($sale_info)){ 
			Request::instance()->redirect(SITEURL.'/sales');
		}
		
		// deleting a transaction which is created from sale save.
		// QUick fix : start 
		$transaction_m 	=	new Model_Transaction;
		if(isset($sale_info[0]['sync_status']) && $sale_info[0]['sync_status'] == '0' ){
			$transaction_m->delete_sales_transaction('transaction', $sale_info[0]['id']);
			$transaction_m->delete_sales_transaction('transaction_other', $sale_info[0]['id']);
			
			// reset sales payment data.
			$sales_data					=	array(	'paid_today'	=>	'0',
													'balance'		=>	$sale_info[0]['total_payment'],
													'updated_date'	=>	date('Y-m-d H:i:s'));
			$sale_update				=	$sales->update("sales", $sales_data, $sale_info[0]['id']);
		} else{
			// Return proper error message.
		}
		// QUick fix : start
		
		$header 				= 	View::factory('sales/sales_list_header')
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('payment_form', '1')
										->set('sale_id', $sale_info[0]['id'])
										->set('title', 'Payment Details');

		$this->template->content = 	View::factory('sales/sale_payment_confirm')
										->set('header', 				$header)
										->set('payment_info', 			$customertoken_m->get_customer_payment_info($sale_info[0]['customer_id']))
										->set('payment_method_details',	$sales->get_payment_method_details($sale_info[0]['customer_id'],$sale_info[0]['id']))
										->set('sale_id',				$sale_info[0]['id'])
										->set('paymentmethod', 			$sales->get_payment_methods())
										->set('sale_key',				$sale_key)
										->set('paid_today',				$sale_info[0]['paid_today'])
										->set('customer', 				$customer_jobs->get_customer_name($sale_info[0]['customer_id']))
										->set('gateway_info', 			$gateway_info)
										->set('ccp', 					$ccp);
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_addpayment
	 * @Description	:	Function to add new payment method
	 */
	public function action_addpayment() {
		try {
			require_once Kohana::find_file('classes', 'library/Sync');
			$transaction_m 	= 	new Model_Transaction;
			$sales			=	new Model_Sales;
			$sync_lib		=	new Sync;
			$gateway_ach_m	=	new Model_Gatewayach;
			$company_m 		=	new Model_Company;
			
			$keys 			= 	array('ccp');
			$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
			if(!empty($company_data)&&$company_data['ccp']=='1'){
				$ccp = 1;
			} else {
				$ccp = 0;
			}
			
			$sale_key		=	$_POST['sale_key'];
			$sale_info		=	$sales->get_sale_by_key($sale_key);
			$gateway_details=	$gateway_ach_m->get_merchant_personal_ach_details($_SESSION['company_id']);
			if(isset($gateway_details[0])&&!empty($gateway_details[0])){
				$gateway_details	=	$gateway_details[0];
			}
			$payment_method = 	!empty($_POST['payment_method'])? $_POST['payment_method']: '';
			
			$sale_id 		= 	$sale_info[0]['id'];
		 	$customer_id	= 	$sale_info[0]['customer_id'];
			$amount			= 	!empty($_POST['paid_today'])? $_POST['paid_today']: 0;
			$check_num		= 	!empty($_POST['check_number'])? $_POST['check_number']: 0;
			switch($_POST['card_type']){
				case 0	: $note_str = 'cash-notes';break;
				case 1	: $note_str = 'check-notes';break;
				case 3	: $note_str = 'debit-notes';break;
				case 4	: $note_str = 'other-notes';break;
				default	: $note_str = 'other-notes';break;
			}
			//$note_str 		= 	!empty($_POST['card_type'])? $_POST['card_type'].'-notes': '';
			
			$card_name 		=	!empty($_POST['name_on_card'])? $_POST['name_on_card'] : '';
			$last4digits	=	!empty($_POST['last_digits'])? $_POST['last_digits'] : '';
			$expiry_date	=	!empty($_POST['expiry_date'])? $_POST['expiry_date'] : '';

			if(!isset($_POST['card_type']) || $_POST['card_type'] == CREDIT_CARD){ // for default or for card_type = credit
				$this->template->tab		=	2;
				$sales_data					=	array(	'paid_today'	=>	$_POST['paid_today'],
														'payment_method'	=>	$_POST['payment_method'],
														'payment_type'	=>	$_POST['card_type'],
														'updated_date'	=>	date('Y-m-d H:i:s'));
				$sale_update				=	$sales->update("sales", $sales_data, $_POST['sale_id']);

				if(isset($_POST['authorize_flag'])) { // authorized button is clicked.
					$payment_status	=	$this->authorize_payment($sale_key, $payment_method);

					$header 		= 	View::factory('sales/sales_list_header')
														->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
														->set('payment_form', '1')
														->set('sale_id', $sale_info[0]['id'])
														->set('title', 'Payment Details');
					
					$this->template->content = 	View::factory('sales/payment_form')
															->set('header', $header)
															->set('sale_details', $sales->get_sale_details($_POST['sale_id']))
															->set('paid_today', $amount)
															->set('card_type', $_POST['card_type'])
															->set('ccp', $ccp)
															->set('notes', '')
															->set('check_num', '')
															->set('payment_method', $payment_method)
															->set('name_on_card', $payment_status['name_on_card'])
															->set('trace_id', $payment_status['trace_id'])
															->set('exp_date', $payment_status['exp_date'])
															->set('last_4_digits', $payment_status['last_4_digits'])
															->set('auth_code', $payment_status['auth_code'])
															->set('payment_receipt', 1);
					} else { //here
					
						if($ccp==0){ // Saving the customer and sales info for CC and CCP off 
						
						// Save Customer Credit card info.
						$transaction_details  = array(	'customer_id'						=> $customer_id,
														'sale_id'							=> $sale_id,
	 						 	 						'transaction_amount'		 		=> $amount,
	 						 	 						'transaction_date'  			 	=> date('Y-m-d H:i:s'),
	 													'gateway_transaction_id'    		=> '',
	 						 	 						'gateway_authorization_code'		=> '',	
														'payment_method'					=> $_POST['payment_method'],
	 													'gateway_transaction_status'		=> '',
	 						 	 						'gateway_transaction_short_message'	=> '', 	
	 						 	 						'gateway_transaction_long_message' 	=> '',
	 						 	 						'card_name'			=>	$card_name,
														'last4digits'		=>	$last4digits,
														'expiry_date'		=>	$expiry_date,
														'payment_method'	=>	$payment_method
	 						  						);
						$new_transaction_id 	= $transaction_m->create('transaction', $transaction_details);
						
						$header 		= 	View::factory('sales/sales_list_header')
														->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
														->set('payment_form', '1')
														->set('sale_id', $sale_info[0]['id'])
														->set('title', 'Payment Details');

						$this->template->content = 	View::factory('sales/payment_form')
															->set('header', $header)
															->set('sale_details', $sales->get_sale_details($_POST['sale_id']))
															->set('paid_today', $amount)
															->set('card_type', $_POST['card_type'])
															->set('ccp', $ccp)
															->set('notes', '')
															->set('check_num', '')
															->set('payment_method', $payment_method)
															->set('name_on_card', $card_name)
															->set('trace_id', '')
															->set('exp_date', $expiry_date)
															->set('last_4_digits', $last4digits)
															->set('auth_code', '')
															->set('payment_receipt', 1);
						} else {
							require Kohana::find_file('classes', 'library/Ach');
							$customer_token_model	=	new Model_Customertoken;
							$token_columns			=	array('customer_id', 'employee_id','created_date');
							$sale_info				=	$sales->get_sale_by_key($sale_key);
							$gateway_ach_model		= 	new Model_Gatewayach;
							$merchant_details 		= 	$gateway_ach_model->get_merchant_personal_ach_details($_SESSION['company_id']);
							$merchant_details		=	json_decode(json_encode($merchant_details));
							if(isset($_POST['save_card'])) {
								$token_columns		=	array('customer_id','company_id','employee_id','created_date');
								$token_values		=	array($sale_info[0]['customer_id'],$_SESSION['company_id'], $_SESSION['employee_id'], date("Y-m-d H:i:s"));
								$customer_token_model->save("customer_token", $token_columns, $token_values, $sale_info[0]['customer_id']);
							}
						
							if(!empty($merchant_details)) {
								if(	$merchant_details[0]->ach_gateway_id != "" || 
										$merchant_details[0]->ach_gateway_password  != "" || 
											$merchant_details[0]->apli_login_id != "" || 
												$merchant_details[0]->transaction_key != "") {
									$this->ach 		= 	new Ach($merchant_details[0], true);
									$save_card_flag	=	isset($_POST['save_card'])? $_POST['save_card'] : '0';
									$payment_url	=	$this->ach->create_payment_token(0, $sale_info[0]['id'], $sale_info[0]['customer_id'], $save_card_flag);
									$header 		= 	View::factory('sales/sales_list_header')
																->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
																->set('payment_form', '1')
																->set('sale_id', $sale_info[0]['id'])
																->set('title', 'Payment Details');
									$this->template->content = 	View::factory('sales/payment_form')
																	->set('header', $header)
																	->set('sale_details', $sales->get_sale_details($_POST['sale_id']))
																	->set('paid_today', $_POST['paid_today'])
																	->set('payment_url', $payment_url)
																	->set('card_type', $_POST['card_type']);
							} else {
								$this->action_payment($sale_key);
							}
						}
					}
				}
			} else if ($_POST['card_type'] == DEBIT_CARD || $_POST['card_type'] == CASH || $_POST['card_type'] == CHECK || $_POST['card_type'] == OTHER )
			{
				if(isset($sale_info)){ 
					
					//$customer_token	=	$customer_token_model->get_customer_tokens($sale_info[0]['customer_id']);
					if($note_str != ''){
						$notes			= 	!empty($_POST[$note_str])? $_POST[$note_str]: '';
					} else {
						$notes			= 	'';
					}
				
					// save the transaction information and redirect to payment receipt.
					$transaction_details  = array('customer_id'			 => intval($customer_id),
												  'sale_id'				 => intval($sale_id),
			 	 								  'transaction_amount'	 => intval($amount),
			 	 								  'transaction_date'  	 => date('Y-m-d H:i:s'),
												  'payment_method'		 => $payment_method,
												
												  'check_num'			 => $check_num,
												  'notes'				 => $notes
				 	 						);
					$new_transaction_id = $transaction_m->create('transaction_other', $transaction_details);
					
					// update sales data.
					$total_amount	=	$sale_info[0]['total_payment'];
					$total_paid		=	$transaction_m->get_transaction_other_details_for_sale($sale_id,$customer_id);
					$balance		=	($total_amount-$total_paid);
					$sales_data		=	array(	'payment'			=>	'2', 
												'balance' 			=> 	$balance,
												'payment_method'	=>	$_POST['payment_method'],
												'payment_type'		=>	$_POST['card_type'],
												'paid_today' 		=> 	$total_paid,
												'updated_date'		=>	date('Y-m-d H:i:s'));
					$sale_update	=	$sales->update("sales", $sales_data, $sale_id);
					
					// view payment receipt
					// This view call id for Debit card, cash, other, check
					
					$header 		= 	View::factory('sales/sales_list_header')
														->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
														->set('payment_form', '1')
														->set('sale_id', $sale_info[0]['id'])
														->set('title', 'Payment Details');
					$this->template->content = 	View::factory('sales/payment_form')
															->set('header', $header)
															->set('sale_details', $sales->get_sale_details($_POST['sale_id']))
															->set('paid_today', $amount)
															->set('card_type', $_POST['card_type'])
															->set('ccp', $ccp)
															->set('notes', $notes)
															->set('check_num', $check_num)
															->set('payment_method', $payment_method)
															->set('name_on_card', '')
															->set('trace_id', '')
															->set('exp_date', '')
															->set('last_4_digits', '')
															->set('auth_code', '')
															->set('payment_receipt', 1);
				} else {
					// sale is not exist.
					// for now do nothing.
				}
			}
		} catch(Exception $e) {Request::instance()->redirect(SITEURL.'/sales/edit/'.$_POST['sale_id']);}
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	authorize_payment
	 * @Description	:	Function to authorize and pay
	 */
	private function authorize_payment($sale_key,$payment_method){
		try {
			require Kohana::find_file('classes', 'library/Ach');
			$sales					=	new Model_Sales;
	 		$customer_token_model	=	new Model_Customertoken;
	 		$transaction_model		=	new Model_Transaction;
	 		$customer_jobs			=	new Model_Customerandjobs;
			$sale_info		=	$sales->get_sale_by_key($sale_key);
			$customer_token	=	$customer_token_model->get_customer_tokens($sale_info[0]['customer_id'],$_SESSION['company_id']);
			$sale_id 		= 	$sale_info[0]['id'];
		 	$customer_id	= 	$sale_info[0]['customer_id'];
		 	$gateway_ach_model	= new Model_Gatewayach;
			$merchant_details 	= $gateway_ach_model->get_merchant_personal_ach_details($_SESSION['company_id']);
			$merchant_details	= json_decode(json_encode($merchant_details));
			$sale_info			= $sales->get_sale_details($sale_id);
			$amount				= $sale_info[0]['paid_today'];
			$payment_token		= $customer_token[0]['payment_token'];
			//take random numbers to make consumer_order_id unique for each transaction
	   		$consumer_order_id 	= rand();
	   		
			if(!empty($merchant_details)) {
				if($merchant_details[0]->ach_gateway_id != "" || $merchant_details[0]->ach_gateway_password  != "" || $merchant_details[0]->apli_login_id != "" || $merchant_details[0]->transaction_key != "") {
					$this->ach 			= new Ach($merchant_details[0], true);
					$output_transaction = "pg_merchant_id=".$merchant_details[0]->ach_gateway_id."&pg_password=".$merchant_details[0]->ach_gateway_password."&pg_transaction_type=10&pg_total_amount=".$amount."&pg_client_id=0&pg_payment_method_id=".$payment_token."&ecom_consumerorderid=".$consumer_order_id."&endofdata&";
					$data_log 			= array();
				 	$data_log['request_code'] 	= $output_transaction;
				 	$data_log['customer_id'] 	= $customer_id;
				 	$data_log['sale_id'] 		= $sale_id;
				 	$data_log['domain_name'] 	= $_SERVER['SERVER_NAME'];
				 	$transaction_log_id 		= $transaction_model->insert_tranasction_log('transaction_log', $data_log);
				 	$this->ach->setParameter('output_data',$output_transaction);
				 	//make a process
					$this->ach->process();
			 	  	$this->ach->setParameter('output_data',$output_transaction);
			 	 	$gateway_response_type		  =   $this->ach->get_response_type();
				 	$gateway_response_description =   $this->ach->get_response_description();
				 	$gateway_response_code 		  =   $this->ach->get_response_code();
					$gateway_transaction_num 	  =   $this->ach->get_transaction_number();
					$gateway_authorization_code   =   $this->ach->get_authorization_code();
	   					
					if($gateway_response_type == 'A' && $gateway_response_code == 'A01') {
						$transaction_details  = array('customer_id'						 => $customer_id,
													  'sale_id'							 => $sale_id,
				 	 								  'transaction_amount'		 		 => $amount,
				 	 								  'transaction_date'  			 	 => date('Y-m-d H:i:s'),
													  'gateway_transaction_id'    		 => $gateway_transaction_num,
				 	 								  'gateway_authorization_code'		 => $gateway_authorization_code,
													  'payment_method'					 => $payment_method,
													  'card_name'						 => isset($sale_info[0]['name_on_card']) ? $sale_info[0]['name_on_card'] : '',
													  'last4digits'						 => isset($sale_info[0]['last_digits_on_card']) ? $sale_info[0]['last_digits_on_card'] : '',
													  'expiry_date'						 => isset($sale_info[0]['expiration_date']) ? $sale_info[0]['expiration_date'] : '',
													  'gateway_transaction_status'		 => $gateway_response_type,
				 	 								  'gateway_transaction_short_message'=> $gateway_response_code, 	
				 	 								  'gateway_transaction_long_message' => $gateway_response_description
						 	 					);
						$new_transaction_id = $transaction_model->create('transaction', $transaction_details);
						$success		=	true;

					} else if($gateway_response_type == 'E' || $gateway_response_type == 'D') { //for PAYMENT-METHOD ID INVALID
						$transaction_details  = array('customer_id'						 => $customer_id,
													  'sale_id'							 => $sale_id,
	    										 	  'transaction_amount'		 		 => $amount,
			 	 								      'transaction_date'  			 	 => date('Y-m-d H:i:s'),
													  'gateway_transaction_id'    		 => $gateway_transaction_num,
				 	 								  'gateway_authorization_code'		 => $gateway_authorization_code,
													  'payment_method'					 => $payment_method,
												      'card_name'						 => isset($sale_info[0]['name_on_card']) ? $sale_info[0]['name_on_card'] : '',
													  'last4digits'						 => isset($sale_info[0]['last_digits_on_card']) ? $sale_info[0]['last_digits_on_card'] : '',
													  'expiry_date'						 => isset($sale_info[0]['expiration_date']) ? $sale_info[0]['expiration_date'] : '',
													  'gateway_transaction_status'		 => $gateway_response_type,
				 	 								  'gateway_transaction_short_message'=> $gateway_response_code, 	
				 	 								  'gateway_transaction_long_message' => $gateway_response_description
											       );
						$new_transaction_id = $transaction_model->create('transaction', $transaction_details);
						$success	=	false;
					}
						
					$total_amount	=	$sale_info[0]['total_payment'];
					$total_paid		=	$transaction_model->get_transaction_details_for_sale($sale_id,$customer_id);
					$balance		=	($total_amount-$total_paid);
					$sales_data		=	array(	'payment'		=>	'1', 
												'balance' 		=> 	$balance,
												'paid_today' 	=> 	$total_paid,
												'updated_date'	=>	date('Y-m-d H:i:s'));
					$sale_update	=	$sales->update("sales", $sales_data, $sale_id);
					//update transaction log table
					$data_log_update = array();
					$transaction_log_response = $amount.'-'.$gateway_transaction_num.'-'.$gateway_authorization_code.'-'.$gateway_response_type.'-'.$gateway_response_code.'-'.$gateway_response_description;
					$data_log_update['response_data']  = $transaction_log_response;
					$data_log_update['transaction_id'] = $new_transaction_id;
					$data_log_update['created_date']   = date("Y-m-d H:i:s");
					$transaction_model->update_transaction_log('transaction_log', $data_log_update, $transaction_log_id);
					
					$return['name_on_card']	= $sale_info[0]['name_on_card'];
					$return['trace_id']		= $gateway_transaction_num;
					$return['exp_date']		= $sale_info[0]['expiration_date'];
					$return['last_4_digits']= $sale_info[0]['last_digits_on_card'];
					$return['auth_code']	= $gateway_response_code;
					
					return $return;
				}
			}			 
		} 
		catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_deletesales
	 * @Description	:	Function to delete selected sales from list page
	 */
	public function action_deletesales() {
		if(empty($_POST['sale_id'])) Request::instance()->redirect(SITEURL.'/sales');
		$sale_id_count	=	count($_POST['sale_id']);
		if($sale_id_count == 0)	Request::instance()->redirect(SITEURL.'/sales');
		foreach($_POST['sale_id'] as $sale_id) {
			$delete_status	=	$this->action_delete($sale_id, 1);
		}
		Request::instance()->redirect(SITEURL.'/sales');
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_success
	 * @Description	: 	After success redirecting to index
	 */
	public function action_success() {
		$this->action_index(1);
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_error
	 * @Description	: 	Error message redirecting to sales list
	 */
	public function action_error() {
		$this->action_index(2);
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_paybycash
	 * @Description	: 	Payment details saving through cash
	 */
	public function action_paybycash() {
		$sales			=	new Model_Sales;
		$total_amount	=	$_POST['total_amount'];
		$change			=	($_POST['change'] == "")?0:$_POST['change'];
		$name			=	$_POST['name'];
		$note			=	$_POST['note'];
		$paid_amount	=	doubleval($total_amount)+doubleval($change);
		$coulmn			=	array("sale_id", "customer_id", "employee_id", "total_amount", "change", "name", "note", "created_date");
		$data			=	array($_POST['sale_id'], $_POST['customer_id'], $_SESSION['employee_id'], $total_amount, $change, $name, $note, date("Y-m-d H:i:s"));
		$order			=	$sales->save('cash_order', $coulmn, $data);
		$sale_info		= 	$sales->get_sale_details($_POST['sale_id']);
		$balance_amount	=	(doubleval($sale_info[0]['total_payment'])-$paid_amount);
		$data			=	array(	"payment" => "2",
									"paid_today" => $paid_amount,
									"updated_date"	=> date("Y-m-d H:i:s"),
									"balance"	=>	$balance_amount
							);
		$order			=	$sales->update('sales', $data, $_POST['sale_id']);
		Request::instance()->redirect(SITEURL.'/sales/success');
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_sort
	 * @Description	: 	sorting the sales list based on priority
	 * @Params		:	int, int
	 */
	public function action_sort($field=0, $order=0) {
		require Kohana::find_file('classes', 'library/Sync');
		$sync_lib 		= 	new Sync;
		$sales			=	new Model_Sales;
		$this->template->tab	=	2;
		switch($field) {
			case 1: $field_val	=	"s.created_date";
					break;
			case 2: $field_val	=	"st.type";
					break;
			case 3: $field_val	=	"s.sale_number";
					break;
			case 4: $field_val	=	"customer_name";
					break;
			case 5: $field_val	=	"s.total_payment";
					break;
			default: $field_val	=	"s.created_date";
					 break;
		}
		$order_method		=	($order == 1) ? "DESC":"ASC";
		$sales_full_list	=	$sales->get_customer_sales_by_order(0, $field_val, $order_method);
		$sales_list			=	$sales->get_customer_sales_by_order(1, $field_val, $order_method);
		$sale_count			=	count($sales_full_list);
		$header 	= 	View::factory('sales/sales_list_header')
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('tab', '1');
		$this->template->content = 	View::factory('sales/list')
											->set('sales', $sales_list)
											->set('header', $header)
											->set('count_slips', $sale_count)
											->set('per_slip', isset($_POST['view_per_page'])?$_POST['view_per_page']:10)
											->set('page', isset($_POST['page'])?$_POST['page']:0)
											->set('sort_field', $field)
											->set('order', $order)
											->set('search', isset($_POST['search'])?$_POST['search']:"")
											->set('error', 0)
											->set('success', 0)
											->set('new_sales_menu', View::factory('sales/new_sales_menu'));
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_filter
	 * @Description	: 	Function to filter the result
	 * @Params		:	int
	 */
	public function action_filter($filter1) {
		require Kohana::find_file('classes', 'library/Sync');
		$sync_lib 			= 	new Sync;
		$sales				=	new Model_Sales;
		$this->template->tab=	2;
	    switch($filter1){
	        case '147'	: $filter = "'1','4','7'";break;
	        case '258'  : $filter = "'2','5','8'";break;
	        case '369'  : $filter = "'3','6','9'";break;
	        case '0' 	: $filter = "'1','2','3','4','5','6','7','8','9'";break;
	    }
		$sales_list			=	$sales->get_customer_sales_filter(1, $filter);
		$sales_full_list	=	$sales->get_customer_sales_filter(0, $filter);
		$sale_count			=	count($sales_full_list);
		//echo $sale_count;die;
		$header 			= 	View::factory('sales/sales_list_header')
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('tab', '1');
		$this->template->content = 	View::factory('sales/list')
											->set('sales', $sales_list)
											->set('header', $header)
											->set('count_slips', $sale_count)
											->set('per_slip', isset($_POST['view_per_page'])?$_POST['view_per_page']:10)
											->set('page', isset($_POST['page'])?$_POST['page']:0)
											->set('sort_field', '1')
											->set('order', '0')
											->set('filter', $filter1)
											->set('search', "")
											->set('error', 0)
											->set('success', 0)
											->set('last_updated_sale',$this->last_created_sale())
											->set('new_sales_menu', View::factory('sales/new_sales_menu'))
											->set('new_sales_filter_menu', View::factory('sales/new_sales_filter_menu'));
	}
	
	// Function to view sales payment details
	// Showing payment list or other payment-processed receipt.
	public function action_details($sale_id){
		$this->template->tab	=	2;
		try {
			require Kohana::find_file('classes', 'library/Sync');
			$sync_lib 	= 	new Sync;
			$sales		=	new Model_Sales;
			$payment	=	array();
			$sale_info	=	$sales->get_sale_details($sale_id);
			$payment_flag	=	true;
			$transaction_m	=	new Model_Transaction;
			
			if(empty($sale_info)) {
				Request::instance()->redirect(SITEURL.'/sales');
			}
			
			if($sale_info[0]['payment'] == "1") { // card payment
				$payment	=	$transaction_m->get_sales_transaction_details($sale_id);
			} elseif($sale_info[0]['payment'] == "2") {
				$payment	=	$transaction_m->get_other_transaction($sale_id);
			}
			
			$header 	= 	View::factory('sales/sales_list_header')
													->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
													->set('payment_form', '1')
													->set('sale_id', $sale_id)
													->set('view', '1')
													->set('title', 'Payment Details');
			$this->template->content = 	View::factory('sales/details')
													->set('header', $header)
													->set('payment_details', $payment)
													->set('payment_mode', $sale_info[0]['payment']);
		} catch(Exception $e) {
			die("ff".$e->getMessage());
		}
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_ajax_pagination
	 * @Description	: 	AJAX pagination
	 */
	public function action_ajax_pagination() { 
		try {	//echo '<pre>';print_r($_POST);die;
				$sales			=	new Model_Sales;
				$page			=	$_POST['page'];
				$rows_per_page	=	$_POST['rows_per_page'];
				$sort_field		=	isset($_POST['sort_field'])?$_POST['sort_field']:1;
				$order			=	isset($_POST['order'])?$_POST['order']:1;
				$start			=	(($page*$rows_per_page)-$rows_per_page);
				$end			=	$rows_per_page;
				$filter			=	isset($_POST['sale_type'])?$_POST['sale_type']:0;
				$search_sale    =   isset($_POST['search_sale'])?$_POST['search_sale']:'000';
				//die($filter);
				switch($filter){
			        case '147'	: $filter = "'1','4','7'";break;
			        case '258'  : $filter = "'2','5','8'";break;
			        case '369'  : $filter = "'3','6','9'";break;
			        case '0' 	: $filter = "'1','2','3','4','5','6','7','8','9'";break;
			    }
				if(isset($_POST['sort_field'])) {
					switch($sort_field) {
						case 1: $field_val	=	"s.created_date";
								break;
						case 2: $field_val	=	"st.type";
								break;
						case 3: $field_val	=	"s.sale_number";
								break;
						case 4: $field_val	=	"customer_name";
								break;
						case 5: $field_val	=	"s.total_payment";
								break;
						default: $field_val	=	"s.created_date";
								 break;
					}
					$order_method		=	($order == 1) ? "DESC":"ASC";
				}
				if($search_sale =='000'){//&& !empty($_SESSION['search_sale'])){
					$sales_result	=	$sales->paginated_sales($start, $end, $field_val, $order_method,$filter);
					unset($_SESSION['search_sale']);
				}
				else {
				    $sales_result		=	$sales->get_sales_search_result($search_sale, 2, $field_val, $order_method);
					$sales_full_list	=	$sales->get_sales_search_result($search_sale, 0, $field_val, $order_method);
					$sale_count			=	count($sales_full_list);
				}
				$paginated_view = 	View::factory('sales/page_view')
													->set('sales', $sales_result)
													->set('sort_field', $sort_field)
													->set('search_value',isset($_POST['search_sale'])?$_POST['search_sale']:'')
													->set('order', $order);
			die($paginated_view); 
		} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	last_created_sale
	 * @Description	: 	to get the last created sale details
	 */
	public function last_created_sale(){
		$sales		=	new Model_Sales;
		try{
			$get_last_sale	=	$sales->get_last_updated_sale();
			if(count($get_last_sale)> 0){
				return $get_last_sale[0]['sale_id'];
			}
			else{
				return 0;
			}
		} catch(Exception $e){
			die ($e->getMessage());
		}
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_payment_details_check
	 * @Description	: 	to get whether the payment details of the owner has been entered or not
	 */
	public function action_payment_details_check()
	{
		$sales			=	new Model_Sales;
		$gateway_ach_m	= 	new Model_Gatewayach;
		
		$result	=	array();
		//	$number	=	$_POST['number'];
		try{
			$payment_status	=	$gateway_ach_m->get_payment_status();
			if($payment_status) {
				$result[0]['success']	=	1;
			} else {
				$result[0]['success']	=	0;
			}
		} catch(Exception $e) {
			die($e->getMessage());	
		}
		echo json_encode($result);die;
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_update_items_row
	 * @Description	: 	Function to Update the sales 
	 * @Params		:	int, int
	 */
	public function action_update_items_row($saleId)
	{
		$sales		=	new Model_Sales;
		$updated	=	$sales->update_by_id_edit($saleId,$_POST['subtotal'],$_POST['total_payment'],$_POST['balance']);
		echo $updated;die;
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_delete_items_row_edit
	 * @Description	: 	Function to delete the sales item
	 * @Params		:	int, int
	 */
	
	public function action_delete_items_row_edit($delete_item_sale_id , $saleId)
	{
		$sales			=	new Model_Sales;
	    $deleted_item	=   $sales->delete_item_by_id_new($delete_item_sale_id, $saleId, $_SESSION['country'], $_SESSION['company_id']);
	    echo json_encode($deleted_item);die;
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_delete_service_row_edit
	 * @Description	: 	Function to delete the sales service
	 * @Params		:	int, int
	 */
    public function action_delete_service_row_edit($delete_service_sale_id , $saleId)
	{
		$sales			=	new Model_Sales;
		$deleted_item	=   $sales->delete_service_by_id_new($delete_service_sale_id, $saleId, $_SESSION['country'], $_SESSION['company_id']);
		echo json_encode($deleted_item);die;
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_delete_tb_row_edit
	 * @Description	: 	Function to delete the sales time-billing
	 * @Params		:	int, int
	 */
	public function action_delete_tb_row_edit($delete_service_sale_id , $saleId)
	{
		$sales			=	new Model_Sales;
		$deleted_item	=   $sales->delete_tb_by_id_new($delete_service_sale_id, $saleId, $_SESSION['country'], $_SESSION['company_id']);
		echo json_encode($deleted_item);die;
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_to_be_synced_pagination
	 * @Description	: 	Function for pagination in to_be_synced_page
	 * @Params		:	field_val as string
	 */
	public function action_to_be_synced_pagination($field_val){
		$this->auto_render = false;
		$final_array			=	array();
		$sales					=	new Model_Sales;
		$customers				=	new Model_Customerandjobs;
		$to_besynced_list		=	$sales->get_to_be_synced_list();
		$to_besynced_customers	=	$customers->get_customer_to_sync();
		$to_besynced_jobs		=	$customers->get_jobs_to_sync();
		
		$final_array				=	array_merge($to_besynced_list,$to_besynced_customers,$to_besynced_jobs);
		usort($final_array,array($this,"compare"));
		$field_val	=	isset($_POST['sort_field'])?$_POST['sort_field']:1;
		$order	=	isset($_POST['order'])?$_POST['order']:1;
		$end	=	isset($_POST['end'])?$_POST['end']:10;
		switch($field_val) {
			case '1': $field_val	=	"created_date";
					break;
			case '2': $field_val	=	"type";
					break;
			case '3': $field_val	=	"company_or_lastname";
					break;
			case '4': $field_val	=	"total_payment";
					break;
			default: $field_val		=	"created_date";
					break;
		}
		$final_array = $this->aasort($final_array,$field_val,$order);
		$ini_array = array_slice($final_array, 0, $end);
		
		$this->request->response = 	View::factory('sales/to_be_synced_pagination')
											->set('sales', $ini_array);
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	aasort
	 * @Description	: 	function for sorting an array as per key values
	 * @Params		:	array, key and order
	 */
	public function aasort ($array, $key,$order) {
		$sorter	=array();
		$ret 	=array();
		reset($array);
		foreach ($array as $ii => $va) {
			if($key == "company_or_lastname"){
				$va[$key][0] = strtoupper($va[$key][0]);
			}
			$sorter[$ii]=$va[$key];
		}
		if($order==1){
			arsort($sorter);
		} else {
			asort($sorter);
		}
		foreach ($sorter as $ii => $va) {
			$ret[$ii]=$array[$ii];
		}
		$array=$ret;
		return $array;
	}
	
	
	public function action_get_tax_values_on_customer_change($id,$table){
		$sales		=	new Model_Sales;
		$tax_values	=   $sales->get_tax_values($id,$table);
		echo json_encode($tax_values);die;
	}
	
	public function action_mailRecipt($mode) {
		$data_model		=	new Model_Data;
		$sale_info		=	json_decode($_POST['sale'],true);
		$slip_info		=	json_decode($_POST['slip_detail'],true);
		$payment_info	=	json_decode($_POST['payment_info'],true);
		$pdf_created	=	$this->generate_invoice_pdf($sale_info, $slip_info, $payment_info);
		if($mode	==	'email') {
		$mail_content['to']				=	$sale_info['customer_email'];
		$mail_content['cc']				=	$_SESSION['admin_email'];
		$mail_content['subject']		=	"Transaction Receipt";
		$mail_content['replyto']		=	$_SESSION['admin_email'];
		$mail_content['reply_to_name']	=	$sale_info['company_name'];
		$mail_content['content']		=	"Hello <b><i>".$sale_info['firstname'].' '.$sale_info['company_or_lastname'].','."</i></b>
											<br/><br/>							
											Please find enclosed your transaction receipt.
											<br/><br/>
											Regards,<br/>".
											$sale_info['company_name'];
		$data_model->send_email($mail_content,'',$pdf_created);
		unlink($pdf_created);
		echo '1';die;
		} else {
			echo '2';die;
		}
	}
	
	public function generate_invoice_pdf($sale_info, $slip_info, $payment_info){  
		include_once DOCUMENT_ROOT.'/application/classes/vendors/pdf_processing/fpdf/fpdf.php';
        include_once DOCUMENT_ROOT.'/application/classes/vendors/pdf_processing/FPDI/fpdi.php';
      	/***Country Names Starts***/
        switch ($sale_info['company_country']){
        	case '1': $sale_info['company_country'] = "USA";
        	break;
        	case '2': $sale_info['company_country'] = "UK";
        	break;
        	case '3': $sale_info['company_country'] = "Australia";
        	break;
        	case '4': $sale_info['company_country'] = "Canada";
        	break;
        	case '5': $sale_info['company_country'] = "New Zealand";
        	break;
        	default	: $sale_info['company_country'] = "USA";
        	break;
        }
        /***Country Names Starts***/
        // initiate FPDI  
        $pdf = new FPDI();
       
        // add a page
        $pdf->AddPage();
        // set the sourcefile
        if($sale_info['type']	==	1 || $sale_info['type']	==	2 || $sale_info['type']	==	3){
        	$pdf->setSourceFile(DOCUMENT_ROOT.'/media/pdf_samples/item.pdf');
        } else if($sale_info['type']	==	4 || $sale_info['type']	==	5 || $sale_info['type']	==	6){
        	$pdf->setSourceFile(DOCUMENT_ROOT.'/media/pdf_samples/service.pdf');
        } else{
       		$pdf->setSourceFile(DOCUMENT_ROOT.'/media/pdf_samples/timebilling.pdf');
        }
        // import page 1  
        $tplIdx = $pdf->importPage(1);
        // use the imported page and place it at point 0,0 with a width of the page in millimeters   (This is the image of the included pdf)
        $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
        // now write some text above the imported page
        $pdf->SetTextColor(02,02,02);

        $pdf->SetFont('Arial', '', 12);
        
        $pdf->SetXY(15, 40);
        $pdf->Write(0, $sale_info['company_name']);
        
        $pdf->SetXY(15, 45);
        $pdf->Write(0, $sale_info['company_address1']);
        
        $pdf->SetXY(15, 50);
        $pdf->Write(0, ($sale_info['company_address2'].' '.$sale_info['company_city']));
        
        $pdf->SetXY(15, 55);
        $pdf->Write(0, ($sale_info['company_state'].' '.$sale_info['company_country'].' '.$sale_info['company_zipcode']));
        
        
        $pdf->SetXY(15, 71);
        if(!empty($sale_info['firstname'])){
        	$pdf->Write(0, ($sale_info['firstname'].' '.$sale_info['company_or_lastname']));
        } else {
        	$pdf->Write(0, ($sale_info['company_or_lastname']));
        }
        
        $pdf->SetXY(15, 76);
        $pdf->Write(0, $sale_info['address1']);
        
        $pdf->SetXY(15, 81);
        $pdf->Write(0, $sale_info['address2']);
        
        $pdf->SetXY(15, 86);
        $pdf->Write(0, $sale_info['address3']);
        
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(156, 34.3);
        $pdf->Write(0, $sale_info['sale_number']);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(156, 43.5);
        $pdf->Write(0, date("F d, Y",strtotime($sale_info['selected_date'])));

        $pdf->SetXY(156, 53);
        $pdf->Write(0, $sale_info['terms']);
        
        $spacing		=	0;
        $y_coordinate	=	115;
        $img_coor		=	-2;
        $enable			=	DOCUMENT_ROOT.'/media/images/tt-new/enable.png';
        $disable		=	DOCUMENT_ROOT.'/media/images/tt-new/disable.png';
        if($sale_info['type']	==	1 || $sale_info['type']	==	2 || $sale_info['type']	==	3){
	        for($row=0;$row<count($slip_info);$row++) {
	        	$pdf->SetXY(15, $y_coordinate);
	        	$pdf->Write(0, $slip_info[$row]['item_number']);
	        	
	        	$pdf->SetXY(50, $y_coordinate);
	        	$pdf->Write(0, substr($slip_info[$row]['item_name'], 0, 18).'..');
	        	
	        	$pdf->SetXY(95, $y_coordinate);
	        	$pdf->Write(0, $slip_info[$row]['quantity']);
	        	
	        	$pdf->SetXY(120, $y_coordinate);
	        	$pdf->Write(0, '$'.$slip_info[$row]['price']);
	        	
	        	$pdf->SetXY(146, $y_coordinate);
	        	//$pdf->Write(0, '$'.$slip_info[$row]['total']);
	        	$pdf->Cell(27,0,'$'.$slip_info[$row]['total'],'','','R');
	        	if($sale_info['company_country'] == "USA"){			//For USA
	        		if(isset($slip_info[$row]['apply_tax']) && $slip_info[$row]['apply_tax'] ==	1){
	        			$pdf->Image($enable, 189, $y_coordinate+$img_coor,4,4);
	        		} else {
	        			$pdf->Image($disable, 189, $y_coordinate+$img_coor,4,4);
	        		}
	        		$img_coor+=7.6;
	        	} else {
	        		$pdf->SetXY(185, $y_coordinate);
	        		$pdf->Write(0, $slip_info[$row]['tax_code']);
	        	}
	        	$spacing+=15;$y_coordinate+=8;
	        }
        } else if($sale_info['type']	==	4 || $sale_info['type']	==	5 || $sale_info['type']	==	6){
       		for($row=0;$row<count($slip_info);$row++) {
	        	$pdf->SetXY(15, $y_coordinate);
	        	$pdf->Write(0, substr($slip_info[$row]['description'], 0, 24).'..');
	        	
	        	$pdf->SetXY(100, $y_coordinate);
	        	$pdf->Write(0, $slip_info[$row]['account_number']);
	        	
	        	$pdf->SetXY(156, $y_coordinate);
	        	//$pdf->Write(0, '$'.$slip_info[$row]['amount']);
	        	$pdf->Cell(17,0,'$'.$slip_info[$row]['amount'],'','','R');
	        	if($sale_info['company_country'] == "USA"){			//For USA
	       			if(isset($slip_info[$row]['tax']) && $slip_info[$row]['tax'] ==	1){
		        		$pdf->Image($enable, 189, $y_coordinate+$img_coor,4, 4);
		        	} else {
		        		$pdf->Image($disable, 189, $y_coordinate+$img_coor,4, 4);
		        	}
		        	$img_coor+=-.4;
	        	} else {
	        		$pdf->SetXY(185, $y_coordinate);
	        		$pdf->Write(0, $slip_info[$row]['tax_code']);
	        	}
	        	$spacing+=15;$y_coordinate+=8;
	        }
        } else {
       		for($row=0;$row<count($slip_info);$row++) {
	        	$pdf->SetXY(15, $y_coordinate);
	        	$pdf->Write(0, $slip_info[$row]['activity_id']);
	        	
	        	$pdf->SetXY(50, $y_coordinate);
	        	$pdf->Write(0, substr($slip_info[$row]['description'], 0, 18).'..');
	        	
	        	$pdf->SetXY(95, $y_coordinate);
	        	$pdf->Write(0, $slip_info[$row]['rates']);
	        	
	        	$pdf->SetXY(120, $y_coordinate);
	        	$pdf->Write(0, '$'.$slip_info[$row]['units']);
	        	
	        	$pdf->SetXY(146, $y_coordinate);
	        	//$pdf->Write(0, '$'.$slip_info[$row]['amount']);
	        	$pdf->Cell(27,0,'$'.$slip_info[$row]['amount'],'','','R');
	        	if($sale_info['company_country'] == "USA"){			//For USA
	       			if(isset($slip_info[$row]['tax_check']) && $slip_info[$row]['tax_check'] ==	1){
		        		$pdf->Image($enable, 189, $y_coordinate+$img_coor,4, 4);
		        	} else {
		        		$pdf->Image($disable, 189, $y_coordinate+$img_coor,4, 4);
		        	}
		        	$img_coor+=7.6;
	        	} else {
	        		$pdf->SetXY(185, $y_coordinate);
	        		$pdf->Write(0, $slip_info[$row]['tax_code']);
	        	}
	        	$spacing+=15;$y_coordinate+=8;
	        }
        }
       	$pdf->Ln(20);
        $y_coordinate=$pdf->GetY()+20;
        $pdf->SetTextColor(79,79,79);
        $pdf->SetXY(23.9, $y_coordinate);
        $pdf->Write(0, 'Salesperson');
        $pdf->SetFont('Arial', 'B', 12);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(119, $y_coordinate);
        $pdf->Write(0, 'Sale Amount');
        $pdf->SetTextColor(02,02,02);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(147, $y_coordinate);
        //$pdf->Write(0, '$'.$sale_info['subtotal']);
        $pdf->Cell(25,0,'$'.$sale_info['subtotal'],'','','R');
        $pdf->SetFont('Arial', '', 12);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(52, $y_coordinate);
        $pdf->Write(0, $sale_info['sales_person']);
        
      	// Payment Method  
		$y_coordinate = $pdf->GetY()+8;
		$pdf->SetTextColor(79,79,79);
        $pdf->SetXY(15, $y_coordinate);
        $pdf->Write(0, 'Payment Method');
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(131, $y_coordinate);
        $pdf->Write(0, 'Freight');
        $pdf->SetTextColor(02,02,02);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(52, $y_coordinate);
        $pdf->Write(0, !empty($payment_info['payment_method']) ? $payment_info['payment_method'] : $sale_info['payment_method']);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(147, $y_coordinate);
       	$pdf->Cell(25,0,'$'.$sale_info['freight'],'','','R');
        
        $y_coordinate = $pdf->GetY()+8;
        $pdf->SetTextColor(79,79,79);
        $pdf->SetXY(15, $y_coordinate);
        $pdf->Write(0, 'Shipping Method');
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(136.7, $y_coordinate);
        $pdf->Write(0, 'Tax');
        $pdf->SetTextColor(02,02,02);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(52, $y_coordinate);
        $pdf->Write(0, $sale_info['shipping_method']);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(147, $y_coordinate);
        $pdf->Cell(25,0, '$'.number_format((float)$sale_info['tax_total_amount'], 2, '.', ''),'','','R');

        $y_coordinate = $pdf->GetY()+8;
        $pdf->SetTextColor(79,79,79);
        $pdf->SetXY(21, $y_coordinate);
        $y_coordinate = $pdf->GetY();
        $pdf->Write(0, 'Card Number');
        $pdf->SetXY(146, $y_coordinate);
        $pdf->Write(0, '_____________');
        $pdf->SetTextColor(02,02,02);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(52, $y_coordinate);
        $pdf->Write(0, !empty($payment_info['last_digits_on_card']) ? '**** **** **** '.$payment_info['last_digits_on_card'] : '');
        
        $y_coordinate = $pdf->GetY()+8;
        $pdf->SetTextColor(79,79,79);
        $pdf->SetXY(17, $y_coordinate);
        $pdf->Write(0, 'Transaction Id#');
        $pdf->SetFont('Arial', 'B', 12);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(134.2, $y_coordinate);
        $pdf->Write(0, 'Total');
        $pdf->SetTextColor(02,02,02);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(147, $y_coordinate);
        $pdf->Cell(25,0,'$'.$sale_info['total_payment'],'','','R');
        $pdf->SetFont('Arial', '', 12);
		
        if(isset($payment_info['gateway_transaction_id']) && !empty($payment_info['gateway_transaction_id'])){
        	$length		=	strlen($payment_info['gateway_transaction_id']);
        	$first		= 	substr($payment_info['gateway_transaction_id'], 0, $length/2);
 			$second		= 	substr($payment_info['gateway_transaction_id'], $length/2+1,$length);
			//
 			$y_coordinate = $pdf->GetY();
			$pdf->SetXY(52, $y_coordinate);
 			$pdf->Write(0,$first);
 			$y_coordinate = $pdf->GetY();
 			$pdf->SetXY(52, $y_coordinate);
			$pdf->Write(10,$second);
        } else {
       		$pdf->Write(0, '');
        }

        
        $y_coordinate = $pdf->GetY()+8;
        $pdf->SetTextColor(79,79,79);
        $pdf->SetXY(119.5, $y_coordinate);
        $pdf->Write(0, 'Amount Paid');
        $pdf->SetTextColor(02,02,02);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(147, $y_coordinate);
        $pdf->Cell(25,0,'$'.$sale_info['paid_today'],'','','R');
        
        $y_coordinate = $pdf->GetY()+4;
        $pdf->SetTextColor(79,79,79);
        $pdf->SetXY(146, $y_coordinate);
        $pdf->Write(0, '_____________');
        
        $y_coordinate = $pdf->GetY()+8;
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->SetTextColor(79,79,79);
        $pdf->SetXY(117, $y_coordinate);
        $pdf->Write(0, 'Balance Due');
        $pdf->SetTextColor(02,02,02);
        $y_coordinate = $pdf->GetY();
        $pdf->SetXY(147, $y_coordinate);
        $pdf->Cell(25,0,'$'.$sale_info['balance'],'','','R');
        
        $y_coordinate = $pdf->GetY()+4;
        $pdf->SetTextColor(79,79,79);
        $pdf->SetXY(146, $y_coordinate);
        $y_coordinate = $pdf->GetY();
        $pdf->Write(0, '_____________');
        $pdf->SetXY(146, $y_coordinate+1);
        $y_coordinate = $pdf->GetY();
        $pdf->Write(0, '_____________');
        $name = preg_replace('/[^A-Za-z0-9]/','',$sale_info['company_or_lastname']).'_Invoice'.'_'.$sale_info['sale_number'].'.pdf';
        $file = $pdf->Output($name,'S');
        $pdf->Output(DOCUMENT_ROOT.'/media/temp_pdf_storage/'.$name,'F');
        return DOCUMENT_ROOT.'/media/temp_pdf_storage/'.$name;
    }
    
    public function action_viewPdf($file){
		$file = $file.'.pdf';
		$path = DOCUMENT_ROOT.'/media/temp_pdf_storage/'.$file;
	     
	    // Set headers
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-Disposition: attachment; filename=$file");
	    header("Content-Type: application/pdf");
	    header("Content-Transfer-Encoding: binary");
	     
	    // Read the file from disk
	    readfile($path);
	    unlink($path);
	    die;
    }
	
	// function: 	
				//	To delete Sales Payment from transaction or transaction id.
				//	Add an entry 0 in sales table.
	public function action_sale_payment_delete($sale_id, $card_type){
		$transaction_m 	=	new Model_Transaction;
		$sale_m 		=	new Model_Sales;
		
		$sale_info	=	$sale_m->get_sale_by_id($sale_id, $_SESSION['company_id']);
		if(isset($sale_info[0]['sync_status']) && $sale_info[0]['sync_status'] == '0' ){
			if($card_type == 'credit'){ // Remove sale from transaction
				$transaction_m->delete_sales_transaction('transaction', $sale_id);
			} else { // Remove sale from transaction other.
				$transaction_m->delete_sales_transaction('transaction_other', $sale_id);
			}
			// Update sale page.
			$arr_data['paid_today']	= 0;
			$arr_data['balance']	= $sale_info[0]['total_payment'];
			$arr_data['payment'] 	= 0;
			$sale_m->update( 'sales', $arr_data, $sale_id);
		} else{
			// Return proper error message.
		}

		Request::instance()->redirect(SITEURL.'/sales/edit/'.$sale_id);
	}
	
	// function: 	
				//	To Void + delete Sales Payment from transaction or transaction id.
				//	Add an entry 0 in sales table.
	public function action_sale_payment_void($sale_id){
		require Kohana::find_file('classes', 'library/Ach');
		$gateway_ach_model	= 	new Model_Gatewayach;
		$transaction_m		=	new Model_Transaction;
		$sale_m				=	new Model_Sales;
		
		$transaction_details 	=	$transaction_m->get_sales_transaction_details($sale_id);
		if(isset($transaction_details[0]) && !empty($transaction_details[0])){
			$transaction_details	= 	$transaction_details[0];
			
			$data_log = array();
	 		$data_log['request_code'] 	= 'Void Authorization';
	 		$data_log['customer_id'] 	= $transaction_details['customer_id'];
	 		$data_log['transaction_id']	= $transaction_details['id'];
			$data_log['sale_id']		= $sale_id;
	 		$data_log['domain_name'] 	= $_SERVER['SERVER_NAME'];
	 		$data_log['created_date']   = date("Y-m-d H:i:s");
	 		$data_log['updated_date']   = date("Y-m-d H:i:s");
	 		$transaction_log_id 		= $transaction_m->insert_tranasction_log('transaction_log', $data_log);
			
			$merchant_details 			= $gateway_ach_model->get_merchant_personal_ach_details($_SESSION['company_id']);
			$ach 						= new Ach($merchant_details[0], true);
			
			$trans_num 	= $transaction_details['gateway_transaction_id'];
			$auth_code 	= $transaction_details['gateway_authorization_code'];
			$response 	= $ach->void_transaction($trans_num, $auth_code);
			$response_descripiton = $response['response_descripiton'];
			// update transaction log.
			$response 	= 'VOID -'.$response["response_transaction_number"].'-'.$response["response_code"].'-'.$response["response_type"].'-'.$response['response_authorization_code'].'-'.$response["response_descripiton"];
			$data_log['updated_date']   = date("Y-m-d H:i:s");
			$data_log['response_data'] 	= $response;
			$transaction_m->update_transaction_log('transaction_log', $data_log, $transaction_log_id);
			
			if($response_descripiton=='APPROVED'){ // update the sale table and also delete the entry from transaction table.
				$sale_info	=	$sale_m->get_sale_by_id($sale_id, $_SESSION['company_id']);
				if(isset($sale_info[0]['sync_status']) && $sale_info[0]['sync_status'] == '0' ){
					$transaction_m->delete_sales_transaction('transaction', $sale_id);
					// Update sale page.
					$arr_data['paid_today']	= 0;
					$arr_data['balance']	= $sale_info[0]['total_payment'];
					$arr_data['payment'] 	= 0;
					$sale_m->update( 'sales', $arr_data, $sale_id);
				} else{
					// Return proper error message.
				}
				// if possible show pop up in redirect a msg that, void authoriation is done. 
				Request::instance()->redirect(SITEURL.'/sales/edit/'.$sale_id);
			}	
		}
	}
	
	// Payment Receipt controller
	public function action_payment_receipt($sale_id){
	
		try{
			require_once Kohana::find_file('classes', 'library/Sync');
			$sync_lib		=	new Sync;
	 		$sales_m		=	new Model_Sales;
			$transaction_m 	=	new Model_Transaction;
			$company_m		=	new Model_Company;
			
			$keys 			= 	array('ccp');
			$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
			if(!empty($company_data)&&$company_data['ccp']=='1'){
				$ccp = 1;
			} else {
				$ccp = 0;
			}
			$sale_info			= 	$sales_m->get_sale_details($sale_id);
			if(isset($sale_info[0]['payment_type']) && $sale_info[0]['payment_type']== "2") { //paid by credit card
				$transaction	=	$transaction_m->get_card_transaction($sale_id);
			} else {  // Payment by other mode.
				$transaction	=	$transaction_m->get_other_transaction($sale_id);
			}
			
			$header 	= 	View::factory('sales/sales_list_header')
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('payment_form', 		'1')
										->set('sale_id', 			$sale_id)
										->set('title', 				'Payment Details'); 
			$this->template->content = 	View::factory('sales/payment_form')
													->set('header', 		$header)
													->set('sale_details', 	$sale_info)
													->set('paid_today', 	$sale_info[0]['paid_today'])
													->set('card_type', 		$sale_info[0]['payment_type'])
													->set('ccp', 			$ccp)
													->set('notes', 			!empty($transaction[0]['notes'])?$transaction[0]['notes']:'')
													->set('check_num', 		!empty($transaction[0]['check_num'])?$transaction[0]['check_num']:'')
													->set('payment_method', $sale_info[0]['payment_method'])
													->set('name_on_card', 	!empty($transaction[0]['card_name'])?$transaction[0]['card_name']:'') 
													->set('trace_id', 		!empty($transaction[0]['gateway_transaction_id'])?$transaction[0]['gateway_transaction_id']:'')
													->set('exp_date', 		!empty($transaction[0]['expiry_date'])?$transaction[0]['expiry_date']:'')
													->set('last_4_digits', 	!empty($transaction[0]['last4digits'])?$transaction[0]['last4digits']:'')
													->set('auth_code', 		!empty($transaction[0]['gateway_authorization_code'])?$transaction[0]['gateway_authorization_code']:'')
													->set('payment_receipt',1);
		} catch(Exception $e){
			// redirect the page to sales edit page. with proper error code & error message.
			
		}
												
	}
}