<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/Cryptlib.php';
class Auth_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('common');
        $this->load->database();
    }
    /**
     * This function used to check the login credentials of the user
     * @param string $username : This is email of the user
     * @param string $password : This is encrypted password of the user
     */

    public function loginme_api($username, $password)
    {
        $data = array(
            'email' => $username,
            'pwd' => $password
        );

        $url = 'auth/loginadm';
        $response = postCURL($url, $data);
        return $response;
    }

    function loginme($username, $password)
    {
        $converter = new Encryption;
        $pwd = $converter->encode($password);
        $this->db->select("user_id, full_name, pwd");
        $this->db->from("sys_users");
        $this->db->where("email ='$username' AND pwd = '$pwd'");
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;
	
    }

    function get_roles_detil($user_id){
        $this->db->select("ur.roles_id, ur.user_id, r.roles_name");
        $this->db->from("sys_user_roles AS ur");
        $this->db->join("sys_roles AS r","ur.roles_id = r.roles_id","INNER");
        $this->db->where("ur.user_id='$user_id'");
        $query=$this->db->get(); 
        $arr_roles_id = array();
        $arr_roles_name = array();
        foreach ($query->result() as $row) {
            $arr_roles_id[] = $row->roles_id;
            $arr_roles_name[] = $row->roles_name; 
        }

        $roles_id = "'" . implode ( "', '", $arr_roles_id ) . "'";
        $roles_name = implode ( " | ", $arr_roles_name );
        $list_role_info = array(
            'roles_id' => $roles_id,
            'roles_name'=> $roles_name
        );

       return $list_role_info;

    }
}
