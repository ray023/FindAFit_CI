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
            echo 'aw man, you canncled';
        }
        public function success()
        {
            echo 'yahoo';
        }
}

/* End of file getupgraded.php */
/* Location: ./application/controllers/getupgraded.php */