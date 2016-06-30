<?php
/** 
 * Legacy_model
 * 
 * @author Ray Nowell
 *	
 */ 
class Legacy_model extends CI_Model {
	
	function Legacy_model()
	{
		parent::__construct();
	}
        
        public function get_faf_history()
	{
		$sql	=   "
                                SELECT 
                                    created_date
                                    ,faf_source
                                    , CASE WHEN IFNULL(processed,0) = 0 THEN 
                                            CASE WHEN IFNULL(search_term,'') = '' THEN  CONCAT_WS(',',latitude,longitude) 
                                            ELSE search_term END
                                            ELSE 
                                                CASE WHEN IFNULL(search_term,'') = '' THEN  
                                                    CASE WHEN country_political_short_code = 'US' THEN CONCAT_WS(', ',locality_political, administrative_area_level_1) 
                                                    ELSE CONCAT_WS(', ',country_political_long_code, administrative_area_level_1) END
                                                ELSE
                                                    REPLACE(REPLACE(search_term,'%20',' '),'%2C',',')
                                                END
                                        END AS location_data
                                FROM 
                                    stats fafs 
                                ORDER BY 
                                        fafs.created_date DESC
                                LIMIT 200";

		$query = $this->db->query($sql);
		
		if ($query->num_rows() == 0)
				return false;
		
		return $query->result_array();		
	}
        
        public function processs_record($data)
        {            
            $data['modified_date']	=	date("Y-m-d H:i:s");
            $this->db->update('stats',   $data, 'stats_id = '.$data['stats_id']);
        }
        
        public function get_unprocessed_faf_records()
        {
		$sql	=   "
                                SELECT 
                                        stats_id
                                        ,latitude
                                        ,longitude
                                        ,ifnull(search_term,'') as search_term
                                FROM 
                                        stats fafs 
                                WHERE
                                        ifnull(processed,0) = 0 AND 
                                        ifnull(latitude,0) <> 0
                                ORDER BY 
                                        fafs.created_date DESC";

		$query = $this->db->query($sql);
		
		if ($query->num_rows() == 0)
				return false;
		
		return $query->result_array();		            
        }
        
        function save_stat($data=null)
        {
            if ($data == null)
                return;
            
            $data['modified_date']	=	date("Y-m-d H:i:s");
            $data['created_date']       =       date('Y-m-d H:i:s');

       
            $this->db->insert('stats', $data);             
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
							affiliates f
						ORDER BY 
							distance
						LIMIT ".$results_count." 
						";
		
		$query		= $this->db->query($sql);	

		return $query->result_array();

	}
	
	
}

/* End of file legacy_model.php */
/* Location: ./application/models/legacy_model.php */