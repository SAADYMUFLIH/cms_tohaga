<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/Cryptlib.php';

class Ide extends CI_Controller
{
    private $menu = 'Home';
    private $idx_base_url = 'ide/ajaxPaginationData';
    private $model_name = 'ide_model';
    private $page_topic = 'ide';
    private $controller = 'main/ide';
    private $views_folder_group = 'ide/';
    private $user_id = '';
    private $key_edit = 'id_ide_startup';
    private $search_place_holder = 'Untuk pencarian data, ketik Judul, lalu tekan enter';
    private $col_title = array('Judul Ide Startup', 'Nama', 'UMKM', 'Tanggal');
    private $list_field = array('title', 'name', 'umkm', 'tanggal');
    private $page_title = 'Ide Startup';
    private $table_width = '100';
    private $tblname = 'tbl_ide_startup';

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

        $custom_content[] =  array('custom_field' => 'img', 'content' => '<a target="_blank" href="' . base_url() . 'assets/images/file_ide/', 'middle_content' => '>', 'last_content' => '</a>');

        $list_data = generateGridElement($this->col_title, $this->list_field, $data_src, $this->key_edit,  $this->table_width, $this->tblname, $custom_content, '', 'eye', $access, '', $access, $component);

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
        $custom_content[] =  array('custom_field' => 'img', 'content' => '<a target="_blank" href="' . base_url() . 'assets/images/file_ide/', 'middle_content' => '>', 'last_content' => '</a>');
        $list_data = generateGridElement($this->col_title, $this->list_field, $data_src, $this->key_edit,  $this->table_width, $this->tblname, $custom_content, '', 'eye', $access, '', $access, $component);
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
        // $cbo_cat = create_chosen_db_combo('id_skill_matrix', 'id_kat_ide', 'ref_skill_matrix', 'id_skill_matrix', 'nama_skill_matrix', 'id_skill_matrix', '', '', '', false);
        $data = array(
            'controller' => $this->controller,
            'form_input_title' => 'Input Data Ide Startup',
            'root' => $this->menu,
            // 'cbo_cat' => $cbo_cat,
            'id_edit_name' => $this->key_edit,
            'key_edit' => $this->key_edit,
        );
        $this->load->view($this->views_folder_group . $this->page_topic . '_add', $data);
    }

    // public function saveData()
    // {
    //     $nm_model = $this->model_name;
    //     $dtarray = array(
    //         'title' => $this->input->post('title'),
    //         'desc' => $this->input->post('desc'),
    //         'id_kat_ide' => $this->input->post('id_kat_ide'),
    //     );
    //     // $result =  $this->$nm_model->insertData($dtarray);
    //     if ($this->input->post($this->key_edit)) {
    //         $dtarray['update_at'] = getsysdate();
    //         $dtarray['update_by'] = $this->user_id;
    //         $id_ide = $this->input->post($this->key_edit);
    //         $result =  $this->$nm_model->updateData($dtarray, $id_ide);
    //     } else {
    //         $dtarray['create_at'] = getsysdate();
    //         $dtarray['create_by'] = $this->user_id;
    //         $result =  $this->$nm_model->insertData($dtarray);
    //         $id_ide = $result['id'];
    //     }

    //     if ($result['error'] == 'false') {
    //         $converter = new Encryption;
    //         $config['upload_path']   = FCPATH . 'assets/images/file_ide/';
    //         $config['allowed_types'] = 'pdf';
    //         $this->load->library('upload', $config);

    //         // $encrypted_name =  $converter->encode($id_ide);

    //         $arr_obj_file_upload = array(
    //             "img" => "File Ide"
    //         );
    //         $arr_document = array();
    //         $incomplete_doc = array();
    //         $arr_document['id_ide'] = $id_ide;
    //         $existing_image = '';

    //         // print_r($arr_obj_file_upload);

    //         foreach ($arr_obj_file_upload as $key => $value) {
    //             if (!$this->upload->do_upload($key)) {
    //                 //$error = $this->upload->display_errors();
    //                 $existing_image = get_info_by_id("tbl_ide", $key, "id_ide", $id_ide);
    //                 $incomplete_doc[] = $value;
    //                 $arr_document[$key] = $existing_image;
    //             } else {
    //                 //if no error then save the data with image info included
    //                 // $source_path = FCPATH . 'assets/images/file_ide/';
    //                 // $target_path = FCPATH . 'assets/images/file_ide/';
    //                 $upload_data = $this->upload->data();
    //                 // $img_path    = FCPATH . 'assets/images/file_ide/' . $upload_data['file_name'];
    //                 // $size         = filesize($img_path);

    //                 // if ($size > 66627) {
    //                 //     resizeImage($upload_data['img'], $source_path, $target_path);
    //                 // }

    //                 $fname = $upload_data['file_name'] .  date("Y-m-d H-i-s") . "_" . $id_ide;
    //                 $fname = $fname . $upload_data['file_ext'];

    //                 rename($upload_data['full_path'], $upload_data['file_path'] . $fname);

    //                 $arr_document[$key] =  $fname;
    //             }
    //         }
    //         $update_data = $this->$nm_model->update_doc($arr_document,  $id_ide);
    //         echo json_encode($update_data);
    //     }

    //     // echo json_encode($result);
    // }

    function remove_img()
    {
        $nm_model = $this->model_name;
        $id_pelamar = $this->input->post('the_id');
        $fid = $this->input->post('fid');

        $arr_img = array(
            $fid => '',
        );
        if ($id_pelamar == "") {
            $process_info = 'Penghapusan file berhasil';
        } else {
            $process_info =  $this->$nm_model->remove_img($arr_img, $id_pelamar, $fid);
        }
        echo $process_info;
    }

    public function editData()
    {
        $nm_model = $this->model_name;
        $id = $this->input->post('the_id');
        $init_data = $this->$nm_model->getDataForInit($id);
        $read_data = $this->$nm_model->readData($id);
        // $cbo_cat = create_chosen_db_combo('id_skill_matrix', 'id_kat_ide', 'ref_skill_matrix', 'id_skill_matrix', 'nama_skill_matrix', 'id_skill_matrix', '', $init_data->id_kat_ide, '', false);

        $data = array(
            'controller' => $this->controller,
            'key_edit' => $this->key_edit,
            'id_edit_name' => $this->key_edit,
            'form_input_title' => 'Data Ide Startup',
            'root' => $this->menu,
            'dt_init' => $init_data,
            // 'cbo_cat' => $cbo_cat,
        );
        $this->load->view($this->views_folder_group . $this->page_topic . '_add', $data);
    }

    // public function updateData()
    // {
    //     $nm_model = $this->model_name;
    //     $id = $this->input->post($this->key_edit);

    //     $dtarray = array(
    //         'title' => $this->input->post('title'),
    //         'id_kat_ide' => $this->input->post('id_kat_ide'),
    //         'img' => $this->input->post('foto'),
    //         'update_at' => getsysdate(),
    //         'update_by' => $this->user_id
    //     );

    //     $result = $this->$nm_model->updateData($dtarray, $id);
    //     echo json_encode($result);
    // }

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

    function ajaxNotifUpdate() {
        $nm_model = $this->model_name;
        $jumlah = $this->$nm_model->getUnred();
        echo $jumlah;
    }
}
