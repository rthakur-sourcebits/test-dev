<?php 
defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Updatepayment extends Controller_Admintemplate
{

	
	public function __construct()
	{
		include_once DOCUMENT_ROOT.'/application/classes/vendors/xmlrpc/lib/xmlrpc.inc';
		include_once DOCUMENT_ROOT.'/application/classes/vendors/xmlrpc/lib/xmlrpcs.inc';
        include_once DOCUMENT_ROOT.'/application/classes/vendors/xmlrpc/lib/xmlrpc_wrappers.inc';
    }	
	
	public function action_index()
 	{	

 		$this->auto_render = false;
		
		if(isset($_POST) && count($_POST)>0 && $_POST['pmid'] != '') {
		
			
			$payment_token  = $_POST['pmid'];
			$referance_id   = $_POST['refid']; // reference_id 
			
		 	$message = new xmlrpcmsg('sample.update_payment_token',
										array(new xmlrpcval('3849818483be4ea523e61181fd7db3f8', 'string'), //api_key
										 	  new xmlrpcval($payment_token, 'int'),
										 	   new xmlrpcval($referance_id, 'string'))
										);
			
			$c = new xmlrpc_client("/server", 'www.devrerunapp.com', 80);
			$c->return_type = 'phpvals'; // let client give us back php values instead of xmlrpcvals
			//$c->setDebug(1);	
			$result =& $c->send($message);
			$struct = $result->value();
			if($struct[0]['response_status'] == 0)
			{	
				echo "<p>error '".htmlspecialchars($result->faultString())."'</p>\n";die;
			}
			else if($struct[0]['response_status'] == 2){
				echo "<script type='text/javascript'>window.parent.loadResultPage('".$struct[0]['response_url']."','".$struct[0]['response_description']."');</script>";die;
			}
			else
			{
				if($struct[0]['response_status'] == 1 ) {
					try {
						$objRegister		=	new Model_Register;
					//	$_SESSION['signup_inputs']['token_id']	=	$struct[0]['response_referance_id'];
						$response_ref_id	=		$struct[0];//$struct[0]['response_referance_id'];
						//check for reactive here
						if(isset($response_ref_id['response_new_referance_id'])) {
							$ref_id		=	$response_ref_id['response_new_referance_id'];
							//$new_ref_id	=	$response_ref_id['response_new_referance_id'];
						} else {
							$ref_id		=	$response_ref_id['response_referance_id'];
						//	$new_ref_id	=	$response_ref_id['response_referance_id'];
						}
						$active_status		=		$objRegister->active_user($response_ref_id);
					} catch(Exception $e) {
						die($e->getMessage());
					}
					if($active_status == 1) {
						$signup_confirm_page	=	SITEURL."/register/confirmation/".$ref_id;
						/*echo "<div style='font-weight:bold;font-size:11px;font-family:arial;color: #4598E0'>Thank you for siging up with Time Tracker.
								<p>We appreciate your interest. Please check the email address you provided for instructions on how to log in.</p></div>";die;*/
						echo "<script type='text/javascript'>window.top.location.href = '".$signup_confirm_page."';</script>";
					} elseif($active_status == 2) {
						//echo "<div style='font-weight:bold;font-size:11px;font-family:arial;color: #4598E0'>Thank you for siging up with Time Tracker. You are successfully upgraded your plan. Please check the email address you provided for instructions on how to log in</p></div>";die;
						//$signup_confirm_page	=	SITEURL."/register/confirmation_upgrade/".$ref_id;
						//$signup_confirm_page	=	SITEURL."/admin";
						$signup_confirm_page	=	SITEURL."/admin/changeplan/2";
						echo "<script type='text/javascript'>window.top.location.href = '".$signup_confirm_page."';</script>";
					}
					
				} else {
					echo "<h1>".$struct[0]['response_description']."</h1>";die;
				}		 	
			}			
		}
 	}	
}