<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * @File : customer.php Controller
 * @Class : Controller_Customer
 * @Description: This class file holds the operations of new customer, edit, view customer and sync
 * Copyright (c) 2011 Acclivity Group LLC 
 */
class Controller_Customer extends Controller_Template
{
	/**
	 * @Method: __construct 
	 * @Description: This method calls the validation session and checks user is logged in or not
	 * 				 if the user is not logged in he will redirected to login page. 
	 */
	private $dropbox_customer_changes_folder;
	private $dropbox_sales_folder;
	private $json_content;
	public function __construct(Request $request)
	{
		parent::__construct($request);
		$validations	= 	new Model_validations;
		$validations->check_user_session();
		$this->dropbox_customer_changes_folder	=	DROPBOXFOLDERPATH."ThirdPartyChanges/Cards/";
		$this->dropbox_sales_folder				=	DROPBOXFOLDERPATH."ThirdPartyChanges/Sales/";
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_index
	 * @Description	:	Customer controller index page
	 */
	public function action_index() {
		try{
		$this->template->tab	=	2;
		$customer		=	new Model_Customerandjobs;
		require Kohana::find_file('classes', 'library/Sync');
		$sync_lib 	= 	new Sync;
		$sort_field		=	isset($_POST['sort_field'])?$_POST['sort_field']:'1';
		$order			=	isset($_POST['order'])?$_POST['order']:'1';
		switch($sort_field) {
			case 1: $field_val	=	"c.company_or_lastname";
					break;
			case 2: $field_val	=	"c.is_individual_card";
					break;
			case 3: $field_val	=	"c.phone";
					break;
			case 4: $field_val	=	"c.contact";
					break;
			default: $field_val	=	"c.company_or_lastname";
					 break;
		}
		$order_method		=	($order == 1) ? "DESC":"ASC";
		if(!empty($_POST['search_customer'])) {
		    $search_customer    =   $_POST['search_customer'];
		    $customers			=	$customer->get_customer_search_result($_POST['search_customer'], 1, $field_val, $order_method);
		    $sales_full_list	=	$customer->get_customer_search_result($_POST['search_customer'], 0, $field_val, $order_method);
			$sale_count			=	count($sales_full_list);
			$header 	= 	View::factory('sales/sales_list_header')
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('search_value',$search_customer)
										->set('tab', '2');

		    $this->template->content = 	View::factory('customer/list')
												->set('customers', $customers)
												->set('total_customer', $sale_count)
												->set('sort_field', $sort_field)
												->set('order', $order)
												->set('filter', '0')
												->set('per_slip', isset($_POST['view_per_page'])?$_POST['view_per_page']:10)
												->set('total_pages', $customer->total_customer_pages())
												->set('header', $header)
												->set('search_customer',$search_customer)
												;
		}
		else
		{
		   $customers	=	$customer->get_all_customer(0,$_SESSION['company_id'], $_SESSION['employee_id'], $_SESSION['admin_user']);
		   $header 	= 	View::factory('sales/sales_list_header')
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('search_value','')
										->set('tab', '2');
			
		    $this->template->content = 	View::factory('customer/list')
												->set('customers', $customers)
												->set('sort_field', $sort_field)
												->set('order', $order)
												->set('filter', '0')
												->set('total_customer', $customer->total_customers())
												->set('total_pages', $customer->total_customer_pages())
												->set('header', $header);
		}
		}catch (Exception $e){ die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_create
	 * @Description	:	Function to show customer create form
	 */
	public function action_create() {
	    try {
		$this->template->tab	=	2;
		$customer				=	new Model_Customerandjobs;
		$accounts_m 			=	new Model_Accounts;
		$taxes_m 				= 	new Model_Taxes;
		$company_m 				=	new Model_Company;
		$employee_m				=	new Model_Employee;
		
		require Kohana::find_file('classes', 'library/Sync');
		$sync_lib 					= 	new Sync;
		$tax_record_id 				=	0;
		$tax_name	   				=	"";
		$sales_person_name			=	"";
		$sales_person_id			=	"";
		$income_account_details		=	"";
		
		$header 					= 	View::factory('sales/sales_list_header')
										->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
										->set('customer_job', '1')
										->set('customer', '1')
										->set('title', 'Add Customer');
										
		$company					= 	$company_m->get_company_details($_SESSION['company_id']);
		$company 					= 	$company[0];
		
		if(isset($_POST['submit'])) {//if form gets submitted
			try {
				
				if($_POST['is_individual'] == "1") {
					if($_POST['firstname'] == "") throw new Exception("Please enter firstname");
					if($_POST['lastname'] == "") throw new Exception("Please enter lastname");
				} else {
					if($_POST['lastname'] == "") throw new Exception("Please enter company");
				}
				
				if($_POST['is_individual'] == "1") {
					if(!preg_match("/^([a-z\s\-\'\.\,]*)$/i", $_POST['firstname'])) throw new Exception("Please enter valid first name");
					if(!preg_match("/^([a-z\s\-\'\.\,]*)$/i", $_POST['lastname'])) throw new Exception("Please enter valid last name");
				} else {
					if(!preg_match("/^([a-z\s\-\'\.\,]*)$/i", $_POST['lastname'])) throw new Exception("Please enter valid company name");
				}
				
				if(isset($_POST['sales_person']) && $_POST['sales_person'] != "") {
					$arr_sales_person	=	explode("--",$_POST['sales_person']);
					$sales_person_name	=	$arr_sales_person[0];
					$sales_person_id	=	$arr_sales_person[1];
				}
				if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
					$employee_id	=	0;
					$admin_card		=	'1';
				} else {
					$employee_id	=	$_SESSION['employee_id'];
					$admin_card		=	'0';
				}
				if(isset($_POST['income_account']) && $_POST['income_account'] != ""){
					$income_account_details		=	($_POST['income_account']);
				}
				$customer_columns			=	array('company_id', 'employee_id', 'admin_card', 'firstname', 'company_or_lastname', 'email', 'phone',
													  'contact', 'tax_record_id', 'freight_tax_record_id','use_customer_tax_code', 'type', 'custom_list1', 'custom_list2',
													  'custom_list3', 'custom_field1', 'custom_field2', 'custom_field3',
													  'street1', 'street2', 'city', 'state', 'country', 'zip',
													  'is_card_inactive', 'is_individual_card', 'account', 'billing_rate', 'salesperson_id', 'salesperson',
													  'credit_limit', 'created_date', 'updated_date', 'synced_status', 'status'
												);
				$customer_values			=	array( $_SESSION['company_id'], $employee_id, $admin_card, $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['phone'],
													   $_POST['contact'], (isset($_POST['tax_record_id'])? $_POST['tax_record_id'] : 0), (isset($_POST['freight_tax_record_id'])? $_POST['freight_tax_record_id'] : 0), (isset($_POST['use_customer_tax_code'])? $_POST['use_customer_tax_code'] : 0), $_POST['type'], $_POST['custom_list1'],
													   $_POST['custom_list2'], $_POST['custom_list3'], $_POST['custom_field1'], $_POST['custom_field2'], $_POST['custom_field3'],
													   $_POST['street1'], $_POST['street2'], $_POST['city'], $_POST['state'],
													   $_POST['country'], $_POST['zipcode'], '0', $_POST['is_individual'], $income_account_details, 
													   $_POST['billing_rate'], $sales_person_id, $sales_person_name, $_POST['credit_limit'],
													   date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), '0', '1');
				$customer_status			=	$customer->save("customers", $customer_columns, $customer_values);
				Request::instance()->redirect(SITEURL.'/customer');
			} catch(Exception $e) { 
				$popups_sales			= View::factory('sales/sales_popups')
											->set('accounts',$accounts_m->get_company_account($_SESSION['country']))
											->set('countries',$customer->get_countries())
											->set('Custom_list_1',$customer->get_custom_list_1())
											->set('Custom_list_2',$customer->get_custom_list_2())
											->set('Custom_list_3',$customer->get_custom_list_3())
											->set('employees', $employee_m->get_all_employee($_SESSION['company_id']));
				$this->template->content = 	View::factory('customer/create')
												->set('header', $header)
												->set('firstname',$_POST['firstname'])
												->set('lastname',$_POST['lastname'])
												->set('email',$_POST['email'])
												->set('street1',$_POST['street1'])
												->set('street2',$_POST['street2'])
												->set('city',$_POST['city'])
												->set('state',$_POST['state'])
												->set('country',$_POST['country'])
												->set('zipcode',$_POST['zipcode'])
												->set('is_individual',$_POST['is_individual'])
												->set('phone',$_POST['phone'])
												
												->set('contact',$_POST['contact'])
												->set('type',$_POST['type'])
												->set('tax_record_id', isset($_POST['tax_record_id']) ? $_POST['tax_record_id'] : 0)
												->set('freight_tax_record_id', isset($_POST['freight_tax_record_id']) ? $_POST['freight_tax_record_id'] : 0)
												->set('use_customer_tax_code', isset($_POST['use_customer_tax_code']) ? $_POST['use_customer_tax_code'] : 0)
												->set('custom_list1',$_POST['custom_list1'])
												->set('custom_list2',$_POST['custom_list2'])
												->set('custom_list3',$_POST['custom_list3'])
												->set('taxes', $taxes_m->get_taxes())
												->set('custom_field1',$_POST['custom_field1'])
												->set('custom_field2',$_POST['custom_field2'])
												->set('custom_field3',$_POST['custom_field3'])
												->set('credit_limit',$_POST['credit_limit'])
												->set('income_account',$income_account_details)
												->set('sales_person',$_POST['sales_person'])
												->set('billing_rate',$_POST['billing_rate'])
												->set('popups_sales',$popups_sales)
												->set('tax_code',$customer->get_all_taxes())
												->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
												->set('error', $e->getMessage())
												->set('custom_names', $customer->get_custom_names());
			}
		
		} 
		
		else { //loads customer create form
		
			$popups_sales			= View::factory('sales/sales_popups')
											->set('accounts',$accounts_m->get_company_account($_SESSION['country']))
											->set('countries',$customer->get_countries())
											->set('Custom_list_1',$customer->get_custom_list_1())
											->set('Custom_list_2',$customer->get_custom_list_2())
											->set('Custom_list_3',$customer->get_custom_list_3())
											->set('employees', $employee_m->get_all_employee($_SESSION['company_id']));
			$this->template->content = 	View::factory('customer/create')
												->set('header', $header)
												->set('firstname','')
												->set('lastname','')
												->set('email','')
												->set('street1','')
												->set('street2','')
												->set('city','')
												->set('state','')
												->set('country','')
												->set('zipcode','')
												->set('is_individual','1')
												->set('phone','')
												->set('contact','')
												->set('type','')
												->set('tax_record_id',$company['customer_tax_code_record_id'])
												->set('customer_tax_code', 	$company['tax_code'])
												->set('freight_tax_record_id',$company['customer_freight_tax_code_record_id'])
												->set('freight_tax_code', 	$company['freight_tax_code'])
												->set('use_customer_tax_code',$company['use_customer_tax_code'])
												->set('custom_list1','')
												->set('custom_list2','')
												->set('custom_list3','')
												->set('custom_field1','')
												->set('custom_field2','')
												->set('custom_field3','')
												->set('country_code',$_SESSION['country'])
												->set('credit_limit','')
												->set('income_account','')
												->set('country', !isset($_SESSION['country'])?1:$_SESSION['country'])
												->set('sales_person','')
												->set('billing_rate','')
												->set('popups_sales',$popups_sales)
												->set('taxes', $taxes_m->get_taxes())
												->set('tax_code',$customer->get_all_taxes())
												->set('custom_names', $customer->get_custom_names());
		}
	    } catch(Exception $e) {die($e->getMessage());}	   
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_edit
	 * @Description	:	Function to show customer edit form
	 * @Param		:	int
	 */
	public function action_edit($customer_id=0) {
		
		$this->template->tab	=	2;
		$customer	=	new Model_Customerandjobs;
		$accounts_m	=	new Model_Accounts;
		$taxes_m	=	new Model_Taxes;
		$employee_m	=	new Model_Employee;
		
		require Kohana::find_file('classes', 'library/Sync');
		$sync_lib 	= 	new Sync;
		$tax_record_id = 0;
		$tax_name	   = "";
		$sales_person_name	=	"";
		$sales_person_id	=	"";
		$income_account_details	=	"";
		$header 	= 	View::factory('sales/sales_list_header')
								->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
								->set('customer_job', '1')
								->set('customer', '1')
								->set('title', 'Edit Customer');
		if(isset($_POST['submit'])) { //if form gets submitted
			try {
				
				if($_POST['is_individual'] == "1") {
					if($_POST['firstname'] == "") throw new Exception("Please enter firstname");
					if($_POST['lastname'] == "") throw new Exception("Please enter lastname");
				} else {
					if($_POST['lastname'] == "") throw new Exception("Please enter company");
					$_POST['firstname'] = '';
				}
				if($_POST['is_individual'] == "1") {
					if(!preg_match("/^([a-z\s\-\'\.\,]*)$/i", $_POST['firstname'])) throw new Exception("Please enter valid first name");
					if(!preg_match("/^([a-z\s\-\'\.\,]*)$/i", $_POST['lastname'])) throw new Exception("Please enter valid last name");
				} else {
					if(!preg_match("/^([a-z\s\-\'\.\,]*)$/i", $_POST['lastname'])) throw new Exception("Please enter valid company name");
				}
				
				if(isset($_POST['sales_person']) && $_POST['sales_person'] != "") {
					$arr_sales_person	=	explode("--",$_POST['sales_person']);
					$sales_person_name	=	$arr_sales_person[0];
					$sales_person_id	=	$arr_sales_person[1];
				}
				
				if(isset($_POST['income_account']) && $_POST['income_account'] != ""){
					$income_account_details		=	$_POST['income_account'];
				}
				else{
					$income_account_details	=	"";
				}	
				$customer_data				=	array('firstname'				=> $_POST['firstname'],
													  'company_or_lastname'		=> $_POST['lastname'], 
													  'email'					=> $_POST['email'],
													  'street1'					=> $_POST['street1'], 
													  'street2'					=> $_POST['street2'], 
													  'city'					=> $_POST['city'], 
													  'state'					=> $_POST['state'], 
													  'country'					=> $_POST['country'], 
													  'zip'						=> $_POST['zipcode'],
													  'phone'					=> $_POST['phone'],
													  'contact' 				=> $_POST['contact'],
													 // 'tax_code' 				=> $tax_name,
													  'tax_record_id'			=> (isset($_POST['tax_record_id']) ? $_POST['tax_record_id'] : 0),
													  'freight_tax_record_id' 	=> (isset($_POST['freight_tax_record_id']) ? $_POST['freight_tax_record_id'] : 0),
													  'use_customer_tax_code'	=> (isset($_POST['use_customer_tax_code']) ? $_POST['use_customer_tax_code'] : 0),
													  'type' 					=> $_POST['type'],
													  'custom_list1' 			=> $_POST['custom_list1'],
													  'custom_list2' 			=> $_POST['custom_list2'],
													  'custom_list3' 			=> $_POST['custom_list3'],
													  'custom_field1' 			=> $_POST['custom_field1'],
													  'custom_field2' 			=> $_POST['custom_field2'],
													  'custom_field3' 			=> $_POST['custom_field3'],
													  'credit_limit' 			=> $_POST['credit_limit'],
													  'account' 				=> $income_account_details,
				 									  'salesperson_id' 			=> $sales_person_id,
													  'salesperson' 			=> $sales_person_name,
													  'billing_rate' 			=> $_POST['billing_rate'],
													  'is_individual_card' 		=> $_POST['is_individual'],
													  'updated_date'			=> date('Y-m-d H:i:s'),
													  'synced_status'			=>	'0'
												);
				$customer_status			=	$customer->update("customers", $customer_data, $_POST['customer_id']);
				Request::instance()->redirect(SITEURL.'/customer');
			} catch(Exception $e) { 
				$customer_info	=	$customer->get_customer($customer_id,$_SESSION['company_id']);
				
				if(isset($_POST['sales_person']) && $_POST['sales_person'] != "") {
					$arr_sales_person	=	explode("--",$_POST['sales_person']);
					$sales_person_name	=	$arr_sales_person[0];
					$sales_person_id	=	$arr_sales_person[1];
				}
				
				$popups_sales				= View::factory('sales/sales_popups')
												->set('accounts',$accounts_m->get_company_account($_SESSION['country']))
												->set('countries',$customer->get_countries())
												->set('Custom_list_1',$customer->get_custom_list_1())
												->set('Custom_list_2',$customer->get_custom_list_2())
												->set('Custom_list_3',$customer->get_custom_list_3())
												->set('employees', $employee_m->get_all_employee($_SESSION['company_id']));
				
				$this->template->content	= 	View::factory('customer/create')
												->set('header', $header)
												->set('customer_id', $customer_id)
												->set('firstname',$_POST['firstname'])
												->set('lastname',$_POST['lastname'])
												->set('email',$_POST['email'])
												->set('street1',$_POST['street1'])
												->set('street2',$_POST['street2'])
												->set('city',$_POST['city'])
												->set('error',$e->getMessage())
												->set('state',$_POST['state'])
												->set('country',$_POST['country'])
												->set('zipcode',$_POST['zipcode'])
												->set('is_individual',$_POST['is_individual'])
												->set('phone',$_POST['phone'])
												->set('contact',$_POST['contact'])
												->set('type',$_POST['type'])
												->set('tax_record_id', isset($_POST['tax_record_id']) ? $_POST['tax_record_id'] : 0)
												->set('freight_tax_record_id', isset($_POST['freight_tax_record_id']) ? $_POST['freight_tax_record_id'] : 0)
												->set('use_customer_tax_code',isset($_POST['use_customer_tax_code']) ? $_POST['use_customer_tax_code'] : 0)
												->set('customer_tax_code',$customer_info[0]['tax_code'])
												->set('country_code',$_SESSION['country'])
												->set('freight_tax_code',$customer_info[0]['freight_tax_code'])
												->set('custom_list1',$_POST['custom_list1'])
												->set('custom_list2',$_POST['custom_list2'])
												->set('custom_list3',$_POST['custom_list3'])
												->set('custom_field1',$_POST['custom_field1'])
												->set('custom_field2',$_POST['custom_field2'])
												->set('custom_field3',$_POST['custom_field3'])
												->set('credit_limit',$_POST['credit_limit'])
												->set('income_account',$_POST['income_account'])
												->set('salesperson_id',$sales_person_id)
												->set('salesperson', $sales_person_name)
												->set('billing_rate',$_POST['billing_rate'])
												->set('taxes', $taxes_m->get_taxes())
												->set('popups_sales',$popups_sales)
												->set('tax_code',$customer->get_all_taxes())
												->set('custom_names', $customer->get_custom_names());
			}
			// save
		} else { //loads customer create form 
			$customer_info	=	$customer->get_customer($customer_id,$_SESSION['company_id']);
			if(empty($customer_info))
				Request::instance()->redirect(SITEURL.'/customer');			
			try {
			$popups_sales			= View::factory('sales/sales_popups')
											->set('accounts',$accounts_m->get_company_account($_SESSION['country']))
											->set('countries',$customer->get_countries())
											->set('Custom_list_1',$customer->get_custom_list_1())
											->set('Custom_list_2',$customer->get_custom_list_2())
											->set('Custom_list_3',$customer->get_custom_list_3())
											->set('employees', $employee_m->get_all_employee($_SESSION['company_id']));
											
			$this->template->content = 	View::factory('customer/create')
												->set('header', $header)
												->set('customer_id', $customer_id)
												->set('firstname',$customer_info[0]['firstname'])
												->set('lastname',$customer_info[0]['company_or_lastname'])
												->set('email',$customer_info[0]['email'])
												->set('street1',$customer_info[0]['street1'])
												->set('street2',$customer_info[0]['street2'])
												->set('city',$customer_info[0]['city'])
												->set('state',$customer_info[0]['state'])
												->set('country_name',$customer_info[0]['country'])
												->set('zipcode',$customer_info[0]['zip'])
												->set('is_individual',$customer_info[0]['is_individual_card'])
												
												->set('phone',$customer_info[0]['phone'])
												->set('contact',$customer_info[0]['contact'])
												->set('type',$customer_info[0]['type'])
												->set('tax_record_id',$customer_info[0]['tax_record_id'])
												->set('freight_tax_record_id',$customer_info[0]['freight_tax_record_id'])
												->set('use_customer_tax_code',$customer_info[0]['use_customer_tax_code'])
												->set('custom_list1',$customer_info[0]['custom_list1'])
												->set('custom_list2',$customer_info[0]['custom_list2'])
												->set('custom_list3',$customer_info[0]['custom_list3'])
												->set('custom_field1',$customer_info[0]['custom_field1'])
												->set('custom_field2',$customer_info[0]['custom_field2'])
												->set('custom_field3',$customer_info[0]['custom_field3'])
												->set('customer_tax_code',$customer_info[0]['tax_code'])
												->set('country_code',$_SESSION['country'])
												->set('freight_tax_code',$customer_info[0]['freight_tax_code'])
												->set('credit_limit',$customer_info[0]['credit_limit'])
												->set('income_account',$customer_info[0]['account'])
												->set('salesperson',$customer_info[0]['salesperson'])
												->set('salesperson_id',$customer_info[0]['salesperson_id'])
												->set('billing_rate',$customer_info[0]['billing_rate'])
												->set('taxes', $taxes_m->get_taxes())
												->set('popups_sales',$popups_sales)
												->set('tax_code',$customer->get_all_taxes())
												->set('custom_names', $customer->get_custom_names());

			} catch(Exception $e) {die($e->getMessage());}
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_view
	 * @Description	:	Function to view customer
	 * @Param		:	int
	 */
	public function action_view($customer_id) {
		$this->template->tab	=	2;
		$customer	=	new Model_Customerandjobs;
		require Kohana::find_file('classes', 'library/Sync');
		$sync_lib 	= 	new Sync;
		$customer_info	=	$customer->get_customer($customer_id,$_SESSION['company_id']);
		
		if(empty($customer_info))Request::instance()->redirect(SITEURL.'/');
		$header 	= 	View::factory('sales/sales_list_header')
								->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
								->set('customer_job', '1')
								->set('customer', '1')
								->set('title', 'View Customer');		
		$this->template->content = 	View::factory('customer/view')
											->set('customer', $customer_info)
											->set('header', $header)
											->set('custom_names', $customer->get_custom_names())
											->set('country_code', $_SESSION['country'])
											->set('country', $this->get_country_real_name($customer_info[0]['country']));
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_sync
	 * @Description	:	Function to sync one customer to Dropbox
	 * @Param		:	int
	 */
	public function action_sync($customer_id) {
		$this->template->tab	=	2;
		$user_model		=	new Model_User;
		$customer		=	new Model_Customerandjobs;
		try{
			$handshake	=	$user_model->check_handshake_updates();	
		} catch(Exception $e){}	
		$customer_info	=	$customer->get_customer($customer_id,$_SESSION['company_id']);
		$customer_card	=	array();
		$customer_card[0]['Addr1City']		=	$customer_info[0]['city'];
		$customer_card[0]['Addr1Country']	=	$customer_info[0]['country'];
		$customer_card[0]['Addr1Email']		=	$customer_info[0]['email'];
		$customer_card[0]['Addr1Line1']		=	$customer_info[0]['street1'];
		$customer_card[0]['Addr1Line2']		=	$customer_info[0]['street2'];
		$customer_card[0]['Addr1State']		=	$customer_info[0]['state'];
		$customer_card[0]['Addr1ZipCode']	=	$customer_info[0]['zip'];
		$customer_card[0]['CompanyOrLastName']	=	$customer_info[0]['company_or_lastname'];
		$customer_card[0]['CustomList1'] 	= 	$customer_info[0]['custom_list1'];
		$customer_card[0]['CustomList2'] 	= 	$customer_info[0]['custom_list2'];
		$customer_card[0]['CustomList3'] 	= 	$customer_info[0]['custom_list3'];
		$customer_card[0]['CustomField1'] 	= 	$customer_info[0]['custom_field1'];
		$customer_card[0]['CustomField2'] 	= 	$customer_info[0]['custom_field2'];
		$customer_card[0]['CustomField3'] 	= 	$customer_info[0]['custom_field3'];
		$customer_card[0]['FirstName']		=	$customer_info[0]['firstname'];
		$customer_card[0]['Account']		=	$customer_info[0]['account'];
		$customer_card[0]['Salesperson']		=	$customer_info[0]['salesperson'];
		$customer_card[0]['SalespersonRecordID']		=	$customer_info[0]['salesperson_id'];
		$customer_card[0]['IsIndividualCard']	=	($customer_info[0]['is_individual_card'] == "1")?true:false;
		
		if($customer_info[0]['record_id'] != "0") {
			$customer_card[0]['RecordID']		=	$customer_info[0]['record_id'];
		} else {
			$customer_card[0]['ThirdPartyCardID']	=	$customer_info[0]['id'];
		}
		if($customer_info[0]['tax_code'] != "") {
			$customer_card[0]['TaxCode']			=	$customer_info[0]['tax_code'];
			$customer_card[0]['TaxCodeRecordID']	=	$customer_info[0]['tax_record_id'];
		}
		if(isset($customer_info[0]['freight_tax_record_id']) && $customer_info[0]['freight_tax_record_id']!= ''){
			$customer_card[0]['FreightTaxCode']			=	$customer_info[0]['freight_tax_code'];
			$customer_card[0]['FreightTaxCodeRecordID']	=	$customer_info[0]['freight_tax_record_id'];
		}
		if(isset($customer_info[0]['use_customer_tax_code']) && $customer_info[0]['use_customer_tax_code']== 1){
			$customer_card[0]['UseCustomerTaxCode']		=	true;
		} else {
			$customer_card[0]['UseCustomerTaxCode']		=	false;
		}
		if(isset($customer_info[0]['payment_method']) && $customer_info[0]['payment_method'] != '') {
			$customer_card[0]['PaymentMethod']	=	$customer_info[0]['payment_method'];
		}
		if(isset($customer_info[0]['expiration_date']) && $customer_info[0]['expiration_date'] != '') {
			$customer_card[0]['ExpirationDate']	=	$customer_info[0]['expiration_date'];
		}
		if(isset($customer_info[0]['last_digits_on_card']) && $customer_info[0]['last_digits_on_card'] != '') {
			$customer_card[0]['Last4DigitsOnCard']	=	$customer_info[0]['last_digits_on_card'];
		}
		if(isset($customer_info[0]['name_on_card']) && $customer_info[0]['name_on_card'] != '') {
			$customer_card[0]['NameOnCard']	=	$customer_info[0]['name_on_card'];
		}
		if(isset($customer_info[0]['payment_token']) && $customer_info[0]['payment_token'] != '') {
			$customer_card[0]['ACHPaymentToken']	=	$customer_info[0]['payment_token'];
		}
		$customer_card[0]['TypeOfCard']	=	$customer_info[0]['type'];
		$this->json_content		=	json_encode($customer_card);
		$json_file_name			=	"CustomerChanges".$customer_info[0]['id'].".json";
		$status	=	$this->sync($json_file_name, $this->dropbox_customer_changes_folder);
		$customer_data			=	array("synced_status"	=>	"1");
		$customer_status		=	$customer->update("customers", $customer_data, $customer_info[0]['id']); //mark customer as synced
		//Request::instance()->redirect(SITEURL.'/sales/tobesynced');
		
		



	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	sync
	 * @Description	:	Function to do sync
	 * @Params		:	string,string
	 */
	private function sync($json_file_name, $dropbox_folder) {
		$dropbox				=	new Model_dropbox(); 
		$user					=	new Model_User;
		$local_folder_path		=	CACHE_DROPBOXFOLDERPATH.$json_file_name; // create json file in local
		$dropbox_folder_path	=	$dropbox_folder.$json_file_name; // create dropbox file
		
		// create local file and write json content
		$fp	=	fopen($local_folder_path,'a+');
		fwrite($fp, $this->json_content);
		fclose($fp);
		//die;
		try {
			$upload_status	=	$dropbox->upload_file($local_folder_path, $dropbox_folder_path); // Upload file to dropbox
		} catch(Exception $e) {
			$folder_status	=	$dropbox->create_folder($dropbox_folder_path); // Create third party folder in dropbox
			$upload_status	=	$dropbox->upload_file($local_folder_path, $dropbox_folder_path); // Upload file to dropbox
		}
		unlink($local_folder_path);
		$handshake_status	=	$user->check_handshake_updates();
		return true;
	}
	/**
	 * @Access		:	Public
	 * @Function	:	action_sync_all_customers
	 * @Description	:	Function to sync all the customers to Dropbox
	 */
	public function action_sync_all_customers() {
		$customer		=	new Model_Customerandjobs;
		$customer_list	=	$customer->get_customer_to_sync_all();
		$customer_card	=	array();
		$total_customer	=	count($customer_list);
		try {
		if(!empty($customer_list)) {
			for($i=0;$i<$total_customer;$i++) {
				$customer_card[$i]['Addr1City']			=	$customer_list[$i]['city'];
				$customer_card[$i]['Addr1Country']		=	$customer_list[$i]['country'];
				$customer_card[$i]['Addr1Email']		=	$customer_list[$i]['email'];
				$customer_card[$i]['Addr1Line1']		=	$customer_list[$i]['street1'];
				$customer_card[$i]['Addr1Line2']		=	$customer_list[$i]['street2'];
				$customer_card[$i]['Addr1State']		=	$customer_list[$i]['state'];
				$customer_card[$i]['Addr1ZipCode']		=	$customer_list[$i]['zip'];
				$customer_card[$i]['CompanyOrLastName']	=	$customer_list[$i]['company_or_lastname'];
				$customer_card[$i]['CustomList1'] 		= 	$customer_list[$i]['custom_list1'];
				$customer_card[$i]['CustomList2'] 		= 	$customer_list[$i]['custom_list2'];
				$customer_card[$i]['CustomList3'] 		= 	$customer_list[$i]['custom_list3'];
				$customer_card[$i]['CustomField1'] 		= 	$customer_list[$i]['custom_field1'];
				$customer_card[$i]['CustomField2'] 		= 	$customer_list[$i]['custom_field2'];
				$customer_card[$i]['CustomField3'] 		= 	$customer_list[$i]['custom_field3'];
				$customer_card[$i]['FirstName']			=	$customer_list[$i]['firstname'];
				$customer_card[$i]['IsIndividualCard']	=	($customer_list[$i]['is_individual_card'] == "1")?true:false;
				
				if($customer_list[$i]['record_id'] != "0") {
					$customer_card[$i]['RecordID']			=	$customer_list[$i]['record_id'];
				} else {
					$customer_card[$i]['ThirdPartyCardID']	=	$customer_list[$i]['id'];
				}
				if($customer_list[$i]['tax_code'] != "") {
					$customer_card[$i]['TaxCode']			=	$customer_list[$i]['tax_code'];
					$customer_card[$i]['TaxCodeRecordID']	=	$customer_list[$i]['tax_record_id'];
				}
				$customer_card[$i]['TypeOfCard']	=	$customer_list[$i]['type'];
				
				$customer_data			=	array("synced_status"	=>	"1");
				$customer_status		=	$customer->update("customers", $customer_data, $customer_list[$i]['id']); //mark customer as synced
			}
			$this->json_content		=	json_encode($customer_card);
			$json_file_name			=	"CustomerChanges".$customer_list[0]['id'].".json";
			$status	=	$this->sync($json_file_name, $this->dropbox_customer_changes_folder);
		}} catch(Exception $e) {die($e->getMessage());}
	//	Request::instance()->redirect(SITEURL.'/customer');
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	get_country_real_name
	 * @Description	:	Function to get country name
	 * @Param		:	string
	 */
	private function get_country_real_name($country) {
		$customer		=	new Model_Customerandjobs;
		$country_name	=	is_numeric($country)?$customer->get_country($country):$country;
		return $country_name;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_delete
	 * @Description	:	Function to delete customers
	 * @Params		:	int,int
	 */
	public function action_delete($customer_id, $delete_multiple=0) {
		$customer		=	new Model_Customerandjobs;
		$satus			=	$customer->delete("customers",$customer_id);
		if($delete_multiple) {
			return true;
		} else {
			Request::instance()->redirect(SITEURL.'/customer');
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_deletecustomers
	 * @Description	:	Function to delete multiple customers 
	 */
	public function action_deletecustomers() {
		if(empty($_POST['customer_id'])) Request::instance()->redirect(SITEURL.'/customer');
		$customer_id_count	=	count($_POST['customer_id']);
		if($customer_id_count == 0)	Request::instance()->redirect(SITEURL.'/customer');
		foreach($_POST['customer_id'] as $customer_id) {
			$delete_status	=	$this->action_delete($customer_id, 1);
		}
		Request::instance()->redirect(SITEURL.'/customer');
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_ajax_pagination
	 * @Description	:	ajax pagination of data
	 * @Params		:	none
	 */ 
	public function action_ajax_pagination() { 
		try {
			$customer			=	new Model_Customerandjobs;
			$page				=	$_POST['page'];
			$rows_per_page		=	$_POST['rows_per_page'];
			$sort_field			=	isset($_POST['sort_field'])?$_POST['sort_field']:1;
			$order				=	isset($_POST['order'])?$_POST['order']:1;
			$start				=	(($page*$rows_per_page)-$rows_per_page);
			$end				=	$rows_per_page;
			$search_customer 	=  	isset($_POST['search_customer'])?$_POST['search_customer']:'000';   
			if(isset($_POST['sort_field'])) {
				switch($sort_field) {
				case 1: $field_val	=	"c.company_or_lastname";
					break;
				case 2: $field_val	=	"c.is_individual_card";
					break;
				case 3: $field_val	=	"c.phone";
					break;
				case 4: $field_val	=	"c.contact";
					break;
				default: $field_val	=	"c.created_date";
					 break;
				}
				$order_method		=	($order == 1) ? "DESC":"ASC";
			}
			if($search_customer=='000') {
				$customers	=	$customer->paginated_customers($start, $end,$field_val,$order_method);
			} else{
				$customers				=	$customer->get_customer_search_result($_POST['search_customer'], 2, $field_val, $order_method);
				$customers_full_list	=	$customer->get_customer_search_result($_POST['search_customer'], 0, $field_val, $order_method);
				$sale_count				=	count($customers_full_list);
			}
			$paginated_view = 	View::factory('customer/page_view')
											->set('sort_field', $sort_field)
											->set('order', $order)
											->set('search_value',isset($_POST['search_customer'])?$_POST['search_customer']:'')
											->set('customers', $customers);
			die($paginated_view); 
		} 
		catch(Exception $e) {die($e->getMessage());}
	}
}