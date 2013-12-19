<?php defined('SYSPATH') or die('No direct script access.');
ini_set("memory_limit", "100M");
/**
 * @File 		: activitysheet.php Controller
 * @Class		: Controller_Activitysheet
 * @Created Date: 30/Aug/2010
 * @Modified	: 23/Dec/2010
 * @Description	: Read the activity logs of the user and display each.
 * Copyright (c) 2011 Acclivity Group LLC 
*/
class Controller_Activitysheet extends Controller_Template 
{

	public $template = 'template';
    public $session  = '';
    
    /**
	 * @Method: __construct 
	 * @Description: This method calls the validation session and checks user is loged in or not
	 * 				 if the user is not logged in he will redirected to login page. 
	 */
	public function __construct(Request $request)
	{
		parent::__construct($request);
		$validations	= 	new Model_validations;
		//echo "im in constructor";die;
		$validations->check_user_session();	
	}
	
	
	/**
	 * 	@Function 	 :	action_index
	 * 	@Descritpion : 	Get user activity list from model and pass to the view file.
	 */
	public function action_index()
	{  
		$this->template->title	= 	"Dharma Activity Sheet";
		$activity_sheet			= 	new Model_Activitysheet;
		$obj_user				=	new Model_User;
		$data_model             =   new Model_Data;
		$employee_m				=	new Model_Employee;
		$dharmausers_m			=	new Model_Dharmausers;
		
		unset($_SESSION['week_start_date']);
		$activity_list['activity_list']                 =	$activity_sheet->read_activity_log(1);
		$activity_list['unsynced_count']                =	$activity_sheet->get_unsynced_count();
		$activity_full_list								=	$activity_sheet->read_activity_log(0);
		$activity_count 								=	count($activity_full_list);
        //$activity_count								=	$activity_sheet->count_activity_log($_SESSION['employee_id'],$_SESSION['company_id']);
		try {
		     if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
                    $this->template->content = View::factory('activity/list',$activity_list)
                                            ->set('username', $obj_user->get_logged_user_name())
                                            ->set('employee_list', $employee_m->get_all_employees())
                                            ->set('count_slips', $activity_count)
                                            ->set('per_slip', isset($_POST['view_per_page'])?$_POST['view_per_page']:10)
                                            ->set('page', isset($_POST['page'])?$_POST['page']:0);
             }else{
                    $this->template->content = View::factory('activity/list',$activity_list)
                                            ->set('username', $obj_user->get_logged_user_name())
                                            ->set('count_slips', $activity_count)
                                            ->set('per_slip', isset($_POST['view_per_page'])?$_POST['view_per_page']:10)
                                            ->set('page', isset($_POST['page'])?$_POST['page']:0);
             }
	    } catch(Exception $e) {}
	}
	
	/**
	 * @Function	:	action_search
	 * @Description	:	get from and to date from the user, do the search and get the result according to the date
	 */
	public function action_search()
	{
		$this->template->title				=	"Dharma Activity Sheet";
		$from_date							=	$_POST['from_date'];
		$to_date							=	$_POST['to_date'];
		$activity_sheet						=	new Model_Activitysheet;
		$activity_list['activity_search'] 	=	1;
		
		try 
		{ 
			$activity_list['activity_list']		=	$activity_sheet->date_search_activity($from_date, $to_date);
			$this->template->content			= 	View::factory('activity/list',$activity_list);
		}
		catch(Exception $e)
		{ 
			$activity_list['error']				=	$e->getMessage();
			$activity_list['activity_list']		=	$activity_sheet->read_activity_log();
			$this->template->content			= 	View::factory('activity/list',$activity_list);
		}
	}
	
	/**
	 * @Function 	: action_view
	 * @Description : Display activity slip in detail.
	 * @param		: activity auto id
	 * @return		: Array contains activity information 
	 */
	public function action_view($id = '')
	{
		$this->template->title	=	"Dharma Activity Sheet";
		$this->template->flag	= 	'slip';
		$slip_id				=	addslashes(htmlentities(trim($id)));
		$activity_sheet			=	new Model_Activitysheet;
		$data_model				=	new Model_data;
		$dharmausers_m			= 	new Model_Dharmausers;	
		unset($_SESSION['week_start_date']); // destroy time sheet start week session if exists, current week should be default in timesheet
		try{
			$slip_data						=	$activity_sheet->get_activity_slip_details($slip_id);
		}catch(Exception $e) {die($e->getMessage());}	
		$activity_details['details']		=	$slip_data['content'];
		$activity_details['payroll_flag']	=	$dharmausers_m->get_payroll_flag();
		$activity_details['next']			=	$slip_data['next'];
		$activity_details['prev']			=	$slip_data['prev'];
		
		try 
		{
			if (empty($activity_details['details'])) 
			{
				$activity_list['error']			=	"Invalid slip id.";
				$activity_list['activity_list']	=	$activity_sheet->read_activity_log();
				$this->template->content		= 	View::factory('activity/list',$activity_list);
			}
			else 
			{				
				$activity_details['slip_id']			=	$id;
				if(isset($activity_details['details']['ActivitySync']['RecordID']))
				{
					$activity_details['sync_flag']		= 	$activity_details['details']['ActivitySync']['RecordID'];
				}
				
				$this->template->content 		= 	View::factory('activity/view',$activity_details);
			}
		} 
		
		catch(Exception $e)
		{
			$activity_list['error']				=	$e->getMessage();//die;
			$activity_list['activity_list']		=	$activity_sheet->read_activity_log();
			$this->template->content			= 	View::factory('activity/list',$activity_list);
		}
	}

	 /**
	 * 	@Function 	 : action_sync
	 * 	@Description : Sync selected or all activities to dropbox.
	 */
	public function action_sync($edit_flag=0)
	{ 
		$this->template->title		=	"Dharma Activity Sheet";
		$activity_sheet				=	new Model_Activitysheet;
		$obj_user					=	new Model_User;
		$handshake_list				=	$activity_sheet->check_handshake_updates();
		$result	=	array();
		try
		{ 
			$activity_sheet->sync_activity_slips();
			if (isset($_POST['sync'])) 			// check if user clicked on sync
			{		
				$activity_list['success_message']	=	"Selected activities has beed synced.";
			}
			elseif (isset($_POST['syncall'])) 	// user clicked on syncall
			{ 	
				$activity_list['success_message']	=	"All activities of this user has beed synced.";
				Request::instance()->redirect(SITEURL.'/activitysheet?msg=3');
			}
			$result[0]['error']	=	0;
		}
		catch (Exception $e)
		{	$activity_list['error']				=	$e->getMessage();
			$activity_list['activity_list']		=	$activity_sheet->read_activity_log();
			
			$result[0]['error']			=	1;
			$result[0]['description']	=	$e->getMessage();
		}
	
		if($edit_flag) {
			Request::instance()->redirect(SITEURL.'/activitysheet');
		} else {
			die(json_encode($result));
		}
		
	}
	 
	/**
	 * @Method: action_ajax_pagination
	 * @Description: This method is used to get the list of records in pagination through ajax call.
	 */
	public function action_ajax_pagination() { 
		try {
			$activity_sheet	=	new Model_Activitysheet;
			$page			=	$_POST['page'];
			$rows_per_page	=	$_POST['rows_per_page'];
			$start			=	(($page*$rows_per_page)-$rows_per_page);
			$end			=	$rows_per_page;
			$slips			=	$activity_sheet->paginated_slips($start, $end);
			$paginated_view = 	View::factory('activity/page_view')
												->set('activity_list', $slips);
			die($paginated_view); 
		} catch(Exception $e) {die($e->getMessage());}
	}
	
	/*
	 * @Method: action_selected_employee
	 * @Description: This method is used to store the selected employee in session variable.
	 */
	public function action_selected_employee(){
		try{
			if(isset($_POST['selected_employee']) && $_POST['selected_employee']!=''){
				$split_name								=	explode(' ',$_POST['selected_employee']);
				$_SESSION['selected_employee_name']		= 	isset($split_name[0])?$split_name[0]:'';
				$_SESSION['selected_employee_lastname']	=	isset($split_name[1])?$split_name[1]:'';
				$_SESSION['selected_emp_id']			=	$_POST['employee_id'];
				$result[0]['error']						=	0;
				$result[0]['description']				=	'success';
			}
		}catch(Exception $e){
			$result[0]['error']			=	1;
			$result[0]['description']	=	$e->getMessage();
		}
		die(json_encode($result));
	}
	
	/**
	 * @Access:	Public
	 * @Method: action_ajax_slipnumber
	 * @Description: This method is used to generate a slip number through ajax call when the employee is changed in the activitysheet creation pages.
	 */
	public function action_get_show_jobs(){
		$activity_model		=	new	Model_activity;
		$data_model			=	new Model_data;
		$dharmausers_m		=	new Model_Dharmausers;
		
		try{	
			if(isset($_POST['employee_id']) && $_POST['employee_id']!='NULL'){
				$response	=	$dharmausers_m->get_show_jobs($_POST['employee_id']);
				if(!empty($response)){
					$result[0]['show_jobs']		=	$response;
					$result[0]['error']			=	0;
					$result[0]['description']	=	'success';
					
					
				}else{
					$result[0]['show_jobs']		=	0;
					$result[0]['error']			=	1;
					$result[0]['description']	=	'Failure';
				}
			}
		}catch(Exception $e){
			$result[0]['show_jobs']		=	0;
			$result[0]['error']			=	1;
			$result[0]['description']	=	$e->getMessage();
		}
		die(json_encode($result));
	}
	
	public function action_unset_employee(){
		try{
			if(isset($_POST['clear']) && $_POST['clear']=='1'){
				if(isset($_SESSION['selected_emp_id'])){
					unset($_SESSION['selected_employee_name']);
					unset($_SESSION['selected_employee_lastname']);
					unset($_SESSION['selected_emp_id']);
				}
				$result[0]['error']			=	0;
				$result[0]['description']	=	'success';
			}
		}catch(Exception $e){
			$result[0]['error']			=	1;
			$result[0]['description']	=	$e->getMessage();
		}
		die(json_encode($result));
	}
}