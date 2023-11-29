<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Cryptlib.php';
class user_profile extends CI_Controller
{
    private $menu = "Home";
    private $idx_base_url = 'user_profile/ajaxPaginationData';
    private $model_name = 'user_profile_model';
    private $page_topic = 'user_profile';
    private $views_folder_group = 'user_profile/';
    private $controller = 'users_management/user_profile';
    private $user_id = '';
    private $user_name = '';
    private $prefix_edit = 'edit';
    private $key_edit = 'roles_id';
    private $page_title = "User Profile";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('common');
        $this->load->helper('users');
        $this->load->helper('grid_generator');
        $this->load->helper('bootstrap_object');
        $this->load->library('Ajax_pagination');
        $this->load->model($this->model_name);
        $this->user_id = $this->session->userdata('user_id');
        $this->user_name = $this->session->userdata('user_name');
        if (!$this->session->has_userdata('isLoggedIn') || !$this->session->isLoggedIn) {
            header('Location: ' . base_url('auth/login'));
            exit;
        }
    }

    public function index()
    {
        $converter = new Encryption;
        $nm_model = $this->model_name;
        $dtl_profile = $this->$nm_model->getRows($this->user_id);
        // $list_customer = $this->$nm_model->get_ero_list_customer($this->user_id);
        // $sign_pic = base_url().'assets/images/profile/'.$dtl_profile->signature_pic;
        // $sign_pic = ($dtl_profile->signature_pic=='') ? '': '<img src="'.$sign_pic.'" style="height:70px;" >';
        
        $data = array(
            'controller' => $this->controller,
            'page_title' => $this->page_title,
            'webcontent' => $this->views_folder_group . $this->page_topic,
            'root' => $this->menu,
            'dtl_profile' => $dtl_profile,
            // 'list_customer' => $list_customer,
            // 'sign_pic'=>$sign_pic,
            'decrypt_pwd' => $converter->decode($dtl_profile->pwd),
        );

        $this->load->view('layout_adm/wrapper_content', $data);
    }


    function update_profile_pic()
    {
        $nm_model = $this->model_name;
        $config['upload_path']   = FCPATH . 'assets/images/profile/';
        $config['allowed_types'] = 'gif|jpg|png|ico';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('pic_profile')) {
            $error = $this->upload->display_errors();
            $arr_response = array(
                'status' => 'error',
                'msg' => $error,
            );
            echo json_encode($arr_response);
        } else {
            //if no error then save the data with image info included
            $upload_data = $this->upload->data();
            $source_path = FCPATH . 'assets/images/profile/';
            $target_path = FCPATH . 'assets/images/profile/';
            $img_path    = FCPATH . 'assets/images/profile/' . $upload_data['file_name'];

            $size           = filesize($img_path);
            if ($size > 66627) {
                resizeImage($upload_data['file_name'], $source_path, $target_path);
            }

            $fname = "profile" . $this->user_id;
            $fname = $fname . $upload_data['file_ext'];

            rename($upload_data['full_path'], $upload_data['file_path'] . $fname);

            $token = $this->input->post('token_foto');

            $arr_pic = array(
                'profile_pic' => $fname
                // 'token_img' => $token
            );
            $update_result =  $this->$nm_model->update_profile_pic($arr_pic, $this->user_id);
            echo $update_result;
        }
    }

    function update_user_info()
    {  
        $converter = new Encryption;
        $nm_model = $this->model_name;     
        parse_str($this->input->post('user_info'), $user_info);
        $id = $this->user_id;
        // $filename = md5(date("dmYhisA"));
        // $file_name_with_ext =  $filename . '.png';
        $dtarray_user_info = array(
            'full_name' => $user_info['full_name'],
            'email' =>$user_info['email'],
            'pwd' => $converter->encode($user_info['pwd']),
            // 'signature_pic'=>$file_name_with_ext,
            'update_date' => getsysdate(),
            'update_by'=>$this->user_id
        ); 
        // $imagedata = base64_decode($this->input->post('img_data'));
        // $update_data =  $this->$nm_model->update_user_info($dtarray_user_info, $file_name_with_ext, $imagedata,  $id);
        $update_data =  $this->$nm_model->update_user_info($dtarray_user_info,  $id);
        echo json_encode($update_data);
      
    }

    function edit_data()
    {
        $converter = new Encryption;
        $nm_model = $this->model_name;
        $id = $this->input->post('the_id');
        $init_data = $this->$nm_model->get_dataforinit_user_profile($id);
        $arr_init_menu_selected = $this->$nm_model->generate_tree_menu_array_init($init_data->roles_id);
        $data = array(
            'controller' => $this->controller,
            'key_edit' => $this->key_edit,
            'prefix_edit' => $this->prefix_edit,
            'id_edit_name' => $this->key_edit . $this->prefix_edit,
            'webcontent' => $this->page_topic . '_edit',
            'root' => $this->menu,
            'dt_init' => $init_data,
            'decrypt_pwd' => $converter->decode($init_data->pwd),
            'arr_init_menu_selected' => $arr_init_menu_selected
        );
        $this->load->view($this->views_folder_group . $this->page_topic . '_edit', $data);
    }

    public function update_data()
    {
        $nm_model = $this->model_name;
        $id = $this->input->post($this->key_edit . $this->prefix_edit);

        $selected_menu = $this->input->post('selected_menu' . $this->prefix_edit);
        $arr_selected_menu =  explode(",", $selected_menu);
        $arr_selected_menu_distnct = array_unique($arr_selected_menu);

        $roles_name = $this->input->post('roles_name' . $this->prefix_edit);
        $criteria = "roles_name = '$roles_name' AND roles_id <> '$id' ";
        if (is_data_exist("sys_roles", "roles_name",  $criteria)) {
            $arr_response = array(
                'error' => 'true',
                'msg' => 'User Group <span class="badge badge-warning">' . $roles_name . '</span>' . MSG_DATA_ALREADY_EXIST,
            );
            echo json_encode($arr_response);
        } else {
            $dtarray_roles = array(
                'roles_name' => $this->input->post('roles_name' . $this->prefix_edit),
                'roles_desc' => $this->input->post('roles_desc' . $this->prefix_edit),
                'update_by' => $this->user_id,
                'update_date' => getsysdate()
            );
            $update_dt = $this->$nm_model->update_data($dtarray_roles, $arr_selected_menu_distnct, $id);
            echo json_encode($update_dt);
        }
    }

    public function get_tree_menu_array()
    {
        $nm_model = $this->model_name;
        $tree_item = $this->$nm_model->generate_tree_menu_array();
        echo json_encode($tree_item);
    }

    public function get_tree_menu_array_init()
    {
        $nm_model = $this->model_name;
        $tree_item = $this->$nm_model->generate_tree_menu_array();
        echo json_encode($tree_item);
    }

    public function row_delete()
    {
        $nm_model = $this->model_name;
        $arr_id = $this->input->post('id_content');
        echo $this->$nm_model->deletedata($arr_id);
    }
}
