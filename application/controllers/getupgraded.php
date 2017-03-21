<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Getupgraded extends CI_Controller {
	public function index()
	{
		$data['view']		=	'get_upgraded';
		$this->load->vars($data);
		$this->load->view('master', $data);

		return;
	}
        public function cancelled()
        {
            $this->load->model('Audit_model');
            
            $audit_data['controller']	=   'Upgrade Cancelled';
            $audit_data['ip_address']	=   $_SERVER['REMOTE_ADDR'];
            $audit_data['short_description']=   'User clicked on PayPal upgrade but cancelled';
            $this->Audit_model->save_audit_log($audit_data);
            
            echo 'No problem.  Find A Fit is here for you should you change you mind.  If you have questions/concerns, use Find A Fits Feedback feature';
        }
        public function success()
        {
            $this->load->model('Audit_model');
            
            $audit_data['controller']	=   'Upgrade Success';
            $audit_data['ip_address']	=   $_SERVER['REMOTE_ADDR'];
            $audit_data['short_description']=   'User clicked on PayPal upgrade and paid';
            $this->Audit_model->save_audit_log($audit_data);
            
            echo 'All right!  You will be contacted within 24 hours to get the details required for your upgrade.  Thank-you!';
        }
}

/* End of file getupgraded.php */
/* Location: ./application/controllers/getupgraded.php */