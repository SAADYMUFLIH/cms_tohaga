<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_registration_model extends CI_Model
{
    private $selected_field = "user_id, email, pwd, full_name, role_id, sys_users.role_id, '' as list_role ";
    private $tbl_name = "sys_users";
    private $field_search = array("email", "full_name");
    private $field_id = "user_id";

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->id_superadmin = get_sys_setting("010");
    }

    public function getCountRows($params = array())
    {
        $this->db->select("COUNT($this->field_id) as jml");
        $this->db->from($this->tbl_name);
        $this->db->where("role_id NOT IN (4, 5, 7)");

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
        // echo $this->db->last_query();
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
        // $this->db->where("role_id NOT IN (4, 5, 7)");

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
        $query = $this->db->get();
        // echo $this->db->last_query();
        return ($query->num_rows() > 0) ? $query->result_array() : false;
    }

    public function insert_data($dtarray_user, $dtarray_role)
    {

        $this->db->trans_begin();
        if ($this->db->insert($this->tbl_name, $dtarray_user)) {
            $user_id = $this->db->insert_id();
            // $jml_item = count($dtarray_role);
            // for ($i = 0; $i <= $jml_item - 1; $i++) {
            //     $list_roles[] = array(
            //         'roles_id' =>  $dtarray_role[$i],
            //         'user_id' =>  $user_id
            //     );
            // }
            // if ($this->db->insert_batch('sys_user_roles', $list_roles)) {
            $list_roles = array(
                'roles_id' =>  $dtarray_role,
                'user_id' =>  $user_id
            );
            if ($this->db->insert('sys_user_roles', $list_roles)) {
                $arr_response = array(
                    'error' => 'false',
                    'msg' => MSG_INSERT_SUCCESS
                );
                $this->db->trans_commit();
            } else {
                $error = $this->db->error();
                $msg_error = $error['message'];
                $arr_response = array(
                    'error' => 'true',
                    'msg' => MSG_INSERT_FAILED .  $msg_error
                );
                $this->db->trans_rollback();
            }
        } else {
            $error = $this->db->error();
            $msg_error = $error['message'];
            $arr_response = array(
                'error' => 'true',
                'msg' => MSG_INSERT_FAILED .  $msg_error
            );
            $this->db->trans_rollback();
        }
        return $arr_response;
    }

    public function get_dataforinit($id)
    {
        $this->db->select($this->selected_field);
        $this->db->from($this->tbl_name);
        $this->db->where($this->field_id . "='$id' ");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $converter = new Encryption;
            $rows = $query->row();
            // print_r($rows);
            $array_dt = (object) array(
                'user_id' => $rows->user_id,
                'email' => !empty($rows->email) ?  $rows->email : '',
                'pwd' => !empty($rows->pwd) ? $converter->decode($rows->pwd) : '',
                'full_name' => !empty($rows->full_name) ? $rows->full_name : '',
                // 'nik' => !empty($rows->nik) ? $rows->nik : '',
                // 'alamat' => !empty($rows->alamat) ? $rows->alamat : '',
                'id_spv' => !empty($rows->id_spv) ?  $rows->id_spv : '',
                'role_id' => !empty($rows->role_id) ?  $rows->role_id : '',
                'no_hp' => !empty($rows->no_hp) ? $rows->no_hp : ''
            );
            return $array_dt;
        } else {
            return false;
        }
    }

    public function get_data_role_forinit($user_id)
    {
        $this->db->select("roles_id, user_id");
        $this->db->from("sys_user_roles");
        $this->db->where("user_id ='$user_id' ");
        $query = $this->db->get();
        $id_customer = array();

        foreach ($query->result() as $row) {
            $roles_id[] = $row->roles_id;
        }
        return $roles_id;
    }

    public function update_data($dt_user, $dt_role, $id)
    {
        $this->db->trans_begin();
        $this->db->where($this->field_id, $id);
        if ($this->db->update($this->tbl_name, $dt_user)) {
            $user_id = $id;
            // $jml_item = count($dt_role);
            // for ($i = 0; $i <= $jml_item - 1; $i++) {
            //     $list_roles[] = array(
            //         'roles_id' =>  $dt_role[$i],
            //         'user_id' =>  $user_id
            //     );
            // }
            $this->db->where($this->field_id, $id);
            if (!$this->db->delete("sys_user_roles")) {
                $error = $this->db->error();
                $msg_error = $error['message'];
                $arr_response = array(
                    'error' => 'true',
                    'msg' => MSG_UPDATE_FAILED .  $msg_error
                );
                $this->db->trans_rollback();
            }
            $list_roles = array(
                'roles_id' =>  $dt_role,
                'user_id' =>  $user_id
            );
            if ($this->db->insert('sys_user_roles', $list_roles)) {
                // if ($this->db->insert_batch('sys_user_roles', $list_roles)) {
                $arr_response = array(
                    'error' => 'false',
                    'msg' => MSG_UPDATE_SUCCESS
                );
                $this->db->trans_commit();
            } else {
                $error = $this->db->error();
                $msg_error = $error['message'];
                $arr_response = array(
                    'error' => 'true',
                    'msg' => MSG_UPDATE_FAILED .  $msg_error
                );
                $this->db->trans_rollback();
            }
        } else {
            $error = $this->db->error();
            $msg_error = $error['message'];
            $arr_response = array(
                'error' => 'true',
                'msg' => MSG_UPDATE_FAILED .  $msg_error
            );
            $this->db->trans_rollback();
        }
        return $arr_response;
    }

    public function deletedata($arr_id)
    {
        $this->db->trans_begin();
        $error = 0;
        if (is_array($arr_id)) {
            foreach ($arr_id as $id) {
                $this->db->where($this->field_id, $id);
                if ($this->db->delete($this->tbl_name)) {
                    $this->db->where($this->field_id, $id);
                    if (!$this->db->delete('sys_user_roles')) {
                        $error++;
                    }
                } else {
                    $error++;
                }
            }
            if ($error > 0) {
                $error = $this->db->error();
                $msg_error = $error['message'];
                $msg = MSG_DELETE_FAILED .  $msg_error;
                $this->db->trans_rollback();
            } else {
                $msg = count($arr_id) . MSG_DELETE_SUCCESS;
                $this->db->trans_commit();
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
        $arr_col = array("No", 'Nama User', 'Email', 'Nomor HP');

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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['full_name']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['email']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['no_hp']);
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

        // $objPHPExcel->getActiveSheet(0)->setTitle('PKWT Dikirim');

        $filename = "DataUserRegistration";
        $filename = $filename . ".xlsx";
        $objPHPExcel->setActiveSheetIndex(0);



        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
