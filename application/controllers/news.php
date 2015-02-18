<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {
    
	public function index()
	{
		$data['view']		=	'generic';
		$this->load->vars($data);
		$this->load->view('master', $data);

		return;
	}
}

/* End of file news.php */
/* Location: ./application/controllers/news.php */