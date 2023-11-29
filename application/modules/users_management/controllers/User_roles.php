<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_roles extends CI_Controller
{
    private $menu = "Home";
    private $idx_base_url = 'user_roles/ajaxPaginationData';
    private $model_name = 'User_roles_model';
    private $page_topic = 'user_roles';
    private $views_folder_group = 'user_roles/';
    private $controller = 'users_management/user_roles';
    private $user_id = '';
    private $prefix_edit = 'edit';
    private $key_edit = 'roles_id';
    private $search_place_holder = "Untuk pencarian data, ketik nama User Group, lalu tekan enter";
    private $col_title = array('User Group', 'Keterangan', 'Menu yang bisa diakses');
    private $list_field = array('roles_name', 'roles_desc', 'list_menu');
    private $page_title = "User Group";
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
        $totalRec = ($this->$nm_model->getRows()) ? count($this->$nm_model->getRows()) : 0;

        //pagination configuration
        $config['target'] = '#postList';
        $config['base_url'] = base_url() . $this->idx_base_url;
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;
        $config['link_func'] = 'searchFilter';
        $this->ajax_pagination->initialize($config);

        $data_src = $this->$nm_model->getRows(array('limit' => $this->perPage));
        $custom_content[] =  array('custom_field' => 'list_menu', 'content' => 'isi');
        $component = array(
            'controller' => $this->controller,
            'rowcount' => $totalRec
        );
        $list_data = generateGridElement($this->col_title, $this->list_field, $data_src, $this->key_edit,  $this->table_width, $this->tblname, $custom_content, 'populate_list_menu', 'edit', true, '', true, $component);

        $data = array(
            'controller' => $this->controller,
            'page_title' => $this->page_title,
            'webcontent' => $this->views_folder_group . $this->page_topic,
            'root' => $this->menu,
            'icon_page' => 'tools',
            'form_input_title' => 'Input ' . $this->page_title,
            'form_edit_title' => 'Edit ' . $this->page_title,
            'list_data' => $list_data,
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
        $rowcount = ($this->$nm_model->getRows($conditions)) ? count($this->$nm_model->getRows($conditions)) : 0;

        $paging_url = base_url() . $this->idx_base_url;
        $data_src = $this->ajaxPaginationDatax($conditions, $rowcount, 'searchFilter', $paging_url);
        $custom_content[] =  array('custom_field' => 'list_menu', 'content' => 'isi');
        $component = array(
            'controller' => $this->controller,
            'rowcount' => $rowcount
        );
        $list_data = generateGridElement($this->col_title, $this->list_field, $data_src, $this->key_edit,  $this->table_width, $this->tblname, $custom_content, 'populate_list_menu', 'edit', true, '', true, $component);
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
        $config['uri_segment'] = 3;
        $this->ajax_pagination->initialize($config);

        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;

        return $this->$nm_model->getRows($conditions);
    }

    function insert_data()
    {
        $nm_model = $this->model_name;
        $selected_menu = $this->input->post('selected_menu');
        $arr_selected_menu =  explode(",", $selected_menu);
        $arr_selected_menu_distnct = array_unique($arr_selected_menu);
        $roles_name = $this->input->post('roles_name');
        $criteria = "roles_name = '$roles_name'";
        if (is_data_exist("sys_roles", "roles_name",  $criteria)) {
            $arr_response = array(
                'error' => 'true',
                'msg' => 'User Group <span class="badge badge-warning">' . $roles_name . '</span>' . MSG_DATA_ALREADY_EXIST,
            );
            echo json_encode($arr_response);
        } else {
            $dtarray_roles = array(
                'roles_id' => $this->input->post('roles_id'),
                'roles_name' => $this->input->post('roles_name'),
                'roles_desc' => $this->input->post('roles_desc'),
                'create_by' => $this->user_id,
                'create_date' => getsysdate()
            );
            $insert_dt = $this->$nm_model->insert_data($dtarray_roles, $arr_selected_menu_distnct);
            echo json_encode($insert_dt);
        }
    }

    function edit_data()
    {
        $nm_model = $this->model_name;
        $id = $this->input->post('the_id');
        $init_data = $this->$nm_model->get_dataforinit_user_roles($id);
        $arr_init_menu_selected = $this->$nm_model->generate_tree_menu_array_init($init_data->roles_id);
        $data = array(
            'controller' => $this->controller,
            'key_edit' => $this->key_edit,
            'prefix_edit' => $this->prefix_edit,
            'id_edit_name' => $this->key_edit . $this->prefix_edit,
            'webcontent' => $this->page_topic . '_edit',
            'root' => $this->menu,
            'dt_init' => $init_data,
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
