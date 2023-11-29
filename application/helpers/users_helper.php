<?php

function get_list_role($user_id)
{
    $ci = &get_instance();
    $ci->db->select("ur.roles_id, r.roles_name, ur.user_id");
    $ci->db->from("sys_user_roles AS ur");
    $ci->db->join("sys_roles AS r", "ur.roles_id = r.roles_id", "INNER");
    $ci->db->where("ur.user_id='$user_id'");
    $query = $ci->db->get();
    return ($query->num_rows() > 0) ? $query->result_array() : false;
}

function get_list_role_new($user_id)
{
    $ci = &get_instance();
    $ci->db->select("ur.role_id, r.roles_name, ur.user_id");
    $ci->db->from("sys_users AS ur");
    $ci->db->join("sys_roles AS r", "ur.role_id = r.roles_id");
    $ci->db->where("ur.user_id='$user_id'");
    $query = $ci->db->get();
    // echo $ci->db->last_query(); die();
    return ($query->num_rows() > 0) ? $query->result_array() : false;
}

function populate_list_role($user_id)
{

    $data_roles =  get_list_role_new($user_id);
    $list_data = '';
    $list_data = '<div class="mt-1">';
    if (is_array($data_roles)) {
        foreach ($data_roles as $dt) {
            $list_data .= '<span class="badge badge-success" style="font-size:11px" >' . $dt['roles_name'] . '</span>&nbsp';
        }
    }
    $list_data .= '</div>';
    return $list_data;
}



function populate_list_menu($roles_id)
{
    $data_roles =  get_list_parent_menu($roles_id);
    $list_data = '';
    
    if (is_array($data_roles)) {
        foreach ($data_roles as $dt) {
            // $list_data .= '<div class="btn-group">';
            $list_data .= '<div class="dropdown d-inline-block">';
            $list_chidren = get_list_children_menu($roles_id, $dt['id_menu']);

            if(!empty( $list_chidren )){
                $list_data .='
                    <button class="btn btn-success dropdown-toggle btn-xs mt-1" type="button" id="dropdownMenuButton'.$dt['id_menu'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $dt['menu_label'] . '</button>&nbsp
                    <div class="dropdown-menu" style="font-size: 9px; padding-top:1px; padding-bottom:1px " aria-labelledby="dropdownMenuButton'.$dt['id_menu'].'">';
                // $list_data .='<button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="mb-2 mr-2 dropdown-toggle btn btn-primary">' . $dt['menu_label'] . '</button>';
                // $list_data .='<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">';
                $list_data .= get_list_children_menu($roles_id, $dt['id_menu']);
                // $list_data .='</div>';
            }else{
                $list_data .='<button class="btn btn-info btn-xs mt-1" type="button" aria-haspopup="true" aria-expanded="false">' . $dt['menu_label'] . '</button>&nbsp';
            }
            $list_data .='</div>';
            $list_data .= '</div>';
        }
    }
    return $list_data;
}

function get_list_parent_menu($roles_id)
{
    $ci = &get_instance();
    $ci->db->select("sr.roles_id, mr.id_menu, am.menu_label, am.parent_id");
    $ci->db->from("sys_roles AS sr");
    $ci->db->join("sys_menu_role AS mr", "sr.roles_id = mr.roles_id", "INNER");
    $ci->db->join("sys_admin_menu AS am", "mr.id_menu = am.id_menu", "INNER");
    $ci->db->where("sr.roles_id='$roles_id' AND am.parent_id =0");
    $query = $ci->db->get();
    return ($query->num_rows() > 0) ? $query->result_array() : false;
}

function get_list_children_menu($roles_id, $parent_id)
{
    $ci = &get_instance();
    $ci->db->select("sr.roles_id, mr.id_menu, am.menu_label");
    $ci->db->from("sys_roles AS sr");
    $ci->db->join("sys_menu_role AS mr", "sr.roles_id = mr.roles_id", "INNER");
    $ci->db->join("sys_admin_menu AS am", "mr.id_menu = am.id_menu", "INNER");
    $ci->db->where("sr.roles_id='$roles_id' AND am.parent_id ='$parent_id'");
    $query = $ci->db->get();
    $result = $query->result_array();
    $list_children = '';
    if (count($result) > 0) {
        foreach ($result as $dt) {
            $list_children .= '<a class="dropdown-item text-sm" >'. $dt['menu_label'] .'</a>';
            // $list_children .= '<button type="button" tabindex="0" class="dropdown-item">'. $dt['menu_label'] .'</button>';
        }
    }else{
        $list_children = '';
    }

    return $list_children;
}

function get_user_privilege($idmenu = ''){
    $ci = &get_instance();
   // $idmenu = get_menu_id($ci->input->get('sessid'));

    $idmenu = get_menu_id($ci->session->userdata('sessid'));
    $roles_id = $ci->session->userdata('roles_id');
    $sql = "SELECT roles_id, action_privilege, id_access_type from sys_menu_access_type ";
    $sql .=" WHERE roles_id in($roles_id)";
    if(!empty($idmenu)){
        $sql .=" AND id_menu = '$idmenu'";
    } 
    $query = $ci->db->query($sql);  //echo $ci->db->last_query() . "<BR>";
    return ($query->num_rows() > 0) ? $query->row() : false;
    
}
