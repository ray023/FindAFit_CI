<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Member Class
 * 
 * @package Legacy
 * @subpackage controller
 * @category legacy
 * @author Ray Nowell
 * 
 */
class Legacy extends CI_Controller {

	function __construct() 
	{
		parent::__construct();
		$this->load->library('form_validation');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * @access public
	 */
	public function index()
	{
            echo 'index';
            /*
		$data['doc_ready_call']	=	'find_a_fit_doc_ready()';
		//Customer Modernizer with just geolocation enabled
		$data['title']			=	'Find-A-Fit';
		$data['description']	=	'Find-A-Fit lets you find CrossFits';
		$data['view']			=	'mobile_find_a_fit';
		$this->load->vars($data);
		$this->load->view('mobile_master_1_4_3', $data);

		return;
             * 
             */
	}
        
        public function _process_records()
        {                
            $json_row   =   '';
            $this->load->model('Legacy_model');
            $unprocessed_records_array  =   $this->Legacy_model->get_unprocessed_faf_records();

            if (!$unprocessed_records_array)
                return true;

            foreach($unprocessed_records_array as $row) 
            {
                $update_array =   array(
                                            'faf_stats_id'  =>  $row['faf_stats_id'], 
                                            'processed'     =>  true);

                if ($row['search_term']   === '')
                {
                    $return_value   =   file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$row['latitude'].','.$row['longitude'].'&key=AIzaSyBecnfstyx5PtF6nJaieEhlP3DpCwTRTzU');
                    $json = json_decode($return_value, true);
                    
                    if (is_array($json))
                    {
                        if ( $json["status"] === "OK" )
                        {
                            foreach($json["results"] as $json_row) 
                            {
                                if (!is_array($json_row))
                                    continue;
                                foreach($json_row as $address_component_row)
                                {
                                    if (!is_array($address_component_row))
                                        continue;

                                    foreach($address_component_row as $component)
                                    {
                                        if (!is_array($component) || !array_key_exists('types',$component))
                                            continue;

                                        if (in_array('locality', $component['types']) && in_array('political', $component['types']))
                                                $update_array['locality_political']    =   $component['long_name'];
                                        elseif (in_array('administrative_area_level_1', $component['types']) && in_array('political', $component['types']))
                                                $update_array['administrative_area_level_1']    =   $component['long_name'];
                                        elseif (in_array('postal_code', $component['types']))
                                                $update_array['postal_code']    =   $component['long_name'];
                                        elseif (in_array('political', $component['types']) && in_array('country', $component['types']))
                                        {
                                                $update_array['country_political_long_code']   =   $component['long_name'];
                                                $update_array['country_political_short_code']  =   $component['short_name'];
                                        }

                                        if (array_key_exists('locality_political', $update_array) && 
                                            array_key_exists('administrative_area_level_1', $update_array) && 
                                            array_key_exists('postal_code', $update_array) && 
                                            array_key_exists('country_political_long_code', $update_array) )
                                                break;

                                   }

                                    if (array_key_exists('locality_political', $update_array) && 
                                        array_key_exists('administrative_area_level_1', $update_array) && 
                                        array_key_exists('postal_code', $update_array) && 
                                        array_key_exists('country_political_long_code', $update_array) )
                                            break;
                                }

                                if (array_key_exists('locality_political', $update_array) && 
                                        array_key_exists('administrative_area_level_1', $update_array) && 
                                        array_key_exists('postal_code', $update_array) && 
                                        array_key_exists('country_political_long_code', $update_array) )
                                            break;
                            
                                
                                }
                        }
                        else
                        {
                            $update_array   =   false;
                                //START AUDIT
                                $this->load->model('Audit_model');
                                $audit_data['controller']	=	'faf_process_record';
                                $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
                                $audit_data['short_description']	=	'Error processing record';
                                $audit_data['full_info']	=	$json["status"];
                                $this->Audit_model->save_audit_log($audit_data);
                                //END AUDIT
                                return;
                        }
                    }
                    else
                    {
                        $update_array   =   false;
                        //START AUDIT
                        $this->load->model('Audit_model');
                        $audit_data['controller']	=	'faf_process_record';
                        $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
                        $audit_data['short_description']	=	'Error processing record';
                        $audit_data['full_info']	=	'Json not an array';
                        $this->Audit_model->save_audit_log($audit_data);
                        //END AUDIT
                        return;
                    }
                }
                
                
                if (!!$update_array)
                    $this->Legacy_model->processs_record($update_array);
           }
                
        }
        
	public function ajax_submit_feedback()
	{
		define('USER_FEEDBACK',3);
		define('CORDOVA',4);
		define('MODEL',5);
		define('PLATFORM',6);
		define('UUID', 7);
		define('VERSION', 8);

		$this->load->model('Feedback_model');

		$feedback_count =   $this->Feedback_model->get_count_of_todays_feedback();

		if ($feedback_count > 500)
		{
			echo 'Maximum feedback reached.  Try again tomorrow.';
			return;
		}

		$data = array(
			'ip_address'  =>   $_SERVER['REMOTE_ADDR'],
			'user_feedback' =>  $this->uri->segment(USER_FEEDBACK),
			'device_info_cordova' =>  $this->uri->segment(CORDOVA),
			'device_info_model' =>  $this->uri->segment(MODEL),
			'device_info_platform' =>  $this->uri->segment(PLATFORM),
			'device_info_uuid' =>  $this->uri->segment(UUID),
			'device_info_version' =>  $this->uri->segment(VERSION),
		 );

		$feedback_count =   $this->Feedback_model->save_feedback($data);

		echo 'Feedback submitted successfully.  Thank-you!';
		
		$this->load->library('email');
		$config['protocol']		=	'mail';
		$config['charset']		=	'iso-8859-1';
		$config['mailtype']		=	'html';
		$this->email->initialize($config);
		$this->email->from('ray@wod-minder.com', 'WOD-Minder Admin');
		$this->email->to('ray023@gmail.com');
		$this->email->subject('Find A Fit Feedback');
		$this->email->message($this->uri->segment(USER_FEEDBACK));
		$this->email->send();

		return;

	}
        
	public function ajax_get_nearest_boxes_with_options()
	{
		define('APP_SOURCE'		,3);
		define('INFO_SOURCE'	,4);
		define('RESULT_COUNT'	,5);
		define('ADDRESS_VALUE'	,6);
		define('LATITUDE'		,6);
		define('LONGITUDE'		,7);
		
		$app_source		=	$this->uri->segment(APP_SOURCE);
		$info_source	=	$this->uri->segment(INFO_SOURCE);
		$latitude		=	0;
		$longitude		=	0;
		$address_value	=	'';
		$return_value	=	'';
		$results		=	'';
		$result_count	=	0;
		
                $this->load->model('Legacy_model');
		
		if ($info_source === 'current_position')
		{
			$latitude	=	$this->uri->segment(LATITUDE);
			$longitude	=	$this->uri->segment(LONGITUDE);
			

			$stat_data['ip_address']    =	$_SERVER['REMOTE_ADDR'];
			$stat_data['faf_source']    =	$app_source;
                        $stat_data['latitude']     =	$latitude;
			$stat_data['longitude']     =	$longitude;
                        $this->Legacy_model->save_stat($stat_data);
                        
		}
		elseif ($info_source === 'address_field')
		{
			$address_value	=	$this->uri->segment(ADDRESS_VALUE);
			
                        $stat_data['ip_address']    =	$_SERVER['REMOTE_ADDR'];
			$stat_data['faf_source']    =	$app_source;
                        $stat_data['search_term']  =	$address_value;
                        $this->Legacy_model->save_stat($stat_data);
			
			$return_value	=	file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address_value.'&key=AIzaSyBecnfstyx5PtF6nJaieEhlP3DpCwTRTzU');
			$json = json_decode($return_value, true);
			
			if ($json["status"] === "OK" )
			{
				if (count($results) > 1)
					echo '<span class="error-messsage">WARNING:  Address info too broad.</span>';
				
				$results =  $json["results"];
				$geom =  $results[0]["geometry"];
				$location = $geom["location"];
				$latitude = $location["lat"];
				$longitude = $location["lng"];
			}
			else
			{
				//START AUDIT
				$this->load->model('Audit_model');
				$audit_data['controller']	=	'find_a_fit_mobile_with_options';
				$audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
				$audit_data['short_description']	=	'Map Error';
				$audit_data['full_info']	=	$json["status"];
				$this->Audit_model->save_audit_log($audit_data);
				//END AUDIT
				echo '<span class="error-messsage">MAP ERROR:  '.$json["status"].'</span>';
				return;
			}
		}
		else
		{
			echo 'Source '.$info_source.' is not defined.';
			return;
		}
		
		$result_count = $this->uri->segment(RESULT_COUNT);
		
		if (is_numeric($result_count) && $result_count <= 50)
		{
			//we are good
		}
		else
			$result_count = 5;
		
		$full_info = $latitude.','.$longitude;
		
		$this->load->model('Legacy_model');
		$facilities_array	=	$this->Legacy_model->get_closest_crossfits($latitude, $longitude, $result_count);
		
		echo '<div class = "ui-grid-b">';
		echo	'<div class="ui-block-a mobile-grid-header " style = "height:60px;text-align: center;">Affiliate</div>'.
				'<div class="ui-block-b mobile-grid-header " style = "height:60px;text-align: center;">Distance<br>(approx.)</div>'.
				'<div class="ui-block-c mobile-grid-header"  style = "height:60px;text-align: center;">Directions</div>';
		foreach($facilities_array as $row) 
		{
			$google_search_affiliate	=	'<a href="#" OnClick="window.open(\'https://www.google.com/?gws_rd=ssl#q='.str_replace(' ','+',$row['affil_name']).'\', \'_system\', \'\');" data-ajax="false">'.$row['affil_name'].'</a>';
			$box = (is_null($row['url']) | $row['url'] === '') ? $google_search_affiliate : '<a href="#" OnClick="window.open(\''.$row['url'].'\', \'_system\', \'\');" data-ajax="false">'.$row['affil_name'].'</a>';
			
			echo '<div class = "ui-block-a"><div class = "ui-bar ui-bar-a" style = "height:60px;text-align: center;">'.$box.'</div></div>';
			echo '<div class = "ui-block-b "><div class = "ui-bar ui-bar-a" style = "height:60px;text-align: center;">'.$row['distance'].'</div></div>';
			echo '<div class = "ui-block-c number-block"><div class = "ui-bar ui-bar-a" style = "height:60px;text-align: center;"><a href="#" OnClick="window.open(\'http://maps.google.com/?saddr='.$latitude.','.$longitude.'&daddr='.$row['latitude'].','.$row['longitude'].'\', \'_system\', \'\');" data-ajax="false" class="ui-btn ui-icon-navigation ui-btn-icon-notext ui-corner-all" >Navigate</a></div></div>';
			
			$full_info .= '|'.$row['affil_name'];
		}
		echo '</div><!--/grid-a-->';

	}
	
	public function ajax_get_nearest_boxes_mobile()
	{
		define('LATITUDE',4);
		define('LONGITUDE', 5);
		
		$latitude			=	$this->uri->segment(LATITUDE);
		$longitude	=	$this->uri->segment(LONGITUDE);
		
		
		
		$this->load->model('Legacy_model');
		$facilities_array	=	$this->Legacy_model->get_closest_crossfits($latitude, $longitude);
		
		echo '<div class = "ui-grid-b">';
		echo	'<div class="ui-block-a mobile-grid-header " style = "height:60px;text-align: center;">Affiliate</div>'.
				'<div class="ui-block-b mobile-grid-header " style = "height:60px;text-align: center;">Distance<br>(approx.)</div>'.
				'<div class="ui-block-c mobile-grid-header"  style = "height:60px;text-align: center;">Directions</div>';
		foreach($facilities_array as $row) 
		{
			$google_search_affiliate	=	'<a href="#" OnClick="window.open(\'https://www.google.com/?gws_rd=ssl#q='.str_replace(' ','+',$row['affil_name']).'\', \'_system\', \'\');" data-ajax="false">'.$row['affil_name'].'</a>';
			$box = (is_null($row['url']) | $row['url'] === '') ? $google_search_affiliate : '<a href="#" OnClick="window.open(\''.$row['url'].'\', \'_system\', \'\');" data-ajax="false">'.$row['affil_name'].'</a>';
			
			echo '<div class = "ui-block-a"><div class = "ui-bar ui-bar-a" style = "height:60px;text-align: center;">'.$box.'</div></div>';
			echo '<div class = "ui-block-b "><div class = "ui-bar ui-bar-a" style = "height:60px;text-align: center;">'.$row['distance'].'</div></div>';
			echo '<div class = "ui-block-c number-block"><div class = "ui-bar ui-bar-a" style = "height:60px;text-align: center;"><a href="#" OnClick="window.open(\'http://maps.google.com/?saddr='.$latitude.','.$longitude.'&daddr='.$row['latitude'].','.$row['longitude'].'\', \'_system\', \'\');" data-ajax="false" class="ui-btn ui-icon-navigation ui-btn-icon-notext ui-corner-all" >Navigate</a></div></div>';
			
		}
		echo '</div><!--/grid-a-->';
		
		
                $stat_data['ip_address']    =	$_SERVER['REMOTE_ADDR'];
                $stat_data['faf_source']    =	'o_mobile';
                $stat_data['latitude']     =	$latitude;
                $stat_data['longitude']     =	$longitude;
                $this->Legacy_model->save_stat($stat_data);
		
	}
	
	public function ajax_get_nearest_boxes()
	{
                //START AUDIT
		$this->load->model('Audit_model');
		$audit_data['controller']	=	'find_a_fit';
		$audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
		$audit_data['short_description']	=	'Find a fit (deprecated)';
		$this->Audit_model->save_audit_log($audit_data);
		//END AUDIT
		
		$this->ajax_get_nearest_boxes_browser();
	}
	
	public function ajax_get_nearest_boxes_browser()
	{
		define('LATITUDE',4);
		define('LONGITUDE', 5);
		
		$latitude			=	$this->uri->segment(LATITUDE);
		$longitude	=	$this->uri->segment(LONGITUDE);
		
		$this->load->model('Legacy_model');
		$facilities_array	=	$this->Legacy_model->get_closest_crossfits($latitude, $longitude);
		
		echo '<div class = "ui-grid-b">';
		echo	'<div class="ui-block-a mobile-grid-header " style = "height:60px;text-align: center;">Affiliate</div>'.
				'<div class="ui-block-b mobile-grid-header " style = "height:60px;text-align: center;">Distance<br>(approx.)</div>'.
				'<div class="ui-block-c mobile-grid-header"  style = "height:60px;text-align: center;">Directions</div>';
		foreach($facilities_array as $row) 
		{
			$google_search_affiliate	=	'<a href="https://www.google.com/?gws_rd=ssl#q='.str_replace(' ','+',$row['affil_name']).'" data-ajax="false">'.$row['affil_name'].'</a>';
			$box = (is_null($row['url']) | $row['url'] === '') ? $google_search_affiliate : '<a href="'.$row['url'].'" data-ajax="false">'.$row['affil_name'].'</a>';
			
			echo '<div class = "ui-block-a"><div class = "ui-bar ui-bar-a" style = "height:60px;text-align: center;">'.$box.'</div></div>';
			echo '<div class = "ui-block-b "><div class = "ui-bar ui-bar-a" style = "height:60px;text-align: center;">'.$row['distance'].'</div></div>';
			echo '<div class = "ui-block-c number-block"><div class = "ui-bar ui-bar-a" style = "height:60px;text-align: center;"><a href="http://maps.google.com/?saddr='.$latitude.','.$longitude.'&daddr='.$row['latitude'].','.$row['longitude'].'" class="ui-btn ui-icon-navigation ui-btn-icon-notext ui-corner-all" >Navigate</a></div></div>';
			
		}
		echo '</div><!--/grid-a-->';
		

                $stat_data['ip_address']    =	$_SERVER['REMOTE_ADDR'];
                $stat_data['faf_source']    =	'browser';
                $stat_data['latitude']     =	$latitude;
                $stat_data['longitude']     =	$longitude;
                $this->Legacy_model->save_stat($stat_data);
		
	}
	
}

/* End of file find_a_fit.php */
/* Location: ./application/controllers/find_a_fit.php */