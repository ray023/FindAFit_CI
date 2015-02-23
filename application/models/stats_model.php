<?php
/** 
 * Stats_model
 * 
 * @author Ray Nowell
 *	
 */ 
class Stats_model extends CI_Model {
	
	function Stats_model()
	{
		parent::__construct();
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
                                        stats s
                                WHERE
                                        ifnull(processed,0) = 0 
                                ORDER BY 
                                        s.created_date DESC";

		$query = $this->db->query($sql);
		
		if ($query->num_rows() == 0)
				return false;
		
		return $query->result_array();		            
        }
        
        public function get_history()
	{
		$sql	=   "
                                SELECT 
                                    created_date
                                    , faf_source
                                    , CASE WHEN IFNULL(processed,0) = 0 THEN 
                                            CASE WHEN IFNULL(search_term,'') = '' THEN  CONCAT_WS(',',latitude,longitude) 
                                            ELSE search_term END
                                            ELSE 
                                                CASE WHEN IFNULL(search_term,'') = '' THEN  
                                                    CASE WHEN country_political_short_code = 'US' THEN CONCAT_WS(', ',locality_political, administrative_area_level_1) 
                                                    ELSE CONCAT_WS(', ',country_political_long_code, administrative_area_level_1) END
                                                ELSE
                                                    search_term 
                                                END
                                        END AS location_data
                                FROM 
                                    stats s 
                                ORDER BY 
                                        s.created_date DESC";

		$query = $this->db->query($sql);
		
		if ($query->num_rows() == 0)
				return false;
		
		return $query->result_array();		
	}
        
        public function save_stat($data=null)
        {
            if ($data == null)
                return;
            
            $data['modified_date']	=	date("Y-m-d H:i:s");
            $data['created_date']       =       date('Y-m-d H:i:s');

       
            $this->db->insert('stats', $data);             
        }
        
}

/* End of file stats_model.php */
/* Location: ./application/models/stats_model.php */