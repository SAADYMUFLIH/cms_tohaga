<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Regulasi_model extends CI_Model
{
    private $selected_field = "regulasi.*, IF(id_kat_regulasi = 1, 'SOP Pengurusan', 'Dinas Terkait') as id_kat";
    private $tbl_name = "tbl_regulasi as regulasi";
    private $tbl_name_for_insert = "tbl_regulasi";
    private $field_search = array("title");
    private $field_id = "id_regulasi";
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->user_id = $this->session->userdata('user_id');
        $this->roles_id = $this->session->userdata('roles_id');
    }

    public function getCountRows($params = array())
    {
        $this->db->select("COUNT($this->field_id) as jml");
        $this->db->from($this->tbl_name);

        //dynamic criteria base on array field Search
        if (!empty($params['search']['keywords'])) {
            foreach ($this->field_search as $like) {
                $this->db->like($like, $params['search']['keywords']);
            }
        }
        //set start and limit
        if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
            $this->db->limit($params['limit'], $params['start']);
        } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
            $this->db->limit($params['limit']);
        }
        $query = $this->db->get()->row();
        if (!$query) {
            $error = $this->db->error();
            $msg_error = $error['message'];
            echo $msg_error;
        }
        return $query->jml;
    }

    public function getRows($params = array())
    {
        $this->db->select($this->selected_field);
        $this->db->from($this->tbl_name);

        //dynamic criteria base on array field Search
        if (!empty($params['search']['keywords'])) {
            foreach ($this->field_search as $like) {
                $this->db->like($like, $params['search']['keywords']);
            }
        }
        $this->db->order_by($this->field_id, 'desc');
        //set start and limit
        if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
            $this->db->limit($params['limit'], $params['start']);
        } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
            $this->db->limit($params['limit']);
        }
        $query = $this->db->get();
        // die($this->db->last_query());
        if (!$query) {
            $error = $this->db->error();
            $msg_error = $error['message'];
            echo $msg_error;
        }
        return ($query->num_rows() > 0) ? $query->result_array() : false;
    }

    public function insertData($arr_data)
    {
        if ($this->db->insert($this->tbl_name_for_insert, $arr_data)) {
            $arr_response = array(
                'error' => 'false',
                'msg' => MSG_INSERT_SUCCESS,
                'id' => $this->db->insert_id()
            );
        } else {
            $arr_response = array(
                'error' => 'true',
                'msg' => $this->db->last_query()
            );
        }
        return $arr_response;
    }

    public function update_doc($doc_info,  $id_key)
    {
        $this->db->where($this->field_id, $id_key);
        if ($this->db->update($this->tbl_name, $doc_info)) {
            $arr_response = array(
                'error' => 'false',
                'msg' => MSG_UPDATE_SUCCESS
            );
        } else {
            $arr_response = array(
                'error' => 'true',
                'msg' => MSG_UPDATE_FAILED
            );
        }
        return $arr_response;
    }

    public function remove_img($arr_dt, $id_key, $field)
    {
        $this->db->where($this->field_id, $id_key);
        $existing_image =  get_info_by_id($this->tbl_name, $field, $this->field_id, $id_key);
        if ($this->db->update($this->tbl_name, $arr_dt)) {
            if (file_exists('assets/images/file_regulasi/' . $existing_image)) {
                unlink('assets/images/file_regulasi/' . $existing_image);
                $msg = 'Penghapusan file berhasil';
            } else {
                $msg = 'Penghapusan file gagal ';
            }
        } else {
            $error = $this->db->error();
            $msg_error = $error['message'];
            $msg = 'Info file gagal diupdate ke database ' .  $msg_error;
        }

        return $msg;
    }

    public function getDataForInit($id)
    {
        $this->db->select($this->selected_field);
        $this->db->from($this->tbl_name);
        $this->db->where($this->field_id . "='$id' ");
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    public function updateData($arr_dat, $id)
    {
        $this->db->where($this->field_id . "='$id' ");
        if ($this->db->update($this->tbl_name, $arr_dat)) {
            $arr_response = array(
                'error' => 'false',
                'msg' => MSG_UPDATE_SUCCESS
            );
        } else {
            $error = $this->db->error();
            $msg_error = $error['message'];
            $arr_response = array(
                'error' => 'true',
                'msg' => MSG_UPDATE_FAILED .  $msg_error
            );
        }
        return $arr_response;
    }

    public function deleteData($arr_id)
    {
        if (is_array($arr_id)) {
            foreach ($arr_id as $id) {
                $this->db->where($this->field_id, $id);
                if ($this->db->delete($this->tbl_name_for_insert)) {
                    $msg = count($arr_id) .  MSG_DELETE_SUCCESS;
                } else {
                    $error = $this->db->error();
                    $msg_error = $error['message'];
                    $msg = MSG_DELETE_FAILED . $msg_error;
                }
            }
        } else {
            $msg = MSG_NO_DATA_DELETED;
        }
        return $msg;
    }


    public function exportToExcel($xls_data)
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
        $arr_col = array("No", 'NIK', 'Nama Regulasi', 'No. BPJS', 'Tempat Lahir', 'Tgl Lahir', 'Jenis Kelamin', 'Kabupaten', 'Kecamatan', 'Kelurahan', 'Alamat', 'No. HP', 'Pendidikan', 'Pekerjaan', 'Status Kawin', 'Gol. Darah', 'Create At', 'Update At');

        //Set Column title
        $column = 0;
        foreach ($arr_col as $field) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        //Set Row Data
        $excel_row = 2;
        $row_number = 1;
        foreach ($xls_data as $row) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row_number);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['nik']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['nama_lengkap']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['no_bpjs']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['tempat_lahir']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['tgl_lahir']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row['jenis_kelamin']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row['nama_wilayah']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row['nama_kecamatan']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row['nama_kelurahan']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row['alamat']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row['no_hp']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row['nama_pendidikan']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row['pekerjaan']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row['status_kawin']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row['nama_gol_darah']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $row['create_at']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row['update_at']);
            $excel_row++;
            $row_number++;
        }

        //Set autsize column
        $nCols = count($arr_col); //set the number of columns
        foreach (range(0, $nCols) as $col) {
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle(
            'A1:' .
                $objPHPExcel->getActiveSheet()->getHighestDataColumn() .
                $objPHPExcel->getActiveSheet()->getHighestDataRow()
        )->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet(0)->setTitle('PKWT Dikirim');

        $filename = "Dataregulasi";
        $filename = $filename . ".xlsx";
        $objPHPExcel->setActiveSheetIndex(0);



        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
