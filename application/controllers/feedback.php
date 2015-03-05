<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback extends CI_Controller {
    
    public function index()
    {
        $data['view']   =   'generic';
        $this->load->vars($data);
        $this->load->view('master', $data);

        return;
    }
    
    public function submit_feedback()
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
                    'ip_address'            =>  $_SERVER['REMOTE_ADDR'],
                    'user_feedback'         =>  $this->uri->segment(USER_FEEDBACK),
                    'device_info_cordova'   =>  $this->uri->segment(CORDOVA),
                    'device_info_model'     =>  $this->uri->segment(MODEL),
                    'device_info_platform'  =>  $this->uri->segment(PLATFORM),
                    'device_info_uuid'      =>  $this->uri->segment(UUID),
                    'device_info_version'   =>  $this->uri->segment(VERSION),
             );
            
            foreach ($data as $key => $value) {
                $data[$key] =  str_replace('%2C',',',str_replace('%27','\'',str_replace('%20', ' ', $value)));
            }

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
            $this->email->message($data['user_feedback']);
            $this->email->send();

            return;

    }

}

/* End of file feedback.php */
/* Location: ./application/controllers/feedback.php */