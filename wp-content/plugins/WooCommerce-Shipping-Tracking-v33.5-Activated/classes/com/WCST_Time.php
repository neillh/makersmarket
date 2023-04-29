<?php 
class WCST_Time
{
	public function __construct()
	{
	}
	public function get_available_date($estimation_rule, $return_object = false)
	{
		if(!isset($estimation_rule))
			return !$return_object ? __('N/A','woocommerce-shipping-tracking') : null;
		
		$wcst_option_model = new WCST_Option();
		$miutes_offeset = $wcst_option_model->get_estimations_options('hour_offset', 0);
		$date_format = $wcst_option_model->get_option('wcst_general_options', 'date_format', "dd/mm/yyyy");
		$time_format = get_option('time_format');
		
		
		$hour =  date('G',strtotime($miutes_offeset.' minutes'));
		//Offset in minute
		$start_day_of_the_week =  date('w',strtotime($miutes_offeset.' minutes')); //0 (for Sunday) through 6 (for Saturday)
		$start_day_of_the_month =  date('j',strtotime($miutes_offeset.' minutes'));
		$start_month =  date('n',strtotime($miutes_offeset.' minutes'));
		$start_year =  date('Y',strtotime($miutes_offeset.' minutes'));
		/* 
		* Format:
		["day_cut_off_hour"]=>
			  string(1) "0"
			  ["days_delay"]=>
			  string(1) "0"
			}
		*/
		$apply_cutoff_additional_day = false;
		//it is like placing an order in the 24h ( 1440m) after
		if($hour >= $estimation_rule['day_cut_off_hour'])
		{
			$apply_cutoff_additional_day = true;
			$start_day_of_the_week =  date('w',strtotime(($miutes_offeset+1440).' minutes')); //0 (for Sunday) through 6 (for Saturday)
			$start_day_of_the_month =  date('j',strtotime(($miutes_offeset+1440).' minutes'));
			$start_month =  date('n',strtotime(($miutes_offeset+1440).' minutes'));
			$start_year =  date('Y',strtotime(($miutes_offeset+1440).' minutes'));
		}
		//First avaiable day by which starting shipment date could take place. It could be today or tomorrow.
		$starting_date = array('day_of_the_week' => $start_day_of_the_week, 'day_of_the_month' => $start_day_of_the_month, 'month'=>$start_month, 'year' => $start_year);
		
		$first_run = true;
		$starting_date = $this->get_next_available_date($starting_date,$estimation_rule, $first_run, $apply_cutoff_additional_day);
		$date = new DateTime($starting_date['year']."-".$starting_date['month']."-".$starting_date['day_of_the_month']);
		
		if($return_object)
			return $date;
		
		//Elastic date
		$end_date = null;
		if($estimation_rule['elastic_date'])
		{
			//Offset in minute
			/* Old method, uncomment if you are experiecing any trouble:
			$additional_days_in_minutes = $estimation_rule['elastic_date_day_offset'] * 1440;
			$miutes_offeset += $additional_days_in_minutes;
			$end_day_of_the_week =  date('w',strtotime($miutes_offeset.' minutes')); //0 (for Sunday) through 6 (for Saturday)
			$end_day_of_the_month =  date('j',strtotime($miutes_offeset.' minutes'));
			$end_month =  date('n',strtotime($miutes_offeset.' minutes'));
			$end_year =  date('Y',strtotime($miutes_offeset.' minutes'));
			$apply_cutoff_additional_day = false;
			if($hour >= $estimation_rule['day_cut_off_hour'])
			{
				$apply_cutoff_additional_day = true;
				$end_day_of_the_week =  date('w',strtotime(($miutes_offeset+1440).' minutes')); //0 (for Sunday) through 6 (for Saturday)
				$end_day_of_the_month =  date('j',strtotime(($miutes_offeset+1440).' minutes'));
				$end_month =  date('n',strtotime(($miutes_offeset+1440).' minutes'));
				$end_year =  date('Y',strtotime(($miutes_offeset+1440).' minutes'));
			}
			$ending_date = array('day_of_the_week' => $end_day_of_the_week, 'day_of_the_month' => $end_day_of_the_month, 'month'=>$end_month, 'year' => $end_year);
			$end_date = $this->get_next_available_date($ending_date,$estimation_rule, true, $apply_cutoff_additional_day);
			$end_date = new DateTime($end_date['year']."-".$end_date['month']."-".$end_date['day_of_the_month']);*/
			
			$end_date = clone $date;
			$end_date = date_add($end_date, date_interval_create_from_date_string($estimation_rule['elastic_date_day_offset'].' days') );
			$ending_date = array('day_of_the_week' => $end_date->format('w'), 'day_of_the_month' => $end_date->format('j'), 'month'=>$end_date->format('n'), 'year' => $end_date->format('Y'));
			$apply_cutoff_additional_day = false;
			$end_date = $this->get_next_available_date($ending_date,$estimation_rule, true, $apply_cutoff_additional_day);
			$end_date = new DateTime($end_date['year']."-".$end_date['month']."-".$end_date['day_of_the_month']);
		}
		
		$first_day_shipping_text = $this->get_data_formatted($date, $date_format, "m/d/Y");
		/* To display the "order by X to get same day shipping" function. This, however will be printend also in the emails.
		if(!isset($end_date))
		{
			$today = new DateTime("today");
			$diff = $today->diff( $date );
			$diffDays = (integer)$diff->format( "%R%a" );
			$cut_off_date = new DateTime($estimation_rule['day_cut_off_hour'].":00");
			if($diffDays == 0)
				$first_day_shipping_text = sprintf(esc_html__( 'Order by %s for same day shipping', 'woocommerce-shipping-tracking' ), $cut_off_date->format($time_format ));
		} */
		
		return isset($end_date) ? $this->get_data_formatted($date, $date_format, "m/d/Y").esc_html__( ' - ', 'woocommerce-shipping-tracking' ).$this->get_data_formatted($end_date, $date_format, "m/d/Y") : $first_day_shipping_text;
	}
	public function format_data($date)
	{
		if($date == "")
			return "";
		
		$wcst_option_model = new WCST_Option();
		$date_format = $wcst_option_model->get_option('wcst_general_options', 'date_format', "dd/mm/yyyy");
		
		try{
			$date = new DateTime($date); //yyyy-mm-dd
		}catch(Exception $e){return $date;}
		
		return $this->get_data_formatted($date, $date_format, 'Y-m-d');
	}
	public function get_data_formatted($date, $date_format, $default_format = 'Y-m-d' )
	{
		$wpml_helper = new WCST_Wpml();
		
		$wcst_option_model = new WCST_Option();
		$disable_utf8_encoding =  $wcst_option_model->get_option('wcst_general_options', 'disable_utf8_encoding', false); 
		setlocale(LC_TIME, $wpml_helper->get_current_locale_code()); //To set to all string types: LC_ALL
		
		if( $date_format == "dd/mm/yyyy" )
			return $date->format("d/m/Y");
		else if( $date_format == "mm/dd/yyyy" )
			return $date->format("m/d/Y");
		else if( $date_format == "yyyy/mm/dd" )
			return $date->format("Y/m/d");
		else if( $date_format == "dd.mm.yyyy" )
			return $date->format("d.m.Y");
		else if( $date_format == "mm.dd.yyyy" )
			return $date->format("m.d.Y");
		else if( $date_format == "yyyy.mm.dd" )
			return $date->format("Y.m.d");
		else if( $date_format == "dd-mm-yyyy" )
			return $date->format("d-m-Y");
		else if( $date_format == "mm-dd-yyyy" )
			return $date->format("m-d-Y");
		else if( $date_format == "yyyy-dd-mm" )
			return $date->format("Y-m-d");
		else if( $date_format == "mmm dd" )
			return  !$disable_utf8_encoding ? utf8_encode(strftime("%b %e", $date->getTimestamp())) : strftime("%b %e", $date->getTimestamp()); //http://php.net/manual/en/function.strftime.php
		else if( $date_format == "dd mmm" )
			return  !$disable_utf8_encoding ? utf8_encode(strftime("%e %b", $date->getTimestamp())) : strftime("%e %b", $date->getTimestamp());
		else if( $date_format == "mmmm dd, yyyy" )
			
			return  !$disable_utf8_encoding ? utf8_encode(strftime("%B %e, %G", $date->getTimestamp())) : strftime("%B %e, %G", $date->getTimestamp()); 
		else if( $date_format == "dddd, dd.mm" )
			return !$disable_utf8_encoding ? utf8_encode(strftime("%A %d, %m", $date->getTimestamp())) : strftime("%A %d, %m", $date->getTimestamp());
		
		return $date->format($default_format);
	}
	public function format_data_according_wordpress_settings($date)
	{
			try{
			$date = new DateTime($date); //yyyy-mm-dd
		}catch(Exception $e){return $date;}
		
		return $date->format(get_option('date_format')." ".get_option('time_format'));
	}
	private function get_next_available_date($starting_date, $estimation_rule, $consider_dispatch_dalay, $apply_cutoff_additional_day = false)
	{
		/* Format:
			["working_days"]=> //0 (for Sunday) through 6 (for Saturday)
			  array(2) {
				[0]=>
				string(1) "2"
				[1]=>
				string(1) "5"
			  }
			  */
		$all_days_of_the_week = array(0 => '1', 1 => '2', 2  => '3', 3 => '4', 4 => '5', 5 => '6', 6 => '0');
		$non_working_days = array_diff($all_days_of_the_week, $estimation_rule["working_days"]);
		$days_left = 1; 
		$force_stop = 365;
		$display_delay_in_day = $estimation_rule['days_delay'];
		$number_of_days_added =  /* $days_left > 0 ||  $apply_cutoff_additional_day  ? 1 :*/ 0;
		$original_day_of_the_week = $starting_date['day_of_the_week'];
		$original_day_of_the_month = $starting_date['day_of_the_month'];
		$original_month = $starting_date['month'];
		$original_year = $starting_date['year'];
		
		do
		{
			$found = false;
			$starting_date_to_string = $original_year."-".$original_month."-".$original_day_of_the_month;
			$starting_date['day_of_the_week'] = ($number_of_days_added + $original_day_of_the_week)%7;
			
			$starting_date["day_of_the_month"] = date('j', strtotime($starting_date_to_string.' + '.($number_of_days_added).' days'));
			$starting_date["month"] = date('n', strtotime($starting_date_to_string. ' + '.($number_of_days_added).' days'));
			$starting_date["year"] = date('Y', strtotime($starting_date_to_string. ' + '.($number_of_days_added).' days'));
			
			
			foreach($all_days_of_the_week as $day)
			{
				if( $starting_date['day_of_the_week'] == $day)
				{
					if(in_array($day, $estimation_rule["working_days"]))
					{
						$found = true;
						
						if($display_delay_in_day > 0 && $found)
						{
							$display_delay_in_day--;
							$found = false;
							
						}
						
					}
					elseif($estimation_rule['consider_non_working_days_as_dispatch_delay'])
					{
						$display_delay_in_day--;
						
					} 
					
				}
			}
			
			$is_non_working_day = $this->check_if_date_is_a_non_working_day($starting_date,$estimation_rule);
			if($is_non_working_day)
			{
				$found = false;
				
			}
			
			$number_of_days_added++;
			$force_stop--;
			
		}while(!$found && $force_stop > 0 );
		
		return $starting_date;
	}
	private function check_if_date_is_a_non_working_day($date, $estimation_rule)
	{
		/*Format:
		  ["non_working_days"]=>
		  array(2) {
			[0]=>
			array(2) {
			  ["day"]=>
			  string(1) "1"
			  ["month"]=>
			  string(1) "3"
			}
			[1]=>
			array(2) {
			  ["day"]=>
			  string(1) "1"
			  ["month"]=>
			  string(2) "11"
			}
		  }
		  */
		if(is_array($estimation_rule["non_working_days"]))
			foreach($estimation_rule["non_working_days"] as $non_working_days)
			{
				if($date['day_of_the_month'] == $non_working_days['day'] && $date['month'] == $non_working_days['month'])
				return true;
					
			}
			return false;
	}
	public function is_valid_date_format($date)
	{
		
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
			return true;
		} else {
			return false;
}
	}
}
?>