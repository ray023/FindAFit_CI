<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gps extends CI_Controller {
    
    public function index()
    {
        $data['view']   =   'generic';
        $this->load->vars($data);
        $this->load->view('master', $data);

        return;
    }
    
    public function get_json()
    {
        define('LATITUDE'           ,3);
        define('LONGITUDE'          ,4);
        define('RESULT_COUNT'       ,5);
        
        define('RETURN_THRESHHOLD'  ,100);

        $latitude	=   0;
        $longitude	=   0;
        $result_count	=   0;

        $this->load->model('Affiliates_model');
        $this->load->model('Stats_model');
        $this->load->model('Audit_model');

        $latitude	=   $this->uri->segment(LATITUDE);
        $longitude	=   $this->uri->segment(LONGITUDE);
        $result_count   =   $this->uri->segment(RESULT_COUNT);
        
        $stat_data['ip_address']    =   $_SERVER['REMOTE_ADDR'];
        $stat_data['faf_source']    =   'gps';
        $stat_data['latitude']      =   $latitude;
        $stat_data['longitude']     =   $longitude;
        $stat_data['result_count']  =   $result_count;
        $this->Stats_model->save_stat($stat_data);
        
        if (!is_numeric($latitude) || !is_numeric($longitude))
        {
            $audit_data['controller']	=   'address';
            $audit_data['ip_address']	=   $_SERVER['REMOTE_ADDR'];
            $audit_data['short_description']=   'Non-numeric latitude or longitude';
            $audit_data['full_info']	=   'latitude:  '.$latitude.'  longitude:'.$longitude;
            $this->Audit_model->save_audit_log($audit_data);
            
            $latitude = 51.1788;
            $longitude = 1.8262;
        }

        if (is_numeric($result_count) && $result_count <= RETURN_THRESHHOLD)
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
    public function get_json_2017_03()
    {
        define('LATITUDE'           ,3);
        define('LONGITUDE'          ,4);
        define('RESULT_COUNT'       ,5);
        
        define('RETURN_THRESHHOLD'  ,100);

        $latitude	=   0;
        $longitude	=   0;
        $result_count	=   0;

        $this->load->model('Affiliates_model');
        $this->load->model('Stats_model');
        $this->load->model('Audit_model');

        $latitude	=   $this->uri->segment(LATITUDE);
        $longitude	=   $this->uri->segment(LONGITUDE);
        $result_count   =   $this->uri->segment(RESULT_COUNT);
        
        $stat_data['ip_address']    =   $_SERVER['REMOTE_ADDR'];
        $stat_data['faf_source']    =   'gps';
        $stat_data['latitude']      =   $latitude;
        $stat_data['longitude']     =   $longitude;
        $stat_data['result_count']  =   $result_count;
        $this->Stats_model->save_stat($stat_data);
        
        if (!is_numeric($latitude) || !is_numeric($longitude))
        {
            $audit_data['controller']	=   'address';
            $audit_data['ip_address']	=   $_SERVER['REMOTE_ADDR'];
            $audit_data['short_description']=   'Non-numeric latitude or longitude';
            $audit_data['full_info']	=   'latitude:  '.$latitude.'  longitude:'.$longitude;
            $this->Audit_model->save_audit_log($audit_data);
            
            $latitude = 51.1788;
            $longitude = 1.8262;
        }

        if (is_numeric($result_count) && $result_count <= RETURN_THRESHHOLD)
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

/* End of file gps.php */
/* Location: ./application/controllers/gps.php */