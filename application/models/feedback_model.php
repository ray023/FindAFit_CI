<?php
/** 
 * Feedback_model
 * 
 * @author Ray Nowell
 *	
 */ 
class Feedback_model extends CI_Model {
	
	function Feedback_model()
	{
		parent::__construct();
	}
        
        function get_count_of_todays_feedback()
        {
            $todays_date = date( 'Y-m-d');
            $sql = "SELECT fb_id FROM feedback WHERE DATE(log_date) = '".$todays_date."'";
            $query = $this->db->query($sql);
            return $query->num_rows;
        }
                
        function save_feedback($data = null)
        {
            if ($data == null)
                return;
            
            $this->db->insert('feedback', $data); 
        }
}

/* End of file feedback_model.php */
/* Location: ./application/models/feedback_model.php */