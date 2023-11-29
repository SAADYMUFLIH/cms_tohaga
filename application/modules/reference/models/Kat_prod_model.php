<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Kat_prod_model extends CI_Model
{
    private $selected_field = "tbl_kat_prod.*";
    private $tbl_name = "tbl_kat_prod";
    private $field_search = array("name");
    private $field_id = "id_kat_prod";
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getCountRows($params = array())
    {
        $this->db->select("COUNT($this->field_id) as jml");
        $this->db->from($this->tbl_name);

        //dynamic criteria base on array field Search
        if (!empty($params['search']['keywords'])) {
            foreach ($this->field_search as $like) {
                $this->db->or_like($like, $params['search']['keywords']);
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
                $this->db->or_like($like, $params['search']['keywords']);
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
        if (!$query) {
            $error = $this->db->error();
            $msg_error = $error['message'];
            echo $msg_error;
        }
        return ($query->num_rows() > 0) ? $query->result_array() : false;
    }

    public function insertData($arr_data)
    {
        if ($this->db->insert($this->tbl_name, $arr_data)) {
            $arr_response = array(
                'error' => 'false',
                'msg' => MSG_INSERT_SUCCESS
            );
        } else {
            $arr_response = array(
                'error' => 'true',
                'msg' => $this->db->last_query()
            );
        }
        return $arr_response;
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
                if ($this->db->delete($this->tbl_name)) {
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
        $arr_col = array('No', 'Nama Kategori Produk', 'Create At', 'Update At');

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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['create_at']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['update_at']);
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

        $objPHPExcel->getActiveSheet(0)->setTitle('Data Kategori Produk');

        $filename = "Datakat_prod";
        $filename = $filename . ".xlsx";
        $objPHPExcel->setActiveSheetIndex(0);



        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function getRwByIdKel($id_kat_prod)
    {
        $this->db->select("id_rw as kode, no_rw as nama");
        $this->db->from("tbl_rw");
        $this->db->where("id_kat_prod = '$id_kat_prod'");
        $query = $this->db->get();
        $arr = [];
        foreach ($query->result() as $row) {
            $arr[] = array(
                'kd' => $row->kode,
                'nm' => $row->nama,
            );
        }
        echo json_encode($arr);
    }

    public function importexcel($path,$userid)
    {
        // $this->load->library('PHPExcel/IOFactory.php');
        $object = IOFactory::load($path); 
        // var_dump($object);  
        $affectedrows=0;	
        foreach($object->getWorksheetIterator() as $worksheet){
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            for($row=2; $row<=$highestRow; $row++){
                $kategori = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                if($this->checkifexist($kategori)==0){
                    $insert = $this->db->query("INSERT IGNORE INTO `tbl_kat_prod` (
                        `name`,
                        `create_by`,
                        `create_at`
                        )Values(
                            '$kategori',
                            '$userid',
                            NOW()
                        )
                    ");
                    $affectedrows = $affectedrows + $this->db->affected_rows();
                }
            }
        }

        return $affectedrows;
    }

    public function checkifexist($name)
    {
        $query = $this->db->query("select * from tbl_kat_prod where name = '$name' limit 1");
        return $query->num_rows();
    }
}
