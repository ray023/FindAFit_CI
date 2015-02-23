<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address extends CI_Controller {
    
    public function index()
    {
        $data['view']   =   'generic';
        $this->load->vars($data);
        $this->load->view('master', $data);

        return;
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
        
        $address_value   =   $this->uri->segment(ADDRESS_VALUE);
        $result_count   =   $this->uri->segment(RESULT_COUNT);

        $stat_data['ip_address']    =   $_SERVER['REMOTE_ADDR'];
        $stat_data['faf_source']    =   'address';
        $stat_data['search_term']   =   $address_value;
        $stat_data['result_count']  =   $result_count;
        $this->Stats_model->save_stat($stat_data);

        $return_value   =   file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address_value.'&key=AIzaSyBecnfstyx5PtF6nJaieEhlP3DpCwTRTzU');
        $json = json_decode($return_value, true);
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
            $this->load->model('Audit_model');
            $audit_data['controller']	=	'address';
            $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
            $audit_data['short_description']	=	'Map Error';
            $audit_data['full_info']	=	$json["status"].' on \''.$address_value.'\'';
            $this->Audit_model->save_audit_log($audit_data);
            return;
        }


        $result_count = $this->uri->segment(RESULT_COUNT);

        if (is_numeric($result_count) && $result_count <= 50)
        {
            //we are good
        }
        else
            $result_count = 5;

        $affiliate_list	=	$this->Affiliates_model->get_affiliates_by_location($latitude, $longitude, $result_count);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($affiliate_list));

    }

}

/* End of file feedback.php */
/* Location: ./application/controllers/address.php */