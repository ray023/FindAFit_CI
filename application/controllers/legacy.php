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
        
    function _address_search_limit_reached()
    {
        $this->load->model('Audit_model');
            $audit_data['controller']	=	'address';
            $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
            $audit_data['short_description']	=	'Search Limit Reached (current)';
            $audit_data['full_info']	=	'User searching too much';
            $this->Audit_model->save_audit_log($audit_data);
                
            $over_limit_array = [array(
                                    'af_id' => 1,
                                    'affil_name' => 'LIMIT REACHED:  Try tomorrow',
                                    'url' => 'http://www.google.com',
                                    'address1' => 'abc',
                                    'city_state_zip' => 'Trussville AL 35173',
                                    'phone' => '',
                                    'latitude' => '86',
                                    'longitude' => '-33',
                                    'distance' => '-99',
                                    'software' => '',
                                    'software_hyperlink' => '',
                                    'drop_in_rate' => '',
                                    'twitter' => '',
                                    'facebook' => '',
                                    'google_plus' => '',
                                    'email' => '',
                                    'nav_link' => 'http://maps.google.com/?saddr=33.6015246,-86.4895463&daddr=33,-86',
                                    'instagram'  => ''
            )];
            $sp = array(
                'search_term' => 'Search',
                'latitude' => -86,
                'longitude' => 33,
            );
            
        
            $r = Array(
                'start_position' => $sp,
                'affil_list' => $over_limit_array,
            );
        
            return $r;
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
                
                if (trim($data['user_feedback']) === '')
                {
                    echo 'Error receiving feedback.  Email ray023@gmail.com';
                    return;
                }

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
                    $this->load->model('Audit_model');
                        if ($this->Audit_model->user_is_over_search_limit())
                        {
                            $t = $this->_address_search_limit_reached();
                            $this->output
                                ->set_content_type('application/json')
                                ->set_output(json_encode($t));
                            return;
                        }
			$address_value	=	$this->uri->segment(ADDRESS_VALUE);
			
                        $stat_data['ip_address']    =	$_SERVER['REMOTE_ADDR'];
			$stat_data['faf_source']    =	$app_source;
                        $stat_data['search_term']  =	$address_value;
                        $this->Legacy_model->save_stat($stat_data);
			
			$return_value	=	file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address_value.'&key=AIzaSyBecnfstyx5PtF6nJaieEhlP3DpCwTRTzU');
			$json = json_decode($return_value, true);
                        
                        //Got hacked by some German ass constantly pinging this code; 
                        //returning "over the limit box if that happens"
                        if ($json["status"] === "OVER_QUERY_LIMIT" )
                        {
                            $audit_data['controller']	=	'address';
                            $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
                            $audit_data['short_description']	=	'Quota Limit Reached (legacy)';
                            $audit_data['full_info']	=	$json["status"].' on \''.$address_value.'\'';
                            $this->Audit_model->save_audit_log($audit_data);

                            $over_limit_array = [array(
                                                    'af_id' => 1,
                                                    'affil_name' => 'QUOTA LIMIT:  Try tomorrow',
                                                    'url' => 'http://www.google.com',
                                                    'address1' => 'abc',
                                                    'city_state_zip' => 'Trussville AL 35173',
                                                    'phone' => '',
                                                    'latitude' => '86',
                                                    'longitude' => '-33',
                                                    'distance' => '-99',
                                                    'software' => '',
                                                    'software_hyperlink' => '',
                                                    'drop_in_rate' => '',
                                                    'twitter' => '',
                                                    'facebook' => '',
                                                    'google_plus' => '',
                                                    'email' => '',
                                                    'nav_link' => 'http://maps.google.com/?saddr=33.6015246,-86.4895463&daddr=33,-86',
                                                    'instagram'  => ''
                            )];
                            $sp = array(
                                'search_term' => $address_value,
                                'latitude' => -86,
                                'longitude' => 33,
                            );


                            $r = Array(
                                'start_position' => $sp,
                                'affil_list' => $over_limit_array,
                            );

                            $this->output
                                ->set_content_type('application/json')
                                ->set_output(json_encode($r));
                            return;
                        }
			
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
