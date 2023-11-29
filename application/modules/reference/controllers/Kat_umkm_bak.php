<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kat_umkm extends CI_Controller
{
    private $menu = 'Home';
    private $idx_base_url = 'kat_umkm/ajaxPaginationData';
    private $model_name = 'kat_umkm_model';
    private $page_topic = 'kat_umkm';
    private $controller = 'reference/kat_umkm';
    private $views_folder_group = 'kat_umkm/';
    private $user_id = '';
    private $key_edit = 'id_kat_umkm';
    private $search_place_holder = 'Untuk pencarian data, ketik Nama Kategori UMKM, lalu tekan enter';
    private $col_title = array('Kategori UMKM', 'Create At', 'Update At');
    private $list_field = array('name', 'create_at', 'update_at');
    private $page_title = 'Data Kategori UMKM';
    private $table_width = '100';
    private $tblname = 'tbl_kat_umkm';

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
        $this->perPage = get_sys_setting('004');
        $this->user_id = $this->session->userdata('user_id');
        $this->user_name = $this->session->userdata('user_name');

        $sessionArray = array(
            'sessid' => $this->input->get('sessid')
        );

        if (!empty($this->input->get('sessid'))) {
            if ($this->session->userdata('sessid') != $this->input->get('sessid')) {
                $this->session->set_userdata($sessionArray);
            }
        }

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

        // $user_privilege = get_user_privilege();
        // if ($user_privilege) {
        //     //if full access
        //     if ($user_privilege->action_privilege == '2') {
        //         $access = 1;
        //     } else {
        //         $access = 0;
        //     }
        // } else {
        //     $access = 1;
        // }
        $access = 1;

        $component = array(
            'controller' => $this->controller,
            'rowcount' => $totalRec
        );

        $list_data = generateGridElement($this->col_title, $this->list_field, $data_src, $this->key_edit,  $this->table_width, $this->tblname, '', '', 'edit', $access, '', $access, $component);

        $data = array(
            'controller' => $this->controller,
            'page_title' => $this->page_title,
            'webcontent' => $this->views_folder_group . $this->page_topic,
            'root' => $this->menu,
            'icon_page' => 'news-paper',
            'list_data' => $list_data,
            'search_place_holder' => $this->search_place_holder,
            'access' => $access
        );
        $this->load->view('layout_adm/wrapper_content', $data);
    }

    public function ajaxPaginationData()
    {
        $nm_model = $this->model_name;
        $conditions = array();
        //set conditions for search
        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        $rownum = $this->input->post('rownum');
        if (!empty($keywords)) {
            $conditions['search']['keywords'] = $keywords;
        }
        if (!empty($sortBy)) {
            $conditions['search']['sortBy'] = $sortBy;
        }
        if (!empty($rownum)) {
            $conditions['perPage'] = $rownum;
        }

        $rowcount = $this->$nm_model->getCountRows($conditions);
        $paging_url = base_url() . $this->idx_base_url;
        $data_src = $this->ajaxPaginationDatax($conditions, $rowcount, 'searchFilter', $paging_url);
        // $user_privilege = get_user_privilege();
        // if ($user_privilege) {
        //     //if full access
        //     if ($user_privilege->action_privilege == '2') {
        //         $access = 1;
        //     } else {
        //         $access = 0;
        //     }
        // } else {
        //     $access = 1;
        // }
        $access = 1;
        $component = array(
            'controller' => $this->controller,
            'rowcount' => $rowcount
        );
        $list_data = generateGridElement($this->col_title, $this->list_field, $data_src, $this->key_edit,  $this->table_width, $this->tblname, '', '', 'edit', $access, '', $access, $component);
        $data = array(
            'controller' => $this->controller,
            'search_place_holder' => $this->search_place_holder,
            'list_data' => $list_data,
            'access' => $access
        );
        $this->load->view($this->views_folder_group . $this->page_topic . '_data' . '', $data, false);
    }

    public function ajaxPaginationDatax($conditions, $rowcount, $func_name_from_view, $paging_url)
    {
        $nm_model = $this->model_name;
        $page = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }

        //pagination configuration

        //if filter perpage available, set record perpage from filter in view 
        if (!empty($conditions['perPage'])) {
            $this->perPage = $conditions['perPage'];
        }

        $config['target'] = '#postList';
        $config['base_url'] = $paging_url;
        $config['total_rows'] = $rowcount;
        $config['per_page'] = $this->perPage;
        $config['link_func'] = $func_name_from_view;
        //'searchFilter';
        $config['uri_segment'] = 4;
        $this->ajax_pagination->initialize($config);

        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;

        return $this->$nm_model->getRows($conditions);
    }

    public function displayInputForm()
    {
        $data = array(
            'controller' => $this->controller,
            'form_input_title' => 'Input Data kategori umkm',
            'root' => $this->menu,
        );
        $this->load->view($this->views_folder_group . $this->page_topic . '_add', $data);
    }

    public function saveData()
    {
        $nm_model = $this->model_name;
        $validate_value = $this->input->post('name');
        if (is_data_exist('tbl_kat_umkm', 'name', "name = '$validate_value'")) {
            $arr_response = array(
                'error' => 'true',
                'msg' => 'Nama kategori umkm <span class="badge badge-warning">' . $validate_value . '</span>' . MSG_DATA_ALREADY_EXIST,
            );
            echo json_encode($arr_response);
        } else {
            $dtarray = array(
                'name' => $this->input->post('name'),
                'create_at' => getsysdate(),
                'create_by' =>  $this->user_id
            );
            $result =  $this->$nm_model->insertData($dtarray);
            echo json_encode($result);
        }
    }

    public function editData()
    {
        $nm_model = $this->model_name;
        $id = $this->input->post('the_id');
        $init_data = $this->$nm_model->getDataForInit($id);

        $data = array(
            'controller' => $this->controller,
            'key_edit' => $this->key_edit,
            'id_edit_name' => $this->key_edit,
            'form_input_title' => 'Edit Data Kategori UMKM',
            'root' => $this->menu,
            'dt_init' => $init_data,
        );
        $this->load->view($this->views_folder_group . $this->page_topic . '_edit', $data);
    }

    public function updateData()
    {
        $nm_model = $this->model_name;
        $id = $this->input->post($this->key_edit);

        $dtarray = array(
            'name' => $this->input->post('name'),
            'update_at' => getsysdate(),
            'update_by' => $this->user_id
        );

        $result = $this->$nm_model->updateData($dtarray, $id);
        echo json_encode($result);
    }

    public function rowDelete()
    {
        $nm_model = $this->model_name;
        $arr_id = $this->input->post('id_content');
        echo $this->$nm_model->deleteData($arr_id);
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
    public function rwByIdKel()
    {
        $nm_model = $this->model_name;
        $idkel = $this->input->post('kodex');
        $this->$nm_model->getRwByIdKel($idkel);
    }
}
