<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_roles_model extends CI_Model
{
    private $selected_field = "roles_id, roles_name, roles_desc, '' as list_menu ";
    private $tbl_name = "sys_roles";
    private $field_search = array("roles_name");
    private $field_id = "roles_id";

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
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

        //set start and limit
        if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
            $this->db->limit($params['limit'], $params['start']);
        } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
            $this->db->limit($params['limit']);
        }
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result_array() : false;
    }

    public function generate_tree_menu_array()
    {
        $this->db->select("id_menu, menu_label");
        $this->db->from("sys_admin_menu");
        $this->db->where("parent_id=0");
        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            foreach ($result as $row) {
                $data_children = $this->get_children($row['id_menu']);
                $arr_data[] = array(
                    'idmenu' => $row['id_menu'],
                    'text' => $row['menu_label'],
                    'children' => $data_children,
                    'checked' => ''
                );
            }
        }

        return  $arr_data;
    }

    private function get_children($parent_id)
    {
        $this->db->select("id_menu, menu_label");
        $this->db->from("sys_admin_menu");
        $this->db->where("parent_id='$parent_id'");
        $query = $this->db->get();
        $result = $query->result_array();
        $arr_data = array();
        if ($query->num_rows() > 0) {
            foreach ($result as $row) {
                $arr_data[] = array(
                    'idmenu' => $parent_id . ',' . $row['id_menu'],
                    'text' => $row['menu_label'],
                    'checked' => ''

                );
            }
        }
        return $arr_data;
    }

    public function insert_data($dtarray_role, $dtarray_menu)
    {
        $this->db->trans_begin();
        if ($this->db->insert($this->tbl_name, $dtarray_role)) {
            $jml_menu = count($dtarray_menu);
            $roles_id = $this->db->insert_id();
            for ($i = 0; $i <= $jml_menu - 1; $i++) {
                $list_menu_roles[] = array(
                    'id_menu' =>  $dtarray_menu[$i],
                    'roles_id' =>  $roles_id
                );
            }
            if ($jml_menu > 0) {
                if ($this->db->insert_batch('sys_menu_role', $list_menu_roles)) {
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
                $arr_response = array(
                    'error' => 'false',
                    'msg' => MSG_INSERT_SUCCESS
                );
                $this->db->trans_commit();
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

    public function get_dataforinit_user_roles($id)
    {
        $this->db->select($this->selected_field);
        $this->db->from($this->tbl_name);
        $this->db->where($this->field_id . "='$id' ");
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    public function generate_tree_menu_array_init($roles_id)
    {
        $this->db->select("a.id_menu, a.menu_label, mr_rid, b.id_menu as id_menu_sr");
        $this->db->from("sys_admin_menu a");
        $this->db->join("(SELECT
        sys_menu_role.roles_id mr_rid,
        sys_admin_menu.id_menu,
        sys_admin_menu.menu_label,
        sys_menu_role.roles_id
        FROM
        sys_admin_menu
        LEFT JOIN sys_menu_role ON sys_admin_menu.id_menu = sys_menu_role.id_menu
        WHERE roles_id = '$roles_id') as b", "a.id_menu = b.id_menu ", "LEFT");
        $this->db->where("a.parent_id=0");
        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            foreach ($result as $row) {
                $data_children = $this->get_children_init($roles_id, $row['id_menu']);
                $checked = ($row['id_menu'] == $row['id_menu_sr']) ? true : false;
                $arr_data[] = array(
                    'idmenu_edit' => $row['id_menu'],
                    'text' => $row['menu_label'],
                    'children' => $data_children,
                    'checked' => $checked
                );
            }
        }

        return  $arr_data;
    }

    private function get_children_init($roles_id, $parent_id)
    {
        $this->db->select("a.id_menu, a.menu_label, mr_rid, b.id_menu as id_menu_sr");
        $this->db->from("sys_admin_menu a");
        $this->db->join("(SELECT
        sys_menu_role.roles_id mr_rid,
        sys_admin_menu.id_menu,
        sys_admin_menu.menu_label,
        sys_menu_role.roles_id
        FROM
        sys_admin_menu
        LEFT JOIN sys_menu_role ON sys_admin_menu.id_menu = sys_menu_role.id_menu
        WHERE roles_id = '$roles_id') as b", "a.id_menu = b.id_menu ", "LEFT");
        $this->db->where("parent_id='$parent_id'");
        $query = $this->db->get();
        $result = $query->result_array();
        $arr_data = array();
        if ($query->num_rows() > 0) {
            foreach ($result as $row) {
                $checked = ($row['id_menu'] == $row['id_menu_sr']) ? true : false;
                $arr_data[] = array(
                    'idmenu_edit' => $parent_id . ',' . $row['id_menu'],
                    'text' => $row['menu_label'],
                    'checked' =>  $checked

                );
            }
        }
        return $arr_data;
    }

    public function update_data($dtarray_role, $dtarray_menu, $id)
    {
        $this->db->trans_begin();
        $this->db->where($this->field_id, $id);
        if ($this->db->update($this->tbl_name, $dtarray_role)) {
            $jml_menu = count($dtarray_menu);
            foreach ($dtarray_menu as $dt) {
                $list_menu_roles[] = array(
                    'roles_id' =>  $id,
                    'id_menu' =>   $dt
                );
            }

            $this->db->where($this->field_id, $id);
            if (!$this->db->delete("sys_menu_role")) {
                $error = $this->db->error();
                $msg_error = $error['message'];
                $arr_response = array(
                    'error' => 'true',
                    'msg' => MSG_UPDATE_FAILED .  $msg_error
                );
                $this->db->trans_rollback();
            }
            if ($jml_menu > 0) {
                if ($this->db->insert_batch('sys_menu_role', $list_menu_roles)) {
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
                        'msg' => MSG_UPDATE_FAILED .  $msg_error  . $this->db->last_query()
                    );
                    $this->db->trans_rollback();
                }
            } else {
                $arr_response = array(
                    'error' => 'false',
                    'msg' => MSG_UPDATE_SUCCESS
                );
                $this->db->trans_commit();
            }
        } else {
            $error = $this->db->error();
            $msg_error = $error['message'];
            $arr_response = array(
                'error' => 'true',
                'msg' => MSG_UPDATE_FAILED .  $msg_error . $this->db->last_query()
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
                    if (!$this->db->delete('sys_menu_role')) {
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
        $arr_col = array("No", 'User Group', 'Keterangan');

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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['roles_name']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['roles_desc']);
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

        $filename = "DataUserGroup";
        $filename = $filename . ".xlsx";
        $objPHPExcel->setActiveSheetIndex(0);



        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
