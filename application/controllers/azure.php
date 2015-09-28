<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Azure extends CI_Controller {
    
    public function index()
    {
        $data['view']   =   'generic';
        $this->load->vars($data);
        $this->load->view('master', $data);

        return;
    }
    
    public function azure_update()
    {
        $user_name		=	$this->db->username;
        $password		=	$this->db->password;
        $database_name          =	$this->db->database;
        $host			=	$this->db->hostname;
        $file_name		=	'MySqlDailyUpdates.sql';
        $full_file_name         =       '/var/findafit/uploads/MySqlDailyUpdates.sql';

        // assuming file.zip is in the same directory as the executing script.
        $file = '/var/findafit/uploads/MySqlDailyUpdates.zip';

        // get the absolute path to $file
        $path = pathinfo(realpath($file), PATHINFO_DIRNAME);
            
        $zip = new ZipArchive;
        $res = $zip->open($file);
        if ($res === TRUE) {
            // extract it to the path we determined above
            $zip->extractTo($path);
            $zip->close();
            echo "$file extracted to $path<br>";

        } else {
            echo "Couldn't open $file<br>";
            return;
        }

        exec('mysql --user='.$user_name.' --password='.$password.' --host='.$host.' '.$database_name.' < '.$full_file_name);
        
        unlink($full_file_name);
    }
}
/* End of file azure.php */
/* Location: ./application/controllers/azure.php */