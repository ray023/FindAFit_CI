<?php
/** 
 * Audit
 * This class handles model work necessary for logging in user
 * 
 * @author Ray Nowell
 *	
 */ 
class Audit_model extends CI_Model {

    function Audit_model()
    {
        parent::__construct();
                $this->load->library('encrypt');
    }	


    public function save_audit_log($data)
    {
        $this->db->insert('audit_log', $data);
        return TRUE;
    }

    public function get_audit_log()
    {
            $sql	=	"SELECT 
                                    DATE_FORMAT(log_date,'%d-%b-%y %H:%i') as log_date
                                      , short_description
                                      , full_info
                                    FROM 
                                      audit_log al 
                                    ORDER BY 
                                      al.log_date DESC";

            $query = $this->db->query($sql);

            if ($query->num_rows() == 0)
                            return false;

            return $query->result_array();		
    }
}
/* End of file audit_model.php */
/* Location: ./system/application/models/audit_model.php */
