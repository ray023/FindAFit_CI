<?php
/** 
 * Events_model
 * 
 * @author Ray Nowell
 *	
 */ 
class Events_model extends CI_Model {
	
	function Events_model()
	{
		parent::__construct();
	}    
        
	function get_events_by_location($latitude = 0, $longitude	=	0, $limit = 100)
	{
		$sql	=   "
                                SELECT 
                                    registration_id,
                                    affiliate,
                                    city_state_country,
                                    date_text,
                                    rtrim(ltrim(replace(replace(title, 'CrossFit', ''),'Course',''))) AS title,
                                    latitude,
                                    longitude,
                                    start_date,
                                    TRUNCATE(SQRT(POWER((69.1 * (latitude - ".$latitude.") ), 2) + POWER((69.1 * (".$longitude." - longitude)) * COS(latitude / 57.3), 2)),1) AS distance
                                FROM 
                                    events f
                                WHERE 
                                    title NOT LIKE '% Test%'
                                ORDER BY 
                                    distance,
                                    start_date
                                LIMIT ".$limit." 
                            ";
		
		$query		= $this->db->query($sql);	

		return $query->result();

	}
	
}

/* End of file events_model.php */
/* Location: ./application/models/events_model.php */