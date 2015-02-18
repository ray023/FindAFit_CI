<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affiliates extends CI_Controller {
    
	public function index()
	{
		$data['view']		=	'generic';
		$this->load->vars($data);
		$this->load->view('master', $data);

		return;
	}
        
        public function get_json()
        {
            $this->load->model('Affiliates_model');
            
            $new_data = $this->Affiliates_model->get_affiliates();
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($new_data));

        }
}

/* End of file affiliates.php */
/* Location: ./application/controllers/affiliates.php */