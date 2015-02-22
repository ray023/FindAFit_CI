<?php
/** 
 * News_model
 * 
 * @author Ray Nowell
 *	
 */ 
class News_model extends CI_Model {
	
	function News_model()
	{
		parent::__construct();
	}
        
        function get_news($limit = 100)
        {
            $this->db->select("news_id, DATE_FORMAT(news_date,'%b %d %Y %h:%i %p') as news_date, details", FALSE);
            $this->db->order_by('news_date', 'desc'); 
            $this->db->limit($limit);
            $query = $this->db->get('news');
        
            return $query->result();
        }
        
}

/* End of file news_model.php */
/* Location: ./application/models/news_model.php */