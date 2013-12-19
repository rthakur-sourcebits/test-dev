<?php
/**
 * @File 		:	json.php
 * @Description :	JSON functions including json select query, insert and update.
 * @Created date:	17 Sep 2010
 */ 
class Model_Json extends Model  {

	private	$json_array;
	private	$fpath;
	public $file_content;
	public $files_list = array();
	/**
	 * @Function	:	JSON_Query
	 * @Description	:	JSON select query
	 * @param1 		: $where-> where condition
	 */
	public function JSON_Query($select = array(), $where = array(), $multiple = null)
	{
		$where_count 	=	count($where);
		$true		 	=	0;
		$return			=	array();
		$j				=  	0;
		foreach($this->file_content as $file)
		{
			$result		=	array();
			if ($where_count != 0)
			{
				foreach($where as $key=>$val)
				{ 
					if (isset($file[$key]) && (($val == $file[$key]) || (strtolower($val) == strtolower($file[$key]))))
					{ 
						$result[]	=	1;
					} 
					else 
					{
						$result[]	=	0;
					}
				}
			} 
			else
			{
				$result[]	=	1;
			}
			
			if (array_sum($result) >= $where_count)
			{		
				if ($select[0] == "*") 
				{
					if($multiple)
					return $file;
					
					$return[$j]	=	$file;
					$true		=	1;
				} 
				else
				{
					foreach($select as $key_field=>$val_field)
					{
						$return[$j][$val_field]	=	$file[$val_field];
					}
					$true	=	1;
				}
			}
			$j++;
		}
		
		if($true) 
		{ 
			return $return;
		} 
		else 
		{
			return false;
		}
	}
	
	/**
	 * @Function	:	insert_array.
	 * @Description	:	clears the id fields in the array set and calls the upload file.
	 * @param1 		:	$topath - the path of dropbox where the file should be uploaded.
	 * @param2 		:	$temp_path - the path where the temp file resides.					 
	 */
	function insert_array($topath, $temp_path, $admin=""){
		
		$arr_emp_info	=	$this->file_content;
		if($arr_emp_info != "")
		{
			$arr_emp_info 	=	$this->unset_element($arr_emp_info, 'id');
		}
		$this->update_file($temp_path, $arr_emp_info, $topath, $admin);
	}
	
	/**
	 * @Function	:	update_array.
	 * @Description	:	this function checks for the id and update that array set, with the new array set.
	 * @param1 		:	$data - whole array set.
	 * @param2 		:	$select - elements that are required.	
	 * @param3 		:	$search - search query.					 
	 */
	function update_array($data, $select, $search){
		
		$search_id		=	$this->JSON_Query($select,$search);
		$row			=	'';
		$arr_emp_info	=	$this->file_content;
		$flag			=	false;
		foreach ($search_id as $key=>$array)
		{
			$row		=	$key;
			$flag		=	true;
		}
	//	echo "row is ".$row;die;
		if ($flag)
		{	   		
	   		$arr_emp_info[$row]	=	$data;
	   	}
	   	return $arr_emp_info;
	}
		
	/**
	 * @Function	:	update_file.
	 * @Description	:	this function creates the temp file in cache folder and calls the upload function to uploaded the created temp file.
	 * @param1 		:	$file_path- path of temp file.	
	 * @param2 		:	$data - new json string that is updated.
	 * @param3 		:	$topath - path of dropbox.					 
	 */
	public function update_file($file_path, $data_slip, $topath, $admin=""){
		
		/* wrtting file to temprary path*/
		$ourFileName	=	$file_path;
		$data			=	array();
		if(!empty($data_slip)) {
			foreach($data_slip as $slip){
				unset($slip['RecordID']);
				$data[]	=	$slip;
			}
		}
		if(!empty($data))
		{
			$data_string 	=   json_encode($data);	//echo $data_string;die;
		}
		else
		{
			$data_string	=	null;//die("null");
		}
		//echo "data ".$data_string;die;
		$dropbox		=   new Model_dropbox();
		
		if ($handle = fopen($ourFileName, 'w'))
		{
			if (is_writable($ourFileName))
			{
				if (fwrite($handle, $data_string) === FALSE)
				{
					//echo "Cannot write to file $ourFileName";
				}
				//echo "The file $ourFileName was created and written successfully!";
				fclose($handle);
			}
			else
			{
				//echo "The file $ourFileName, could not written to!";				
			}
		}
		else
		{
			//echo "The file $ourFileName, could not be created!";
		}
		
		
		/* uploading file to dropbox */
		try{
			return $dropbox->upload_file($ourFileName, $topath, $admin);
		}
		catch(Exception $e){
			throw new Exception($e->getMessage());
			return;
		}
	}
	
	/**
	 * @Function 	:	filter_json_array
	 * @Description :	reads all the json and add unique id to it.
	 */
	public function filter_json_array()
	{
		$data	=	new Model_Data;
		$arr_sales_files	=	array("Preference", "SalesAndPurchase", "Employees", "Vendors", "Activities", "Customers", "Jobs", "Items", "Accounts",
									  "Taxes", "ConsolidatedTaxes", "CustomListAndFieldNames", "CustomLists");
		// this is implicitly containing json data 
		// pre call : create json session.
		foreach($this->files_list as $key=>$list) {
			
			if($key == "ActivitySlips")
			{
				$i	=	$this->last_slips_autoid();
			}
			else {
				$i	=	1;
			}
			$new_json_array			=	array();
			if(!empty($list) && $key != "Preference"){
				foreach($list as $rows) 
				{
					if($key == "ActivitySlips")
					{
						$rows['RecordID'] = $i;
					}
					$rows['id'] 		=	$i;
					$new_json_array[] 	=	$this->decode_utf8($rows);
					$i++;
				}
			} else{
				$new_json_array=$list; 
			}
			if(in_array($key, $arr_sales_files)) {
					$stats	=	$data->update_aed_files($key, $new_json_array);
			} else {
				$_SESSION[$key]			=	$new_json_array;
			}
		}
		return true;
		
	}
	
	/**
	 * Convert all names to html entities
	 *
	 * @param unknown_type $array
	 */
	private function decode_utf8($arr_json)
	{
		$name_keys	=	array("CompanyOrLastName", "FirstName", "JobName", "JobNumber",
							  "ActivityID", "ActivityName"
							 );
		
		foreach($arr_json as $key=>$value) {
			if(in_array($key, $name_keys)) {
				$arr_json[$key]		=	utf8_decode($value);
			}
		}
		return $arr_json;
	}
	
	/**
	 * Function to handle spanish charectors
	 *
	 * @param unknown_type $input
	 * @return unknown
	 */
	private function handleSpanishCharacters($input)
	{
	 
		$translate = array();
		$translate['�'] = "&#193;";
		$translate['�'] = "&#201;";
		$translate['�'] = "&#205;";
		$translate['�'] = "&#211;";
		$translate['�'] = "&#218;";
		$translate['�'] = "&#209;";
		$translate['�'] = "&#220;";
		$translate['�'] = "&#225;";
		$translate['�'] = "&#237;";
		$translate['�'] = "&#233;";
		$translate['�'] = "&#243;";
		$translate['�'] = "&#250;";
		$translate['�'] = "&#241;";
		$translate['�'] = "&#252;";
		$translate['�'] = "&#191;";
		$translate['�'] = "&#161;";
		$translate['�'] = "&#171;";
		$translate['�'] = "&#187;";
		$translate['�'] = "&#128;";
	 
		$search = array_keys($translate);
		$replace = array_values($translate);
		return str_replace($search,$replace,$input);
	 
	}
	private function last_slips_autoid()
	{
		$sql	=	"SELECT MAX(RecordID) as lastid
					 FROM activities_sync
					 WHERE company_id = '".$_SESSION['company_id']."'";
		$query	=	DB::query(Database::SELECT, $sql);
		$data	=	$query->execute()->as_array();
		return $data[0]['lastid']+1;
	}
	/**
	 * @Function : unset_element
	 * @Description : removes a element from the array set.
	 * @param1 		:	$arrays - array from which element should be removed.	
	 * @param2 		:	$element - elements that should be removed from the array. 
	 */
	public function unset_element($arrays, $element)
	{
		$count_array	=	count($arrays);
		for($i=0;	$i<$count_array;	$i++)
		{
			if(!empty($arrays[$i][$element]))
				unset($arrays[$i][$element]);
		}
		return $arrays;
	}
		
	/**
	 * @Function 	: 	join
	 * @Description : 	function join is used to join two or more arrays based on the join condition.
	 * @param1 		:	$first - main array.	
	 * @param2 		:	$second - sub array that is to be joined with main array. 
	 */
	public function join($first, $second) 
	{
		$joined_array 	= 	array();
		$count_second	=	count($second);
	
		for($j=0; $j<$count_second; $j++)		// $second is the multiple arrays that are to be joined.
		{
			if($j==0){
				$joined_array =  $this->multiple_joins($j,$first, $second);
			}else{
				
				$joined_array =  $this->multiple_joins($j,$joined_array, $second);
			}
			
		}
		
		return $joined_array;
	}
	
	/**
	 * @Function 	: 	multiple_joins
	 * @Description : 	function that handles the join iterators.
	 * 					$second[$j][0] => merge the array based on.
	 *  				$second[$j][1] => array content that is to be merged.
	 *  				$second[$j][2] => name of the merged array.
	 * @param1 		:	$j - number of iterator array.	  
	 * @param2 		:	$first - main array.	
	 * @param3 		:	$second - sub array that is to be joined with main array. 
	 */
	public function multiple_joins($j,$first, $second)
	{
		$merged_array	=	array();
		$json 			=	new Model_json;
		if(empty($first))
		{
			return false;
		}
		$first			=	array_merge(array(),$first);	
		$count_first	=	count($first);
		for ($i=0; $i<$count_first;$i++)
		{
							
			// get array set from the secondary array, based on the $row value of the first and then merge the arrays.
			$json->file_content = $second[$j][1];
			
			if (isset($first[$i][$second[$j][0]]))
			{
				$search_string = $first[$i][$second[$j][0]];
			}
			else
			{
				$search_string = '';
			}
			$search_id		=	$json->JSON_Query('*',array($second[$j][0] => $search_string ));	
			
			if ($search_id)	// if condition is match array set contains the matched array.
			{
				$set =  array_merge(array(),$search_id);
				$array_search[$second[$j][2]]=$set['0'];
				$result = array_merge($first[$i],$array_search);
				array_push($merged_array, $result);
			}
			else			// No matches found.
			{			
				$result = $first[$i];
				array_push($merged_array, $result );
			}		
		}				
		return $merged_array;
	}
	
	/**
	 * @Function 	: 	between
	 * @Description : 	function get the array that contains the between the range values.
	 * 					date formate should have the month, day, year.
	 * @param1 		:	$array - array  that is filter based on the date.	
	 * @param2 		:	$field - element name in the array. 
	 * @param3 		:	$type - integer or data (presently we have for there two types). 
	 * @param4 		:	$min - minimum value.
	 * @param5 		:	$max - maximum value.
	 */
	public function between($array, $field, $type, $min, $max )
	{
		$between_array	=	array();
		if(!empty($array))
		{
			foreach ($array as $row)
			{
				if ($row[$field])
				{
					if ($type == 'date')
					{
						$date 			=	$row[$field];
						$compare_str	=	mktime('0','0','0',$date['Month'],$date['Day'],$date['Year']);
					}
					else
					{
						$compare_str	=	$row[$field];
					}
						
					if ($compare_str >= $min AND  $compare_str <= $max)
					{
						array_push($between_array, $row);
					}
				}
			}
	   }
	   return $between_array;
	}
	
   /**
	* @Function 	:	group_by
	* @Description	:	Group the arrays by the fields.
	* @param1 		:	Array source content
	* @param2 		:	Array containing the fields to be grouped.
	* @return 		:	Grouped array
	*/
	public function group_by($arr_content = array(), $arr_group_fields)
	{
		$this->file_content	=	$arr_content;
		$count_content	=	count($arr_content);
		$count_group	=	count($arr_group_fields);
		$match			=	array();
		$arr_id			=	array();
		$k				=	0;
		foreach($arr_content as $content) 
		{
			$arr_id[]	=	$content['id'];
			$return 	=	1;
			$time_first	=	mktime(0, 0, 0, $content['SlipDate']['Month'], $content['SlipDate']['Day'], $content['SlipDate']['Year']);
			for($i=$k;$i<$count_content;$i++)
			{
				$true_match	=	array();
				$time_scnd	=	mktime(0, 0, 0,$arr_content[$i]['SlipDate']['Month'], $arr_content[$i]['SlipDate']['Day'], $arr_content[$i]['SlipDate']['Year']);
				for($j=0;$j<$count_group;$j++) 
				{
					if(($content[$arr_group_fields[$j]] == $arr_content[$i][$arr_group_fields[$j]]) && $content['id'] != $arr_content[$i]['id'] && ($time_first != $time_scnd)) 
					{
						$true_match[]	=	1;
					}
					else
					{
						$true_match[]	=	0;
					}
				}
				if(array_sum($true_match) >= $count_group) 
				{
					$match[]	=	$arr_content[$i]['id'];
				}
			}
			$k++;
		}
		$arr_key_id			=	array_merge(array(), array_diff($arr_id,array_unique($match)));
		$count_key_id		=	count($arr_key_id);	
		$arr_group_activity	=	array();
		for($i=0;$i<$count_key_id;$i++) 
		{
			array_push($arr_group_activity,$this->JSON_Query(array("*"), array("id"=>$arr_key_id[$i]), 1));
		}
		$len_list	=	count($arr_group_activity);
		for($i=0;$i<$len_list-1;$i++)
		{
			$time_first	=	mktime(0, 0, 0, $arr_group_activity[$i]['SlipDate']['Month'], $arr_group_activity[$i]['SlipDate']['Day'], $arr_group_activity[$i]['SlipDate']['Year']);
			for($j=$i+1;$j<$len_list;$j++)
			{
				$same_entry	=	array();
				$time_scnd	=	mktime(0, 0, 0,$arr_group_activity[$j]['SlipDate']['Month'], $arr_group_activity[$j]['SlipDate']['Day'], $arr_group_activity[$j]['SlipDate']['Year']);
				for($k=0;$k<$count_group;$k++) 
				{
					if(($arr_group_activity[$i][$arr_group_fields[$k]] == $arr_group_activity[$j][$arr_group_fields[$k]]))
					{
						if($time_first == $time_scnd)
						{
							//echo $arr_group_activity[$i][$arr_group_fields[$k]]." ".$arr_group_activity[$i]['RecordID']." ".$arr_group_activity[$j][$arr_group_fields[$k]]." ".$arr_group_activity[$j]['RecordID']."<br/>";
							$same_entry[]	=	1;
						}
						else
						{
							$same_entry[]	=	0;
						}
					}
					else
					{
						$same_entry[]	=	0;
					}
				}
				if(array_sum($same_entry) >= $count_group)
				{
					$arr_group_activity[$i]['multiple_entry']	=	1;
				}
			}
		}
		return $arr_group_activity;
	}
	
   /**
	* @Function    : order_by_date
	* @Description : Order the array by date value
	*/
	public function order_by_date($arr_value, $field, $sort_by)
	{
		$arr_order	=	array();
		$arr_keys	=	array();
		
			
		if(empty($arr_value))
		{
			return false;
		}
		$i=0;
		foreach($arr_value as $value) 
		{
			$date_time		=	mktime(0, 0, 0, $value[$field]['Month'], $value[$field]['Day'], $value[$field]['Year']);
			/* if(in_array($date_time,$arr_keys)) 
			{
				$arr_order[$date_time]['Units'] = $value['Units'];
				$arr_order[$date_time]['RecordID'].=','. $value['RecordID'];		// added for getting the record id of the element.
			} 
			else 
			{ */
				$arr_order[$date_time]	=	$value;
			//	$arr_keys[$i]			=	$date_time;
				$arr_order[$date_time]['RecordID']= $value['RecordID'];
			//}
			$i++;
		}
		
		if($sort_by == "asc") 
		ksort($arr_order);
		else 
		krsort($arr_order);
		
		return array_merge(array(), $arr_order);
	}
	
	/**
	 * @Function : get_last_auto_id
	 * @Description : function to retreive last auto id of the session.
	 */
	public function get_last_auto_id($content, $field)
	{
		if(!empty($content))
		{
			$arr_id	=	array();
			
			foreach($content as $value) 
			{
				$arr_id[] = $value[$field];
			}
			
			return max($arr_id);
		}else{
			return 0;
		}
	}
	
	/**
	 * @Function	:	get_array_key
	 * @Description :	get the key index value of the array by using one field.
	 * @param1		:	array input
	 * @param2		:	field value 
	 * @param3		:	field name
	 * @return		:	integer value - array key
	 */	
	public function get_array_key($arr_content, $field_val, $field_name)
	{
		foreach ($arr_content as $key=>$content)
		{
			if ($content[$field_name] == $field_val)
			return $key;
		}
		return null;
	}
	
	/**
	 * @Function	:	get_users_email
	 * @Description :	read all user's email except the user whose id is param1.
	 * @param1		:	user id 
	 * @return		:	array contains email id
	 */	
	public function get_users_email($fcontent, $id)
	{
		$email	=	array();
		foreach($fcontent as $user)
		{
			if($user['RecordID'] != $id)
			{
				$email[]	=	$user['Email'];
			}
		}
		return $email;
	}
	
public function JSON_query_search_user($select = array(), $where = array(), $multiple = null)
	{
		$where_count 	=	count($where);
		$true		 	=	0;
		$return			=	array();
		$j				=  	0;
		foreach($this->file_content as $file)
		{
			$result		=	array();
			//echo "<pre>";print_r($file);echo "</pre><br/>";
			if ($where_count != 0)
			{
				foreach($where as $key=>$val)
				{
					try {
						//echo "val -> ".$val." file ->".$file[$key]."<br/><br/>";
					
						if (strstr(strtolower($file[$key]), strtolower($val)))
						{ 
							//echo "<br/>------------match----------<br/>";
							$result[]	=	1;
							break;
						} 
						else 
						{
							//echo "<br/>-------------no match---------<br/>";
							$result[]	=	0;
						}
					} catch(Exception $e) {
						continue;
					}
				}
			} 
			else
			{
				$result[]	=	1;
			}
			
			if (array_sum($result) >= 1)
			{		
				if ($select[0] == "*") 
				{
					if($multiple)
					return $file;
					
					$return[$j]	=	$file;	
					$true		=	1;
				} 
				else
				{
					foreach($select as $key_field=>$val_field)
					{
						$return[$j][$val_field]	=	$file[$val_field];	
					}
					$true	=	1;
				}
			}
			$j++;
		}
		
		if($true) 
		{ 
			return $return;
		} 
		else 
		{
			return false;
		}
	}
}