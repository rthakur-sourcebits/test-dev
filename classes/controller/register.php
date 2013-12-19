<?php
/**
 * 
 * @author Shijith M
 * Copyright (c) 2011 Acclivity Group LLC
 */
class Controller_Register extends Controller_Admintemplate
{
	/**
	 * @Access		:	Public
	 * @Function	:	action_register
	 * @Description	:	submit registration form
	 */
	public function action_index()
	{
		unset($_SESSION['signup_inputs']);
		$xmlrpc	=	new Model_Xmlrpc;
		try {
		$plan	=	$xmlrpc->get_signup_plans();
		} catch(Exception $e) {die($e->getMessage());}	
		$this->template->content	= 	View::factory('register/signup')
												->set("payment_stream", $plan);	
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_submit
	 * @Description	:	Processing of submit form.
	 */
	public function action_submit()
	{
		$inputs			=	$_POST;
		$xmlrpc			=	new Model_Xmlrpc;
		$objRegister	=	new Model_Register;
		$admin_model	=	new Model_Admin;
		$payment_plan	=	$xmlrpc->pay_method($_POST['plan']);
		$return			=	array();
		try {
			if($_POST['plan'] == $admin_model->get_free_plan_id()) 
			{
				$admin_model				=	new Model_Admin;
				$admin_model->submit_company_info($inputs);
				$return[0]['success']	=	1;
				$return[0]['free']		=	1;
			}
			else {
				$result			=	$xmlrpc->payment_form();
				if(isset($result['response_status']) && $result['response_status'] == 1 || $result['response_status'] == 2 ) 
				{
					if(isset($result['response_new_referance_id'])) {
						$inputs["token_id"]			=	$result['response_new_referance_id'];
					} else {
						$inputs["token_id"]			=	$result['response_referance_id'];
					}
					$objRegister->save_company_info($inputs);
					$return[0]['success']	=	1;
					$return[0]['free']		=	0;
					$return[0]['result']	=	$result;
				} else {
					$return[0]['error']	=	1;
					$return[0]['error_message']	=	$result['response_description'];
				}
			}
		}
		catch(Exception $e) {
			$return[0]['error']	=	1;
			$return[0]['error_message']	=	$e->getMessage();
		}		
		echo json_encode($return); 
		die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_confirmation
	 * @Description	:	Confirmation after submitting the tokens.	
	 */
	public function action_confirmation($ref_id)
	{
		$objRegister	=	new Model_Register;
		$xmlrpc			=	new Model_Xmlrpc;
		$company		=	$objRegister->get_company_by_token($ref_id);
		$company[0]['cardinfo']	=	$xmlrpc->get_card_details($ref_id);
		if(empty($company)) request::instance()->redirect(SITEURL.'/admin');
		$this->template->content	= 	View::factory('register/success')
													->set('company', $company);
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_freeuserconfirmation
	 * @Description	:	Free user confirmation function.
	 */
	public function action_freeuserconfirmation($id)
	{
		$objRegister	=	new Model_Register;
		$company		=	$objRegister->get_company_by_active_id($id);
		
		if(empty($company)) request::instance()->redirect(SITEURL.'/admin');
		$this->template->content	= 	View::factory('register/success')
													->set('company', $company);
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_getplanId
	 * @Description	:	Function to fetch the plan id.
	 */
	public function action_getplanId()
	{
	    $objRegister	=	new Model_Register;
	    $company		=	$objRegister->get_planId();
	    die(json_encode($company));
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_confirmation_upgrade
	 * @Description	:	Confirming the upgradation of plan.
	 */
	public function action_confirmation_upgrade($id)
	{ //die("");
		try {
		$objRegister	=	new Model_Register;
		$xmlrpc			=	new Model_Xmlrpc;
		$company		=	$objRegister->get_company_by_upgrade_token($id);
		$company[0]['cardinfo']	=	$xmlrpc->get_card_details($id, 1);
		if(empty($company)) request::instance()->redirect(SITEURL.'/admin');
		$this->template->content	= 	View::factory('register/success')
													->set('company', $company)
													->set('upgrade', 1);
		} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_sucessregister
	 * @Description	:	Displaying the success msg after success.
	 */
	public function action_sucessregister(){ 
		$this->template->content =	View::factory('register/register_success')
		                        ->set('email', $_POST['email']);
	}

	
}
?>