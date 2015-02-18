<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function index()
	{
		//$this->output->enable_profiler(TRUE);
		
		$this->load->library('user_agent');
		$data['view']		=	'welcome_message';
		$this->load->vars($data);
		$this->load->view('master', $data);

		return;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */