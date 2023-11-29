<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{
	private $menu = "Home";
	private $model_name = 'Dashboard_model';
	private $page_topic = 'dashboard';
	private $controller = 'main';
	private $page_title = "Dashboard";
	function __construct()
	{
		parent::__construct();
		$this->load->helper('common');
		$this->load->library('session');
		$this->load->helper('users');
		$this->load->model($this->model_name);
		$this->user_id = $this->session->userdata('user_id');
		$this->clear_cache();
		if (!$this->session->has_userdata('isLoggedIn') || !$this->session->isLoggedIn || !$this->session->userdata('roles_id')) {
			header('Location: ' . base_url('auth/login'));
			exit;
		}
	}

	public function index()
	{
		$nm_model = $this->model_name;

		$cbo_provinsi = create_chosen_db_combo('id_provinsi', 'id_provinsi', 'ref_provinsi', 'id_provinsi', 'nama_provinsi', 'nama_provinsi', '');
		$cbo_kota = create_chosen_db_combo('id_kabupaten', 'id_kabupaten', 'ref_kabupaten', 'id_kabupaten', 'nama_kabupaten', 'nama_kabupaten', '', '', 'WHERE id_kabupaten is null');
		$cbo_kec = create_chosen_db_combo('id_kecamatan', 'id_kecamatan', 'ref_kecamatan', 'id_kecamatan', 'nama_kecamatan', 'nama_kecamatan', '', '', 'WHERE id_kecamatan is null');
		$cbo_kel = create_chosen_db_combo('id_kelurahan', 'id_kelurahan', 'ref_kelurahan', 'id_kelurahan', 'nama_kelurahan', 'nama_kelurahan', '', '', 'WHERE id_kelurahan is null');
		$cbo_kat_prod = create_chosen_db_combo('id_kat_prod', 'id_kat_prod', 'tbl_kat_prod', 'id_kat_prod', 'name', 'name', '', '', 'GROUP BY name');
		$cbo_bb = create_chosen_db_combo('id_bahan_baku', 'id_bahan_baku', 'tbl_bahan_baku', 'id_bahan_baku', 'name', 'name', '', '', 'GROUP BY name');

		$data = array(
			'total_user' => $this->$nm_model->get_total_user(),
			'total_umkm' => $this->$nm_model->get_total_umkm(),
			'total_produk' => $this->$nm_model->get_total_produk(),
			'cbo_provinsi' => $cbo_provinsi,
			'cbo_kota' => $cbo_kota,
			'cbo_kec' => $cbo_kec,
			'cbo_kel' => $cbo_kel,
			'cbo_kat_prod' => $cbo_kat_prod,
			'cbo_bb' => $cbo_bb,
			'controller' => $this->controller,
			'page_title' => $this->page_title,
			'webcontent' => $this->page_topic,
			'icon_page' => 'graph2',
			'root' => $this->menu
		);

		if ($this->input->get("q") == '1') {
			$this->load->view('layout_adm/wrapper_content', $data);
		} else {
			$this->load->view('layout_adm/wrapper', $data);
		}
	}

	public function showChart()
	{
			$nm_model = $this->model_name;
			$this->$nm_model->showChart();
			// echo json_encode($data);
	}

	public function kabupatenbyprov_id()
	{
			$nm_model = $this->model_name;
			$idWilayah = $this->input->post('kodex');
			$this->$nm_model->getkotaByIdProv($idWilayah);
	}

	public function kecamatanbykab_id()
	{
			$nm_model = $this->model_name;
			$idWilayah = $this->input->post('kodex');
			$this->$nm_model->getkecByIdKab($idWilayah);
	}


	public function kelurahanbykec_id()
	{
			$nm_model = $this->model_name;
			$idWilayah = $this->input->post('kodex');
			$this->$nm_model->getKelByIdKec($idWilayah);
	}

	function clear_cache()
	{
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
	}

	public function test1()
	{
		// $data = array(
		// 	'controller' => $this->controller,
		// 	'id_pkwt'    => $this->input->post('tid')
		// );
		// $pageContent = $this->load->view('testview', $data, true);
		// $data_view = array(
		// 	'web_content' => $pageContent,
		// 	'icon_content'=>'graph2'
		// );

		// echo $pageContent;


		$data = array(
			'controller' => $this->controller,
			'page_title' => "Test 2",
			'webcontent' => 'testview',
			'icon_page' => 'menu',
			'root' => $this->menu
		);


		$this->load->view('layout_adm/wrapper_content', $data);
	}
}
