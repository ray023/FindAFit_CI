<?php
/** 
 * Affiliates_model
 * 
 * @author Ray Nowell
 *	
 */ 
class Affiliates_model extends CI_Model {
	
	function Affiliates_model()
	{
		parent::__construct();
	}
        
        function get_affiliates($limit = 100)
        {
            $this->db->limit($limit);
            $query = $this->db->get('affiliates');

            return $query->result();
        }
        
	function get_affiliates_by_location($latitude = 0, $longitude	=	0, $limit = 5)
	{
		$sql	=   "
                                SELECT 
                                    af_id,
                                    affil_name,
                                    url,
                                    address1,
                                    city_state_zip,
                                    phone,
                                    latitude,
                                    longitude,
                                    TRUNCATE(SQRT(POWER((69.1 * (latitude - ".$latitude.") ), 2) + POWER((69.1 * (".$longitude." - longitude)) * COS(latitude / 57.3), 2)),1) AS distance,
                                    software,
                                    drop_in_rate,
                                    twitter,
                                    facebook,
                                    email
                                FROM 
                                    affiliates f
                                ORDER BY 
                                    distance
                                LIMIT ".$limit." 
                            ";
		
		$query		= $this->db->query($sql);	

		return $query->result();

	}
	
}

/* End of file affiliates_model.php */
/* Location: ./application/models/affiliates_model.php */