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
        
	function get_closest_crossfits($latitude = 0, $longitude	=	0, $results_count = 5)
	{
		$sql	=	"
						SELECT 
							affil_name,
							url,
							latitude,
							longitude,
							TRUNCATE(SQRT(POWER((69.1 * (latitude - ".$latitude.") ), 2) + POWER((69.1 * (".$longitude." - longitude)) * COS(latitude / 57.3), 2)),1) AS distance
						FROM 
							affiliates_affiliates f
						ORDER BY 
							distance
						LIMIT ".$results_count." 
						";
		
		$query		= $this->db->query($sql);	

		return $query->result_array();

	}
	
}

/* End of file affiliates_model.php */
/* Location: ./application/models/affiliates_model.php */