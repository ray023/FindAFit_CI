<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Events extends CI_Controller {
    
    public function index()
    {
        $data['view']   =   'generic';
        $this->load->vars($data);
        $this->load->view('master', $data);

        return;
    }
    
    public function get_by_gps()
    {
        define('LATITUDE'           ,3);
        define('LONGITUDE'          ,4);
        
        define('RETURN_THRESHHOLD'  ,1000);

        $latitude	=   0;
        $longitude	=   0;

        $this->load->model('Events_model');
        $this->load->model('Stats_model');
        $this->load->model('Audit_model');

        $latitude	=   $this->uri->segment(LATITUDE);
        $longitude	=   $this->uri->segment(LONGITUDE);
        
        $stat_data['ip_address']    =   $_SERVER['REMOTE_ADDR'];
        $stat_data['faf_source']    =   'gps for event';
        $stat_data['latitude']      =   $latitude;
        $stat_data['longitude']     =   $longitude;
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

        $event_list	=	$this->Events_model->get_events_by_location($latitude, $longitude);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($event_list));

    }

}

/* End of file events.php */
/* Location: ./application/controllers/events.php */