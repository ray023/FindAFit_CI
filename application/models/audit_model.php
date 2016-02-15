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


    public function user_is_over_search_limit()
    {
        $sql = "SELECT 
                    ip_address, count(ip_address) as count_ip
                FROM 
                    stats
                WHERE 
                    created_date >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)  AND
                    ip_address = '".$_SERVER['REMOTE_ADDR']."'
                GROUP BY
                    ip_address
                HAVING 
                    count(ip_address) > 25 ";
        
            $query = $this->db->query($sql);

            return ($query->num_rows() > 0);
        
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
