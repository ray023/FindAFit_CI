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
            define('USER_CONTACT', 9);

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
                    'user_contact'          =>  $this->uri->segment(USER_CONTACT),
             );
            
            foreach ($data as $key => $value) {
                $value = urldecode($value);
                $value = str_replace("_OPEN_PARENTHESIS_","(",$value);
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
                $data[$key] =  $value;
            }

            $feedback_count =   $this->Feedback_model->save_feedback($data);
            
            if (trim($data['user_feedback']) === '')
            {
                echo 'Error receiving feedback.  Email ray023@gmail.com!';
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
            $this->email->message($data['user_feedback'].'   Contact Info:  '.$data['user_contact']);
            $this->email->send();

            return;

    }

}

/* End of file feedback.php */
/* Location: ./application/controllers/feedback.php */