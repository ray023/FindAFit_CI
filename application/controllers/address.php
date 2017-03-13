<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address extends CI_Controller {
    
    public function index()
    {
        $data['view']   =   'generic';
        $this->load->vars($data);
        $this->load->view('master', $data);

        return;
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
    
    public function get_json_with_start_position()
    {
  
        define('ADDRESS_VALUE'	,3);
        define('RESULT_COUNT'	,4);

        $address_value	=   '';
        $return_value	=   '';
        $results	=   '';
        $result_count	=   0;

        $this->load->model('Affiliates_model');
        $this->load->model('Stats_model');
        $this->load->model('Audit_model');
        
        if ($this->Audit_model->user_is_over_search_limit())
        {
            $t = $this->_address_search_limit_reached();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($t));
            return;
        }
           
        
        $address_value   =   $this->uri->segment(ADDRESS_VALUE);
        $result_count   =   $this->uri->segment(RESULT_COUNT);

        $stat_data['ip_address']    =   $_SERVER['REMOTE_ADDR'];
        $stat_data['faf_source']    =   'address';
        $stat_data['search_term']   =   $address_value;
        $stat_data['result_count']  =   $result_count;
        $this->Stats_model->save_stat($stat_data);
        
        $value = str_replace("_OPEN_PARENTHESIS_","(",$address_value);
        $value = str_replace("_CLOSE_PARENTHESIS_",")",$value);
        $value = str_replace("_HYPHEN_","-",$value);
        $value = str_replace("_PERIOD_",".",$value);
        $value = str_replace("_EXCLAMATION_MARK_","!",$value);
        $value = str_replace("_TILDE_","~",$value);
        $value = str_replace("_ASTERISK_","*",$value);
        $value = str_replace("_APOSTROPHE_","'",$value);
        $value = str_replace("_COLON_",":",$value);
        $value = str_replace("_SEMICOLON_",";",$value);
        $value = str_replace("_AT_SIGN_","@",$value);
        $value = str_replace("_AMPERSAND_","&",$value);
        $value = str_replace("_DOUBLE_QUOTE_","\"",$value);
        $value = str_replace("_PERCENT_","%",$value);
        $value = str_replace("_QUESTION_","?",$value);
        $value = str_replace("_COMMA_",",",$value);
        $value = str_replace("_BACKSLASH_","\\",$value);
        $value = str_replace("_SLASH_","/",$value);
        $value = str_replace("_DOLLAR_SIGN_","$",$value);
        
        $address_value = $value;

        $return_value   =   file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address_value.'&key=AIzaSyBecnfstyx5PtF6nJaieEhlP3DpCwTRTzU');
        $json = json_decode($return_value, true);
        
        $start_position = false;
        //Got hacked by some German ass constantly pinging this code; 
        //returning "over the limit box if that happens"
        if ($json["status"] === "OVER_QUERY_LIMIT" )
        {
            $this->load->model('Audit_model');
            $audit_data['controller']	=	'address';
            $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
            $audit_data['short_description']	=	'Quota Limit Reached (current)';
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
            {
                $audit_data['controller']	=   'address';
                $audit_data['ip_address']	=   $_SERVER['REMOTE_ADDR'];
                $audit_data['short_description']=   'Map Warning';
                $audit_data['full_info']	=   'Address info too broad for '.$address_value;
                $this->Audit_model->save_audit_log($audit_data);
            }

            $results =  $json["results"];
            $geom =  $results[0]["geometry"];
            $location = $geom["location"];
            $latitude = $location["lat"];
            $longitude = $location["lng"];
            
            $start_position = array(
                'search_term' => $address_value,
                'latitude' => $latitude,
                'longitude' => $longitude,
            );
        }
        else
        {
            
            $affil_by_name = false;
            
            if ($json["status"] === 'ZERO_RESULTS')
            {
                $address_value = urldecode($address_value);
                $address_value = str_replace("%20", " ", $address_value);
                //User might be trying to find affiliate by name.  Search the databae
                $affil_by_name  =   $this->Affiliates_model->get_affiliate_by_name($address_value);   
            }
         
            
            if (!!$affil_by_name)
            {
                $latitude   =   $affil_by_name['latitude'];
                $longitude  =   $affil_by_name['longitude'];
            }
            else
            {
                $this->load->model('Audit_model');
                $audit_data['controller']	=	'address';
                $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
                $audit_data['short_description']	=	'Map Error';
                $audit_data['full_info']	=	$json["status"].' on \''.$address_value.'\'';
                $this->Audit_model->save_audit_log($audit_data);
                return;
            }                    
        }


        $result_count = $this->uri->segment(RESULT_COUNT);

        if (is_numeric($result_count) && $result_count <= 50)
        {
            //we are good
        }
        else
            $result_count = 5;

        $affiliate_list	=	$this->Affiliates_model->get_affiliates_by_location_2017_03($latitude, $longitude, $result_count);
        
        $return_array = Array(
            'start_position' => $start_position,
            'affil_list' => $affiliate_list,
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($return_array));

    }
    
    public function get_json()
    {
        define('ADDRESS_VALUE'	,3);
        define('RESULT_COUNT'	,4);

        $address_value	=   '';
        $return_value	=   '';
        $results	=   '';
        $result_count	=   0;

        $this->load->model('Affiliates_model');
        $this->load->model('Stats_model');
        $this->load->model('Audit_model');
        
        if ($this->Audit_model->user_is_over_search_limit())
        {
            $t = $this->_address_search_limit_reached();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($t));
            return;
        }
        
        $address_value   =   $this->uri->segment(ADDRESS_VALUE);
        $result_count   =   $this->uri->segment(RESULT_COUNT);

        $stat_data['ip_address']    =   $_SERVER['REMOTE_ADDR'];
        $stat_data['faf_source']    =   'address';
        $stat_data['search_term']   =   $address_value;
        $stat_data['result_count']  =   $result_count;
        $this->Stats_model->save_stat($stat_data);

        $return_value   =   file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address_value.'&key=AIzaSyBecnfstyx5PtF6nJaieEhlP3DpCwTRTzU');
        $json = json_decode($return_value, true);
        
//Got hacked by some German ass constantly pinging this code; 
        //returning "over the limit box if that happens"
        if ($json["status"] === "OVER_QUERY_LIMIT" )
        {
            $this->load->model('Audit_model');
            $audit_data['controller']	=	'address';
            $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
            $audit_data['short_description']	=	'Quota Limit Reached (older)';
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
            {
                $audit_data['controller']	=   'address';
                $audit_data['ip_address']	=   $_SERVER['REMOTE_ADDR'];
                $audit_data['short_description']=   'Map Warning';
                $audit_data['full_info']	=   'Address info too broad for '.$address_value;
                $this->Audit_model->save_audit_log($audit_data);
            }

            $results =  $json["results"];
            $geom =  $results[0]["geometry"];
            $location = $geom["location"];
            $latitude = $location["lat"];
            $longitude = $location["lng"];
        }
        else
        {
            $affil_by_name = false;
            if ($json["status"] === 'ZERO_RESULTS')
            {
                //User might be trying to find affiliate by name.  Search the databae
                $affil_by_name  =   $this->Affiliates_model->get_affiliate_by_name($address_value);   
            }
            
            if (!!$affil_by_name)
            {
                $latitude   =   $affil_by_name['latitude'];
                $longitude  =   $affil_by_name['longitude'];
            }
            else
            {
                $this->load->model('Audit_model');
                $audit_data['controller']	=	'address';
                $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
                $audit_data['short_description']	=	'Map Error';
                $audit_data['full_info']	=	$json["status"].' on \''.$address_value.'\'';
                $this->Audit_model->save_audit_log($audit_data);
                return;
            }                    
        }


        $result_count = $this->uri->segment(RESULT_COUNT);

        if (is_numeric($result_count) && $result_count <= 50)
        {
            //we are good
        }
        else
            $result_count = 5;

        $affiliate_list	=	$this->Affiliates_model->get_affiliates_by_location_2017_03($latitude, $longitude, $result_count);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($affiliate_list));

    }

}

/* End of file feedback.php */
/* Location: ./application/controllers/address.php */