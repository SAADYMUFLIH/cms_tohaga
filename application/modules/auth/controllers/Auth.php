<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */

class Auth extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('session');
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->isLoggedIn();
    }

    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn()
    {
        $isLoggedIn =  $this->session->userdata('isLoggedIn');

        if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            $this->load->view('login');
        } else {
            redirect('main');
            echo "masih login=" . $isLoggedIn;
            print_r($this->session);
        }
    }




    public function login()
    {

        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $result = $this->Auth_model->loginme($email, $password);
        // echo "<pre>"; print_r($result); echo "</pre>"; die();
        if ($result) {
            $role_info = $this->Auth_model->get_roles_detil($result->user_id);
            $sessionArray = array(
                'user_id' => $result->user_id,
                'user_name' => (isset($result->user_name)?$result->user_name:''),
                'roles_id' => $role_info['roles_id'],
                'full_name' => $result->full_name,
                'isLoggedIn' => true,
                'roles_name' => $role_info['roles_name']
                //'is_admin' => $res->is_admin
            );

            $this->session->set_userdata($sessionArray);
            $is_admin = $this->session->userdata('is_admin');
            redirect('main');
        } else {
            if (isset($email)) {
                $this->session->set_flashdata('error', 'Email or password mismatch');
            }
            redirect('auth');
        }
    }
    function logout()
    {
        $this->session->sess_destroy();
        unset($_SESSION['isLoggedIn']);
        redirect('auth');
    }
}
