<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_profile_model extends CI_Model
{
    private $selected_field = "user_id, email, pwd, full_name, profile_pic";//, nip, signature_pic, sys_users.roles_id, sys_users.id_area, sys_users.id_provinsi, sys_users.id_kabupaten, sys_users.no_hp, a.nama_area, sys_users.profile_pic, sys_users.signature_pic, p.nama_provinsi, k.nama_kabupaten, id_perusahaan, '' as list_role ";
    private $tbl_name = "sys_users";
    private $field_id = "user_id";

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getRows($user_id)
    {
        $this->db->select($this->selected_field);
        $this->db->from($this->tbl_name);
        // $this->db->join('ref_area AS a', "sys_users.id_area = a.id_area", "LEFT");
        // $this->db->join('ref_provinsi AS p', "sys_users.id_provinsi = p.id_provinsi", "LEFT");
        // $this->db->join('ref_kabupaten AS k', "sys_users.id_kabupaten = k.id_kabupaten", "LEFT");
        $this->db->where("user_id ='$user_id' ");
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    public function get_ero_list_customer($user_id)
    {
        $this->db->select("pe.id_ero, c.nama_perusahaan, c.alamat_perusahaan");
        $this->db->from("sys_users AS u");
        $this->db->join('tbl_penugasan_ero AS pe', "u.user_id = pe.id_ero", "INNER");
        $this->db->join('tbl_customer AS c', "pe.id_customer = c.id_customer", "INNER");
        $this->db->where("user_id ='$user_id' ");
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result_array() : false;
    }

    public function update_profile_pic($dt_profile_pic, $id)
    {
        $this->db->where($this->field_id, $id);
        $pic_profile = $dt_profile_pic['profile_pic'];
        $mtime = DateTime::createFromFormat('U.u', microtime(true));
        $mtime = $mtime->format("mdYHisu");
        if ($this->db->update($this->tbl_name, $dt_profile_pic)) {
            $container = '<img id="display_pic" class="profile-user-img img-fluid img-circle" style="height: 180px; width:200px" 
            src="' . base_url() . 'assets/images/profile/' . $pic_profile . '?' . $mtime . '" >';
            $msg = $container;
        } else {
            $error = $this->db->error();
            $msg_error = $error['message'];
            $arr_response = array(
                'error' => 'true',
                'msg' => MSG_UPDATE_FAILED .  $msg_error
            );

            $msg = MSG_UPDATE_FAILED . $msg_error;
        }

        return $msg;
    }

    public function update_user_info($arr_user_info, $id)
    {
        $this->db->trans_begin();
        $this->db->where($this->field_id, $id);
        // $prev_signature = get_info_by_id("sys_users","signature_pic","user_id", $id);
        // $prev_signature_file = './assets/images/profile/' . $prev_signature;
        if ($this->db->update($this->tbl_name, $arr_user_info)) {
            $arr_response = array(
                'error' => 'false',
                'msg' => MSG_UPDATE_SUCCESS
            );
            $this->db->trans_commit();
        //Location to where you want to created sign image
            // $saved_file_name = './assets/images/profile/' . $file_name;
            // $putfile = file_put_contents($saved_file_name, $img_signature);
            // if (!$putfile) {
            //     $arr_response = array(
            //         'error' => 'true',
            //         'msg' => MSG_UPDATE_FAILED .  $putfile
            //     );
            //     $this->db->trans_rollback();
            // } else {
            //     $arr_response = array(
            //         'error' => 'false',
            //         'msg' => MSG_UPDATE_SUCCESS,
            //         'saved_pic'=>'<img src="'.$saved_file_name.'" style="height:70px;">' 
            //     );
            //     if(file_exists($prev_signature_file)){
            //         @unlink($prev_signature_file);
            //     }
            //     $this->db->trans_commit();
            // }
        } else {
            $arr_response = array(
                'error' => 'true',
                'msg' => MSG_UPDATE_FAILED
            );
            $this->db->trans_rollback();
        }
        return $arr_response;
    }

    public function get_dataforinit($id)
    {
        $this->db->select($this->selected_field);
        $this->db->from($this->tbl_name);
        $this->db->join('ref_area AS a', "sys_users.id_area = a.id_area", "LEFT");
        $this->db->join('ref_provinsi AS p', "sys_users.id_provinsi = p.id_provinsi", "LEFT");
        $this->db->join('ref_kabupaten AS k', "sys_users.id_kabupaten = k.id_kabupaten", "LEFT");
        $this->db->where($this->field_id . "='$id' ");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $converter = new Encryption;
            $rows = $query->row();
            $array_dt = (object) array(
                'user_id' => $rows->user_id,
                'email' => !empty($rows->email) ?  $rows->email : '',
                'pwd' => !empty($rows->pwd) ? $converter->decode($rows->pwd) : '',
                'full_name' => !empty($rows->full_name) ? $rows->full_name : '',
                'nip' => !empty($rows->nip) ? $rows->nip : '',
                'roles_id' => !empty($rows->roles_id) ?  $rows->roles_id : '',
                'id_area' => !empty($rows->id_area) ? $rows->id_area : '',
                'id_provinsi' => !empty($rows->id_provinsi) ? $rows->id_provinsi : '',
                'id_kabupaten' => !empty($rows->id_kabupaten) ? $rows->id_kabupaten : '',
                'id_perusahaan' => !empty($rows->id_perusahaan) ? $rows->id_perusahaan : '',
                'no_hp' => !empty($rows->no_hp) ? $rows->no_hp : '',
                'nama_area' => !empty($rows->nama_area) ? $rows->nama_area : '',
                'nama_provinsi' => !empty($rows->nama_provinsi) ? $rows->nama_provinsi : '',
                'nama_kabupaten' => !empty($rows->nama_kabupaten) ? $rows->nama_kabupaten : ''
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
            $jml_item = count($dt_role);
            $user_id = $id;
            for ($i = 0; $i <= $jml_item - 1; $i++) {
                $list_roles[] = array(
                    'roles_id' =>  $dt_role[$i],
                    'user_id' =>  $user_id
                );
            }
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
            if ($this->db->insert_batch('sys_user_roles', $list_roles)) {
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
}
