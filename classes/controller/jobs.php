<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * @File : jobs.php Controller
 * @Class : Controller_Customer
 * @Description: This class file holds the operations of new jobs, edit, view customer and sync
 * Copyright (c) 2011 Acclivity Group LLC 
 */
class Controller_Jobs extends Controller_Template
{
	private $dropbox_jobs_changes_folder;
	private $json_content;
	/**
	 * @Method: __construct 
	 * @Description: This method calls the validation session and checks user is logged in or not
	 * 				 if the user is not logged in he will redirected to login page. 
	 */
	public function __construct(Request $request)
	{
		parent::__construct($request);
		$validations	= 	new Model_validations;
		$validations->check_user_session();	
		$this->dropbox_jobs_changes_folder	=	DROPBOXFOLDERPATH."ThirdPartyChanges/Jobs/";
	}	
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_index
	 * @Description	:	Customer controller index page
	 */
	public function action_index() {
		try {
			$this->template->tab	=	2;
			require Kohana::find_file('classes', 'library/Sync');
			$sync_lib 	= 	new Sync;
			$job		=	new Model_Jobs;
			$cust_n_job	=	new Model_Customerandjobs;
			$sort_field		=	isset($_POST['sort_field'])?$_POST['sort_field']:'1';
			$order			=	isset($_POST['order'])?$_POST['order']:'1';
			switch($sort_field) {
				case 1: $field_val	=	"j.job_number";
						break;
				case 2: $field_val	=	"j.job_name";
						break;
				default: $field_val	=	"j.job_number";
						 break;
			}
			$order_method		=	($order == 1) ? "DESC":"ASC";
		    if(!empty($_POST['search_jobs'])) {
			    $search_jobs    =   $_POST['search_jobs'];
			    $jobs			=	$cust_n_job->get_jobs_search_result($_POST['search_jobs'], 1, $field_val, $order_method);
				$sales_full_list=   $cust_n_job->get_jobs_search_result($_POST['search_jobs'], 0, $field_val, $order_method);
				$sale_count		=	count($sales_full_list);
				$header 		= 	View::factory('sales/sales_list_header')
											->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
											->set('search_value',$search_jobs)
											->set('tab', '3');
											
			    $this->template->content = 	View::factory('jobs/list')
													->set('jobs', $jobs)
													->set('total_jobs', $sale_count)
													->set('sort_field', $sort_field)
													->set('per_slip', isset($_POST['view_per_page'])?$_POST['view_per_page']:10)
													->set('total_pages', $job->total_job_pages())
													->set('header', $header)
													->set('order', $order)
													->set('filter', '0')
													->set('search_jobs',$search_jobs);
			 }
			else
			{
			    $jobs	=	$job->get_all_jobs();
			    $header 	= 	View::factory('sales/sales_list_header')
											->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
											->set('search_value','')
											->set('tab', '3');
											
			    $this->template->content = 	View::factory('jobs/list')
													->set('jobs', $jobs)
													->set('sort_field', $sort_field)
													->set('total_jobs', $job->total_jobs())
													->set('filter', '0')
													->set('total_pages', $job->total_job_pages())
													->set('order', $order)
													->set('header', $header);
			}
		} catch(Exception $e){ die($e->getMessage());}
	}	
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_create
	 * @Description	:	Function to create jobs
	 */
	public function action_create() {
		$this->template->tab	=	2;
		require Kohana::find_file('classes', 'library/Sync');
		$sync_lib 	= 	new Sync;
		$job		=	new Model_Customerandjobs;
		$job_m		=	new Model_Jobs;	
		$employee_m	=	new Model_Employee;
		$linked_customer_id 	= "";
		$linked_customer_name 	= "";
		$header 	= 	View::factory('sales/sales_list_header')
								->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
								->set('customer_job', '1')
								->set('job', '1')
								->set('title', 'Add Job');	
		if(isset($_POST['submit'])) {
			try { //echo"<pre>";print_r($_POST);die;
				if($_POST['job_number'] == "") throw new Exception("Please enter job number");
				if($_POST['job_name'] == "") throw new Exception("Please enter job name");
				if(!empty($_POST['linked_customer'])) {
					$linked_customer_result	=	$job->get_job_related_customer_info($_POST['linked_customer']);
					if(!empty($linked_customer_result)) {
						$linked_customer_id 	= $linked_customer_result[0]['record_id'];
						if(!empty($linked_customer_result[0]['firstname'])){
							$linked_customer_name 	= $linked_customer_result[0]['firstname'].' '.$linked_customer_result[0]['company_or_lastname'];
						} else {
							$linked_customer_name 	= $linked_customer_result[0]['company_or_lastname'];
						}
					}
				}
				//if($_POST['contact'] == "") throw new Exception("Please enter contact");
				//if($_POST['description'] == "") throw new Exception("Please enter description");
				if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
					$employee_id	=	0;
					$admin_card		=	'1';
				} else {
					$employee_id	=	$_SESSION['employee_id'];
					$admin_card		=	'0';
				}
				$job_columns				=	array('company_id', 'employee_id', 'admin_card', 'contact', 'description', 'is_header_job', 'is_job_inactive', 
													  'job_name', 'job_number', 'sub_job_of', 'start_date', 'finish_date', 'manager', 'track_reimbursables',
													  'linked_customer', 'linked_customer_record_id', 'percent_complete', 'created_date', 'updated_date', 'synced_status', 'status'
												);
				$job_values					=	array( $_SESSION['company_id'], $employee_id, $admin_card, $_POST['contact'], $_POST['description'],	$_POST['is_header'], $_POST['is_inactive'],
													   $_POST['job_name'], $_POST['job_number'], $_POST['sub_job_of'],  $_POST['start_date'], $_POST['finish_date'], $_POST['manager'],
													   $_POST['track_reimbursable'], $linked_customer_name, $linked_customer_id, $_POST['percentage'], date('Y-m-d H:i:s'),
													   date('Y-m-d H:i:s'), '0','1');
				$job_status			=	$job->save("jobs", $job_columns, $job_values);
				Request::instance()->redirect(SITEURL.'/jobs');
			} catch(Exception $e) { 
				$popups_sales        	=	View::factory('sales/sales_popups')
											->set('customers',$job->get_job_related_customers())
											->set('sub_job',$job_m->get_sub_job());

				$this->template->content = 	View::factory('jobs/create')
													->set('header', $header)
													->set('job_name',$_POST['job_name'])
													->set('job_number',$_POST['job_number'])
													->set('description',$_POST['description'])
													->set('percentage',$_POST['percentage'])
													->set('contact',$_POST['contact'])
													->set('add_field','1')
													->set('is_inactive',$_POST['is_inactive'])
													->set('sub_job_of',$_POST['sub_job_of'])
													->set('start_date',$_POST['start_date'])
													->set('finish_date',$_POST['finish_date'])
													->set('manager',$_POST['manager'])
													->set('track_reimbursable',$_POST['track_reimbursable'])
													->set('linked_customer',$_POST['linked_customer'])
													->set('linked_customer_name','')
													->set('error', $e->getMessage())
													->set('is_header',$_POST['is_header'])
													->set('popups_sales',$popups_sales);
												//	echo "<pre>";print_r($_POST);die;
			}
		} else {
		
			$popups_sales        	=	View::factory('sales/sales_popups')
											->set('customers',$job->get_job_related_customers())
											->set('employees', $employee_m->get_all_employee($_SESSION['company_id']))
											->set('sub_job',$job_m->get_sub_job());
			$this->template->content = 	View::factory('jobs/create')
													->set('header', $header)
													->set('job_name','')
													->set('job_number','')
													->set('description','')
													->set('percentage','')
													->set('contact','')
													->set('add_field','1')
													->set('is_inactive','')
													->set('sub_job_of','')
													->set('start_date','')
													->set('finish_date','')
													->set('manager','')
													->set('track_reimbursable','')
													->set('linked_customer','')
													->set('linked_customer_name','')
													->set('is_header','0')
													->set('popups_sales',$popups_sales);
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_edit
	 * @Description	:	Function to edit jobs
	 * @Param		:	int
	 */
	public function action_edit($job_id=0) {
		$this->template->tab	=	2;
		require Kohana::find_file('classes', 'library/Sync');
		$sync_lib 	= 	new Sync;
		$job		=	new Model_Customerandjobs;
		$job_m		=	new Model_Jobs;	
		$employee_m	=	new Model_Employee;
		$linked_customer_id    =  "";
		$linked_customer_name  = "";
		$header 	= 	View::factory('sales/sales_list_header')
								->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
								->set('customer_job', '1')
								->set('job', '1')
								->set('title', 'Edit Job');	
		if(isset($_POST['submit'])) {
			try {//echo "<pre>";print_r($_POST);die;
				if($_POST['job_number'] == "") throw new Exception("Please enter job number");
				if($_POST['job_name'] == "") throw new Exception("Please enter job name");
				//if($_POST['contact'] == "") throw new Exception("Please enter contact");
				//if($_POST['description'] == "") throw new Exception("Please enter description");
				if(!empty($_POST['linked_customer'])) {
					$linked_customer_result	=	$job->get_job_related_customer_info($_POST['linked_customer']);
					if(!empty($linked_customer_result)) {
						$linked_customer_id 	= $linked_customer_result[0]['record_id'];
						if(!empty($linked_customer_result[0]['firstname'])){
							$linked_customer_name 	= $linked_customer_result[0]['firstname'].' '.$linked_customer_result[0]['company_or_lastname'];
						} else {
							$linked_customer_name 	= $linked_customer_result[0]['company_or_lastname'];
						}
					}
				}
			
				$job_data					=	array('contact'	=> $_POST['contact'], 
													  'description'	=> $_POST['description'],
													  'is_header_job'	=> $_POST['is_header'], 
													  'job_name'	=> $_POST['job_name'], 
													  'job_number'	=> $_POST['job_number'], 
													  'is_job_inactive'	=> $_POST['is_inactive'], 
				
													  'percent_complete'	=> $_POST['percentage'],
													  'sub_job_of'	=> $_POST['sub_job_of'], 
													  'start_date'	=> $_POST['start_date'], 
													  'finish_date'	=> $_POST['finish_date'], 
													  'manager'	=> $_POST['manager'], 
													  'track_reimbursables'	=> $_POST['track_reimbursable'], 
												      'linked_customer'	=> $linked_customer_name,  		
													  'linked_customer_record_id' => $linked_customer_id,						
					
													  'updated_date'	=> date('Y-m-d H:i:s'),
													  'synced_status' => '0'
													); 
													  
				$job_status			=	$job->update("jobs", $job_data, $_POST['job_id']);
				Request::instance()->redirect(SITEURL.'/jobs');
			} catch(Exception $e) {die($e->getMessage());
			$popups_sales        	=	View::factory('sales/sales_popups')
											->set('customers',$job->get_job_related_customers())
											->set('employees', $employee_m->get_all_employee($_SESSION['company_id']))
											->set('sub_job',$job_m->get_sub_job($_POST['job_id']));
				$this->template->content = 	View::factory('jobs/create')
													->set('header', $header)
													->set('job_id',$_POST['job_id'])
													->set('job_name',$_POST['job_name'])
													->set('job_number',$_POST['job_number'])
													->set('description',$_POST['description'])
													->set('percentage',$_POST['percentage'])
													->set('contact',$_POST['contact'])
													->set('error', $e->getMessage())
													->set('add_field','0')
													->set('is_inactive',$_POST['is_inactive'])
													->set('sub_job_of',$_POST['sub_job_of'])
													->set('start_date',$_POST['start_date'])
													->set('finish_date',$_POST['finish_date'])
													->set('manager',$_POST['manager'])
													->set('track_reimbursable',$_POST['track_reimbursable'])
													->set('linked_customer',$_POST['linked_customer'])
													->set('is_header',$_POST['is_header'])
													->set('popups_sales',$popups_sales);
			}
		} else {
			$job_info	=	$job->get_jobs($job_id);
			if(empty($job_info))Request::instance()->redirect(SITEURL.'/jobs');
			$popups_sales        	=	View::factory('sales/sales_popups')
											->set('customers',$job->get_job_related_customers())
											->set('employees', $employee_m->get_all_employee($_SESSION['company_id']))
											->set('sub_job',$job_m->get_sub_job($job_info[0]['id']));;
			$this->template->content = 	View::factory('jobs/create')
													->set('header', $header)
													->set('job_id',$job_info[0]['id'])
													->set('job_name',$job_info[0]['job_name'])
													->set('job_number',$job_info[0]['job_number'])
													->set('description',$job_info[0]['description'])
													->set('percentage',$job_info[0]['percent_complete'])
													->set('contact',$job_info[0]['contact'])
													->set('is_header',$job_info[0]['is_header_job'])
													->set('add_field','0')
													->set('is_inactive',$job_info[0]['is_job_inactive'])
													->set('sub_job_of',$job_info[0]['sub_job_of'])
													->set('start_date',$job_info[0]['start_date'])
													->set('finish_date',$job_info[0]['finish_date'])
													->set('manager',$job_info[0]['manager'])
													->set('track_reimbursable',$job_info[0]['track_reimbursables'])
													->set('linked_customer_name',$job_info[0]['linked_customer'])
													->set('linked_customer',$job_info[0]['linked_customer_record_id'])
													->set('popups_sales',$popups_sales);
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_view
	 * @Description	:	Function to view jobs
	 * @Param		:	int
	 */
	public function action_view($job_id) {
		$this->template->tab	=	2;
		require Kohana::find_file('classes', 'library/Sync');
		$sync_lib 	= 	new Sync;
		$job		=	new Model_Customerandjobs;
		$job_info	=	$job->get_jobs($job_id);
		//echo "<pre>";print_r($job_info);die;
		$header 	= 	View::factory('sales/sales_list_header')
								->set('to_be_synced_count', $sync_lib->get_unsynced_sale_count())
								->set('customer_job', '1')
								->set('job', '1')
								->set('title', 'View Job');	
		if(empty($job_info))Request::instance()->redirect(SITEURL.'/jobs');
		$this->template->content = 	View::factory('jobs/view')
											->set('header', $header)
											->set('jobs', $job_info);
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_delete
	 * @Description	:	Function to delete jobs
	 * @Params		:	int,int
	 */
	public function action_delete($job_id, $delete_multiple=0) {
		$job		=	new Model_Customerandjobs;
		$job		=	$job->delete("jobs",$job_id);
		if($delete_multiple) {
			return true;
		} else {
			Request::instance()->redirect(SITEURL.'/jobs');
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_sync
	 * @Description	:	Function to sync one job to Dropbox
	 * @Param		:	int
	 */
	public function action_sync($job_id) {
		try {
		$this->template->tab	=	2;
		$user_model		=	new Model_User;
		try{
			$handshake	=	$user_model->check_handshake_updates();	
		} catch(Exception $e){}
		$job		=	new Model_Customerandjobs;
		$job_info	=	$job->get_jobs($job_id);
		$job_card	=	array();
		
		$job_card[0]['Contact']		=	$job_info[0]['contact'];
		$job_card[0]['Description']	=	$job_info[0]['description'];
		$job_card[0]['IsHeaderJob']	=	($job_info[0]['is_header_job'] == "1")?true:false;
		$job_card[0]['IsJobInactive']	=	($job_info[0]['is_job_inactive'] == "1")?true:false;
		$job_card[0]['JobName']		=	$job_info[0]['job_name'];
		$job_card[0]['JobNumber']	=	$job_info[0]['job_number'];
		$job_card[0]['Manager'] 	= 	$job_info[0]['manager'];
		if($job_info[0]['record_id'] != "0") {
			$job_card[0]['JobRecordID']		=	$job_info[0]['record_id'];
		} else {
			$job_card[0]['ThirdPartyJobID']	=	$job_info[0]['id'];
		}
		if($job_info[0]['start_date'] != "0000-00-00") {
			$start_date	=	explode("-", $job_info[0]['start_date']);
			$job_card[0]['StartDate']['Day']	=	$start_date[2];
			$job_card[0]['StartDate']['Month']	=	$start_date[1];
			$job_card[0]['StartDate']['Year']	=	$start_date[0];
		}
		if($job_info[0]['finish_date'] != "0000-00-00") {
			$finish_date	=	explode("-", $job_info[0]['finish_date']);
			$job_card[0]['FinishDate']['Day']	=	$finish_date[2];
			$job_card[0]['FinishDate']['Month']	=	$finish_date[1];
			$job_card[0]['FinishDate']['Year']	=	$finish_date[0];
		}
		if($job_info[0]['linked_customer_record_id'] != 0){
			$job_card[0]['LinkedCustomer']			=	$job_info[0]['linked_customer'];
			$job_card[0]['LinkedCustomerRecordID'] 	=	$job_info[0]['linked_customer_record_id'];
		}
		
		$job_card[0]['PercentComplete'] 	= 	!empty($job_info[0]['percent_complete']) ? $job_info[0]['percent_complete'] : "0";
		$job_card[0]['SubJobOf'] 	= 	$job_info[0]['sub_job_of'];
		$job_card[0]['TrackReimbursables'] 	= 	($job_info[0]['track_reimbursables'] == "1")?true:false;
		
		$this->json_content		=	json_encode($job_card);
		//die($this->json_content);
		$json_file_name			=	"JobChanges".$job_info[0]['id'].".json";
		$status	=	$this->sync($json_file_name, $this->dropbox_jobs_changes_folder);
		$job_data		=	array("synced_status"	=>	"1");
		$job_status		=	$job->update("jobs", $job_data, $job_info[0]['id']); //mark customer as synced
		//Request::instance()->redirect(SITEURL.'/sales/tobesynced');
		} catch(Exception $e){ die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_sync_all_jobs
	 * @Description	:	Function to sync all jobs to Dropbox
	 */
	public function action_sync_all_jobs() {
		try{ 
		$job		=	new Model_Customerandjobs;
		$job_list	=	$job->get_jobs_to_sync_all();
		$job_card	=	array();
		$total_jobs	=	count($job_list);
		if(!empty($job_list)) {
			for($i=0;$i<$total_jobs;$i++) {
				$start_date	=	array();
				$finish_date=	array();
				$job_card[$i]['Contact']		=	$job_list[$i]['contact'];
				$job_card[$i]['Description']	=	$job_list[$i]['description'];
				$job_card[$i]['IsHeaderJob']	=	($job_list[$i]['is_header_job'] == "1")?true:false;
				$job_card[$i]['IsJobInactive']	=	($job_list[$i]['is_job_inactive'] == "1")?true:false;
				$job_card[$i]['JobName']		=	$job_list[$i]['job_name'];
				$job_card[$i]['JobNumber']	=	$job_list[$i]['job_number'];
				$job_card[$i]['Manager'] 	= 	$job_list[$i]['manager'];
				if($job_list[$i]['record_id'] != "0") {
					$job_card[$i]['JobRecordID']		=	$job_list[$i]['record_id'];
				} else {
					$job_card[$i]['ThirdPartyJobID']	=	$job_list[$i]['id'];
				}
				if($job_list[$i]['start_date'] != "0000-00-00") {
					$start_date	=	explode("-", $job_list[$i]['start_date']);
					$job_card[$i]['StartDate']['Day']	=	$start_date[2];
					$job_card[$i]['StartDate']['Month']	=	$start_date[1];
					$job_card[$i]['StartDate']['Year']	=	$start_date[0];
				}
				if($job_list[$i]['finish_date'] != "0000-00-00") {
					$finish_date	=	explode("-", $job_list[$i]['finish_date']);
					$job_card[$i]['FinishDate']['Day']	=	$finish_date[2];
					$job_card[$i]['FinishDate']['Month']	=	$finish_date[1];
					$job_card[$i]['FinishDate']['Year']	=	$finish_date[0];
				}
				$job_card[$i]['LinkedCustomer']	=	$job_list[$i]['linked_customer'];
				$job_card[$i]['LinkedCustomerRecordID'] 	= 	$job_list[$i]['linked_customer_record_id'];
				$job_card[$i]['PercentComplete'] 	= 	$job_list[$i]['percent_complete'];
				$job_card[$i]['SubJobOf'] 	= 	$job_list[$i]['sub_job_of'];
				$job_card[$i]['TrackReimbursables'] 	= 	($job_list[$i]['track_reimbursables'] == "1")?true:false;
				$job_data		=	array("synced_status"	=>	"1");
				$job_status		=	$job->update("jobs", $job_data, $job_list[$i]['id']); //mark customer as synced
			}
			$this->json_content		=	json_encode($job_card);
			$json_file_name			=	"JobChanges".$job_list[0]['id'].".json";
			$status	=	$this->sync($json_file_name, $this->dropbox_jobs_changes_folder);
		}
		} catch(Exception $e) { die($e->getMessage()); }
		//	Request::instance()->redirect(SITEURL.'/jobs');
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
		} catch(Exception $e) {//die($e->getMessage()."-".$e->getFile()."--".$e->getLine());
			$folder_status	=	$dropbox->create_folder($dropbox_folder_path); // Create third party folder in dropbox
			$upload_status	=	$dropbox->upload_file($local_folder_path, $dropbox_folder_path); // Upload file to dropbox
		}
		unlink($local_folder_path);
		$handshake_status	=	$user->check_handshake_updates();
		return true;
	}

	/**
	 * @Access		:	public
	 * @Function	:	action_deletejobs
	 * @Description	:	Function to delete multiple customers	 
	 */ 
	public function action_deletejobs() {
		if(empty($_POST['job_id'])) Request::instance()->redirect(SITEURL.'/jobs');
		$job_id_count	=	count($_POST['job_id']);
		if($job_id_count == 0)	Request::instance()->redirect(SITEURL.'/jobs');
		foreach($_POST['job_id'] as $job_id) {
			$delete_status	=	$this->action_delete($job_id, 1);
		}
		Request::instance()->redirect(SITEURL.'/jobs');
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_ajax_pagination
	 * @Description	:	Pagination in ajax call
	 * @Params		:	none
	 */
	public function action_ajax_pagination() { 
		try {
		$jobs			=	new Model_Customerandjobs;
		$page			=	$_POST['page'];
		$rows_per_page	=	$_POST['rows_per_page'];
		$start			=	(($page*$rows_per_page)-$rows_per_page);
		$end			=	$rows_per_page;
		$sort_field		=	isset($_POST['sort_field'])?$_POST['sort_field']:1;
		$order			=	isset($_POST['order'])?$_POST['order']:1;
		$search_jobs 	=  	isset($_POST['search_jobs'])?$_POST['search_jobs']:'000';
		if(isset($_POST['sort_field'])) {
			switch($sort_field) {
				case 1: $field_val	=	"j.job_number";
						break;
				case 2: $field_val	=	"j.job_name";
						break;
				case 3: $field_val	=	"j.is_header_job";
						break;
				default: $field_val	=	"j.created_date";
						 break;
			}
			$order_method			=	($order == 0) ? "DESC":"ASC";
		}
		if($search_jobs=='000'){
			$jobs			=	$jobs->paginated_jobs($start, $end,$field_val,$order_method);
		} else{
			$jobs			=	$jobs->get_jobs_search_result($_POST['search_jobs'], 2, $field_val, $order_method);
		}
		$paginated_view = 	View::factory('jobs/page_view')
		                                    ->set('sort_field', $sort_field)
		                                    ->set('order', $order)
		                                    ->set('search_value',isset($_POST['search_jobs'])?$_POST['search_jobs']:'')
											->set('jobs', $jobs);
		die($paginated_view); 
		} catch(Exception $e) {die($e->getMessage());}
	}
}
