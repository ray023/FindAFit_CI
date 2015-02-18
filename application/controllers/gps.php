<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gps extends CI_Controller {
    
    public function index()
    {
        $data['view']   =   'generic';
        $this->load->vars($data);
        $this->load->view('master', $data);

        return;
    }
    
    public function get_boxes()
    {
        define('APP_SOURCE'		,3);
        define('INFO_SOURCE'	,4);
        define('RESULT_COUNT'	,5);
        define('ADDRESS_VALUE'	,6);
        define('LATITUDE'		,6);
        define('LONGITUDE'		,7);

        $app_source	=   $this->uri->segment(APP_SOURCE);
        $info_source	=   $this->uri->segment(INFO_SOURCE);
        $latitude	=   0;
        $longitude	=   0;
        $address_value	=   '';
        $return_value	=   '';
        $results	=   '';
        $result_count	=   0;

        $this->load->model('Find_a_fit_model');

        if ($info_source === 'current_position')
        {
            $latitude	=   $this->uri->segment(LATITUDE);
            $longitude	=   $this->uri->segment(LONGITUDE);

            $stat_data['ip_address']    =   $_SERVER['REMOTE_ADDR'];
            $stat_data['faf_source']    =   $app_source;
            $stat_data['latitude']      =   $latitude;
            $stat_data['longitude']     =   $longitude;
            $this->Find_a_fit_model->save_stat($stat_data);

        }
        elseif ($info_source === 'address_field')
        {
            $address_value  =   $this->uri->segment(ADDRESS_VALUE);

            $stat_data['ip_address']    =   $_SERVER['REMOTE_ADDR'];
            $stat_data['faf_source']    =   $app_source;
            $stat_data['search_term']   =   $address_value;
            $this->Find_a_fit_model->save_stat($stat_data);

            $return_value   =   file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address_value.'&key=AIzaSyBecnfstyx5PtF6nJaieEhlP3DpCwTRTzU');
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
                $audit_data['member_id']	=	$this->session->userdata('member_id');
                $audit_data['member_name']	=	$this->session->userdata('display_name');
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

        $this->load->model('Find_a_fit_model');
        $facilities_array	=	$this->Find_a_fit_model->get_closest_crossfits($latitude, $longitude, $result_count);

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

}

/* End of file gps.php */
/* Location: ./application/controllers/gps.php */