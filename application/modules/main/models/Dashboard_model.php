<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . '/libraries/Cryptlib.php';
class Dashboard_model extends CI_Model
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->user_id = $this->session->userdata('user_id');
	}

	public function get_total_user()
	{
		$this->db->select("count(user_id) as total");
		$this->db->from("sys_users");
		$this->db->where('role_id = 2');
		$query = $this->db->get();
		return ($query->num_rows() > 0) ? $query->row() : false;
	}

	public function get_total_umkm()
	{
		$this->db->select("count(user_id) as total");
		$this->db->from("tbl_umkm");
		// $this->db->where('is_terminate = 0');
		// $this->db->group_by("user_id");
		$query = $this->db->get();
		return ($query->num_rows() > 0) ? $query->row() : false;
	}

	public function get_total_produk()
	{
		$this->db->select("count(id_product) as total");
		$this->db->from("tbl_product");
		// $this->db->where('is_terminate = 1 AND (role_id = 4 OR role_id = 5)');
		$query = $this->db->get();
		return ($query->num_rows() > 0) ? $query->row() : false;
	}

	public function showChart()
	{
		$tampil = $this->input->post('tampil');
		$chart = $this->input->post('chart');
		$id_provinsi = $this->input->post('id_provinsi');
		$id_kabupaten = $this->input->post('id_kabupaten');
		$id_kecamatan = $this->input->post('id_kecamatan');
		$id_kelurahan = $this->input->post('id_kelurahan');
		$id_kat_prod = $this->input->post('id_kat_prod');
		if ($tampil == "wil") {
			if ($id_kelurahan != "" && $id_kelurahan != 'null') {
				$nama = "nama_kelurahan";
			} else if ($id_kecamatan != "" && $id_kecamatan != 'null') {
				$nama = "nama_kelurahan";
			} else if ($id_kabupaten != "" && $id_kabupaten != 'null') {
				$nama = "nama_kecamatan";
			} else if ($id_provinsi != "") {
				$nama = "nama_kabupaten";
			} else if ($id_provinsi == "") {
				$nama = "nama_provinsi";
			}
			$this->db->select("count(*) as jml, " . $nama . " as nama");
			$this->db->from("tbl_umkm tu");

			if ($id_kelurahan != "" && $id_kelurahan != 'null') {
				$this->db->where("tu.id_kelurahan = '$id_kelurahan'");
			} else if ($id_kecamatan != "" && $id_kecamatan != 'null') {
				$this->db->join("ref_kelurahan as rp", "rp.id_kelurahan = tu.id_kelurahan", "inner");
				$this->db->where("tu.id_kecamatan = '$id_kecamatan'");
				$this->db->group_by("tu.id_kelurahan");
			} else if ($id_kabupaten != "" && $id_kabupaten != 'null') {
				$this->db->join("ref_kecamatan as rp", "rp.id_kecamatan = tu.id_kecamatan", "inner");
				$this->db->where("tu.id_kabupaten = '$id_kabupaten'");
				$this->db->group_by("tu.id_kecamatan");
			} else if ($id_provinsi != "") {
				$this->db->join("ref_kabupaten as rp", "rp.id_kabupaten = tu.id_kabupaten", "inner");
				$this->db->where("tu.id_provinsi = '$id_provinsi'");
				$this->db->group_by("tu.id_kabupaten");
			} else if ($id_provinsi == "") {
				$this->db->join("ref_provinsi as rp", "rp.id_provinsi = tu.id_provinsi", "inner");
				$this->db->group_by("tu.id_provinsi");
			}
			$query = $this->db->get();
		} else if ($tampil == "Produk") {

			$this->db->select("count(*) as jml, tkp.name as nama");
			$this->db->from("jml_umkm_by_katprod rp");
			$this->db->join("tbl_kat_prod as tkp", "rp.id_kat_prod = tkp.id_kat_prod", "inner");
			if ($id_kat_prod != "" && $id_kat_prod != 'null') {
				$this->db->where("rp.id_kat_prod = '$id_kat_prod'");
			}
			$this->db->group_by("rp.id_kat_prod");
			$query = $this->db->get();
		} else if ($tampil == "bb") {
		}
		// print_r($_POST);
		// 		die($this->db->last_query());

		echo json_encode($query->result());

		// $this->db->select("id_kabupaten as kode, nama_kabupaten as nama");
		// $this->db->from("ref_kabupaten");
		// $this->db->where("id_provinsi = '$idWilayah'");
		// $query = $this->db->get();
		// foreach ($query->result() as $row) {
		// 	$arr[] = array(
		// 		'kd' => $row->kode,
		// 		'nm' => $row->nama,
		// 	);
		// }
	}

	public function getkotaByIdProv($idWilayah)
	{
		$this->db->select("id_kabupaten as kode, nama_kabupaten as nama");
		$this->db->from("ref_kabupaten");
		$this->db->where("id_provinsi = '$idWilayah'");
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$arr[] = array(
				'kd' => $row->kode,
				'nm' => $row->nama,
			);
		}
		echo json_encode($arr);
	}

	public function getkecByIdKab($idWilayah)
	{
		$this->db->select("id_kecamatan as kode, nama_kecamatan as nama");
		$this->db->from("ref_kecamatan");
		$this->db->where("id_kabupaten = '$idWilayah'");
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$arr[] = array(
				'kd' => $row->kode,
				'nm' => $row->nama,
			);
		}
		echo json_encode($arr);
	}

	public function getKelByIdKec($idWilayah)
	{
		$this->db->select("id_kelurahan as kode, nama_kelurahan as nama");
		$this->db->from("ref_kelurahan");
		$this->db->where("id_kecamatan = '$idWilayah'");
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$arr[] = array(
				'kd' => $row->kode,
				'nm' => $row->nama,
			);
		}
		echo json_encode($arr);
	}
	// public function get_total_pasien(){
	// 	$this->db->select("count(id_pasien) as total, create_at");
	// 	$this->db->from("tbl_pasien");
	// 	$query = $this->db->get(); 
	// 	return ($query->num_rows() > 0) ? $query->row() : false;
	// }

	// public function get_total_pasien_baru(){
	// 	$this->db->select("count(*) as total, create_at");
	// 	$this->db->where('create_at between NOW() - INTERVAL 30 DAY AND NOW()');
	// 	$this->db->from("tbl_pasien");
	// 	$query = $this->db->get();
	// 	return ($query->num_rows() > 0) ? $query->row() : false;
	// }

	// public function get_total_pasien_lama(){
	// 	$this->db->select("count(*) as total");
	// 	$this->db->where('create_at < NOW() - INTERVAL 30 DAY AND NOW()');
	// 	$this->db->from("tbl_pasien");
	// 	$query = $this->db->get();
	// 	return ($query->num_rows() > 0) ? $query->row() : false;
	// }

	// public function get_kunjungan_pasien_baru(){
	// 	$this->db->select("count(id_ubm) as count, DATE_FORMAT(create_at, '%d/%m/%y') as create_at, status_kunjungan");
	// 	$this->db->from("tbl_ubm");
	// 	$this->db->where('status_kunjungan', 'Baru');
	// 	$this->db->where('create_at between NOW() - INTERVAL 30 DAY AND NOW()');
	// 	$this->db->group_by("DATE_FORMAT(create_at, '%d/%m/%y')");
	// 	$query = $this->db->get();
	// 	return ($query->num_rows() > 0) ? $query->result_array() : $query->result_array();
	// }

	// public function get_kunjungan_pasien_lama(){
	// 	$this->db->select("count(id_ubm) as count, DATE_FORMAT(create_at, '%d/%m/%y') as create_at, status_kunjungan");
	// 	$this->db->from("tbl_ubm");
	// 	$this->db->where('status_kunjungan', 'Lama');
	// 	$this->db->where('create_at between NOW() - INTERVAL 30 DAY AND NOW()');
	// 	$this->db->group_by("DATE_FORMAT(create_at, '%d/%m/%y')");
	// 	$query = $this->db->get();
	// 	return ($query->num_rows() > 0) ? $query->result_array() : $query->result_array();
	// }

	// public function get_total_pasien_ubm(){
	// 	$this->db->select("
	// 	sum(if(sukses = 'Ya', 1, 0) ) as sukses,
	// 	sum(if(drop_out = 'Ya', 1, 0) ) as drop_out,
	// 	sum(if(kambuh = 'Ya', 1, 0) ) as kambuh,
	// 	sum(if(rujuk = 'Ya', 1, 0) ) as rujuk
	// 	"
	// );
	// 	$this->db->from("tbl_ubm");
	// 	// $this->db->where('status_kunjungan', 'Lama');
	// 	// $this->db->where('create_at between NOW() - INTERVAL 30 DAY AND NOW()');
	// 	// $this->db->group_by("DATE_FORMAT(create_at, '%d/%m/%y')");
	// 	$query = $this->db->get();
	// 	return ($query->num_rows() > 0) ? $query->row() : false;
	// }

}
