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
        
        function get_affiliate_by_name($search_term)
        {
            $this->db->limit(1);
            $this->db->like('affil_name', $search_term); 
            $query = $this->db->get('affiliates');

            $result_array = $query->result_array();
            return count($result_array) == 0 ? false : $result_array[0];
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
                                    software_hyperlink,
                                    drop_in_rate,
                                    twitter,
                                    facebook,
                                    google_plus,
                                    email,
                                    CONCAT('http://maps.google.com/?saddr=".$latitude.",".$longitude."&daddr=', latitude, ',' , longitude) AS nav_link,
                                    instagram
                                FROM 
                                    affiliates f
                                ORDER BY 
                                    distance
                                LIMIT ".$limit." 
                            ";
		
		$query		= $this->db->query($sql);	

		return $query->result();

	}
	
        function get_affiliates_by_location_2017_03($latitude = 0, $longitude	=	0, $limit = 5)
	{
		$sql	=   "
                                SELECT 
                                    f.af_id,
                                    f.affil_name,
                                    CASE WHEN f.url = 'false' THEN '' ELSE f.url END AS url,
                                    f.address1,
                                    f.city_state_zip,
                                    f.phone,
                                    f.latitude,
                                    f.longitude,
                                    TRUNCATE(SQRT(POWER((69.1 * (latitude - ".$latitude.") ), 2) + POWER((69.1 * (".$longitude." - longitude)) * COS(latitude / 57.3), 2)),1) AS distance,
                                    CASE WHEN ifnull(g.crossfit_affiliate_id,0) > 0 THEN 1 ELSE 0 END as drop_in_friendly,
                                    IFNULL(g.preferred_order,9999) as preferred_order,
                                    g.preferred_expiration,
                                    g.software,
                                    g.software_hyperlink,
                                    g.facebook,
                                    g.google_plus,
                                    g.twitter,
                                    g.instagram,
                                    g.youtube,
                                    g.snapchat,
                                    g.email,
                                    g.note_to_drop_in,
                                    g.drop_in_rate,
                                    CONCAT('http://maps.google.com/?saddr=".$latitude.",".$longitude."&daddr=', latitude, ',' , longitude) AS nav_link
                                FROM 
                                    affiliates f 
                                        LEFT JOIN 
                                           affiliates_custom g ON
                                            f.crossfit_affiliate_id = g.crossfit_affiliate_id
                                WHERE
                                    IFNULL(hide_from_results,0) = 0
                                ORDER BY 
                                    #g.preferred_order, #This will have to be done in the UI
                                    distance 
                                LIMIT ".$limit." 
                            ";
		
		$query		= $this->db->query($sql);	

		return $query->result();

	}
}

/* End of file affiliates_model.php */
/* Location: ./application/models/affiliates_model.php */