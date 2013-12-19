<?php defined('SYSPATH') or die('No direct script access.');
/**
 *  Created Date:	March 19, 2013
 *	Class		:	date_format
 *	Description	:	use to convert various date format.
 *	Example		:	10/23/2013 to 2013-10-23
 */

    class date_format {
    	/**
    	 * @Access		:	Public
    	 * @Function	:	get_formatted_date
    	 * @Description	:	Function to change the date format as required MM/DD/YYYY to YYYY-MM-DD Format
    	 * @Param		:	Date
    	 */
        public function get_formatted_date($date) {
            return date("Y-m-d", strtotime($date));
    	}
    }
?>