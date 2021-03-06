<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {
    
	public function index()
	{
		$data['view']		=	'generic';
		$this->load->vars($data);
		$this->load->view('master', $data);

		return;
	}
        
        public function get_json()
        {
            $this->load->model('News_model');
            
            $new_data = $this->News_model->get_news();
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($new_data));

        }
}

/* End of file news.php */
/* Location: ./application/controllers/news.php */