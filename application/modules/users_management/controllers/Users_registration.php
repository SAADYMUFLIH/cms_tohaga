<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


require APPPATH . '/libraries/Cryptlib.php';

class Users_registration extends CI_Controller
{
    private $menu = "Home";
    private $idx_base_url = 'users_registration/ajaxPaginationData';
    private $model_name = 'Users_registration_model';
    private $page_topic = 'users_registration';
    private $views_folder_group = 'registration/';
    private $controller = 'users_management/users_registration';
    private $user_id = '';
    private $prefix_edit = 'edit';
    private $key_edit = 'user_id';
    private $search_place_holder = "Untuk pencarian data, ketik nama user, lalu tekan enter";
    private $col_title = array('Nama user', 'Email', 'User Sebagai');
    private $list_field = array('full_name', 'email',  'list_role');
    private $page_title = "User Registration";
    private $table_width = '100';
    private $tblname = 'sys_users';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('common');
        $this->load->helper('users');
        $this->load->helper('grid_generator');
        $this->load->helper('bootstrap_object');
        $this->load->library('Ajax_pagination');
        $this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
        $this->load->model($this->model_name);
        $this->perPage = get_sys_setting("004");
        $this->user_id = $this->session->userdata('user_id');
        $this->user_name = $this->session->userdata('user_name');
        if (!$this->session->has_userdata('isLoggedIn') || !$this->session->isLoggedIn) {
            header('Location: ' . base_url('auth/login'));
            exit;
        }
    }

    public function index()
    {

        $nm_model = $this->model_name;
        $totalRec = $this->$nm_model->getCountRows();
        //pagination configuration
        $config['target'] = '#postList';
        $config['base_url'] = base_url() . $this->idx_base_url;
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;
        $config['link_func'] = 'searchFilter';
        $this->ajax_pagination->initialize($config);

        $data_src = $this->$nm_model->getRows(array('limit' => $this->perPage));
        $access = 1;

        $custom_content[] =  array('custom_field' => 'list_role', 'content' => '');

        $component = array(
            'controller' => $this->controller,
            'rowcount' => $totalRec
        );
        $list_data = generateGridElement($this->col_title, $this->list_field, $data_src, $this->key_edit,  $this->table_width, $this->tblname, $custom_content, 'populate_list_role', 'edit', $access, '',  $access, $component);

        $data = array(
            'controller' => $this->controller,
            'page_title' => $this->page_title,
            'webcontent' => $this->views_folder_group . $this->page_topic,
            'root' => $this->menu,
            'icon_page' => 'users',
            'form_input_title' => 'Input ' . $this->page_title,
            'form_edit_title' => 'Edit ' . $this->page_title,
            'list_data' => $list_data,
            'access' => $access,
            'search_place_holder' => $this->search_place_holder
        );
        $this->load->view('layout_adm/wrapper_content', $data);
    }

    function ajaxPaginationData()
    {
        $nm_model = $this->model_name;
        $conditions = array();
        //set conditions for search
        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        if (!empty($keywords)) {
            $conditions['search']['keywords'] = $keywords;
        }
        if (!empty($sortBy)) {
            $conditions['search']['sortBy'] = $sortBy;
        }
        $rowcount = $this->$nm_model->getCountRows($conditions);

        $paging_url = base_url() . $this->idx_base_url;
        $data_src = $this->ajaxPaginationDatax($conditions, $rowcount, 'searchFilter', $paging_url);
        $custom_content[] =  array('custom_field' => 'list_role', 'content' => '');
        $component = array(
            'controller' => $this->controller,
            'rowcount' => $rowcount
        );
        $list_data = generateGridElement($this->col_title, $this->list_field, $data_src, $this->key_edit,  $this->table_width, $this->tblname, $custom_content, 'populate_list_role', 'edit', true, '', true, $component);
        $data = array(
            'controller' => $this->controller,
            'search_place_holder' => $this->search_place_holder,
            'list_data' => $list_data
        );
        $this->load->view($this->views_folder_group . $this->page_topic . '_data' . '', $data, false);
    }

    function ajaxPaginationDatax($conditions, $rowcount, $func_name_from_view, $paging_url)
    {
        $nm_model = $this->model_name;
        $page = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }

        //pagination configuration
        $config['target'] = '#postList';
        $config['base_url'] = $paging_url;
        $config['total_rows'] = $rowcount;
        $config['per_page'] = $this->perPage;
        $config['link_func'] = $func_name_from_view; //'searchFilter';
        $config['uri_segment'] = 4;
        $this->ajax_pagination->initialize($config);

        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;

        return $this->$nm_model->getRows($conditions);
    }

    public function displayInputForm()
    {
        // $cbo_jabatan = create_chosen_db_combo('id_jabatan', 'id_jabatan', 'ref_jabatan', 'id_jabatan', 'nama_jabatan', 'id_jabatan', '', '', '', false);
        // $cbo_spv = create_chosen_db_combo('user_id', 'id_spv', 'sys_users', 'sys_users.user_id', 'full_name', 'sys_users.user_id', '', "", 'left JOIN tbl_penugasan b ON sys_users.`user_id`=b.`user_id` where (is_terminate = 0 OR is_terminate IS NULL) AND sys_users.`user_id` != '.$this->user_id, false);
        // $cbo_spv = create_chosen_db_combo('user_id', 'id_spv', 'sys_users', 'sys_users.user_id', 'full_name', 'sys_users.user_id', '', "", 'where (is_terminate = 0 OR is_terminate IS NULL) AND role_id IN (2,6) AND sys_users.`user_id` != '.$this->user_id, false, '', false);
        $cbo_role = create_chosen_db_combo('roles_id', 'roles_id', 'sys_roles', 'roles_id', 'roles_name', 'roles_id', '', '', 'where roles_id NOT IN (4,5,7)', false);
        $data = array(
            'controller' => $this->controller,
            'form_input_title' => 'User Registration',
            'root' => $this->menu,
            // 'cbo_spv' => $cbo_spv,
            // 'cbo_jabatan' => $cbo_jabatan,
            'cbo_role' => $cbo_role
        );
        $this->load->view($this->views_folder_group . $this->page_topic . '_add', $data);
    }

    function saveData()
    {
        $nm_model = $this->model_name;
        $email = $this->input->post('email');
        $criteria = "email = '$email'";
        if (is_data_exist("sys_users", "email",  $criteria)) {
            $arr_response = array(
                'error' => 'true',
                'msg' => 'Email  <span class="badge badge-warning">' . $email . '</span>' . MSG_DATA_ALREADY_EXIST,
            );
            echo json_encode($arr_response);
        } else {
            $converter = new Encryption;
            $dtarray_users = array(
                'email' => strtolower($this->input->post('email')),
                'pwd' => $converter->encode($this->input->post('password')),
                'full_name' => $this->input->post('full_name'),
                'role_id' => $this->input->post('roles_id'),
                'create_by' => $this->user_id,
                'create_at' => getsysdate()
            );

            $list_role = $this->input->post('roles_id');

            $insert_dt = $this->$nm_model->insert_data($dtarray_users, $list_role);
            echo json_encode($insert_dt);
        }
    }

    function editData()
    {
        $nm_model = $this->model_name;
        $id = $this->input->post('the_id');
        $init_data = $this->$nm_model->get_dataforinit($id);
        // $selected_role = $this->$nm_model->get_data_role_forinit($id);
        
        // $cbo_jabatan = create_chosen_db_combo('id_jabatan', 'id_jabatan', 'ref_jabatan', 'id_jabatan', 'nama_jabatan', 'id_jabatan', '', '', '', false);
        // $cbo_spv = create_chosen_db_combo('user_id', 'id_spv', 'sys_users', 'sys_users.user_id', 'full_name', 'sys_users.user_id', '', "", 'left JOIN tbl_penugasan b ON sys_users.`user_id`=b.`user_id` where (is_terminate = 0 OR is_terminate IS NULL) AND sys_users.`user_id` != '.$this->user_id, false);
        // $cbo_spv = create_chosen_db_combo('user_id', 'id_spv', 'sys_users', 'sys_users.user_id', 'full_name', 'sys_users.user_id', '', $init_data->id_spv, 'where (is_terminate = 0 OR is_terminate IS NULL) AND role_id IN (2,6) AND sys_users.`user_id` != '.$this->user_id, false, '', false);
        $cbo_role = create_chosen_db_combo("roles_id", "roles_id", "sys_roles", "roles_id", "roles_name", "roles_id", "", $init_data->role_id, "where roles_id NOT IN (4,5,7)", false);

        $data = array(
            'controller' => $this->controller,
            'key_edit' => $this->key_edit,
            'id_edit_name' => $this->key_edit,
            'form_input_title' => 'Edit Data User Registration',
            'root' => $this->menu,
            'dt_init' => $init_data,
            // 'cbo_spv' => $cbo_spv,
            // 'cbo_jabatan' => $cbo_jabatan,
            'cbo_role' => $cbo_role
        );
        $this->load->view($this->views_folder_group . $this->page_topic . '_edit', $data);
    }

    public function updateData()
    {
        $nm_model = $this->model_name;
        $converter = new Encryption;
        $id = $this->input->post('user_id_real');

        $converter = new Encryption;
        $dtarray_users = array(
            'email' => strtolower($this->input->post('email')),
            'pwd' => $converter->encode($this->input->post('password')),
            'full_name' => $this->input->post('full_name'),
            'role_id' => $this->input->post('roles_id'),
            'update_at' => getSysDate(),
            'update_by' => $this->user_id
        );

        $list_role = $this->input->post('roles_id');

        $update_dt = $this->$nm_model->update_data($dtarray_users, $list_role, $id);
        echo json_encode($update_dt);
    }

    public function rowDelete()
    {
        $nm_model = $this->model_name;
        $arr_id = $this->input->post('id_content');
        echo $this->$nm_model->deletedata($arr_id);
    }

    function exportToExcel()
    {
        $nm_model = $this->model_name;

        $conditions = array();
        //set conditions for search
        $keywords   = $this->input->post('keywords');
        $sortBy     = $this->input->post('sortBy');
        if (!empty($keywords)) {
            $conditions['search']['keywords'] = $keywords;
        }
        if (!empty($sortBy)) {
            $conditions['search']['sortBy'] = $sortBy;
        }

        $data_src = $this->$nm_model->getRows($conditions);
        $this->$nm_model->exportToExcel($data_src);
    }
}
