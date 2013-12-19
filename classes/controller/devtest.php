<?php defined('SYSPATH') or die('No direct script access.');
ini_set("memory_limit", "100M");

	/**
	 * 
	 */
	class Controller_Devtest extends Controller_Template{
		
		public function __construct(Request $request)
		{
			parent::__construct($request);
			$this -> testJSencryption();
			die;
		}
		
		private function testJSencryption(){
			echo "testJSencryption";
			try {				 
			echo View::factory('sandbox/testJSencryption');											
			} catch(Exception $e) {die($e->getMessage());}
		}
	}
?>