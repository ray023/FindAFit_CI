<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {
    
    public function _process_records()
    {                
        $json_row   =   '';
        $this->load->model('Find_a_fit_model');
        $unprocessed_records_array  =   $this->Find_a_fit_model->get_unprocessed_faf_records();

        if (!$unprocessed_records_array)
            return true;

        foreach($unprocessed_records_array as $row) 
        {
            $update_array =   array(
                                        'faf_stats_id'  =>  $row['faf_stats_id'], 
                                        'processed'     =>  true);

            if ($row['search_term']   === '')
            {
                $return_value   =   file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$row['latitude'].','.$row['longitude'].'&key=AIzaSyBecnfstyx5PtF6nJaieEhlP3DpCwTRTzU');
                $json = json_decode($return_value, true);

                if (is_array($json))
                {
                    if ( $json["status"] === "OK" )
                    {
                        foreach($json["results"] as $json_row) 
                        {
                            if (!is_array($json_row))
                                continue;
                            foreach($json_row as $address_component_row)
                            {
                                if (!is_array($address_component_row))
                                    continue;

                                foreach($address_component_row as $component)
                                {
                                    if (!is_array($component) || !array_key_exists('types',$component))
                                        continue;

                                    if (in_array('locality', $component['types']) && in_array('political', $component['types']))
                                            $update_array['locality_political']    =   $component['long_name'];
                                    elseif (in_array('administrative_area_level_1', $component['types']) && in_array('political', $component['types']))
                                            $update_array['administrative_area_level_1']    =   $component['long_name'];
                                    elseif (in_array('postal_code', $component['types']))
                                            $update_array['postal_code']    =   $component['long_name'];
                                    elseif (in_array('political', $component['types']) && in_array('country', $component['types']))
                                    {
                                            $update_array['country_political_long_code']   =   $component['long_name'];
                                            $update_array['country_political_short_code']  =   $component['short_name'];
                                    }

                                    if (array_key_exists('locality_political', $update_array) && 
                                        array_key_exists('administrative_area_level_1', $update_array) && 
                                        array_key_exists('postal_code', $update_array) && 
                                        array_key_exists('country_political_long_code', $update_array) )
                                            break;

                               }

                                if (array_key_exists('locality_political', $update_array) && 
                                    array_key_exists('administrative_area_level_1', $update_array) && 
                                    array_key_exists('postal_code', $update_array) && 
                                    array_key_exists('country_political_long_code', $update_array) )
                                        break;
                            }

                            if (array_key_exists('locality_political', $update_array) && 
                                    array_key_exists('administrative_area_level_1', $update_array) && 
                                    array_key_exists('postal_code', $update_array) && 
                                    array_key_exists('country_political_long_code', $update_array) )
                                        break;


                            }
                    }
                    else
                    {
                        $update_array   =   false;
                            //START AUDIT
                            $this->load->model('faf_model');
                            $audit_data['controller']	=	'faf_process_record';
                            $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
                            $audit_data['member_id']	=	$this->session->userdata('member_id');
                            $audit_data['member_name']	=	$this->session->usedata('display_name');
                            $audit_data['short_description']	=	'Error processing record';
                            $audit_data['full_info']	=	$json["status"];
                            $this->Audit_model->save_audit_log($audit_data);
                            //END AUDIT
                            return;
                    }
                }
                else
                {
                    $update_array   =   false;
                    //START AUDIT
                    $this->load->model('faf_model');
                    $audit_data['controller']	=	'faf_process_record';
                    $audit_data['ip_address']	=	$_SERVER['REMOTE_ADDR'];
                    $audit_data['member_id']	=	$this->session->userdata('member_id');
                    $audit_data['member_name']	=	$this->session->usedata('display_name');
                    $audit_data['short_description']	=	'Error processing record';
                    $audit_data['full_info']	=	'Json not an array';
                    $this->Audit_model->save_audit_log($audit_data);
                    //END AUDIT
                    return;
                }
            }


            if (!!$update_array)
                $this->Find_a_fit_model->processs_record($update_array);
       }

    }

    public function show_history()
    {

            $this->_process_records();

            $faf_history_list   =   '';
            $this->load->model('Find_a_fit_model');
            $faf_history_array	=	$this->Find_a_fit_model->get_faf_history();

            $alt_row = 1;
            $faf_history_list = '';
            foreach($faf_history_array as $row) 
            {
                    $is_odd         =	$alt_row%2==1;
                    $alt_row_class	=	$is_odd	? 'alternate-row' : '';
                    $faf_history_list	.=	'<div class="ui-block-a '.$alt_row_class.'">'.$row['created_date'].'</div>';
                    $faf_history_list	.=	'<div class="ui-block-b '.$alt_row_class.'">'.$row['faf_source'].'</div>';
                    $faf_history_list	.=	'<div class="ui-block-c '.$alt_row_class.'">'.$row['location_data'].'</div>';

                    $alt_row++;
            }

            $data['faf_history_list']	=	$faf_history_list;
            $data['title']				=	'FAF History';
            $data['heading']			=	'FAF History';
            $data['view']				=	'mobile_admin_show_faf_history';
            $this->load->vars($data);
            $this->load->view('mobile_master', $data);


    }
}

/* End of file stats.php */
/* Location: ./application/controllers/stats.php */



        