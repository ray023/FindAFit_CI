<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function index()
	{
		$this->load->model('Stats_model');
                $data['download_count'] = $this->Stats_model->get_download_count();
                $data['search_count'] = number_format(count($this->Stats_model->get_history()));
                $data['country_count'] = number_format(count($this->Stats_model->get_country_list()));
                
		$this->load->library('user_agent');
		$data['view']		=	'welcome_message';
		$this->load->vars($data);
		$this->load->view('master', $data);

		return;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */